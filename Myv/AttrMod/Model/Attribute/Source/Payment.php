<?php
namespace Myv\AttrMod\Model\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Payment extends AbstractSource
{
    /**
     * Get all options
     * @return array
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['label' => __('Type1'), 'value' => 'method1'],
                ['label' => __('Type2'), 'value' => 'method2'],
                ['label' => __('Type3'), 'value' => 'method3']
            ];
        }
        return $this->_options;
    }
}