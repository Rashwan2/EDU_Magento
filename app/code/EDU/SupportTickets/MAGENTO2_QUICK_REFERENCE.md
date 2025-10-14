# Magento 2 Quick Reference Guide

## Essential Commands

### Module Management
```bash
# Enable module
bin/magento module:enable EDU_SupportTickets

# Disable module
bin/magento module:disable EDU_SupportTickets

# Check module status
bin/magento module:status

# List all modules
bin/magento module:status --all
```

### Cache and Setup
```bash
# Clear cache
bin/magento cache:clean

# Flush cache
bin/magento cache:flush

# Reindex
bin/magento indexer:reindex

# Setup upgrade (after schema changes)
bin/magento setup:upgrade

# Compile DI
bin/magento setup:di:compile

# Deploy static content
bin/magento setup:static-content:deploy
```


## Common File Patterns

### Module Configuration
```xml
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="urn:magento:framework:Module/etc/module.xsd">
    <module name="EDU_SupportTickets" setup_version="1.0.0">
        <sequence>
            <module name="Magento_Customer"/>
            <module name="Magento_Backend"/>
            <module name="Magento_Ui"/>
        </sequence>
    </module>
</config>
```

### Model Class
```php
// Model/Ticket.php
<?php
namespace EDU\SupportTickets\Model;

use Magento\Framework\Model\AbstractModel;

class Ticket extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\EDU\SupportTickets\Model\ResourceModel\Ticket::class);
    }
}
```

### Resource Model
```php
// Model/ResourceModel/Ticket.php
<?php
namespace EDU\SupportTickets\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Ticket extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('support_ticket', 'ticket_id');
    }
}
```

### Collection
```php
// Model/ResourceModel/Ticket/Collection.php
<?php
namespace EDU\SupportTickets\Model\ResourceModel\Ticket;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(\EDU\SupportTickets\Model\Ticket::class, \EDU\SupportTickets\Model\ResourceModel\Ticket::class);
    }
}
```

### Admin Controller
```php
<?php

namespace EDU\SupportTickets\Controller\Adminhtml\Ticket;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    const ADMIN_RESOURCE = 'EDU_SupportTickets::tickets';
    
    protected $pageFactory;

    public function __construct(
        Context $context,
        PageFactory $pageFactory
    ) {
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Support Tickets'));
        return $resultPage;
    }
}
```

### Frontend Controller
```php
<?php

namespace EDU\SupportTickets\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session as CustomerSession;
use EDU\SupportTickets\Api\TicketRepositoryInterface;

class Index implements HttpGetActionInterface
{
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

    public function __construct(
        PageFactory $pageFactory,
        CustomerSession $customerSession,
        TicketRepositoryInterface $ticketRepository
    ) {
        $this->pageFactory = $pageFactory;
        $this->customerSession = $customerSession;
        $this->ticketRepository = $ticketRepository;
    }

    public function execute()
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('My Support Tickets'));

        // Get customer tickets
        if ($this->customerSession->isLoggedIn()) {
            $customerId = $this->customerSession->getCustomerId();
            $tickets = $this->ticketRepository->getByCustomerId($customerId);
            $resultPage->getLayout()->getBlock('ticket.list')->setData('tickets', $tickets);
        }

        return $resultPage;
    }
}
```

### Block Class
```php
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

```

### Layout XML
```xml
<?xml version="1.0"?>
<page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">
    <body>
        <referenceContainer name="content">
            <block class="EDU\SupportTickets\Block\Ticket\ListBlock"
                   name="ticket.list"
                   template="EDU_SupportTickets::ticket/list.phtml"/>
        </referenceContainer>
    </body>
</page>
```

