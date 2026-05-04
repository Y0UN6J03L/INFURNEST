<?php
ob_start();
header('Content-Type: application/json');

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => "PHP Error [$errno]: $errstr in $errfile on line $errline"
    ]);
    exit;
});

/**
 * Orders API for INFURNEST
 * Handles: Create order, get orders, update order status, cancel order
 */

require_once __DIR__ . '/../includes/config.php';

startSession();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'create':
    case 'checkout':
        createOrder();
        break;
    case 'list':
    case 'get':
        getOrders();
        break;
    case 'single':
    case 'view':
        getOrder();
        break;
    case 'cancel':
        cancelOrder();
        break;
    case 'update_status':
        updateOrderStatus();
        break;
    case 'recent':
        getRecentOrders();
        break;
    case 'track':
        trackOrder();
        break;
    case 'dashboard_stats_user':
        getDashboardStatsUser();
        break;
    default:
        jsonResponse(['success' => false, 'message' => 'Invalid action'], 400);

}

/**
 * Generate unique order number
 */
function generateOrderNumber() {
    return 'INF-' . date('Ymd') . '-' . strtoupper(generateRandomString(6));
}

/**
 * Track order by order number (no login required)
 */
function trackOrder() {
    $orderNumber = $_GET['order_number'] ?? '';

    if (empty($orderNumber)) {
        jsonResponse(['success' => false, 'message' => 'Order number required']);
    }

    try {
        $pdo = getDB();

        $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_number = ?");
        $stmt->execute([$orderNumber]);
        $order = $stmt->fetch();

        if (!$order) {
            jsonResponse(['success' => false, 'message' => 'Order not found'], 404);
        }

        $itemsStmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $itemsStmt->execute([$order['id']]);
        $items = $itemsStmt->fetchAll();

        $formatted = [
            'id'             => (int)$order['id'],
            'order_number'   => $order['order_number'],
            'subtotal'       => (float)$order['subtotal'],
            'shipping_fee'   => (float)$order['shipping_fee'],
            'total'          => (float)$order['total'],
            'status'         => $order['status'],
            'payment_method' => $order['payment_method'],
            'created_at'     => $order['created_at'],
            'items'          => array_map(function($item) {
                return [
                    'product_id'   => (int)$item['product_id'],
                    'product_name' => $item['product_name'],
                    'price'        => (float)$item['price'],
                    'quantity'     => (int)$item['quantity'],
                    'total'        => (float)$item['total']
                ];
            }, $items)
        ];

        jsonResponse(['success' => true, 'order' => $formatted]);

    } catch (PDOException $e) {
        error_log("Track Order Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error loading order']);
    }
}

/**
 * Create new order (checkout)
 */
function createOrder() {
    try {
        if (!isLoggedIn()) {
            jsonResponse(['success' => false, 'message' => 'Please login to checkout', 'require_login' => true], 401);
        }

        $shippingName    = trim($_POST['shipping_name']    ?? '');
        $shippingPhone   = trim($_POST['shipping_phone']   ?? '');
        $shippingAddress = trim($_POST['shipping_address'] ?? '');
        $shippingCity    = trim($_POST['shipping_city']    ?? '');
        $shippingZip     = trim($_POST['shipping_zip']     ?? '');
        $paymentMethod   = $_POST['payment_method']        ?? 'cod';
        $notes           = trim($_POST['notes']            ?? '');

        if (empty($shippingName) || empty($shippingPhone) || empty($shippingAddress) || empty($shippingCity)) {
            jsonResponse(['success' => false, 'message' => 'Please fill in all shipping details'], 400);
        }

        $userId    = getCurrentUserId();
        $sessionId = session_id();

        // Get cart from POST (localStorage sync) or fall back to DB cart
        $cartData  = $_POST['cart_data'] ?? null;
        $cartItems = $cartData ? (json_decode($cartData, true) ?: []) : [];

        $pdo = getDB();

        // If no localStorage cart, load from DB
        if (empty($cartItems)) {
            $cartStmt = $pdo->prepare("
                SELECT c.product_id, c.quantity, c.price, p.name, p.stock_quantity
                FROM carts c
                JOIN products p ON c.product_id = p.id
                WHERE (c.session_id = ? OR c.user_id = ?)
            ");
            $cartStmt->execute([$sessionId, $userId]);
            $dbCart = $cartStmt->fetchAll();

            foreach ($dbCart as $row) {
                $cartItems[] = [
                    'product_id' => $row['product_id'],
                    'name'       => $row['name'],
                    'price'      => $row['price'],
                    'quantity'   => $row['quantity'],
                ];
            }
        }

        if (empty($cartItems)) {
            jsonResponse(['success' => false, 'message' => 'Cart is empty'], 400);
        }

        // Build items array and calculate subtotal
        $subtotal = 0;
        $items    = [];

        foreach ($cartItems as $item) {
            $itemPrice = (float)($item['price']                          ?? 0);
            $itemQty   = (int)($item['qty'] ?? $item['quantity']         ?? 1);
            $itemName  = $item['name']                                   ?? 'Unknown Product';
            $itemId    = $item['product_id'] ?? $item['id']              ?? 0;

            $itemTotal  = $itemPrice * $itemQty;
            $subtotal  += $itemTotal;

            $items[] = [
                'product_id'   => $itemId,
                'product_name' => $itemName,
                'price'        => $itemPrice,
                'quantity'     => $itemQty,
                'total'        => $itemTotal
            ];
        }

        $shippingFee = $subtotal >= 999 ? 0 : 99;
        $total       = $subtotal + $shippingFee;
        $orderNumber = generateOrderNumber();

        $pdo->beginTransaction();

        $orderStmt = $pdo->prepare("
            INSERT INTO orders (
                order_number, user_id, subtotal, shipping_fee, total,
                status, shipping_name, shipping_phone, shipping_address,
                shipping_city, shipping_zip, payment_method, notes
            ) VALUES (?, ?, ?, ?, ?, 'pending', ?, ?, ?, ?, ?, ?, ?)
        ");
        $orderStmt->execute([
            $orderNumber, $userId, $subtotal, $shippingFee, $total,
            $shippingName, $shippingPhone, $shippingAddress,
            $shippingCity, $shippingZip, $paymentMethod, $notes
        ]);

        $orderId = $pdo->lastInsertId();

        foreach ($items as $item) {
            $itemStmt = $pdo->prepare("
                INSERT INTO order_items (order_id, product_id, product_name, price, quantity, total)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $itemStmt->execute([
                $orderId,
                $item['product_id'],
                $item['product_name'],
                $item['price'],
                $item['quantity'],
                $item['total']
            ]);
        }

        // Clear DB cart
        $clearStmt = $pdo->prepare("DELETE FROM carts WHERE session_id = ? OR user_id = ?");
        $clearStmt->execute([$sessionId, $userId]);

        $pdo->commit();

        logActivity($userId, 'order_create', 'Order created: ' . $orderNumber);

        jsonResponse([
            'success' => true,
            'message' => 'Order placed successfully!',
            'order'   => [
                'id'          => (int)$orderId,
                'order_number'=> $orderNumber,
                'subtotal'    => $subtotal,
                'shipping_fee'=> $shippingFee,
                'total'       => $total,
                'status'      => 'pending',
                'items_count' => count($items)
            ]
        ]);

    } catch (PDOException $e) {
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Order Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error placing order. Please try again.']);
    }
}

/**
 * Get user's orders
 */
function getOrders() {
    if (!isLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Please login first'], 401);
    }

    $page   = isset($_GET['page'])  ? (int)$_GET['page']  : 1;
    $limit  = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $status = $_GET['status'] ?? '';
    $offset = ($page - 1) * $limit;
    $userId = getCurrentUserId();

    try {
        $pdo = getDB();

        $sql      = "SELECT * FROM orders WHERE user_id = ?";
        $countSql = "SELECT COUNT(*) as total FROM orders WHERE user_id = ?";
        $params   = [$userId];

        if ($status) {
            $sql      .= " AND status = ?";
            $countSql .= " AND status = ?";
            $params[]  = $status;
        }

        $sql     .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute(array_slice($params, 0, -2));
        $total = $countStmt->fetch()['total'];

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $orders = $stmt->fetchAll();

        $formatted = array_map(function($o) {
            return [
                'id'           => (int)$o['id'],
                'order_number' => $o['order_number'],
                'total'        => (float)$o['total'],
                'status'       => $o['status'],
                'item_count'   => 0,
                'created_at'   => $o['created_at']
            ];
        }, $orders);

        jsonResponse([
            'success'    => true,
            'orders'     => $formatted,
            'pagination' => [
                'page'  => $page,
                'limit' => $limit,
                'total' => (int)$total,
                'pages' => ceil($total / $limit)
            ]
        ]);

    } catch (PDOException $e) {
        error_log("Get Orders Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error loading orders']);
    }
}

/**
 * Get single order details
 */
function getOrder() {
    if (!isLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Please login first'], 401);
    }

    $orderId     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $orderNumber = $_GET['order_number'] ?? '';

    if (!$orderId && !$orderNumber) {
        jsonResponse(['success' => false, 'message' => 'Order ID or number required']);
    }

    $userId = getCurrentUserId();

    try {
        $pdo = getDB();

        if ($orderId) {
            $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
            $stmt->execute([$orderId, $userId]);
        } else {
            $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_number = ? AND user_id = ?");
            $stmt->execute([$orderNumber, $userId]);
        }

        $order = $stmt->fetch();

        if (!$order) {
            jsonResponse(['success' => false, 'message' => 'Order not found'], 404);
        }

        $itemsStmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $itemsStmt->execute([$order['id']]);
        $items = $itemsStmt->fetchAll();

        $formatted = [
            'id'              => (int)$order['id'],
            'order_number'    => $order['order_number'],
            'subtotal'        => (float)$order['subtotal'],
            'shipping_fee'    => (float)$order['shipping_fee'],
            'total'           => (float)$order['total'],
            'status'          => $order['status'],
            'payment_method'  => $order['payment_method'],
            'payment_status'  => $order['payment_status'],
            'shipping_name'   => $order['shipping_name'],
            'shipping_phone'  => $order['shipping_phone'],
            'shipping_address'=> $order['shipping_address'],
            'shipping_city'   => $order['shipping_city'],
            'shipping_zip'    => $order['shipping_zip'],
            'notes'           => $order['notes'],
            'created_at'      => $order['created_at'],
            'items'           => array_map(function($item) {
                return [
                    'product_id'   => (int)$item['product_id'],
                    'product_name' => $item['product_name'],
                    'price'        => (float)$item['price'],
                    'quantity'     => (int)$item['quantity'],
                    'total'        => (float)$item['total']
                ];
            }, $items)
        ];

        jsonResponse(['success' => true, 'order' => $formatted]);

    } catch (PDOException $e) {
        error_log("Get Order Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error loading order']);
    }
}

/**
 * Cancel order
 */
function cancelOrder() {
    if (!isLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Please login first'], 401);
    }

    $orderId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $userId  = getCurrentUserId();

    if (!$orderId) {
        jsonResponse(['success' => false, 'message' => 'Order ID required']);
    }

    try {
        $pdo = getDB();

        $stmt = $pdo->prepare("SELECT id, status FROM orders WHERE id = ? AND user_id = ?");
        $stmt->execute([$orderId, $userId]);
        $order = $stmt->fetch();

        if (!$order) {
            jsonResponse(['success' => false, 'message' => 'Order not found']);
        }

        if (!in_array($order['status'], ['pending', 'processing'])) {
            jsonResponse(['success' => false, 'message' => 'Order cannot be cancelled at this stage']);
        }

        $updateStmt = $pdo->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
        $updateStmt->execute([$orderId]);

        logActivity($userId, 'order_cancel', 'Order cancelled: ' . $orderId);

        jsonResponse(['success' => true, 'message' => 'Order cancelled successfully']);

    } catch (PDOException $e) {
        error_log("Cancel Order Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error cancelling order']);
    }
}

/**
 * Update order status (admin only)
 */
function updateOrderStatus() {
    if (!isLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Please login first'], 401);
    }

    $user = getCurrentUser();
    if ($user['role'] !== 'admin') {
        jsonResponse(['success' => false, 'message' => 'Unauthorized'], 403);
    }

    $orderId       = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $status        = $_POST['status'] ?? '';
    $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

    if (!$orderId || !in_array($status, $validStatuses)) {
        jsonResponse(['success' => false, 'message' => 'Invalid order ID or status']);
    }

    try {
        $pdo = getDB();

        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $orderId]);

        logActivity($user['id'], 'order_status_update', 'Order status updated to: ' . $status);

        jsonResponse(['success' => true, 'message' => 'Order status updated']);

    } catch (PDOException $e) {
        error_log("Update Order Status Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error updating order status']);
    }
}

/**
 * Get user dashboard stats
 */
function getDashboardStatsUser() {
    if (!isLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Please login first'], 401);
    }

    $userId = getCurrentUserId();

    try {
        $pdo = getDB();

        // Total orders and spent
        $totalStmt = $pdo->prepare("SELECT COUNT(*) as total_orders, SUM(total) as total_spent FROM orders WHERE user_id = ?");
        $totalStmt->execute([$userId]);
        $totals = $totalStmt->fetch();
        
        $totalOrders = (int)$totals['total_orders'];
        $totalSpent = (float)($totals['total_spent'] ?? 0);
        $avgOrderValue = $totalOrders > 0 ? round($totalSpent / $totalOrders, 2) : 0;

        // Status counts
        $statusStmt = $pdo->prepare("
            SELECT 
                SUM(CASE WHEN status IN ('pending', 'processing') THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN status IN ('delivered', 'shipped') THEN 1 ELSE 0 END) as completed_count
            FROM orders WHERE user_id = ?
        ");
        $statusStmt->execute([$userId]);
        $statusCounts = $statusStmt->fetch();
        
        jsonResponse([
            'success' => true,
            'stats' => [
                'total_orders' => $totalOrders,
                'total_spent' => $totalSpent,
                'avg_order_value' => $avgOrderValue,
                'pending_count' => (int)$statusCounts['pending_count'],
                'completed_count' => (int)$statusCounts['completed_count']
            ]
        ]);

    } catch (PDOException $e) {
        error_log("Dashboard Stats Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error loading stats']);
    }
}

/**
 * Get recent orders
 */
function getRecentOrders() {
    if (!isLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Please login first'], 401);
    }

    $userId = getCurrentUserId();
    $limit  = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;

    try {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT * FROM orders
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$userId, $limit]);
        $orders = $stmt->fetchAll();

        $formatted = array_map(function($o) {
            return [
                'id'           => (int)$o['id'],
                'order_number' => $o['order_number'],
                'total'        => (float)$o['total'],
                'status'       => $o['status'],
                'created_at'   => $o['created_at']
            ];
        }, $orders);

        jsonResponse(['success' => true, 'orders' => $formatted]);

    } catch (PDOException $e) {
        error_log("Recent Orders Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Error loading recent orders']);
    }
}
?>