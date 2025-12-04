# Frontend Structure - HTML, CSS & JavaScript Files

## Overview
This document provides a complete overview of all HTML (PHP), CSS, and JavaScript files in the Saree Availability website.

---

## HTML Files (PHP with HTML)

### 1. `index.php` - Home Page
**Purpose**: Landing page with category navigation
**Key Features**:
- Header with site title
- Dynamic category navigation menu (loaded via JavaScript)
- Welcome section
- Link to admin panel
- WhatsApp customer care floating button

**JavaScript Functions**:
- `loadCategories()` - Fetches and displays all categories in navigation

---

### 2. `products.php` - Product Listing Page
**Purpose**: Display products filtered by category and day
**Key Features**:
- Breadcrumb navigation
- Category and Day filter dropdowns
- Product grid display with images, numbers, and availability status
- WhatsApp customer care floating button

**JavaScript Functions**:
- `loadCategories()` - Loads categories into dropdown and menu
- `loadSubcategories(categoryId)` - Loads days (1-30) for selected category
- `loadProducts(subcategoryId)` - Fetches and displays products
- `createProductCard(product)` - Creates product card HTML element

**Product Card Structure**:
- Product image with fallback
- Product number
- Availability status badge (Available/Unavailable/Hold)

---

### 3. `admin.php` - Admin Panel
**Purpose**: Complete CRUD operations for product management
**Key Features**:
- Three tabbed sections:
  1. **Single Product Upload** - Add one product at a time
  2. **Bulk Product Upload** - Upload multiple images with auto/manual numbering
  3. **Manage Products** - Edit and delete existing products

**JavaScript Functions**:
- `showTab(tabName)` - Switch between admin tabs
- `loadCategoriesForAdmin()` - Load categories for all admin forms
- `loadSubcategoriesForAdmin(categoryId, targetSelectId)` - Load days for category
- `handleSingleUpload(e)` - Process single product upload
- `handleBulkUpload(e)` - Process bulk image upload
- `loadProductsForManagement()` - Load products for editing
- `createManageProductCard(product)` - Create product card with edit button
- `openEditModal()` - Open edit modal with product data
- `closeEditModal()` - Close edit modal
- `handleEditProduct(e)` - Update product details
- `deleteProduct()` - Delete product with confirmation

**Modal Features**:
- Edit product form
- Current image preview
- Update/Delete buttons

---

## CSS File

### `css/style.css` - Main Stylesheet

**Sections**:

1. **Reset and Base Styles**
   - Universal reset
   - Body typography and colors
   - Container layout

2. **Header Styles**
   - Gradient background
   - Navigation menu styling
   - Responsive menu

3. **Main Content**
   - Welcome section
   - Breadcrumb navigation
   - Filter sections

4. **Products Grid**
   - Responsive grid layout (auto-fill, minmax 200px)
   - Product card styling
   - Image containers
   - Status badges (Available/Unavailable/Hold)

5. **Admin Panel Styles**
   - Tab navigation
   - Form styling
   - Button styles (primary, secondary, danger, edit)
   - Modal overlay and content

6. **WhatsApp Floating Button**
   - Fixed position (bottom-right)
   - WhatsApp green color (#25D366)
   - Hover effects
   - Responsive (icon-only on mobile)

7. **Responsive Design**
   - Mobile breakpoints (768px, 480px)
   - Grid adjustments
   - Navigation changes
   - Button sizing

**Color Scheme**:
- Primary: #667eea (Purple gradient)
- Success: #4caf50 (Green)
- Error: #f44336 (Red)
- Warning: #ff9800 (Orange)
- WhatsApp: #25D366 (Green)

---

## JavaScript File

### `js/main.js` - Utility Functions

**Functions**:

1. **`formatDate(dateString)`**
   - Formats date strings to readable format
   - Returns: "Jan 15, 2024"

2. **`showNotification(message, type)`**
   - Displays temporary notification popup
   - Types: 'success', 'error', 'info'
   - Auto-dismisses after 3 seconds
   - Includes slide animations

3. **`previewImage(input, previewId)`**
   - Shows image preview before upload
   - Uses FileReader API
   - Updates preview element

4. **`validateImageFile(file)`**
   - Validates image file type and size
   - Allowed: JPG, PNG, WEBP, GIF
   - Max size: 5MB
   - Returns validation object

5. **`formatFileSize(bytes)`**
   - Converts bytes to human-readable format
   - Returns: "2.5 MB", "150 KB", etc.

**Dynamic Styles**:
- Adds notification animation keyframes to document head
- Prevents duplicate style injection

---

## File Structure Summary

```
/
├── index.php              (79 lines)   - Home page
├── products.php           (215 lines)  - Product listing
├── admin.php              (501 lines)  - Admin panel
├── css/
│   └── style.css          (519 lines)  - All styles
└── js/
    └── main.js            (110 lines)  - Utilities
```

---

## Key Technologies Used

- **HTML5**: Semantic markup
- **CSS3**: 
  - Flexbox
  - CSS Grid
  - CSS Variables (implicit)
  - Media Queries
  - Animations
- **JavaScript (ES6+)**:
  - Fetch API
  - Async/Await (via Promises)
  - DOM Manipulation
  - Event Listeners
  - FileReader API

---

## Browser Compatibility

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Mobile responsive
- Graceful degradation for older browsers

---

## Notes

- All PHP files contain HTML structure
- JavaScript is embedded in PHP files for page-specific functionality
- `main.js` contains reusable utility functions
- CSS uses mobile-first responsive design approach
- WhatsApp button uses SVG icon for scalability

