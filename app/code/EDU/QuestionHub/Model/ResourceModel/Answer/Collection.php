<?php

namespace EDU\QuestionHub\Model\ResourceModel\Answer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'answer_id';
    protected $_eventPrefix = 'product_answer_collection';
    protected $_eventObject = 'answer_collection';

    protected function _construct()
    {
        $this->_init(
            \EDU\QuestionHub\Model\Answer::class,
            \EDU\QuestionHub\Model\ResourceModel\Answer::class
        );
    }

    public function addQuestionFilter($questionId)
    {
        $this->addFieldToFilter('question_id', $questionId);
        return $this;
    }


    public function orderByAdminFirst()
    {
        $this->setOrder('is_admin_answer', 'DESC');
        $this->setOrder('created_at', 'ASC');
        return $this;
    }


    public function orderByCreatedAt($direction = 'ASC')
    {
        $this->setOrder('created_at', $direction);
        return $this;
    }

}
