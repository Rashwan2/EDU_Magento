<?php

namespace EDU\SupportTickets\Controller\Adminhtml\Ticket;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use EDU\SupportTickets\Api\TicketRepositoryInterface;
use EDU\SupportTickets\Api\MessageRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class View extends Action
{
    const ADMIN_RESOURCE = 'EDU_SupportTickets::tickets';
    
    protected $pageFactory;
    protected $ticketRepository;
    protected $messageRepository;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        TicketRepositoryInterface $ticketRepository,
        MessageRepositoryInterface $messageRepository
    ) {
        $this->pageFactory = $pageFactory;
        $this->ticketRepository = $ticketRepository;
        $this->messageRepository = $messageRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $ticketId = $this->getRequest()->getParam('id');
        
        try {
            $ticket = $this->ticketRepository->getById($ticketId);
            $messages = $this->messageRepository->getByTicketId($ticketId);
            
            $resultPage = $this->pageFactory->create();
            $resultPage->getConfig()->getTitle()->prepend(__('Ticket #%1', $ticket->getTicketNumber()));
            
            $resultPage->getLayout()->getBlock('ticket.view')->setData('ticket', $ticket);
            $resultPage->getLayout()->getBlock('ticket.view')->setData('messages', $messages);
            
            return $resultPage;
            
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('Ticket not found.'));
            return $this->_redirect('supporttickets/ticket/index');
        }
    }
}

