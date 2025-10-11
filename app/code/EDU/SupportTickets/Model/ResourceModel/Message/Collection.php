<?php

namespace EDU\SupportTickets\Model\ResourceModel\Message;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \EDU\SupportTickets\Model\Message::class,
            \EDU\SupportTickets\Model\ResourceModel\Message::class
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
     * Filter by sender
     *
     * @param int $senderId
     * @param string $senderType
     * @return $this
     */
    public function addSenderFilter($senderId, $senderType)
    {
        $this->addFieldToFilter('sender_id', $senderId)
             ->addFieldToFilter('sender_type', $senderType);
        return $this;
    }

    /**
     * Filter by sender type
     *
     * @param string $senderType
     * @return $this
     */
    public function addSenderTypeFilter($senderType)
    {
        $this->addFieldToFilter('sender_type', $senderType);
        return $this;
    }

    /**
     * Filter by internal messages
     *
     * @param bool $isInternal
     * @return $this
     */
    public function addInternalFilter($isInternal = true)
    {
        $this->addFieldToFilter('is_internal', $isInternal ? 1 : 0);
        return $this;
    }

    /**
     * Filter by public messages (non-internal)
     *
     * @return $this
     */
    public function addPublicFilter()
    {
        return $this->addInternalFilter(false);
    }

    /**
     * Order by created date (oldest first for conversation flow)
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
