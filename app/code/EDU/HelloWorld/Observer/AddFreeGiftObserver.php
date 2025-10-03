<?php

namespace EDU\HelloWorld\Observer;

use Magento\Checkout\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class AddFreeGiftObserver implements ObserverInterface
{
    protected $cartRepository;
    protected $productRepository;
    protected $eventManager;
    protected $messageManager;
    protected $request;
    private Session $_checkoutSession;

    public function __construct(
        CartRepositoryInterface $cartRepository,
        ProductRepository $productRepository,
        ManagerInterface $eventManager,
        MessageManagerInterface $messageManager,
        Session $checkoutSession,
    ) {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
        $this->eventManager = $eventManager;
        $this->messageManager = $messageManager;
        $this->_checkoutSession = $checkoutSession;
    }

    public function execute(Observer $observer)
    {
        $product = $observer->getEvent()->getData('product');
        $freeGiftProductSku = 'secondProduct'; // Replace with your free gift product SKU
        $cartId = $this->_checkoutSession->getQuoteId();

        // Only proceed if it's a simple product and not the gift product itself
        if ($product && $product->getTypeId() === 'simple' && $product->getSku() !== $freeGiftProductSku) {
            try {
                // Get the free gift product using ProductRepository
                $freeGiftProduct = $this->productRepository->get($freeGiftProductSku);

                if (!$cartId) {
                    return; // No cart ID available
                }

                $cart = $this->cartRepository->get($cartId);
                $cartItems = $cart->getAllVisibleItems();
                $isGiftInCart = false;

                // Check if the free gift is already in the cart
                foreach ($cartItems as $item) {
                    if ($item->getSku() === $freeGiftProductSku) {
                        $isGiftInCart = true;
                        break;
                    }
                }

                // Add the free gift if not already in the cart
                if (!$isGiftInCart && $freeGiftProduct) {
                    // Add product to cart using the quote
                    $cart->addProduct($freeGiftProduct, 1);
                    $this->cartRepository->save($cart);

                    // Dispatch custom event for additional processing if needed
                    $this->eventManager->dispatch('vendor_freegift_added', [
                        'product_sku' => $freeGiftProductSku,
                        'product_id' => $freeGiftProduct->getId(),
                        'product_name' => $freeGiftProduct->getName()
                    ]);
                }
            } catch (NoSuchEntityException $e) {
                // Product not found
                $this->messageManager->addErrorMessage(
                    __('Sorry, the free gift product is currently unavailable.')
                );
            } catch (\Exception $e) {
                // Log error and show user-friendly message
                $this->messageManager->addErrorMessage(
                    __('Sorry, we encountered an issue adding your free gift. Please try again.')
                );
            }
        }
    }
}
