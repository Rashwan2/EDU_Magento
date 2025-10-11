<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreBlogs\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use EDU\StoreBlogs\Model\ResourceModel\Blog\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Blog Index Controller (Frontend)
 */
class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var CollectionFactory
     */
    protected $blogCollectionFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param CollectionFactory $blogCollectionFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        CollectionFactory $blogCollectionFactory,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->blogCollectionFactory = $blogCollectionFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
//        $resultPage->getConfig()->getTitle()->set(__('Store Blog & Announcements'));

        return $resultPage;
    }
}
