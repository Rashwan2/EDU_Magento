<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreNews\Block\News;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use EDU\StoreNews\Model\ResourceModel\News\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * News List Block
 */
class ListBlock extends Template
{
    /**
     * @var CollectionFactory
     */
    protected $newsCollectionFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param Context $context
     * @param CollectionFactory $newsCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $newsCollectionFactory,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->newsCollectionFactory = $newsCollectionFactory;
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * Get news collection
     *
     * @return \EDU\StoreNews\Model\ResourceModel\News\Collection
     */
    public function getNewsCollection()
    {
        $collection = $this->newsCollectionFactory->create();
        $collection->addPublishedFilter()
                   ->addStoreFilter($this->storeManager->getStore()->getId())
                   ->orderByPublishedAt('DESC');

        return $collection;
    }

    /**
     * Get news view URL
     *
     * @param \EDU\StoreNews\Model\News $news
     * @return string
     */
    public function getNewsUrl($news)
    {
        return $this->getUrl('news/index/view', ['id' => $news->getNewsId()]);
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


}
