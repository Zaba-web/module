<?php 

namespace Myv\StoreLocator\Ui\Component\Listing\Columns;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Thumbnail extends Column {

    protected $storeManagerInterface;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StoreManagerInterface $storeManagerInterface,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->storeManagerInterface = $storeManagerInterface;
    }

    public function prepareDataSource(array $dataSource) {
        foreach($dataSource["data"]["items"] as &$item) {
            if (isset($item['image'])) {
                $imageUrl = $item['image'];

                if(strpos($imageUrl, "http") != 0 || strpos($imageUrl, "http") === false) {
                    $imageUrl = $this->storeManagerInterface->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $item['image'];
                }

                $item['image_src'] = $imageUrl;
                $item['image_alt'] = $item['id'];
                $item['image_link'] = $imageUrl;
                $item['image_orig_src'] = $imageUrl;
            }
        }

        return $dataSource;
    }
}