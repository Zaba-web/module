<?php

namespace Myv\StoreLocator\Controller\Adminhtml\Overview;

use Magento\Backend\App\AbstractAction;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends AbstractAction {
    protected $pageFactory;

    public function __construct(Context $context, PageFactory $pageFactory) {
        $this->pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    public function execute() {
        $page = $this->pageFactory->create();
        $page->getConfig()->getTitle()->set(__('Stores List Grid'));

		return $page;
    }
}