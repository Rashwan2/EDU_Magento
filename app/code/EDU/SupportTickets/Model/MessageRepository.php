<?php

namespace EDU\SupportTickets\Model;

use EDU\SupportTickets\Api\Data\MessageInterface;
use EDU\SupportTickets\Api\MessageRepositoryInterface;
use EDU\SupportTickets\Model\ResourceModel\Message as MessageResourceModel;
use EDU\SupportTickets\Model\ResourceModel\Message\Collection as MessageCollection;
use EDU\SupportTickets\Model\ResourceModel\Message\CollectionFactory as MessageCollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

class MessageRepository implements MessageRepositoryInterface
{
    protected $messageFactory;
    protected $messageResourceModel;
    protected $messageCollectionFactory;
    protected $searchResultsFactory;
    protected $collectionProcessor;

    public function __construct(
        \EDU\SupportTickets\Model\MessageFactory $messageFactory,
        MessageResourceModel $messageResourceModel,
        MessageCollectionFactory $messageCollectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->messageFactory = $messageFactory;
        $this->messageResourceModel = $messageResourceModel;
        $this->messageCollectionFactory = $messageCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    public function save(MessageInterface $message)
    {
        try {
            $this->messageResourceModel->save($message);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__('Could not save the message: %1', $exception->getMessage()));
        }
        return $message;
    }

    public function getById($messageId)
    {
        $message = $this->messageFactory->create();
        $this->messageResourceModel->load($message, $messageId);
        if (!$message->getMessageId()) {
            throw new NoSuchEntityException(__('Message with id "%1" does not exist.', $messageId));
        }
        return $message;
    }

    public function getByTicketId($ticketId)
    {
        $collection = $this->messageCollectionFactory->create();
        $collection->addTicketFilter($ticketId);
        $collection->orderByCreatedAt();
        return $collection->getItems();
    }

    public function getPublicByTicketId($ticketId)
    {
        $collection = $this->messageCollectionFactory->create();
        $collection->addTicketFilter($ticketId);
        $collection->addPublicFilter();
        $collection->orderByCreatedAt();
        return $collection->getItems();
    }

    public function getInternalByTicketId($ticketId)
    {
        $collection = $this->messageCollectionFactory->create();
        $collection->addTicketFilter($ticketId);
        $collection->addInternalFilter(true);
        $collection->orderByCreatedAt();
        return $collection->getItems();
    }

    public function getBySender($senderId, $senderType)
    {
        $collection = $this->messageCollectionFactory->create();
        $collection->addSenderFilter($senderId, $senderType);
        $collection->orderByCreatedAtDesc();
        return $collection->getItems();
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->messageCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    public function delete(MessageInterface $message)
    {
        try {
            $this->messageResourceModel->delete($message);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__('Could not delete the message: %1', $exception->getMessage()));
        }
        return true;
    }

    public function deleteById($messageId)
    {
        return $this->delete($this->getById($messageId));
    }

    public function getCountByTicketId($ticketId)
    {
        $collection = $this->messageCollectionFactory->create();
        $collection->addTicketFilter($ticketId);
        return $collection->getSize();
    }

    public function getLastByTicketId($ticketId)
    {
        $collection = $this->messageCollectionFactory->create();
        $collection->addTicketFilter($ticketId);
        $collection->orderByCreatedAtDesc();
        $collection->setPageSize(1);
        $collection->load();
        
        return $collection->getFirstItem();
    }
}
