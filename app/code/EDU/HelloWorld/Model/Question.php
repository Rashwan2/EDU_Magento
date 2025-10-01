<?php

namespace EDU\HelloWorld\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use EDU\HelloWorld\Api\Data\QuestionInterface;

class Question extends AbstractModel implements QuestionInterface, IdentityInterface
{
    const CACHE_TAG = 'product_question';
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $_cacheTag = 'product_question';
    protected $_eventPrefix = 'product_question';

    protected function _construct()
    {
        $this->_init(\EDU\HelloWorld\Model\ResourceModel\Question::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getQuestionId()
    {
        return $this->getData(self::QUESTION_ID);
    }

    public function setQuestionId($questionId)
    {
        return $this->setData(self::QUESTION_ID, $questionId);
    }

    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    public function getCustomerName()
    {
        return $this->getData(self::CUSTOMER_NAME);
    }

    public function setCustomerName($customerName)
    {
        return $this->setData(self::CUSTOMER_NAME, $customerName);
    }

    public function getCustomerEmail()
    {
        return $this->getData(self::CUSTOMER_EMAIL);
    }

    public function setCustomerEmail($customerEmail)
    {
        return $this->setData(self::CUSTOMER_EMAIL, $customerEmail);
    }

    public function getQuestionText()
    {
        return $this->getData(self::QUESTION_TEXT);
    }

    public function setQuestionText($questionText)
    {
        return $this->setData(self::QUESTION_TEXT, $questionText);
    }

    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    public function getAnsweredCount()
    {
        return $this->getData(self::ANSWERED_COUNT);
    }

    public function setAnsweredCount($answeredCount)
    {
        return $this->setData(self::ANSWERED_COUNT, $answeredCount);
    }

    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }


    public function approve()
    {
        return $this->setStatus(self::STATUS_APPROVED);
    }

    public function reject()
    {
        return $this->setStatus(self::STATUS_REJECTED);
    }
}
