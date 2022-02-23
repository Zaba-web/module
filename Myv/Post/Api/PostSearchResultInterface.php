<?php
namespace Myv\Post\Api;

use Magento\Framework\Api\SearchResultsInterface;

interface PostSearchResultInterface extends SearchResultsInterface {

    /**
     * @return \Myv\Post\Api\Data\PostInterface[]
     */
    public function getItems();

    
    /**
     * @param \Myv\Post\Api\Data\PostInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}