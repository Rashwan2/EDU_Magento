<?php

namespace EDU\SupportTickets\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session as CustomerSession;
use EDU\SupportTickets\Api\TicketRepositoryInterface;
use EDU\SupportTickets\Api\Data\TicketInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;

class Save extends Action
{
    protected $customerSession;
    protected $ticketRepository;
    protected $ticketFactory;

    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        TicketRepositoryInterface $ticketRepository,
        TicketInterfaceFactory $ticketFactory
    ) {
        $this->customerSession = $customerSession;
        $this->ticketRepository = $ticketRepository;
        $this->ticketFactory = $ticketFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if (!$this->getRequest()->isPost()) {
            return $resultRedirect->setPath('supporttickets/index/create');
        }

        try {
            $data = $this->getRequest()->getPostValue();
            
            $ticket = $this->ticketFactory->create();
            $ticket->setSubject($data['subject']);
            $ticket->setDescription($data['description']);
            $ticket->setCategoryId($data['category_id']);
            $ticket->setPriorityId($data['priority_id']);
            $ticket->setStatus(\EDU\SupportTickets\Api\Data\TicketInterface::STATUS_OPEN);
            $ticket->setTicketNumber($this->ticketRepository->generateTicketNumber());
            
            if ($this->customerSession->isLoggedIn()) {
                $customer = $this->customerSession->getCustomer();
                $ticket->setCustomerId($customer->getId());
                $ticket->setCustomerEmail($customer->getEmail());
                $ticket->setCustomerName($customer->getName());
            } else {
                $ticket->setCustomerEmail($data['customer_email']);
                $ticket->setCustomerName($data['customer_name']);
            }
            
            $this->ticketRepository->save($ticket);
            
            $this->messageManager->addSuccessMessage(__('Your support ticket has been created successfully. Ticket number: %1', $ticket->getTicketNumber()));
            return $resultRedirect->setPath('supporttickets/index/view', ['id' => $ticket->getTicketId()]);
            
        } catch (CouldNotSaveException $e) {
            $this->messageManager->addErrorMessage(__('Unable to create support ticket. Please try again.'));
            return $resultRedirect->setPath('supporttickets/index/create');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while creating the support ticket.'));
            return $resultRedirect->setPath('supporttickets/index/create');
        }
    }
}

