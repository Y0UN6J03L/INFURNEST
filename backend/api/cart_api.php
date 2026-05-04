<?php
/**
 * Cart API for INFURNEST
 * Handles: Add to cart, remove from cart, update quantity, get cart
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../../backend/includes/config.php';

startSession();

// Get action from request
$action = $_POST['action'] ?? $_GET['action'] ?? 'get';

switch ($action) {
    case 'get':
    case 'list':
        getCart();
        break;
    case 'add':
        addToCart();
        break;
    case 'update':
        updateCartItem();
        break;
    case 'remove':
        removeFromCart();
        break;
    case 'clear':
        clearCart();
        break;
    case 'sync':
        syncCart();
        break;
    case 'count':
        getCartCount();
        break;
    case 'total':
        getCartTotal();
        break;
    default:
        jsonResponse(['success' => false, 'message' => 'Invalid action'], 400);
}

/**
 * Get current session ID
 */
function getSessionId() {
    if (session_status() === PHP_SESSION_NONE) {
        startSession();
    }
    return session_id();
}

/**
 * Get cart items
 */
function getCartData() {
    $sessionId = getSessionId();
    $userId = getCurrentUserId();
    
    try {
        $pdo = getDB();
        
        // Get cart items - either by session or user ID
        $sql = "SELECT c.id, c.product_id, c.quantity, c.price, p.name, p.emoji, p.category, p.pet_type, p.stock_quantity, p.in_stock
                FROM carts c
                JOIN products p ON c.product_id = p.id
                WHERE (c.session_id = ? OR c.user_id = ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$sessionId, $userId]);
        $items = $stmt->fetchAll();
        
        // Format cart items
        $formatted = array_map(function($item) {
            return [
                'id' => (int)$item['id'],
                'product_id' => (int)$item['product_id'],
                'name' => $item['name'],
                'emoji' => $item['emoji'],
                'category' => $item['category'],
                'pet' => $item['pet_type'],
                'price' => (float)$item['price'],
                'quantity' => (int)$item['quantity'],
                'total' => (float)$item['price'] * (int)$item['quantity'],
                'inStock' => (bool)$item['in_stock'],
                'stockQuantity' => (int)$item['stock_quantity'],
            ];
        }, $items);
        
        // Calculate totals
        $subtotal = array_reduce($formatted, function($sum, $item) {
            return $sum + $item['total'];
        }, 0);
        
        // Calculate shipping
        $shipping = $subtotal >= 999 ? 0 : 99;
        $total = $subtotal + $shipping;
        
        return [
            'success' => true,
            'items' => $formatted,
            'count' => array_reduce($formatted, function($sum, $item) {
                return $sum + $item['quantity'];
            }, 0),
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total
        ];
        
    } catch (PDOException $e) {
        error_log("Cart Error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error loading cart'];
    }
}

/**
 * Get cart and return JSON response
 */
function getCart() {
    jsonResponse(getCartData());
}

/**
 * Add item to cart
 */
