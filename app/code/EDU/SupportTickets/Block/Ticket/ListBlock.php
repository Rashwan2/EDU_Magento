<?php

namespace EDU\SupportTickets\Block\Ticket;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Locale\ResolverInterface;
use EDU\SupportTickets\Api\Data\TicketInterface;

class ListBlock extends Template
{
    protected $tickets = [];
    protected $localeResolver;

    public function __construct(
        Context $context,
        ResolverInterface $localeResolver,
        array $data = []
    ) {
        $this->localeResolver = $localeResolver;
        parent::__construct($context, $data);
    }

    public function setTickets($tickets)
    {
        $this->tickets = $tickets;
        return $this;
    }

    public function getTickets()
    {
        return $this->tickets;
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

    /**
     * Format published date
     *
     * @param string $date
     * @return string
     */
    public function formatDate($date = null, $format = \IntlDateFormatter::MEDIUM, $showTime = false, $timezone = null)
    {
        if (!$date) {
            return '';
        }

        return parent::formatDate($date, $format, $showTime, $timezone);
    }

}
