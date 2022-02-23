<?php
namespace Myv\Post\Controller\Adminhtml\Save;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Myv\Post\Model\PostRepository;
use Myv\Post\Model\PostFactory;

class Index extends Action {
	private $pageFactory;
    private $postRepository;
    private $postFactory;

    protected $uploaderFactory;
    protected $mediaDirectory;

	public function __construct(
        Context $context, 
        PageFactory $pageFactory,
        PostRepository $postRepository,
        PostFactory $postFactory,
        UploaderFactory $uploaderFactory,
        Filesystem $filesystem
    ) {
		$this->pageFactory = $pageFactory;
        $this->postRepository = $postRepository;
        $this->postFactory = $postFactory;

        $this->uploaderFactory = $uploaderFactory;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);

		return parent::__construct($context);
	}

	public function execute() {
        if(!$this->getRequest()->isPost()) {
            $this->messageManager->addError(__("You should fill the form."));
            $this->_redirect('posts/overview/index');
        }

        $input = $this->getRequest()->getPostValue();
        
        if($input['title'] == '') {
            $this->messageManager->addError(__("You should provide at least Title."));
            $this->_redirect('posts/create/index');
        }

        $post = $this->createPost($input);

        $this->postRepository->save($post);

        $this->messageManager->addSuccess(__("Post saved."));
        $this->_redirect('posts/overview/index');
    }

    private function createPost($input) {
        $post = $this->postFactory->create();

        $post->setTitle($input['title']);
        $post->setAuthor($input['author']);
        $post->setContent($input['content']);
        $post->setDate($input['publication_date']);

        if($input['id'] != '') {
            $post->setId($input['id']);
        }

        if($input['publication_date'] == "") {
            $post->setDate(date("Y-m-d"));
        }

        $post->setImage($this->handleImage($input));

        return $post;
    }

    private function handleImage($input) {
        $imageId = 'image';

        if (isset($input['image']) && count($input['image'])) {
            $imageId = $input['image'][0];

            if(!array_key_exists("tmp_name", $imageId)) {
                return $this->useExistingImage($imageId);
            }

            if (!file_exists($imageId['tmp_name'])) {
                $imageId['tmp_name'] = $imageId['path'] . '/' . $imageId['file'];
            }

            return $this->loadNewImage($imageId);
        }
    }

    private function useExistingImage($imageId) {
        return "imageUploader/images/".$imageId['name'];
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
            $this->_redirect('posts/create/index');
        }
    }
}