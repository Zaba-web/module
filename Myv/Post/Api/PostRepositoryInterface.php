<?php
namespace Myv\Post\Api;

interface PostRepositoryInterface {
    /**
     * @param int $id
     * @return \Myv\Post\Api\Data\PostInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id);

    /**
     * @param \Myv\Post\Api\Data\PostInterface $post
     * @return \Myv\Post\Api\Data\PostInterface
     * @throws \Magento\Framework\Exception\LocalizedExeption
     */
    public function save(\Myv\Post\Api\Data\PostInterface $post);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Myv\Post\Api\Data\PostSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedExeption
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @param \Myv\Post\Api\Data\PostInterface $post
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedExeption
     */
    public function delete(\Myv\Post\Api\Data\PostInterface $post);
}