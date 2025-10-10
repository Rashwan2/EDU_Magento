# EDU Magento 2 Learning Modules

A comprehensive collection of three educational Magento 2 modules designed to demonstrate different aspects of Magento 2
development. Each module focuses on specific Magento 2 concepts and provides practical examples for learning purposes.

## 📋 Table of Contents

- [Overview](#overview)
- [Module Structure](#module-structure)
- [EDU_HelloWorld - Preferences, Plugins & Events](#edu_helloworld---preferences-plugins--events)
- [EDU_InventoryReport - Controllers & Layout](#edu_inventoryreport---controllers--layout)
- [EDU_QuestionHub - Data Layer](#edu_questionhub---data-layer)
- [Installation](#installation)
- [Development Notes](#development-notes)

## 🎯 Overview

This educational project consists of three distinct Magento 2 modules, each demonstrating different core concepts:

1. **EDU_HelloWorld** - Focuses on **Preferences, Plugins & Events**
2. **EDU_InventoryReport** - Demonstrates **Controllers & Layout** functionality
3. **EDU_QuestionHub** - Implements a complete **Data Layer** with Q&A system

Each module is self-contained and can be studied independently, making it perfect for learning specific Magento 2
development patterns.

## 📁 Module Structure

```
EDU/
├── HelloWorld/          # Preferences, Plugins & Events
├── InventoryReport/     # Controllers & Layout
└── QuestionHub/         # Data Layer
```

---

## 🎪 EDU_HelloWorld - Preferences, Plugins & Events

**Purpose**: Demonstrates Magento 2's dependency injection, plugins, and event system.

### Key Learning Concepts

- **Preferences** - Overriding core Magento classes
- **Plugins** - Intercepting and modifying method behavior
- **Events & Observers** - Reacting to system events

### Module Tasks & Examples

#### 1. **Preferences Example**

- **File**: `Model/Product.php`
- **Concept**: Overriding the core Product model using dependency injection
- **Learning**: How to replace core Magento functionality with custom implementations

#### 2. **Plugin Example**

- **File**: `Plugin/CheckReview.php`
- **Target**: `Magento\Review\Model\Review::validate()`
- **Function**: Prevents review submission if nickname contains a dash (-)
- **Learning**: Using `beforeValidate()` and `afterValidate()` methods to modify validation logic

#### 3. **Event Observer Examples**

**AddFreeGiftObserver**

- **File**: `Observer/AddFreeGiftObserver.php`
- **Event**: `checkout_cart_product_add_after`
- **Function**: Automatically adds a free gift product to cart when a simple product is added
- **Learning**: Reacting to cart events and modifying cart behavior

**NotifyUserAboutFreeGift**

- **File**: `Observer/NotifyUserAboutFreeGift.php`
- **Event**: `vendor_freegift_added` (custom event)
- **Function**: Sends notification when free gift is added
- **Learning**: Creating and dispatching custom events

### Configuration Files

- `etc/di.xml` - Dependency injection configuration
- `etc/events.xml` - Event observer registration
- `etc/frontend/di.xml` - Frontend-specific DI configuration

---

## 🎛️ EDU_InventoryReport - Controllers & Layout

**Purpose**: Demonstrates Magento 2's controller system and layout management for both frontend and admin.

### Key Learning Concepts

- **Controllers** - Handling HTTP requests and responses
- **Layout** - Managing page structure and templates
- **Admin Controllers** - Creating admin panel functionality
- **Blocks** - Business logic separation

### Module Tasks & Examples

#### 1. **Frontend Controller**

- **File**: `Controller/Index/Index.php`
- **Route**: Frontend page controller
- **Learning**: Basic controller structure and page rendering

#### 2. **Admin Controller**

- **File**: `Controller/Adminhtml/Report/Index.php`
- **Route**: Admin panel controller with ACL protection
- **Learning**: Admin controller patterns and authorization

#### 3. **Layout Management**

- **Frontend Layout**: `view/frontend/layout/cart_index_index.xml`
- **Admin Layout**: `view/adminhtml/layout/report_report_index.xml`
- **Learning**: XML layout configuration and block assignment

#### 4. **Template System**

- **File**: `view/adminhtml/templates/content.phtml`
- **Function**: Comprehensive inventory report dashboard
- **Features**:
  - Product stock analysis
  - Visual charts and statistics
  - Responsive design
  - Stock health indicators

#### 5. **Block Implementation**

- **File**: `Block/Adminhtml/Inventory.php`
- **Function**: Business logic for inventory reporting
- **Methods**:
  - `getProducts()` - Retrieve in-stock products
  - `getOutOfStockProducts()` - Get out-of-stock products
  - `getProductCount()` - Count products
  - Stock analysis and filtering

### Configuration Files

- `etc/frontend/routes.xml` - Frontend routing
- `etc/adminhtml/routes.xml` - Admin routing
- `etc/acl.xml` - Access control list
- `etc/adminhtml/menu.xml` - Admin menu structure

---

## 🗄️ EDU_QuestionHub - Data Layer

**Purpose**: Implements a complete Product Q&A system demonstrating Magento 2's data layer architecture.

### Key Learning Concepts

- **Models** - Data representation and business logic
- **Resource Models** - Database operations
- **Repositories** - Data access abstraction
- **API Interfaces** - Service contracts
- **Collections** - Data querying and filtering

### Module Tasks & Examples

#### 1. **Complete Q&A System Implementation**

**Models**

- **Question Model** (`Model/Question.php`)
  - Implements `QuestionInterface` and `IdentityInterface`
  - Status management (pending/approved/rejected)
  - Cache tag implementation
  - Business logic methods (`approve()`, `reject()`)

- **Answer Model** (`Model/Answer.php`)
  - Answer management with helpful voting
  - Admin vs customer answer distinction
  - Vote counting functionality

- **Vote Model** (`Model/Vote.php`)
  - Voting system with duplicate prevention
  - IP and email tracking

**Resource Models**

- **Question Resource** (`Model/ResourceModel/Question.php`)
- **Answer Resource** (`Model/ResourceModel/Answer.php`)
- **Vote Resource** (`Model/ResourceModel/Vote.php`)
- **Collections** for each entity with filtering capabilities

**Repositories**

- **QuestionRepository** (`Model/QuestionRepository.php`)
  - Full CRUD operations
  - Search criteria support
  - Product-specific queries
  - Status management methods

- **AnswerRepository** (`Model/AnswerRepository.php`)
  - Answer management
  - Question-specific answer retrieval

#### 2. **API Layer Implementation**

**Interfaces**

- `Api/QuestionRepositoryInterface.php`
- `Api/AnswerRepositoryInterface.php`
- `Api/Data/QuestionInterface.php`
- `Api/Data/AnswerInterface.php`
- `Api/Data/QuestionSearchResultsInterface.php`

**REST API Endpoints**

- Question management endpoints
- Answer submission and voting
- Product-specific Q&A retrieval

#### 3. **Frontend Integration**

**Block Implementation**

- **QuestionAnswer Block** (`Block/Product/QuestionAnswer.php`)
  - Product page integration
  - Question and answer display logic
  - Customer authentication handling

**Templates**

- **Q&A Template** (`view/frontend/templates/product/question-answer.phtml`)
  - Interactive Q&A interface
  - Modal forms for questions
  - Inline answer forms
  - Voting system UI
  - Responsive design

**Layout Integration**

- **Product Page Layout** (`view/frontend/layout/catalog_product_view.xml`)
  - Seamless product page integration

#### 4. **Admin Management**

**Admin Controllers**

- Question management with mass actions
- Approval/rejection workflow
- Grid display with filtering

**Admin Templates**

- Question management grid
- Status workflow interface

### Database Schema

- **product_question** - Questions table
- **product_answer** - Answers table
- **question_vote** - Voting system table

### Configuration Files

- `etc/db_schema.xml` - Database structure
- `etc/di.xml` - Dependency injection
- `etc/webapi.xml` - REST API endpoints
- `etc/acl.xml` - Admin permissions

---

## 🚀 Installation

### Prerequisites

- Magento 2.4+
- PHP 7.4+
- MySQL 5.7+

### Installation Steps

1. **Copy modules to Magento installation**:
   ```bash
   cp -r app/code/EDU /path/to/magento/app/code/
   ```

2. **Enable modules**:
   ```bash
   php bin/magento module:enable EDU_HelloWorld EDU_InventoryReport EDU_QuestionHub
   ```

3. **Run setup upgrade**:
   ```bash
   php bin/magento setup:upgrade
   ```

4. **Compile and deploy**:
   ```bash
   php bin/magento setup:di:compile
   php bin/magento setup:static-content:deploy
   ```

5. **Clear cache**:
   ```bash
   php bin/magento cache:clean
   ```

### Access Points

- **HelloWorld Features**: Integrated throughout the site (cart, reviews)
- **Inventory Report**: Admin → EDU InventoryReport → Inventory Report
- **Question Hub**: Product pages (Q&A section) + Admin → EDU QuestionHub → Questions

---

## 📝 Development Notes

### Learning Path Recommendations

1. **Start with HelloWorld** - Learn basic Magento concepts
2. **Move to InventoryReport** - Understand controllers and layouts
3. **Finish with QuestionHub** - Master the data layer

### Key Takeaways

- **Dependency Injection**: How Magento's DI system works
- **Plugin System**: Intercepting and modifying core functionality
- **Event System**: Reacting to system events and creating custom events
- **Controller Patterns**: Both frontend and admin controller development
- **Layout System**: XML layout configuration and template management
- **Data Layer**: Complete model-repository-resource architecture
- **API Development**: REST API implementation and service contracts

### Module Dependencies

- **HelloWorld**: Magento_Catalog, Magento_Customer, Magento_Review
- **InventoryReport**: Magento_Catalog, Magento_Backend, Magento_CatalogInventory
- **QuestionHub**: Magento_Catalog, Magento_Customer, Magento_Backend

---

## 📄 License

This educational project follows Magento's standard licensing terms and is intended for learning purposes.

**Magento Compatibility**: 2.4+  
**PHP Version**: 7.4+  
**Last Updated**: 2025