### Template File
```php
<?php
/** @var \EDU\SupportTickets\Block\Ticket\FormBlock $block */
$categories = $block->getCategories();
$priorities = $block->getPriorities();
$customerTickets = $block->getCustomerTickets();
?>

<div class="support-ticket-form">
    <div class="page-title-wrapper">
        <h1 class="page-title"><?= $block->escapeHtml(__('Support Tickets')) ?></h1>
    </div>

    <?php if ($block->isCustomerLoggedIn()): ?>
    <div class="ticket-tabs">
        <div class="tab-headers">
            <button class="tab-header active" data-tab="create-ticket">
                <?= $block->escapeHtml(__('Create New Ticket')) ?>
            </button>
            <button class="tab-header" data-tab="my-tickets">
                <?= $block->escapeHtml(__('My Tickets')) ?>
                <span class="ticket-count">(<?= count($customerTickets) ?>)</span>
            </button>
        </div>
    </div>
    <?php endif; ?>

    <div class="tab-content">
        <div class="tab-panel active" id="create-ticket">
            <form action="<?= $block->escapeUrl($block->getFormAction()) ?>" method="post" class="ticket-form">
        <fieldset class="fieldset">
            <legend class="legend">
                <span><?= $block->escapeHtml(__('Ticket Information')) ?></span>
            </legend>

            <div class="field required">
                <label for="subject" class="label">
                    <span><?= $block->escapeHtml(__('Subject')) ?></span>
                </label>
                <div class="control">
                    <input type="text"
                           name="subject"
                           id="subject"
                           class="input-text required-entry"
                           required>
                </div>
            </div>

            <div class="field required">
                <label for="description" class="label">
                    <span><?= $block->escapeHtml(__('Description')) ?></span>
                </label>
                <div class="control">
                    <textarea name="description"
                              id="description"
                              class="input-text required-entry"
                              rows="8"
                              required></textarea>
                </div>
            </div>

            <div class="field required">
                <label for="category_id" class="label">
                    <span><?= $block->escapeHtml(__('Category')) ?></span>
                </label>
                <div class="control">
                    <select name="category_id"
                            id="category_id"
                            class="select required-entry"
                            required>
                        <option value=""><?= $block->escapeHtml(__('Please select a category')) ?></option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $block->escapeHtml($category['value']) ?>">
                                <?= $block->escapeHtml($category['label']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="field required">
                <label for="priority_id" class="label">
                    <span><?= $block->escapeHtml(__('Priority')) ?></span>
                </label>
                <div class="control">
                    <select name="priority_id"
                            id="priority_id"
                            class="select required-entry"
                            required>
                        <option value=""><?= $block->escapeHtml(__('Please select a priority')) ?></option>
                        <?php foreach ($priorities as $priority): ?>
                            <option value="<?= $block->escapeHtml($priority['value']) ?>">
                                <?= $block->escapeHtml($priority['label']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <?php if (!$block->isCustomerLoggedIn()): ?>
                <div class="field required">
                    <label for="customer_name" class="label">
                        <span><?= $block->escapeHtml(__('Your Name')) ?></span>
                    </label>
                    <div class="control">
                        <input type="text"
                               name="customer_name"
                               id="customer_name"
                               class="input-text required-entry"
                               value="<?= $block->escapeHtml($block->getCustomerName()) ?>"
                               required>
                    </div>
                </div>

                <div class="field required">
                    <label for="customer_email" class="label">
                        <span><?= $block->escapeHtml(__('Your Email')) ?></span>
                    </label>
                    <div class="control">
                        <input type="email"
                               name="customer_email"
                               id="customer_email"
                               class="input-text required-entry validate-email"
                               value="<?= $block->escapeHtml($block->getCustomerEmail()) ?>"
                               required>
                    </div>
                </div>
            <?php endif; ?>
        </fieldset>

        <div class="actions-toolbar">
            <div class="primary">
                <button type="submit" class="action submit primary">
                    <span><?= $block->escapeHtml(__('Create Ticket')) ?></span>
                </button>
            </div>
            <div class="secondary">
                <a href="<?= $block->escapeUrl($block->getUrl('supporttickets/index/index')) ?>"
                   class="action back">
                    <?= $block->escapeHtml(__('Back to Tickets')) ?>
                </a>
            </div>
        </div>
            </form>
        </div>

        <?php if ($block->isCustomerLoggedIn()): ?>
        <div class="tab-panel" id="my-tickets">
            <div class="my-tickets-content">
                <h2><?= $block->escapeHtml(__('My Support Tickets')) ?></h2>
                
                <?php if (empty($customerTickets)): ?>
                    <div class="no-tickets">
                        <p><?= $block->escapeHtml(__('You haven\'t submitted any support tickets yet.')) ?></p>
                        <p><?= $block->escapeHtml(__('Click on "Create New Ticket" to submit your first ticket.')) ?></p>
                    </div>
                <?php else: ?>
                    <div class="tickets-list">
                        <?php foreach ($customerTickets as $ticket): ?>
                            <div class="ticket-item">
                                <div class="ticket-header">
                                    <div class="ticket-title">
                                        <h3>
                                            <a href="<?= $block->escapeUrl($block->getUrl('supporttickets/index/view', ['id' => $ticket->getTicketId()])) ?>">
                                                <?= $block->escapeHtml($ticket->getSubject()) ?>
                                            </a>
                                        </h3>
                                        <span class="ticket-number">#<?= $block->escapeHtml($ticket->getTicketNumber()) ?></span>
                                    </div>
                                    <div class="ticket-status">
                                        <span class="status-badge <?= $block->escapeHtml($block->getStatusClass($ticket->getStatus())) ?>">
                                            <?= $block->escapeHtml($block->getStatusLabel($ticket->getStatus())) ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="ticket-meta">
                                    <div class="ticket-date">
                                        <strong><?= $block->escapeHtml(__('Created:')) ?></strong>
                                        <span><?= $block->escapeHtml($block->formatDate($ticket->getCreatedAt())) ?></span>
                                    </div>
                                    <?php if ($ticket->getLastReplyAt()): ?>
                                    <div class="ticket-last-reply">
                                        <strong><?= $block->escapeHtml(__('Last Reply:')) ?></strong>
                                        <span><?= $block->escapeHtml($block->formatDate($ticket->getLastReplyAt())) ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="ticket-description">
                                    <p><?= $block->escapeHtml(substr($ticket->getDescription(), 0, 150)) ?><?= strlen($ticket->getDescription()) > 150 ? '...' : '' ?></p>
                                </div>
                                <div class="ticket-actions">
                                    <a href="<?= $block->escapeUrl($block->getUrl('supporttickets/index/view', ['id' => $ticket->getTicketId()])) ?>" 
                                       class="action view">
                                        <?= $block->escapeHtml(__('View Details')) ?>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.support-ticket-form {
    max-width: 800px;
    margin: 0 auto;
}

/* Tab Styles */
.ticket-tabs {
    margin-bottom: 30px;
}

.tab-headers {
    display: flex;
    border-bottom: 2px solid #e0e0e0;
    margin-bottom: 20px;
}

.tab-header {
    background: none;
    border: none;
    padding: 15px 25px;
    font-size: 16px;
    font-weight: 500;
    color: #666;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    transition: all 0.3s ease;
    position: relative;
}

.tab-header:hover {
    color: #007bff;
    background-color: #f8f9fa;
}

.tab-header.active {
    color: #007bff;
    border-bottom-color: #007bff;
    background-color: #fff;
}

.tab-header .ticket-count {
    background: #007bff;
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 12px;
    margin-left: 8px;
}

.tab-content {
    position: relative;
}

.tab-panel {
    display: none;
}

.tab-panel.active {
    display: block;
}

/* My Tickets Styles */
.my-tickets-content h2 {
    margin-bottom: 20px;
    color: #333;
    font-size: 24px;
}

.no-tickets {
    text-align: center;
    padding: 40px 20px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 2px dashed #ddd;
}

.no-tickets p {
    margin: 10px 0;
    color: #666;
    font-size: 16px;
}

.tickets-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.ticket-item {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: box-shadow 0.3s ease;
}

.ticket-item:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.ticket-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

.ticket-title h3 {
    margin: 0 0 5px 0;
    font-size: 18px;
}

.ticket-title h3 a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
}

.ticket-title h3 a:hover {
    color: #007bff;
}

.ticket-number {
    color: #666;
    font-size: 14px;
    font-weight: normal;
}

.ticket-status {
    flex-shrink: 0;
}

.status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
}

.status-open {
    background: #d4edda;
    color: #155724;
}

.status-in-progress {
    background: #fff3cd;
    color: #856404;
}

.status-waiting {
    background: #cce5ff;
    color: #004085;
}

.status-resolved {
    background: #d1ecf1;
    color: #0c5460;
}

.status-closed {
    background: #f8d7da;
    color: #721c24;
}

.ticket-meta {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
    font-size: 14px;
    color: #666;
}

.ticket-meta > div {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.ticket-meta strong {
    color: #333;
}

.ticket-description {
    margin-bottom: 15px;
    color: #666;
    line-height: 1.5;
}

.ticket-actions {
    text-align: right;
}

.ticket-actions .action.view {
    background: #007bff;
    color: white;
    padding: 8px 16px;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

.ticket-actions .action.view:hover {
    background: #0056b3;
    color: white;
}

@media (max-width: 768px) {
    .tab-headers {
        flex-direction: column;
    }
    
    .tab-header {
        text-align: center;
        border-bottom: 1px solid #e0e0e0;
        border-right: none;
    }
    
    .ticket-header {
        flex-direction: column;
        gap: 10px;
    }
    
    .ticket-meta {
        flex-direction: column;
        gap: 10px;
    }
    
    .ticket-actions {
        text-align: center;
    }
}

.support-ticket-form .ticket-form {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 30px;
    margin: 20px 0;
}

.support-ticket-form .fieldset {
    border: none;
    margin: 0 0 30px 0;
    padding: 0;
}

.support-ticket-form .legend {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 20px;
    color: #333;
}

.support-ticket-form .field {
    margin-bottom: 20px;
}

.support-ticket-form .field.required .label::after {
    content: ' *';
    color: #e02b27;
}

.support-ticket-form .label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #333;
}

.support-ticket-form .control input,
.support-ticket-form .control textarea,
.support-ticket-form .control select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    box-sizing: border-box;
}

.support-ticket-form .control select {
    height: auto;
    min-height: 44px;
    padding: 10px 12px;
    background-color: #fff;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23666' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 12px;
    padding-right: 40px;
}

.support-ticket-form .control select:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

.support-ticket-form .control select option {
    padding: 8px 12px;
    background-color: #fff;
    color: #333;
    white-space: normal;
    word-wrap: break-word;
    min-height: 20px;
    line-height: 1.4;
}

.support-ticket-form .control textarea {
    resize: vertical;
    min-height: 120px;
}

.support-ticket-form .actions-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.support-ticket-form .action.submit.primary {
    background-color: #007bff;
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
}

.support-ticket-form .action.submit.primary:hover {
    background-color: #0056b3;
}

.support-ticket-form .action.back {
    color: #007bff;
    text-decoration: none;
    padding: 10px 20px;
}

.support-ticket-form .action.back:hover {
    text-decoration: underline;
}

/* Additional select styling for better appearance */
.support-ticket-form .control select::-ms-expand {
    display: none;
}

.support-ticket-form .control select:focus::-ms-value {
    background: transparent;
    color: #333;
}

.support-ticket-form .control select option:checked {
    background-color: #007bff;
    color: white;
}

.support-ticket-form .control select option:hover {
    background-color: #f8f9fa;
}

/* Ensure proper spacing for select elements */
.support-ticket-form .field select {
    margin-bottom: 0;
}

/* Better visual hierarchy */
.support-ticket-form .field.required .label {
    position: relative;
}

.support-ticket-form .field.required .label::after {
    content: ' *';
    color: #e02b27;
    font-weight: bold;
    position: absolute;
    right: -15px;
    top: 0;
}

@media (max-width: 768px) {
    .support-ticket-form .actions-toolbar {
        flex-direction: column;
        gap: 15px;
    }

    .support-ticket-form .actions-toolbar .primary,
    .support-ticket-form .actions-toolbar .secondary {
        width: 100%;
        text-align: center;
    }

    .support-ticket-form .control select {
        font-size: 16px; /* Prevent zoom on iOS */
    }
}
</style>

<script>
require(['jquery'], function($) {
    $(document).ready(function() {
        // Tab functionality
        $('.tab-header').on('click', function() {
            var targetTab = $(this).data('tab');
            
            // Remove active class from all tabs and panels
            $('.tab-header').removeClass('active');
            $('.tab-panel').removeClass('active');
            
            // Add active class to clicked tab and corresponding panel
            $(this).addClass('active');
            $('#' + targetTab).addClass('active');
        });
    });
});
</script>
```

