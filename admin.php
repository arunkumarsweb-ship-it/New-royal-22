<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saree Availability - Admin Panel</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><a href="index.php">Saree Availability System - Admin Panel</a></h1>
        </div>
    </header>

    <main class="container">
        <div class="admin-tabs">
            <button class="tab-button active" onclick="showTab('single-upload')">Single Product Upload</button>
            <button class="tab-button" onclick="showTab('bulk-upload')">Bulk Product Upload</button>
            <button class="tab-button" onclick="showTab('manage-products')">Manage Products</button>
        </div>

        <!-- Single Product Upload Tab -->
        <div id="single-upload" class="tab-content active">
            <h2>Add Single Product</h2>
            <form id="single-product-form" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="single-category">Category:</label>
                    <select id="single-category" required>
                        <option value="">Select Category</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="single-day">Day:</label>
                    <select id="single-day" required>
                        <option value="">Select Day</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="single-product-number">Product Number:</label>
                    <input type="number" id="single-product-number" min="1" max="500" required>
                </div>

                <div class="form-group">
                    <label for="single-image">Product Image:</label>
                    <input type="file" id="single-image" accept="image/*" required>
                </div>

                <div class="form-group">
                    <label for="single-status">Availability Status:</label>
                    <select id="single-status">
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                        <option value="hold">Hold</option>
                    </select>
                </div>

                <button type="submit" class="btn-primary">Add Product</button>
                <div id="single-message" class="message"></div>
            </form>
        </div>

        <!-- Bulk Upload Tab -->
        <div id="bulk-upload" class="tab-content">
            <h2>Bulk Product Upload</h2>
            <form id="bulk-upload-form" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="bulk-category">Category:</label>
                    <select id="bulk-category" required>
                        <option value="">Select Category</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="bulk-day">Day:</label>
                    <select id="bulk-day" required>
                        <option value="">Select Day</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="numbering-mode">Numbering Mode:</label>
                    <select id="numbering-mode" required>
                        <option value="auto">Auto (Sequential)</option>
                        <option value="manual">Manual (Specify starting number)</option>
                    </select>
                </div>

                <div class="form-group" id="starting-number-group">
                    <label for="starting-number">Starting Number:</label>
                    <input type="number" id="starting-number" min="1" max="500" value="1">
                </div>

                <div class="form-group">
                    <label for="bulk-images">Select Images (Multiple):</label>
                    <input type="file" id="bulk-images" accept="image/*" multiple required>
                    <small>You can select multiple images at once</small>
                </div>

                <div class="form-group">
                    <label for="bulk-status">Availability Status:</label>
                    <select id="bulk-status">
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                        <option value="hold">Hold</option>
                    </select>
                </div>

                <button type="submit" class="btn-primary">Upload Products</button>
                <div id="bulk-message" class="message"></div>
            </form>
        </div>

        <!-- Manage Products Tab -->
        <div id="manage-products" class="tab-content">
            <h2>Manage Products</h2>
            
            <div class="filter-section">
                <label for="manage-category">Category:</label>
                <select id="manage-category">
                    <option value="">Select Category</option>
                </select>

                <label for="manage-day">Day:</label>
                <select id="manage-day">
                    <option value="">Select Day</option>
                </select>

                <button onclick="loadProductsForManagement()" class="btn-secondary">Load Products</button>
            </div>

            <div id="manage-products-container" class="products-grid">
                <p class="info-message">Select a category and day to manage products.</p>
            </div>
        </div>
    </main>

    <!-- Edit Product Modal -->
    <div id="edit-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Product</h2>
            <form id="edit-product-form" enctype="multipart/form-data">
                <input type="hidden" id="edit-product-id">
                
                <div class="form-group">
                    <label for="edit-product-number">Product Number:</label>
                    <input type="number" id="edit-product-number" min="1" max="500" required>
                </div>

                <div class="form-group">
                    <label for="edit-image">Product Image (leave empty to keep current):</label>
                    <input type="file" id="edit-image" accept="image/*">
                    <div id="current-image-preview"></div>
                </div>

                <div class="form-group">
                    <label for="edit-status">Availability Status:</label>
                    <select id="edit-status">
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                        <option value="hold">Hold</option>
                    </select>
                </div>

                <button type="submit" class="btn-primary">Update Product</button>
                <button type="button" class="btn-danger" onclick="deleteProduct()">Delete Product</button>
                <div id="edit-message" class="message"></div>
            </form>
        </div>
    </div>

    <script src="js/main.js"></script>
    <script>
        let currentEditProductId = null;

        document.addEventListener('DOMContentLoaded', function() {
            loadCategoriesForAdmin();
            
            // Single product form
            document.getElementById('single-product-form').addEventListener('submit', handleSingleUpload);
            
            // Bulk upload form
            document.getElementById('bulk-upload-form').addEventListener('submit', handleBulkUpload);
            
            // Edit product form
            document.getElementById('edit-product-form').addEventListener('submit', handleEditProduct);
            
            // Category change handlers
            document.getElementById('single-category').addEventListener('change', function() {
                loadSubcategoriesForAdmin(this.value, 'single-day');
            });
            
            document.getElementById('bulk-category').addEventListener('change', function() {
                loadSubcategoriesForAdmin(this.value, 'bulk-day');
            });
            
            document.getElementById('manage-category').addEventListener('change', function() {
                loadSubcategoriesForAdmin(this.value, 'manage-day');
            });
        });

        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
        }

        function loadCategoriesForAdmin() {
            fetch('api/get_categories.php')
                .then(response => response.json())
                .then(categories => {
                    ['single-category', 'bulk-category', 'manage-category'].forEach(selectId => {
                        const select = document.getElementById(selectId);
                        select.innerHTML = '<option value="">Select Category</option>';
                        categories.forEach(category => {
                            const option = document.createElement('option');
                            option.value = category.id;
                            option.textContent = category.name;
                            select.appendChild(option);
                        });
                    });
                })
                .catch(error => console.error('Error loading categories:', error));
        }

        function loadSubcategoriesForAdmin(categoryId, targetSelectId) {
            if (!categoryId) {
                document.getElementById(targetSelectId).innerHTML = '<option value="">Select Day</option>';
                return;
            }

            fetch(`api/get_subcategories.php?category_id=${categoryId}`)
                .then(response => response.json())
                .then(subcategories => {
                    const select = document.getElementById(targetSelectId);
                    select.innerHTML = '<option value="">Select Day</option>';
                    subcategories.forEach(sub => {
                        const option = document.createElement('option');
                        option.value = sub.id;
                        option.textContent = `Day ${sub.day_number}`;
                        select.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading subcategories:', error));
        }

        function handleSingleUpload(e) {
            e.preventDefault();
            const messageDiv = document.getElementById('single-message');
            messageDiv.textContent = 'Uploading...';
            messageDiv.className = 'message';

            const formData = new FormData();
            formData.append('subcategory_id', document.getElementById('single-day').value);
            formData.append('product_number', document.getElementById('single-product-number').value);
            formData.append('availability_status', document.getElementById('single-status').value);
            formData.append('image', document.getElementById('single-image').files[0]);

            fetch('api/create_product.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageDiv.textContent = 'Product added successfully!';
                    messageDiv.className = 'message success';
                    document.getElementById('single-product-form').reset();
                } else {
                    messageDiv.textContent = data.error || 'Error adding product';
                    messageDiv.className = 'message error';
                }
            })
            .catch(error => {
                messageDiv.textContent = 'Error: ' + error.message;
                messageDiv.className = 'message error';
            });
        }

        function handleBulkUpload(e) {
            e.preventDefault();
            const messageDiv = document.getElementById('bulk-message');
            const files = document.getElementById('bulk-images').files;
            
            if (files.length === 0) {
                messageDiv.textContent = 'Please select at least one image';
                messageDiv.className = 'message error';
                return;
            }

            messageDiv.textContent = 'Uploading ' + files.length + ' products...';
            messageDiv.className = 'message';

            const formData = new FormData();
            formData.append('subcategory_id', document.getElementById('bulk-day').value);
            formData.append('numbering_mode', document.getElementById('numbering-mode').value);
            formData.append('starting_number', document.getElementById('starting-number').value);
            formData.append('availability_status', document.getElementById('bulk-status').value);

            for (let i = 0; i < files.length; i++) {
                formData.append('images[]', files[i]);
            }

            fetch('api/bulk_upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageDiv.textContent = `Successfully uploaded ${data.uploaded_count} product(s)!`;
                    messageDiv.className = 'message success';
                    if (data.errors && data.errors.length > 0) {
                        messageDiv.textContent += '\nErrors: ' + data.errors.join(', ');
                    }
                    document.getElementById('bulk-upload-form').reset();
                } else {
                    messageDiv.textContent = data.error || 'Error uploading products';
                    messageDiv.className = 'message error';
                }
            })
            .catch(error => {
                messageDiv.textContent = 'Error: ' + error.message;
                messageDiv.className = 'message error';
            });
        }

        function loadProductsForManagement() {
            const subcategoryId = document.getElementById('manage-day').value;
            if (!subcategoryId) {
                alert('Please select a category and day');
                return;
            }

            const container = document.getElementById('manage-products-container');
            container.innerHTML = '<p class="loading">Loading products...</p>';

            fetch(`api/get_products.php?subcategory_id=${subcategoryId}`)
                .then(response => response.json())
                .then(products => {
                    if (products.error) {
                        container.innerHTML = `<p class="error">${products.error}</p>`;
                        return;
                    }

                    if (products.length === 0) {
                        container.innerHTML = '<p class="info-message">No products found.</p>';
                        return;
                    }

                    container.innerHTML = '';
                    products.forEach(product => {
                        const productCard = createManageProductCard(product);
                        container.appendChild(productCard);
                    });
                })
                .catch(error => {
                    console.error('Error loading products:', error);
                    container.innerHTML = '<p class="error">Error loading products.</p>';
                });
        }

        function createManageProductCard(product) {
            const card = document.createElement('div');
            card.className = 'product-card manage-card';
            
            const statusClass = product.availability_status;
            const statusText = product.availability_status.charAt(0).toUpperCase() + product.availability_status.slice(1);
            
            card.innerHTML = `
                <div class="product-image">
                    <img src="${product.image_path}" alt="Product ${product.product_number}" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23ddd%22 width=%22200%22 height=%22200%22/%3E%3Ctext fill=%22%23999%22 font-family=%22sans-serif%22 font-size=%2214%22 dy=%2210.5%22 font-weight=%22bold%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22%3ENo Image%3C/text%3E%3C/svg%3E'">
                </div>
                <div class="product-number">${product.product_number}</div>
                <div class="product-status status-${statusClass}">${statusText}</div>
                <button class="btn-edit" onclick="openEditModal(${product.id}, ${product.product_number}, '${product.availability_status}', '${product.image_path}')">Edit</button>
            `;
            
            return card;
        }

        function openEditModal(productId, productNumber, status, imagePath) {
            currentEditProductId = productId;
            document.getElementById('edit-product-id').value = productId;
            document.getElementById('edit-product-number').value = productNumber;
            document.getElementById('edit-status').value = status;
            
            const preview = document.getElementById('current-image-preview');
            preview.innerHTML = `<p>Current Image:</p><img src="${imagePath}" style="max-width: 200px; margin-top: 10px;">`;
            
            document.getElementById('edit-modal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('edit-modal').style.display = 'none';
            currentEditProductId = null;
            document.getElementById('edit-product-form').reset();
            document.getElementById('current-image-preview').innerHTML = '';
        }

        function handleEditProduct(e) {
            e.preventDefault();
            const messageDiv = document.getElementById('edit-message');
            messageDiv.textContent = 'Updating...';
            messageDiv.className = 'message';

            const formData = new FormData();
            formData.append('product_id', document.getElementById('edit-product-id').value);
            formData.append('product_number', document.getElementById('edit-product-number').value);
            formData.append('availability_status', document.getElementById('edit-status').value);

            const imageFile = document.getElementById('edit-image').files[0];
            if (imageFile) {
                formData.append('image', imageFile);
            }

            fetch('api/update_product.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageDiv.textContent = 'Product updated successfully!';
                    messageDiv.className = 'message success';
                    setTimeout(() => {
                        closeEditModal();
                        loadProductsForManagement();
                    }, 1500);
                } else {
                    messageDiv.textContent = data.error || 'Error updating product';
                    messageDiv.className = 'message error';
                }
            })
            .catch(error => {
                messageDiv.textContent = 'Error: ' + error.message;
                messageDiv.className = 'message error';
            });
        }

        function deleteProduct() {
            if (!confirm('Are you sure you want to delete this product?')) {
                return;
            }

            const messageDiv = document.getElementById('edit-message');
            messageDiv.textContent = 'Deleting...';
            messageDiv.className = 'message';

            const formData = new FormData();
            formData.append('product_id', currentEditProductId);

            fetch('api/delete_product.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageDiv.textContent = 'Product deleted successfully!';
                    messageDiv.className = 'message success';
                    setTimeout(() => {
                        closeEditModal();
                        loadProductsForManagement();
                    }, 1500);
                } else {
                    messageDiv.textContent = data.error || 'Error deleting product';
                    messageDiv.className = 'message error';
                }
            })
            .catch(error => {
                messageDiv.textContent = 'Error: ' + error.message;
                messageDiv.className = 'message error';
            });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('edit-modal');
            if (event.target == modal) {
                closeEditModal();
            }
        }
    </script>
</body>
</html>

