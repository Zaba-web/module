<?php

namespace Myv\PlaneShipping\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Psr\Log\LoggerInterface;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Checkout\Model\Session;

class PlaneShipping extends AbstractCarrier implements CarrierInterface {
    protected $_code = 'planeshipping';
    protected $_isFixed = true;

    private $rateResultFactory;
    private $rateMethodFactory;

    private $checkoutSession;

    private $categoryIdWithAdditionalCost;
    private $additionRate = 0.075;

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
        $this->categoryIdWithAdditionalCost = $this->getConfigData('category_with_addition');
    }

    public function collectRates(RateRequest $request) {
        if (!$this->getConfigFlag('active')) {
            return false;
        }
   
        $result = $this->rateResultFactory->create();
        $method = $this->rateMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setMethod($this->_code);

        $method->setMethodTitle($this->getConfigData('plane_shipment_name'));
        $method->setCarrierTitle($this->getConfigData('plane_shipment_title'));

        $method->setMethod($this->_code);

        $shippingCost = (float)$this->getConfigData('base_cost') + $this->getAdditionalCost();
        
        $method->setPrice($shippingCost);
        $method->setCost($shippingCost);

        $result->append($method);
        return $result;
    }

    public function getAllowedMethods() {
        return [$this->_code => $this->getConfigData('plane_shipment_name')];
    }

    /**
     * @return float
     */
    private function getAdditionalCost() {
        $addition = 0;
        $items = $this->checkoutSession->getQuote()->getAllItems();

        foreach($items as $item) {
            $product = $item->getProduct();
            $categoryIds = $product->getCategoryIds();

            if(in_array($this->categoryIdWithAdditionalCost, $categoryIds)) {
                $addition += $item->getQty() * ($product->getPrice() * $this->additionRate);
            }
        }

        return $addition;
    }
}