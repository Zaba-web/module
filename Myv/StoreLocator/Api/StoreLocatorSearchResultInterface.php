<?php
namespace Myv\StoreLocator\Api;

use Magento\Framework\Api\SearchResultsInterface;

interface StoreLocatorSearchResultInterface extends SearchResultsInterface {

    /**
     * @return \Myv\StoreLocator\Api\Data\StoreLocatorInterface[]
     */
    public function getItems();

    
    /**
     * @return \Myv\StoreLocator\Api\Data\StoreLocatorInterface[]
     * @return void
     */
    public function setItems(array $items);
}