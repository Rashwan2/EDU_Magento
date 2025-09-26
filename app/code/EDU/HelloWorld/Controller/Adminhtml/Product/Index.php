<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\HelloWorld\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Reviews admin controller.
 */
class Index extends Action
{
    /**
     * Authorization resource
     */
    public const ADMIN_RESOURCE = 'EDU_HelloWorld::helloWorld';
    private PageFactory $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        // Return a page result to render the layout
        return $this->resultPageFactory->create();
    }
}
