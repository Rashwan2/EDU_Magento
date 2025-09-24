<?php

namespace EDU\HelloWorld\Block;


use Magento\Catalog\Model\ProductRepository;
use Magento\Checkout\Model\Cart;

class Display extends \Magento\Framework\View\Element\Template
{
    protected $checkoutSession;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        Cart $cart,
        ProductRepository $productRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->cart = $cart;
        $this->productRepository = $productRepository;
    }

    /**
     * Get all items from cart
     */
    public function getCartItems()
    {
        return $this->cart->getQuote()->getAllVisibleItems();
    }

    /**
     * Get product by ID
     */
    public function getProductById($productId)
    {
        try {
            return $this->productRepository->getById($productId);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Format price
     */
    public function formatPrice($price)
    {
        return $this->_storeManager->getStore()->getCurrentCurrency()->format($price);
    }

}
