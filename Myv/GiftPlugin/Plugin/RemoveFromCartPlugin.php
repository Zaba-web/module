<?php

namespace Myv\GiftPlugin\Plugin;

use Magento\Checkout\Model\Cart;
use Magento\Framework\Data\Form\FormKey;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\App\Config\ScopeConfigInterface;

class RemoveFromCartPlugin {
    private $productRepository;
    private $formKey;
    private $cart;

    private $baseProductSKU;
    private $giftProductSKU;
    
    public function __construct(
        FormKey $formKey,
        ProductRepository $productRepository,
        Cart $cart,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->productRepository = $productRepository;
        $this->formKey = $formKey;
        $this->cart = $cart;

        $this->giftProductSKU = $scopeConfig->getValue('gift/general/gift');
        $this->baseProductSKU = $scopeConfig->getValue('gift/general/base');
    }

    public function beforeRemoveItem(Cart $subject, $itemId) {
        $item = $subject->getQuote()->getItemById($itemId);

        $product = $item->getProduct();
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

        return $itemId;
    }

    private function findAndRemoveGift($items, $giftProductId) {
        foreach($items as $item) {
            if($item->getProductId() == $giftProductId) {
                $this->cart->removeItem($item->getId());
            }
        }
    }
}