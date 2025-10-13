<?php

namespace EDU\SupportTickets\Controller\Message;

use EDU\SupportTickets\Api\Data\MessageInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\Customer\Model\Session as CustomerSession;
use EDU\SupportTickets\Api\MessageRepositoryInterface;
use EDU\SupportTickets\Api\TicketRepositoryInterface;
use EDU\SupportTickets\Api\Data\MessageInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class Add implements HttpPostActionInterface
{
    /**
     * @var CustomerSession
     */
    protected $customerSession;
    /**
     * @var MessageRepositoryInterface
     */
    protected $messageRepository;
    /**
     * @var TicketRepositoryInterface
     */
    protected $ticketRepository;
    /**
     * @var MessageInterfaceFactory
     */
    protected $messageFactory;
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
        MessageRepositoryInterface $messageRepository,
        TicketRepositoryInterface $ticketRepository,
        MessageInterfaceFactory $messageFactory,
        RequestInterface $request,
        MessageManager $messageManager,
        RedirectFactory $resultRedirectFactory
    ) {
        $this->customerSession = $customerSession;
        $this->messageRepository = $messageRepository;
        $this->ticketRepository = $ticketRepository;
        $this->messageFactory = $messageFactory;
        $this->request = $request;
        $this->messageManager = $messageManager;
        $this->resultRedirectFactory = $resultRedirectFactory;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!$this->request->isPost()) {
            return $resultRedirect->setPath('supporttickets/index/index');
        }

        try {
            $data = $this->request->getPostValue();
            $ticketId = $data['ticket_id'];

            $ticket = $this->ticketRepository->getById($ticketId);

            // Check if customer owns this ticket
            if ($this->customerSession->isLoggedIn() &&
                $ticket->getCustomerId() != $this->customerSession->getCustomerId()) {
                $this->messageManager->addErrorMessage(
                    __('You are not authorized to add messages to this ticket.')
                );
                return $resultRedirect->setPath('supporttickets/index/index');
            }

            $message = $this->messageFactory->create();
            $message->setTicketId($ticketId);
            $message->setMessage($data['message']);
            $message->setIsInternal(false);

            if ($this->customerSession->isLoggedIn()) {
                $customer = $this->customerSession->getCustomer();
                $message->setSenderId($customer->getId());
                $message->setSenderType(MessageInterface::SENDER_TYPE_CUSTOMER);
                $message->setSenderName($customer->getName());
                $message->setSenderEmail($customer->getEmail());
            } else {
                $message->setSenderType(MessageInterface::SENDER_TYPE_CUSTOMER);
                $message->setSenderName($ticket->getCustomerName());
                $message->setSenderEmail($ticket->getCustomerEmail());
            }

            $this->messageRepository->save($message);

            // Update ticket last reply time
            $ticket->setLastReplyAt(date('Y-m-d H:i:s'));
            $this->ticketRepository->save($ticket);

            $this->messageManager->addSuccessMessage(
                __('Your message has been added successfully.')
            );
            return $resultRedirect->setPath('supporttickets/index/view', ['id' => $ticketId]);

        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('Ticket not found.'));
            return $resultRedirect->setPath('supporttickets/index/index');
        } catch (CouldNotSaveException $e) {
            $this->messageManager->addErrorMessage(
                __('Unable to add message. Please try again.')
            );
            return $resultRedirect->setPath('supporttickets/index/view', ['id' => $ticketId]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('An error occurred while adding the message.')
            );
            return $resultRedirect->setPath('supporttickets/index/index');
        }
    }
}
