<?php

namespace EDU\HelloWorld\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use EDU\HelloWorld\Api\Data\AnswerInterface;

class Answer extends AbstractModel implements AnswerInterface, IdentityInterface
{
    const CACHE_TAG = 'product_answer';

    protected $_cacheTag = 'product_answer';
    protected $_eventPrefix = 'product_answer';

    protected function _construct()
    {
        $this->_init(\EDU\HelloWorld\Model\ResourceModel\Answer::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getAnswerId()
    {
        return $this->getData(self::ANSWER_ID);
    }

    public function setAnswerId($answerId)
    {
        return $this->setData(self::ANSWER_ID, $answerId);
    }

    public function getQuestionId()
    {
        return $this->getData(self::QUESTION_ID);
    }

    public function setQuestionId($questionId)
    {
        return $this->setData(self::QUESTION_ID, $questionId);
    }

    public function getCustomerName()
    {
        return $this->getData(self::CUSTOMER_NAME);
    }

    public function setCustomerName($customerName)
    {
        return $this->setData(self::CUSTOMER_NAME, $customerName);
    }

    public function getAdminUserId()
    {
        return $this->getData(self::ADMIN_USER_ID);
    }

    public function setAdminUserId($adminUserId)
    {
        return $this->setData(self::ADMIN_USER_ID, $adminUserId);
    }

    public function getAnswerText()
    {
        return $this->getData(self::ANSWER_TEXT);
    }

    public function setAnswerText($answerText)
    {
        return $this->setData(self::ANSWER_TEXT, $answerText);
    }

    public function getIsAdminAnswer()
    {
        return $this->getData(self::IS_ADMIN_ANSWER);
    }

    public function setIsAdminAnswer($isAdminAnswer)
    {
        return $this->setData(self::IS_ADMIN_ANSWER, $isAdminAnswer);
    }

    public function getHelpfulCount()
    {
        return $this->getData(self::HELPFUL_COUNT);
    }

    public function setHelpfulCount($helpfulCount)
    {
        return $this->setData(self::HELPFUL_COUNT, $helpfulCount);
    }

    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    public function isAdminAnswer()
    {
        return (bool) $this->getIsAdminAnswer();
    }

    public function incrementHelpfulCount()
    {
        $currentCount = (int) $this->getHelpfulCount();
        return $this->setHelpfulCount($currentCount + 1);
    }

    public function decrementHelpfulCount()
    {
        $currentCount = (int) $this->getHelpfulCount();
        if ($currentCount > 0) {
            return $this->setHelpfulCount($currentCount - 1);
        }
        return $this;
    }
}
