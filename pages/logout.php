<?php
/**
 * Logout Handler for INFURNEST
 * Destroys session and redirects user
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get redirect destination (default to login page)
$redirect = $_GET['redirect'] ?? 'account/login.php';
$base = '../';

// Validate redirect to prevent open redirect vulnerabilities
$allowedRedirects = ['login.php', 'index.php', '../index.php'];
if (!in_array($redirect, $allowedRedirects)) {
    $redirect = 'account/login.php';
}

// Log the logout activity before destroying session
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../backend/includes/config.php';
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("INSERT INTO activity_log (user_id, action, details, created_at) VALUES (?, 'logout', 'User logged out', NOW())");
        $stmt->execute([$_SESSION['user_id']]);
    } catch (Exception $e) {
        // Silently fail - logging is not critical
        error_log("Logout logging error: " . $e->getMessage());
    }
}

// Use destroySession if available, otherwise manual cleanup
if (function_exists('destroySession')) {
    destroySession();
} else {
    // Manual cleanup fallback
    $_SESSION = [];
    
    // Clear session cookie
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    
    session_destroy();
    session_start();
}

// Clear remember me cookie
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
}

// Redirect to login or homepage
header('Location: ' . $base . $redirect);
exit;
