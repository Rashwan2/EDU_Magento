<?php

namespace EDU\SupportTickets\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use EDU\SupportTickets\Api\Data\CategoryInterface;

class Category extends AbstractModel implements CategoryInterface, IdentityInterface
{
    const CACHE_TAG = 'support_ticket_category';

    protected $_cacheTag = 'support_ticket_category';
    protected $_eventPrefix = 'support_ticket_category';

    protected function _construct()
    {
        $this->_init(\EDU\SupportTickets\Model\ResourceModel\Category::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getCategoryId()
    {
        return $this->getData(self::CATEGORY_ID);
    }

    public function setCategoryId($categoryId)
    {
        return $this->setData(self::CATEGORY_ID, $categoryId);
    }

    public function getName()
    {
        return $this->getData(self::NAME);
    }

    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    public function getSlug()
    {
        return $this->getData(self::SLUG);
    }

    public function setSlug($slug)
    {
        return $this->setData(self::SLUG, $slug);
    }

    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * Check if category is active
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
}
