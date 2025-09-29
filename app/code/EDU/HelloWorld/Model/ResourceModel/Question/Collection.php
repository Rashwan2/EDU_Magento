<?php

namespace EDU\HelloWorld\Model\ResourceModel\Question;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'question_id';
    protected $_eventPrefix = 'product_question_collection';
    protected $_eventObject = 'question_collection';

    protected function _construct()
    {
        $this->_init(
            \EDU\HelloWorld\Model\Question::class,
            \EDU\HelloWorld\Model\ResourceModel\Question::class
        );
    }

    public function addProductFilter($productId)
    {
        $this->addFieldToFilter('product_id', $productId);
        return $this;
    }

    public function addStatusFilter($status)
    {
        $this->addFieldToFilter('status', $status);
        return $this;
    }

    public function addApprovedFilter()
    {
        return $this->addStatusFilter(\EDU\HelloWorld\Model\Question::STATUS_APPROVED);
    }

    public function addPendingFilter()
    {
        return $this->addStatusFilter(\EDU\HelloWorld\Model\Question::STATUS_PENDING);
    }

    public function addRejectedFilter()
    {
        return $this->addStatusFilter(\EDU\HelloWorld\Model\Question::STATUS_REJECTED);
    }

    public function addCustomerEmailFilter($email)
    {
        $this->addFieldToFilter('customer_email', $email);
        return $this;
    }

    public function addDateRangeFilter($from, $to)
    {
        $this->addFieldToFilter('created_at', ['from' => $from, 'to' => $to]);
        return $this;
    }

    public function addAnsweredFilter($answered = true)
    {
        if ($answered) {
            $this->addFieldToFilter('answered_count', ['gt' => 0]);
        } else {
            $this->addFieldToFilter('answered_count', 0);
        }
        return $this;
    }

    public function orderByCreatedAt($direction = 'DESC')
    {
        $this->setOrder('created_at', $direction);
        return $this;
    }

    public function orderByAnsweredCount($direction = 'DESC')
    {
        $this->setOrder('answered_count', $direction);
        return $this;
    }

    public function getQuestionsWithAnswers()
    {
        $this->getSelect()
            ->joinLeft(
                ['answer' => $this->getTable('product_answer')],
                'main_table.question_id = answer.question_id',
                ['answer_count' => 'COUNT(answer.answer_id)']
            )
            ->group('main_table.question_id');

        return $this;
    }
}
