<?php

namespace EDU\SupportTickets\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use EDU\SupportTickets\Api\Data\MessageInterface;

class Message extends AbstractModel implements MessageInterface, IdentityInterface
{
    const CACHE_TAG = 'support_ticket_message';

    protected $_cacheTag = 'support_ticket_message';
    protected $_eventPrefix = 'support_ticket_message';

    protected function _construct()
    {
        $this->_init(\EDU\SupportTickets\Model\ResourceModel\Message::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getMessageId()
    {
        return $this->getData(self::MESSAGE_ID);
    }

    public function setMessageId($messageId)
    {
        return $this->setData(self::MESSAGE_ID, $messageId);
    }

    public function getTicketId()
    {
        return $this->getData(self::TICKET_ID);
    }

    public function setTicketId($ticketId)
    {
        return $this->setData(self::TICKET_ID, $ticketId);
    }

    public function getSenderId()
    {
        return $this->getData(self::SENDER_ID);
    }

    public function setSenderId($senderId)
    {
        return $this->setData(self::SENDER_ID, $senderId);
    }

    public function getSenderType()
    {
        return $this->getData(self::SENDER_TYPE);
    }

    public function setSenderType($senderType)
    {
        return $this->setData(self::SENDER_TYPE, $senderType);
    }

    public function getSenderName()
    {
        return $this->getData(self::SENDER_NAME);
    }

    public function setSenderName($senderName)
    {
        return $this->setData(self::SENDER_NAME, $senderName);
    }

    public function getSenderEmail()
    {
        return $this->getData(self::SENDER_EMAIL);
    }

    public function setSenderEmail($senderEmail)
    {
        return $this->setData(self::SENDER_EMAIL, $senderEmail);
    }

    public function getMessage()
    {
        return $this->getData(self::MESSAGE);
    }

    public function setMessage($message)
    {
        return $this->setData(self::MESSAGE, $message);
    }

    public function getIsInternal()
    {
        return $this->getData(self::IS_INTERNAL);
    }

    public function setIsInternal($isInternal)
    {
        return $this->setData(self::IS_INTERNAL, $isInternal);
    }

    public function getAttachment()
    {
        return $this->getData(self::ATTACHMENT);
    }

    public function setAttachment($attachment)
    {
        return $this->setData(self::ATTACHMENT, $attachment);
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
     * Check if message is from customer
     *
     * @return bool
     */
    public function isFromCustomer()
    {
        return $this->getSenderType() === self::SENDER_TYPE_CUSTOMER;
    }

    /**
     * Check if message is from admin
     *
     * @return bool
     */
    public function isFromAdmin()
    {
        return $this->getSenderType() === self::SENDER_TYPE_ADMIN;
    }

    /**
     * Check if message is internal
     *
     * @return bool
     */
    public function isInternal()
    {
        return (bool) $this->getIsInternal();
    }

    /**
     * Get sender type label
     *
     * @return string
     */
    public function getSenderTypeLabel()
    {
        $typeLabels = [
            self::SENDER_TYPE_CUSTOMER => 'Customer',
            self::SENDER_TYPE_ADMIN => 'Admin'
        ];

        return $typeLabels[$this->getSenderType()] ?? $this->getSenderType();
    }
}
