<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreBlogs\Model;

use Magento\Framework\Model\AbstractModel;
use EDU\StoreBlogs\Model\ResourceModel\Blog as BlogResourceModel;
use EDU\StoreBlogs\Api\Data\BlogInterface;

/**
 * Blog Model
 */
class Blog extends AbstractModel implements BlogInterface
{
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ARCHIVED = 'archived';

    /**
     * @var string
     */
    protected $_eventPrefix = 'edu_store_blogs';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\EDU\StoreBlogs\Model\ResourceModel\Blog::class);
    }

    /**
     * Get available statuses
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [
            self::STATUS_DRAFT => __('Draft'),
            self::STATUS_PUBLISHED => __('Published'),
            self::STATUS_ARCHIVED => __('Archived')
        ];
    }

    /**
     * Check if blog is published
     *
     * @return bool
     */
    public function isPublished()
    {
        return $this->getStatus() === self::STATUS_PUBLISHED;
    }

    /**
     * Publish blog
     *
     * @return $this
     */
    public function publish()
    {
        $this->setStatus(self::STATUS_PUBLISHED);
        if (!$this->getPublishedAt()) {
            $this->setPublishedAt(date('Y-m-d H:i:s'));
        }
        return $this;
    }

    /**
     * Get blog ID
     *
     * @return int|null
     */
    public function getBlogId()
    {
        return $this->getData(self::BLOG_ID);
    }

    /**
     * Set blog ID
     *
     * @param int $blogId
     * @return $this
     */
    public function setBlogId($blogId)
    {
        return $this->setData(self::BLOG_ID, $blogId);
    }

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * Set title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Get content
     *
     * @return string|null
     */
    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * Set content
     *
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * Get status
     *
     * @return string|null
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Set status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get created at
     *
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Set created at
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get updated at
     *
     * @return string|null
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * Set updated at
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * Get published at
     *
     * @return string|null
     */
    public function getPublishedAt()
    {
        return $this->getData(self::PUBLISHED_AT);
    }

    /**
     * Set published at
     *
     * @param string $publishedAt
     * @return $this
     */
    public function setPublishedAt($publishedAt)
    {
        return $this->setData(self::PUBLISHED_AT, $publishedAt);
    }

    /**
     * Get store ID
     *
     * @return int|null
     */
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * Set store ID
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }
}
