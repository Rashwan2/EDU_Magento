<?php

namespace EDU\SupportTickets\Api\Data;

interface TicketInterface
{
    const TICKET_ID = 'ticket_id';
    const TICKET_NUMBER = 'ticket_number';
    const CUSTOMER_ID = 'customer_id';
    const CUSTOMER_EMAIL = 'customer_email';
    const CUSTOMER_NAME = 'customer_name';
    const SUBJECT = 'subject';
    const DESCRIPTION = 'description';
    const STATUS = 'status';
    const PRIORITY_ID = 'priority_id';
    const CATEGORY_ID = 'category_id';
    const ASSIGNED_TO = 'assigned_to';
    const ORDER_NUMBER = 'order_number';
    const ATTACHMENT = 'attachment';
    const LAST_REPLY_AT = 'last_reply_at';
    const RESOLVED_AT = 'resolved_at';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Status constants
    const STATUS_OPEN = 'open';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_WAITING_CUSTOMER = 'waiting_customer';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_CLOSED = 'closed';

    /**
     * Get ticket ID
     *
     * @return int|null
     */
    public function getTicketId();

    /**
     * Set ticket ID
     *
     * @param int $ticketId
     * @return $this
     */
    public function setTicketId($ticketId);

    /**
     * Get ticket number
     *
     * @return string|null
     */
    public function getTicketNumber();

    /**
     * Set ticket number
     *
     * @param string $ticketNumber
     * @return $this
     */
    public function setTicketNumber($ticketNumber);

    /**
     * Get customer ID
     *
     * @return int|null
     */
    public function getCustomerId();

    /**
     * Set customer ID
     *
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId($customerId);

    /**
     * Get customer email
     *
     * @return string|null
     */
    public function getCustomerEmail();

    /**
     * Set customer email
     *
     * @param string $customerEmail
     * @return $this
     */
    public function setCustomerEmail($customerEmail);

    /**
     * Get customer name
     *
     * @return string|null
     */
    public function getCustomerName();

    /**
     * Set customer name
     *
     * @param string $customerName
     * @return $this
     */
    public function setCustomerName($customerName);

    /**
     * Get subject
     *
     * @return string|null
     */
    public function getSubject();

    /**
     * Set subject
     *
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject);

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
     * Get status
     *
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus($status);

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
     * Get assigned to
     *
     * @return int|null
     */
    public function getAssignedTo();

    /**
     * Set assigned to
     *
     * @param int $assignedTo
     * @return $this
     */
    public function setAssignedTo($assignedTo);

    /**
     * Get order number
     *
     * @return string|null
     */
    public function getOrderNumber();

    /**
     * Set order number
     *
     * @param string $orderNumber
     * @return $this
     */
    public function setOrderNumber($orderNumber);

    /**
     * Get attachment
     *
     * @return string|null
     */
    public function getAttachment();

    /**
     * Set attachment
     *
     * @param string $attachment
     * @return $this
     */
    public function setAttachment($attachment);

    /**
     * Get last reply at
     *
     * @return string|null
     */
    public function getLastReplyAt();

    /**
     * Set last reply at
     *
     * @param string $lastReplyAt
     * @return $this
     */
    public function setLastReplyAt($lastReplyAt);

    /**
     * Get resolved at
     *
     * @return string|null
     */
    public function getResolvedAt();

    /**
     * Set resolved at
     *
     * @param string $resolvedAt
     * @return $this
     */
    public function setResolvedAt($resolvedAt);

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
