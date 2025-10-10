<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreNews\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use EDU\StoreNews\Api\NewsRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * News View Controller (Frontend)
 */
class View extends Action
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
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param NewsRepositoryInterface $newsRepository
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        NewsRepositoryInterface $newsRepository
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->newsRepository = $newsRepository;
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
            $this->messageManager->addErrorMessage(__('News article not found.'));
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        try {
            $news = $this->newsRepository->getById($id);
            
            if (!$news->isPublished()) {
                throw new NoSuchEntityException(__('News article not found.'));
            }

            /** @var \Magento\Framework\View\Result\Page $resultPage */
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set($news->getTitle());
            
            return $resultPage;
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }
    }
}
