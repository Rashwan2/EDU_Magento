<?php

namespace EDU\SupportTickets\Controller\Adminhtml\Ticket;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use EDU\SupportTickets\Api\TicketRepositoryInterface;
use EDU\SupportTickets\Api\CategoryRepositoryInterface;
use EDU\SupportTickets\Api\PriorityRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Edit extends Action
{
    const ADMIN_RESOURCE = 'EDU_SupportTickets::tickets';

    protected $pageFactory;
    protected $ticketRepository;
    protected $categoryRepository;
    protected $priorityRepository;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        TicketRepositoryInterface $ticketRepository,
        CategoryRepositoryInterface $categoryRepository,
        PriorityRepositoryInterface $priorityRepository
    ) {
        $this->pageFactory = $pageFactory;
        $this->ticketRepository = $ticketRepository;
        $this->categoryRepository = $categoryRepository;
        $this->priorityRepository = $priorityRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $ticketId = $this->getRequest()->getParam('id');

        try {
            $ticket = $this->ticketRepository->getById($ticketId);
            $categories = $this->categoryRepository->getActiveCategories();
            $priorities = $this->priorityRepository->getActivePriorities();

            $resultPage = $this->pageFactory->create();
            $resultPage->getConfig()->getTitle()->prepend(__('Edit Ticket #%1', $ticket->getTicketNumber()));

            $resultPage->getLayout()->getBlock('ticket.edit.form')->setData('ticket', $ticket);
            $resultPage->getLayout()->getBlock('ticket.edit.form')->setData('categories', $categories);
            $resultPage->getLayout()->getBlock('ticket.edit.form')->setData('priorities', $priorities);

            return $resultPage;

        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('Ticket not found.'));
            return $this->_redirect('supporttickets/ticket/index');
        }
    }
}

