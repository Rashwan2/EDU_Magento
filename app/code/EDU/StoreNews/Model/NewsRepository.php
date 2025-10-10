<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreNews\Model;

use EDU\StoreNews\Api\Data\NewsInterface;
use EDU\StoreNews\Api\Data\NewsInterfaceFactory;
use EDU\StoreNews\Api\Data\NewsSearchResultsInterface;
use EDU\StoreNews\Api\Data\NewsSearchResultsInterfaceFactory;
use EDU\StoreNews\Api\NewsRepositoryInterface;
use EDU\StoreNews\Model\ResourceModel\News as NewsResourceModel;
use EDU\StoreNews\Model\ResourceModel\News\CollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;

/**
 * News Repository
 */
class NewsRepository implements NewsRepositoryInterface
{
    /**
     * @var NewsResourceModel
     */
    protected $resource;

    /**
     * @var NewsFactory
     */
    protected $newsFactory;

    /**
     * @var NewsInterfaceFactory
     */
    protected $newsDataFactory;

    /**
     * @var CollectionFactory
     */
    protected $newsCollectionFactory;

    /**
     * @var NewsSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @param NewsResourceModel $resource
     * @param NewsFactory $newsFactory
     * @param NewsInterfaceFactory $newsDataFactory
     * @param CollectionFactory $newsCollectionFactory
     * @param NewsSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        NewsResourceModel $resource,
        NewsFactory $newsFactory,
        NewsInterfaceFactory $newsDataFactory,
        CollectionFactory $newsCollectionFactory,
        NewsSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->newsFactory = $newsFactory;
        $this->newsDataFactory = $newsDataFactory;
        $this->newsCollectionFactory = $newsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * Save news
     *
     * @param NewsInterface $news
     * @return NewsInterface
     * @throws CouldNotSaveException
     */
    public function save(NewsInterface $news)
    {
        try {
            $this->resource->save($news);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__('Could not save the news: %1', $exception->getMessage()));
        }
        return $news;
    }

    /**
     * Load news data by given news identity
     *
     * @param int $newsId
     * @return NewsInterface
     * @throws NoSuchEntityException
     */
    public function getById($newsId)
    {
        $news = $this->newsFactory->create();
        $this->resource->load($news, $newsId);
        if (!$news->getNewsId()) {
            throw new NoSuchEntityException(__('News with id "%1" does not exist.', $newsId));
        }
        return $news;
    }

    /**
     * Load news data collection by given search criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return NewsSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $collection = $this->newsCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Delete news
     *
     * @param NewsInterface $news
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(NewsInterface $news)
    {
        try {
            $this->resource->delete($news);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__('Could not delete the news: %1', $exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete news by given news identity
     *
     * @param int $newsId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($newsId)
    {
        return $this->delete($this->getById($newsId));
    }
}
