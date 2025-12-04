// Data Management System using localStorage

// Initialize data structure
const STORAGE_KEYS = {
    CATEGORIES: 'saree_categories',
    SUBCATEGORIES: 'saree_subcategories',
    PRODUCTS: 'saree_products',
    WHATSAPP: 'whatsapp_config',
    NEXT_ID: 'next_product_id',
    NEXT_CATEGORY_ID: 'next_category_id'
};

// Initialize default categories
function initializeCategories() {
    const categories = [
        { id: 1, name: 'Poonam Sarees Without Blouse' },
        { id: 2, name: 'Poonam Sarees With Blouse' },
        { id: 3, name: 'Farak Sarees With Blouse' },
        { id: 4, name: 'Farak Sarees Without Blouse' },
        { id: 5, name: 'Designer Sarees' },
        { id: 6, name: 'Accessories' },
        { id: 7, name: 'Others' }
    ];
    
    if (!localStorage.getItem(STORAGE_KEYS.CATEGORIES)) {
        localStorage.setItem(STORAGE_KEYS.CATEGORIES, JSON.stringify(categories));
    }
    
    // Initialize subcategories (30 days for each category)
    if (!localStorage.getItem(STORAGE_KEYS.SUBCATEGORIES)) {
        const subcategories = [];
        categories.forEach(category => {
            for (let day = 1; day <= 30; day++) {
                subcategories.push({
                    id: (category.id - 1) * 30 + day,
                    category_id: category.id,
                    day_number: day
                });
            }
        });
        localStorage.setItem(STORAGE_KEYS.SUBCATEGORIES, JSON.stringify(subcategories));
    }
    
    // Initialize products
    if (!localStorage.getItem(STORAGE_KEYS.PRODUCTS)) {
        localStorage.setItem(STORAGE_KEYS.PRODUCTS, JSON.stringify([]));
    }
    
    // Initialize next product ID
    if (!localStorage.getItem(STORAGE_KEYS.NEXT_ID)) {
        localStorage.setItem(STORAGE_KEYS.NEXT_ID, '1');
    }
    
    // Initialize WhatsApp config
    if (!localStorage.getItem(STORAGE_KEYS.WHATSAPP)) {
        localStorage.setItem(STORAGE_KEYS.WHATSAPP, JSON.stringify({
            number: '911234567890',
            message: 'Hello! I need help with saree availability.'
        }));
    }
    
    // Initialize next category ID
    if (!localStorage.getItem(STORAGE_KEYS.NEXT_CATEGORY_ID)) {
        const existingCategories = localStorage.getItem(STORAGE_KEYS.CATEGORIES);
        if (existingCategories) {
            const cats = JSON.parse(existingCategories);
            const maxId = Math.max(...cats.map(c => c.id), 0);
            localStorage.setItem(STORAGE_KEYS.NEXT_CATEGORY_ID, String(maxId + 1));
        } else {
            localStorage.setItem(STORAGE_KEYS.NEXT_CATEGORY_ID, '8');
        }
    }
}

// Get all categories
function getCategories() {
    const data = localStorage.getItem(STORAGE_KEYS.CATEGORIES);
    return data ? JSON.parse(data) : [];
}

// Get subcategories by category ID
function getSubcategories(categoryId) {
    const data = localStorage.getItem(STORAGE_KEYS.SUBCATEGORIES);
    const allSubs = data ? JSON.parse(data) : [];
    if (categoryId === null || categoryId === undefined) {
        return allSubs;
    }
    return allSubs.filter(sub => sub.category_id == categoryId);
}

// Get products by subcategory ID
function getProducts(subcategoryId) {
    const data = localStorage.getItem(STORAGE_KEYS.PRODUCTS);
    const allProducts = data ? JSON.parse(data) : [];
    return allProducts.filter(product => product.subcategory_id == subcategoryId)
        .sort((a, b) => a.product_number - b.product_number);
}

// Get product by ID
function getProductById(productId) {
    const data = localStorage.getItem(STORAGE_KEYS.PRODUCTS);
    const allProducts = data ? JSON.parse(data) : [];
    return allProducts.find(p => p.id == productId);
}

// Create product
function createProduct(productData) {
    const data = localStorage.getItem(STORAGE_KEYS.PRODUCTS);
    const products = data ? JSON.parse(data) : [];
    
    // Check if product number already exists in this subcategory
    const exists = products.find(p => 
        p.subcategory_id == productData.subcategory_id && 
        p.product_number == productData.product_number
    );
    
    if (exists) {
        return { success: false, error: 'Product number already exists in this day' };
    }
    
    const nextId = parseInt(localStorage.getItem(STORAGE_KEYS.NEXT_ID) || '1');
    const newProduct = {
        id: nextId,
        ...productData,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
    };
    
    products.push(newProduct);
    localStorage.setItem(STORAGE_KEYS.PRODUCTS, JSON.stringify(products));
    localStorage.setItem(STORAGE_KEYS.NEXT_ID, String(nextId + 1));
    
    return { success: true, id: nextId };
}

