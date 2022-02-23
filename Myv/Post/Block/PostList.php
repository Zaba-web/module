<?php
namespace Myv\Post\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder; 
use \Myv\Post\Model\PostRepository;

class PostList extends Template {
    private $postRepository;
    private $searchCriteriaBuilder;

    public function __construct(
        Context $context, 
        PostRepository $postRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->postRepository = $postRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;

        parent::__construct($context);
    }

    public function getAllPosts() {
        $searchCriteria = $this->searchCriteriaBuilder->create();

        $posts = $this->postRepository->getList($searchCriteria);
        return $posts;
    }
}