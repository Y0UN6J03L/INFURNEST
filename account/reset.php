<?php
// Start session before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if already logged in, redirect to home
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Get token from URL
$token = $_GET['token'] ?? '';
if (empty($token)) {
    $error = 'No reset token provided';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INFURNEST - Reset Password</title>
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
        }
        .container-main { width: 100%; max-width: 800px; padding: 20px; }
        .reset-wrapper { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center; }
        @media (max-width: 768px) { .reset-wrapper { grid-template-columns: 1fr; gap: 20px; } }
        .brand-section { color: var(--white); padding: 40px; }
        .logo { display:flex; align-items:center; gap:12px; margin-bottom:40px; font-size:32px; font-weight:900; font-family:'Playfair Display',serif; }
        .logo i { font-size:40px; color:var(--primary-light); }
        .logo span { color:var(--primary-light); }
        .brand-section h1 { font-size:3.5rem; font-family:'Playfair Display',serif; font-weight:900; line-height:1.1; margin-bottom:20px; background:linear-gradient(135deg,var(--white),var(--primary-light)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
        .brand-section p { font-size:1.1rem; color:rgba(255,255,255,0.9); line-height:1.6; margin-bottom:30px; font-weight:300; }
        .reset-section { animation: slideInRight 0.8s ease-out; }
        @keyframes slideInRight { from { opacity:0; transform:translateX(50px); } to { opacity:1; transform:translateX(0); } }
        .reset-card { background:var(--white); border-radius:24px; padding:50px 40px; box-shadow:0 20px 60px rgba(0,0,0,0.3); max-width:450px; margin:0 auto; position:relative; }
        .reset-card::before { content:''; position:absolute; top:0; left:0; right:0; height:4px; background:linear-gradient(90deg,var(--primary),var(--primary-light)); }
        .form-header { margin-bottom:30px; text-align:center; }
        .form-header h2 { font-size:1.8rem; font-weight:700; color:var(--brown); margin-bottom:8px; font-family:'Playfair Display',serif; }
        .form-header p { color:var(--text-light); font-size:0.95rem; font-weight:300; }
        .token-display { background:#EFF6FF; padding:12px; border-radius:12px; margin-bottom:20px; border:1px solid #93C5FD; font-family:monospace; font-size:0.85rem; word-break:break-all; }
        .form-group { margin-bottom:20px; }
        label { display:block; font-size:0.85rem; font-weight:600; color:var(--brown); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px; }
        input { width:100%; padding:14px 16px; border:2px solid var(--cream-dark); border-radius:12px; font-family:'Space Grotesk',sans-serif; font-size:0.95rem; transition:all 0.3s; }
        input:focus { border-color:var(--primary); box-shadow:0 0 0 4px rgba(232,146,42,0.1); }
        .btn { width:100%; padding:14px 20px; border:none; border-radius:12px; font-weight:700; cursor:pointer; transition:all 0.3s; display:flex; align-items:center; justify-content:center; gap:8px; }
        .btn-primary { background:linear-gradient(135deg,var(--primary),var(--primary-dark)); color:var(--white); font-size:0.95rem; }
        .btn-primary:hover:not(:disabled) { transform:translateY(-2px); box-shadow:0 8px 20px rgba(232,146,42,0.3); }
        .btn-primary:disabled { opacity:0.7; cursor:not-allowed; }
        .alert { padding:12px; border-radius:12px; margin-bottom:20px; display:none; }
        .alert.show { display:block; }
        .alert-error { background:#FFE5E5; color:var(--error); border:1px solid var(--error); }
        .alert-success { background:#E6FFED; color:var(--success); border:1px solid var(--success); }
        .back-link { display:inline-block; margin-top:20px; color:var(--primary); text-decoration:none; font-weight:600; text-align:center; }
        .back-link:hover { color:var(--primary-dark); }
        #toastContainer { position:fixed; bottom:20px; right:20px; z-index:9999; }
        .toast { background:var(--white); padding:12px 20px; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.15); margin-bottom:10px; }
        @media (max-width:768px) { .reset-card { padding:30px 20px; margin:10px; } .brand-section h1 { font-size:2.5rem; } .brand-section { padding:20px; } }
    </style>
</head>
<body>
    <div class="container-main">
        <div class="reset-wrapper">
            <div class="brand-section">
                <div class="logo"><i class="fas fa-paw"></i><span>INFURNEST</span></div>
                <h1>Reset Your Password</h1>
                <p>Enter your new password below. Your reset token is valid for 1 hour.</p>
            </div>
            <div class="reset-section">
                <div class="reset-card">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-error show"><?php echo htmlspecialchars($error); ?></div>
                        <a href="login.php" class="back-link">← Back to Login</a>
                    <?php else: ?>
                        <div class="form-header">
                            <h2>New Password</h2>
                            <p>Enter your new password to complete the reset.</p>
                        </div>
                        <div id="resetAlert" class="alert"></div>
                        <form onsubmit="handleReset(event)">
                            <input type="hidden" id="resetToken" value="<?php echo htmlspecialchars($token); ?>">
                            <div class="form-group">
                                <label for="newPassword">New Password (min 8 chars)</label>
                                <input type="password" id="newPassword" required minlength="8">
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword">Confirm New Password</label>
                                <input type="password" id="confirmPassword" required minlength="8">
                            </div>
                            <button type="submit" class="btn btn-primary" id="resetBtn">
                                <i class="fas fa-lock"></i> Reset Password
                            </button>
                        </form>
                        <a href="login.php" class="back-link">← Back to Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        function showAlert(id, msg, type) {
            const el = document.getElementById(id);
            el.textContent = msg;
            el.className = `alert alert-${type} show`;
        }
        function clearAlert(id) {
            const el = document.getElementById(id);
            el.className = 'alert';
            el.textContent = '';
        }
        async function handleReset(event) {
            event.preventDefault();
            const token = document.getElementById('resetToken').value;
            const password = document.getElementById('newPassword').value;
            const confirm = document.getElementById('confirmPassword').value;
            const btn = document.getElementById('resetBtn');
            const alertEl = document.getElementById('resetAlert');

            if (password.length < 8) {
                showAlert('resetAlert', 'Password must be at least 8 characters', 'error');
                return;
            }
            if (password !== confirm) {
                showAlert('resetAlert', 'Passwords do not match', 'error');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Resetting...';
            clearAlert('resetAlert');

            try {
                const res = await fetch('./backend/api/auth_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=reset&token=${encodeURIComponent(token)}&password=${encodeURIComponent(password)}&confirmPassword=${encodeURIComponent(confirm)}`
                });
                const data = await res.json();

                if (data.success) {
                    showAlert('resetAlert', data.message, 'success');
                    btn.style.display = 'none';
                    setTimeout(() => window.location.href = 'login.php', 2000);
                } else {
                    showAlert('resetAlert', data.message, 'error');
                }
            } catch (err) {
                showAlert('resetAlert', 'Network error. Please try again.', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-lock"></i> Reset Password';
            }
        }
    </script>
</body>
</html>
