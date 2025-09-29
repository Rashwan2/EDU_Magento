<?php

namespace EDU\HelloWorld\Api;

use EDU\HelloWorld\Api\Data\AnswerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface AnswerRepositoryInterface
{
    /**
     * Save answer
     *
     * @param \EDU\HelloWorld\Api\Data\AnswerInterface $answer
     * @return \EDU\HelloWorld\Api\Data\AnswerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(AnswerInterface $answer);

    /**
     * Retrieve answer
     *
     * @param int $answerId
     * @return \EDU\HelloWorld\Api\Data\AnswerInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($answerId);

    /**
     * Delete answer
     *
     * @param \EDU\HelloWorld\Api\Data\AnswerInterface $answer
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(AnswerInterface $answer);

    /**
     * Delete answer by ID
     *
     * @param int $answerId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($answerId);

    /**
     * Get answers by question ID
     *
     * @param int $questionId
     * @return \EDU\HelloWorld\Api\Data\AnswerInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByQuestionId($questionId);

    /**
     * Get answer count by question ID
     *
     * @param int $questionId
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCountByQuestionId($questionId);

    /**
     * Increment helpful count
     *
     * @param int $answerId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function incrementHelpfulCount($answerId);

    /**
     * Decrement helpful count
     *
     * @param int $answerId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function decrementHelpfulCount($answerId);
}
