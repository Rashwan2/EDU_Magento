<?php

namespace EDU\HelloWorld\Model;

use EDU\HelloWorld\Api\Data\AnswerInterface;
use EDU\HelloWorld\Model\AnswerFactory;
use EDU\HelloWorld\Api\AnswerRepositoryInterface;
use EDU\HelloWorld\Model\ResourceModel\Answer as AnswerResourceModel;
use EDU\HelloWorld\Model\ResourceModel\Answer\Collection as AnswerCollection;
use EDU\HelloWorld\Model\ResourceModel\Answer\CollectionFactory as AnswerCollectionFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class AnswerRepository implements AnswerRepositoryInterface
{
    protected $answerFactory;
    protected $answerResourceModel;
    protected $answerCollectionFactory;

    public function __construct(
        AnswerFactory $answerFactory,
        AnswerResourceModel $answerResourceModel,
        AnswerCollectionFactory $answerCollectionFactory
    ) {
        $this->answerFactory = $answerFactory;
        $this->answerResourceModel = $answerResourceModel;
        $this->answerCollectionFactory = $answerCollectionFactory;
    }

    public function save(AnswerInterface $answer)
    {
        try {
            $this->answerResourceModel->save($answer);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__('Could not save the answer: %1', $exception->getMessage()));
        }
        return $answer;
    }

    public function getById($answerId)
    {
        $answer = $this->answerFactory->create();
        $this->answerResourceModel->load($answer, $answerId);
        if (!$answer->getAnswerId()) {
            throw new NoSuchEntityException(__('Answer with id "%1" does not exist.', $answerId));
        }
        return $answer;
    }

    public function delete(AnswerInterface $answer)
    {
        try {
            $this->answerResourceModel->delete($answer);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__('Could not delete the answer: %1', $exception->getMessage()));
        }
        return true;
    }

    public function deleteById($answerId)
    {
        return $this->delete($this->getById($answerId));
    }

    public function getByQuestionId($questionId)
    {
        $collection = $this->answerCollectionFactory->create();
        $collection->addQuestionFilter($questionId);
        $collection->orderByAdminFirst();
        
        return $collection->getItems();
    }

    public function getCountByQuestionId($questionId)
    {
        $collection = $this->answerCollectionFactory->create();
        $collection->addQuestionFilter($questionId);
        
        return $collection->getSize();
    }

    public function incrementHelpfulCount($answerId)
    {
        $answer = $this->getById($answerId);
        $answer->incrementHelpfulCount();
        return $this->save($answer);
    }

    public function decrementHelpfulCount($answerId)
    {
        $answer = $this->getById($answerId);
        $answer->decrementHelpfulCount();
        return $this->save($answer);
    }
}
