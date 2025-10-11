<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreBlogs\Controller\Adminhtml\Blog;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use EDU\StoreBlogs\Api\BlogRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Blog Delete Controller
 */
class Delete extends Action
{
    /**
     * @var BlogRepositoryInterface
     */
    protected $blogRepository;

    /**
     * @param Context $context
     * @param BlogRepositoryInterface $blogRepository
     */
    public function __construct(
        Context $context,
        BlogRepositoryInterface $blogRepository
    ) {
        parent::__construct($context);
        $this->blogRepository = $blogRepository;
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
        $id = $this->getRequest()->getParam('blog_id');

        if ($id) {
            try {
                $this->blogRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('You deleted the blog article.'));
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while deleting the blog article.'));
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a blog article to delete.'));
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
