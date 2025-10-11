<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreBlogs\Api\Data;

/**
 * Blog Interface
 */
interface BlogInterface
{
    const BLOG_ID = 'blog_id';
    const TITLE = 'title';
    const CONTENT = 'content';
    const STATUS = 'status';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const PUBLISHED_AT = 'published_at';
    const STORE_ID = 'store_id';

    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ARCHIVED = 'archived';

    /**
     * Get blog ID
     *
     * @return int|null
     */
    public function getBlogId();

    /**
     * Set blog ID
     *
     * @param int $blogId
     * @return $this
     */
    public function setBlogId($blogId);

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Set title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Get content
     *
     * @return string|null
     */
    public function getContent();

    /**
     * Set content
     *
     * @param string $content
     * @return $this
     */
    public function setContent($content);

    /**
     * Get status
     *
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * Get created at
     *
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created at
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated at
     *
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated at
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get published at
     *
     * @return string|null
     */
    public function getPublishedAt();

    /**
     * Set published at
     *
     * @param string $publishedAt
     * @return $this
     */
    public function setPublishedAt($publishedAt);

    /**
     * Get store ID
     *
     * @return int|null
     */
    public function getStoreId();

    /**
     * Set store ID
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId($storeId);
}
