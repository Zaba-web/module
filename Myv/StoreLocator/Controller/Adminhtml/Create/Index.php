<?php
namespace Myv\StoreLocator\Controller\Adminhtml\Create;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action {
    protected $pageFactory;

    public function __construct(Context $context, PageFactory $pageFactory) {
        $this->pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    public function execute() {
        $page = $this->pageFactory->create();
        $page->getConfig()->getTitle()->set(__('Add Store'));

		return $page;
    }
}