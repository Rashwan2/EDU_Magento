<?php

namespace EDU\SupportTickets\Block\Ticket;

use EDU\SupportTickets\Model\ResourceModel\Message\CollectionFactory as MessageCollectionFactory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Locale\ResolverInterface;
use EDU\SupportTickets\Api\Data\TicketInterface;
use EDU\SupportTickets\Api\Data\MessageInterface;

class ViewBlock extends Template
{
    protected $ticket;
    protected $messages = [];
    protected $localeResolver;
    protected $messageCollectionFactory;

    public function __construct(
        Context $context,
        ResolverInterface $localeResolver,
        MessageCollectionFactory $messageCollectionFactory,
        array $data = []
    ) {
        $this->localeResolver = $localeResolver;
        $this->messageCollectionFactory = $messageCollectionFactory;
        $this->ticket = $this->getData('ticket') ?: [];
        parent::__construct($context, $data);
    }


    public function getTicket()
    {
        return $this->getData('ticket') ?: [];
    }

    public function getMessages()
    {
        if (!$this->messages && $this->getTicket()) {
            $collection = $this->messageCollectionFactory->create();
            $collection->addFieldToFilter('ticket_id', $this->getTicket()->getTicketId());
            $collection->setOrder('created_at', 'ASC');
            $this->messages = $collection->getItems();
        }
        return $this->messages ?? [];
    }

    public function getStatusLabel($status)
    {
        $statusLabels = [
            TicketInterface::STATUS_OPEN => 'Open',
            TicketInterface::STATUS_IN_PROGRESS => 'In Progress',
            TicketInterface::STATUS_WAITING_CUSTOMER => 'Waiting for Customer',
            TicketInterface::STATUS_RESOLVED => 'Resolved',
            TicketInterface::STATUS_CLOSED => 'Closed'
        ];

        return $statusLabels[$status] ?? $status;
    }

    public function getStatusClass($status)
    {
        $statusClasses = [
            TicketInterface::STATUS_OPEN => 'status-open',
            TicketInterface::STATUS_IN_PROGRESS => 'status-in-progress',
            TicketInterface::STATUS_WAITING_CUSTOMER => 'status-waiting',
            TicketInterface::STATUS_RESOLVED => 'status-resolved',
            TicketInterface::STATUS_CLOSED => 'status-closed'
        ];

        return $statusClasses[$status] ?? 'status-default';
    }

    public function getSenderTypeLabel($senderType)
    {
        $typeLabels = [
            MessageInterface::SENDER_TYPE_CUSTOMER => 'Customer',
            MessageInterface::SENDER_TYPE_ADMIN => 'Admin'
        ];

        return $typeLabels[$senderType] ?? $senderType;
    }

    /**
     * Format published date
     *
     * @param string $date
     * @return string
     */
    public function formatDate($date = null, $format = \IntlDateFormatter::MEDIUM, $showTime = true, $timezone = null)
    {
        if (!$date) {
            return '';
        }

        return parent::formatDate($date, $format, $showTime, $timezone);
    }


    public function canAddMessage()
    {
        return $this->ticket &&
               $this->ticket->getStatus() !== TicketInterface::STATUS_CLOSED;
    }
}
