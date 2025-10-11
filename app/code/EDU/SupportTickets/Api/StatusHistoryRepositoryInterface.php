<?php

namespace EDU\SupportTickets\Api;

use EDU\SupportTickets\Api\Data\StatusHistoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface StatusHistoryRepositoryInterface
{
    /**
     * Save status history
     *
     * @param StatusHistoryInterface $statusHistory
     * @return StatusHistoryInterface
     * @throws CouldNotSaveException
     */
    public function save(StatusHistoryInterface $statusHistory);

    /**
     * Get status history by ID
     *
     * @param int $historyId
     * @return StatusHistoryInterface
     * @throws NoSuchEntityException
     */
    public function getById($historyId);

    /**
     * Get status history by ticket ID
     *
     * @param int $ticketId
     * @return StatusHistoryInterface[]
     */
    public function getByTicketId($ticketId);

    /**
     * Get status history ordered by created date
     *
     * @param int $ticketId
     * @return StatusHistoryInterface[]
     */
    public function getOrderedByTicketId($ticketId);

    /**
     * Get status history list
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete status history
     *
     * @param StatusHistoryInterface $statusHistory
     * @return bool
     */
    public function delete(StatusHistoryInterface $statusHistory);

    /**
     * Delete status history by ID
     *
     * @param int $historyId
     * @return bool
     */
    public function deleteById($historyId);

    /**
     * Add status change record
     *
     * @param int $ticketId
     * @param string $oldStatus
     * @param string $newStatus
     * @param int|null $changedBy
     * @param string|null $comment
     * @return StatusHistoryInterface
     */
    public function addStatusChange($ticketId, $oldStatus, $newStatus, $changedBy = null, $comment = null);

    /**
     * Get last status change for ticket
     *
     * @param int $ticketId
     * @return StatusHistoryInterface|null
     */
    public function getLastByTicketId($ticketId);
}
