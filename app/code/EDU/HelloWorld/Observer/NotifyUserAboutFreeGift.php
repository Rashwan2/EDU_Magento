<?php

namespace EDU\HelloWorld\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;

class NotifyUserAboutFreeGift implements ObserverInterface
{
    protected $messageManager;

    public function __construct(ManagerInterface $messageManager)
    {
        $this->messageManager = $messageManager;
    }

    public function execute(Observer $observer)
    {
        // Get the product ID from the event
        $productSku = $observer->getEvent()->getData('product_sku');

        // You can use the product ID to fetch more details about the free gift if needed
        if ($productSku) {
            // Notify the user with a success message
            $this->messageManager->addSuccessMessage(__('A free gift has been added to your cart!'));
        }
    }
}
