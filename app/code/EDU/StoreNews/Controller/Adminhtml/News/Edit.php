<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreNews\Controller\Adminhtml\News;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use EDU\StoreNews\Api\NewsRepositoryInterface;
use EDU\StoreNews\Api\Data\NewsInterfaceFactory;
use Magento\Framework\Registry;

/**
 * News Edit Controller
 */
class Edit extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var NewsRepositoryInterface
     */
    protected $newsRepository;

    /**
     * @var NewsInterfaceFactory
     */
    protected $newsFactory;

    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param NewsRepositoryInterface $newsRepository
     * @param NewsInterfaceFactory $newsFactory
     * @param Registry $coreRegistry
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        NewsRepositoryInterface $newsRepository,
        NewsInterfaceFactory $newsFactory,
        Registry $coreRegistry
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->newsRepository = $newsRepository;
        $this->newsFactory = $newsFactory;
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * Edit action
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('news_id');

        if ($id) {
            try {
                $model = $this->newsRepository->getById($id);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('This news article no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        } else {
            // Create a new news instance for new articles
            $model = $this->newsFactory->create();
        }

        $this->coreRegistry->register('storenews_news', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('EDU_StoreNews::news');
        $resultPage->getConfig()->getTitle()->prepend(
            $model->getId() ? __('Edit News Article') : __('New News Article')
        );
        return $resultPage;
    }

    /**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('EDU_StoreNews::news');
    }
}
