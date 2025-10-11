<?php

namespace EDU\SupportTickets\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use EDU\SupportTickets\Api\Data\PriorityInterface;

class Priority extends AbstractModel implements PriorityInterface, IdentityInterface
{
    const CACHE_TAG = 'support_ticket_priority';

    protected $_cacheTag = 'support_ticket_priority';
    protected $_eventPrefix = 'support_ticket_priority';

    protected function _construct()
    {
        $this->_init(\EDU\SupportTickets\Model\ResourceModel\Priority::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getPriorityId()
    {
        return $this->getData(self::PRIORITY_ID);
    }

    public function setPriorityId($priorityId)
    {
        return $this->setData(self::PRIORITY_ID, $priorityId);
    }

    public function getName()
    {
        return $this->getData(self::NAME);
    }

    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    public function getColor()
    {
        return $this->getData(self::COLOR);
    }

    public function setColor($color)
    {
        return $this->setData(self::COLOR, $color);
    }

    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Check if priority is active
     *
     * @return bool
     */
    public function isActive()
    {
        return (bool) $this->getIsActive();
    }

    /**
     * Get active status label
     *
     * @return string
     */
    public function getActiveStatusLabel()
    {
        return $this->isActive() ? 'Active' : 'Inactive';
    }

    /**
     * Get color style for display
     *
     * @return string
     */
    public function getColorStyle()
    {
        return 'color: ' . $this->getColor() . ';';
    }
}
