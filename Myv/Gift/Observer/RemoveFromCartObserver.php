<?php

namespace Myv\Gift\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Data\Form\FormKey;
use Magento\Checkout\Model\Cart;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\App\Config\ScopeConfigInterface;

class RemoveFromCartObserver implements ObserverInterface {
    private $productRepository;
    private $cart;
    private $formKey;

    private $baseProductSKU;
    private $giftProductSKU;

    public function __construct(
        FormKey $formKey, 
        Cart $cart, 
        ProductRepository $productRepository,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->productRepository = $productRepository;
        $this->cart = $cart;
        $this->formKey = $formKey;

        $this->giftProductSKU = $scopeConfig->getValue('gift/general/gift');
        $this->baseProductSKU = $scopeConfig->getValue('gift/general/base');
    }

    public function execute(Observer $observer) {
        $quoteItem = $observer->getQuoteItem();

        $product = $quoteItem->getProduct();
        $SKU = $product->getSku();

        if($this->baseProductSKU == $SKU) {
            $giftProduct = $this->productRepository->get($this->giftProductSKU);
            $giftProductId = $giftProduct->getId();

            $checkoutSessionQuote = $this->cart->getCheckoutSession()->getQuote();
            
            if($checkoutSessionQuote->hasProductId($giftProductId)) {
                $items = $checkoutSessionQuote->getItemsCollection();
                $this->findAndRemoveGift($items, $giftProductId);
            }
        }
    }

    private function findAndRemoveGift($items, $giftProductId) {
        foreach($items as $item) {
            if($item->getProductId() == $giftProductId) {
                $this->cart->removeItem($item->getId());
            }
        }
    }
}