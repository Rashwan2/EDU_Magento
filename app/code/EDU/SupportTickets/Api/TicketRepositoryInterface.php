<?php

namespace EDU\SupportTickets\Api;

use EDU\SupportTickets\Api\Data\TicketInterface;
use EDU\SupportTickets\Api\Data\TicketSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface TicketRepositoryInterface
{
    /**
     * Save ticket
     *
     * @param TicketInterface $ticket
     * @return TicketInterface
     * @throws CouldNotSaveException
     */
    public function save(TicketInterface $ticket);

    /**
     * Get ticket by ID
     *
     * @param int $ticketId
     * @return TicketInterface
     * @throws NoSuchEntityException
     */
    public function getById($ticketId);

    /**
     * Get ticket by ticket number
     *
     * @param string $ticketNumber
     * @return TicketInterface
     * @throws NoSuchEntityException
     */
    public function getByTicketNumber($ticketNumber);

    /**
     * Get tickets by customer ID
     *
     * @param int $customerId
     * @return TicketInterface[]
     */
    public function getByCustomerId($customerId);

    /**
     * Get tickets by customer email
     *
     * @param string $customerEmail
     * @return TicketInterface[]
     */
    public function getByCustomerEmail($customerEmail);

    /**
     * Get tickets by status
     *
     * @param string $status
     * @return TicketInterface[]
     */
    public function getByStatus($status);

    /**
     * Get tickets by priority ID
     *
     * @param int $priorityId
     * @return TicketInterface[]
     */
    public function getByPriorityId($priorityId);

    /**
     * Get tickets by category ID
     *
     * @param int $categoryId
     * @return TicketInterface[]
     */
    public function getByCategoryId($categoryId);

    /**
     * Get tickets by assigned admin
     *
     * @param int $assignedTo
     * @return TicketInterface[]
     */
    public function getByAssignedTo($assignedTo);

    /**
     * Get ticket list
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return TicketSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete ticket
     *
     * @param TicketInterface $ticket
     * @return bool
     */
    public function delete(TicketInterface $ticket);

    /**
     * Delete ticket by ID
     *
     * @param int $ticketId
     * @return bool
     */
    public function deleteById($ticketId);

    /**
     * Update ticket status
     *
     * @param int $ticketId
     * @param string $status
     * @param int|null $changedBy
     * @param string|null $comment
     * @return bool
     */
    public function updateStatus($ticketId, $status, $changedBy = null, $comment = null);

    /**
     * Assign ticket to admin
     *
     * @param int $ticketId
     * @param int $assignedTo
     * @return bool
     */
    public function assignTicket($ticketId, $assignedTo);

    /**
     * Generate unique ticket number
     *
     * @return string
     */
    public function generateTicketNumber();
}
