<?php
namespace EDU\SupportTickets\Block\Ticket;

use EDU\SupportTickets\Model\ResourceModel\Category\CollectionFactory;
use EDU\SupportTickets\Model\ResourceModel\Priority\CollectionFactory as PriorityCollectionFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class FormBlock extends Template
{
    protected $categories = [];
    protected $priorities = [];
    protected $customerSession;
    protected $categoryCollectionFactory;
    protected $priorityCollectionFactory;

    public function __construct(
        Context $context,
        Session $customerSession,
        CollectionFactory $categoryCollectionFactory,
        PriorityCollectionFactory $priorityCollectionFactory,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->priorityCollectionFactory = $priorityCollectionFactory;
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
}
