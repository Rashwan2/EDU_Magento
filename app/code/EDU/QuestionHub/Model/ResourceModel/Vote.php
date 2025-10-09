<?php

namespace EDU\QuestionHub\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\AbstractModel;

class Vote extends AbstractDb
{


    protected function _construct()
    {
        $this->_init('question_vote', 'vote_id');
    }

    protected function _beforeSave(AbstractModel $object)
    {
        if ($object->isObjectNew() && !$object->hasCreatedAt()) {
            $object->setCreatedAt(new \DateTime());
        }
        return parent::_beforeSave($object);
    }

    public function hasVoted($voteableId, $voteableType, $customerEmail = null, $ipAddress = null)
    {
        $select = $this->getConnection()->select()
            ->from($this->getMainTable(), ['vote_id'])
            ->where('voteable_id = ?', $voteableId)
            ->where('voteable_type = ?', $voteableType);

        if ($customerEmail) {
            $select->where('customer_email = ?', $customerEmail);
        } elseif ($ipAddress) {
            $select->where('ip_address = ?', $ipAddress);
        }

        return (bool) $this->getConnection()->fetchOne($select);
    }
}
