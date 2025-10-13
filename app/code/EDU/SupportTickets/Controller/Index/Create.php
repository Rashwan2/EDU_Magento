<?php

namespace EDU\SupportTickets\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session as CustomerSession;
use EDU\SupportTickets\Api\CategoryRepositoryInterface;
use EDU\SupportTickets\Api\PriorityRepositoryInterface;

class Create implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected PageFactory $pageFactory;
    /**
     * @var CustomerSession
     */
    protected CustomerSession $customerSession;
    /**
     * @var CategoryRepositoryInterface
     */
    protected CategoryRepositoryInterface $categoryRepository;
    /**
     * @var PriorityRepositoryInterface
     */
    protected PriorityRepositoryInterface $priorityRepository;

    public function __construct(
//        Context $context,
        PageFactory $pageFactory,
        CustomerSession $customerSession,
        CategoryRepositoryInterface $categoryRepository,
        PriorityRepositoryInterface $priorityRepository
    ) {
        $this->pageFactory = $pageFactory;
        $this->customerSession = $customerSession;
        $this->categoryRepository = $categoryRepository;
        $this->priorityRepository = $priorityRepository;
    }

    public function execute()
    {
        $resultPage = $this->pageFactory->create();

        // Get categories and priorities for form
        $categories = $this->categoryRepository->getActiveCategories();
        $priorities = $this->priorityRepository->getActivePriorities();

        $resultPage->getLayout()->getBlock('ticket.form')->setData('categories', $categories);
        $resultPage->getLayout()->getBlock('ticket.form')->setData('priorities', $priorities);

        return $resultPage;
    }
}

