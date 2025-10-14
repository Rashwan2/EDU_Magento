# Magento 2 Support Ticket System - Student Project Guide

## Project Overview
You will build a complete Support Ticket system for Magento 2 that allows customers to submit support tickets and administrators to manage them. This is a comprehensive project that will test your understanding of Magento 2 module development, database design, admin panels, frontend development, and more.

## Learning Objectives
By completing this project, you will learn:
- Magento 2 module structure and development
- Database schema design and data patches
- Model-ResourceModel-Collection pattern
- Admin panel development with UI Components
- Frontend development and customer integration
- API layer and service contracts
- Layout XML and template development

## Project Requirements

### Core Features
1. **Customer Features:**
   - Submit support tickets with subject, description, category, and priority
   - View their submitted tickets
   - Track ticket status and responses
   - Access ticket submission from multiple locations (header, floating button, account dashboard)

2. **Admin Features:**
   - View all tickets in a grid with filtering and sorting
   - Create, edit, view, and delete tickets
   - Assign tickets to staff members
   - Add messages to tickets
   - Manage ticket categories and priorities
   - Bulk operations (mass delete, mass status update)

3. **System Features:**
   - Ticket status workflow (Open → In Progress → Waiting → Resolved → Closed)
   - Internal notes (admin-only messages)
   - Ticket numbering system
   - Customer session integration

## Project Structure

### Module Information
- **Module Name:** EDU_SupportTickets
- **Namespace:** EDU\SupportTickets
- **Version:** 1.0.0
- **Dependencies:** Magento_Customer, Magento_Backend, Magento_Ui

### File Structure to Create
```
app/code/EDU/SupportTickets/
├── registration.php
├── composer.json
├── etc/
│   ├── module.xml
│   ├── db_schema.xml
│   ├── adminhtml/
│   │   ├── menu.xml
│   │   ├── routes.xml
│   │   └── acl.xml
│   └── frontend/
│       └── routes.xml
├── Setup/
│   └── Patch/
│       └── Data/
│           └── InstallDefaultData.php
├── Model/
│   ├── Ticket.php
│   ├── Message.php
│   ├── Category.php
│   ├── Priority.php
│   └── ResourceModel/
│       ├── Ticket.php
│       ├── Message.php
│       ├── Category.php
│       ├── Priority.php
│       └── Ticket/
│       └── Category/
│       └── Priority/
│           └── Collection.php
├── Api/
│   ├── Data/
│   │   ├── TicketInterface.php
│   │   ├── MessageInterface.php
│   │   ├── CategoryInterface.php
│   │   └── PriorityInterface.php
│   └── TicketRepositoryInterface.php
├── Controller/
│   ├── Adminhtml/
│   │   ├── Ticket/
│   │   │   ├── Index.php
│   │   │   ├── Edit.php
│   │   │   ├── Save.php
│   │   │   ├── Delete.php
│   │   │   ├── View.php
│   │   │   └── NewAction.php
│   │   ├── Category/
│   │   │   ├── Index.php
│   │   │   ├── Edit.php
│   │   │   ├── Save.php
│   │   │   ├── Delete.php
│   │   │   └── MassDelete.php
│   │   ├── Priority/
│   │   │   ├── Index.php
│   │   │   ├── Edit.php
│   │   │   ├── Save.php
│   │   │   ├── Delete.php
│   │   │   └── MassDelete.php
│   │   └── Message/
│   │       └── Add.php
│   └── Index/
│       ├── Index.php
│       ├── Save.php
│       └── View.php
├── Block/
│   ├── Ticket/
│   │   └── FormBlock.php
│   └── Adminhtml/
│       ├── Ticket/
│       │   ├── Edit/
│       │   │   └── Form.php
│       │   └── View.php
│       ├── Category/
│       │   └── Edit/
│       │       └── Form.php
│       └── Priority/
│           └── Edit/
│               └── Form.php
├── Ui/
│   └── Component/
│       ├── TicketDataProvider.php
│       ├── CategoryDataProvider.php
│       ├── PriorityDataProvider.php
│       └── Listing/
│           └── Column/
│               ├── TicketActions.php
│               ├── CategoryActions.php
│               └── PriorityActions.php
├── view/
│   ├── adminhtml/
│   │   ├── layout/
│   │   │   ├── supporttickets_ticket_index.xml
│   │   │   ├── supporttickets_ticket_edit.xml
│   │   │   ├── supporttickets_ticket_view.xml
│   │   │   ├── supporttickets_ticket_newaction.xml
│   │   │   ├── supporttickets_category_index.xml
│   │   │   ├── supporttickets_category_edit.xml
│   │   │   ├── supporttickets_priority_index.xml
│   │   │   └── supporttickets_priority_edit.xml
│   │   ├── ui_component/
│   │   │   ├── support_ticket_listing.xml
│   │   │   ├── support_category_listing.xml
│   │   │   └── support_priority_listing.xml
│   │   └── templates/
│   │       ├── ticket/
│   │       │   ├── edit.phtml
│   │       │   ├── view.phtml
│   │       │   └── new.phtml
│   │       ├── category/
│   │       │   └── edit.phtml
│   │       └── priority/
│   │           └── edit.phtml
│   └── frontend/
│       ├── layout/
│       │   ├── default.xml
│       │   └── header_panel.xml
│       ├── templates/
│       │   ├── ticket/
│       │   │   ├── form.phtml
│       │   │   └── view.phtml
│       │   ├── support_ticket_button.phtml
│       │   └── header_support_link.phtml
│       └── web/
│           └── css/
│               └── source/
│                   └── _module.less
```

