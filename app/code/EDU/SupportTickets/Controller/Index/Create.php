<?php

namespace EDU\SupportTickets\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session as CustomerSession;
use EDU\SupportTickets\Api\CategoryRepositoryInterface;
use EDU\SupportTickets\Api\PriorityRepositoryInterface;

class Create extends Action
{
    protected $pageFactory;
    protected $customerSession;
    protected $categoryRepository;
    protected $priorityRepository;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        CustomerSession $customerSession,
        CategoryRepositoryInterface $categoryRepository,
        PriorityRepositoryInterface $priorityRepository
    ) {
        $this->pageFactory = $pageFactory;
        $this->customerSession = $customerSession;
        $this->categoryRepository = $categoryRepository;
        $this->priorityRepository = $priorityRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->pageFactory->create();
//        $resultPage->getConfig()->getTitle()->set(__('Create Support Ticket'));

        // Get categories and priorities for form
        $categories = $this->categoryRepository->getActiveCategories();
        $priorities = $this->priorityRepository->getActivePriorities();

        $resultPage->getLayout()->getBlock('ticket.form')->setData('categories', $categories);
        $resultPage->getLayout()->getBlock('ticket.form')->setData('priorities', $priorities);

        return $resultPage;
    }
}

