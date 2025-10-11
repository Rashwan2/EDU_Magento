<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreBlogs\Model;

use EDU\StoreBlogs\Api\Data\BlogInterface;
use EDU\StoreBlogs\Api\Data\BlogInterfaceFactory;
use EDU\StoreBlogs\Api\Data\BlogSearchResultsInterface;
use EDU\StoreBlogs\Api\Data\BlogSearchResultsInterfaceFactory;
use EDU\StoreBlogs\Api\BlogRepositoryInterface;
use EDU\StoreBlogs\Model\ResourceModel\Blog as BlogResourceModel;
use EDU\StoreBlogs\Model\ResourceModel\Blog\CollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;

/**
 * Blog Repository
 */
class BlogRepository implements BlogRepositoryInterface
{
    /**
     * @var BlogResourceModel
     */
    protected $resource;

    /**
     * @var BlogFactory
     */
    protected $blogFactory;

    /**
     * @var BlogInterfaceFactory
     */
    protected $blogDataFactory;

    /**
     * @var CollectionFactory
     */
    protected $blogCollectionFactory;

    /**
     * @var BlogSearchResultsInterfaceFactory
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
     * @param BlogResourceModel $resource
     * @param BlogFactory $blogFactory
     * @param BlogInterfaceFactory $blogDataFactory
     * @param CollectionFactory $blogCollectionFactory
     * @param BlogSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        BlogResourceModel $resource,
        BlogFactory $blogFactory,
        BlogInterfaceFactory $blogDataFactory,
        CollectionFactory $blogCollectionFactory,
        BlogSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->blogFactory = $blogFactory;
        $this->blogDataFactory = $blogDataFactory;
        $this->blogCollectionFactory = $blogCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * Save blog
     *
     * @param BlogInterface $blog
     * @return BlogInterface
     * @throws CouldNotSaveException
     */
    public function save(BlogInterface $blog)
    {
        try {
            $this->resource->save($blog);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__('Could not save the blog: %1', $exception->getMessage()));
        }
        return $blog;
    }

    /**
     * Load blog data by given blog identity
     *
     * @param int $blogId
     * @return BlogInterface
     * @throws NoSuchEntityException
     */
    public function getById($blogId)
    {
        $blog = $this->blogFactory->create();
        $this->resource->load($blog, $blogId);
        if (!$blog->getBlogId()) {
            throw new NoSuchEntityException(__('Blog with id "%1" does not exist.', $blogId));
        }
        return $blog;
    }

    /**
     * Load blog data collection by given search criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return BlogSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $collection = $this->blogCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Delete blog
     *
     * @param BlogInterface $blog
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(BlogInterface $blog)
    {
        try {
            $this->resource->delete($blog);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__('Could not delete the blog: %1', $exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete blog by given blog identity
     *
     * @param int $blogId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($blogId)
    {
        return $this->delete($this->getById($blogId));
    }
}
