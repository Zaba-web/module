<?php
namespace Myv\PlaneShipping\Model\Config\Source;
 
use Magento\Framework\Option\ArrayInterface;
 
class Categorylist implements ArrayInterface {
    protected $_categoryHelper;
 
    public function __construct(\Magento\Catalog\Helper\Category $catalogCategory) {
        $this->_categoryHelper = $catalogCategory;
    }
 
    public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true) {
        return $this->_categoryHelper->getStoreCategories($sorted , $asCollection, $toLoad);
    }
 
    public function toOptionArray() {
        $arrayResult = $this->toArray();
        $result = [];
 
        foreach ($arrayResult as $key => $value) {
            $result[] = [
                'value' => $key,
                'label' => $value
            ];
        }
 
        return $result;
    }
 
    public function toArray() {
        $categories = $this->getStoreCategories(true,false,true);
        $catagoryList = array();

        foreach ($categories as $category){
            $catagoryList[$category->getEntityId()] = __($category->getName());
        }
 
        return $catagoryList;
    }
 
}