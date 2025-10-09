<?php

namespace EDU\QuestionHub\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\AbstractModel;

class Question extends AbstractDb
{
    
    protected function _construct()
    {
        $this->_init('product_question', 'question_id');
    }

    protected function _beforeSave(AbstractModel $object)
    {
        if ($object->isObjectNew() && !$object->hasCreatedAt()) {
            $object->setCreatedAt(new \DateTime());
        }
        $object->setUpdatedAt(new \DateTime());
        return parent::_beforeSave($object);
    }

    public function updateAnsweredCount($questionId, $count)
    {
        $this->getConnection()->update(
            $this->getMainTable(),
            ['answered_count' => $count],
            ['question_id = ?' => $questionId]
        );
    }
}
