<?php

namespace EDU\SupportTickets\Api;

use EDU\SupportTickets\Api\Data\CategoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface CategoryRepositoryInterface
{
    /**
     * Save category
     *
     * @param CategoryInterface $category
     * @return CategoryInterface
     * @throws CouldNotSaveException
     */
    public function save(CategoryInterface $category);

    /**
     * Get category by ID
     *
     * @param int $categoryId
     * @return CategoryInterface
     * @throws NoSuchEntityException
     */
    public function getById($categoryId);

    /**
     * Get category by slug
     *
     * @param string $slug
     * @return CategoryInterface
     * @throws NoSuchEntityException
     */
    public function getBySlug($slug);

    /**
     * Get active categories
     *
     * @return CategoryInterface[]
     */
    public function getActiveCategories();

    /**
     * Get categories ordered by sort order
     *
     * @return CategoryInterface[]
     */
    public function getOrderedCategories();

    /**
     * Get category list
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete category
     *
     * @param CategoryInterface $category
     * @return bool
     */
    public function delete(CategoryInterface $category);

    /**
     * Delete category by ID
     *
     * @param int $categoryId
     * @return bool
     */
    public function deleteById($categoryId);

    /**
     * Check if category can be deleted
     *
     * @param int $categoryId
     * @return bool
     */
    public function canDelete($categoryId);

    /**
     * Generate unique slug
     *
     * @param string $name
     * @return string
     */
    public function generateSlug($name);
}
