<?php

namespace EDU\InventoryReport\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\CatalogInventory\Helper\Stock as StockHelper;
use Magento\Store\Model\StoreManagerInterface;

class Inventory extends Template
{
    /**
     * @var ProductCollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var StockHelper
     */
    protected $stockHelper;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param Template\Context $context
     * @param ProductCollectionFactory $productCollectionFactory
     * @param StockHelper $stockHelper
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ProductCollectionFactory $productCollectionFactory,
        StockHelper $stockHelper,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->stockHelper = $stockHelper;
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve the list of all products that are in stock.
     * @return Collection
     */
    public function getProducts()
    {
        $collection = $this->productCollectionFactory->create();

        // Add all necessary attributes
        $collection->addAttributeToSelect('*');

        // Filter by enabled products only
        $collection->addAttributeToFilter('status',
            \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);

        // Filter by visibility (catalog, search, both)
        $collection->addAttributeToFilter('visibility', [
            'in' => [
                \Magento\Catalog\Model\Product\Visibility::VISIBILITY_IN_CATALOG,
                \Magento\Catalog\Model\Product\Visibility::VISIBILITY_IN_SEARCH,
                \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH
            ]
        ]);

        // Add store filter
        $collection->addStoreFilter($this->storeManager->getStore());

        // Add stock filter - this will only show products that are in stock
        $this->stockHelper->addIsInStockFilterToCollection($collection);

        // Set page size to limit results (optional)
        $collection->setPageSize(50);

        // Load the collection
        $collection->load();

        return $collection;
    }

    /**
     * Get total count of in-stock products
     * @return int
     */
    public function getProductCount()
    {
        return $this->getProducts()->getSize();
    }

    /**
     * Get total count of all products (for debugging)
     * @return int
     */
    public function getAllProductCount()
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('status',
            \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
        $collection->addStoreFilter($this->storeManager->getStore());
        return $collection->getSize();
    }

    /**
     * Get in-stock products
     * @return Collection
     */
    public function getInStockProducts()
    {
        $collection = $this->getAllProducts();
        $this->stockHelper->addIsInStockFilterToCollection($collection);
        return $collection;
    }

    /**
     * Get out-of-stock products
     * @return Collection
     */
    public function getOutOfStockProducts()
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('status',
            \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
        $collection->addAttributeToFilter('visibility', [
            'in' => [
                \Magento\Catalog\Model\Product\Visibility::VISIBILITY_IN_CATALOG,
                \Magento\Catalog\Model\Product\Visibility::VISIBILITY_IN_SEARCH,
                \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH
            ]
        ]);
        $collection->addStoreFilter($this->storeManager->getStore());

        // Join stock status table
        $collection->joinField(
            'stock_status',
            'cataloginventory_stock_status',
            'stock_status',
            'product_id=entity_id',
            '{{table}}.stock_id=1',
            'left'
        );

        // Filter for out of stock products
        $collection->addFieldToFilter('stock_status', ['eq' => 0]);

        return $collection;
    }

    /**
     * Get all enabled products
     * @return Collection
     */
    public function getAllProducts()
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('status',
            \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
        $collection->addAttributeToFilter('visibility', [
            'in' => [
                \Magento\Catalog\Model\Product\Visibility::VISIBILITY_IN_CATALOG,
                \Magento\Catalog\Model\Product\Visibility::VISIBILITY_IN_SEARCH,
                \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH
            ]
        ]);
        $collection->addStoreFilter($this->storeManager->getStore());
        $collection->load();
        return $collection;
    }
}
