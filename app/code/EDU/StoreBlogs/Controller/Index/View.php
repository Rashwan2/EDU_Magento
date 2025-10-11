<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreBlogs\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use EDU\StoreBlogs\Api\BlogRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Blog View Controller (Frontend)
 */
class View extends Action
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
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param BlogRepositoryInterface $blogRepository
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        BlogRepositoryInterface $blogRepository
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->blogRepository = $blogRepository;
    }

    /**
     * View action
     *
     * @return \Magento\Framework\View\Result\Page|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        
        if (!$id) {
            $this->messageManager->addErrorMessage(__('Blog article not found.'));
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        try {
            $blog = $this->blogRepository->getById($id);
            
            if (!$blog->isPublished()) {
                throw new NoSuchEntityException(__('Blog article not found.'));
            }

            /** @var \Magento\Framework\View\Result\Page $resultPage */
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set($blog->getTitle());
            
            return $resultPage;
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }
    }
}
