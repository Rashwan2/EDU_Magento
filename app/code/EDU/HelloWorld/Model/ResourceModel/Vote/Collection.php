<?php

namespace EDU\HelloWorld\Model\ResourceModel\Vote;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'vote_id';
    protected $_eventPrefix = 'question_vote_collection';
    protected $_eventObject = 'vote_collection';

    protected function _construct()
    {
        $this->_init(
            \EDU\HelloWorld\Model\Vote::class,
            \EDU\HelloWorld\Model\ResourceModel\Vote::class
        );
    }

    public function addVoteableFilter($voteableId, $voteableType)
    {
        $this->addFieldToFilter('voteable_id', $voteableId);
        $this->addFieldToFilter('voteable_type', $voteableType);
        return $this;
    }

    public function addVoteTypeFilter($voteType)
    {
        $this->addFieldToFilter('vote_type', $voteType);
        return $this;
    }

    public function addHelpfulFilter()
    {
        return $this->addVoteTypeFilter(\EDU\HelloWorld\Model\Vote::VOTE_TYPE_HELPFUL);
    }

    public function addNotHelpfulFilter()
    {
        return $this->addVoteTypeFilter(\EDU\HelloWorld\Model\Vote::VOTE_TYPE_NOT_HELPFUL);
    }

    public function addCustomerEmailFilter($email)
    {
        $this->addFieldToFilter('customer_email', $email);
        return $this;
    }

    public function addIpAddressFilter($ipAddress)
    {
        $this->addFieldToFilter('ip_address', $ipAddress);
        return $this;
    }

    public function addDateRangeFilter($from, $to)
    {
        $this->addFieldToFilter('created_at', ['from' => $from, 'to' => $to]);
        return $this;
    }

    public function orderByCreatedAt($direction = 'DESC')
    {
        $this->setOrder('created_at', $direction);
        return $this;
    }

    public function getVoteStats()
    {
        $this->getSelect()
            ->columns([
                'total_votes' => 'COUNT(*)',
                'helpful_votes' => 'SUM(CASE WHEN vote_type = "helpful" THEN 1 ELSE 0 END)',
                'not_helpful_votes' => 'SUM(CASE WHEN vote_type = "not_helpful" THEN 1 ELSE 0 END)'
            ])
            ->group('voteable_id');

        return $this;
    }
}
