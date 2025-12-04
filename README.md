# Saree Availability Checking Website

A web application for managing and checking saree availability with product images, numbering, and status tracking.

## Features

- **7 Main Categories**: 
  - Poonam Sarees Without Blouse
  - Poonam Sarees With Blouse
  - Farak Sarees With Blouse
  - Farak Sarees Without Blouse
  - Designer Sarees
  - Accessories
  - Others

- **30 Days Subcategories**: Each category has 30 subcategories (Day 1 to Day 30)

- **Product Management**:
  - Up to 500 products per subcategory
  - Image upload for each product
  - Sequential product numbering
  - Availability status (Available, Unavailable, Hold)

- **Admin Panel**:
  - Single product upload
  - Bulk image upload with auto or manual numbering
  - Edit product details
  - Delete products
  - Update availability status

## Installation

1. **Database Setup**:
   - Create a MySQL database
   - Update database credentials in `config/database.php`
   - Import the schema from `database/schema.sql`

2. **File Permissions**:
   - Ensure `uploads/products/` directory is writable (chmod 755 or 777)

3. **Web Server**:
   - Place files in your web server directory (htdocs, www, etc.)
   - Ensure PHP is enabled
   - Access via browser: `http://localhost/your-folder/`

## Database Configuration

Edit `config/database.php` with your database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'saree_availability');
```

## Usage

1. **Customer View**: 
   - Navigate to `index.php`
   - Select a category from the menu
   - Choose a day (1-30)
   - View products with availability status

2. **Admin Panel**:
   - Navigate to `admin.php`
   - Use tabs to:
     - Upload single products
     - Bulk upload multiple products
     - Manage existing products (edit/delete)

## File Structure

```
/
├── index.php              # Main landing page
├── products.php           # Product listing page
├── admin.php              # Admin panel
├── config/
│   └── database.php       # Database configuration
├── api/
│   ├── get_categories.php
│   ├── get_subcategories.php
│   ├── get_products.php
│   ├── create_product.php
│   ├── update_product.php
│   ├── delete_product.php
│   └── bulk_upload.php
├── css/
│   └── style.css          # Main stylesheet
├── js/
│   └── main.js            # JavaScript utilities
├── uploads/
│   └── products/          # Product images directory
└── database/
    └── schema.sql         # Database schema
```

## Requirements

- PHP 7.0 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Modern web browser

## Notes

- Maximum product number per subcategory: 500
- Supported image formats: JPG, PNG, WEBP, GIF
- Images are stored in `uploads/products/` directory
- Product numbers must be unique within each subcategory

