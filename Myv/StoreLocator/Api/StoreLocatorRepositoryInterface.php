<?php
namespace Myv\StoreLocator\Api;

interface StoreLocatorRepositoryInterface {
    /**
     * @param int $id
     * @return \Myv\StoreLocator\Api\Data\StoreLocatorInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id);

    /**
     * @param \Myv\StoreLocator\Api\Data\StoreLocatorInterface $store
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedExeption
     */
    public function save(\Myv\StoreLocator\Api\Data\StoreLocatorInterface $store);
    
    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Myv\StoreLocator\Api\StoreLocatorSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedExeption
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @param \Myv\StoreLocator\Api\Data\StoreLocatorInterface $store
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedExeption
     */
    public function delete(\Myv\StoreLocator\Api\Data\StoreLocatorInterface $store);

    /**
     * @param \Myv\StoreLocator\Api\Data\StoreLocatorInterface $store
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedExeption
     */
    public function deleteById(int $id);
}