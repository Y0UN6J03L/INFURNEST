<?php
/**
 * Authentication API for INFURNEST
 * Handles: Login, Register, Forgot Password, Verification
 */

ob_start(); // buffer any accidental output from included files
date_default_timezone_set('Asia/Manila');
header('Content-Type: application/json');

require_once __DIR__ . '/../../backend/includes/config.php';
require_once __DIR__ . '/send_verification_email.php';
require_once __DIR__ . '/auth_api_otp_functions.php';

startSession();

// Get action from request
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        handleLogin();
        break;
    case 'register':
        handleRegister();
        break;
    case 'forgot':
        handleForgotPassword();
        break;
    case 'reset':
        handleResetPassword();
        break;
    case 'verify':
        handleVerification();
        break;
    case 'logout':
        handleLogout();
        break;
    case 'check':
        checkAuth();
        break;
    case 'update_profile':
        handleUpdateProfile();
        break;
    case 'verify_otp':
        handleVerifyOTP();
        break;
    case 'resend_otp':
        handleResendOTP();
        break;
    default:
        jsonResponse(['success' => false, 'message' => 'Invalid action'], 400);
}

/**
 * Handle user login
 */
function handleLogin() {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = $_POST['remember'] ?? false;

    if (empty($email) || empty($password)) {
        jsonResponse(['success' => false, 'message' => 'Email and password are required']);
    }

    try {
        $pdo  = getDB();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            jsonResponse(['success' => false, 'message' => 'Invalid email or password']);
        }

        if (!verifyPassword($password, $user['password_hash'])) {
            jsonResponse(['success' => false, 'message' => 'Invalid email or password']);
        }

        if ($user['is_verified']) {
            // Set full session
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name']  = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_role']  = $user['role'];

            // Update last login
            $update = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $update->execute([$user['id']]);

            // Handle remember me
            if ($remember) {
                $token = generateRandomString(64);
                setcookie('remember_token', $token, time() + REMEMBER_ME_LIFETIME, '/');
            }

            logActivity($user['id'], 'login', 'User logged in');

            jsonResponse([
                'success' => true,
                'message' => 'Login successful',
                'name'    => $user['first_name'] . ' ' . $user['last_name'],
                'email'   => $user['email'],
                'role'    => $user['role']
            ]);
        } else {
            // Generate OTP for unverified user
            $otp     = sprintf("%06d", rand(0, 999999));
            $expires = date('Y-m-d H:i:s', time() + 300); // 5 min

            $update = $pdo->prepare("UPDATE users SET otp_code = ?, otp_expires = ? WHERE id = ?");
            $update->execute([$otp, $expires, $user['id']]);

            $emailSent = sendVerificationEmail($user['email'], $otp, $user['first_name'] . ' ' . $user['last_name']);

            $_SESSION['pending_verify_email'] = $user['email'];
            $_SESSION['pending_user_id']      = $user['id'];

            logActivity($user['id'], 'otp_sent', 'OTP sent for verification');

            jsonResponse([
                'success'          => true,
                'needs_verification' => true,
                'message'          => $emailSent
                    ? 'OTP sent to your email! Please verify.'
                    : 'Please verify your email (email send failed - check SMTP).',
                'redirect_url'     => 'account/verification-otp.php?email=' . urlencode($user['email'])
            ]);
        }
    } catch (PDOException $e) {
        error_log("Login Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'An error occurred. Please try again.']);
    }
}

/**
 * Handle user registration
 * ✅ CHANGED: inserts user as unverified (is_verified = 0),
 *    generates OTP, emails it, and returns redirect_url to OTP page.
 */
function handleRegister() {
    $firstName       = trim($_POST['firstName'] ?? '');
    $lastName        = trim($_POST['lastName'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $phone           = trim($_POST['phone'] ?? '');
    $password        = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm'] ?? $password;

    // Validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        jsonResponse(['success' => false, 'message' => 'All required fields must be filled']);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        jsonResponse(['success' => false, 'message' => 'Please enter a valid email address']);
    }

    if (strlen($password) < 8) {
        jsonResponse(['success' => false, 'message' => 'Password must be at least 8 characters']);
    }

    if ($password !== $confirmPassword) {
        jsonResponse(['success' => false, 'message' => 'Passwords do not match']);
    }

    try {
        $pdo = getDB();

        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            jsonResponse(['success' => false, 'message' => 'Email already registered']);
        }

        $passwordHash = hashPassword($password);

        // ✅ Insert user as UNVERIFIED (is_verified = 0)
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, password_hash, is_verified) VALUES (?, ?, ?, ?, ?, 0)");
        $stmt->execute([$firstName, $lastName, $email, $phone, $passwordHash]);

        $userId = $pdo->lastInsertId();

        // ✅ Generate OTP and save to DB
        $otp     = sprintf("%06d", rand(0, 999999));
        $expires = date('Y-m-d H:i:s', time() + 300); // 5 minutes
        $stmt    = $pdo->prepare("UPDATE users SET otp_code = ?, otp_expires = ? WHERE id = ?");
        $stmt->execute([$otp, $expires, $userId]);

        // ✅ Send OTP email
        $emailSent = sendVerificationEmail($email, $otp, $firstName . ' ' . $lastName);

        logActivity($userId, 'register', 'New user registered');

        jsonResponse([
            'success'      => true,
            'message'      => $emailSent
                ? 'Account created! Check your email for the 6-digit OTP.'
                : 'Account created but email failed. Contact support.',
            'redirect_url' => 'account/verification-otp.php?email=' . urlencode($email)
        ]);

    } catch (PDOException $e) {
        error_log("Register Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'An error occurred. Please try again.']);
    }
}

