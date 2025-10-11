<?php

namespace EDU\SupportTickets\Api;

use EDU\SupportTickets\Api\Data\MessageInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface MessageRepositoryInterface
{
    /**
     * Save message
     *
     * @param MessageInterface $message
     * @return MessageInterface
     * @throws CouldNotSaveException
     */
    public function save(MessageInterface $message);

    /**
     * Get message by ID
     *
     * @param int $messageId
     * @return MessageInterface
     * @throws NoSuchEntityException
     */
    public function getById($messageId);

    /**
     * Get messages by ticket ID
     *
     * @param int $ticketId
     * @return MessageInterface[]
     */
    public function getByTicketId($ticketId);

    /**
     * Get public messages by ticket ID (exclude internal notes)
     *
     * @param int $ticketId
     * @return MessageInterface[]
     */
    public function getPublicByTicketId($ticketId);

    /**
     * Get internal messages by ticket ID
     *
     * @param int $ticketId
     * @return MessageInterface[]
     */
    public function getInternalByTicketId($ticketId);

    /**
     * Get messages by sender
     *
     * @param int $senderId
     * @param string $senderType
     * @return MessageInterface[]
     */
    public function getBySender($senderId, $senderType);

    /**
     * Get message list
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete message
     *
     * @param MessageInterface $message
     * @return bool
     */
    public function delete(MessageInterface $message);

    /**
     * Delete message by ID
     *
     * @param int $messageId
     * @return bool
     */
    public function deleteById($messageId);

    /**
     * Get message count for ticket
     *
     * @param int $ticketId
     * @return int
     */
    public function getCountByTicketId($ticketId);

    /**
     * Get last message for ticket
     *
     * @param int $ticketId
     * @return MessageInterface|null
     */
    public function getLastByTicketId($ticketId);
}
