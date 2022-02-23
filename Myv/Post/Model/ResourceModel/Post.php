<?php

namespace Myv\Post\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Post extends AbstractDb {
    public function _construct() {
        $this->_init('posts', 'id');
    }
}