## Step-by-Step Implementation Guide

### Phase 1: Module Foundation 

#### Step 1.1: Create Module Structure
1. Create the main module directory `app/code/EDU/SupportTickets/`
2. Create `registration.php` with proper module registration
3. Create `composer.json` with module metadata and dependencies
4. Create `etc/module.xml` with module configuration
5. Enable the module using `bin/magento module:enable EDU_SupportTickets`

#### Step 1.2: Database Schema Design
1. Create `etc/db_schema.xml` with the following tables:
   - `support_ticket` (main ticket table)
   - `ticket_message` (ticket messages/conversations)
   - `ticket_category` (ticket categories)
   - `ticket_priority` (ticket priorities)
2. Define proper columns, indexes, and foreign key relationships
3. Consider data types, constraints, and performance

#### Step 1.3: Data Patches
1. Create `Setup/Patch/Data/InstallDefaultData.php`
2. Insert default categories (Technical Support, Billing, General Inquiry, etc.)
3. Insert default priorities (Low, Medium, High, Critical)
4. Run data patch using `bin/magento setup:upgrade`

### Phase 2: Models and Data Layer 

#### Step 2.1: Create Model Classes
1. Create `Model/Ticket.php` with getters/setters and business logic
2. Create `Model/Message.php` for ticket messages
3. Create `Model/Category.php` for ticket categories
4. Create `Model/Priority.php` for ticket priorities
5. Implement proper data validation and business rules

#### Step 2.2: Create Resource Models
1. Create resource model classes for each entity
2. Implement `_construct()` method with table and primary key definitions
3. Add any custom database operations if needed

#### Step 2.3: Create Collections
1. Create collection classes for each entity
2. Implement filtering, sorting, and pagination methods
3. Add custom collection methods for specific queries

#### Step 2.4: Create API Interfaces
1. Create data interfaces for each entity
2. Define getter/setter methods
3. Create repository interfaces
4. Implement repository classes

### Phase 3: Admin Panel Development 

#### Step 3.1: Admin Routes and Menu
1. Create `etc/adminhtml/routes.xml` for admin routes
2. Create `etc/adminhtml/menu.xml` for admin menu structure
3. Create `etc/adminhtml/acl.xml` for permissions
4. Test admin menu appears correctly

#### Step 3.2: Admin Controllers
1. Create admin controllers for each entity (Index, Edit, Save, Delete)
2. Implement proper form handling and validation
3. Add redirect logic and success/error messages
4. Implement mass actions for bulk operations

#### Step 3.3: UI Components
1. Create UI component XML files for admin grids
2. Define columns, filters, and actions
3. Create data providers for grid data
4. Implement action columns for edit/delete operations

#### Step 3.4: Admin Forms and Templates
1. Create form blocks for edit pages
2. Create admin templates for forms and views
3. Implement proper form validation
4. Add JavaScript for enhanced functionality

