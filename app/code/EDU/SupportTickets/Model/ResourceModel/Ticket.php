<?php

namespace EDU\SupportTickets\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Ticket extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('support_ticket', 'ticket_id');
    }
}
