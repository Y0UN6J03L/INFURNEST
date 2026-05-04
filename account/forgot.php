<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

require '../backend/includes/db.php';
require '../backend/api/send_reset_email.php';
?>
<?php

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate token
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Save token to DB
        $stmt2 = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $stmt2->bind_param("sss", $token, $expires, $email);
        $stmt2->execute();

        // Send email
        $resetLink = "http://localhost/INFURNESTE/account/reset.php?token=$token";
        sendResetEmail($email, $resetLink);

        $message = "✅ Reset link sent! Check your email.";
    } else {
        $message = "❌ Email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INFURNEST - Forgot Password</title>
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
            overflow-x: hidden; position: relative;
        }
        body::before {
            content: ''; position: fixed; top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(232,146,42,0.1) 1px, transparent 1px);
            background-size: 50px 50px; animation: moveBackground 20s linear infinite; z-index: -1;
        }
        @keyframes moveBackground { 0% { transform: translate(0,0); } 100% { transform: translate(50px,50px); } }
        .container-main { width: 100%; max-width: 800px; padding: 20px; perspective: 1000px; }
        .forgot-wrapper { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center; }
        @media (max-width: 768px) { .forgot-wrapper { grid-template-columns: 1fr; gap: 20px; } }
        .brand-section { color: var(--white); padding: 40px; animation: slideInLeft 0.8s ease-out; }
        @keyframes slideInLeft { from { opacity:0; transform:translateX(-50px); } to { opacity:1; transform:translateX(0); } }
        .logo { display:flex; align-items:center; gap:12px; margin-bottom:40px; font-size:32px; font-weight:900; font-family:'Playfair Display',serif; letter-spacing:-1px; }
        .logo i { font-size:40px; color:var(--primary-light); filter:drop-shadow(0 4px 12px rgba(232,146,42,0.3)); }
        .logo span { color:var(--primary-light); }
        .brand-section h1 { font-size:3.5rem; font-family:'Playfair Display',serif; font-weight:900; line-height:1.1; margin-bottom:20px; background:linear-gradient(135deg,var(--white),var(--primary-light)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
        .brand-section p { font-size:1.1rem; color:rgba(255,255,255,0.8); line-height:1.6; margin-bottom:30px; font-weight:300; }
        .features { display:flex; flex-direction:column; gap:16px; }
        .feature { display:flex; align-items:center; gap:12px; color:rgba(255,255,255,0.9); font-size:0.95rem; }
        .feature i { font-size:20px; color:var(--primary-light); }
        .forgot-section { animation: slideInRight 0.8s ease-out; }
        @keyframes slideInRight { from { opacity:0; transform:translateX(50px); } to { opacity:1; transform:translateX(0); } }
        .forgot-card { background:var(--white); border-radius:24px; padding:50px 40px; box-shadow:0 20px 60px rgba(0,0,0,0.3); backdrop-filter:blur(10px); position:relative; overflow:hidden; max-width:450px; margin:0 auto; }
        .forgot-card::before { content:''; position:absolute; top:0; left:0; right:0; height:4px; background:linear-gradient(90deg,var(--primary),var(--primary-light)); }
        .form-header { margin-bottom:30px; text-align:center; }
        .form-header h2 { font-size:1.8rem; font-weight:700; color:var(--brown); margin-bottom:8px; font-family:'Playfair Display',serif; }
        .form-header p { color:var(--text-light); font-size:0.95rem; font-weight:300; }
        .form-group { margin-bottom:20px; }
        label { display:block; font-size:0.85rem; font-weight:600; color:var(--brown); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px; }
        input { width:100%; padding:14px 16px; border:2px solid var(--cream-dark); border-radius:12px; font-family:'Space Grotesk',sans-serif; font-size:0.95rem; color:var(--brown); background:var(--cream); transition:all 0.3s ease; outline:none; }
        input:focus { border-color:var(--primary); background:var(--white); box-shadow:0 0 0 4px rgba(232,146,42,0.1); transform:translateY(-2px); }
        input::placeholder { color:var(--text-light); }
        .btn { width:100%; padding:14px 20px; border:none; border-radius:12px; font-family:'Space Grotesk',sans-serif; font-size:0.95rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; cursor:pointer; transition:all 0.3s ease; display:flex; align-items:center; justify-content:center; gap:8px; }
        .btn-primary { background:linear-gradient(135deg,var(--primary),var(--primary-dark)); color:var(--white); box-shadow:0 8px 20px rgba(232,146,42,0.3); }
        .btn-primary:hover { transform:translateY(-3px); box-shadow:0 12px 30px rgba(232,146,42,0.4); }
        .btn-primary:active { transform:translateY(-1px); }
        .btn-primary:disabled { opacity:0.7; cursor:not-allowed; transform:none; }
        .alert { padding:12px 16px; border-radius:12px; margin-bottom:20px; display:none; align-items:center; gap:10px; font-size:0.9rem; animation:slideDown 0.3s ease; }
        .alert.show { display:flex; }
        .alert-error { background:#FFE5E5; color:var(--error); border:2px solid var(--error); }
        .alert-success { background:#E6FFED; color:var(--success); border:2px solid var(--success); }
        .alert-info { background:#FFF8E5; color:#D97706; border:2px solid #FBBF24; }
        @keyframes slideDown { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }
        .dev-link { background:#EFF6FF; padding:12px; border-radius:10px; margin-top:15px; border:1px solid #93C5FD; }
        .dev-link a { color:#2563EB; text-decoration:none; font-weight:600; }
        .dev-link a:hover { text-decoration:underline; }
        .back-link { display:inline-block; margin-top:20px; color:var(--primary); text-decoration:none; font-weight:600; }
        .back-link:hover { color:var(--primary-dark); }
        #toastContainer { position:fixed; bottom:30px; right:30px; z-index:9999; }
        .toast { background:var(--white); padding:16px 24px; border-radius:12px; margin-bottom:12px; box-shadow:0 8px 24px rgba(0,0,0,0.15); display:flex; align-items:center; gap:12px; min-width:300px; animation:slideInToast 0.3s ease; }
        .toast-success { border-left:4px solid var(--success); }
        .toast-error { border-left:4px solid var(--error); }
        @keyframes slideInToast { from { opacity:0; transform:translateX(400px); } to { opacity:1; transform:translateX(0); } }
        @media (max-width:768px) { .brand-section { padding:20px; } .brand-section h1 { font-size:2.5rem; } .forgot-card { padding:30px 20px; margin:0 10px; } #toastContainer { bottom:10px; right:10px; } .toast { min-width:auto; max-width:calc(100vw - 20px); } }
    </style>
</head>
<body>
    <div class="container-main">
        <div class="forgot-wrapper">
            <!-- Left Side - Branding -->
            <div class="brand-section">
                <div class="logo"><i class="fas fa-paw"></i><span>INFURNEST</span></div>
                <h1>Forgot Password?</h1>
                <p>Enter your email address and we'll send you a link to reset your password securely.</p>
                <div class="features">
                    <div class="feature"><i class="fas fa-lock"></i><span>Secure Reset Process</span></div>
                    <div class="feature"><i class="fas fa-clock"></i><span>Link expires in 1 hour</span></div>
                    <div class="feature"><i class="fas fa-shield-alt"></i><span>Fully encrypted</span></div>
                </div>
            </div>

            <!-- Right Side - Forgot Form -->
            <div class="forgot-section">
                <div class="forgot-card">
                    <div class="form-header">
                        <h2>Reset Password</h2>
                        <p>We'll send a reset link to your email</p>
                    </div>
                    <div id="forgotAlert" class="alert"></div>
                    <form onsubmit="handleForgot(event)">
                        <div class="form-group">
                            <label for="forgotEmail">Email Address</label>
                            <input type="email" id="forgotEmail" placeholder="you@example.com" required>
                        </div>
                        <button type="submit" class="btn btn-primary" id="forgotBtn">
                            <i class="fas fa-paper-plane"></i><span>Send Reset Link</span>
                        </button>
                        <div id="resultSection" style="display:none;"></div>
                    </form>
                    <div class="back-link">
                        <i class="fas fa-arrow-left"></i> <a href="login.php">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="toastContainer"></div>

    <script>
        function showAlert(id, msg, type) {
            const el = document.getElementById(id);
            el.innerHTML = '<i class="fas fa-' + (type==='error'?'exclamation-circle':'check-circle') + '"></i><span>' + msg + '</span>';
            el.className = 'alert show alert-' + type;
        }
        function clearAlert(id) { 
            const el = document.getElementById(id);
            el.classList.remove('show'); 
            el.innerHTML = '';
        }
        function showToast(msg, type) {
            const c = document.getElementById('toastContainer');
            const t = document.createElement('div');
            t.className = 'toast toast-' + type;
            t.innerHTML = '<i class="fas fa-' + (type==='error'?'times-circle':'check-circle') + '"></i><span>' + msg + '</span>';
            c.appendChild(t);
            setTimeout(function() { 
                t.style.animation = 'slideInToast 0.3s ease reverse'; 
                setTimeout(function() { t.remove(); }, 300); 
            }, 4000);
        }

        async function handleForgot(event) {
            event.preventDefault();
            const email = document.getElementById('forgotEmail').value.trim();
            const btn = document.getElementById('forgotBtn');
            const alert = document.getElementById('forgotAlert');
            const resultSection = document.getElementById('resultSection');

            if (!email || !email.includes('@')) {
                showAlert('forgotAlert', '⚠️ Please enter a valid email address.', 'error');
                showToast('Invalid email', 'error');
                return;
            }

            // Clear previous
            clearAlert('forgotAlert');
            resultSection.style.display = 'none';
            resultSection.innerHTML = '';

            // Loading state
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Sending...</span>';

            try {
                const res = await fetch('../backend/api/auth_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=forgot&email=' + encodeURIComponent(email)
                });
                const data = await res.json();

                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane"></i><span>Send Reset Link</span>';

                if (data.success) {
                    resultSection.style.display = 'block';
                    resultSection.innerHTML = `
                        <div style="background:#E6FFED; padding:16px; border-radius:12px; border:2px solid var(--success); margin-top:20px;">
                            <i class="fas fa-check-circle" style="color:var(--success); font-size:20px; margin-right:10px;"></i>
                            <strong>✅ ${data.message}</strong><br><br>
                            ${data.reset_link ? `
                                <div class="dev-link">
                                    <strong>🔗 Dev Reset Link:</strong><br>
                                    <a href="${data.reset_link}" target="_blank">Link Here</a>
                                </div>
                            ` : ''}
                        </div>
                    `;
                    showToast('Reset link sent!', 'success');
                } else {
                    showAlert('forgotAlert', '⚠️ ' + data.message, 'info');
                    showToast(data.message, 'error');
                }
            } catch (err) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane"></i><span>Send Reset Link</span>';
                showAlert('forgotAlert', '⚠️ Network error. Please try again.', 'error');
                showToast('Connection error', 'error');
                console.error('Forgot fetch error:', err);
            }
        }
    </script>
</body>
</html>
