<?php

namespace EDU\QuestionHub\Model\ResourceModel\Question;

use EDU\QuestionHub\Model\Question;
use EDU\QuestionHub\Model\ResourceModel\Question as QuestionResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'question_id';
    protected $_eventPrefix = 'product_question_collection';
    protected $_eventObject = 'question_collection';

    protected function _construct()
    {
        $this->_init(
            Question::class,
            QuestionResourceModel::class
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


    public function orderByCreatedAt($direction = 'DESC')
    {
        $this->setOrder('created_at', $direction);
        return $this;
    }

}
