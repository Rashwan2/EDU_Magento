<?php

namespace EDU\SupportTickets\Model;

use EDU\SupportTickets\Api\Data\StatusHistoryInterface;
use EDU\SupportTickets\Api\StatusHistoryRepositoryInterface;
use EDU\SupportTickets\Model\ResourceModel\StatusHistory as StatusHistoryResourceModel;
use EDU\SupportTickets\Model\ResourceModel\StatusHistory\Collection as StatusHistoryCollection;
use EDU\SupportTickets\Model\ResourceModel\StatusHistory\CollectionFactory as StatusHistoryCollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

class StatusHistoryRepository implements StatusHistoryRepositoryInterface
{
    protected $statusHistoryFactory;
    protected $statusHistoryResourceModel;
    protected $statusHistoryCollectionFactory;
    protected $searchResultsFactory;
    protected $collectionProcessor;

    public function __construct(
        \EDU\SupportTickets\Model\StatusHistoryFactory $statusHistoryFactory,
        StatusHistoryResourceModel $statusHistoryResourceModel,
        StatusHistoryCollectionFactory $statusHistoryCollectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->statusHistoryFactory = $statusHistoryFactory;
        $this->statusHistoryResourceModel = $statusHistoryResourceModel;
        $this->statusHistoryCollectionFactory = $statusHistoryCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    public function save(StatusHistoryInterface $statusHistory)
    {
        try {
            $this->statusHistoryResourceModel->save($statusHistory);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__('Could not save the status history: %1', $exception->getMessage()));
        }
        return $statusHistory;
    }

    public function getById($historyId)
    {
        $statusHistory = $this->statusHistoryFactory->create();
        $this->statusHistoryResourceModel->load($statusHistory, $historyId);
        if (!$statusHistory->getHistoryId()) {
            throw new NoSuchEntityException(__('Status history with id "%1" does not exist.', $historyId));
        }
        return $statusHistory;
    }

    public function getByTicketId($ticketId)
    {
        $collection = $this->statusHistoryCollectionFactory->create();
        $collection->addTicketFilter($ticketId);
        $collection->orderByCreatedAt();
        return $collection->getItems();
    }

    public function getOrderedByTicketId($ticketId)
    {
        return $this->getByTicketId($ticketId);
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->statusHistoryCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    public function delete(StatusHistoryInterface $statusHistory)
    {
        try {
            $this->statusHistoryResourceModel->delete($statusHistory);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__('Could not delete the status history: %1', $exception->getMessage()));
        }
        return true;
    }

    public function deleteById($historyId)
    {
        return $this->delete($this->getById($historyId));
    }

    public function addStatusChange($ticketId, $oldStatus, $newStatus, $changedBy = null, $comment = null)
    {
        $statusHistory = $this->statusHistoryFactory->create();
        $statusHistory->setTicketId($ticketId);
        $statusHistory->setOldStatus($oldStatus);
        $statusHistory->setNewStatus($newStatus);
        $statusHistory->setChangedBy($changedBy);
        $statusHistory->setComment($comment);
        $statusHistory->setCreatedAt(date('Y-m-d H:i:s'));

        return $this->save($statusHistory);
    }

    public function getLastByTicketId($ticketId)
    {
        $collection = $this->statusHistoryCollectionFactory->create();
        $collection->addTicketFilter($ticketId);
        $collection->orderByCreatedAtDesc();
        $collection->setPageSize(1);
        $collection->load();
        
        return $collection->getFirstItem();
    }
}
