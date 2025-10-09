<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\InventoryReport\Block;


use Magento\Catalog\Model\ProductFactory;
use Magento\Checkout\Model\Cart;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Provide information to frontend storage manager
 *
 * @api
 * @since 102.0.0
 */
class Display extends Template
{
    private Cart $cart;
    private ProductFactory $productFactory;

    public function __construct(
        Context $context,
        Cart $cart,
        ProductFactory $productFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->cart = $cart;
        $this->productFactory = $productFactory;
    }

    public function getCartItems()
    {
        return $this->cart->getQuote()->getAllVisibleItems();
    }

    public function getProductById($productId)
    {
        return $this->productFactory->create()->load($productId);
    }

    public function formatPrice($price)
    {
        return $this->_storeManager->getStore()->getCurrentCurrency()->format($price);
    }
}
