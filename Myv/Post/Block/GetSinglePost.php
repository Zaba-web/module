<?php
namespace Myv\Post\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Myv\Post\Model\PostRepository;

class GetSinglePost extends Template {
    private $postRepository;

    public function __construct(
        Context $context,
        PostRepository $postRepository
    ) {
        $this->postRepository = $postRepository;
        parent::__construct($context);
    }

    public function getPost() {
        $postId = $this->getData('post_id');

        $post = $this->postRepository->getById($postId);
        return $post;
    }
}