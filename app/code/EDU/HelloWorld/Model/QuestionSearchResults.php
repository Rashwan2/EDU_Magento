<?php

namespace EDU\HelloWorld\Model;

use EDU\HelloWorld\Api\Data\QuestionSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

class QuestionSearchResults extends SearchResults implements QuestionSearchResultsInterface
{
    // This class inherits all methods from SearchResults
    // The interface methods are already implemented in the parent class
}
