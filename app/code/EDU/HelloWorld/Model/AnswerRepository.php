<?php

namespace EDU\HelloWorld\Model;

use EDU\HelloWorld\Api\Data\AnswerInterface;
use EDU\HelloWorld\Model\AnswerFactory;
use EDU\HelloWorld\Api\AnswerRepositoryInterface;
use EDU\HelloWorld\Model\ResourceModel\Answer as AnswerResourceModel;
use EDU\HelloWorld\Model\ResourceModel\Answer\Collection as AnswerCollection;
use EDU\HelloWorld\Model\ResourceModel\Answer\CollectionFactory as AnswerCollectionFactory;
use EDU\HelloWorld\Model\ResourceModel\Question as QuestionResourceModel;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class AnswerRepository implements AnswerRepositoryInterface
{
    protected $answerFactory;
    protected $answerResourceModel;
    protected $answerCollectionFactory;
    protected $questionResourceModel;
    protected $searchCriteriaBuilder;

    public function __construct(
        AnswerFactory $answerFactory,
        AnswerResourceModel $answerResourceModel,
        AnswerCollectionFactory $answerCollectionFactory,
        QuestionResourceModel $questionResourceModel,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->answerFactory = $answerFactory;
        $this->answerResourceModel = $answerResourceModel;
        $this->answerCollectionFactory = $answerCollectionFactory;
        $this->questionResourceModel = $questionResourceModel;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function save(AnswerInterface $answer)
    {
        try {
            $this->answerResourceModel->save($answer);

            // Update the question's answered_count
            $this->updateQuestionAnsweredCount($answer->getQuestionId());

            return $answer;
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__('Could not save answer: %1', $exception->getMessage()));
        }
    }

    private function updateQuestionAnsweredCount($questionId)
    {
        // Count answers for this question using collection
        $collection = $this->answerCollectionFactory->create();
        $collection->addQuestionFilter($questionId);
        $answerCount = $collection->getSize();

        // Update the question's answered_count
        $this->questionResourceModel->updateAnsweredCount($questionId, $answerCount);
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
            $questionId = $answer->getQuestionId();
            $this->answerResourceModel->delete($answer);
            
            // Update the question's answered_count after deletion
            $this->updateQuestionAnsweredCount($questionId);
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
