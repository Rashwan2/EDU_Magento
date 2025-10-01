<?php

namespace EDU\HelloWorld\Model\ResourceModel\Vote;

use EDU\HelloWorld\Model\Vote as VoteModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'vote_id';
    protected $_eventPrefix = 'question_vote_collection';
    protected $_eventObject = 'vote_collection';

    protected function _construct()
    {
        $this->_init(
            VoteModel::class,
            \EDU\HelloWorld\Model\ResourceModel\Vote::class
        );
    }
}
