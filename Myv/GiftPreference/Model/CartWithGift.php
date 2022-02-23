<?php

namespace Myv\GiftPreference\Model;

use Magento\Checkout\Model\Cart;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Checkout\Model\Cart\CartInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Data\Form\FormKey;

class CartWithGift extends Cart {
    private $formKey;
    private $baseProductSKU;
    private $giftProductSKU;

    public function __construct(
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Checkout\Model\ResourceModel\Cart $resourceCart,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\CatalogInventory\Api\StockStateInterface $stockState,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        FormKey $formKey,
        ProductRepositoryInterface $productRepository,
        array $data = []
    ) {
        $this->formKey = $formKey;

        $this->baseProductSKU = $scopeConfig->getValue('gift/general/base');
        $this->giftProductSKU = $scopeConfig->getValue('gift/general/gift');
        
        parent::__construct(
            $eventManager,
            $scopeConfig,
            $storeManager,
            $resourceCart,
            $checkoutSession,
            $customerSession,
            $messageManager,
            $stockRegistry,
            $stockState,
            $quoteRepository,
            $productRepository,
            $data
        );
    }

    public function addProduct(
        $productInfo, 
        $requestInfo = null
    ) {
        $product = $this->_getProduct($productInfo);
        $SKU = $product->getSku();

        if($SKU == $this->baseProductSKU) {
            $this->addGiftToCart();
        }

        return parent::addProduct($productInfo, $requestInfo);
    }

    public function removeItem($itemId) {
        $item = $this->getQuote()->getItemById($itemId);

        $product = $item->getProduct();
        $SKU = $product->getSku();

        if($this->baseProductSKU == $SKU) {
            $giftProduct = $this->productRepository->get($this->giftProductSKU);
            $giftProductId = $giftProduct->getId();

            $checkoutSessionQuote = $this->getCheckoutSession()->getQuote();
        
            if($checkoutSessionQuote->hasProductId($giftProductId)) {
                $items = $checkoutSessionQuote->getItemsCollection();
                $this->findAndRemoveGift($items, $giftProductId);
            }
        }

        return parent::removeItem($itemId);
    }

    private function addGiftToCart() {
        $giftProduct = $this->productRepository->get($this->giftProductSKU);
        $giftProductId = $giftProduct->getId();
        $checkoutSession = $this->getCheckoutSession();

        if($checkoutSession->getQuote()->hasProductId($giftProductId)) {
            return ;
        }

        $params = [
            'form_key' => $this->formKey->getFormKey(),
            'product' => $giftProductId, 
            'qty'   => 1
        ];
    
        $this->addProduct($giftProduct, $params);
        $this->save();
    }

    private function findAndRemoveGift($items, $giftProductId) {
        foreach($items as $item) {
            if($item->getProductId() == $giftProductId) {
                $this->removeItem($item->getId());
            }
        }
    }
}   