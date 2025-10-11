<?php

namespace EDU\SupportTickets\Api\Data;

interface StatusHistoryInterface
{
    const HISTORY_ID = 'history_id';
    const TICKET_ID = 'ticket_id';
    const OLD_STATUS = 'old_status';
    const NEW_STATUS = 'new_status';
    const CHANGED_BY = 'changed_by';
    const COMMENT = 'comment';
    const CREATED_AT = 'created_at';

    /**
     * Get history ID
     *
     * @return int|null
     */
    public function getHistoryId();

    /**
     * Set history ID
     *
     * @param int $historyId
     * @return $this
     */
    public function setHistoryId($historyId);

    /**
     * Get ticket ID
     *
     * @return int|null
     */
    public function getTicketId();

    /**
     * Set ticket ID
     *
     * @param int $ticketId
     * @return $this
     */
    public function setTicketId($ticketId);

    /**
     * Get old status
     *
     * @return string|null
     */
    public function getOldStatus();

    /**
     * Set old status
     *
     * @param string $oldStatus
     * @return $this
     */
    public function setOldStatus($oldStatus);

    /**
     * Get new status
     *
     * @return string|null
     */
    public function getNewStatus();

    /**
     * Set new status
     *
     * @param string $newStatus
     * @return $this
     */
    public function setNewStatus($newStatus);

    /**
     * Get changed by
     *
     * @return int|null
     */
    public function getChangedBy();

    /**
     * Set changed by
     *
     * @param int $changedBy
     * @return $this
     */
    public function setChangedBy($changedBy);

    /**
     * Get comment
     *
     * @return string|null
     */
    public function getComment();

    /**
     * Set comment
     *
     * @param string $comment
     * @return $this
     */
    public function setComment($comment);

    /**
     * Get created at
     *
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created at
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);
}
