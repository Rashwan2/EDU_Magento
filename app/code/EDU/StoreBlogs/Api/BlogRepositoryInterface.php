<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreBlogs\Api;

use EDU\StoreBlogs\Api\Data\BlogInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Blog Repository Interface
 */
interface BlogRepositoryInterface
{
    /**
     * Save blog
     *
     * @param BlogInterface $blog
     * @return BlogInterface
     * @throws LocalizedException
     */
    public function save(BlogInterface $blog);

    /**
     * Retrieve blog
     *
     * @param int $blogId
     * @return BlogInterface
     * @throws LocalizedException
     */
    public function getById($blogId);

    /**
     * Retrieve blogs matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete blog
     *
     * @param BlogInterface $blog
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(BlogInterface $blog);

    /**
     * Delete blog by ID
     *
     * @param int $blogId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($blogId);
}