### 12. UI Component Grid, The Ui Component XML for ticket listing, for priority & category follow the same pattern
```xml
<?xml version="1.0" encoding="UTF-8"?>
<listing xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="urn:magento:module:Magento_Ui:etc/ui_configuration.xsd">
    <argument name="data" xsi:type="array">
        <item name="js_config" xsi:type="array">
            <item name="provider" xsi:type="string">support_ticket_listing.support_ticket_listing_data_source</item>
            <item name="deps" xsi:type="string">support_ticket_listing.support_ticket_listing_data_source</item>
        </item>
        <item name="spinner" xsi:type="string">support_ticket_columns</item>
        <item name="buttons" xsi:type="array">
            <item name="add" xsi:type="array">
                <item name="name" xsi:type="string">add</item>
                <item name="label" xsi:type="string" translate="true">Add New Ticket</item>
                <item name="class" xsi:type="string">primary</item>
                <item name="url" xsi:type="string">supporttickets/ticket/newaction</item>
            </item>
        </item>
    </argument>

    <dataSource name="support_ticket_listing_data_source">
        <argument name="dataProvider" xsi:type="configurableObject">
            <argument name="class" xsi:type="string">EDU\SupportTickets\Ui\Component\TicketDataProvider</argument>
            <argument name="name" xsi:type="string">support_ticket_listing_data_source</argument>
            <argument name="primaryFieldName" xsi:type="string">ticket_id</argument>
            <argument name="requestFieldName" xsi:type="string">ticket_id</argument>
            <argument name="data" xsi:type="array">
                <item name="config" xsi:type="array">
                    <item name="update_url" xsi:type="url" path="mui/index/render"/>
                    <item name="storageConfig" xsi:type="array">
                        <item name="indexField" xsi:type="string">ticket_id</item>
                    </item>
                </item>
            </argument>
        </argument>
        <argument name="data" xsi:type="array">
            <item name="js_config" xsi:type="array">
                <item name="component" xsi:type="string">Magento_Ui/js/grid/provider</item>
            </item>
        </argument>
    </dataSource>

    <listingToolbar name="listing_top">
        <bookmark name="bookmarks"/>
        <columnsControls name="columns_controls"/>
        <massaction name="listing_massaction">
            <argument name="data" xsi:type="array">
                <item name="data" xsi:type="array">
                    <item name="selectProvider" xsi:type="string">
                        support_ticket_listing.support_ticket_listing.support_ticket_columns.ids
                    </item>
                    <item name="displayArea" xsi:type="string">bottom</item>
                    <item name="component" xsi:type="string">Magento_Ui/js/grid/tree-massactions</item>
                    <item name="indexField" xsi:type="string">ticket_id</item>
                </item>
            </argument>
            <action name="delete">
                <argument name="data" xsi:type="array">
                    <item name="config" xsi:type="array">
                        <item name="type" xsi:type="string">delete</item>
                        <item name="label" xsi:type="string" translate="true">Delete</item>
                        <item name="url" xsi:type="url" path="supporttickets/ticket/massDelete"/>
                        <item name="confirm" xsi:type="array">
                            <item name="title" xsi:type="string" translate="true">Delete tickets</item>
                            <item name="message" xsi:type="string" translate="true">Are you sure you want to delete
                                selected tickets?
                            </item>
                        </item>
                    </item>
                </argument>
            </action>
        </massaction>
        <filters name="listing_filters">
            <argument name="data" xsi:type="array">
                <item name="config" xsi:type="array">
                    <item name="templates" xsi:type="array">
                        <item name="filters" xsi:type="array">
                            <item name="select" xsi:type="array">
                                <item name="component" xsi:type="string">Magento_Ui/js/form/element/ui-select</item>
                                <item name="template" xsi:type="string">ui/grid/filters/elements/ui-select</item>
                            </item>
                        </item>
                    </item>
                </item>
            </argument>
        </filters>
        <paging name="listing_paging"/>
    </listingToolbar>

    <columns name="support_ticket_columns">
        <selectionsColumn name="ids">
            <argument name="data" xsi:type="array">
                <item name="config" xsi:type="array">
                    <item name="indexField" xsi:type="string">ticket_id</item>
                </item>
            </argument>
        </selectionsColumn>

        <column name="ticket_id">
            <settings>
                <filter>textRange</filter>
                <label translate="true">ID</label>
                <resizeDefaultWidth>50</resizeDefaultWidth>
            </settings>
        </column>

        <column name="ticket_number">
            <settings>
                <filter>text</filter>
                <bodyTmpl>ui/grid/cells/text</bodyTmpl>
                <label translate="true">Ticket Number</label>
            </settings>
        </column>

        <column name="subject">
            <settings>
                <filter>text</filter>
                <bodyTmpl>ui/grid/cells/text</bodyTmpl>
                <label translate="true">Subject</label>
            </settings>
        </column>

        <column name="customer_name">
            <settings>
                <filter>text</filter>
                <bodyTmpl>ui/grid/cells/text</bodyTmpl>
                <label translate="true">Customer</label>
            </settings>
        </column>

        <column name="status" component="Magento_Ui/js/grid/columns/select">
            <settings>
                <options class="EDU\SupportTickets\Ui\Component\Listing\Column\Status\Options"/>
                <filter>select</filter>
                <editor>
                    <editorType>select</editorType>
                </editor>
                <dataType>select</dataType>
                <label translate="true">Status</label>
            </settings>
        </column>
        <column name="created_at" class="Magento\Ui\Component\Listing\Columns\Date"
                component="Magento_Ui/js/grid/columns/date">
            <settings>
                <filter>dateRange</filter>
                <dataType>date</dataType>
                <label translate="true">Created At</label>
            </settings>
        </column>

        <actionsColumn name="actions" class="\EDU\SupportTickets\Ui\Component\Listing\Column\Actions" sortOrder="200">
            <argument name="data" xsi:type="array">
                <item name="config" xsi:type="array">
                    <item name="resizeEnabled" xsi:type="boolean">false</item>
                    <item name="resizeDefaultWidth" xsi:type="string">150</item>
                    <item name="indexField" xsi:type="string">ticket_id</item>
                </item>
            </argument>
        </actionsColumn>
    </columns>
</listing>

```

