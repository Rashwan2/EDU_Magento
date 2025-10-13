<?php

namespace EDU\SupportTickets\Controller\Adminhtml\Message;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use EDU\SupportTickets\Api\MessageRepositoryInterface;
use EDU\SupportTickets\Api\TicketRepositoryInterface;
use EDU\SupportTickets\Api\Data\MessageInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Backend\Model\Auth\Session;

class Add extends Action
{
    const ADMIN_RESOURCE = 'EDU_SupportTickets::tickets';

    protected $messageRepository;
    protected $ticketRepository;
    protected $messageFactory;
    protected $authSession;

    public function __construct(
        Context $context,
        MessageRepositoryInterface $messageRepository,
        TicketRepositoryInterface $ticketRepository,
        MessageInterfaceFactory $messageFactory,
        Session $authSession
    ) {
        $this->messageRepository = $messageRepository;
        $this->ticketRepository = $ticketRepository;
        $this->messageFactory = $messageFactory;
        $this->authSession = $authSession;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if (!$this->getRequest()->isPost()) {
            $this->messageManager->addErrorMessage(__('Invalid request.'));
            return $resultRedirect->setPath('supporttickets/ticket/index');
        }

        try {
            $data = $this->getRequest()->getPostValue();
            $ticketId = $data['ticket_id'];
            
            $ticket = $this->ticketRepository->getById($ticketId);
            
            $message = $this->messageFactory->create();
            $message->setTicketId($ticketId);
            $message->setMessage($data['message']);
            $message->setIsInternal($data['is_internal'] ?? false);
            
            // Set admin as sender
            $adminUser = $this->authSession->getUser();
            $message->setSenderId($adminUser->getId());
            $message->setSenderType(\EDU\SupportTickets\Api\Data\MessageInterface::SENDER_TYPE_ADMIN);
            $message->setSenderName($adminUser->getFirstName() . ' ' . $adminUser->getLastName());
            $message->setSenderEmail($adminUser->getEmail());
            
            $this->messageRepository->save($message);
            
            // Update ticket last reply time
            $ticket->setLastReplyAt(date('Y-m-d H:i:s'));
            $this->ticketRepository->save($ticket);
            
            $this->messageManager->addSuccessMessage(__('Message has been added successfully.'));
            return $resultRedirect->setPath('supporttickets/ticket/view', ['id' => $ticketId]);
            
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('Ticket not found.'));
            return $resultRedirect->setPath('supporttickets/ticket/index');
        } catch (CouldNotSaveException $e) {
            $this->messageManager->addErrorMessage(__('Unable to add message. Please try again.'));
            return $resultRedirect->setPath('supporttickets/ticket/view', ['id' => $ticketId]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while adding the message.'));
            return $resultRedirect->setPath('supporttickets/ticket/index');
        }
    }
}
