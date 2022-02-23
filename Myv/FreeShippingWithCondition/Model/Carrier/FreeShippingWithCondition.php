<?php

namespace Myv\FreeShippingWithCondition\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Psr\Log\LoggerInterface;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Checkout\Model\Session;

class FreeShippingWithCondition extends AbstractCarrier implements CarrierInterface {
    protected $_code = 'customshipping';
    protected $_isFixed = true;

    private $rateResultFactory;
    private $rateMethodFactory;

    private $checkoutSession;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        Session $checkoutSession,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);

        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->checkoutSession = $checkoutSession;
        $this->checkoutSession = $checkoutSession;
    }

    public function collectRates(RateRequest $request) {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $isShippingFree = $this->isFreeShippingAllowed();
        $shippingCost = $this->getCost($isShippingFree);

        $result = $this->rateResultFactory->create();
        $method = $this->rateMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setMethod($this->_code);

        $method->setMethodTitle($this->getConfigData('name'));
        $method->setCarrierTitle($this->getTitle($isShippingFree));
        
        $method->setMethod($this->_code);

        $method->setPrice($shippingCost);
        $method->setCost($shippingCost);

        $result->append($method);
        return $result;
    }

    public function getAllowedMethods() {
        return [$this->_code => $this->getConfigData('name')];
    }

    private function getPrice() {
        $price = 0;

        $quote = $this->checkoutSession->getQuote();
        $items = $quote->getAllItems();

        foreach($items as $item) {
            $price += $item->getQty() * $item->getProduct()->getPrice();
        }

        return $price;
    }

    /**
     * @return bool
     */
    private function isFreeShippingAllowed() {
        $minPriceForFreeShipping = $this->getConfigData('min_for_free');
        $price = $this->getPrice();

        return $price >= $minPriceForFreeShipping;
    }

    /**
     * @return string
     */
    private function getTitle($isShippingFree) {
        return $isShippingFree ? $this->getConfigData('title_if_free') : $this->getConfigData('title_if_not_free');
    }

    /**
     * @return float
     */
    private function getCost($isShippingFree) {
        return $isShippingFree ? 0 : (float)$this->getConfigData('cost');
    }
}