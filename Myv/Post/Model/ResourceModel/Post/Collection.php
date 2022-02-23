<?php
namespace Myv\Post\Model\ResourceModel\Post;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

use Myv\Post\Model\Post as Model;
use Myv\Post\Model\ResourceModel\Post as ResourceModel;

class Collection extends AbstractCollection {
    public function _construct() {
        $this->_init(Model::class, ResourceModel::class);
    }
}