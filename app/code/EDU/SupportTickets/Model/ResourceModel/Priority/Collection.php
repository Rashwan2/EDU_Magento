<?php

namespace EDU\SupportTickets\Model\ResourceModel\Priority;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \EDU\SupportTickets\Model\Priority::class,
            \EDU\SupportTickets\Model\ResourceModel\Priority::class
        );
    }

    /**
     * Filter by active status
     *
     * @param bool $isActive
     * @return $this
     */
    public function addActiveFilter($isActive = true)
    {
        $this->addFieldToFilter('is_active', $isActive ? 1 : 0);
        return $this;
    }

    /**
     * Order by sort order
     *
     * @return $this
     */
    public function orderBySortOrder()
    {
        $this->setOrder('sort_order', 'ASC');
        return $this;
    }

    /**
     * Order by name
     *
     * @return $this
     */
    public function orderByName()
    {
        $this->setOrder('name', 'ASC');
        return $this;
    }
}
