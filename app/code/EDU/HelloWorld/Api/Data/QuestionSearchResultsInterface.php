<?php

namespace EDU\HelloWorld\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface QuestionSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get questions list
     *
     * @return \EDU\HelloWorld\Api\Data\QuestionInterface[]
     */
    public function getItems();

    /**
     * Set questions list
     *
     * @param \EDU\HelloWorld\Api\Data\QuestionInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
