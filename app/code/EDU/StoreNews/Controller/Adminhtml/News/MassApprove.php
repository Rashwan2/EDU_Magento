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
use EDU\StoreNews\Api\Data\NewsInterfaceFactory;
use EDU\StoreNews\Model\ResourceModel\News\CollectionFactory;

/**
 * Mass Approve News Controller
 */
class MassApprove extends Action
{
    /**
     * @var NewsRepositoryInterface
     */
    protected $newsRepository;

    /**
     * @var NewsInterfaceFactory
     */
    protected $newsFactory;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param NewsRepositoryInterface $newsRepository
     * @param NewsInterfaceFactory $newsFactory
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        NewsRepositoryInterface $newsRepository,
        NewsInterfaceFactory $newsFactory,
        CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
        $this->newsRepository = $newsRepository;
        $this->newsFactory = $newsFactory;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Mass approve action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $newsIds = $this->getRequest()->getParam('news_ids');
        
        if (empty($newsIds) || !is_array($newsIds)) {
            $this->messageManager->addErrorMessage(__('Please select items to approve.'));
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            return $resultRedirect->setPath('*/*/');
        }
        
        $newsApproved = 0;

        foreach ($newsIds as $newsId) {
            try {
                $news = $this->newsRepository->getById($newsId);
                $news->setStatus('published');
                if (!$news->getPublishedAt()) {
                    $news->setPublishedAt(date('Y-m-d H:i:s'));
                }
                $this->newsRepository->save($news);
                $newsApproved++;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Error approving news ID %1: %2', $newsId, $e->getMessage()));
            }
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 news article(s) have been approved.', $newsApproved));

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
