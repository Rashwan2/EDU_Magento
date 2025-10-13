<?php

namespace EDU\SupportTickets\Ui\Component\Listing\Column\Status;

use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'open', 'label' => __('Open')],
            ['value' => 'in_progress', 'label' => __('In Progress')],
            ['value' => 'waiting_customer', 'label' => __('Waiting for Customer')],
            ['value' => 'resolved', 'label' => __('Resolved')],
            ['value' => 'closed', 'label' => __('Closed')]
        ];
    }
}
