<?php
namespace Myv\StoreLocator\Model;
 
use Myv\StoreLocator\Model\ResourceModel\StoreLocator\CollectionFactory;
use Myv\StoreLocator\Model\StoreLocatorRepository;
use Magento\Backend\Model\UrlInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;

class DataProvider extends AbstractDataProvider {
    private $request;
    private $storeManager;
    private $fileHelper;
    private $mediaDirectory;
    private $storeLocatorRepository;

    protected $collection;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        StoreLocatorRepository $storeLocatorRepository,
        StoreManagerInterface $storeManager,
        Filesystem $filesystem,
        array $meta = [],
        array $data = []
    ) {
        $this->request = $request;
        $this->collection = $collectionFactory->create();
        $this->storeManager = $storeManager;
        $this->mediaDirectory = $filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $this->storeLocatorRepository = $storeLocatorRepository;

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData() {
        try {
            $id = $this->getStoreId();
            $storeData = $this->storeLocatorRepository->getById($id)->getData();
            
            $storeData["image"] = $this->getImageInfo($storeData["image"]);
            $resultData = [$id => $storeData];

            return $resultData;
        } catch (NoSuchEntityException $error) {
            return [];
        }    
    }

    private function getStoreId() {
        return (int) $this->request->getParam('id');
    }

    private function getImageInfo($imageName) {
        $url = $imageName;

        $name = $imageName;

        $imageSize = 0;

        if(strpos($url, "http") != 0 || strpos($url, "http") === false) {
            $url = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $imageName;

            $nameParts = explode("/", $imageName);
            $name = array_pop($nameParts);

            $imageSize = $this->mediaDirectory->stat($imageName)['size'];
        }
        
        return [
            0 => [
            "url" => $url,
            "name" => $name,
            "size" => $imageSize,
            ]
        ];
    }
}