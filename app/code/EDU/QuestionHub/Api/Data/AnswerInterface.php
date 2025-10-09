<?php

namespace EDU\QuestionHub\Api\Data;

interface AnswerInterface
{
    const ANSWER_ID = 'answer_id';
    const QUESTION_ID = 'question_id';
    const CUSTOMER_NAME = 'customer_name';
    const ADMIN_USER_ID = 'admin_user_id';
    const ANSWER_TEXT = 'answer_text';
    const IS_ADMIN_ANSWER = 'is_admin_answer';
    const HELPFUL_COUNT = 'helpful_count';
    const CREATED_AT = 'created_at';

    /**
     * Get answer ID
     *
     * @return int|null
     */
    public function getAnswerId();

    /**
     * Set answer ID
     *
     * @param int $answerId
     * @return $this
     */
    public function setAnswerId($answerId);

    /**
     * Get question ID
     *
     * @return int
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
     * Get customer name
     *
     * @return string|null
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
     * Get admin user ID
     *
     * @return int|null
     */
    public function getAdminUserId();

    /**
     * Set admin user ID
     *
     * @param int $adminUserId
     * @return $this
     */
    public function setAdminUserId($adminUserId);

    /**
     * Get answer text
     *
     * @return string
     */
    public function getAnswerText();

    /**
     * Set answer text
     *
     * @param string $answerText
     * @return $this
     */
    public function setAnswerText($answerText);

    /**
     * Get is admin answer
     *
     * @return bool
     */
    public function getIsAdminAnswer();

    /**
     * Set is admin answer
     *
     * @param bool $isAdminAnswer
     * @return $this
     */
    public function setIsAdminAnswer($isAdminAnswer);

    /**
     * Get helpful count
     *
     * @return int
     */
    public function getHelpfulCount();

    /**
     * Set helpful count
     *
     * @param int $helpfulCount
     * @return $this
     */
    public function setHelpfulCount($helpfulCount);

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
     * Check if answer is from admin
     *
     * @return bool
     */
    public function isAdminAnswer();
}
