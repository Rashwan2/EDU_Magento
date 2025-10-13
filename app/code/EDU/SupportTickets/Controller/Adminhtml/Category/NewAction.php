<?php

namespace EDU\SupportTickets\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use EDU\SupportTickets\Api\CategoryRepositoryInterface;
use EDU\SupportTickets\Api\PriorityRepositoryInterface;

class NewAction extends Action
{
    const ADMIN_RESOURCE = 'EDU_SupportTickets::categories';

    protected $pageFactory;
    protected $categoryRepository;
    protected $priorityRepository;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        CategoryRepositoryInterface $categoryRepository,
        PriorityRepositoryInterface $priorityRepository
    ) {
        $this->pageFactory = $pageFactory;
        $this->categoryRepository = $categoryRepository;
        $this->priorityRepository = $priorityRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('New Category Ticket'));

        return $resultPage;
    }
}
