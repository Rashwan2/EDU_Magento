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

### Development Mode
```bash
# Enable developer mode
bin/magento deploy:mode:set developer

# Enable production mode
bin/magento deploy:mode:set production

# Check current mode
bin/magento deploy:mode:show
```

## Common File Patterns

### 1. Module Registration
```php
// registration.php
<?php
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'EDU_SupportTickets',
    __DIR__
);
```

### 2. Module Configuration
```xml
<!-- etc/module.xml -->
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Module/etc/module.xsd">
    <module name="EDU_SupportTickets" setup_version="1.0.0">
        <sequence>
            <module name="Magento_Customer"/>
            <module name="Magento_Backend"/>
        </sequence>
    </module>
</config>
```

### 3. Database Schema
```xml
<!-- etc/db_schema.xml -->
<?xml version="1.0"?>
<schema xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Setup/Declaration/Schema/etc/schema.xsd">
    <table name="support_ticket" resource="default" engine="innodb" comment="Support Tickets">
        <column xsi:type="int" name="ticket_id" unsigned="true" nullable="false" identity="true" comment="Ticket ID"/>
        <column xsi:type="varchar" name="ticket_number" length="50" nullable="false" comment="Ticket Number"/>
        <constraint xsi:type="primary" referenceId="PRIMARY">
            <column name="ticket_id"/>
        </constraint>
    </table>
</schema>
```

### 4. Model Class
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

### 5. Resource Model
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

### 6. Collection
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

### 7. Admin Controller
```php
// Controller/Adminhtml/Ticket/Index.php
<?php
namespace EDU\SupportTickets\Controller\Adminhtml\Ticket;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $resultPageFactory;

    public function __construct(Context $context, PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Support Tickets'));
        return $resultPage;
    }
}
```

### 8. Frontend Controller
```php
// Controller/Index/Index.php
<?php
namespace EDU\SupportTickets\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $resultPageFactory;

    public function __construct(Context $context, PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }
}
```

### 9. Block Class
```php
// Block/Ticket/FormBlock.php
<?php
namespace EDU\SupportTickets\Block\Ticket;

use Magento\Framework\View\Element\Template;

class FormBlock extends Template
{
    public function getFormAction()
    {
        return $this->getUrl('supporttickets/index/save');
    }
}
```

### 10. Layout XML
```xml
<!-- view/frontend/layout/supporttickets_index_index.xml -->
<?xml version="1.0"?>
<page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">
    <body>
        <referenceContainer name="content">
            <block class="EDU\SupportTickets\Block\Ticket\FormBlock" name="support.ticket.form" template="EDU_SupportTickets::ticket/form.phtml"/>
        </referenceContainer>
    </body>
</page>
```

### 11. Template File
```php
<!-- view/frontend/templates/ticket/form.phtml -->
<?php
/** @var \EDU\SupportTickets\Block\Ticket\FormBlock $block */
?>
<div class="support-ticket-form">
    <h1><?= $block->escapeHtml(__('Create Support Ticket')) ?></h1>
    <form action="<?= $block->escapeUrl($block->getFormAction()) ?>" method="post">
        <!-- Form content -->
    </form>
</div>
```

### 12. UI Component Grid
```xml
<!-- view/adminhtml/ui_component/support_ticket_listing.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<listing xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:module:Magento_Ui:etc/ui_configuration.xsd">
    <argument name="data" xsi:type="array">
        <item name="js_config" xsi:type="array">
            <item name="provider" xsi:type="string">support_ticket_listing.support_ticket_listing_data_source</item>
        </item>
    </argument>
    <dataSource name="support_ticket_listing_data_source">
        <argument name="dataProvider" xsi:type="configurableObject">
            <argument name="class" xsi:type="string">EDU\SupportTickets\Ui\Component\TicketDataProvider</argument>
        </argument>
    </dataSource>
    <columns name="support_ticket_columns">
        <column name="ticket_id">
            <argument name="data" xsi:type="array">
                <item name="config" xsi:type="array">
                    <item name="filter" xsi:type="string">text</item>
                    <item name="label" xsi:type="string" translate="true">ID</item>
                </item>
            </argument>
        </column>
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

### 10. JavaScript in Templates
```javascript
require(['jquery'], function($) {
    $(document).ready(function() {
        // Your JavaScript code here
    });
});
```

## Debugging Tips

### 1. Enable Developer Mode
```bash
bin/magento deploy:mode:set developer
```

### 2. Check Logs
```bash
tail -f var/log/debug.log
tail -f var/log/system.log
tail -f var/log/exception.log
```

### 3. Clear Cache
```bash
bin/magento cache:clean
bin/magento cache:flush
```

### 4. Check Module Status
```bash
bin/magento module:status EDU_SupportTickets
```

### 5. Verify Database
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

- [Magento 2 Developer Documentation](https://devdocs.magento.com/)
- [Magento 2 GitHub Repository](https://github.com/magento/magento2)
- [Magento 2 Community Forums](https://community.magento.com/)
- [Stack Overflow - Magento 2](https://stackoverflow.com/questions/tagged/magento2)

## File Permissions

```bash
# Set proper permissions
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod +x bin/magento
```

This quick reference guide should help you navigate the most common patterns and commands needed for Magento 2 development!
