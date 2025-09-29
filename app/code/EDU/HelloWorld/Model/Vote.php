<?php

namespace EDU\HelloWorld\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;

class Vote extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'question_vote';
    const VOTEABLE_TYPE_QUESTION = 'question';
    const VOTEABLE_TYPE_ANSWER = 'answer';
    const VOTE_TYPE_HELPFUL = 'helpful';
    const VOTE_TYPE_NOT_HELPFUL = 'not_helpful';

    protected $_cacheTag = 'question_vote';
    protected $_eventPrefix = 'question_vote';

    protected function _construct()
    {
        $this->_init(\EDU\HelloWorld\Model\ResourceModel\Vote::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getVoteId()
    {
        return $this->getData('vote_id');
    }

    public function setVoteId($voteId)
    {
        return $this->setData('vote_id', $voteId);
    }

    public function getVoteableId()
    {
        return $this->getData('voteable_id');
    }

    public function setVoteableId($voteableId)
    {
        return $this->setData('voteable_id', $voteableId);
    }

    public function getVoteableType()
    {
        return $this->getData('voteable_type');
    }

    public function setVoteableType($voteableType)
    {
        return $this->setData('voteable_type', $voteableType);
    }

    public function getCustomerEmail()
    {
        return $this->getData('customer_email');
    }

    public function setCustomerEmail($customerEmail)
    {
        return $this->setData('customer_email', $customerEmail);
    }

    public function getIpAddress()
    {
        return $this->getData('ip_address');
    }

    public function setIpAddress($ipAddress)
    {
        return $this->setData('ip_address', $ipAddress);
    }

    public function getVoteType()
    {
        return $this->getData('vote_type');
    }

    public function setVoteType($voteType)
    {
        return $this->setData('vote_type', $voteType);
    }

    public function getCreatedAt()
    {
        return $this->getData('created_at');
    }

    public function setCreatedAt($createdAt)
    {
        return $this->setData('created_at', $createdAt);
    }

    public function isHelpful()
    {
        return $this->getVoteType() === self::VOTE_TYPE_HELPFUL;
    }

    public function isNotHelpful()
    {
        return $this->getVoteType() === self::VOTE_TYPE_NOT_HELPFUL;
    }

    public function isForQuestion()
    {
        return $this->getVoteableType() === self::VOTEABLE_TYPE_QUESTION;
    }

    public function isForAnswer()
    {
        return $this->getVoteableType() === self::VOTEABLE_TYPE_ANSWER;
    }
}
