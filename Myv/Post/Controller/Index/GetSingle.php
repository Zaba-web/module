<?php
namespace Myv\Post\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class GetSingle extends Action {
    protected $pageFactory;

    public function __construct(
        Context $context,
        PageFactory $pageFactory
    ) {
        $this->pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    public function execute() {
        $id = $this->getRequest()->getParam("id");

        $singlePage = $this->pageFactory->create();
        $singlePage->getConfig()->getTitle()->set('Read single post');

        $block = $singlePage->getLayout()->getBlock('get.single');
        $block->setData('post_id', $id);

		return $singlePage;
    }
}