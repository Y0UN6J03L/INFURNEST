<?php
/**
 * Admin API for INFURNEST
 * Handles: Product CRUD, Order Management, User Management
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/includes/config.php';

startSession();

// Check if user is admin (for demo, allow all logged in users)
if (!isset($_SESSION['user_id'])) {
    jsonResponse(['success' => false, 'message' => 'Please login first'], 401);
}

// Get action from request
$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    // PRODUCTS
    case 'products_list':
        getProducts();
        break;
    case 'product_create':
        createProduct();
        break;
    case 'product_update':
        updateProduct();
        break;
    case 'product_delete':
        deleteProduct();
        break;
    
    // ORDERS
    case 'orders_list':
        getOrders();
        break;
    case 'order_update':
        updateOrder();
        break;
    
    // USERS
    case 'users_list':
        getUsers();
        break;
    case 'user_create':
        createUser();
        break;
    case 'user_update':
        updateUser();
        break;
    case 'user_delete':
        deleteUser();
        break;
    
    // DASHBOARD STATS
    case 'dashboard_stats':
        getDashboardStats();
        break;
    
    default:
        jsonResponse(['success' => false, 'message' => 'Invalid action'], 400);
}

/**
 * Handle image upload — returns relative path or null
 */
function handleImageUpload() {
    if (empty($_FILES['image']['name'])) return null;

    $file    = $_FILES['image'];
    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
    $maxSize = 2 * 1024 * 1024; // 2MB

    if (!in_array($file['type'], $allowed)) {
        jsonResponse(['success' => false, 'message' => 'Invalid image type. Use JPG, PNG, or WEBP.']);
    }
    if ($file['size'] > $maxSize) {
        jsonResponse(['success' => false, 'message' => 'Image must be under 2MB.']);
    }

    $uploadDir = __DIR__ . '/../../uploads/products/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('product_', true) . '.' . strtolower($ext);
    $dest     = $uploadDir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        jsonResponse(['success' => false, 'message' => 'Failed to save image.']);
    }

    return 'uploads/products/' . $filename;
}

/**
 * Get all products
 */
