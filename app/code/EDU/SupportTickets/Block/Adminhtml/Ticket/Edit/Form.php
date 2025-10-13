<?php
namespace EDU\SupportTickets\Block\Adminhtml\Ticket\Edit;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use EDU\SupportTickets\Api\TicketRepositoryInterface;
use EDU\SupportTickets\Api\CategoryRepositoryInterface;
use EDU\SupportTickets\Api\PriorityRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Form extends Template
{
    protected $ticketRepository;
    protected $categoryRepository;
    protected $priorityRepository;
    protected $ticket;

    public function __construct(
        Context $context,
        TicketRepositoryInterface $ticketRepository,
        CategoryRepositoryInterface $categoryRepository,
        PriorityRepositoryInterface $priorityRepository,
        array $data = []
    ) {
        $this->ticketRepository = $ticketRepository;
        $this->categoryRepository = $categoryRepository;
        $this->priorityRepository = $priorityRepository;
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

    public function getCategories()
    {
        try {
            return $this->categoryRepository->getActiveCategories();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getPriorities()
    {
        try {
            return $this->priorityRepository->getActivePriorities();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getFormAction()
    {
        return $this->getUrl('supporttickets/ticket/save');
    }

    public function getBackUrl()
    {
        return $this->getUrl('supporttickets/ticket/index');
    }

    public function getStatusOptions()
    {
        return [
            'open' => __('Open'),
            'in_progress' => __('In Progress'),
            'waiting_customer' => __('Waiting for Customer'),
            'resolved' => __('Resolved'),
            'closed' => __('Closed')
        ];
    }
}
