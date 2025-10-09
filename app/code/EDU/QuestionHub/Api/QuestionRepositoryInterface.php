<?php

namespace EDU\QuestionHub\Api;

use EDU\QuestionHub\Api\Data\QuestionInterface;
use EDU\QuestionHub\Api\Data\QuestionSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface QuestionRepositoryInterface
{
    /**
     * Save question
     *
     * @param \EDU\QuestionHub\Api\Data\QuestionInterface $question
     * @return \EDU\QuestionHub\Api\Data\QuestionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(QuestionInterface $question);

    /**
     * Retrieve question
     *
     * @param int $questionId
     * @return \EDU\QuestionHub\Api\Data\QuestionInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($questionId);

    /**
     * Retrieve questions matching the specified criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \EDU\QuestionHub\Api\Data\QuestionSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete question
     *
     * @param \EDU\QuestionHub\Api\Data\QuestionInterface $question
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(QuestionInterface $question);

    /**
     * Delete question by ID
     *
     * @param int $questionId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($questionId);

    /**
     * Get questions by product ID
     *
     * @param int $productId
     * @param string $status
     * @return \EDU\QuestionHub\Api\Data\QuestionInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByProductId($productId, $status = null);

    /**
     * Approve question
     *
     * @param int $questionId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function approveByQuestionId($questionId);

    /**
     * Reject question
     *
     * @param int $questionId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function rejectByQuestionId($questionId);
}
