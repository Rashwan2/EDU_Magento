<?php

namespace EDU\SupportTickets\Model;

use EDU\SupportTickets\Api\Data\TicketInterface;
use EDU\SupportTickets\Api\TicketRepositoryInterface;
use EDU\SupportTickets\Model\ResourceModel\Ticket as TicketResourceModel;
use EDU\SupportTickets\Model\ResourceModel\Ticket\Collection as TicketCollection;
use EDU\SupportTickets\Model\ResourceModel\Ticket\CollectionFactory as TicketCollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Math\Random;

class TicketRepository implements TicketRepositoryInterface
{
    protected $ticketFactory;
    protected $ticketResourceModel;
    protected $ticketCollectionFactory;
    protected $searchResultsFactory;
    protected $collectionProcessor;
    protected $random;

    public function __construct(
        \EDU\SupportTickets\Model\TicketFactory $ticketFactory,
        TicketResourceModel $ticketResourceModel,
        TicketCollectionFactory $ticketCollectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        Random $random
    ) {
        $this->ticketFactory = $ticketFactory;
        $this->ticketResourceModel = $ticketResourceModel;
        $this->ticketCollectionFactory = $ticketCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->random = $random;
    }

    public function save(TicketInterface $ticket)
    {
        try {
            $this->ticketResourceModel->save($ticket);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__('Could not save the ticket: %1', $exception->getMessage()));
        }
        return $ticket;
    }

    public function getById($ticketId)
    {
        $ticket = $this->ticketFactory->create();
        $this->ticketResourceModel->load($ticket, $ticketId);
        if (!$ticket->getTicketId()) {
            throw new NoSuchEntityException(__('Ticket with id "%1" does not exist.', $ticketId));
        }
        return $ticket;
    }

    public function getByTicketNumber($ticketNumber)
    {
        $ticket = $this->ticketFactory->create();
        $this->ticketResourceModel->load($ticket, $ticketNumber, 'ticket_number');
        if (!$ticket->getTicketId()) {
            throw new NoSuchEntityException(__('Ticket with number "%1" does not exist.', $ticketNumber));
        }
        return $ticket;
    }

    public function getByCustomerId($customerId)
    {
        $collection = $this->ticketCollectionFactory->create();
        $collection->addCustomerFilter($customerId);
        return $collection->getItems();
    }

    public function getByStatus($status)
    {
        $collection = $this->ticketCollectionFactory->create();
        $collection->addStatusFilter($status);
        return $collection->getItems();
    }

    public function getByPriorityId($priorityId)
    {
        $collection = $this->ticketCollectionFactory->create();
        $collection->addPriorityFilter($priorityId);
        return $collection->getItems();
    }

    public function getByCategoryId($categoryId)
    {
        $collection = $this->ticketCollectionFactory->create();
        $collection->addCategoryFilter($categoryId);
        return $collection->getItems();
    }

    public function getByAssignedTo($assignedTo)
    {
        $collection = $this->ticketCollectionFactory->create();
        $collection->addAssignedToFilter($assignedTo);
        return $collection->getItems();
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->ticketCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    public function delete(TicketInterface $ticket)
    {
        try {
            $this->ticketResourceModel->delete($ticket);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__('Could not delete the ticket: %1', $exception->getMessage()));
        }
        return true;
    }

    public function deleteById($ticketId)
    {
        return $this->delete($this->getById($ticketId));
    }

    public function updateStatus($ticketId, $status, $changedBy = null, $comment = null)
    {
        $ticket = $this->getById($ticketId);
        $oldStatus = $ticket->getStatus();
        $ticket->setStatus($status);
        
        if ($status === TicketInterface::STATUS_RESOLVED || $status === TicketInterface::STATUS_CLOSED) {
            $ticket->setResolvedAt(date('Y-m-d H:i:s'));
        }
        
        $this->save($ticket);

        // Add status history record
        $statusHistory = $this->ticketFactory->create();
        $statusHistory->setTicketId($ticketId);
        $statusHistory->setOldStatus($oldStatus);
        $statusHistory->setNewStatus($status);
        $statusHistory->setChangedBy($changedBy);
        $statusHistory->setComment($comment);
        $statusHistory->setCreatedAt(date('Y-m-d H:i:s'));

        return true;
    }

    public function assignTicket($ticketId, $assignedTo)
    {
        $ticket = $this->getById($ticketId);
        $ticket->setAssignedTo($assignedTo);
        $this->save($ticket);
        return true;
    }

    public function generateTicketNumber()
    {
        $prefix = 'TKT';
        $timestamp = date('Ymd');
        $random = $this->random->getRandomString(6, '0123456789');
        return $prefix . $timestamp . $random;
    }
}
