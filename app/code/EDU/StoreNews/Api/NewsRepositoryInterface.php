<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreNews\Api;

use EDU\StoreNews\Api\Data\NewsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * News Repository Interface
 */
interface NewsRepositoryInterface
{
    /**
     * Save news
     *
     * @param NewsInterface $news
     * @return NewsInterface
     * @throws LocalizedException
     */
    public function save(NewsInterface $news);

    /**
     * Retrieve news
     *
     * @param int $newsId
     * @return NewsInterface
     * @throws LocalizedException
     */
    public function getById($newsId);

    /**
     * Retrieve news matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete news
     *
     * @param NewsInterface $news
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(NewsInterface $news);

    /**
     * Delete news by ID
     *
     * @param int $newsId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($newsId);
}
