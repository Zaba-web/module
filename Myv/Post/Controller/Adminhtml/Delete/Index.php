<?php
namespace Myv\Post\Controller\Adminhtml\Delete;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Myv\Post\Model\PostRepository;

class Index extends Action {
    private $postRepository;

    public function __construct(
        Context $context,
        PostRepository $postRepository
    ) {
        $this->postRepository = $postRepository;
        return parent::__construct($context);
    }

    public function execute() {
        $id = $this->getRequest()->getParam("id");

        try {
            $post = $this->postRepository->getById($id);
            $this->messageManager->addSuccess(__("Post deleted"));
            $this->postRepository->delete($post);
        } catch (NoSuchEntityException $exception) {
            $this->messageManager->addError(__("Post with id %1 does not exist", $id));
        }

        return $this->_redirect($this->_redirect->getRefererUrl());
    }
}