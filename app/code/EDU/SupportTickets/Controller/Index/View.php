<?php

namespace EDU\SupportTickets\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session as CustomerSession;
use EDU\SupportTickets\Api\TicketRepositoryInterface;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\Framework\Exception\NoSuchEntityException;

class View implements HttpGetActionInterface
{
    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;
    /**
     * @var PageFactory
     */
    protected $pageFactory;
    /**
     * @var CustomerSession
     */
    protected $customerSession;
    /**
     * @var TicketRepositoryInterface
     */
    protected $ticketRepository;

    /**
     * @var RequestInterface
     */
    protected $request;
    /**
     * @var MessageManager
     */
    protected $messageManager;

    public function __construct(
        PageFactory $pageFactory,
        CustomerSession $customerSession,
        TicketRepositoryInterface $ticketRepository,
        RedirectFactory $resultRedirectFactory,
        RequestInterface $request,
        MessageManager $messageManager
    ) {
        $this->pageFactory = $pageFactory;
        $this->customerSession = $customerSession;
        $this->ticketRepository = $ticketRepository;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->request = $request;
        $this->messageManager = $messageManager;
    }

    public function execute()
    {
        $ticketId = $this->request->getParam('id');

        try {
            $ticket = $this->ticketRepository->getById($ticketId);

            // Check if customer owns this ticket
            if ($this->customerSession->isLoggedIn() &&
                $ticket->getCustomerId() != $this->customerSession->getCustomerId()) {
                $this->messageManager->addErrorMessage(__('You are not authorized to view this ticket.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('supporttickets/index/index');
            }


            $resultPage = $this->pageFactory->create();
            $resultPage->getConfig()->getTitle()->set(__('Ticket Info'));
            $resultPage->getLayout()->getBlock('ticket.view')->setTicket($ticket);


            return $resultPage;

        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('Ticket not found.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('supporttickets/index/index');
        }
    }
}
