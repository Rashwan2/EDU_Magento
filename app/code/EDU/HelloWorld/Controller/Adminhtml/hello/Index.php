<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace EDU\HelloWorld\Controller\Adminhtml\hello;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

/**
 * Catalog product controller
 *
 * @api
 *
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
class Index extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'EDU_HelloWorld::inventory';
    private PageFactory $pageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        PageFactory $pageFactory,

    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
    }

    public function execute()
    {
        return $this->pageFactory->create();
    }
}
