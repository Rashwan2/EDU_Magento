<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreNews\Block\News;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use EDU\StoreNews\Api\NewsRepositoryInterface;
use Magento\Framework\Registry;

/**
 * News View Block
 */
class ViewBlock extends Template
{
    /**
     * @var NewsRepositoryInterface
     */
    protected $newsRepository;

    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @param Context $context
     * @param NewsRepositoryInterface $newsRepository
     * @param Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        Context $context,
        NewsRepositoryInterface $newsRepository,
        Registry $coreRegistry,
        array $data = []
    ) {
        $this->newsRepository = $newsRepository;
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    /**
     * Get current news
     *
     * @return \EDU\StoreNews\Model\News
     */
    public function getCurrentNews()
    {
        if (!$this->coreRegistry->registry('current_news')) {
            $newsId = $this->getRequest()->getParam('id');
            try {
                $news = $this->newsRepository->getById($newsId);
                $this->coreRegistry->register('current_news', $news);
            } catch (\Exception $e) {
                $this->coreRegistry->register('current_news', null);
            }
        }

        return $this->coreRegistry->registry('current_news');
    }

    /**
     * Get back URL
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('news/index/index');
    }

    /**
     * Format published date
     *
     * @param string $date
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
