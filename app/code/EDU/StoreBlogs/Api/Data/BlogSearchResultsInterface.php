<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreBlogs\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Blog search results interface
 */
interface BlogSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get blog list
     *
     * @return BlogInterface[]
     */
    public function getItems();

    /**
     * Set blog list
     *
     * @param BlogInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
