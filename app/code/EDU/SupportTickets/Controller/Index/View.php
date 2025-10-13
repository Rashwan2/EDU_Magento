<?php

namespace EDU\SupportTickets\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session as CustomerSession;
use EDU\SupportTickets\Api\TicketRepositoryInterface;
use EDU\SupportTickets\Api\MessageRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class View extends Action
{
    protected $pageFactory;
    protected $customerSession;
    protected $ticketRepository;
    protected $messageRepository;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        CustomerSession $customerSession,
        TicketRepositoryInterface $ticketRepository,
        MessageRepositoryInterface $messageRepository
    ) {
        $this->pageFactory = $pageFactory;
        $this->customerSession = $customerSession;
        $this->ticketRepository = $ticketRepository;
        $this->messageRepository = $messageRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $ticketId = $this->getRequest()->getParam('id');

        try {
            $ticket = $this->ticketRepository->getById($ticketId);

            // Check if customer owns this ticket
            if ($this->customerSession->isLoggedIn() &&
                $ticket->getCustomerId() != $this->customerSession->getCustomerId()) {
                $this->messageManager->addErrorMessage(__('You are not authorized to view this ticket.'));
                return $this->_redirect('supporttickets/index/index');
            }

            $messages = $this->messageRepository->getByTicketId($ticketId);

            $resultPage = $this->pageFactory->create();
            $resultPage->getConfig()->getTitle()->set(__('Ticket #%1', $ticket->getTicketNumber()));
            $resultPage->getLayout()->getBlock('ticket.view')->setTicket($ticket);


            return $resultPage;

        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('Ticket not found.'));
            return $this->_redirect('supporttickets/index/index');
        }
    }
}