function addToCart() {
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    if (!$productId) {
        jsonResponse(['success' => false, 'message' => 'Product ID required']);
    }
    
    if ($quantity < 1) {
        $quantity = 1;
    }
    
    $sessionId = getSessionId();
    $userId = getCurrentUserId();
    
    try {
        $pdo = getDB();
        
        // Check if product exists and is in stock
        $stmt = $pdo->prepare("SELECT id, price, in_stock, stock_quantity FROM products WHERE id = ? AND is_active = 1");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();
        
        if (!$product) {
            jsonResponse(['success' => false, 'message' => 'Product not found']);
        }
        
        if (!$product['in_stock']) {
            jsonResponse(['success' => false, 'message' => 'Product is out of stock']);
        }
        
        // Check if item already in cart
        $checkStmt = $pdo->prepare("SELECT id, quantity FROM carts WHERE product_id = ? AND (session_id = ? OR user_id = ?)");
        $checkStmt->execute([$productId, $sessionId, $userId]);
        $existing = $checkStmt->fetch();
        
        if ($existing) {
            // Update quantity
            $newQty = $existing['quantity'] + $quantity;
            $updateStmt = $pdo->prepare("UPDATE carts SET quantity = ?, updated_at = NOW() WHERE id = ?");
            $updateStmt->execute([$newQty, $existing['id']]);
        } else {
            // Insert new item
            $insertStmt = $pdo->prepare("INSERT INTO carts (session_id, user_id, product_id, quantity, price) VALUES (?, ?, ?, ?, ?)");
            $insertStmt->execute([$sessionId, $userId, $productId, $quantity, $product['price']]);
        }
        
        logActivity($userId, 'cart_add', 'Added product to cart: ' . $productId);
        
        jsonResponse([
            'success' => true,
            'message' => 'Added to cart',
            'product_id' => $productId
        ]);
        
    } catch (PDOException $e) {
        error_log("Add to Cart Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error adding to cart']);
    }
}

/**
 * Update cart item quantity
 */
function updateCartItem() {
    $cartId = isset($_POST['cart_id']) ? (int)$_POST['cart_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    if (!$cartId) {
        jsonResponse(['success' => false, 'message' => 'Cart item ID required']);
    }
    
    if ($quantity < 1) {
        $quantity = 1;
    }
    
    $sessionId = getSessionId();
    $userId = getCurrentUserId();
    
    try {
        $pdo = getDB();
        
        $stmt = $pdo->prepare("UPDATE carts SET quantity = ?, updated_at = NOW() WHERE id = ? AND (session_id = ? OR user_id = ?)");
        $stmt->execute([$quantity, $cartId, $sessionId, $userId]);
        
        if ($stmt->rowCount() === 0) {
            jsonResponse(['success' => false, 'message' => 'Cart item not found']);
        }
        
        jsonResponse([
            'success' => true,
            'message' => 'Cart updated'
        ]);
        
    } catch (PDOException $e) {
        error_log("Update Cart Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error updating cart']);
    }
}

/**
 * Remove item from cart
 */
function removeFromCart() {
    $cartId = isset($_POST['cart_id']) ? (int)$_POST['cart_id'] : 0;
    
    if (!$cartId) {
        jsonResponse(['success' => false, 'message' => 'Cart item ID required']);
    }
    
    $sessionId = getSessionId();
    $userId = getCurrentUserId();
    
    try {
        $pdo = getDB();
        
        $stmt = $pdo->prepare("DELETE FROM carts WHERE id = ? AND (session_id = ? OR user_id = ?)");
        $stmt->execute([$cartId, $sessionId, $userId]);
        
        if ($stmt->rowCount() === 0) {
            jsonResponse(['success' => false, 'message' => 'Cart item not found']);
        }
        
        jsonResponse([
            'success' => true,
            'message' => 'Item removed from cart'
        ]);
        
    } catch (PDOException $e) {
        error_log("Remove Cart Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error removing from cart']);
    }
}

/**
 * Clear cart
 */
function clearCart() {
    $sessionId = getSessionId();
    $userId = getCurrentUserId();
    
    try {
        $pdo = getDB();
        
        $stmt = $pdo->prepare("DELETE FROM carts WHERE session_id = ? OR user_id = ?");
        $stmt->execute([$sessionId, $userId]);
        
        logActivity($userId, 'cart_clear', 'Cart cleared');
        
        jsonResponse([
            'success' => true,
            'message' => 'Cart cleared'
        ]);
        
    } catch (PDOException $e) {
        error_log("Clear Cart Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error clearing cart']);
    }
}

/**
 * Sync cart from localStorage
 */
function syncCart() {
    $items = $_POST['items'] ?? $_POST['cart'] ?? [];
    
    if (!is_array($items)) {
        $items = json_decode($items, true);
    }
    
    $sessionId = getSessionId();
    $userId = getCurrentUserId();
    
    try {
        $pdo = getDB();
        
        // Clear existing cart
        $deleteStmt = $pdo->prepare("DELETE FROM carts WHERE session_id = ? OR user_id = ?");
        $deleteStmt->execute([$sessionId, $userId]);
        
        foreach ($items as $item) {
            $productId = (int)$item['id'];
            $quantity = (int)$item['qty'];
            
            if ($productId && $quantity > 0) {
                // Get product price
                $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ? AND is_active = 1");
                $stmt->execute([$productId]);
                $product = $stmt->fetch();
                
                if ($product) {
                    $insertStmt = $pdo->prepare("INSERT INTO carts (session_id, user_id, product_id, quantity, price) VALUES (?, ?, ?, ?, ?)");
                    $insertStmt->execute([$sessionId, $userId, $productId, $quantity, $product['price']]);
                }
            }
        }
        
        jsonResponse([
            'success' => true,
            'message' => 'Cart synced'
        ]);
        
    } catch (PDOException $e) {
        error_log("Sync Cart Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error syncing cart']);
    }
}

/**
 * Get cart item count
 */
function getCartCount() {
    $cart = getCart();
    jsonResponse([
        'success' => true,
        'count' => $cart['count']
    ]);
}

/**
 * Get cart total
 */
function getCartTotal() {
    $cart = getCart();
    jsonResponse([
        'success' => true,
        'subtotal' => $cart['subtotal'],
        'shipping' => $cart['shipping'],
        'total' => $cart['total']
    ]);
}
