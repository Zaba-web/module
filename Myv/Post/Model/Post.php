<?php
namespace Myv\Post\Model;

use Magento\Framework\Model\AbstractModel;
use Myv\Post\Api\Data\PostInterface;

use Myv\Post\Model\ResourceModel\Post as ResourceModel;

class Post extends AbstractModel implements PostInterface {
    private $data;

    public function _construct() {
        $this->_init(ResourceModel::class);
    }

    public function getDefaultValues() {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return parent::getData(self::ENTITY_ID);
    }

    /**
     * @inheritdoc
     */
    public function setId($id) {
        return parent::setData(self::ENTITY_ID, $id);
    }

    /**
     * @inheritdoc
     */
    public function getTitle() {
        return parent::getData(self::TITLE);
    }

    /**
     * @inheritdoc
     */
    public function setTitle($title) {
        return parent::setData(self::TITLE, $title);
    }

    /**
     * @inheritdoc
     */
    public function getAuthor() {
        return parent::getData(self::AUTHOR);
    }

    /**
     * @inheritdoc
     */
    public function setAuthor($author) {
        return parent::setData(self::AUTHOR, $author);
    }

    /**
     * @inheritdoc
     */
    public function getDate() {
        return parent::getData(self::DATE);
    }

    /**
     * @inheritdoc
     */
    public function setDate($date) {
        return parent::setData(self::DATE, $date);
    }

    /**
     * @inheritdoc
     */
    public function getContent() {
        return parent::getData(self::CONTENT);
    }

    /**
     * @inheritdoc
     */
    public function setContent($content) {
        return parent::setData(self::CONTENT, $content);
    }

    /**
     * @inheritdoc
     */
    public function getImage() {
        return parent::getData(self::IMAGE);
    }

    /**
     * @inheritdoc
     */
    public function setImage($image) {
        return parent::setData(self::IMAGE, $image);
    }

    /**
     * @inheritdoc
     */
    public function getContentPreview() {
        $content = parent::getData(self::CONTENT);
        
        if (strlen($content) > 60) {
            $content = substr($content, 0, 60) . '...';
        }

        return $content;
    }
}