<?php

namespace EDU\SupportTickets\Controller\Adminhtml\Ticket;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use EDU\SupportTickets\Model\ResourceModel\Ticket\CollectionFactory;
use EDU\SupportTickets\Api\TicketRepositoryInterface;
use EDU\SupportTickets\Api\Data\TicketInterface;
use Magento\Framework\Exception\CouldNotSaveException;

class MassStatus extends Action
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
        $status = $this->getRequest()->getParam('status');
        
        if (!$status) {
            $this->messageManager->addErrorMessage(__('Status parameter is required.'));
            return $resultRedirect->setPath('supporttickets/ticket/index');
        }
        
        // Validate status
        $validStatuses = [
            TicketInterface::STATUS_OPEN,
            TicketInterface::STATUS_IN_PROGRESS,
            TicketInterface::STATUS_WAITING_CUSTOMER,
            TicketInterface::STATUS_RESOLVED,
            TicketInterface::STATUS_CLOSED
        ];
        
        if (!in_array($status, $validStatuses)) {
            $this->messageManager->addErrorMessage(__('Invalid status selected.'));
            return $resultRedirect->setPath('supporttickets/ticket/index');
        }
        
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $ticketCount = $collection->getSize();
            
            if ($ticketCount === 0) {
                $this->messageManager->addWarningMessage(__('No tickets selected for status update.'));
                return $resultRedirect->setPath('supporttickets/ticket/index');
            }
            
            $updatedCount = 0;
            $errorCount = 0;
            $adminUserId = $this->_auth->getUser()->getId();
            
            foreach ($collection as $ticket) {
                try {
                    $oldStatus = $ticket->getStatus();
                    $ticket->setStatus($status);
                    
                    // Set resolved date if status is resolved or closed
                    if ($status === TicketInterface::STATUS_RESOLVED || $status === TicketInterface::STATUS_CLOSED) {
                        $ticket->setResolvedAt(date('Y-m-d H:i:s'));
                    }
                    
                    $this->ticketRepository->save($ticket);
                    
                    // Add status history record
                    $this->ticketRepository->updateStatus(
                        $ticket->getTicketId(),
                        $status,
                        $adminUserId,
                        __('Status changed via mass action from %1 to %2', $oldStatus, $status)
                    );
                    
                    $updatedCount++;
                } catch (CouldNotSaveException $e) {
                    $errorCount++;
                    $this->messageManager->addErrorMessage(
                        __('Could not update ticket #%1: %2', $ticket->getTicketNumber(), $e->getMessage())
                    );
                } catch (\Exception $e) {
                    $errorCount++;
                    $this->messageManager->addErrorMessage(
                        __('Error updating ticket #%1: %2', $ticket->getTicketNumber(), $e->getMessage())
                    );
                }
            }
            
            // Show success message
            if ($updatedCount > 0) {
                $statusLabel = $this->getStatusLabel($status);
                if ($updatedCount === 1) {
                    $this->messageManager->addSuccessMessage(__('1 ticket status has been updated to %1.', $statusLabel));
                } else {
                    $this->messageManager->addSuccessMessage(__('%1 tickets status have been updated to %2.', $updatedCount, $statusLabel));
                }
            }
            
            // Show error summary if there were errors
            if ($errorCount > 0) {
                if ($errorCount === 1) {
                    $this->messageManager->addErrorMessage(__('1 ticket could not be updated.'));
                } else {
                    $this->messageManager->addErrorMessage(__('%1 tickets could not be updated.', $errorCount));
                }
            }
            
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while processing the mass status update.'));
        }
        
        return $resultRedirect->setPath('supporttickets/ticket/index');
    }
    
    /**
     * Get status label for display
     *
     * @param string $status
     * @return string
     */
    private function getStatusLabel($status)
    {
        $statusLabels = [
            TicketInterface::STATUS_OPEN => __('Open'),
            TicketInterface::STATUS_IN_PROGRESS => __('In Progress'),
            TicketInterface::STATUS_WAITING_CUSTOMER => __('Waiting for Customer'),
            TicketInterface::STATUS_RESOLVED => __('Resolved'),
            TicketInterface::STATUS_CLOSED => __('Closed')
        ];
        
        return $statusLabels[$status] ?? $status;
    }
}
