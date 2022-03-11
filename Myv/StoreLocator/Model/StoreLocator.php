<?php

namespace Myv\StoreLocator\Model;

use Magento\Framework\Model\AbstractModel;
use Myv\StoreLocator\Model\ResourceModel\StoreLocator as ResourceModel;
use Myv\StoreLocator\Api\Data\StoreLocatorInterface;

class StoreLocator extends AbstractModel implements StoreLocatorInterface {
    public function _construct() {
        $this->_init(ResourceModel::class);
    }

    public function getDefaultValues() {
        return [];
    }

    public function getId() {
        return parent::getData(self::ID);
    }

    public function setId($id) {
        return parent::setData(self::ID, $id);
    }

    public function getName() {
        return parent::getData(self::NAME);
    }

    public function setName($name) {
        return parent::setData(self::NAME, $name);
    }

    public function getImage() {
        return parent::getData(self::IMAGE);
    }

    public function setImage($image) {
        return parent::setData(self::IMAGE, $image);
    }

    public function getDescription() {
        return parent::getData(self::DESCRIPTION);
    }

    public function setDescription($description) {
        return parent::setData(self::DESCRIPTION, $description);
    }

    public function getLatitude() {
        return parent::getData(self::LAT);
    }

    public function setLatitude($latitude) {
        return parent::setData(self::LAT, $latitude);
    }

    public function getLongitude() {
        return parent::getData(self::LONG);
    }

    public function setLongitude($longitude) {
        return parent::setData(self::LONG, $longitude);
    }

    public function getOpenTime() {
        return parent::getData(self::OPEN_TIME);
    }

    public function setOpenTime($time) {
        return parent::setData(self::OPEN_TIME, $time);
    }

    public function getCloseTime() {
        return parent::getData(self::CLOSE_TIME);
    }

    public function setCloseTime($time) {
        return parent::setData(self::CLOSE_TIME, $time);
    }
}