<?php

namespace EDU\SupportTickets\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use EDU\SupportTickets\Api\Data\StatusHistoryInterface;

class StatusHistory extends AbstractModel implements StatusHistoryInterface, IdentityInterface
{
    const CACHE_TAG = 'support_ticket_status_history';

    protected $_cacheTag = 'support_ticket_status_history';
    protected $_eventPrefix = 'support_ticket_status_history';

    protected function _construct()
    {
        $this->_init(\EDU\SupportTickets\Model\ResourceModel\StatusHistory::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getHistoryId()
    {
        return $this->getData(self::HISTORY_ID);
    }

    public function setHistoryId($historyId)
    {
        return $this->setData(self::HISTORY_ID, $historyId);
    }

    public function getTicketId()
    {
        return $this->getData(self::TICKET_ID);
    }

    public function setTicketId($ticketId)
    {
        return $this->setData(self::TICKET_ID, $ticketId);
    }

    public function getOldStatus()
    {
        return $this->getData(self::OLD_STATUS);
    }

    public function setOldStatus($oldStatus)
    {
        return $this->setData(self::OLD_STATUS, $oldStatus);
    }

    public function getNewStatus()
    {
        return $this->getData(self::NEW_STATUS);
    }

    public function setNewStatus($newStatus)
    {
        return $this->setData(self::NEW_STATUS, $newStatus);
    }

    public function getChangedBy()
    {
        return $this->getData(self::CHANGED_BY);
    }

    public function setChangedBy($changedBy)
    {
        return $this->setData(self::CHANGED_BY, $changedBy);
    }

    public function getComment()
    {
        return $this->getData(self::COMMENT);
    }

    public function setComment($comment)
    {
        return $this->setData(self::COMMENT, $comment);
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
     * Get status change description
     *
     * @return string
     */
    public function getStatusChangeDescription()
    {
        $oldStatus = $this->getOldStatus() ?? 'New';
        $newStatus = $this->getNewStatus();

        return sprintf('Status changed from "%s" to "%s"', $oldStatus, $newStatus);
    }

    /**
     * Check if this is the initial status
     *
     * @return bool
     */
    public function isInitialStatus()
    {
        return empty($this->getOldStatus());
    }
}
