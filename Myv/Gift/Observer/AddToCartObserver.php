<?php

namespace Myv\Gift\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Data\Form\FormKey;
use Magento\Checkout\Model\Cart;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\App\Config\ScopeConfigInterface;

class AddToCartObserver implements ObserverInterface {
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

        $this->baseProductSKU = $scopeConfig->getValue('gift/general/base');
        $this->giftProductSKU = $scopeConfig->getValue('gift/general/gift');
    }

    public function execute(Observer $observer) {
        $product = $observer['product'];
        $SKU = $product->getSku();

        if($SKU == $this->baseProductSKU) {
            $this->addGiftToCart();
        }
    }

    private function addGiftToCart() {
        $giftProduct = $this->productRepository->get($this->giftProductSKU);
        $giftProductId = $giftProduct->getId();
        $checkoutSession = $this->cart->getCheckoutSession();

        if($checkoutSession->getQuote()->hasProductId($giftProductId)) {
            return ;
        }

        $params = [
            'form_key' => $this->formKey->getFormKey(),
            'product' => $giftProductId, 
            'qty'   => 1
        ];
    
        $this->cart->addProduct($giftProduct, $params);
        $this->cart->save();
    }
}