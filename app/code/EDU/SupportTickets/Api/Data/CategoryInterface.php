<?php

namespace EDU\SupportTickets\Api\Data;

interface CategoryInterface
{
    const CATEGORY_ID = 'category_id';
    const NAME = 'name';
    const SLUG = 'slug';
    const DESCRIPTION = 'description';
    const IS_ACTIVE = 'is_active';
    const SORT_ORDER = 'sort_order';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Get category ID
     *
     * @return int|null
     */
    public function getCategoryId();

    /**
     * Set category ID
     *
     * @param int $categoryId
     * @return $this
     */
    public function setCategoryId($categoryId);

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Get slug
     *
     * @return string|null
     */
    public function getSlug();

    /**
     * Set slug
     *
     * @param string $slug
     * @return $this
     */
    public function setSlug($slug);

    /**
     * Get description
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Set description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * Get is active
     *
     * @return bool|null
     */
    public function getIsActive();

    /**
     * Set is active
     *
     * @param bool $isActive
     * @return $this
     */
    public function setIsActive($isActive);

    /**
     * Get sort order
     *
     * @return int|null
     */
    public function getSortOrder();

    /**
     * Set sort order
     *
     * @param int $sortOrder
     * @return $this
     */
    public function setSortOrder($sortOrder);

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
}
