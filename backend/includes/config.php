<?php
/**
 * Config & Helper Functions for INFURNEST Backend
 */


// require_once __DIR__ . '/db.php'; // Not needed for PDO

date_default_timezone_set('Asia/Manila');
// Convert mysqli to PDO for API
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
    $pdo = new PDO("mysql:host=localhost;dbname=infurnest;charset=utf8mb4", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return $pdo;
}

// Password hash/verify
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Secure random string
function generateRandomString($length = 64) {
    return bin2hex(random_bytes($length / 2));
}

// Start secure session
function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.cookie_httponly', 1);
        ini_set('session.cookie_secure', 1); // HTTPS only
        ini_set('session.use_strict_mode', 1);
        session_start();
    }
}

// JSON response helper
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

// Check if logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Get current user ID
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Get current user data
function getCurrentUser() {
    if (!isLoggedIn()) return null;
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

// Require login
function requireLogin() {
    if (!isLoggedIn()) {
        jsonResponse(['success' => false, 'message' => 'Please login first'], 401);
    }
}

// Log activity
function logActivity($userId, $action, $details) {
    $pdo = getDB();
    $stmt = $pdo->prepare("INSERT INTO activity_log (user_id, action, details, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $userId,
        $action,
        $details,
        $_SERVER['REMOTE_ADDR'] ?? null,
        substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500)
    ]);
}

// CSRF token (basic)
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

define('REMEMBER_ME_LIFETIME', 30 * 24 * 60 * 60); // 30 days
?>

