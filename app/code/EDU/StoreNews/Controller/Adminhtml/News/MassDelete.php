<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreNews\Controller\Adminhtml\News;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use EDU\StoreNews\Api\NewsRepositoryInterface;
use EDU\StoreNews\Model\ResourceModel\News\CollectionFactory;

/**
 * Mass Delete News Controller
 */
class MassDelete extends Action
{
    /**
     * @var NewsRepositoryInterface
     */
    protected $newsRepository;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param NewsRepositoryInterface $newsRepository
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        NewsRepositoryInterface $newsRepository,
        CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
        $this->newsRepository = $newsRepository;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Mass delete action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $newsIds = $this->getRequest()->getParam('news_ids');
        
        if (empty($newsIds) || !is_array($newsIds)) {
            $this->messageManager->addErrorMessage(__('Please select items to delete.'));
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            return $resultRedirect->setPath('*/*/');
        }
        
        $newsDeleted = 0;

        foreach ($newsIds as $newsId) {
            try {
                $news = $this->newsRepository->getById($newsId);
                $this->newsRepository->delete($news);
                $newsDeleted++;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Error deleting news ID %1: %2', $newsId, $e->getMessage()));
            }
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 news article(s) have been deleted.', $newsDeleted));

        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
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