## Common Patterns

### 1. Dependency Injection
```php
public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Magento\Customer\Model\Session $customerSession,
    \EDU\SupportTickets\Model\ResourceModel\Ticket\CollectionFactory $ticketCollectionFactory,
    array $data = []
) {
    $this->customerSession = $customerSession;
    $this->ticketCollectionFactory = $ticketCollectionFactory;
    parent::__construct($context, $data);
}
```

### 2. Data Collection
```php
public function getTickets()
{
    $collection = $this->ticketCollectionFactory->create();
    $collection->addFieldToFilter('customer_id', $this->customerSession->getCustomerId());
    $collection->setOrder('created_at', 'DESC');
    return $collection;
}
```

### 3. Form Handling
```php
public function execute()
{
    $resultRedirect = $this->resultRedirectFactory->create();
    
    if ($this->getRequest()->isPost()) {
        $data = $this->getRequest()->getPostValue();
        
        try {
            // Process form data
            $this->messageManager->addSuccessMessage(__('Ticket created successfully.'));
            return $resultRedirect->setPath('supporttickets/index/index');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
    }
    
    return $resultRedirect->setPath('supporttickets/index/index');
}
```

### 4. URL Generation
```php
// In Block
$this->getUrl('supporttickets/index/save');

// With parameters
$this->getUrl('supporttickets/index/view', ['id' => $ticketId]);

// Admin URL
$this->getUrl('admin/supporttickets/ticket/edit', ['id' => $ticketId]);
```

