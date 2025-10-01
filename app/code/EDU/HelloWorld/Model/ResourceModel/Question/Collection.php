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


    public function orderByCreatedAt($direction = 'DESC')
    {
        $this->setOrder('created_at', $direction);
        return $this;
    }

}
