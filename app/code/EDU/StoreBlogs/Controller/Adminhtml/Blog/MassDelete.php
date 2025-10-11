<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreBlogs\Controller\Adminhtml\Blog;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use EDU\StoreBlogs\Api\BlogRepositoryInterface;
use EDU\StoreBlogs\Model\ResourceModel\Blog\CollectionFactory;

/**
 * Mass Delete Blog Controller
 */
class MassDelete extends Action
{
    /**
     * @var BlogRepositoryInterface
     */
    protected $blogRepository;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param BlogRepositoryInterface $blogRepository
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        BlogRepositoryInterface $blogRepository,
        CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
        $this->blogRepository = $blogRepository;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Mass delete action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $blogIds = $this->getRequest()->getParam('blog_ids');
        
        if (empty($blogIds) || !is_array($blogIds)) {
            $this->messageManager->addErrorMessage(__('Please select items to delete.'));
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            return $resultRedirect->setPath('*/*/');
        }
        
        $blogDeleted = 0;

        foreach ($blogIds as $blogId) {
            try {
                $blog = $this->blogRepository->getById($blogId);
                $this->blogRepository->delete($blog);
                $blogDeleted++;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Error deleting blog ID %1: %2', $blogId, $e->getMessage()));
            }
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 blog article(s) have been deleted.', $blogDeleted));

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
        return $this->_authorization->isAllowed('EDU_StoreBlogs::blogs');
    }
}