### 5. Data Validation
```php
public function validate($data)
{
    $errors = [];
    
    if (empty($data['subject'])) {
        $errors[] = __('Subject is required.');
    }
    
    if (empty($data['description'])) {
        $errors[] = __('Description is required.');
    }
    
    return $errors;
}
```

### 6. Customer Session
```php
// Check if customer is logged in
if ($this->customerSession->isLoggedIn()) {
    $customerId = $this->customerSession->getCustomerId();
    $customer = $this->customerSession->getCustomer();
}
```

### 7. Admin Authorization
```php
// In admin controller
protected function _isAllowed()
{
    return $this->_authorization->isAllowed('EDU_SupportTickets::tickets');
}
```

### 8. Data Escaping
```php
// In templates
<?= $block->escapeHtml($data) ?>
<?= $block->escapeUrl($url) ?>
<?= $block->escapeJs($javascript) ?>
```

### 9. Translation
```php
// In PHP
__('Text to translate')

// In templates
<?= $block->escapeHtml(__('Text to translate')) ?>
```

## Debugging Tips

### 1. Check Logs
```bash
tail -f var/log/debug.log
tail -f var/log/system.log
tail -f var/log/exception.log
```

### 2. Clear Cache
```bash
bin/magento cache:clean
bin/magento cache:flush
```

### 3. Check Module Status
```bash
bin/magento module:status EDU_SupportTickets
```

### 4. Verify Database
```sql
-- Check if tables exist
SHOW TABLES LIKE 'support_%';

-- Check table structure
DESCRIBE support_ticket;
```

## Common Issues and Solutions

### 1. Module Not Loading
- Check `registration.php` syntax
- Verify `module.xml` structure
- Run `bin/magento setup:upgrade`

### 2. Template Not Found
- Check layout XML references
- Verify template file path
- Clear cache

### 3. Database Issues
- Check `db_schema.xml` syntax
- Run `bin/magento setup:upgrade`
- Check database permissions

### 4. Admin Menu Not Showing
- Check `menu.xml` structure
- Verify ACL permissions
- Check controller authorization

### 5. Frontend Not Working
- Check routes configuration
- Verify controller exists
- Check layout XML

## Useful Resources

- [Add an Admin grid](https://developer.adobe.com/commerce/php/development/components/add-admin-grid/)


