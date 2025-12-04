<?php require_once 'config/whatsapp.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saree Availability - Products</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><a href="index.php">Saree Availability System</a></h1>
            <nav class="main-nav">
                <ul id="category-menu">
                    <li>Loading...</li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="breadcrumb">
            <a href="index.php">Home</a> > 
            <span id="category-name">Category</span> > 
            <span id="day-name">Day 1</span>
        </div>

        <div class="filter-section">
            <label for="category-select">Category:</label>
            <select id="category-select">
                <option value="">Select Category</option>
            </select>

            <label for="day-select">Day:</label>
            <select id="day-select">
                <option value="">Select Day</option>
            </select>
        </div>

        <div id="products-container" class="products-grid">
            <p class="info-message">Please select a category and day to view products.</p>
        </div>
    </main>

    <!-- WhatsApp Customer Care Button -->
    <a href="https://wa.me/<?php echo WHATSAPP_NUMBER; ?>?text=<?php echo urlencode(WHATSAPP_MESSAGE); ?>" 
       class="whatsapp-float" 
       target="_blank" 
       rel="noopener noreferrer"
       title="Contact Customer Care on WhatsApp">
        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M16 0C7.164 0 0 7.164 0 16c0 2.825.74 5.47 2.035 7.77L0 32l8.5-2.1C10.8 31.4 13.3 32 16 32c8.836 0 16-7.164 16-16S24.836 0 16 0z" fill="#25D366"/>
            <path d="M24.5 20.1c-.3.8-1.5 1.5-2.1 1.6-.6.1-1.4.5-4.1-.5-3.3-1.2-5.4-5.9-5.6-6.1-.2-.3-1.6-2.1-1.6-4s1-2.8 1.4-3.4c.4-.5.8-.6 1.1-.6h1.3c.3 0 .7.1.9.5.2.4.7 1.4.8 1.7.1.3.1.5 0 .8-.1.3-.2.5-.4.7-.2.2-.4.4-.6.6-.2.2-.4.4-.2.7.2.3.4.6.8 1 .4.4.7.7 1 .9.4.3.6.5.9.4.3-.1 1.3-.5 1.6-1 .3-.5.3-1 .2-1.2-.1-.2-.4-.3-.8-.5-.4-.2-2.4-1.2-2.8-1.3-.4-.1-.7-.2-1 .2-.3.4-1.2 1.3-1.5 1.6-.3.3-.6.4-1 .1-.4-.3-1.7-.6-3.2-1.9-1.2-1-2-2.2-2.8-3.1-.3-.4 0-.6.2-.8.2-.2.4-.4.6-.6.2-.2.3-.4.4-.6.1-.2.1-.4 0-.6-.1-.2-.4-.6-.6-.8-.2-.2-.4-.3-.6-.3-.2 0-.4 0-.6.1-.2.1-.4.2-.6.3-.2.1-.4.3-.5.5-.2.3-.4.6-.6 1-.2.4-.4.8-.6 1.2-.2.4-.1.8.1 1.2.4.8 1.8 3.3 3.9 4.5 2.1 1.2 3.9 1.5 4.7 1.7.8.2 1.6.2 2.2.1.6-.1 1.3-.3 1.8-.6.5-.3 1-.7 1.4-1.1.2-.2.4-.4.6-.6.2-.2.4-.2.6-.1.2.1.4.3.6.5.2.2.4.4.6.6.2.2.4.4.5.6.1.2.1.4.1.6 0 .2-.1.4-.2.6z" fill="#FFF"/>
        </svg>
        <span>Customer Care</span>
    </a>

    <script src="js/main.js"></script>
    <script>
        let currentCategoryId = null;
        let currentSubcategoryId = null;

        document.addEventListener('DOMContentLoaded', function() {
            loadCategories();
            
            // Get category_id from URL if present
            const urlParams = new URLSearchParams(window.location.search);
            const categoryId = urlParams.get('category_id');
            if (categoryId) {
                currentCategoryId = parseInt(categoryId);
                document.getElementById('category-select').value = categoryId;
                loadSubcategories(categoryId);
            }

            // Category change handler
            document.getElementById('category-select').addEventListener('change', function() {
                const catId = parseInt(this.value);
                if (catId) {
                    currentCategoryId = catId;
                    loadSubcategories(catId);
                    // Update URL
                    window.history.pushState({}, '', `products.php?category_id=${catId}`);
                }
            });

            // Day change handler
            document.getElementById('day-select').addEventListener('change', function() {
                const subId = parseInt(this.value);
                if (subId) {
                    currentSubcategoryId = subId;
                    loadProducts(subId);
                } else {
                    document.getElementById('products-container').innerHTML = '<p class="info-message">Please select a day to view products.</p>';
                }
            });
        });

        function loadCategories() {
            fetch('api/get_categories.php')
                .then(response => response.json())
                .then(categories => {
                    const select = document.getElementById('category-select');
                    const menu = document.getElementById('category-menu');
                    
                    // Populate dropdown
                    categories.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.id;
                        option.textContent = category.name;
                        if (currentCategoryId && category.id == currentCategoryId) {
                            option.selected = true;
                        }
                        select.appendChild(option);
                    });

                    // Populate navigation menu
                    menu.innerHTML = '';
                    categories.forEach(category => {
                        const li = document.createElement('li');
                        const a = document.createElement('a');
                        a.href = `products.php?category_id=${category.id}`;
                        a.textContent = category.name;
                        li.appendChild(a);
                        menu.appendChild(li);
                    });
                })
                .catch(error => console.error('Error loading categories:', error));
        }

        function loadSubcategories(categoryId) {
            fetch(`api/get_subcategories.php?category_id=${categoryId}`)
                .then(response => response.json())
                .then(subcategories => {
                    const select = document.getElementById('day-select');
                    select.innerHTML = '<option value="">Select Day</option>';
                    
                    subcategories.forEach(sub => {
                        const option = document.createElement('option');
                        option.value = sub.id;
                        option.textContent = `Day ${sub.day_number}`;
                        select.appendChild(option);
                    });

                    // Update category name in breadcrumb
                    fetch('api/get_categories.php')
                        .then(response => response.json())
                        .then(categories => {
                            const category = categories.find(c => c.id == categoryId);
                            if (category) {
                                document.getElementById('category-name').textContent = category.name;
                            }
                        });
                })
                .catch(error => console.error('Error loading subcategories:', error));
        }

        function loadProducts(subcategoryId) {
            const container = document.getElementById('products-container');
            container.innerHTML = '<p class="loading">Loading products...</p>';

            fetch(`api/get_products.php?subcategory_id=${subcategoryId}`)
                .then(response => response.json())
                .then(products => {
                    if (products.error) {
                        container.innerHTML = `<p class="error">${products.error}</p>`;
                        return;
                    }

                    if (products.length === 0) {
                        container.innerHTML = '<p class="info-message">No products available for this day.</p>';
                        return;
                    }

                    container.innerHTML = '';
                    products.forEach(product => {
                        const productCard = createProductCard(product);
                        container.appendChild(productCard);
                    });

                    // Update day name in breadcrumb
                    const daySelect = document.getElementById('day-select');
                    const selectedOption = daySelect.options[daySelect.selectedIndex];
                    if (selectedOption) {
                        document.getElementById('day-name').textContent = selectedOption.textContent;
                    }
                })
                .catch(error => {
                    console.error('Error loading products:', error);
                    container.innerHTML = '<p class="error">Error loading products. Please try again.</p>';
                });
        }

        function createProductCard(product) {
            const card = document.createElement('div');
            card.className = 'product-card';
            
            const statusClass = product.availability_status;
            const statusText = product.availability_status.charAt(0).toUpperCase() + product.availability_status.slice(1);
            
            card.innerHTML = `
                <div class="product-image">
                    <img src="${product.image_path}" alt="Product ${product.product_number}" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23ddd%22 width=%22200%22 height=%22200%22/%3E%3Ctext fill=%22%23999%22 font-family=%22sans-serif%22 font-size=%2214%22 dy=%2210.5%22 font-weight=%22bold%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22%3ENo Image%3C/text%3E%3C/svg%3E'">
                </div>
                <div class="product-number">${product.product_number}</div>
                <div class="product-status status-${statusClass}">${statusText}</div>
            `;
            
            return card;
        }
    </script>
</body>
</html>

