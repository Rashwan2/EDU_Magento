<?php

namespace EDU\SupportTickets\Controller\Adminhtml\Ticket;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use EDU\SupportTickets\Api\TicketRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

class Delete extends Action
{
    const ADMIN_RESOURCE = 'EDU_SupportTickets::tickets';
    
    protected $ticketRepository;
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        TicketRepositoryInterface $ticketRepository
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->ticketRepository = $ticketRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $ticketId = $this->getRequest()->getParam('id');
        $confirm = $this->getRequest()->getParam('confirm');
        
        if (!$ticketId) {
            $this->messageManager->addErrorMessage(__('Ticket ID is required.'));
            return $resultRedirect->setPath('supporttickets/ticket/index');
        }

        // If not confirmed, show confirmation page
        if (!$confirm) {
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->prepend(__('Delete Ticket Confirmation'));
            return $resultPage;
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