function getProducts() {
    try {
        $pdo  = getDB();
        $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
        $products = $stmt->fetchAll();
        jsonResponse(['success' => true, 'products' => $products]);
    } catch (PDOException $e) {
        error_log("Products Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error loading products']);
    }
}

/**
 * Create new product
 */
function createProduct() {
    $name        = $_POST['name'] ?? '';
    $category    = $_POST['category'] ?? '';
    $pet         = $_POST['pet'] ?? 'both';
    $price       = $_POST['price'] ?? 0;
    $stock       = $_POST['stock'] ?? 0;
    $description = $_POST['description'] ?? '';

    if (empty($name) || empty($category)) {
        jsonResponse(['success' => false, 'message' => 'Name and category are required']);
    }

    $imagePath = handleImageUpload();

    try {
        $pdo  = getDB();
        $slug = strtolower(str_replace(' ', '-', preg_replace('/[^a-zA-Z0-9 ]/', '', $name)));

        $stmt = $pdo->prepare("INSERT INTO products (name, slug, category, pet_type, price, stock, description, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $slug, $category, $pet, $price, $stock, $description, $imagePath]);

        jsonResponse([
            'success'    => true,
            'message'    => 'Product created successfully',
            'product_id' => $pdo->lastInsertId()
        ]);
    } catch (PDOException $e) {
        error_log("Create Product Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error creating product']);
    }
}

/**
 * Update product
 */
function updateProduct() {
    $id          = $_POST['id'] ?? 0;
    $name        = $_POST['name'] ?? '';
    $category    = $_POST['category'] ?? '';
    $pet         = $_POST['pet'] ?? 'both';
    $price       = $_POST['price'] ?? 0;
    $stock       = $_POST['stock'] ?? 0;
    $description = $_POST['description'] ?? '';

    if (!$id) {
        jsonResponse(['success' => false, 'message' => 'Product ID is required']);
    }

    try {
        $pdo       = getDB();
        $imagePath = handleImageUpload();

        if ($imagePath) {
            // Delete old image file
            $old = $pdo->prepare("SELECT image FROM products WHERE id = ?");
            $old->execute([$id]);
            $oldImage = $old->fetchColumn();
            if ($oldImage) {
                $oldFile = __DIR__ . '/../../' . $oldImage;
                if (file_exists($oldFile)) unlink($oldFile);
            }

            $stmt = $pdo->prepare("UPDATE products SET name=?, category=?, pet_type=?, price=?, stock=?, description=?, image=? WHERE id=?");
            $stmt->execute([$name, $category, $pet, $price, $stock, $description, $imagePath, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE products SET name=?, category=?, pet_type=?, price=?, stock=?, description=? WHERE id=?");
            $stmt->execute([$name, $category, $pet, $price, $stock, $description, $id]);
        }

        jsonResponse(['success' => true, 'message' => 'Product updated successfully']);
    } catch (PDOException $e) {
        error_log("Update Product Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error updating product']);
    }
}

/**
 * Delete product
 */
function deleteProduct() {
    $id = $_GET['id'] ?? $_POST['id'] ?? 0;

    if (!$id) {
        jsonResponse(['success' => false, 'message' => 'Product ID is required']);
    }

    try {
        $pdo = getDB();

        $old = $pdo->prepare("SELECT image FROM products WHERE id = ?");
        $old->execute([$id]);
        $oldImage = $old->fetchColumn();
        if ($oldImage) {
            $oldFile = __DIR__ . '/../../' . $oldImage;
            if (file_exists($oldFile)) unlink($oldFile);
        }

        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);

        jsonResponse(['success' => true, 'message' => 'Product deleted successfully']);
    } catch (PDOException $e) {
        error_log("Delete Product Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error deleting product']);
    }
}

/**
 * Get all orders
 */
function getOrders() {
    try {
        $pdo  = getDB();
        $stmt = $pdo->query("SELECT * FROM orders ORDER BY id DESC");
        $orders = $stmt->fetchAll();
        jsonResponse(['success' => true, 'orders' => $orders]);
    } catch (PDOException $e) {
        error_log("Orders Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error loading orders']);
    }
}

/**
 * Update order status
 */
function updateOrder() {
    $id     = $_POST['id'] ?? 0;
    $status = $_POST['status'] ?? '';

    if (!$id || empty($status)) {
        jsonResponse(['success' => false, 'message' => 'Order ID and status are required']);
    }

    try {
        $pdo  = getDB();
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        jsonResponse(['success' => true, 'message' => 'Order status updated successfully']);
    } catch (PDOException $e) {
        error_log("Update Order Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error updating order']);
    }
}

/**
 * Get all users
 */
function getUsers() {
    try {
        $pdo  = getDB();
        $stmt = $pdo->query("SELECT id, first_name, last_name, email, phone, role, is_verified, created_at FROM users ORDER BY id DESC");
        $users = $stmt->fetchAll();
        jsonResponse(['success' => true, 'users' => $users]);
    } catch (PDOException $e) {
        error_log("Users Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error loading users']);
    }
}

/**
 * Create new user
 */
function createUser() {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');
    $password   = $_POST['password'] ?? '';
    $role       = $_POST['role'] ?? 'customer';

    if (!$first_name || !$email || !$password) {
        jsonResponse(['success' => false, 'message' => 'Required fields missing']);
    }

    try {
        $pdo   = getDB();
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            jsonResponse(['success' => false, 'message' => 'Email already in use']);
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt   = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, password, role, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$first_name, $last_name, $email, $phone, $hashed, $role]);

        jsonResponse(['success' => true, 'message' => 'User created successfully']);
    } catch (PDOException $e) {
        error_log("Create User Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error creating user']);
    }
}

/**
 * Update user
 */
function updateUser() {
    $id   = $_POST['id'] ?? 0;
    $role = $_POST['role'] ?? 'customer';

    if (!$id) {
        jsonResponse(['success' => false, 'message' => 'User ID is required']);
    }

    try {
        $pdo  = getDB();
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$role, $id]);
        jsonResponse(['success' => true, 'message' => 'User updated successfully']);
    } catch (PDOException $e) {
        error_log("Update User Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error updating user']);
    }
}

/**
 * Delete user
 */
function deleteUser() {
    $id = $_GET['id'] ?? $_POST['id'] ?? 0;

    if (!$id) {
        jsonResponse(['success' => false, 'message' => 'User ID is required']);
    }

    try {
        $pdo  = getDB();
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        jsonResponse(['success' => true, 'message' => 'User deleted successfully']);
    } catch (PDOException $e) {
        error_log("Delete User Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error deleting user']);
    }
}

/**
 * Get dashboard statistics
 */
function getDashboardStats() {
    try {
        $pdo = getDB();

        $productsCount = $pdo->query("SELECT COUNT(*) as count FROM products")->fetch()['count'];
        $ordersCount   = $pdo->query("SELECT COUNT(*) as count FROM orders")->fetch()['count'];
        $usersCount    = $pdo->query("SELECT COUNT(*) as count FROM users")->fetch()['count'];

        $revenueStmt = $pdo->query("SELECT SUM(total) as total FROM orders WHERE payment_status = 'paid'");
        $revenue     = $revenueStmt->fetch()['total'] ?? 0;

        jsonResponse([
            'success' => true,
            'stats'   => [
                'products' => $productsCount,
                'orders'   => $ordersCount,
                'users'    => $usersCount,
                'revenue'  => $revenue
            ]
        ]);
    } catch (PDOException $e) {
        error_log("Dashboard Stats Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error loading stats']);
    }
}