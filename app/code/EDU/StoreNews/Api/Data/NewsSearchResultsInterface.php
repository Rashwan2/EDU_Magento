<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreNews\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * News search results interface
 */
interface NewsSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get news list
     *
     * @return NewsInterface[]
     */
    public function getItems();

    /**
     * Set news list
     *
     * @param NewsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
