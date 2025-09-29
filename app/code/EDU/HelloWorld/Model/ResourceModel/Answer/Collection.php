<?php

namespace EDU\HelloWorld\Model\ResourceModel\Answer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'answer_id';
    protected $_eventPrefix = 'product_answer_collection';
    protected $_eventObject = 'answer_collection';

    protected function _construct()
    {
        $this->_init(
            \EDU\HelloWorld\Model\Answer::class,
            \EDU\HelloWorld\Model\ResourceModel\Answer::class
        );
    }

    public function addQuestionFilter($questionId)
    {
        $this->addFieldToFilter('question_id', $questionId);
        return $this;
    }

    public function addAdminAnswerFilter($isAdmin = true)
    {
        $this->addFieldToFilter('is_admin_answer', $isAdmin ? 1 : 0);
        return $this;
    }

    public function addCustomerAnswerFilter()
    {
        return $this->addAdminAnswerFilter(false);
    }

    public function addCustomerEmailFilter($email)
    {
        $this->addFieldToFilter('customer_name', ['like' => '%' . $email . '%']);
        return $this;
    }

    public function addHelpfulCountFilter($minCount = 0)
    {
        $this->addFieldToFilter('helpful_count', ['gteq' => $minCount]);
        return $this;
    }

    public function orderByAdminFirst()
    {
        $this->setOrder('is_admin_answer', 'DESC');
        $this->setOrder('created_at', 'ASC');
        return $this;
    }

    public function orderByHelpfulCount($direction = 'DESC')
    {
        $this->setOrder('helpful_count', $direction);
        return $this;
    }

    public function orderByCreatedAt($direction = 'ASC')
    {
        $this->setOrder('created_at', $direction);
        return $this;
    }

    public function getAnswersWithVotes()
    {
        $this->getSelect()
            ->joinLeft(
                ['vote' => $this->getTable('question_vote')],
                'main_table.answer_id = vote.voteable_id AND vote.voteable_type = "answer"',
                ['helpful_votes' => 'SUM(CASE WHEN vote.vote_type = "helpful" THEN 1 ELSE 0 END)']
            )
            ->group('main_table.answer_id');

        return $this;
    }
}
