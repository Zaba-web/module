<?php

namespace Myv\StoreLocator\Api\Data;

interface StoreLocatorInterface {
    const ID = 'id';
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const LAT = 'latitude';
    const LONG = 'longitude';
    const OPEN_TIME = 'open_time';
    const CLOSE_TIME = 'close_time';
    const IMAGE = 'image';

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return Myv\StoreLocator\Api\Data\StoreLocatorInterface
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return Myv\StoreLocator\Api\Data\StoreLocatorInterface
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getImage();

    /**
     * @param string $image
     * @return Myv\StoreLocator\Api\Data\StoreLocatorInterface
     */
    public function setImage($image);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param string $description
     * @return Myv\StoreLocator\Api\Data\StoreLocatorInterface
     */
    public function setDescription($description);

    /**
     * @return string
     */
    public function getLatitude();

    /**
     * @param float $latitude
     * @return Myv\StoreLocator\Api\Data\StoreLocatorInterface
     */
    public function setLatitude($latitude);

    /**
     * @return float
     */
    public function getLongitude();

    /**
     * @param float $longitude
     * @return Myv\StoreLocator\Api\Data\StoreLocatorInterface
     */
    public function setLongitude($longitude);

    /**
     * @return string
     */
    public function getOpenTime();

    /**
     * @param string $time
     * @return Myv\StoreLocator\Api\Data\StoreLocatorInterface
     */
    public function setOpenTime($time);

    /**
     * @return string
     */
    public function getCloseTime();

    /**
     * @param string $time
     * @return Myv\StoreLocator\Api\Data\StoreLocatorInterface
     */
    public function setCloseTime($time);
}