<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreBlogs\Controller\Adminhtml\Blog;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use EDU\StoreBlogs\Api\BlogRepositoryInterface;
use EDU\StoreBlogs\Api\Data\BlogInterfaceFactory;
use Magento\Framework\Registry;

/**
 * Blog Edit Controller
 */
class Edit extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var BlogRepositoryInterface
     */
    protected $blogRepository;

    /**
     * @var BlogInterfaceFactory
     */
    protected $blogFactory;

    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param BlogRepositoryInterface $blogRepository
     * @param BlogInterfaceFactory $blogFactory
     * @param Registry $coreRegistry
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        BlogRepositoryInterface $blogRepository,
        BlogInterfaceFactory $blogFactory,
        Registry $coreRegistry
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->blogRepository = $blogRepository;
        $this->blogFactory = $blogFactory;
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * Edit action
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('blog_id');

        if ($id) {
            try {
                $model = $this->blogRepository->getById($id);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('This blog article no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        } else {
            // Create a new blog instance for new articles
            $model = $this->blogFactory->create();
        }

        $this->coreRegistry->register('storeblogs_blog', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('EDU_StoreBlogs::blogs');
        $resultPage->getConfig()->getTitle()->prepend(
            $model->getId() ? __('Edit Blog Article') : __('New Blog Article')
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
        return $this->_authorization->isAllowed('EDU_StoreBlogs::blogs');
    }
}
