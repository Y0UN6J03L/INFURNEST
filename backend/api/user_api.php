<?php
/**
 * User API for INFURNEST
 * Handles: Profile, wishlist, addresses
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';

startSession();

// Get action from request
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'profile':
        getProfile();
        break;
    case 'update':
        updateProfile();
        break;
    case 'addresses':
        getAddresses();
        break;
    case 'add_address':
        addAddress();
        break;
    case 'default_address':
        setDefaultAddress();
        break;
    case 'delete_address':
        deleteAddress();
        break;
    case 'wishlist':
        getWishlist();
        break;
    case 'add_wishlist':
        addToWishlist();
        break;
    case 'remove_wishlist':
        removeFromWishlist();
        break;
    case 'check_wishlist':
        checkWishlist();
        break;
    default:
        jsonResponse(['success' => false, 'message' => 'Invalid action'], 400);
}

/**
 * Get user profile
 */
function getProfile() {
    requireLogin();
    
    $userId = getCurrentUserId();
    
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, phone, role, created_at, last_login FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        if (!$user) {
            jsonResponse(['success' => false, 'message' => 'User not found']);
        }
        
        jsonResponse([
            'success' => true,
            'user' => [
                'id' => (int)$user['id'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'name' => $user['first_name'] . ' ' . $user['last_name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'role' => $user['role'],
                'created_at' => $user['created_at'],
                'last_login' => $user['last_login']
            ]
        ]);
        
    } catch (PDOException $e) {
        error_log("Profile Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error loading profile']);
    }
}

/**
 * Update user profile
 */
function updateProfile() {
    requireLogin();
    
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    
    if (empty($firstName) || empty($lastName)) {
        jsonResponse(['success' => false, 'message' => 'First name and last name are required']);
    }
    
    $userId = getCurrentUserId();
    
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, phone = ? WHERE id = ?");
        $stmt->execute([$firstName, $lastName, $phone, $userId]);
        
        // Update session
        $_SESSION['user_name'] = $firstName . ' ' . $lastName;
        
        logActivity($userId, 'profile_update', 'Profile updated');
        
        jsonResponse([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
        
    } catch (PDOException $e) {
        error_log("Update Profile Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error updating profile']);
    }
}

/**
 * Get addresses (placeholder for future address table)
 */
function getAddresses() {
    requireLogin();
    
    jsonResponse([
        'success' => true,
        'addresses' => [],
        'message' => 'Address management coming soon'
    ]);
}

/**
 * Add address
 */
function addAddress() {
    requireLogin();
    jsonResponse(['success' => false, 'message' => 'Address management coming soon']);
}

/**
 * Set default address
 */
function setDefaultAddress() {
    requireLogin();
    jsonResponse(['success' => false, 'message' => 'Address management coming soon']);
}

/**
 * Delete address
 */
function deleteAddress() {
    requireLogin();
    jsonResponse(['success' => false, 'message' => 'Address management coming soon']);
}

/**
 * Get wishlist
 */
function getWishlist() {
    requireLogin();
    
    $userId = getCurrentUserId();
    
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("
            SELECT p.id, p.name, p.emoji, p.category, p.pet_type, p.price, p.old_price, p.rating, p.review_count, p.badge, p.in_stock
            FROM wishlists w
            JOIN products p ON w.product_id = p.id
            WHERE w.user_id = ? AND p.is_active = 1
            ORDER BY w.created_at DESC
        ");
        $stmt->execute([$userId]);
        $items = $stmt->fetchAll();
        
        $formatted = array_map(function($item) {
            return [
                'id' => (int)$item['id'],
                'name' => $item['name'],
                'emoji' => $item['emoji'],
                'category' => $item['category'],
                'pet' => $item['pet_type'],
                'price' => (float)$item['price'],
                'oldPrice' => $item['old_price'] ? (float)$item['old_price'] : null,
                'rating' => (float)$item['rating'],
                'reviews' => (int)$item['review_count'],
                'badge' => $item['badge'],
                'inStock' => (bool)$item['in_stock']
            ];
        }, $items);
        
        jsonResponse([
            'success' => true,
            'wishlist' => $formatted,
            'count' => count($formatted)
        ]);
        
    } catch (PDOException $e) {
        error_log("Wishlist Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error loading wishlist']);
    }
}

/**
 * Add to wishlist
 */
function addToWishlist() {
    requireLogin();
    
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    
    if (!$productId) {
        jsonResponse(['success' => false, 'message' => 'Product ID required']);
    }
    
    $userId = getCurrentUserId();
    
    try {
        $pdo = getDB();
        
        // Check product exists
        $stmt = $pdo->prepare("SELECT id FROM products WHERE id = ? AND is_active = 1");
        $stmt->execute([$productId]);
        if (!$stmt->fetch()) {
            jsonResponse(['success' => false, 'message' => 'Product not found']);
        }
        
        // Add to wishlist
        $insertStmt = $pdo->prepare("INSERT IGNORE INTO wishlists (user_id, product_id) VALUES (?, ?)");
        $insertStmt->execute([$userId, $productId]);
        
        logActivity($userId, 'wishlist_add', 'Added to wishlist: ' . $productId);
        
        jsonResponse([
            'success' => true,
            'message' => 'Added to wishlist'
        ]);
        
    } catch (PDOException $e) {
        error_log("Add Wishlist Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error adding to wishlist']);
    }
}

/**
 * Remove from wishlist
 */
function removeFromWishlist() {
    requireLogin();
    
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    
    if (!$productId) {
        jsonResponse(['success' => false, 'message' => 'Product ID required']);
    }
    
    $userId = getCurrentUserId();
    
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("DELETE FROM wishlists WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$userId, $productId]);
        
        jsonResponse([
            'success' => true,
            'message' => 'Removed from wishlist'
        ]);
        
    } catch (PDOException $e) {
        error_log("Remove Wishlist Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error removing from wishlist']);
    }
}

/**
 * Check if product is in wishlist
 */
function checkWishlist() {
    requireLogin();
    
    $productId = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
    
    if (!$productId) {
        jsonResponse(['success' => false, 'message' => 'Product ID required']);
    }
    
    $userId = getCurrentUserId();
    
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT id FROM wishlists WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$userId, $productId]);
        $item = $stmt->fetch();
        
        jsonResponse([
            'success' => true,
            'in_wishlist' => (bool)$item
        ]);
        
    } catch (PDOException $e) {
        error_log("Check Wishlist Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error checking wishlist']);
    }
}
