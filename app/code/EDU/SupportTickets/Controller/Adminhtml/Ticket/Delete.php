<?php

namespace EDU\SupportTickets\Controller\Adminhtml\Ticket;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use EDU\SupportTickets\Api\TicketRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

class Delete extends Action
{
    const ADMIN_RESOURCE = 'EDU_SupportTickets::tickets';
    
    protected $ticketRepository;

    public function __construct(
        Context $context,
        TicketRepositoryInterface $ticketRepository
    ) {
        $this->ticketRepository = $ticketRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $ticketId = $this->getRequest()->getParam('id');
        
        if (!$ticketId) {
            $this->messageManager->addErrorMessage(__('Ticket ID is required.'));
            return $resultRedirect->setPath('supporttickets/ticket/index');
        }
        
        try {
            $ticket = $this->ticketRepository->getById($ticketId);
            $this->ticketRepository->delete($ticket);
            
            $this->messageManager->addSuccessMessage(__('Ticket has been deleted successfully.'));
            
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('Ticket not found.'));
        } catch (CouldNotDeleteException $e) {
            $this->messageManager->addErrorMessage(__('Unable to delete ticket. Please try again.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while deleting the ticket.'));
        }
        
        return $resultRedirect->setPath('supporttickets/ticket/index');
    }
}


