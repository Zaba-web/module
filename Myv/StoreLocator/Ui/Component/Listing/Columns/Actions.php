<?php

namespace Myv\StoreLocator\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Framework\Url;

class Actions extends Column {
    protected $urlBuilder;
    protected $urlHelper;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        Url $urlHelper,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->urlHelper = $urlHelper;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource) {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')]['edit'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'storelocator/edit/index',
                        ['id' => $item['id']]
                    ),
                    'label' => __('Edit'),
                    'hidden' => false,
                ];

                $item[$this->getData('name')]['delete'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'storelocator/delete/index',
                        ['id' => $item['id']]
                    ),
                    'label' => __('Delete'),
                    'hidden' => false,
                ];

                $item[$this->getData('name')]['view'] = [
                    'href' => $this->urlHelper->getUrl('storelocator/location/index'),
                    'label' => __('View Map'),
                    'hidden' => false,
                ];
            }
        }

        return $dataSource;
    }
}
