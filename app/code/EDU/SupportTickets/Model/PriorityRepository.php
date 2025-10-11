<?php

namespace EDU\SupportTickets\Model;

use EDU\SupportTickets\Api\Data\PriorityInterface;
use EDU\SupportTickets\Api\PriorityRepositoryInterface;
use EDU\SupportTickets\Model\ResourceModel\Priority as PriorityResourceModel;
use EDU\SupportTickets\Model\ResourceModel\Priority\Collection as PriorityCollection;
use EDU\SupportTickets\Model\ResourceModel\Priority\CollectionFactory as PriorityCollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

class PriorityRepository implements PriorityRepositoryInterface
{
    protected $priorityFactory;
    protected $priorityResourceModel;
    protected $priorityCollectionFactory;
    protected $searchResultsFactory;
    protected $collectionProcessor;

    public function __construct(
        \EDU\SupportTickets\Model\PriorityFactory $priorityFactory,
        PriorityResourceModel $priorityResourceModel,
        PriorityCollectionFactory $priorityCollectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->priorityFactory = $priorityFactory;
        $this->priorityResourceModel = $priorityResourceModel;
        $this->priorityCollectionFactory = $priorityCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    public function save(PriorityInterface $priority)
    {
        try {
            $this->priorityResourceModel->save($priority);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__('Could not save the priority: %1', $exception->getMessage()));
        }
        return $priority;
    }

    public function getById($priorityId)
    {
        $priority = $this->priorityFactory->create();
        $this->priorityResourceModel->load($priority, $priorityId);
        if (!$priority->getPriorityId()) {
            throw new NoSuchEntityException(__('Priority with id "%1" does not exist.', $priorityId));
        }
        return $priority;
    }

    public function getActivePriorities()
    {
        $collection = $this->priorityCollectionFactory->create();
        $collection->addActiveFilter(true);
        $collection->orderBySortOrder();
        return $collection->getItems();
    }

    public function getOrderedPriorities()
    {
        $collection = $this->priorityCollectionFactory->create();
        $collection->orderBySortOrder();
        return $collection->getItems();
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->priorityCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    public function delete(PriorityInterface $priority)
    {
        try {
            $this->priorityResourceModel->delete($priority);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__('Could not delete the priority: %1', $exception->getMessage()));
        }
        return true;
    }

    public function deleteById($priorityId)
    {
        return $this->delete($this->getById($priorityId));
    }

    public function canDelete($priorityId)
    {
        // Check if priority is used by any tickets
        $ticketCollection = $this->priorityCollectionFactory->create();
        $ticketCollection->addFieldToFilter('priority_id', $priorityId);
        return $ticketCollection->getSize() === 0;
    }

    public function getDefaultPriority()
    {
        $collection = $this->priorityCollectionFactory->create();
        $collection->addActiveFilter(true);
        $collection->orderBySortOrder();
        $collection->setPageSize(1);
        $collection->load();
        
        return $collection->getFirstItem();
    }
}
