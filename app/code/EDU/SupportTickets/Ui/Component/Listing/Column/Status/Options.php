<?php

namespace EDU\SupportTickets\Ui\Component\Listing\Column\Status;

use Magento\Framework\Data\OptionSourceInterface;
use EDU\SupportTickets\Api\Data\TicketInterface;

class Options implements OptionSourceInterface
{
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => TicketInterface::STATUS_OPEN, 'label' => __('Open')],
            ['value' => TicketInterface::STATUS_IN_PROGRESS, 'label' => __('In Progress')],
            ['value' => TicketInterface::STATUS_WAITING_CUSTOMER, 'label' => __('Waiting for Customer')],
            ['value' => TicketInterface::STATUS_RESOLVED, 'label' => __('Resolved')],
            ['value' => TicketInterface::STATUS_CLOSED, 'label' => __('Closed')]
        ];
    }
}