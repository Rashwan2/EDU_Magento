<?php

namespace EDU\HelloWorld\Api\Data;

interface QuestionInterface
{
    const QUESTION_ID = 'question_id';
    const PRODUCT_ID = 'product_id';
    const CUSTOMER_NAME = 'customer_name';
    const CUSTOMER_EMAIL = 'customer_email';
    const QUESTION_TEXT = 'question_text';
    const STATUS = 'status';
    const ANSWERED_COUNT = 'answered_count';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Get question ID
     *
     * @return int|null
     */
    public function getQuestionId();

    /**
     * Set question ID
     *
     * @param int $questionId
     * @return $this
     */
    public function setQuestionId($questionId);

    /**
     * Get product ID
     *
     * @return int
     */
    public function getProductId();

    /**
     * Set product ID
     *
     * @param int $productId
     * @return $this
     */
    public function setProductId($productId);

    /**
     * Get customer name
     *
     * @return string
     */
    public function getCustomerName();

    /**
     * Set customer name
     *
     * @param string $customerName
     * @return $this
     */
    public function setCustomerName($customerName);

    /**
     * Get customer email
     *
     * @return string
     */
    public function getCustomerEmail();

    /**
     * Set customer email
     *
     * @param string $customerEmail
     * @return $this
     */
    public function setCustomerEmail($customerEmail);

    /**
     * Get question text
     *
     * @return string
     */
    public function getQuestionText();

    /**
     * Set question text
     *
     * @param string $questionText
     * @return $this
     */
    public function setQuestionText($questionText);

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus();

    /**
     * Set status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * Get answered count
     *
     * @return int
     */
    public function getAnsweredCount();

    /**
     * Set answered count
     *
     * @param int $answeredCount
     * @return $this
     */
    public function setAnsweredCount($answeredCount);

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * Set created at
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt();

    /**
     * Set updated at
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Check if question is approved
     *
     * @return bool
     */
    public function isApproved();

    /**
     * Check if question is pending
     *
     * @return bool
     */
    public function isPending();

    /**
     * Check if question is rejected
     *
     * @return bool
     */
    public function isRejected();
}
