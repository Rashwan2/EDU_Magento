<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreNews\Block\Adminhtml\News;

use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data;
use EDU\StoreNews\Model\ResourceModel\News\CollectionFactory;

/**
 * News Grid Block
 */
class Grid extends Extended
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param Data $backendHelper
     * @param CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('newsGrid');
        $this->setDefaultSort('news_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setMassactionIdField('news_id');
        $this->setMassactionIdFilter('news_id');
    }

    /**
     * Prepare grid buttons
     *
     * @return $this
     */
    protected function _prepareButtons()
    {
        $this->addButton(
            'add',
            [
                'label' => __('Add New Article'),
                'onclick' => 'setLocation(\'' . $this->getUrl('*/*/newaction') . '\')',
                'class' => 'add primary'
            ]
        );
        return parent::_prepareButtons();
    }

    /**
     * Prepare mass actions
     *
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('news_id');
        $this->getMassactionBlock()->setFormFieldName('news_ids');
        $this->getMassactionBlock()->setUseSelectAll(true);

        $this->getMassactionBlock()->addItem(
            'mass_approve',
            [
                'label' => __('Mass Approve'),
                'url' => $this->getUrl('*/*/massApprove'),
                'confirm' => __('Are you sure you want to approve selected news articles?')
            ]
        );

        $this->getMassactionBlock()->addItem(
            'mass_archive',
            [
                'label' => __('Mass Archive'),
                'url' => $this->getUrl('*/*/massArchive'),
                'confirm' => __('Are you sure you want to archive selected news articles?')
            ]
        );

        $this->getMassactionBlock()->addItem(
            'mass_delete',
            [
                'label' => __('Mass Delete'),
                'url' => $this->getUrl('*/*/massDelete'),
                'confirm' => __('Are you sure you want to delete selected news articles?')
            ]
        );

        return $this;
    }

    /**
     * Prepare collection
     *
     * @return Extended
     */
    protected function _prepareCollection()
    {
        $collection = $this->collectionFactory->create();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare columns
     *
     * @return Extended
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'news_id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'news_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );

        $this->addColumn(
            'in_news',
            [
                'type' => 'checkbox',
                'name' => 'in_news',
                'align' => 'center',
                'index' => 'news_id',
                'header_css_class' => 'col-select',
                'column_css_class' => 'col-select',
            ]
        );

        $this->addColumn(
            'title',
            [
                'header' => __('Title'),
                'index' => 'title',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );

        $this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'index' => 'status',
                'type' => 'options',
                'options' => [
                    'draft' => __('Draft'),
                    'published' => __('Published'),
                    'archived' => __('Archived')
                ],
                'width' => '50px',
            ]
        );

        $this->addColumn(
            'published_at',
            [
                'header' => __('Published At'),
                'index' => 'published_at',
                'type' => 'datetime',
                'width' => '100px',
            ]
        );

        $this->addColumn(
            'created_at',
            [
                'header' => __('Created At'),
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
                'getter' => 'getNewsId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => ['base' => '*/*/edit'],
                        'field' => 'news_id',
                    ],
                    [
                        'caption' => __('Delete'),
                        'url' => ['base' => '*/*/delete'],
                        'field' => 'news_id',
                        'confirm' => __('Are you sure you want to delete this news article?'),
                    ],
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'news_id',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action',
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * Get grid URL
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', ['_current' => true]);
    }

    /**
     * Get row URL
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', ['news_id' => $row->getNewsId()]);
    }
}
