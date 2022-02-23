<?php 
namespace Myv\Post\Model;

use Myv\Post\Api\PostRepositoryInterface;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Myv\Post\Model\PostFactory as ModelFactory;
use Myv\Post\Model\ResourceModel\PostFactory as ResourceModelFactory;
use Myv\Post\Model\ResourceModel\Post\CollectionFactory;
use Myv\Post\Model\PostSearchResultFactory;

class PostRepository implements PostRepositoryInterface {
    private $postModelFactory;
    private $collectionFactory;
    private $collectionProcessorFactory;
    private $postSearchResultFactory;
    private $postResourceModelFactory;

    public function __construct(
        ModelFactory $postModelFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        PostSearchResultFactory $postSearchResultFactory,
        ResourceModelFactory $postResourceModelFactory
    ) {
        $this->collectionProcessor = $collectionProcessor;
        $this->collectionFactory = $collectionFactory;
        $this->postModelFactory = $postModelFactory;
        $this->postSearchResultFactory = $postSearchResultFactory;
        $this->postResourceModelFactory = $postResourceModelFactory;
    }

    public function getById($id) {
        $postModel = $this->postModelFactory->create(); 
        $post = $postModel->load($id);

        if (!$post->getId()) {
            throw new NoSuchEntityException(__("Post with ID %1 not found", $id));
        }

        return $post;
    }

    public function save($post) {
        $postResourceModel = $this->postResourceModelFactory->create();
        return $postResourceModel->save($post);
    }

    public function delete($post) {
        $postResourceModel = $this->postResourceModelFactory->create();
        $postResourceModel->delete($post);
    }

    public function getList(SearchCriteriaInterface $searchCriteria) {
       $collection = $this->collectionFactory->create();
       $this->collectionProcessor->process($searchCriteria, ($collection));

       $searchResults = $this->postSearchResultFactory->create();
 
       $searchResults->setSearchCriteria($searchCriteria);
       $searchResults->setItems($collection->getItems());
 
       return $searchResults;
    } 
}