<?php

namespace EDU\HelloWorld\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Cart;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Event\ManagerInterface;

class AddFreeGiftObserver implements ObserverInterface
{
    protected $cart;
    protected $productFactory;
    protected $_eventManager;

    public function __construct(
        Cart $cart,
        ProductFactory $productFactory,
        ManagerInterface $eventManager
    ) {
        $this->cart = $cart;
        $this->productFactory = $productFactory;
        $this->_eventManager = $eventManager;
    }

    public function execute(Observer $observer)
    {
        $product = $observer->getEvent()->getData('product');

        if ($product->getId() && $product->getTypeId() === 'simple') {
            $freeGiftProductId = 2; // Replace with your free gift product ID
            $freeGiftProduct = $this->productFactory->create()->load($freeGiftProductId);

            $items = $this->cart->getQuote()->getItemsCollection();
            $isGiftInCart = false;

            foreach ($items as $item) {
                if ($item->getProductId() == $freeGiftProductId) {
                    $isGiftInCart = true;
                    break;
                }
            }

            if (!$isGiftInCart) {
                $this->cart->addProduct($freeGiftProduct, 1); // Add the free gift product
                $this->cart->save();

                // Notify the user about the free gift
                $this->_eventManager->dispatch('vendor_freegift_added', ['product_id' => $freeGiftProductId]);
            }
        }
    }
}
