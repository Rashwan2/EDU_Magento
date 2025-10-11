<?php
namespace EDU\SupportTicket\Model;

use Magento\Framework\Model\AbstractModel;
use EDU\SupportTicket\Api\Data\TicketInterface;

class Ticket extends AbstractModel implements TicketInterface
{
    const STATUS_OPEN = 'open';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_WAITING_CUSTOMER = 'waiting_customer';
    const STATUS_CLOSED = 'closed';

    protected function _construct()
    {
        $this->_init(\EDU\SupportTicket\Model\ResourceModel\Ticket::class);
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

    public function getAvailableStatuses()
    {
        return [
            self::STATUS_OPEN => __('Open'),
            self::STATUS_IN_PROGRESS => __('In Progress'),
            self::STATUS_WAITING_CUSTOMER => __('Waiting for Customer'),
            self::STATUS_CLOSED => __('Closed'),
        ];
    }
}

