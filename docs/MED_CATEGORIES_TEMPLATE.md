# Medical Categories Page Template

## Overview
Created a template page to display all medical categories in a grid layout.

## Template File
- **File**: `template-med_categories.php`
- **Template Name**: Medical Categories
- **Usage**: Assign to any WordPress page to display medical categories

## Features

### 1. Page Structure
- **Breadcrumbs**: Home > Page Title
- **Page Title**: Dynamic from WordPress page
- **Page Description**: Uses WordPress page content
- **Categories Grid**: Displays all medical categories

### 2. Category Cards
Each category card displays:
- **Category Title**: With clickable link to category archive
- **Category Image**: From `med_category_image_id` meta field or fallback image
- **Category Description**: Term description
- **Products Count**: Number of products in category
- **View Products Button**: Link to category archive

### 3. Responsive Design
- **Desktop**: Grid layout with 3+ columns
- **Mobile**: Single column layout
- **Hover Effects**: Cards lift up on hover

## Usage Instructions

### Step 1: Create WordPress Page
1. Go to **Pages > Add New** in WordPress admin
2. Set page title (e.g., "Medical Categories")
3. Add page description in content area
4. In **Page Attributes > Template**, select "Medical Categories"
5. **Publish** the page

### Step 2: Navigation
Add the page to your main navigation menu or link to it from other pages.

### Step 3: Customization
- **Images**: Add category images via `med_category_image_id` field
- **Descriptions**: Add category descriptions in taxonomy edit page
- **Styling**: Modify CSS in `style.css` under `.med-categories-page`

## Template Structure

```php
template-med_categories.php
├── Header with breadcrumbs
├── Page title and description
├── Categories grid loop
│   ├── Category title (linked)
│   ├── Category image (liteimage)
│   ├── Category description
│   ├── Products count
│   └── View products button
└── Footer
```

## Styling Classes
- `.med-categories-page` - Main page container
- `.med-categories-grid` - Grid layout container
- `.category-card` - Individual category card
- `.category-info` - Card content area
- `.category-description` - Category description text
- `.products-count` - Products count display
- `.page-description` - Page content area

## Dependencies
- **LiteImage helper** - For responsive images
- **WordPress taxonomy functions**
- **Custom meta fields** - `med_category_image_id`

## Future Enhancements
- Add category color picker
- Add category sorting options
- Add search/filter functionality
- Add pagination for large category lists