// Update product
function updateProduct(productId, updateData) {
    const data = localStorage.getItem(STORAGE_KEYS.PRODUCTS);
    const products = data ? JSON.parse(data) : [];
    
    const index = products.findIndex(p => p.id == productId);
    if (index === -1) {
        return { success: false, error: 'Product not found' };
    }
    
    // Check if new product number conflicts with another product
    if (updateData.product_number !== undefined) {
        const conflict = products.find(p => 
            p.id != productId &&
            p.subcategory_id == products[index].subcategory_id &&
            p.product_number == updateData.product_number
        );
        if (conflict) {
            return { success: false, error: 'Product number already exists in this day' };
        }
    }
    
    products[index] = {
        ...products[index],
        ...updateData,
        updated_at: new Date().toISOString()
    };
    
    localStorage.setItem(STORAGE_KEYS.PRODUCTS, JSON.stringify(products));
    return { success: true };
}

// Delete product
function deleteProduct(productId) {
    const data = localStorage.getItem(STORAGE_KEYS.PRODUCTS);
    const products = data ? JSON.parse(data) : [];
    
    const filtered = products.filter(p => p.id != productId);
    localStorage.setItem(STORAGE_KEYS.PRODUCTS, JSON.stringify(filtered));
    
    return { success: true };
}

// Get max product number for subcategory
function getMaxProductNumber(subcategoryId) {
    const products = getProducts(subcategoryId);
    if (products.length === 0) return 0;
    return Math.max(...products.map(p => p.product_number));
}

// Get WhatsApp config
function getWhatsAppConfig() {
    const data = localStorage.getItem(STORAGE_KEYS.WHATSAPP);
    return data ? JSON.parse(data) : { number: '911234567890', message: 'Hello! I need help with saree availability.' };
}

// Convert image file to base64
function imageToBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result);
        reader.onerror = reject;
        reader.readAsDataURL(file);
    });
}

// Create new category
function createCategory(categoryName) {
    if (!categoryName || categoryName.trim() === '') {
        return { success: false, error: 'Category name is required' };
    }
    
    const categories = getCategories();
    
    // Check if category name already exists
    const exists = categories.find(c => c.name.toLowerCase() === categoryName.trim().toLowerCase());
    if (exists) {
        return { success: false, error: 'Category name already exists' };
    }
    
    const nextId = parseInt(localStorage.getItem(STORAGE_KEYS.NEXT_CATEGORY_ID) || '8');
    const newCategory = {
        id: nextId,
        name: categoryName.trim()
    };
    
    categories.push(newCategory);
    localStorage.setItem(STORAGE_KEYS.CATEGORIES, JSON.stringify(categories));
    localStorage.setItem(STORAGE_KEYS.NEXT_CATEGORY_ID, String(nextId + 1));
    
    // Create 30 subcategories (days) for the new category
    const subcategories = getSubcategories(null) || [];
    const allSubcategories = localStorage.getItem(STORAGE_KEYS.SUBCATEGORIES) ? JSON.parse(localStorage.getItem(STORAGE_KEYS.SUBCATEGORIES)) : [];
    
    for (let day = 1; day <= 30; day++) {
        // Find the next available subcategory ID
        const maxSubId = allSubcategories.length > 0 ? Math.max(...allSubcategories.map(s => s.id)) : 0;
        allSubcategories.push({
            id: maxSubId + day,
            category_id: nextId,
            day_number: day
        });
    }
    
    localStorage.setItem(STORAGE_KEYS.SUBCATEGORIES, JSON.stringify(allSubcategories));
    
    return { success: true, id: nextId };
}

// Delete category
function deleteCategory(categoryId) {
    const categories = getCategories();
    const category = categories.find(c => c.id == categoryId);
    
    if (!category) {
        return { success: false, error: 'Category not found' };
    }
    
    // Check if category has products
    const allProducts = localStorage.getItem(STORAGE_KEYS.PRODUCTS) ? JSON.parse(localStorage.getItem(STORAGE_KEYS.PRODUCTS)) : [];
    const subcategories = getSubcategories(categoryId);
    const subcategoryIds = subcategories.map(s => s.id);
    const hasProducts = allProducts.some(p => subcategoryIds.includes(p.subcategory_id));
    
    if (hasProducts) {
        return { success: false, error: 'Cannot delete category with existing products. Please delete all products first.' };
    }
    
    // Remove category
    const filtered = categories.filter(c => c.id != categoryId);
    localStorage.setItem(STORAGE_KEYS.CATEGORIES, JSON.stringify(filtered));
    
    // Remove subcategories
    const allSubcategories = localStorage.getItem(STORAGE_KEYS.SUBCATEGORIES) ? JSON.parse(localStorage.getItem(STORAGE_KEYS.SUBCATEGORIES)) : [];
    const filteredSubs = allSubcategories.filter(s => s.category_id != categoryId);
    localStorage.setItem(STORAGE_KEYS.SUBCATEGORIES, JSON.stringify(filteredSubs));
    
    return { success: true };
}


// Initialize on load
initializeCategories();

