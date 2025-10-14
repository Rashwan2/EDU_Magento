<?php

namespace EDU\SupportTickets\Controller\Index;

use EDU\SupportTickets\Api\Data\TicketInterface;
use Magento\Customer\Model\Session as CustomerSession;
use EDU\SupportTickets\Api\TicketRepositoryInterface;
use EDU\SupportTickets\Api\Data\TicketInterfaceFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\CouldNotSaveException;

class Save implements HttpPostActionInterface
{
    /**
     * @var CustomerSession
     */
    protected $customerSession;
    /**
     * @var TicketRepositoryInterface
     */
    protected $ticketRepository;
    /**
     * @var TicketInterfaceFactory
     */
    protected $ticketFactory;
    /**
     * @var RequestInterface
     */
    protected $request;
    /**
     * @var MessageManager
     */
    protected $messageManager;
    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;

    public function __construct(
        CustomerSession $customerSession,
        TicketRepositoryInterface $ticketRepository,
        TicketInterfaceFactory $ticketFactory,
        RequestInterface $request,
        MessageManager $messageManager,
        RedirectFactory $resultRedirectFactory
    ) {
        $this->customerSession = $customerSession;
        $this->ticketRepository = $ticketRepository;
        $this->ticketFactory = $ticketFactory;
        $this->request = $request;
        $this->messageManager = $messageManager;
        $this->resultRedirectFactory = $resultRedirectFactory;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!$this->request->isPost()) {
            return $resultRedirect->setPath('supporttickets/index/create');
        }

        try {
            $data = $this->request->getPostValue();

            $ticket = $this->ticketFactory->create();
            $ticket->setSubject($data['subject']);
            $ticket->setDescription($data['description']);
            $ticket->setCategoryId($data['category_id']);
            $ticket->setPriorityId($data['priority_id']);
            $ticket->setStatus(TicketInterface::STATUS_OPEN);
            $ticket->setTicketNumber($this->ticketRepository->generateTicketNumber());

            if ($this->customerSession->isLoggedIn()) {
                $customer = $this->customerSession->getCustomer();
                $ticket->setCustomerId($customer->getId());
                $ticket->setCustomerEmail($customer->getEmail());
                $ticket->setCustomerName($customer->getName());

                // Update any existing tickets with the same email to have the customer ID
                $this->updateExistingTicketsWithCustomerId($customer->getId(), $customer->getEmail());
            } else {
                $ticket->setCustomerEmail($data['customer_email']);
                $ticket->setCustomerName($data['customer_name']);
            }

            $this->ticketRepository->save($ticket);
            if ($this->customerSession->isLoggedIn()) {
                $this->messageManager->addSuccessMessage(
                    __('Your support ticket has been created successfully. Ticket number: %1',
                        $ticket->getTicketNumber())
                );
                return $resultRedirect->setPath('supporttickets/index/view', ['id' => $ticket->getTicketId()]);
            } else {
                $this->messageManager->addSuccessMessage(
                    __('Your support ticket has been created successfully. Ticket number: %1. Please log in or create an account to track your ticket.',
                        $ticket->getTicketNumber())
                );
                return $resultRedirect->setPath('supporttickets/index/create', ['id' => $ticket->getTicketId()]);
            }

        } catch (CouldNotSaveException $e) {
            $this->messageManager->addErrorMessage(
                __('Unable to create support ticket. Please try again.')
            );
            return $resultRedirect->setPath('supporttickets/index/create');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('An error occurred while creating the support ticket.')
            );
            return $resultRedirect->setPath('supporttickets/index/create');
        }
    }

    /**
     * Update existing tickets with customer ID when customer registers
     *
     * @param int $customerId
     * @param string $customerEmail
     * @return void
     */
    private function updateExistingTicketsWithCustomerId($customerId, $customerEmail)
    {
        try {
            $existingTickets = $this->ticketRepository->getByCustomerEmail($customerEmail);

            foreach ($existingTickets as $ticket) {
                // Only update tickets that don't have a customer ID yet
                if (!$ticket->getCustomerId()) {
                    $ticket->setCustomerId($customerId);
                    $this->ticketRepository->save($ticket);
                }
            }
        } catch (\Exception $e) {
            // Log error but don't break the main flow
            // Could add logging here if needed
        }
    }
}
