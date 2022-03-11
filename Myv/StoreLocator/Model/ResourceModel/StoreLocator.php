<?php

namespace Myv\StoreLocator\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class StoreLocator extends AbstractDb {
    public function _construct() {
        $this->_init('storelocator_stores', 'id');
    }
}