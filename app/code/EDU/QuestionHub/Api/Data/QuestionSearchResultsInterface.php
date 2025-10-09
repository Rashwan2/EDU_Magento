<?php

namespace EDU\QuestionHub\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface QuestionSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get questions list
     *
     * @return \EDU\QuestionHub\Api\Data\QuestionInterface[]
     */
    public function getItems();

    /**
     * Set questions list
     *
     * @param \EDU\QuestionHub\Api\Data\QuestionInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
