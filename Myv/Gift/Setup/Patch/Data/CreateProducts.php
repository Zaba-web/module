<?php
namespace Myv\Gift\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Catalog\Api\CategoryLinkManagementInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\App\State;

class CreateProducts implements DataPatchInterface {
    private $productFactory;
    private $productRepository;
    private $categoryLinkManager;
    private $stockRegistry;
    private $state;

    public function __construct(
        ProductFactory $productFactory, 
        ProductRepository $productRepository,
        CategoryLinkManagementInterface $categoryLinkManager, 
        StockRegistryInterface $stockRegistry,
        State $state
    ) {
        $this->productFactory = $productFactory;
        $this->productRepository = $productRepository;
        $this->categoryLinkManager = $categoryLinkManager;
        $this->stockRegistry = $stockRegistry;
        $this->state = $state;
    }

    public function apply() {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);

        $this->createBaseProduct();
        $this->createGiftProduct();
    }

    public static function getDependencies() {
        return [];
    }

    public function getAliases() {
        return [];
    }

    private function createBaseProduct() {
        $sku = "datapatch_base_product";

        $baseProduct = $this->productFactory->create();
        $baseProduct->setSku($sku);
        $baseProduct->setName('DataPatch Base Product');
        $baseProduct->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE);
        $baseProduct->setVisibility(4);
        $baseProduct->setPrice(2042);
        $baseProduct->setAttributeSetId(4);
        $baseProduct->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);

        $baseProduct = $this->productRepository->save($baseProduct);

        $this->categoryLinkManager->assignProductToCategories($sku, [3, 4]);

        $stockItem = $this->stockRegistry->getStockItemBySku($baseProduct->getSku());
        $stockItem->setIsInStock(true);
        $stockItem->setQty(50);

        $this->stockRegistry->updateStockItemBySku($baseProduct->getSku(), $stockItem);
    }

    private function createGiftProduct() {
        $sku = "datapatch_gift_product";

        $giftProduct = $this->productFactory->create();
        $giftProduct->setSku($sku);
        $giftProduct->setName('DataPatch Gift Product');
        $giftProduct->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE);
        $giftProduct->setVisibility(1);
        $giftProduct->setPrice(0);
        $giftProduct->setAttributeSetId(4);
        $giftProduct->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);

        $giftProduct = $this->productRepository->save($giftProduct);

        $this->categoryLinkManager->assignProductToCategories($sku, [3, 4]);

        $stockItem = $this->stockRegistry->getStockItemBySku($giftProduct->getSku());
        $stockItem->setIsInStock(true);
        $stockItem->setQty(50);

        $this->stockRegistry->updateStockItemBySku($giftProduct->getSku(), $stockItem);
    }
}