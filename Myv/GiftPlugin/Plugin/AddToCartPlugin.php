<?php

namespace Myv\GiftPlugin\Plugin;

use Magento\Checkout\Model\Cart;
use Magento\Catalog\Model\Product;
use Magento\Framework\Data\Form\FormKey;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\App\Config\ScopeConfigInterface;

class AddToCartPlugin {
    private $productRepository;
    private $formKey;

    private $baseProductSKU;
    private $giftProductSKU;

    public function __construct(
        FormKey $formKey, 
        ProductRepository $productRepository,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->productRepository = $productRepository;
        $this->formKey = $formKey;

        $this->baseProductSKU = $scopeConfig->getValue('gift/general/base');
        $this->giftProductSKU = $scopeConfig->getValue('gift/general/gift');
    }
    
    public function beforeAddProduct(Cart $subject, $productInfo, $requestInfo = null) {
        $product = null;
        
        if($productInfo instanceof Product) {
            $product = $productInfo;
        } else {
            $product = $this->productRepository->getById($productInfo);
        }

        $SKU = $product->getSku();

        if($SKU == $this->baseProductSKU) {
            $this->addGiftToCart($subject);
        }

        return [$productInfo, $requestInfo];
    }

    private function addGiftToCart($cart) {
        $giftProduct = $this->productRepository->get($this->giftProductSKU);
        $giftProductId = $giftProduct->getId();
        $checkoutSession = $cart->getCheckoutSession();

        if($checkoutSession->getQuote()->hasProductId($giftProductId)) {
            return ;
        }

        $params = [
            'form_key' => $this->formKey->getFormKey(),
            'product' => $giftProductId, 
            'qty'   => 1
        ];
    
        $cart->addProduct($giftProduct, $params);
        $cart->save();
    }
}