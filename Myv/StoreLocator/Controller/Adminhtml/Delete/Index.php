<?php
namespace Myv\StoreLocator\Controller\Adminhtml\Delete;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Myv\StoreLocator\Model\StoreLocatorRepository;

class Index extends Action {
    private $storeLocatorRepository;

    public function __construct(
        Context $context,
        StoreLocatorRepository $storeLocatorRepository
    ) {
        $this->storeLocatorRepository = $storeLocatorRepository;
        return parent::__construct($context);
    }

    public function execute() {
        $id = $this->getRequest()->getParam("id");

        try {
            $store = $this->storeLocatorRepository->getById($id);
            $result = $this->storeLocatorRepository->delete($store);
            $this->messageManager->addSuccess(__("Store deleted"));
        } catch (NoSuchEntityException $exception) {
            $this->messageManager->addError(__("Store with id %1 does not exist", $id));
        }

        return $this->_redirect($this->_redirect->getRefererUrl());
    }
}