<?php
namespace Myv\Post\Model;
 
use Myv\Post\Model\ResourceModel\Post\CollectionFactory;
use Myv\Post\Model\PostRepository;
use Magento\Backend\Model\UrlInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Downloadable\Helper\File;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;

class DataProvider extends AbstractDataProvider {
    private $request;
    private $storeManager;
    private $fileHelper;
    private $mediaDirectory;

    protected $collection;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $postCollectionFactory,
        RequestInterface $request,
        PostRepository $postRespotitory,
        StoreManagerInterface $storeManager,
        Filesystem $filesystem,
        array $meta = [],
        array $data = []
    ) {
        $this->request = $request;
        $this->collection = $postCollectionFactory->create();
        $this->postRespotitory = $postRespotitory;
        $this->storeManager = $storeManager;
        $this->mediaDirectory = $filesystem->getDirectoryRead(DirectoryList::MEDIA);

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
 
    public function getData() {
        try {
            $id = $this->getPostId();
            $postData = $this->postRespotitory->getById($id)->getData();
            
            $postData["image"] = $this->getImageInfo($postData["image"]);
            $resultData = [$id => $postData];

            return $resultData;
        } catch (NoSuchEntityException $error) {
            return [];
        }    
    }

    private function getPostId() {
        return (int) $this->request->getParam('id');
    }

    private function getImageInfo($imageName) {
        $url = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $imageName;
        
        $imageSize = $this->mediaDirectory->stat($imageName)['size'];
        
        $nameParts = explode("/", $imageName);
        $name = array_pop($nameParts);
        
        return [
            0 => [
            "url" => $url,
            "name" => $name,
            "size" => $imageSize,
            ]
        ];
    }
}