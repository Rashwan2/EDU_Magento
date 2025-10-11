<?php

namespace EDU\SupportTickets\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use EDU\SupportTickets\Api\Data\TicketInterface;

class Ticket extends AbstractModel implements TicketInterface, IdentityInterface
{
    const CACHE_TAG = 'support_ticket';

    protected $_cacheTag = 'support_ticket';
    protected $_eventPrefix = 'support_ticket';

    protected function _construct()
    {
        $this->_init(\EDU\SupportTickets\Model\ResourceModel\Ticket::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getTicketId()
    {
        return $this->getData(self::TICKET_ID);
    }

    public function setTicketId($ticketId)
    {
        return $this->setData(self::TICKET_ID, $ticketId);
    }

    public function getTicketNumber()
    {
        return $this->getData(self::TICKET_NUMBER);
    }

    public function setTicketNumber($ticketNumber)
    {
        return $this->setData(self::TICKET_NUMBER, $ticketNumber);
    }

    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    public function getCustomerEmail()
    {
        return $this->getData(self::CUSTOMER_EMAIL);
    }

    public function setCustomerEmail($customerEmail)
    {
        return $this->setData(self::CUSTOMER_EMAIL, $customerEmail);
    }

    public function getCustomerName()
    {
        return $this->getData(self::CUSTOMER_NAME);
    }

    public function setCustomerName($customerName)
    {
        return $this->setData(self::CUSTOMER_NAME, $customerName);
    }

    public function getSubject()
    {
        return $this->getData(self::SUBJECT);
    }

    public function setSubject($subject)
    {
        return $this->setData(self::SUBJECT, $subject);
    }

    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    public function getPriorityId()
    {
        return $this->getData(self::PRIORITY_ID);
    }

    public function setPriorityId($priorityId)
    {
        return $this->setData(self::PRIORITY_ID, $priorityId);
    }

    public function getCategoryId()
    {
        return $this->getData(self::CATEGORY_ID);
    }

    public function setCategoryId($categoryId)
    {
        return $this->setData(self::CATEGORY_ID, $categoryId);
    }

    public function getAssignedTo()
    {
        return $this->getData(self::ASSIGNED_TO);
    }

    public function setAssignedTo($assignedTo)
    {
        return $this->setData(self::ASSIGNED_TO, $assignedTo);
    }

    public function getOrderNumber()
    {
        return $this->getData(self::ORDER_NUMBER);
    }

    public function setOrderNumber($orderNumber)
    {
        return $this->setData(self::ORDER_NUMBER, $orderNumber);
    }

    public function getAttachment()
    {
        return $this->getData(self::ATTACHMENT);
    }

    public function setAttachment($attachment)
    {
        return $this->setData(self::ATTACHMENT, $attachment);
    }

    public function getLastReplyAt()
    {
        return $this->getData(self::LAST_REPLY_AT);
    }

    public function setLastReplyAt($lastReplyAt)
    {
        return $this->setData(self::LAST_REPLY_AT, $lastReplyAt);
    }

    public function getResolvedAt()
    {
        return $this->getData(self::RESOLVED_AT);
    }

    public function setResolvedAt($resolvedAt)
    {
        return $this->setData(self::RESOLVED_AT, $resolvedAt);
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
     * Check if ticket is open
     *
     * @return bool
     */
    public function isOpen()
    {
        return $this->getStatus() === self::STATUS_OPEN;
    }

    /**
     * Check if ticket is resolved
     *
     * @return bool
     */
    public function isResolved()
    {
        return $this->getStatus() === self::STATUS_RESOLVED;
    }

    /**
     * Check if ticket is closed
     *
     * @return bool
     */
    public function isClosed()
    {
        return $this->getStatus() === self::STATUS_CLOSED;
    }

    /**
     * Check if ticket is assigned
     *
     * @return bool
     */
    public function isAssigned()
    {
        return !empty($this->getAssignedTo());
    }

    /**
     * Get status label
     *
     * @return string
     */
    public function getStatusLabel()
    {
        $statusLabels = [
            self::STATUS_OPEN => 'Open',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_WAITING_CUSTOMER => 'Waiting for Customer',
            self::STATUS_RESOLVED => 'Resolved',
            self::STATUS_CLOSED => 'Closed'
        ];

        return $statusLabels[$this->getStatus()] ?? $this->getStatus();
    }
}
