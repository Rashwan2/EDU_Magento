<?php

namespace EDU\SupportTickets\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session as CustomerSession;
use EDU\SupportTickets\Api\TicketRepositoryInterface;

class Index implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected $pageFactory;
    /**
     * @var CustomerSession
     */
    protected $customerSession;
    /**
     * @var TicketRepositoryInterface
     */
    protected $ticketRepository;

    public function __construct(
        PageFactory $pageFactory,
        CustomerSession $customerSession,
        TicketRepositoryInterface $ticketRepository
    ) {
        $this->pageFactory = $pageFactory;
        $this->customerSession = $customerSession;
        $this->ticketRepository = $ticketRepository;
    }

    public function execute()
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('My Support Tickets'));

        // Get customer tickets
        if ($this->customerSession->isLoggedIn()) {
            $customer = $this->customerSession->getCustomer();
            $customerEmail = $customer->getEmail();
            $tickets = $this->ticketRepository->getByCustomerEmail($customerEmail);
            $resultPage->getLayout()->getBlock('ticket.list')->setData('tickets', $tickets);
        }

        return $resultPage;
    }
}

