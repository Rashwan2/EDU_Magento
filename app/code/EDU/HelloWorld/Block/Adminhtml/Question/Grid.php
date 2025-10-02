<?php

namespace EDU\HelloWorld\Block\Adminhtml\Question;

use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data;
use EDU\HelloWorld\Model\ResourceModel\Question\CollectionFactory;
use EDU\HelloWorld\Model\Question;

class Grid extends Extended
{
    protected $collectionFactory;

    public function __construct(
        Context $context,
        Data $backendHelper,
        CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('questionGrid');
        $this->setDefaultSort('question_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = $this->collectionFactory->create();
        $collection->getSelect()->joinLeft(
            ['product' => 'catalog_product_entity_varchar'],
            'main_table.product_id = product.entity_id AND product.attribute_id = 73',
            ['product_name' => 'product.value']
        );
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'question_id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'question_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );

        $this->addColumn(
            'product_name',
            [
                'header' => __('Product'),
                'index' => 'product_name',
                'type' => 'text',
                'truncate' => 50,
                'escape' => true,
            ]
        );

        $this->addColumn(
            'question_text',
            [
                'header' => __('Question'),
                'index' => 'question_text',
                'type' => 'text',
                'truncate' => 100,
                'escape' => true,
            ]
        );

        $this->addColumn(
            'customer_name',
            [
                'header' => __('Customer'),
                'index' => 'customer_name',
                'type' => 'text',
            ]
        );

        $this->addColumn(
            'customer_email',
            [
                'header' => __('Email'),
                'index' => 'customer_email',
                'type' => 'text',
            ]
        );

        $this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'index' => 'status',
                'type' => 'options',
                'options' => [
                    Question::STATUS_PENDING => __('Pending'),
                    Question::STATUS_APPROVED => __('Approved'),
                    Question::STATUS_REJECTED => __('Rejected'),
                ],
                'frame_callback' => [$this, 'decorateStatus'],
            ]
        );

        $this->addColumn(
            'answered_count',
            [
                'header' => __('Answers'),
                'index' => 'answered_count',
                'type' => 'number',
                'width' => '50px',
            ]
        );

        $this->addColumn(
            'created_at',
            [
                'header' => __('Created'),
                'index' => 'created_at',
                'type' => 'datetime',
                'width' => '100px',
            ]
        );

        $this->addColumn(
            'action',
            [
                'header' => __('Action'),
                'type' => 'action',
                'getter' => 'getQuestionId',
                'actions' => [
                    [
                        'caption' => __('Approve'),
                        'url' => ['base' => '*/*/approve'],
                        'field' => 'question_id',
                        'confirm' => __('Are you sure you want to approve this question?'),
                    ],
                    [
                        'caption' => __('Reject'),
                        'url' => ['base' => '*/*/reject'],
                        'field' => 'question_id',
                        'confirm' => __('Are you sure you want to reject this question?'),
                    ],
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action',
            ]
        );

        return parent::_prepareColumns();
    }

    public function decorateStatus($value, $row, $column, $isExport)
    {
        $class = '';
        switch ($value) {
            case Question::STATUS_PENDING:
                $class = 'grid-severity-minor';
                break;
            case Question::STATUS_APPROVED:
                $class = 'grid-severity-notice';
                break;
            case Question::STATUS_REJECTED:
                $class = 'grid-severity-critical';
                break;
        }
        return '<span class="' . $class . '"><span>' . $value . '</span></span>';
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('question_id');
        $this->getMassactionBlock()->setFormFieldName('question_ids');

        $this->getMassactionBlock()->addItem(
            'approve',
            [
                'label' => __('Approve'),
                'url' => $this->getUrl('*/*/massApprove'),
                'confirm' => __('Are you sure you want to approve selected questions?'),
            ]
        );

        $this->getMassactionBlock()->addItem(
            'reject',
            [
                'label' => __('Reject'),
                'url' => $this->getUrl('*/*/massReject'),
                'confirm' => __('Are you sure you want to reject selected questions?'),
            ]
        );

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('*/*/massDelete'),
                'confirm' => __('Are you sure you want to delete selected questions?'),
            ]
        );

        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', ['_current' => true]);
    }
}
