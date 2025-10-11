<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreBlogs\Block\Blog;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use EDU\StoreBlogs\Model\ResourceModel\Blog\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Cms\Model\Template\FilterProvider;

/**
 * Blog List Block
 */
class ListBlock extends Template
{
    /**
     * @var CollectionFactory
     */
    protected $blogCollectionFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var FilterProvider
     */
    protected $filterProvider;

    /**
     * @param Context $context
     * @param CollectionFactory $blogCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param FilterProvider $filterProvider
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $blogCollectionFactory,
        StoreManagerInterface $storeManager,
        FilterProvider $filterProvider,
        array $data = []
    ) {
        $this->blogCollectionFactory = $blogCollectionFactory;
        $this->storeManager = $storeManager;
        $this->filterProvider = $filterProvider;
        parent::__construct($context, $data);
    }

    /**
     * Get blog collection
     *
     * @return \EDU\StoreBlogs\Model\ResourceModel\Blog\Collection
     */
    public function getBlogCollection()
    {
        $collection = $this->blogCollectionFactory->create();
        $collection->addPublishedFilter()
                   ->addStoreFilter($this->storeManager->getStore()->getId())
                   ->orderByPublishedAt('DESC');

        return $collection;
    }

    /**
     * Get blog view URL
     *
     * @param \EDU\StoreBlogs\Model\Blog $blog
     * @return string
     */
    public function getBlogUrl($blog)
    {
        return $this->getUrl('blogs/index/view', ['id' => $blog->getBlogId()]);
    }

    /**
     * Format published date
     *
     * @param \DateTimeInterface|string|null $date
     * @param int $format
     * @param bool $showTime
     * @param string|null $timezone
     * @return string
     */
    public function formatDate($date = null, $format = \IntlDateFormatter::MEDIUM, $showTime = false, $timezone = null)
    {
        if (!$date) {
            return '';
        }

        return parent::formatDate($date, $format, $showTime, $timezone);
    }

    /**
     * Filter WYSIWYG content
     *
     * @param string $content
     * @return string
     */
    public function filterWysiwygContent($content)
    {
        if (!$content) {
            return '';
        }

        return $this->filterProvider->getPageFilter()->filter($content);
    }


}
