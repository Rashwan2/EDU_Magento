<?php

namespace EDU\HelloWorld\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\AbstractModel;

class Vote extends AbstractDb
{
    public function __construct(
        Context $context,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
    }

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

    public function getVoteCount($voteableId, $voteableType, $voteType = null)
    {
        $select = $this->getConnection()->select()
            ->from($this->getMainTable(), ['COUNT(*)'])
            ->where('voteable_id = ?', $voteableId)
            ->where('voteable_type = ?', $voteableType);

        if ($voteType) {
            $select->where('vote_type = ?', $voteType);
        }

        return (int) $this->getConnection()->fetchOne($select);
    }

    public function getVotesByVoteable($voteableId, $voteableType)
    {
        $select = $this->getConnection()->select()
            ->from($this->getMainTable())
            ->where('voteable_id = ?', $voteableId)
            ->where('voteable_type = ?', $voteableType)
            ->order('created_at DESC');

        return $this->getConnection()->fetchAll($select);
    }
}
