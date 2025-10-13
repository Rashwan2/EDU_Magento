<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\SupportTickets\Model;

use EDU\SupportTickets\Api\Data\TicketSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

/**
 * Blog search results
 */
class TicketSearchResults extends SearchResults implements TicketSearchResultsInterface
{
}
