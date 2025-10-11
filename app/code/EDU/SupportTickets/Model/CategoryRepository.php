<?php

namespace EDU\SupportTickets\Model;

use EDU\SupportTickets\Api\Data\CategoryInterface;
use EDU\SupportTickets\Api\CategoryRepositoryInterface;
use EDU\SupportTickets\Model\ResourceModel\Category as CategoryResourceModel;
use EDU\SupportTickets\Model\ResourceModel\Category\Collection as CategoryCollection;
use EDU\SupportTickets\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Filter\TranslitUrl;

class CategoryRepository implements CategoryRepositoryInterface
{
    protected $categoryFactory;
    protected $categoryResourceModel;
    protected $categoryCollectionFactory;
    protected $searchResultsFactory;
    protected $collectionProcessor;
    protected $translitUrl;

    public function __construct(
        \EDU\SupportTickets\Model\CategoryFactory $categoryFactory,
        CategoryResourceModel $categoryResourceModel,
        CategoryCollectionFactory $categoryCollectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        TranslitUrl $translitUrl
    ) {
        $this->categoryFactory = $categoryFactory;
        $this->categoryResourceModel = $categoryResourceModel;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->translitUrl = $translitUrl;
    }

    public function save(CategoryInterface $category)
    {
        try {
            // Generate slug if not set
            if (!$category->getSlug()) {
                $category->setSlug($this->generateSlug($category->getName()));
            }
            
            $this->categoryResourceModel->save($category);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__('Could not save the category: %1', $exception->getMessage()));
        }
        return $category;
    }

    public function getById($categoryId)
    {
        $category = $this->categoryFactory->create();
        $this->categoryResourceModel->load($category, $categoryId);
        if (!$category->getCategoryId()) {
            throw new NoSuchEntityException(__('Category with id "%1" does not exist.', $categoryId));
        }
        return $category;
    }

    public function getBySlug($slug)
    {
        $category = $this->categoryFactory->create();
        $this->categoryResourceModel->load($category, $slug, 'slug');
        if (!$category->getCategoryId()) {
            throw new NoSuchEntityException(__('Category with slug "%1" does not exist.', $slug));
        }
        return $category;
    }

    public function getActiveCategories()
    {
        $collection = $this->categoryCollectionFactory->create();
        $collection->addActiveFilter(true);
        $collection->orderBySortOrder();
        return $collection->getItems();
    }

    public function getOrderedCategories()
    {
        $collection = $this->categoryCollectionFactory->create();
        $collection->orderBySortOrder();
        return $collection->getItems();
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->categoryCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    public function delete(CategoryInterface $category)
    {
        try {
            $this->categoryResourceModel->delete($category);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__('Could not delete the category: %1', $exception->getMessage()));
        }
        return true;
    }

    public function deleteById($categoryId)
    {
        return $this->delete($this->getById($categoryId));
    }

    public function canDelete($categoryId)
    {
        // Check if category is used by any tickets
        $ticketCollection = $this->categoryCollectionFactory->create();
        $ticketCollection->addFieldToFilter('category_id', $categoryId);
        return $ticketCollection->getSize() === 0;
    }

    public function generateSlug($name)
    {
        $slug = $this->translitUrl->filter($name);
        $slug = strtolower($slug);
        $slug = preg_replace('/[^a-z0-9\-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        // Ensure uniqueness
        $originalSlug = $slug;
        $counter = 1;
        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    protected function slugExists($slug)
    {
        try {
            $this->getBySlug($slug);
            return true;
        } catch (NoSuchEntityException $e) {
            return false;
        }
    }
}
