<?php
namespace EDU\SupportTickets\Block\Ticket;

use Magento\Framework\View\Element\Template;

class FormBlock extends Template
{
    protected $categories = [];
    protected $priorities = [];
    protected $customerSession;
    protected $categoryCollectionFactory;
    protected $priorityCollectionFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \EDU\SupportTickets\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \EDU\SupportTickets\Model\ResourceModel\Priority\CollectionFactory $priorityCollectionFactory,
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
}
