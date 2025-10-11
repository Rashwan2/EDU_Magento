# Store News & Announcements Module

A Magento 2 module that allows store administrators to manage news articles and announcements, with a customer-facing frontend to display published news.

## Features

### Admin Features
- **News Management**: Create, edit, and delete news articles
- **Status Management**: Draft, Published, and Archived statuses
- **Store Scope**: Assign news to specific stores or all stores
- **Admin Grid**: View all news articles in a sortable, filterable grid
- **Form Interface**: Simple form for creating and editing news articles

### Frontend Features
- **News Listing**: Display all published news articles
- **News Detail View**: Individual news article pages
- **Responsive Design**: Mobile-friendly templates
- **Navigation Link**: Easy access from main navigation

## Installation

1. Copy the module files to `app/code/EDU/StoreNews/`
2. Run the following commands:
   ```bash
   php bin/magento module:enable EDU_StoreNews
   php bin/magento setup:upgrade
   php bin/magento setup:di:compile
   php bin/magento cache:flush
   ```

## Usage

### Admin Usage

1. **Access News Management**:
   - Go to Admin Panel → Content → Store News
   - Click "Manage News" to view all articles
   - Click "New Article" to create a new news article

2. **Create News Article**:
   - Fill in the title and content
   - Select status (Draft/Published/Archived)
   - Choose store scope
   - Save the article

3. **Edit News Article**:
   - Click on any article in the grid to edit
   - Make changes and save

### Frontend Usage

1. **View News**:
   - Visit `/news` on your store frontend
   - Click on any news article to read the full content
   - Use the "Back to News List" link to return to the listing

## File Structure

```
app/code/EDU/StoreNews/
├── Block/
│   ├── Adminhtml/News/
│   │   ├── Edit.php
│   │   └── Grid.php
│   └── News/
│       ├── ListBlock.php
│       └── ViewBlock.php
├── Controller/
│   ├── Adminhtml/News/
│   │   ├── Index.php
│   │   ├── NewAction.php
│   │   ├── Edit.php
│   │   ├── Save.php
│   │   └── Delete.php
│   └── Index/
│       ├── Index.php
│       └── View.php
├── Model/
│   ├── News.php
│   └── ResourceModel/
│       ├── News.php
│       └── News/Collection.php
├── etc/
│   ├── module.xml
│   ├── db_schema.xml
│   ├── acl.xml
│   ├── adminhtml/
│   │   ├── routes.xml
│   │   └── menu.xml
│   └── frontend/
│       └── routes.xml
├── view/
│   ├── adminhtml/
│   │   ├── layout/
│   │   └── templates/
│   └── frontend/
│       ├── layout/
│       └── templates/
└── registration.php
```

## Database Schema

The module creates a `edu_store_news` table with the following structure:
- `news_id` (Primary Key)
- `title` (News title)
- `content` (News content)
- `status` (draft/published/archived)
- `created_at` (Creation timestamp)
- `updated_at` (Last update timestamp)
- `published_at` (Publication timestamp)
- `store_id` (Store scope)

## Routes

### Admin Routes
- `/admin/storenews/news/index` - News listing
- `/admin/storenews/news/new` - Create new news
- `/admin/storenews/news/edit/news_id/{id}` - Edit news
- `/admin/storenews/news/save` - Save news
- `/admin/storenews/news/delete/news_id/{id}` - Delete news

### Frontend Routes
- `/news` - News listing page
- `/news/index/view/id/{id}` - Individual news article

## Learning Focus

This module demonstrates:
- **Controllers**: Both admin and frontend controllers
- **Layout XML**: Page layouts and block configurations
- **Templates**: PHTML templates for rendering
- **Menu and Routing**: Admin menu and URL routing
- **Models**: Data models and collections
- **Blocks**: View logic and data preparation
- **Database**: Schema and data persistence

## Customization

### Adding Fields
To add new fields to the news form:
1. Update `db_schema.xml` to add the column
2. Update the admin form template
3. Update the model if needed
4. Run `setup:upgrade`

### Styling
Customize the frontend appearance by modifying the CSS in the template files or creating custom CSS files.

### Functionality
Extend the module by adding features like:
- Categories for news
- Image uploads
- Comments system
- Email notifications
- RSS feeds
