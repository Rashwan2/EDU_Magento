<?php

namespace EDU\SupportTickets\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session as CustomerSession;
use EDU\SupportTickets\Api\TicketRepositoryInterface;

class Index extends Action
{
    protected $pageFactory;
    protected $customerSession;
    protected $ticketRepository;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        CustomerSession $customerSession,
        TicketRepositoryInterface $ticketRepository
    ) {
        $this->pageFactory = $pageFactory;
        $this->customerSession = $customerSession;
        $this->ticketRepository = $ticketRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('My Support Tickets'));

        // Get customer tickets
        if ($this->customerSession->isLoggedIn()) {
            $customerId = $this->customerSession->getCustomerId();
            $tickets = $this->ticketRepository->getByCustomerId($customerId);
            $resultPage->getLayout()->getBlock('ticket.list')->setData('tickets', $tickets);
        }

        return $resultPage;
    }
}

