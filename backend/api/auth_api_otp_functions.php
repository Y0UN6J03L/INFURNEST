<?php
/**
 * OTP Functions for auth_api.php
 */
function handleVerifyOTP() {
    $email = trim($_POST['email'] ?? '');
    $otp = trim($_POST['otp'] ?? '');
    
    if (empty($email) || strlen($otp) !== 6) {
        jsonResponse(['success' => false, 'message' => 'Email and 6-digit OTP required']);
    }
    
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT id, first_name, last_name, role FROM users WHERE email = ? AND otp_code = ? AND otp_expires > NOW()");
        $stmt->execute([$email, $otp]);
        $user = $stmt->fetch();
        
        if (!$user) {
            jsonResponse(['success' => false, 'message' => 'Invalid or expired OTP']);
        }
        
        // Verify user
        $update = $pdo->prepare("UPDATE users SET is_verified = 1, otp_code = NULL, otp_expires = NULL WHERE id = ?");
        $update->execute([$user['id']]);
        
        // Set full session if pending
        if (isset($_SESSION['pending_user_id']) && $_SESSION['pending_user_id'] == $user['id']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_role'] = $user['role'] ?? 'customer';
            unset($_SESSION['pending_verify_email'], $_SESSION['pending_user_id']);
            
            logActivity($user['id'], 'login', 'User logged in after OTP verification');
            
            jsonResponse([
                'success' => true,
                'message' => 'Verified! Redirecting...',
                'redirect_url' => 'index.php'
            ]);
        } else {
            logActivity($user['id'], 'otp_verified', 'Email verified via OTP');
            jsonResponse([
                'success' => true,
                'message' => 'Email verified! You can now login.'
            ]);
        }
        
    } catch (PDOException $e) {
        error_log("OTP Verify Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Server error. Try again.']);
    }
}

function handleResendOTP() {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        jsonResponse(['success' => false, 'message' => 'Email required']);
    }
    
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT id, first_name, last_name FROM users WHERE email = ? AND is_verified = 0");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if (!$user) {
            jsonResponse(['success' => false, 'message' => 'No pending verification for this email']);
        }
        
        $otp = sprintf("%06d", rand(0, 999999));
        $expires = date('Y-m-d H:i:s', time() + 300); // 5 min
        
        $update = $pdo->prepare("UPDATE users SET otp_code = ?, otp_expires = ? WHERE id = ?");
        $update->execute([$otp, $expires, $user['id']]);
        
        $emailSent = sendVerificationEmail($email, $otp, $user['first_name'] . ' ' . $user['last_name']);
        
        logActivity($user['id'], 'otp_resend', 'OTP resent');
        
        jsonResponse([
            'success' => true,
            'message' => $emailSent ? 'New OTP sent!' : 'OTP generated (check SMTP)',
            'cooldown' => 60
        ]);
    } catch (PDOException $e) {
        error_log("Resend OTP Error: " . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'Server error.']);
    }
}
?>

