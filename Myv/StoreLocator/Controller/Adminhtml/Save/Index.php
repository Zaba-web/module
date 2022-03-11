<?php
namespace Myv\StoreLocator\Controller\Adminhtml\Save;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Myv\StoreLocator\Model\StoreLocatorRepository;
use Myv\StoreLocator\Model\StoreLocatorFactory;

class Index extends Action {
    private $storeLocatorRepository;
    private $storeFactory;
    private $uploaderFactory;
    private $mediaDirectory;

    public function __construct(
        Context $context, 
        StoreLocatorRepository $storeLocatorRepository,
        StoreLocatorFactory $storeFactory,
        UploaderFactory $uploaderFactory,
        Filesystem $filesystem
    ) {
        $this->storeLocatorRepository = $storeLocatorRepository;
        $this->storeFactory = $storeFactory;

        $this->uploaderFactory = $uploaderFactory;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        
		return parent::__construct($context);
	}

    public function execute() {
        if(!$this->getRequest()->isPost()) {
            $this->messageManager->addError(__("You should fill the form."));
            return $this->redirectToList();
        }

        $input = $this->getRequest()->getPostValue();

        $store = $this->createStore($input);

        $this->storeLocatorRepository->save($store);
        $this->messageManager->addSuccess(__("Store saved."));
        
        return $this->redirectToList();
    }

    private function createStore($input) {
        $store = $this->storeFactory->create();
        
        $store->setName($input['name']);
        $store->setDescription($input['description']);
        $store->setImage($this->handleImage($input));
        $store->setLatitude($input['latitude']);
        $store->setLongitude($input['longitude']);
        $store->setOpenTime($input['open_time']);
        $store->setCloseTime($input['close_time']);

        if($input['id'] != '') {
            $store->setId($input['id']);
        }

        return $store;
    }

    private function handleImage($input) {
        $imageId = 'image';

        if(!isset($input['image'])) {
            return '';
        }

        if($this->isImageURLOnly($input['image'])) {
            return $input['image'];
        }

        $imageId = $input['image'][0];

        if($this->isItAbsoluteURL($imageId['url'])) {
            return $imageId['url'];
        }

        if(!array_key_exists("tmp_name", $imageId)) {
            return $this->useExistingImage($imageId);
        }

        if (!file_exists($imageId['tmp_name'])) {
            $imageId['tmp_name'] = $imageId['path'] . '/' . $imageId['file'];
        }

        return $this->loadNewImage($imageId);
    }

    private function useExistingImage($imageId) {
        return "imageUploader/images/".$imageId['name'];
    }

    private function isItAbsoluteURL($url) {
        if(strpos($url, "http") == 0) {
            return true;
        }

        return false;
    }

    private function isImageURLOnly($image) {
        if(gettype($image) == "string") {
            return true;
        }

        return false;
    }

    private function loadNewImage($imageId) {
        try { 
            $fileUploader = $this->uploaderFactory->create(['fileId' => $imageId]);
            $fileUploader->setAllowedExtensions(['jpg', 'jpeg', 'png']);
            $fileUploader->setAllowRenameFiles(true);
            $fileUploader->setAllowCreateFolders(true);

            $info = $fileUploader->save($this->mediaDirectory->getAbsolutePath('imageUploader/images'));

            return $this->mediaDirectory->getRelativePath('imageUploader/images') . '/' . $info['file'];
        } catch (Exception $error) {
            $this->messageManager->addError(__("Error occured while image uploading."));
            return $this->redirectToList();
        }
    }

    private function redirectToList() {
        return $this->_redirect('storelocator/overview/index');
    }
}