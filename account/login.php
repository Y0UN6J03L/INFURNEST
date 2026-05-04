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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INFURNEST - Login &amp; Register</title>
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
        .container-main { width: 100%; max-width: 1000px; padding: 20px; perspective: 1000px; }
        .auth-wrapper { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center; }
        @media (max-width: 768px) { .auth-wrapper { grid-template-columns: 1fr; gap: 20px; } }
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
        .auth-section { animation: slideInRight 0.8s ease-out; }
        @keyframes slideInRight { from { opacity:0; transform:translateX(50px); } to { opacity:1; transform:translateX(0); } }
        .auth-card { background:var(--white); border-radius:24px; padding:50px 40px; box-shadow:0 20px 60px rgba(0,0,0,0.3); backdrop-filter:blur(10px); position:relative; overflow:hidden; }
        .auth-card::before { content:''; position:absolute; top:0; left:0; right:0; height:4px; background:linear-gradient(90deg,var(--primary),var(--primary-light)); }
        .form-container { display:none; animation:fadeIn 0.4s ease-out; }
        .form-container.active { display:block; }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
        .form-header { margin-bottom:30px; text-align:center; }
        .form-header h2 { font-size:1.8rem; font-weight:700; color:var(--brown); margin-bottom:8px; font-family:'Playfair Display',serif; }
        .form-header p { color:var(--text-light); font-size:0.95rem; font-weight:300; }
        .form-group { margin-bottom:20px; }
        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:15px; }
        @media (max-width:600px) { .form-row { grid-template-columns:1fr; } }
        label { display:block; font-size:0.85rem; font-weight:600; color:var(--brown); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px; }
        input { width:100%; padding:14px 16px; border:2px solid var(--cream-dark); border-radius:12px; font-family:'Space Grotesk',sans-serif; font-size:0.95rem; color:var(--brown); background:var(--cream); transition:all 0.3s ease; outline:none; }
        input:focus { border-color:var(--primary); background:var(--white); box-shadow:0 0 0 4px rgba(232,146,42,0.1); transform:translateY(-2px); }
        input::placeholder { color:var(--text-light); }
        .input-wrapper { position:relative; }
        .toggle-password { position:absolute; right:14px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--text-light); cursor:pointer; font-size:1.1rem; transition:color 0.2s; }
        .toggle-password:hover { color:var(--primary); }
        .form-options { display:flex; justify-content:space-between; align-items:center; margin:20px 0 30px; font-size:0.9rem; }
        .checkbox-wrapper { display:flex; align-items:center; gap:8px; cursor:pointer; }
        input[type="checkbox"] { width:18px; height:18px; cursor:pointer; accent-color:var(--primary); }
        .forgot-link { color:var(--primary); text-decoration:none; font-weight:600; transition:color 0.2s; cursor:pointer; }
        .forgot-link:hover { color:var(--primary-dark); }
        .btn { width:100%; padding:14px 20px; border:none; border-radius:12px; font-family:'Space Grotesk',sans-serif; font-size:0.95rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; cursor:pointer; transition:all 0.3s ease; display:flex; align-items:center; justify-content:center; gap:8px; }
        .btn-primary { background:linear-gradient(135deg,var(--primary),var(--primary-dark)); color:var(--white); box-shadow:0 8px 20px rgba(232,146,42,0.3); }
        .btn-primary:hover { transform:translateY(-3px); box-shadow:0 12px 30px rgba(232,146,42,0.4); }
        .btn-primary:active { transform:translateY(-1px); }
        .btn-primary:disabled { opacity:0.7; cursor:not-allowed; transform:none; }
        .toggle-form { margin-top:20px; text-align:center; color:var(--text-light); font-size:0.95rem; }
        .toggle-form button { background:none; border:none; color:var(--primary); font-weight:700; cursor:pointer; text-decoration:underline; font-family:'Space Grotesk',sans-serif; font-size:0.95rem; }
        .toggle-form button:hover { color:var(--primary-dark); }
        .divider { display:flex; align-items:center; margin:30px 0; color:var(--text-light); font-size:0.85rem; text-transform:uppercase; }
        .divider::before, .divider::after { content:''; flex:1; height:1px; background:var(--cream-dark); }
        .divider::before { margin-right:12px; }
        .divider::after { margin-left:12px; }
        .social-buttons { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:20px; }
        .btn-social { padding:12px; background:var(--cream); border:2px solid var(--cream-dark); border-radius:12px; cursor:pointer; font-family:'Space Grotesk',sans-serif; font-size:0.85rem; font-weight:600; color:var(--brown); display:flex; align-items:center; justify-content:center; gap:8px; transition:all 0.3s ease; }
        .btn-social:hover { border-color:var(--primary); background:var(--white); color:var(--primary); }
        .alert { padding:12px 16px; border-radius:12px; margin-bottom:20px; display:none; align-items:center; gap:10px; font-size:0.9rem; animation:slideDown 0.3s ease; }
        .alert.show { display:flex; }
        .alert-error { background:#FFE5E5; color:var(--error); border:2px solid var(--error); }
        .alert-success { background:#E6FFED; color:var(--success); border:2px solid var(--success); }
        .alert-info { background:#FFF8E5; color:#D97706; border:2px solid #FBBF24; }
        @keyframes slideDown { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }
        .password-strength { height:4px; background:var(--cream-dark); border-radius:2px; margin-top:6px; overflow:hidden; }
        .password-strength-bar { height:100%; width:0; background:var(--error); transition:width 0.3s,background-color 0.3s; }
        #toastContainer { position:fixed; bottom:30px; right:30px; z-index:9999; }
        .toast { background:var(--white); padding:16px 24px; border-radius:12px; margin-bottom:12px; box-shadow:0 8px 24px rgba(0,0,0,0.15); display:flex; align-items:center; gap:12px; min-width:300px; animation:slideInToast 0.3s ease; }
        .toast-success { border-left:4px solid var(--success); }
        .toast-error { border-left:4px solid var(--error); }
        @keyframes slideInToast { from { opacity:0; transform:translateX(400px); } to { opacity:1; transform:translateX(0); } }
        .forgot-overlay { display:none; position:fixed; inset:0; background:rgba(61,43,31,0.6); backdrop-filter:blur(4px); z-index:2000; align-items:center; justify-content:center; padding:20px; }
        .forgot-overlay.active { display:flex; }
        .forgot-modal { background:var(--white); border-radius:20px; padding:36px 32px; width:100%; max-width:400px; box-shadow:0 24px 60px rgba(0,0,0,0.3); position:relative; }
        .forgot-modal h3 { font-family:'Playfair Display',serif; font-size:1.4rem; color:var(--brown); margin-bottom:4px; }
        .forgot-modal p { color:var(--text-light); font-size:0.9rem; margin-bottom:18px; }
        .forgot-close { position:absolute; top:12px; right:14px; background:none; border:none; font-size:1.4rem; cursor:pointer; color:var(--text-light); }
        .forgot-close:hover { color:var(--brown); }
        .forgot-result { display:none; padding:14px; border-radius:10px; font-size:0.85rem; margin-bottom:16px; word-break:break-word; }
        .forgot-result.show { display:block; }
        .forgot-result.success { background:#E6FFED; color:var(--success); border:1px solid var(--success); }
        .forgot-result.info { background:#EFF6FF; color:#2563EB; border:1px solid #93C5FD; }
        @media (max-width:768px) { .brand-section { padding:20px; } .brand-section h1 { font-size:2.5rem; } .auth-card { padding:30px 20px; } #toastContainer { bottom:10px; right:10px; } .toast { min-width:auto; max-width:calc(100vw - 20px); } }
    </style>
</head>
<body>

    <!-- Forgot Password Modal -->
    <div class="forgot-overlay" id="forgotOverlay" onclick="if(event.target===this) closeForgot()">
        <div class="forgot-modal">
            <button class="forgot-close" onclick="closeForgot()"><i class="fas fa-times"></i></button>
            <h3>Forgot Password?</h3>
            <p>Enter your email and we will send you a reset link.</p>
            <div id="forgotResult" class="forgot-result"></div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" id="forgotEmail" placeholder="you@example.com">
            </div>
            <button class="btn btn-primary" id="forgotBtn" onclick="handleForgot()">
                <span>📧</span> Send Reset Link
            </button>
        </div>
    </div>

    <div class="container-main">
        <div class="auth-wrapper">
            <!-- Left Side - Branding -->
            <div class="brand-section">
                <div class="logo"><i class="fas fa-paw"></i><span>INFURNEST</span></div>
                <h1>Your Furry Friend Deserves the Best</h1>
                <p>Welcome to the Philippines' most trusted online pet store. Shop premium pet products with care and convenience.</p>
                <div class="features">
                    <div class="feature"><i class="fas fa-truck"></i><span>Fast &amp; Reliable Delivery</span></div>
                    <div class="feature"><i class="fas fa-check-circle"></i><span>Vet-Approved Products</span></div>
                    <div class="feature"><i class="fas fa-heart"></i><span>24/7 Customer Support</span></div>
                    <div class="feature"><i class="fas fa-shield-alt"></i><span>Secure &amp; Safe Payments</span></div>
                </div>
            </div>

            <!-- Right Side - Auth Forms -->
            <div class="auth-section">
                <div class="auth-card">
                    <!-- Login Form -->
                    <div class="form-container active" id="loginForm">
                        <div class="form-header"><h2>Welcome Back</h2><p>Sign in to your account</p></div>
                        <div id="loginAlert" class="alert"></div>
                        <form onsubmit="handleLogin(event)">
                            <div class="form-group">
                                <label for="loginEmail">Email Address</label>
                                <input type="email" id="loginEmail" placeholder="you@example.com" required>
                            </div>
                            <div class="form-group">
                                <label for="loginPassword">Password</label>
                                <div class="input-wrapper">
                                    <input type="password" id="loginPassword" placeholder="Enter your password" required>
                                    <button type="button" class="toggle-password" onclick="toggleLoginPassword()">
                                        <i class="fas fa-eye" id="loginEyeIcon"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-options">
                                <label class="checkbox-wrapper">
                                    <input type="checkbox" id="rememberMe">
                                    <span>Remember me</span>
                                </label>
                                <a class="forgot-link" onclick="openForgot(); return false;">Forgot password?</a>
                            </div>
                            <button type="submit" class="btn btn-primary" id="loginBtn">
                                <i class="fas fa-sign-in-alt"></i><span>Sign In</span>
                            </button>
                        </form>
                        <div class="divider">or</div>
                        <div class="social-buttons">
                            <button class="btn-social" onclick="showToast('Google login coming soon', 'success')">
                                <i class="fab fa-google"></i> Google
                            </button>
                            <button class="btn-social" onclick="showToast('Facebook login coming soon', 'success')">
                                <i class="fab fa-facebook"></i> Facebook
                            </button>
                        </div>
                        <div class="toggle-form">
                            Don't have an account? <button type="button" onclick="switchForm()">Create one</button>
                        </div>
                    </div>

                    <!-- Register Form -->
                    <div class="form-container" id="registerForm">
                        <div class="form-header"><h2>Create Account</h2><p>Join INFURNEST today</p></div>
                        <div id="registerAlert" class="alert"></div>
                        <form onsubmit="handleRegister(event)">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="firstName">First Name</label>
                                    <input type="text" id="firstName" placeholder="John" required>
                                </div>
                                <div class="form-group">
                                    <label for="lastName">Last Name</label>
                                    <input type="text" id="lastName" placeholder="Doe" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="registerEmail">Email Address</label>
                                <input type="email" id="registerEmail" placeholder="you@example.com" required>
                            </div>
                            <div class="form-group">
                                <label for="registerPhone">Phone Number</label>
                                <input type="tel" id="registerPhone" placeholder="+63 9XX XXX XXXX">
                            </div>
                            <div class="form-group">
                                <label for="registerPassword">Password</label>
                                <div class="input-wrapper">
                                    <input type="password" id="registerPassword" placeholder="At least 8 characters" required>
                                    <button type="button" class="toggle-password" onclick="toggleRegisterPassword()">
                                        <i class="fas fa-eye" id="registerEyeIcon"></i>
                                    </button>
                                </div>
                                <div class="password-strength">
                                    <div class="password-strength-bar" id="strengthBar"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword">Confirm Password</label>
                                <div class="input-wrapper">
                                    <input type="password" id="confirmPassword" placeholder="Re-enter password" required>
                                    <button type="button" class="toggle-password" onclick="toggleConfirmPassword()">
                                        <i class="fas fa-eye" id="confirmEyeIcon"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="checkbox-wrapper">
                                    <input type="checkbox" id="agreeTerms" required>
                                    <span>I agree to Terms &amp; Conditions and Privacy Policy</span>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary" id="registerBtn">
                                <i class="fas fa-user-check"></i><span>Create Account</span>
                            </button>
                        </form>
                        <div class="toggle-form">
                            Already have an account? <button type="button" onclick="switchForm()">Sign in</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="toastContainer"></div>

    <script>
        // Auto-switch to register form if URL has #register hash
        document.addEventListener('DOMContentLoaded', function() {
            if (window.location.hash === '#register') {
                document.getElementById('loginForm').classList.remove('active');
                document.getElementById('registerForm').classList.add('active');
            }
        });
        function switchForm() {
            document.getElementById('loginForm').classList.toggle('active');
            document.getElementById('registerForm').classList.toggle('active');
            clearAlert('loginAlert');
            clearAlert('registerAlert');
        }
        function toggleLoginPassword() {
            const input = document.getElementById('loginPassword');
            const icon = document.getElementById('loginEyeIcon');
            input.type = input.type === 'password' ? 'text' : 'password';
            icon.className = input.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        }
        function toggleRegisterPassword() {
            const input = document.getElementById('registerPassword');
            const icon = document.getElementById('registerEyeIcon');
            input.type = input.type === 'password' ? 'text' : 'password';
            icon.className = input.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        }
        function toggleConfirmPassword() {
            const input = document.getElementById('confirmPassword');
            const icon = document.getElementById('confirmEyeIcon');
            input.type = input.type === 'password' ? 'text' : 'password';
            icon.className = input.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        }
        document.getElementById('registerPassword')?.addEventListener('input', function() {
            const pwd = this.value; let strength = 0; let color = '#FF6B6B';
            if (pwd.length >= 8) strength += 25;
            if (pwd.length >= 12) strength += 25;
            if (/[A-Z]/.test(pwd)) strength += 25;
            if (/[0-9]/.test(pwd)) strength += 15;
            if (/[^A-Za-z0-9]/.test(pwd)) strength += 10;
            const bar = document.getElementById('strengthBar');
            bar.style.width = strength + '%';
            if (strength < 50) color = '#FF6B6B';
            else if (strength < 75) color = '#FFA500';
            else color = '#51CF66';
            bar.style.backgroundColor = color;
        });
        function showAlert(id, msg, type) {
            const el = document.getElementById(id);
            el.innerHTML = '<i class="fas fa-' + (type === 'error' ? 'exclamation-circle' : 'check-circle') + '"></i><span>' + msg + '</span>';
            el.className = 'alert show alert-' + type;
        }
        function clearAlert(id) { document.getElementById(id).classList.remove('show'); }
        function showToast(msg, type) {
            const c = document.getElementById('toastContainer');
            const t = document.createElement('div');
            t.className = 'toast toast-' + type;
            t.innerHTML = '<i class="fas fa-' + (type === 'error' ? 'times-circle' : 'check-circle') + '"></i><span>' + msg + '</span>';
            c.appendChild(t);
            setTimeout(function() { t.style.animation = 'slideInToast 0.3s ease reverse'; setTimeout(function() { t.remove(); }, 300); }, 3000);
        }
        function openForgot() {
            document.getElementById('forgotOverlay').classList.add('active');
            document.getElementById('forgotEmail').value = document.getElementById('loginEmail').value || '';
        }
        function closeForgot() {
            document.getElementById('forgotOverlay').classList.remove('active');
            document.getElementById('forgotResult').className = 'forgot-result';
            document.getElementById('forgotResult').innerHTML = '';
            document.getElementById('forgotBtn').disabled = false;
            document.getElementById('forgotBtn').innerHTML = '<span>📧</span> Send Reset Link';
        }
        async function handleForgot() {
            const email = document.getElementById('forgotEmail').value.trim();
            const btn = document.getElementById('forgotBtn');
            const result = document.getElementById('forgotResult');
            if (!email || !email.includes('@')) {
                result.className = 'forgot-result show info';
                result.innerHTML = '⚠️ Please enter a valid email address.';
                return;
            }
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            result.className = 'forgot-result';
            result.innerHTML = '';
            try {
                const res = await fetch('../backend/api/auth_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=forgot&email=' + encodeURIComponent(email)
                });
                const data = await res.json();
                btn.disabled = false;
                btn.innerHTML = '<span>📧</span> Send Reset Link';
                if (data.success) {
                    result.className = 'forgot-result show success';
                    result.innerHTML = '✅ ' + data.message + '<br>';
                    if (data.reset_link) {
                        result.innerHTML += '<small><b>Dev link:</b> <a href="' + data.reset_link + '" target="_blank" style="color:#2563EB;">Link Here</a></small>';
                    }
                } else {
                    result.className = 'forgot-result show info';
                    result.innerHTML = '⚠️ ' + data.message;
                }
            } catch (err) {
                btn.disabled = false;
                btn.innerHTML = '<span>📧</span> Send Reset Link';
                result.className = 'forgot-result show info';
                result.innerHTML = '⚠️ Network error. Please try again.';
            }
        }
        async function handleLogin(event) {
            event.preventDefault();
            const email = document.getElementById('loginEmail').value.trim();
            const password = document.getElementById('loginPassword').value;
            const btn = document.getElementById('loginBtn');
            if (!email || !password) {
                showAlert('loginAlert', 'Please enter email and password.', 'error');
                showToast('Fill in all fields', 'error');
                return;
            }
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Signing in...</span>';
            try {
                const res = await fetch('../backend/api/auth_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=login&email=' + encodeURIComponent(email)
                        + '&password=' + encodeURIComponent(password)
                        + '&remember=' + (document.getElementById('rememberMe').checked ? '1' : '0')
                });
                const data = await res.json();
                if (data.success) {
                    if (data.needs_verification) {
                        showAlert('loginAlert', data.message, 'info');
                        showToast('Verification needed', 'success');
                        setTimeout(function() { window.location.href = data.redirect_url; }, 1200);
                    } else {
                        showAlert('loginAlert', '🎉 Welcome back, ' + data.name + '! Redirecting...', 'success');
                        showToast('Login successful!', 'success');
                        setTimeout(function() { window.location.href = '../index.php'; }, 1200);
                    }
                } else {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-sign-in-alt"></i><span>Sign In</span>';
                    showAlert('loginAlert', data.message, 'error');
                    showToast('Login failed', 'error');
                }
            } catch (err) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-sign-in-alt"></i><span>Sign In</span>';
                showAlert('loginAlert', 'Connection error: ' + (err.message || 'Unable to reach server.'), 'error');
                showToast('Connection error', 'error');
            }
        }
        async function handleRegister(event) {
            event.preventDefault();
            const firstName = document.getElementById('firstName').value.trim();
            const lastName = document.getElementById('lastName').value.trim();
            const email = document.getElementById('registerEmail').value.trim();
            const phone = document.getElementById('registerPhone').value.trim();
            const password = document.getElementById('registerPassword').value;
            const confirm = document.getElementById('confirmPassword').value;
            const agree = document.getElementById('agreeTerms').checked;
            const btn = document.getElementById('registerBtn');
            if (!firstName || !lastName || !email || !password) {
                showAlert('registerAlert', 'All required fields must be filled.', 'error');
                showToast('Fill in all required fields', 'error');
                return;
            }
            if (!email.includes('@')) {
                showAlert('registerAlert', 'Please enter a valid email address.', 'error');
                return;
            }
            if (password.length < 8) {
                showAlert('registerAlert', 'Password must be at least 8 characters.', 'error');
                return;
            }
            if (password !== confirm) {
                showAlert('registerAlert', 'Passwords do not match.', 'error');
                return;
            }
            if (!agree) {
                showAlert('registerAlert', 'You must agree to the terms.', 'error');
                return;
            }
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Creating account...</span>';
            try {
                const res = await fetch('../backend/api/auth_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=register'
                        + '&firstName=' + encodeURIComponent(firstName)
                        + '&lastName=' + encodeURIComponent(lastName)
                        + '&email=' + encodeURIComponent(email)
                        + '&phone=' + encodeURIComponent(phone)
                        + '&password=' + encodeURIComponent(password)
                        + '&confirm=' + encodeURIComponent(confirm)
                });
                const data = await res.json();
                if (data.success) {
                    // ✅ CHANGED: redirect to OTP verification page instead of switching to login
                    showAlert('registerAlert', '✅ ' + data.message, 'success');
                    showToast('Check your email for the OTP!', 'success');
                    setTimeout(function() {
                        window.location.href = data.redirect_url;
                    }, 1500);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-user-check"></i><span>Create Account</span>';
                    showAlert('registerAlert', data.message, 'error');
                    showToast(data.message, 'error');
                }
            } catch (err) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-user-check"></i><span>Create Account</span>';
                showAlert('registerAlert', 'Network error. Please try again.', 'error');
                showToast('Network error', 'error');
            }
        }
    </script>
</body>
</html>