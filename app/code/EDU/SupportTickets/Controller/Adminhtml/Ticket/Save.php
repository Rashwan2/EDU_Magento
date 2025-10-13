<?php

namespace EDU\SupportTickets\Controller\Adminhtml\Ticket;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use EDU\SupportTickets\Api\TicketRepositoryInterface;
use EDU\SupportTickets\Api\Data\TicketInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends Action
{
    const ADMIN_RESOURCE = 'EDU_SupportTickets::tickets';

    protected $ticketRepository;
    protected $ticketFactory;

    public function __construct(
        Context $context,
        TicketRepositoryInterface $ticketRepository,
        TicketInterfaceFactory $ticketFactory
    ) {
        $this->ticketRepository = $ticketRepository;
        $this->ticketFactory = $ticketFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!$this->getRequest()->isPost()) {
            return $resultRedirect->setPath('supporttickets/ticket/index');
        }

        try {
            $data = $this->getRequest()->getPostValue();
            $ticketId = $data['ticket_id'] ?? null;

            if ($ticketId) {
                $ticket = $this->ticketRepository->getById($ticketId);
            } else {
                $ticket = $this->ticketFactory->create();
                $ticket->setTicketNumber($this->ticketRepository->generateTicketNumber());
            }

            $ticket->setSubject($data['subject']);
            $ticket->setDescription($data['description']);
            $ticket->setCategoryId($data['category_id']);
            $ticket->setPriorityId($data['priority_id']);
            $ticket->setStatus($data['status']);
            $ticket->setAssignedTo($data['assigned_to'] ?? null);
            $ticket->setOrderNumber($data['order_number'] ?? null);

            if (!$ticketId) {
                $ticket->setCustomerEmail($data['customer_email']);
                $ticket->setCustomerName($data['customer_name']);
            }

            $this->ticketRepository->save($ticket);

            $this->messageManager->addSuccessMessage(__('Ticket has been saved successfully.'));
            return $resultRedirect->setPath('supporttickets/ticket/view', ['id' => $ticket->getTicketId()]);

        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('Ticket not found.'));
            return $resultRedirect->setPath('supporttickets/ticket/index');
        } catch (CouldNotSaveException $e) {
            $this->messageManager->addErrorMessage(__('Unable to save ticket. Please try again.'));
            return $resultRedirect->setPath('supporttickets/ticket/index');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while saving the ticket.'));
            return $resultRedirect->setPath('supporttickets/ticket/index');
        }
    }
}

