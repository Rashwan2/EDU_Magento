<?php

namespace EDU\HelloWorld\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\AbstractModel;

class Answer extends AbstractDb
{
    public function __construct(
        Context $context,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
    }

    protected function _construct()
    {
        $this->_init('product_answer', 'answer_id');
    }

    protected function _beforeSave(AbstractModel $object)
    {
        if ($object->isObjectNew() && !$object->hasCreatedAt()) {
            $object->setCreatedAt(new \DateTime());
        }
        return parent::_beforeSave($object);
    }

    public function getAnswersByQuestionId($questionId)
    {
        $select = $this->getConnection()->select()
            ->from($this->getMainTable())
            ->where('question_id = ?', $questionId)
            ->order('is_admin_answer DESC, created_at ASC');

        return $this->getConnection()->fetchAll($select);
    }

    public function getAnswerCountByQuestionId($questionId)
    {
        $select = $this->getConnection()->select()
            ->from($this->getMainTable(), ['COUNT(*)'])
            ->where('question_id = ?', $questionId);

        return (int) $this->getConnection()->fetchOne($select);
    }

    public function updateHelpfulCount($answerId, $count)
    {
        $this->getConnection()->update(
            $this->getMainTable(),
            ['helpful_count' => $count],
            ['answer_id = ?' => $answerId]
        );
    }
}
