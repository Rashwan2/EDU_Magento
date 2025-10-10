<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreNews\Block\Adminhtml\News;

use Magento\Backend\Block\Widget\Form\Container;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;

/**
 * News Edit Block
 */
class Edit extends Container
{
    /**
     * @var Registry
     */
    protected $coreRegistry = null;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize news edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'news_id';
        $this->_blockGroup = 'EDU_StoreNews';
        $this->_controller = 'adminhtml_news';

        parent::_construct();

        // Remove all default buttons to prevent duplicates
        $this->buttonList->remove('save');
        $this->buttonList->remove('saveandcontinue');
        $this->buttonList->remove('delete');
        $this->buttonList->remove('reset');
        $this->buttonList->remove('back');

    }

    /**
     * Retrieve text for header element depending on loaded news
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->coreRegistry->registry('storenews_news')->getId()) {
            return __("Edit News Article '%1'", $this->escapeHtml($this->coreRegistry->registry('storenews_news')->getTitle()));
        } else {
            return __('New News Article');
        }
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Get save URL
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save', ['_current' => true]);
    }

    /**
     * Get form key
     *
     * @return string
     */
    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }

    /**
     * Get core registry
     *
     * @return Registry
     */
    public function getCoreRegistry()
    {
        return $this->coreRegistry;
    }

    /**
     * Get available statuses
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        $news = $this->coreRegistry->registry('storenews_news');
        if ($news) {
            return $news->getAvailableStatuses();
        }
        
        // Return default statuses for new articles
        return [
            'draft' => __('Draft'),
            'published' => __('Published'),
            'archived' => __('Archived')
        ];
    }
}
