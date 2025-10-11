<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreBlogs\Block\Blog;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use EDU\StoreBlogs\Api\BlogRepositoryInterface;
use Magento\Framework\Registry;
use Magento\Cms\Model\Template\FilterProvider;

/**
 * Blog View Block
 */
class ViewBlock extends Template
{
    /**
     * @var BlogRepositoryInterface
     */
    protected $blogRepository;

    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var FilterProvider
     */
    protected $filterProvider;

    /**
     * @param Context $context
     * @param BlogRepositoryInterface $blogRepository
     * @param Registry $coreRegistry
     * @param FilterProvider $filterProvider
     * @param array $data
     */
    public function __construct(
        Context $context,
        BlogRepositoryInterface $blogRepository,
        Registry $coreRegistry,
        FilterProvider $filterProvider,
        array $data = []
    ) {
        $this->blogRepository = $blogRepository;
        $this->coreRegistry = $coreRegistry;
        $this->filterProvider = $filterProvider;
        parent::__construct($context, $data);
    }

    /**
     * Get current blog
     *
     * @return \EDU\StoreBlogs\Model\Blog
     */
    public function getCurrentBlog()
    {
        if (!$this->coreRegistry->registry('current_blog')) {
            $blogId = $this->getRequest()->getParam('id');
            try {
                $blog = $this->blogRepository->getById($blogId);
                $this->coreRegistry->register('current_blog', $blog);
            } catch (\Exception $e) {
                $this->coreRegistry->register('current_blog', null);
            }
        }

        return $this->coreRegistry->registry('current_blog');
    }

    /**
     * Get back URL
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('blogs/index/index');
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
