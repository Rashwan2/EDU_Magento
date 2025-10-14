<?php

namespace EDU\SupportTickets\Controller\Adminhtml\Ticket;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use EDU\SupportTickets\Model\ResourceModel\Ticket\CollectionFactory;
use EDU\SupportTickets\Api\TicketRepositoryInterface;
use Magento\Framework\Exception\CouldNotDeleteException;

class MassDelete extends Action
{
    const ADMIN_RESOURCE = 'EDU_SupportTickets::tickets';
    
    protected $filter;
    protected $collectionFactory;
    protected $ticketRepository;

    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        TicketRepositoryInterface $ticketRepository
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->ticketRepository = $ticketRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $ticketCount = $collection->getSize();
            
            if ($ticketCount === 0) {
                $this->messageManager->addWarningMessage(__('No tickets selected for deletion.'));
                return $resultRedirect->setPath('supporttickets/ticket/index');
            }
            
            $deletedCount = 0;
            $errorCount = 0;
            
            foreach ($collection as $ticket) {
                try {
                    $this->ticketRepository->delete($ticket);
                    $deletedCount++;
                } catch (CouldNotDeleteException $e) {
                    $errorCount++;
                    $this->messageManager->addErrorMessage(
                        __('Could not delete ticket #%1: %2', $ticket->getTicketNumber(), $e->getMessage())
                    );
                } catch (\Exception $e) {
                    $errorCount++;
                    $this->messageManager->addErrorMessage(
                        __('Error deleting ticket #%1: %2', $ticket->getTicketNumber(), $e->getMessage())
                    );
                }
            }
            
            // Show success message
            if ($deletedCount > 0) {
                if ($deletedCount === 1) {
                    $this->messageManager->addSuccessMessage(__('1 ticket has been deleted successfully.'));
                } else {
                    $this->messageManager->addSuccessMessage(__('%1 tickets have been deleted successfully.', $deletedCount));
                }
            }
            
            // Show error summary if there were errors
            if ($errorCount > 0) {
                if ($errorCount === 1) {
                    $this->messageManager->addErrorMessage(__('1 ticket could not be deleted.'));
                } else {
                    $this->messageManager->addErrorMessage(__('%1 tickets could not be deleted.', $errorCount));
                }
            }
            
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while processing the mass delete operation.'));
        }
        
        return $resultRedirect->setPath('supporttickets/ticket/index');
    }
}
