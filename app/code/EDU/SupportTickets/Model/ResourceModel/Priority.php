<?php

namespace EDU\SupportTickets\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Priority extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('support_ticket_priority', 'priority_id');
    }
}
