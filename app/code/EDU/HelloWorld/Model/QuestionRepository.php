<?php

namespace EDU\HelloWorld\Model;

use EDU\HelloWorld\Api\Data\QuestionInterface;
use EDU\HelloWorld\Api\Data\QuestionInterfaceFactory;
use EDU\HelloWorld\Api\Data\QuestionSearchResultsInterface;
use EDU\HelloWorld\Api\Data\QuestionSearchResultsInterfaceFactory;
use EDU\HelloWorld\Api\QuestionRepositoryInterface;
use EDU\HelloWorld\Model\ResourceModel\Question as QuestionResourceModel;
use EDU\HelloWorld\Model\ResourceModel\Question\Collection as QuestionCollection;
use EDU\HelloWorld\Model\ResourceModel\Question\CollectionFactory as QuestionCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class QuestionRepository implements QuestionRepositoryInterface
{
    protected $questionFactory;
    protected $questionResourceModel;
    protected $questionCollectionFactory;
    protected $searchResultsFactory;
    protected $collectionProcessor;

    public function __construct(
        QuestionInterfaceFactory $questionFactory,
        QuestionResourceModel $questionResourceModel,
        QuestionCollectionFactory $questionCollectionFactory,
        QuestionSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->questionFactory = $questionFactory;
        $this->questionResourceModel = $questionResourceModel;
        $this->questionCollectionFactory = $questionCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    public function save(QuestionInterface $question)
    {
        try {
            $this->questionResourceModel->save($question);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__('Could not save the question: %1', $exception->getMessage()));
        }
        return $question;
    }

    public function getById($questionId)
    {
        $question = $this->questionFactory->create();
        $this->questionResourceModel->load($question, $questionId);
        if (!$question->getQuestionId()) {
            throw new NoSuchEntityException(__('Question with id "%1" does not exist.', $questionId));
        }
        return $question;
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->questionCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    public function delete(QuestionInterface $question)
    {
        try {
            $this->questionResourceModel->delete($question);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__('Could not delete the question: %1', $exception->getMessage()));
        }
        return true;
    }

    public function deleteById($questionId)
    {
        return $this->delete($this->getById($questionId));
    }

    public function getByProductId($productId, $status = null)
    {
        $collection = $this->questionCollectionFactory->create();
        $collection->addProductFilter($productId);
        
        if ($status) {
            $collection->addStatusFilter($status);
        }
        
        $collection->orderByCreatedAt('DESC');
        
        return $collection->getItems();
    }

    public function approve($questionId)
    {
        $question = $this->getById($questionId);
        $question->approve();
        return $this->save($question);
    }

    public function reject($questionId)
    {
        $question = $this->getById($questionId);
        $question->reject();
        return $this->save($question);
    }
}
