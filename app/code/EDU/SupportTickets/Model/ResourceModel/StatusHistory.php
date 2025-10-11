<?php

namespace EDU\SupportTickets\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class StatusHistory extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('support_ticket_status_history', 'history_id');
    }
}
