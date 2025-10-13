<?php
namespace EDU\SupportTickets\Block\Adminhtml\Ticket;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use EDU\SupportTickets\Api\TicketRepositoryInterface;
use EDU\SupportTickets\Api\MessageRepositoryInterface;
use EDU\SupportTickets\Model\ResourceModel\Message\CollectionFactory as MessageCollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class View extends Template
{
    protected $ticketRepository;
    protected $messageRepository;
    protected $messageCollectionFactory;
    protected $ticket;
    protected $messages;

    public function __construct(
        Context $context,
        TicketRepositoryInterface $ticketRepository,
        MessageRepositoryInterface $messageRepository,
        MessageCollectionFactory $messageCollectionFactory,
        array $data = []
    ) {
        $this->ticketRepository = $ticketRepository;
        $this->messageRepository = $messageRepository;
        $this->messageCollectionFactory = $messageCollectionFactory;
        parent::__construct($context, $data);
    }

    public function getTicket()
    {
        if (!$this->ticket) {
            $ticketId = $this->getRequest()->getParam('id');
            if ($ticketId) {
                try {
                    $this->ticket = $this->ticketRepository->getById($ticketId);
                } catch (NoSuchEntityException $e) {
                    $this->ticket = null;
                }
            } else {
                $this->ticket = null;
            }
        }
        return $this->ticket;
    }

    public function getMessages()
    {
        if (!$this->messages && $this->getTicket()) {
            $collection = $this->messageCollectionFactory->create();
            $collection->addFieldToFilter('ticket_id', $this->getTicket()->getTicketId());
            $collection->setOrder('created_at', 'ASC');
            $this->messages = $collection->getItems();
        }
        return $this->messages ?? [];
    }

    public function getBackUrl()
    {
        return $this->getUrl('supporttickets/ticket/index');
    }

    public function getEditUrl()
    {
        return $this->getUrl('supporttickets/ticket/edit', ['id' => $this->getTicket()->getTicketId()]);
    }

    public function getStatusLabel($status)
    {
        $statusLabels = [
            'open' => __('Open'),
            'in_progress' => __('In Progress'),
            'waiting_customer' => __('Waiting for Customer'),
            'resolved' => __('Resolved'),
            'closed' => __('Closed')
        ];
        return $statusLabels[$status] ?? $status;
    }

    public function getStatusClass($status)
    {
        $statusClasses = [
            'open' => 'status-open',
            'in_progress' => 'status-in-progress',
            'waiting_customer' => 'status-waiting',
            'resolved' => 'status-resolved',
            'closed' => 'status-closed'
        ];
        return $statusClasses[$status] ?? 'status-default';
    }
}
