<?php

namespace EDU\HelloWorld\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\AbstractModel;

class Question extends AbstractDb
{
    public function __construct(
        Context $context,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
    }

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

    public function loadByProductId($object, $productId)
    {
        $select = $this->getConnection()->select()
            ->from($this->getMainTable())
            ->where($this->getMainTable() . '.product_id = ?', $productId);

        $data = $this->getConnection()->fetchRow($select);

        if ($data) {
            $object->setData($data);
        }

        return $this;
    }

    public function getQuestionsByProductId($productId, $status = null)
    {
        $select = $this->getConnection()->select()
            ->from($this->getMainTable())
            ->where('product_id = ?', $productId)
            ->order('created_at DESC');

        if ($status) {
            $select->where('status = ?', $status);
        }

        return $this->getConnection()->fetchAll($select);
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
