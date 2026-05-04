<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if already logged in, redirect to home
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INFURNEST - Email Verification</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Playfair+Display:wght@700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #E8922A; --primary-dark: #C9730E; --primary-light: #F5B854;
            --brown: #3D2B1F; --brown-light: #6B4226; --brown-lighter: #A0674A;
            --cream: #FAF6F1; --cream-dark: #F0E6DC;
            --text-dark: #2C1810; --text-light: #8B7355;
            --white: #FFFFFF; --error: #FF6B6B; --success: #51CF66;
        }
        body {
            font-family: 'Space Grotesk', sans-serif;
            background: linear-gradient(135deg, var(--brown) 0%, var(--brown-light) 50%, var(--brown-lighter) 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            overflow-x: hidden;
        }
        body::before {
            content: ''; position: fixed; top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(232,146,42,0.1) 1px, transparent 1px);
            background-size: 50px 50px; animation: moveBackground 20s linear infinite; z-index: -1;
        }
        @keyframes moveBackground { 0% { transform: translate(0,0); } 100% { transform: translate(50px,50px); } }
        .container-main { width: 100%; max-width: 800px; padding: 20px; }
        .verification-card { 
            background: var(--white); 
            border-radius: 24px; 
            padding: 60px 40px; 
            box-shadow: 0 20px 60px rgba(0,0,0,0.3); 
            backdrop-filter: blur(10px); 
            text-align: center;
            position: relative; 
            overflow: hidden;
            max-width: 500px; margin: 0 auto;
        }
        .verification-card::before { 
            content: ''; 
            position: absolute; 
            top: 0; left: 0; right: 0; 
            height: 4px; 
            background: linear-gradient(90deg, var(--primary), var(--primary-light)); 
        }
        .logo { 
            display: flex; align-items: center; justify-content: center; 
            gap: 12px; margin-bottom: 30px; 
            font-size: 28px; font-weight: 900; 
            font-family: 'Playfair Display', serif; 
            color: var(--brown);
        }
        .logo i { font-size: 36px; color: var(--primary); }
        .status-icon { 
            width: 100px; height: 100px; 
            margin: 0 auto 24px; 
            border-radius: 50%; 
            display: flex; align-items: center; justify-content: center;
            font-size: 48px; 
        }
        .status-success { background: rgba(81, 207, 102, 0.1); color: var(--success); border: 3px solid rgba(81, 207, 102, 0.3); }
        .status-error { background: rgba(255, 107, 107, 0.1); color: var(--error); border: 3px solid rgba(255, 107, 107, 0.3); }
        .status-loading { background: rgba(232, 146, 42, 0.1); color: var(--primary); border: 3px solid rgba(232, 146, 42, 0.2); animation: pulse 1.5s infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
        h1 { 
            font-family: 'Playfair Display', serif; 
            font-size: 2.2rem; 
            font-weight: 900; 
            color: var(--brown); 
            margin-bottom: 16px; 
            line-height: 1.2;
        }
        .message { 
            color: var(--text-light); 
            font-size: 1.1rem; 
            line-height: 1.6; 
            margin-bottom: 32px; 
            max-width: 400px; margin-left: auto; margin-right: auto;
        }
        .btn { 
            display: inline-flex; align-items: center; gap: 12px; 
            padding: 16px 32px; 
            background: linear-gradient(135deg, var(--primary), var(--primary-dark)); 
            color: var(--white); 
            border: none; 
            border-radius: 12px; 
            font-family: 'Space Grotesk', sans-serif; 
            font-size: 1rem; font-weight: 700; 
            text-decoration: none; 
            cursor: pointer; 
            transition: all 0.3s ease; 
            box-shadow: 0 8px 20px rgba(232,146,42,0.3);
        }
        .btn:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 12px 30px rgba(232,146,42,0.4); 
        }
        .btn-secondary { 
            background: var(--cream); 
            color: var(--brown); 
            border: 2px solid var(--cream-dark); 
            margin-top: 16px;
        }
        .btn-secondary:hover { border-color: var(--primary); color: var(--primary); }
        .loading { display: none; }
        .loading.show { display: block; }
        .result { display: none; }
        .result.show { display: block; }
        @media (max-width: 768px) { 
            .verification-card { padding: 40px 24px; margin: 20px; } 
            h1 { font-size: 1.8rem; }
        }
    </style>
</head>
<body>
    <div class="container-main">
        <div class="verification-card">
            <div class="logo">
                <i class="fas fa-paw"></i>
                <span>INFURNEST</span>
            </div>
            
            <!-- Loading State -->
            <div id="loadingState">
                <div class="status-icon status-loading">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
                <h1>Verifying Email</h1>
                <p class="message">Please wait while we verify your email address...</p>
            </div>
            
            <!-- Success State -->
            <div id="successState" class="result">
                <div class="status-icon status-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1>Email Verified!</h1>
                <p class="message">Your account has been successfully verified. You can now sign in to your INFURNEST account.</p>
                <a href="login.php" class="btn">
                    <i class="fas fa-sign-in-alt"></i>
                    Sign In Now
                </a>
                <a href="../index.php" class="btn btn-secondary">
                    <i class="fas fa-home"></i>
                    Continue to Home
                </a>
            </div>
            
            <!-- Error State -->
            <div id="errorState" class="result">
                <div class="status-icon status-error">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <h1>Verification Failed</h1>
                <p class="message" id="errorMessage">The verification link is invalid or has expired.</p>
                <a href="login.php" class="btn">
                    <i class="fas fa-sign-in-alt"></i>
                    Go to Login
                </a>
                <a href="login.php" class="btn btn-secondary">
                    <i class="fas fa-redo"></i>
                    Resend Verification
                </a>
            </div>
        </div>
    </div>

    <script>
        // Auto-verify on page load
        async function verifyEmail() {
            const urlParams = new URLSearchParams(window.location.search);
            const token = urlParams.get('token');
            
            if (!token) {
                showError('No verification token found. Please request a new verification email.');
                return;
            }
            
            try {
                const response = await fetch('../backend/api/auth_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=verify&token=' + encodeURIComponent(token)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showSuccess();
                    // Auto-redirect after 5 seconds
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 5000);
                } else {
                    showError(data.message || 'Verification failed. Please try again.');
                }
            } catch (error) {
                console.error('Verification error:', error);
                showError('Network error. Please check your connection and try again.');
            }
        }
        
        function showSuccess() {
            document.getElementById('loadingState').style.display = 'none';
            document.getElementById('successState').classList.add('show');
        }
        
        function showError(message) {
            document.getElementById('loadingState').style.display = 'none';
            document.getElementById('errorState').classList.add('show');
            document.getElementById('errorMessage').textContent = message;
        }
        
        // Start verification
        window.addEventListener('load', verifyEmail);
    </script>
</body>
</html>
