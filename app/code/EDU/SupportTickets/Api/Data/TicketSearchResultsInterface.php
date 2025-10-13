<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\SupportTickets\Api\Data;

use EDU\SupportTickets\Api\Data\TicketInterface;
use Magento\Framework\Api\SearchResultsInterface;

/**
 * Ticket search results interface
 */
interface TicketSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Ticket list
     *
     * @return TicketInterface[]
     */
    public function getItems();

    /**
     * Set Ticket list
     *
     * @param TicketInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
