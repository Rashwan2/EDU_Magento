<?php

namespace EDU\SupportTickets\Model\ResourceModel\Ticket;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \EDU\SupportTickets\Model\Ticket::class,
            \EDU\SupportTickets\Model\ResourceModel\Ticket::class
        );
    }

    /**
     * Filter by customer ID
     *
     * @param int $customerId
     * @return $this
     */
    public function addCustomerFilter($customerId)
    {
        $this->addFieldToFilter('customer_id', $customerId);
        return $this;
    }

    /**
     * Filter by status
     *
     * @param string $status
     * @return $this
     */
    public function addStatusFilter($status)
    {
        $this->addFieldToFilter('status', $status);
        return $this;
    }

    /**
     * Filter by priority ID
     *
     * @param int $priorityId
     * @return $this
     */
    public function addPriorityFilter($priorityId)
    {
        $this->addFieldToFilter('priority_id', $priorityId);
        return $this;
    }

    /**
     * Filter by category ID
     *
     * @param int $categoryId
     * @return $this
     */
    public function addCategoryFilter($categoryId)
    {
        $this->addFieldToFilter('category_id', $categoryId);
        return $this;
    }

    /**
     * Filter by assigned admin
     *
     * @param int $assignedTo
     * @return $this
     */
    public function addAssignedToFilter($assignedTo)
    {
        $this->addFieldToFilter('assigned_to', $assignedTo);
        return $this;
    }

    /**
     * Filter by unassigned tickets
     *
     * @return $this
     */
    public function addUnassignedFilter()
    {
        $this->addFieldToFilter('assigned_to', ['null' => true]);
        return $this;
    }

    /**
     * Filter by date range
     *
     * @param string $from
     * @param string $to
     * @return $this
     */
    public function addDateRangeFilter($from, $to)
    {
        $this->addFieldToFilter('created_at', [
            'from' => $from,
            'to' => $to
        ]);
        return $this;
    }

    /**
     * Order by created date (newest first)
     *
     * @return $this
     */
    public function orderByCreatedAt()
    {
        $this->setOrder('created_at', 'DESC');
        return $this;
    }

    /**
     * Order by priority (highest first)
     *
     * @return $this
     */
    public function orderByPriority()
    {
        $this->getSelect()->joinLeft(
            ['priority' => $this->getTable('support_ticket_priority')],
            'main_table.priority_id = priority.priority_id',
            ['priority_sort_order' => 'priority.sort_order']
        )->order('priority.sort_order ASC');
        return $this;
    }

    /**
     * Order by last reply date
     *
     * @return $this
     */
    public function orderByLastReply()
    {
        $this->setOrder('last_reply_at', 'DESC');
        return $this;
    }
}
