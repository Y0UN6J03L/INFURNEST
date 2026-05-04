<?php
/**
 * Admin Products Management
 * Only accessible by admin users
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Check if user is admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$pageTitle = 'Products';
$activePage = 'products';
$base = '../';
include '../includes/admin_head.php';
?>

<?php include '../includes/admin_navbar.php'; ?>

<!-- Products Management -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 style="font-family: 'Playfair Display', serif; color: var(--brown);">Products Management</h2>
    <button class="btn-primary-custom" onclick="showAddModal()">
        <i class="fas fa-plus"></i> Add Product
    </button>
</div>

<!-- Filters -->
<div class="d-flex gap-3 mb-4 flex-wrap">
    <select class="sort-select" style="width: auto;" id="filter-category">
        <option value="">All Categories</option>
        <option value="food">Food</option>
        <option value="toys">Toys</option>
        <option value="beds">Beds</option>
        <option value="accessories">Accessories</option>
        <option value="grooming">Grooming</option>
    </select>
    <select class="sort-select" style="width: auto;" id="filter-pet">
        <option value="">All Pets</option>
        <option value="dog">Dogs</option>
        <option value="cat">Cats</option>
    </select>
    <input type="text" placeholder="Search products..." style="border: 1.5px solid var(--blush); border-radius: 9px; padding: 8px 14px; width: 250px;" id="search-products">
</div>

<!-- Products Table -->
<div class="table-admin">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Product</th>
                <th>Category</th>
                <th>Pet Type</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="products-tbody">
            <tr>
                <td colspan="9" class="text-center text-muted">Loading products...</td>
            </tr>
        </tbody>
    </table>
</div>

</div>
</div>

<!-- Add/Edit Modal -->
<div class="modal-admin" id="productModal">
    <div class="modal-content-admin">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 style="font-family: 'Playfair Display', serif; color: var(--brown);">Add/Edit Product</h4>
            <button onclick="hideModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>
        <form id="product-form">
            <input type="hidden" id="product-id">
            <div class="form-group-custom">
                <label>Product Name</label>
                <input type="text" id="product-name" placeholder="Enter product name" required>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group-custom">
                        <label>Category</label>
                        <select id="product-category" required>
                            <option value="">Select Category</option>
                            <option value="food">Food</option>
                            <option value="toys">Toys</option>
                            <option value="beds">Beds</option>
                            <option value="accessories">Accessories</option>
                            <option value="grooming">Grooming</option>
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group-custom">
                        <label>Pet Type</label>
                        <select id="product-pet">
                            <option value="dog">Dog</option>
                            <option value="cat">Cat</option>
                            <option value="both">Both</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group-custom">
                        <label>Price (₱)</label>
                        <input type="number" id="product-price" placeholder="0.00" step="0.01" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group-custom">
                        <label>Stock Quantity</label>
                        <input type="number" id="product-stock" placeholder="0">
                    </div>
                </div>
            </div>
            <div class="form-group-custom">
                <label>Description</label>
                <textarea id="product-description" rows="3" placeholder="Product description"></textarea>
            </div>

            <!-- Image Upload -->
            <div class="form-group-custom">
                <label>Product Image</label>
                <div id="image-upload-area" onclick="document.getElementById('product-image').click()" style="border: 2px dashed var(--blush); border-radius: 9px; padding: 20px; text-align: center; cursor: pointer; transition: border-color 0.2s;">
                    <img id="image-preview" src="" alt="" style="max-height: 140px; max-width: 100%; border-radius: 7px; display: none; margin-bottom: 8px;">
                    <div id="image-placeholder">
                        <i class="fas fa-image" style="font-size: 2rem; color: var(--blush); display: block; margin-bottom: 6px;"></i>
                        <span style="color: var(--text-light); font-size: 0.875rem;">Click to upload image</span><br>
                        <span style="color: var(--text-light); font-size: 0.75rem;">JPG, PNG, WEBP — max 2MB</span>
                    </div>
                </div>
                <input type="file" id="product-image" accept="image/jpeg,image/png,image/webp" style="display: none;">
                <button type="button" id="remove-image-btn" onclick="removeImage()" style="display: none; background: none; border: none; color: #E84A2A; font-size: 0.8rem; cursor: pointer; margin-top: 4px;">
                    <i class="fas fa-times"></i> Remove image
                </button>
            </div>

            <div class="d-flex gap-3">
                <button type="button" class="btn-primary-custom flex-grow-1" onclick="saveProduct()">
                    <i class="fas fa-save"></i> Save Product
                </button>
                <button type="button" class="btn-outline-custom" onclick="hideModal()" style="border-color: var(--blush); color: var(--brown);">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
<script>
// Load products from API
async function loadProducts() {
    try {
        const response = await fetch('../backend/api/admin_api.php?action=products_list');
        const data = await response.json();
        
        const tbody = document.getElementById('products-tbody');
        
        if (data.success && data.products && data.products.length > 0) {
            tbody.innerHTML = data.products.map(product => `
                <tr>
                    <td>${product.id}</td>
                    <td>
                        ${product.image
                            ? `<img src="../${product.image}" alt="${product.name}" style="width:48px; height:48px; object-fit:cover; border-radius:7px; border: 1px solid var(--blush);">`
                            : `<div style="width:48px; height:48px; border-radius:7px; background: var(--blush); display:flex; align-items:center; justify-content:center;"><i class="fas fa-image" style="color:#fff; font-size:1.1rem;"></i></div>`
                        }
                    </td>
                    <td>${product.name}</td>
                    <td><span class="badge-pet">${product.category || 'N/A'}</span></td>
                    <td>${product.pet_type === 'dog' ? '🐕' : product.pet_type === 'cat' ? '🐈' : '🐕🐈'} ${product.pet_type || 'both'}</td>
                    <td>₱${parseFloat(product.price || 0).toLocaleString()}</td>
                    <td>${product.stock || 0}</td>
                    <td><span class="status-badge active">Active</span></td>
                    <td>
                        <button class="action-btn" title="Edit" onclick='editProduct(${JSON.stringify(product)})'>
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn delete" title="Delete" onclick="deleteProduct(${product.id})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted">No products found</td></tr>';
        }
    } catch (error) {
        console.error('Error loading products:', error);
        document.getElementById('products-tbody').innerHTML = '<tr><td colspan="9" class="text-center text-muted">Error loading products</td></tr>';
    }
}

// Image preview handler
document.getElementById('product-image').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;

    if (file.size > 2 * 1024 * 1024) {
        alert('Image must be under 2MB.');
        this.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('image-preview');
        preview.src = e.target.result;
        preview.style.display = 'block';
        document.getElementById('image-placeholder').style.display = 'none';
        document.getElementById('remove-image-btn').style.display = 'inline-block';
    };
    reader.readAsDataURL(file);
});

function removeImage() {
    document.getElementById('product-image').value = '';
    document.getElementById('image-preview').src = '';
    document.getElementById('image-preview').style.display = 'none';
    document.getElementById('image-placeholder').style.display = 'block';
    document.getElementById('remove-image-btn').style.display = 'none';
}

function showAddModal() {
    document.getElementById('product-form').reset();
    document.getElementById('product-id').value = '';
    removeImage();
    document.getElementById('productModal').classList.add('show');
}

function hideModal() {
    document.getElementById('productModal').classList.remove('show');
}

function editProduct(product) {
    document.getElementById('product-id').value = product.id;
    document.getElementById('product-name').value = product.name;
    document.getElementById('product-category').value = product.category;
    document.getElementById('product-pet').value = product.pet_type;
    document.getElementById('product-price').value = product.price;
    document.getElementById('product-stock').value = product.stock || 0;
    document.getElementById('product-description').value = product.description || '';

    // Show existing image if available
    removeImage();
    if (product.image) {
        const preview = document.getElementById('image-preview');
        preview.src = '../' + product.image;
        preview.style.display = 'block';
        document.getElementById('image-placeholder').style.display = 'none';
        document.getElementById('remove-image-btn').style.display = 'inline-block';
    }

    document.getElementById('productModal').classList.add('show');
}

async function saveProduct() {
    const id = document.getElementById('product-id').value;
    const name = document.getElementById('product-name').value;
    const category = document.getElementById('product-category').value;
    const pet = document.getElementById('product-pet').value;
    const price = document.getElementById('product-price').value;
    const stock = document.getElementById('product-stock').value;
    const description = document.getElementById('product-description').value;
    const imageFile = document.getElementById('product-image').files[0];
    
    if (!name || !category || !price) {
        alert('Please fill in required fields');
        return;
    }
    
    const formData = new FormData();
    formData.append('action', id ? 'product_update' : 'product_create');
    if (id) formData.append('id', id);
    formData.append('name', name);
    formData.append('category', category);
    formData.append('pet', pet);
    formData.append('price', price);
    formData.append('stock', stock);
    formData.append('description', description);
    if (imageFile) formData.append('image', imageFile);
    
    try {
        const response = await fetch('../backend/api/admin_api.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        
        if (data.success) {
            hideModal();
            loadProducts();
            alert(data.message || 'Product saved successfully!');
        } else {
            alert(data.message || 'Error saving product');
        }
    } catch (error) {
        console.error('Error saving product:', error);
        alert('Error saving product');
    }
}

async function deleteProduct(id) {
    if (!confirm('Are you sure you want to delete this product?')) return;
    
    try {
        const formData = new FormData();
        formData.append('action', 'product_delete');
        formData.append('id', id);
        
        const response = await fetch('../backend/api/admin_api.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        
        if (data.success) {
            loadProducts();
            alert('Product deleted successfully!');
        } else {
            alert(data.message || 'Error deleting product');
        }
    } catch (error) {
        console.error('Error deleting product:', error);
        alert('Error deleting product');
    }
}

// Filter functions
document.getElementById('filter-category').addEventListener('change', loadProducts);
document.getElementById('filter-pet').addEventListener('change', loadProducts);
document.getElementById('search-products').addEventListener('input', function() {
    // Add search filtering logic if needed
});

// Load products on page load
document.addEventListener('DOMContentLoaded', loadProducts);
</script>

</body>
</html>