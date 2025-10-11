<?php

namespace EDU\SupportTickets\Api;

use EDU\SupportTickets\Api\Data\PriorityInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface PriorityRepositoryInterface
{
    /**
     * Save priority
     *
     * @param PriorityInterface $priority
     * @return PriorityInterface
     * @throws CouldNotSaveException
     */
    public function save(PriorityInterface $priority);

    /**
     * Get priority by ID
     *
     * @param int $priorityId
     * @return PriorityInterface
     * @throws NoSuchEntityException
     */
    public function getById($priorityId);

    /**
     * Get active priorities
     *
     * @return PriorityInterface[]
     */
    public function getActivePriorities();

    /**
     * Get priorities ordered by sort order
     *
     * @return PriorityInterface[]
     */
    public function getOrderedPriorities();

    /**
     * Get priority list
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete priority
     *
     * @param PriorityInterface $priority
     * @return bool
     */
    public function delete(PriorityInterface $priority);

    /**
     * Delete priority by ID
     *
     * @param int $priorityId
     * @return bool
     */
    public function deleteById($priorityId);

    /**
     * Check if priority can be deleted
     *
     * @param int $priorityId
     * @return bool
     */
    public function canDelete($priorityId);

    /**
     * Get default priority
     *
     * @return PriorityInterface|null
     */
    public function getDefaultPriority();
}
