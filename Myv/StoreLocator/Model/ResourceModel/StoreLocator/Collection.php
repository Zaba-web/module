<?php

namespace Myv\StoreLocator\Model\ResourceModel\StoreLocator;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

use Myv\StoreLocator\Model\StoreLocator as Model;
use Myv\StoreLocator\Model\ResourceModel\StoreLocator as ResourceModel;

class Collection extends AbstractCollection {
    public function _construct() {
        $this->_init(Model::class, ResourceModel::class);
    }
}