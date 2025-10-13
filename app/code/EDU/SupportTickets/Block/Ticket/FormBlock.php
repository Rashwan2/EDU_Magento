<?php
namespace EDU\SupportTickets\Block\Ticket;

use EDU\SupportTickets\Model\ResourceModel\Category\CollectionFactory;
use EDU\SupportTickets\Model\ResourceModel\Priority\CollectionFactory as PriorityCollectionFactory;
use EDU\SupportTickets\Model\ResourceModel\Ticket\CollectionFactory as TicketCollectionFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class FormBlock extends Template
{
    protected $categories = [];
    protected $priorities = [];
    protected $customerTickets = [];
    protected $customerSession;
    protected $categoryCollectionFactory;
    protected $priorityCollectionFactory;
    protected $ticketCollectionFactory;

    public function __construct(
        Context $context,
        Session $customerSession,
        CollectionFactory $categoryCollectionFactory,
        PriorityCollectionFactory $priorityCollectionFactory,
        TicketCollectionFactory $ticketCollectionFactory,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->priorityCollectionFactory = $priorityCollectionFactory;
        $this->ticketCollectionFactory = $ticketCollectionFactory;
        parent::__construct($context, $data);
    }

    public function getCategories()
    {
        if (empty($this->categories)) {
            $collection = $this->categoryCollectionFactory->create();
            $collection->addFieldToFilter('is_active', 1);
            $collection->setOrder('sort_order', 'ASC');

            foreach ($collection as $category) {
                $this->categories[] = [
                    'value' => $category->getCategoryId(),
                    'label' => $category->getName()
                ];
            }
        }
        return $this->categories;
    }

    public function getPriorities()
    {
        if (empty($this->priorities)) {
            $collection = $this->priorityCollectionFactory->create();
            $collection->addFieldToFilter('is_active', 1);
            $collection->setOrder('sort_order', 'ASC');

            foreach ($collection as $priority) {
                $this->priorities[] = [
                    'value' => $priority->getPriorityId(),
                    'label' => $priority->getName(),
                    'color' => $priority->getColor()
                ];
            }
        }
        return $this->priorities;
    }

    public function getCurrentCustomer()
    {
        return $this->customerSession->getCustomer();
    }

    public function isCustomerLoggedIn()
    {
        return $this->customerSession->isLoggedIn();
    }

    public function getCustomerName()
    {
        if ($this->isCustomerLoggedIn()) {
            $customer = $this->getCurrentCustomer();
            return $customer->getFirstname() . ' ' . $customer->getLastname();
        }
        return '';
    }

    public function getCustomerEmail()
    {
        if ($this->isCustomerLoggedIn()) {
            $customer = $this->getCurrentCustomer();
            return $customer->getEmail();
        }
        return '';
    }

    public function getFormAction()
    {
        return $this->getUrl('supporttickets/index/save');
    }

    public function getCustomerTickets()
    {
        if (empty($this->customerTickets) && $this->isCustomerLoggedIn()) {
            $customerId = $this->customerSession->getCustomerId();
            $collection = $this->ticketCollectionFactory->create();
            $collection->addFieldToFilter('customer_id', $customerId);
            $collection->setOrder('created_at', 'DESC');
            $collection->setPageSize(20); // Limit to 20 most recent tickets

            foreach ($collection as $ticket) {
                $this->customerTickets[] = $ticket;
            }
        }
        return $this->customerTickets;
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

    /**
     * Format published date
     *
     * @param string $date
     * @return string
     */
    public function formatDate($date = null, $format = \IntlDateFormatter::MEDIUM, $showTime = true, $timezone = null)
    {
        if (!$date) {
            return '';
        }

        return parent::formatDate($date, $format, $showTime, $timezone);
    }
}
