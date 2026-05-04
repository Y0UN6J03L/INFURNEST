<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';

startSession();

$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':
    case 'get':
        getProducts();
        break;
    case 'single':
        getProduct();
        break;
    case 'search':
        searchProducts();
        break;
    case 'categories':
        getCategories();
        break;
    case 'featured':
        getFeaturedProducts();
        break;
    default:
        jsonResponse(['success' => false, 'message' => 'Invalid action'], 400);
}

function getProducts() {
    $page     = isset($_GET['page'])      ? (int)$_GET['page']      : 1;
    $limit    = isset($_GET['limit'])     ? (int)$_GET['limit']     : 20;
    $category = $_GET['category']         ?? 'all';
    $pet      = $_GET['pet']              ?? '';
    $sort     = $_GET['sort']             ?? 'featured';
    $minPrice = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
    $maxPrice = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 999999;
    $offset   = ($page - 1) * $limit;

    try {
        $pdo = getDB();
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Fix: prevents LIMIT/OFFSET being quoted as strings

        $sql      = "SELECT * FROM products WHERE is_active = 1";
        $countSql = "SELECT COUNT(*) as total FROM products WHERE is_active = 1";
        $params      = [];
        $conditions  = [];

        if ($category && $category !== 'all') {
            $conditions[] = "category = ?";
            $params[] = $category;
        }
        if ($pet) {
            $conditions[] = "(pet_type = ? OR pet_type = 'both')";
            $params[] = $pet;
        }
        $conditions[] = "price BETWEEN ? AND ?";
        $params[] = $minPrice;
        $params[] = $maxPrice;

        if (!empty($conditions)) {
            $where    = implode(' AND ', $conditions);
            $sql      .= " AND " . $where;
            $countSql .= " AND " . $where;
        }

        switch ($sort) {
            case 'price-asc':  $sql .= " ORDER BY price ASC";                        break;
            case 'price-desc': $sql .= " ORDER BY price DESC";                       break;
            case 'rating':     $sql .= " ORDER BY rating DESC, review_count DESC";   break;
            case 'newest':     $sql .= " ORDER BY created_at DESC";                  break;
            case 'name':       $sql .= " ORDER BY name ASC";                         break;
            default:           $sql .= " ORDER BY is_featured DESC, rating DESC";
        }

        $sql .= " LIMIT ? OFFSET ?";
        $params[] = (int)$limit;
        $params[] = (int)$offset;

        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute(array_slice($params, 0, -2));
        $total = $countStmt->fetch()['total'];

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll();

        $formatted = array_map(function($p) {
            return [
                'id'            => (int)$p['id'],
                'name'          => $p['name'],
                'slug'          => $p['slug'],
                'emoji'         => $p['emoji'],
                'category'      => $p['category'],
                'pet'           => $p['pet_type'],
                'brand'         => $p['brand'],
                'price'         => (float)$p['price'],
                'oldPrice'      => $p['old_price'] ? (float)$p['old_price'] : null,
                'rating'        => (float)$p['rating'],
                'reviews'       => (int)$p['review_count'],
                'badge'         => $p['badge'],
                'desc'          => $p['description'],
                'details'       => $p['details'],
                'weight'        => $p['weight'],
                'ageGroup'      => $p['age_group'],
                'inStock'       => (bool)$p['in_stock'],
                'stockQuantity' => (int)$p['stock_quantity'],
                'imageUrl'      => $p['image_url'],
            ];
        }, $products);

        jsonResponse([
            'success'    => true,
            'products'   => $formatted,
            'pagination' => [
                'page'  => $page,
                'limit' => $limit,
                'total' => (int)$total,
                'pages' => ceil($total / $limit)
            ]
        ]);

    } catch (PDOException $e) {
        error_log("Products Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => $e->getMessage()]);
    }
}

function getProduct() {
    $id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $slug = $_GET['slug'] ?? '';

    if (!$id && !$slug) {
        jsonResponse(['success' => false, 'message' => 'Product ID or slug required']);
    }

    try {
        $pdo = getDB();
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND is_active = 1");
            $stmt->execute([$id]);
        } else {
            $stmt = $pdo->prepare("SELECT * FROM products WHERE slug = ? AND is_active = 1");
            $stmt->execute([$slug]);
        }

        $product = $stmt->fetch();
        if (!$product) {
            jsonResponse(['success' => false, 'message' => 'Product not found'], 404);
        }

        $relatedStmt = $pdo->prepare("SELECT * FROM products WHERE id != ? AND (category = ? OR pet_type = ?) AND is_active = 1 LIMIT 4");
        $relatedStmt->execute([$product['id'], $product['category'], $product['pet_type']]);
        $related = $relatedStmt->fetchAll();

        $formatted = [
            'id'            => (int)$product['id'],
            'name'          => $product['name'],
            'slug'          => $product['slug'],
            'emoji'         => $product['emoji'],
            'category'      => $product['category'],
            'pet'           => $product['pet_type'],
            'brand'         => $product['brand'],
            'price'         => (float)$product['price'],
            'oldPrice'      => $product['old_price'] ? (float)$product['old_price'] : null,
            'rating'        => (float)$product['rating'],
            'reviews'       => (int)$product['review_count'],
            'badge'         => $product['badge'],
            'desc'          => $product['description'],
            'details'       => $product['details'],
            'weight'        => $product['weight'],
            'ageGroup'      => $product['age_group'],
            'inStock'       => (bool)$product['in_stock'],
            'stockQuantity' => (int)$product['stock_quantity'],
            'imageUrl'      => $product['image_url'],
            'related'       => array_map(function($p) {
                return [
                    'id'       => (int)$p['id'],
                    'name'     => $p['name'],
                    'emoji'    => $p['emoji'],
                    'price'    => (float)$p['price'],
                    'oldPrice' => $p['old_price'] ? (float)$p['old_price'] : null,
                    'rating'   => (float)$p['rating'],
                    'reviews'  => (int)$p['review_count'],
                ];
            }, $related)
        ];

        jsonResponse(['success' => true, 'product' => $formatted]);

    } catch (PDOException $e) {
        error_log("Product Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => $e->getMessage()]);
    }
}

function searchProducts() {
    $query = $_GET['q'] ?? $_GET['search'] ?? '';
    $page  = isset($_GET['page'])  ? (int)$_GET['page']  : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;

    if (strlen($query) < 2) {
        jsonResponse(['success' => false, 'message' => 'Search query too short']);
    }

    $offset     = ($page - 1) * $limit;
    $searchTerm = '%' . $query . '%';

    try {
        $pdo = getDB();
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        $stmt = $pdo->prepare("
            SELECT * FROM products 
            WHERE is_active = 1 AND (name LIKE ? OR description LIKE ? OR brand LIKE ?)
            ORDER BY is_featured DESC, rating DESC 
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, (int)$limit, (int)$offset]);
        $products = $stmt->fetchAll();

        $countStmt = $pdo->prepare("
            SELECT COUNT(*) as total FROM products 
            WHERE is_active = 1 AND (name LIKE ? OR description LIKE ? OR brand LIKE ?)
        ");
        $countStmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        $total = $countStmt->fetch()['total'];

        $formatted = array_map(function($p) {
            return [
                'id'       => (int)$p['id'],
                'name'     => $p['name'],
                'emoji'    => $p['emoji'],
                'category' => $p['category'],
                'pet'      => $p['pet_type'],
                'price'    => (float)$p['price'],
                'oldPrice' => $p['old_price'] ? (float)$p['old_price'] : null,
                'rating'   => (float)$p['rating'],
                'reviews'  => (int)$p['review_count'],
                'badge'    => $p['badge'],
                'desc'     => $p['description'],
                'inStock'  => (bool)$p['in_stock'],
            ];
        }, $products);

        jsonResponse([
            'success'    => true,
            'products'   => $formatted,
            'query'      => $query,
            'pagination' => [
                'page'  => $page,
                'limit' => $limit,
                'total' => (int)$total,
                'pages' => ceil($total / $limit)
            ]
        ]);

    } catch (PDOException $e) {
        error_log("Search Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => $e->getMessage()]);
    }
}

function getCategories() {
    try {
        $pdo = getDB();

        $stmt = $pdo->query("
            SELECT category, COUNT(*) as count,
                   AVG(price) as avg_price, MIN(price) as min_price, MAX(price) as max_price
            FROM products WHERE is_active = 1 
            GROUP BY category ORDER BY count DESC
        ");
        $categories = $stmt->fetchAll();

        $petStmt = $pdo->query("
            SELECT pet_type, COUNT(*) as count
            FROM products WHERE is_active = 1 
            GROUP BY pet_type
        ");
        $petTypes = $petStmt->fetchAll();

        jsonResponse(['success' => true, 'categories' => $categories, 'petTypes' => $petTypes]);

    } catch (PDOException $e) {
        error_log("Categories Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => $e->getMessage()]);
    }
}

function getFeaturedProducts() {
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 8;

    try {
        $pdo = getDB();
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        $stmt = $pdo->prepare("
            SELECT * FROM products 
            WHERE is_active = 1 AND (is_featured = 1 OR rating >= 4.5)
            ORDER BY is_featured DESC, rating DESC, review_count DESC
            LIMIT ?
        ");
        $stmt->execute([(int)$limit]);
        $products = $stmt->fetchAll();

        $formatted = array_map(function($p) {
            return [
                'id'       => (int)$p['id'],
                'name'     => $p['name'],
                'emoji'    => $p['emoji'],
                'category' => $p['category'],
                'pet'      => $p['pet_type'],
                'price'    => (float)$p['price'],
                'oldPrice' => $p['old_price'] ? (float)$p['old_price'] : null,
                'rating'   => (float)$p['rating'],
                'reviews'  => (int)$p['review_count'],
                'badge'    => $p['badge'],
                'desc'     => $p['description'],
                'inStock'  => (bool)$p['in_stock'],
            ];
        }, $products);

        jsonResponse(['success' => true, 'products' => $formatted]);

    } catch (PDOException $e) {
        error_log("Featured Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => $e->getMessage()]);
    }
}