/**
 * Handle forgot password request
 */
function handleForgotPassword() {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        jsonResponse(['success' => false, 'message' => 'Email is required']);
    }

    try {
        $pdo  = getDB();
        $stmt = $pdo->prepare("SELECT id, first_name FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            // Don't reveal if email exists (security best practice)
            jsonResponse(['success' => true, 'message' => 'If the email exists, a reset link has been sent']);
            return;
        }

        $resetToken = generateRandomString(64);
        $expires    = date('Y-m-d H:i:s', time() + 3600); // 1 hour

        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?");
        $stmt->execute([$resetToken, $expires, $user['id']]);

        logActivity($user['id'], 'forgot_password', 'Password reset requested');

        require_once __DIR__ . '/send_reset_email.php';
"http://localhost/INFURNEST/account/reset.php?token=" . $resetToken;
        $emailSent = sendResetEmail($email, $resetLink);

        if ($emailSent) {
            jsonResponse([
                'success'    => true,
                'message'    => 'Reset link sent! Check your email (including spam folder).',
                'reset_link' => $resetLink // remove in production
            ]);
        } else {
            jsonResponse([
                'success'    => false,
                'message'    => 'Failed to send email. Please try again.',
                'reset_link' => $resetLink // remove in production
            ]);
        }

    } catch (PDOException $e) {
        error_log("Forgot Password Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'An error occurred. Please try again.']);
    }
}

/**
 * Handle password reset with token
 */
function handleResetPassword() {
    $token           = $_POST['token'] ?? $_GET['token'] ?? '';
    $password        = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? $password;

    if (empty($token)) {
        jsonResponse(['success' => false, 'message' => 'Invalid or expired reset token']);
    }

    if (strlen($password) < 8) {
        jsonResponse(['success' => false, 'message' => 'Password must be at least 8 characters']);
    }

    if ($password !== $confirmPassword) {
        jsonResponse(['success' => false, 'message' => 'Passwords do not match']);
    }

    try {
        $pdo  = getDB();
        $stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if (!$user) {
            jsonResponse(['success' => false, 'message' => 'Invalid or expired reset token']);
        }

        $passwordHash = hashPassword($password);
        $stmt = $pdo->prepare("UPDATE users SET password_hash = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
        $stmt->execute([$passwordHash, $user['id']]);

        logActivity($user['id'], 'password_reset', 'Password was reset');

        jsonResponse(['success' => true, 'message' => 'Password reset successfully! Please login with your new password.']);

    } catch (PDOException $e) {
        error_log("Reset Password Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'An error occurred. Please try again.']);
    }
}

/**
 * Handle email verification via token (legacy)
 */
function handleVerification() {
    $token = $_GET['token'] ?? $_POST['token'] ?? '';

    if (empty($token)) {
        jsonResponse(['success' => false, 'message' => 'Invalid verification token']);
    }

    try {
        $pdo  = getDB();
        $stmt = $pdo->prepare("SELECT id FROM users WHERE verification_token = ?");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if (!$user) {
            jsonResponse(['success' => false, 'message' => 'Invalid verification token']);
        }

        $stmt = $pdo->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?");
        $stmt->execute([$user['id']]);

        logActivity($user['id'], 'verification', 'Email verified');

        jsonResponse(['success' => true, 'message' => 'Email verified successfully! You can now login.']);

    } catch (PDOException $e) {
        error_log("Verification Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'An error occurred. Please try again.']);
    }
}

/**
 * Handle logout
 */
function handleLogout() {
    $userId = getCurrentUserId();

    if ($userId) {
        logActivity($userId, 'logout', 'User logged out');
    }

    $_SESSION = [];
    session_destroy();
    setcookie('remember_token', '', time() - 3600, '/');

    jsonResponse(['success' => true, 'message' => 'Logged out successfully']);
}

/**
 * Check if user is authenticated
 */
function checkAuth() {
    if (isLoggedIn()) {
        $user = getCurrentUser();
        jsonResponse(['success' => true, 'authenticated' => true, 'user' => $user]);
    } else {
        jsonResponse(['success' => true, 'authenticated' => false]);
    }
}

/**
 * Update user profile
 */
function handleUpdateProfile() {
    requireLogin();

    $firstName = trim($_POST['firstName'] ?? '');
    $lastName  = trim($_POST['lastName'] ?? '');
    $phone     = trim($_POST['phone'] ?? '');

    if (empty($firstName) || empty($lastName)) {
        jsonResponse(['success' => false, 'message' => 'First name and last name are required']);
    }

    try {
        $pdo    = getDB();
        $userId = getCurrentUserId();

        $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, phone = ? WHERE id = ?");
        $stmt->execute([$firstName, $lastName, $phone, $userId]);

        $_SESSION['user_name'] = $firstName . ' ' . $lastName;

        logActivity($userId, 'profile_update', 'Profile updated');

        jsonResponse(['success' => true, 'message' => 'Profile updated successfully']);

    } catch (PDOException $e) {
        error_log("Update Profile Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'An error occurred. Please try again.']);
    }
}