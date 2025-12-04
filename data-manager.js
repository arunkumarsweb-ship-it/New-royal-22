// Data Manager - Handles all data operations using localStorage

class DataManager {
    constructor() {
        this.init();
    }

    init() {
        // Initialize default categories if not exists
        if (!localStorage.getItem('categories')) {
            const defaultCategories = [
                { id: 1, name: 'Poonam Sarees Without Blouse' },
                { id: 2, name: 'Poonam Sarees With Blouse' },
                { id: 3, name: 'Farak Sarees With Blouse' },
                { id: 4, name: 'Farak Sarees Without Blouse' },
                { id: 5, name: 'Designer Sarees' },
                { id: 6, name: 'Accessories' },
                { id: 7, name: 'Others' }
            ];
            localStorage.setItem('categories', JSON.stringify(defaultCategories));
        }

        // Initialize subcategories (30 days for each category)
        if (!localStorage.getItem('subcategories')) {
            const categories = this.getCategories();
            const subcategories = [];
            let subId = 1;
            
            categories.forEach(category => {
                for (let day = 1; day <= 30; day++) {
                    subcategories.push({
                        id: subId++,
                        category_id: category.id,
                        day_number: day
                    });
                }
            });
            
            localStorage.setItem('subcategories', JSON.stringify(subcategories));
        }

        // Initialize products array if not exists
        if (!localStorage.getItem('products')) {
            localStorage.setItem('products', JSON.stringify([]));
        }

        // Initialize next product ID
        if (!localStorage.getItem('nextProductId')) {
            localStorage.setItem('nextProductId', '1');
        }
    }

    // Category operations
    getCategories() {
        return JSON.parse(localStorage.getItem('categories') || '[]');
    }

    getCategory(id) {
        const categories = this.getCategories();
        return categories.find(c => c.id === parseInt(id));
    }

    // Subcategory operations
    getSubcategories(categoryId) {
        const subcategories = JSON.parse(localStorage.getItem('subcategories') || '[]');
        return subcategories.filter(s => s.category_id === parseInt(categoryId));
    }

    getSubcategory(id) {
        const subcategories = JSON.parse(localStorage.getItem('subcategories') || '[]');
        return subcategories.find(s => s.id === parseInt(id));
    }

    // Product operations
    getProducts(subcategoryId) {
        const products = JSON.parse(localStorage.getItem('products') || '[]');
        return products.filter(p => p.subcategory_id === parseInt(subcategoryId))
            .sort((a, b) => a.product_number - b.product_number);
    }

    getProduct(id) {
        const products = JSON.parse(localStorage.getItem('products') || '[]');
        return products.find(p => p.id === parseInt(id));
    }

    createProduct(productData) {
        const products = JSON.parse(localStorage.getItem('products') || '[]');
        const nextId = parseInt(localStorage.getItem('nextProductId') || '1');
        
        // Check if product number already exists in this subcategory
        const existing = products.find(p => 
            p.subcategory_id === productData.subcategory_id && 
            p.product_number === productData.product_number
        );
        
        if (existing) {
            return { success: false, error: 'Product number already exists in this subcategory' };
        }

        const newProduct = {
            id: nextId,
            subcategory_id: parseInt(productData.subcategory_id),
            product_number: parseInt(productData.product_number),
            image_path: productData.image_path, // base64 or data URL
            availability_status: productData.availability_status || 'available',
            created_at: new Date().toISOString(),
            updated_at: new Date().toISOString()
        };

        products.push(newProduct);
        localStorage.setItem('products', JSON.stringify(products));
        localStorage.setItem('nextProductId', String(nextId + 1));

        return { success: true, id: nextId };
    }

    updateProduct(productId, productData) {
        const products = JSON.parse(localStorage.getItem('products') || '[]');
        const index = products.findIndex(p => p.id === parseInt(productId));

        if (index === -1) {
            return { success: false, error: 'Product not found' };
        }

        // Check if product number already exists in this subcategory (excluding current product)
        if (productData.product_number) {
            const existing = products.find(p => 
                p.id !== parseInt(productId) &&
                p.subcategory_id === products[index].subcategory_id && 
                p.product_number === parseInt(productData.product_number)
            );
            
            if (existing) {
                return { success: false, error: 'Product number already exists in this subcategory' };
            }
        }

        // Update product
        if (productData.product_number) {
            products[index].product_number = parseInt(productData.product_number);
        }
        if (productData.image_path) {
            products[index].image_path = productData.image_path;
        }
        if (productData.availability_status) {
            products[index].availability_status = productData.availability_status;
        }
        products[index].updated_at = new Date().toISOString();

        localStorage.setItem('products', JSON.stringify(products));
        return { success: true };
    }

    deleteProduct(productId) {
        const products = JSON.parse(localStorage.getItem('products') || '[]');
        const filtered = products.filter(p => p.id !== parseInt(productId));
        
        if (filtered.length === products.length) {
            return { success: false, error: 'Product not found' };
        }

        localStorage.setItem('products', JSON.stringify(filtered));
        return { success: true };
    }

    getMaxProductNumber(subcategoryId) {
        const products = this.getProducts(subcategoryId);
        if (products.length === 0) return 0;
        return Math.max(...products.map(p => p.product_number));
    }

    // Image handling - convert file to base64
    async fileToBase64(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => resolve(reader.result);
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }
}

// Create global instance
const dataManager = new DataManager();

