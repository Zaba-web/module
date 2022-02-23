<?php 
namespace Myv\Post\Api\Data;

interface PostInterface {
    const ENTITY_ID = 'id';
    const TITLE = 'title';
    const AUTHOR = 'author';
    const DATE = 'publication_date';
    const CONTENT = 'content';
    const IMAGE = "image";

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return PostInterface
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     * @return PostInterface
     */
    public function setTitle($title);

    /**
     * @return string
     */
    public function getAuthor();

    /**
     * @param string $author
     * @return PostInterface
     */
    public function setAuthor($author);

    /**
     * @return string
     */
    public function getDate();

    /**
     * @param string $date
     * @return PostInterface
     */
    public function setDate($date);

    /**
     * @return string
     */
    public function getContent();

    /**
     * @param string $content
     * @return PostInterface
     */
    public function setContent($content);

    /**
     * @return string
     */
    public function getImage();

    /**
     * @param string $image
     * @return PostInterface
     */
    public function setImage($image);

    /**
     * @return string
     */
    public function getContentPreview();
}