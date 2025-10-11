<?php

namespace EDU\SupportTickets\Model\ResourceModel\StatusHistory;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \EDU\SupportTickets\Model\StatusHistory::class,
            \EDU\SupportTickets\Model\ResourceModel\StatusHistory::class
        );
    }

    /**
     * Filter by ticket ID
     *
     * @param int $ticketId
     * @return $this
     */
    public function addTicketFilter($ticketId)
    {
        $this->addFieldToFilter('ticket_id', $ticketId);
        return $this;
    }

    /**
     * Filter by changed by admin
     *
     * @param int $changedBy
     * @return $this
     */
    public function addChangedByFilter($changedBy)
    {
        $this->addFieldToFilter('changed_by', $changedBy);
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
        $this->addFieldToFilter('new_status', $status);
        return $this;
    }

    /**
     * Order by created date (oldest first for timeline)
     *
     * @return $this
     */
    public function orderByCreatedAt()
    {
        $this->setOrder('created_at', 'ASC');
        return $this;
    }

    /**
     * Order by created date (newest first)
     *
     * @return $this
     */
    public function orderByCreatedAtDesc()
    {
        $this->setOrder('created_at', 'DESC');
        return $this;
    }
}
