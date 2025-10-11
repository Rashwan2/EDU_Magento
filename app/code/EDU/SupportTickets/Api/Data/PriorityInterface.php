<?php

namespace EDU\SupportTickets\Api\Data;

interface PriorityInterface
{
    const PRIORITY_ID = 'priority_id';
    const NAME = 'name';
    const COLOR = 'color';
    const SORT_ORDER = 'sort_order';
    const IS_ACTIVE = 'is_active';
    const CREATED_AT = 'created_at';

    /**
     * Get priority ID
     *
     * @return int|null
     */
    public function getPriorityId();

    /**
     * Set priority ID
     *
     * @param int $priorityId
     * @return $this
     */
    public function setPriorityId($priorityId);

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
     * Get color
     *
     * @return string|null
     */
    public function getColor();

    /**
     * Set color
     *
     * @param string $color
     * @return $this
     */
    public function setColor($color);

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
}