### Phase 4: Frontend Development 

#### Step 4.1: Frontend Routes and Controllers
1. Create `etc/frontend/routes.xml` for frontend routes
2. Create frontend controllers for ticket submission and viewing
3. Implement customer session integration
4. Add proper error handling and redirects

#### Step 4.2: Frontend Blocks and Templates
1. Create form block for ticket submission
2. Create templates for ticket forms and views
3. Implement customer-specific data loading
4. Add responsive design and styling

#### Step 4.3: Layout Integration
1. Add "Submit Ticket" link to header
2. Create floating support button
3. Add customer account dashboard integration
4. Implement proper layout XML files

#### Step 4.4: Customer Experience
1. Create "My Tickets" functionality
2. Implement ticket status tracking
3. Add message viewing and replies
4. Create responsive and user-friendly interface

### Phase 5: Advanced Features


#### Step 5.1: Ticket Workflow
1. Implement status change logic
2. Add validation for status transitions
3. Create status update functionality
4. Add workflow notifications

#### Step 5.2: Admin Message System
1. Create admin message functionality
2. Implement internal notes system
3. Add message threading
4. Create message management interface

#### Step 5.3: Testing and Optimization
1. Test all functionality thoroughly
2. Optimize database queries
3. Add proper error handling
4. Implement security measures

## Database Schema Requirements

### support_ticket Table
- ticket_id (primary key)
- ticket_number (unique identifier)
- customer_id (foreign key to customer_entity)
- subject (varchar)
- description (text)
- status (enum: open, in_progress, waiting_customer, resolved, closed)
- priority_id (foreign key)
- category_id (foreign key)
- assigned_to (admin user ID)
- created_at (timestamp)
- updated_at (timestamp)
- last_reply_at (timestamp)

### ticket_message Table
- message_id (primary key)
- ticket_id (foreign key)
- sender_type (enum: customer, admin)
- sender_id (customer or admin ID)
- message (text)
- is_internal (boolean)
- created_at (timestamp)

### ticket_category Table
- category_id (primary key)
- name (varchar)
- description (text)
- is_active (boolean)
- sort_order (int)
- created_at (timestamp)
- updated_at (timestamp)

### ticket_priority Table
- priority_id (primary key)
- name (varchar)
- description (text)
- color (varchar)
- is_active (boolean)
- sort_order (int)
- created_at (timestamp)
- updated_at (timestamp)

## Technical Requirements

### Code Quality Standards
1. Follow PSR-12 coding standards
2. Use proper PHPDoc comments
3. Implement proper error handling
4. Use dependency injection
5. Follow Magento 2 best practices

### Security Considerations
1. Validate all user inputs
2. Use proper CSRF protection
3. Implement proper ACL permissions
4. Sanitize output data
5. Use prepared statements for database queries

### Performance Requirements
1. Optimize database queries
2. Use proper indexing
3. Minimize database calls
4. Use efficient data loading

### Testing Requirements
1. Test all CRUD operations
2. Test admin functionality
3. Test frontend functionality
4. Test responsive design

## Deliverables

### Code Deliverables
1. Complete module with all files
2. Database schema and data patches
3. Admin panel with full functionality
4. Frontend interface with customer features

### Documentation Deliverables
1. Module installation guide
2. User manual for admin features
3. User manual for customer features
4. Technical documentation
5. Database schema documentation

### Testing Deliverables
1. Test cases for all functionality
2. Bug reports and fixes
3. Performance testing results
4. Security testing results
5. User acceptance testing

## Evaluation Criteria

### Functionality (40%)
- All required features implemented
- Proper error handling
- User-friendly interface
- Responsive design

### Code Quality (30%)
- Follows coding standards
- Proper documentation
- Clean and maintainable code
- Proper use of Magento 2 patterns

### Database Design (15%)
- Efficient schema design
- Proper relationships
- Good indexing strategy
- Data integrity

### Testing (15%)
- Comprehensive testing
- Bug-free implementation
- Performance optimization
- Security considerations



## Notes 

1. Start with the basic structure and build up
2. Test each phase thoroughly before moving to the next
3. Use Magento 2 documentation extensively
4. Ask questions and seek help when stuck
5. Focus on code quality and best practices

