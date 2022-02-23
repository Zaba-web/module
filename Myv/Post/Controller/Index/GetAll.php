<?php
namespace Myv\Post\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class GetAll extends Action {
	protected $pageFactory;

	public function __construct(Context $context, PageFactory $pageFactory) {
		$this->pageFactory = $pageFactory;
		return parent::__construct($context);
	}

	public function execute() {
		$listPage = $this->pageFactory->create();
        $listPage->getConfig()->getTitle()->set('All publications');

		return $listPage;
	}
}

