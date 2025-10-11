<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreBlogs\Model\ResourceModel\Blog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use EDU\StoreBlogs\Model\Blog;
use EDU\StoreBlogs\Model\ResourceModel\Blog as BlogResourceModel;

/**
 * Blog Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'blog_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'edu_store_blogs_collection';

    /**
     * @var string
     */
    protected $_eventObject = 'blog_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Blog::class, BlogResourceModel::class);
    }

    /**
     * Add published filter
     *
     * @return $this
     */
    public function addPublishedFilter()
    {
        $this->addFieldToFilter('status', Blog::STATUS_PUBLISHED);
        return $this;
    }

    /**
     * Add store filter
     *
     * @param int|array $storeId
     * @return $this
     */
    public function addStoreFilter($storeId)
    {
        if (is_array($storeId)) {
            $storeIds = $storeId;
            $storeIds[] = 0; // Include "All Store Views" (store_id = 0)
            $this->addFieldToFilter('store_id', ['in' => $storeIds]);
        } else {
            $this->addFieldToFilter('store_id', ['in' => [$storeId, 0]]);
        }
        return $this;
    }

    /**
     * Order by published date
     *
     * @param string $direction
     * @return $this
     */
    public function orderByPublishedAt($direction = 'DESC')
    {
        $this->setOrder('created_at', $direction); // Use created_at as fallback
        $this->setOrder('published_at', $direction);
        return $this;
    }
}
