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
use EDU\StoreBlogs\Api\Data\BlogInterfaceFactory;
use EDU\StoreBlogs\Model\ResourceModel\Blog\CollectionFactory;

/**
 * Mass Approve Blog Controller
 */
class MassApprove extends Action
{
    /**
     * @var BlogRepositoryInterface
     */
    protected $blogRepository;

    /**
     * @var BlogInterfaceFactory
     */
    protected $blogFactory;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param BlogRepositoryInterface $blogRepository
     * @param BlogInterfaceFactory $blogFactory
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        BlogRepositoryInterface $blogRepository,
        BlogInterfaceFactory $blogFactory,
        CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
        $this->blogRepository = $blogRepository;
        $this->blogFactory = $blogFactory;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Mass approve action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $blogIds = $this->getRequest()->getParam('blog_ids');
        
        if (empty($blogIds) || !is_array($blogIds)) {
            $this->messageManager->addErrorMessage(__('Please select items to approve.'));
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            return $resultRedirect->setPath('*/*/');
        }
        
        $blogApproved = 0;

        foreach ($blogIds as $blogId) {
            try {
                $blog = $this->blogRepository->getById($blogId);
                $blog->setStatus('published');
                if (!$blog->getPublishedAt()) {
                    $blog->setPublishedAt(date('Y-m-d H:i:s'));
                }
                $this->blogRepository->save($blog);
                $blogApproved++;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Error approving blog ID %1: %2', $blogId, $e->getMessage()));
            }
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 blog article(s) have been approved.', $blogApproved));

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
