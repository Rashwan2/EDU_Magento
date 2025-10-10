<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreNews\Controller\Adminhtml\News;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use EDU\StoreNews\Api\NewsRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * News Delete Controller
 */
class Delete extends Action
{
    /**
     * @var NewsRepositoryInterface
     */
    protected $newsRepository;

    /**
     * @param Context $context
     * @param NewsRepositoryInterface $newsRepository
     */
    public function __construct(
        Context $context,
        NewsRepositoryInterface $newsRepository
    ) {
        parent::__construct($context);
        $this->newsRepository = $newsRepository;
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('news_id');

        if ($id) {
            try {
                $this->newsRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('You deleted the news article.'));
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while deleting the news article.'));
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a news article to delete.'));
        return $resultRedirect->setPath('*/*/');
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
