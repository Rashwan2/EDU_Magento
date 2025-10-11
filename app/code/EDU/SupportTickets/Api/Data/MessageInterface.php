<?php

namespace EDU\SupportTickets\Api\Data;

interface MessageInterface
{
    const MESSAGE_ID = 'message_id';
    const TICKET_ID = 'ticket_id';
    const SENDER_ID = 'sender_id';
    const SENDER_TYPE = 'sender_type';
    const SENDER_NAME = 'sender_name';
    const SENDER_EMAIL = 'sender_email';
    const MESSAGE = 'message';
    const IS_INTERNAL = 'is_internal';
    const ATTACHMENT = 'attachment';
    const CREATED_AT = 'created_at';

    // Sender type constants
    const SENDER_TYPE_CUSTOMER = 'customer';
    const SENDER_TYPE_ADMIN = 'admin';

    /**
     * Get message ID
     *
     * @return int|null
     */
    public function getMessageId();

    /**
     * Set message ID
     *
     * @param int $messageId
     * @return $this
     */
    public function setMessageId($messageId);

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
     * Get sender ID
     *
     * @return int|null
     */
    public function getSenderId();

    /**
     * Set sender ID
     *
     * @param int $senderId
     * @return $this
     */
    public function setSenderId($senderId);

    /**
     * Get sender type
     *
     * @return string|null
     */
    public function getSenderType();

    /**
     * Set sender type
     *
     * @param string $senderType
     * @return $this
     */
    public function setSenderType($senderType);

    /**
     * Get sender name
     *
     * @return string|null
     */
    public function getSenderName();

    /**
     * Set sender name
     *
     * @param string $senderName
     * @return $this
     */
    public function setSenderName($senderName);

    /**
     * Get sender email
     *
     * @return string|null
     */
    public function getSenderEmail();

    /**
     * Set sender email
     *
     * @param string $senderEmail
     * @return $this
     */
    public function setSenderEmail($senderEmail);

    /**
     * Get message
     *
     * @return string|null
     */
    public function getMessage();

    /**
     * Set message
     *
     * @param string $message
     * @return $this
     */
    public function setMessage($message);

    /**
     * Get is internal
     *
     * @return bool|null
     */
    public function getIsInternal();

    /**
     * Set is internal
     *
     * @param bool $isInternal
     * @return $this
     */
    public function setIsInternal($isInternal);

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
