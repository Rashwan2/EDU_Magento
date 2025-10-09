<?php

namespace EDU\HelloWorld\Observer;

use Magento\Catalog\Model\ProductFactory;
use Magento\Checkout\Model\Cart;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AddFreeGiftObserver implements ObserverInterface
{
    protected $cart;
    protected $productFactory;
    protected $eventManager;

    public function __construct(
        Cart $cart,
        ProductFactory $productFactory,
        ManagerInterface $eventManager
    ) {
        $this->cart = $cart;
        $this->productFactory = $productFactory;
        $this->eventManager = $eventManager;
    }

    public function execute(Observer $observer)
    {
        $product = $observer->getEvent()->getData('product');

        if ($product->getId() && $product->getTypeId() === 'simple') {
            $freeGiftProductId = 2; // Replace with your free gift product ID
            $freeGiftProduct = $this->productFactory->create()->load($freeGiftProductId);

            // Check if the free gift is already in the cart
            $items = $this->cart->getQuote()->getItemsCollection();
            $isGiftInCart = false;

            foreach ($items as $item) {
                if ($item->getProductId() == $freeGiftProductId) {
                    $isGiftInCart = true;
                    break;
                }
            }

            // Add the free gift if not already in the cart
            if (!$isGiftInCart) {
                $this->cart->addProduct($freeGiftProduct, 1); // Add the free gift product
                $this->cart->save();

                // Dispatch the custom event after adding the free gift
                $this->eventManager->dispatch('vendor_freegift_added', ['product_id' => $freeGiftProductId]);
            }
        }
    }
}
