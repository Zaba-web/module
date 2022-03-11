<?php
namespace Myv\StoreLocator\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Myv\StoreLocator\Api\StoreLocatorRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

class StoreLocatorRepository implements StoreLocatorRepositoryInterface {
    private $storeLocatorModelFactory;
    private $collectionFactory;
    private $storeLocatorResourceModelFactory;
    private $storeLocatorSearchResultFactory;
    private $collectionProcessor;

    public function __construct(
        \Myv\StoreLocator\Model\StoreLocatorFactory $slModelFactory,
        \Myv\StoreLocator\Model\ResourceModel\StoreLocator\CollectionFactory $slCollectionFactory,
        \Myv\StoreLocator\Model\StoreLocatorSearchResultFactory $slSearchResultFactory,
        \Myv\StoreLocator\Model\ResourceModel\StoreLocatorFactory $slResourceModelFactory,
        \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
    ) {
        $this->storeLocatorModelFactory = $slModelFactory;
        $this->collectionFactory = $slCollectionFactory;
        $this->storeLocatorResourceModelFactory = $slResourceModelFactory;
        $this->storeLocatorSearchResultFactory = $slSearchResultFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    public function getById($id) {
        $storeLocatorModel = $this->storeLocatorModelFactory->create(); 
        $store = $storeLocatorModel->load($id);

        if (!$store->getId()) {
            throw new NoSuchEntityException(__("Store with ID %1 not found", $id));
        }

        return $store;
    }

    public function save($store) {
        $storeLocatorResourceModelFactory = $this->storeLocatorResourceModelFactory->create();
        
        if($storeLocatorResourceModelFactory->save($store)) {
            return true;
        };

        return false;
    }

    public function delete($store) {
        $storeLocatorResourceModelFactory = $this->storeLocatorResourceModelFactory->create();

        if($storeLocatorResourceModelFactory->delete($store)) {
            return true;
        };

        return false;
    }

    public function deleteById(int $id) {
        $storeLocatorResourceModelFactory = $this->storeLocatorResourceModelFactory->create();

        if($storeLocatorResourceModelFactory->delete($this->getById($id))) {
            return true;
        };

        return false;
    }

    public function getList(SearchCriteriaInterface $searchCriteria) {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, ($collection));
 
        $searchResults = $this->storeLocatorSearchResultFactory->create();
  
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
  
        return $searchResults;
     } 
}