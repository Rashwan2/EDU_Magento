<?php

namespace EDU\SupportTickets\Block\Ticket;

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

    public function __construct(
        Context $context,
        ResolverInterface $localeResolver,
        array $data = []
    ) {
        $this->localeResolver = $localeResolver;
        parent::__construct($context, $data);
    }

    public function setTicket($ticket)
    {
        $this->ticket = $ticket;
        return $this;
    }

    public function getTicket()
    {
        return $this->ticket;
    }

    public function setMessages($messages)
    {
        $this->messages = $messages;
        return $this;
    }

    public function getMessages()
    {
        return $this->messages;
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

    public function formatDate($date)
    {
        return $this->_localeDate->formatDateTime(
            new \DateTime($date),
            \IntlDateFormatter::MEDIUM,
            \IntlDateFormatter::SHORT,
            $this->localeResolver->getLocale()
        );
    }

    public function canAddMessage()
    {
        return $this->ticket && 
               $this->ticket->getStatus() !== TicketInterface::STATUS_CLOSED;
    }
}
