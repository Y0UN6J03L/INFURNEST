<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INFURNEST - Enter Verification Code</title>
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
        .container-main { width: 100%; max-width: 500px; padding: 20px; }
        .verification-card { 
            background: var(--white); 
            border-radius: 24px; 
            padding: 50px 40px; 
            box-shadow: 0 20px 60px rgba(0,0,0,0.3); 
            text-align: center;
            position: relative; 
        }
        .verification-card::before { 
            content: ''; 
            position: absolute; top: 0; left: 0; right: 0; 
            height: 4px; 
            background: linear-gradient(90deg, var(--primary), var(--primary-light)); 
        }
        .logo { display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 30px; font-size: 28px; font-weight: 900; font-family: 'Playfair Display', serif; color: var(--brown); }
        .logo i { font-size: 36px; color: var(--primary); }
        h1 { font-family: 'Playfair Display', serif; font-size: 2.2rem; font-weight: 900; color: var(--brown); margin-bottom: 16px; }
        .message { color: var(--text-light); font-size: 1.1rem; line-height: 1.6; margin-bottom: 32px; }
        .otp-input-container { margin-bottom: 30px; }
        .otp-label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--brown); margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
        .otp-inputs { display: flex; gap: 12px; justify-content: center; max-width: 300px; margin: 0 auto; }
        .otp-input { width: 55px; height: 55px; font-size: 24px; font-weight: 700; text-align: center; border: 2px solid var(--cream-dark); border-radius: 12px; background: var(--cream); transition: all 0.3s; font-family: monospace; }
        .otp-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(232,146,42,0.1); outline: none; transform: scale(1.05); }
        .otp-input.error { border-color: var(--error); }
        #verifyBtn { width: 100%; padding: 16px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: var(--white); border: none; border-radius: 12px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: all 0.3s; box-shadow: 0 8px 20px rgba(232,146,42,0.3); text-transform: uppercase; letter-spacing: 0.5px; }
        #verifyBtn:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(232,146,42,0.4); }
        #verifyBtn:disabled { opacity: 0.7; cursor: not-allowed; }
        .alert { padding: 12px 20px; border-radius: 12px; margin: 20px 0; display: none; align-items: center; gap: 10px; font-size: 0.9rem; }
        .alert.show { display: flex; }
        .alert-error { background: #FFE5E5; color: var(--error); border: 2px solid var(--error); }
        .alert-success { background: #E6FFED; color: var(--success); border: 2px solid var(--success); }
        .resend-section { margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--cream-dark); }
        .resend-link { color: var(--primary); text-decoration: none; font-weight: 600; }
        .resend-link:hover { color: var(--primary-dark); }
        .countdown { font-size: 0.9rem; color: var(--text-light); margin-top: 8px; }
        @media (max-width: 768px) { .otp-inputs { gap: 8px; } .otp-input { width: 45px; height: 45px; font-size: 20px; } }
    </style>
</head>
<body>
    <div class="container-main">
        <div class="verification-card">
            <div class="logo">
                <i class="fas fa-paw"></i>
                <span>INFURNEST</span>
            </div>
            
            <h1>Verify Your Email</h1>
            <p class="message" id="emailDisplay">Enter the 6-digit code sent to your email</p>
            
            <div id="alertContainer" class="alert"></div>
            
            <form id="otpForm">
                <div class="otp-input-container">
                    <label class="otp-label">Verification Code</label>
                    <div class="otp-inputs">
                        <input type="text" maxlength="1" class="otp-input" id="otp1">
                        <input type="text" maxlength="1" class="otp-input" id="otp2">
                        <input type="text" maxlength="1" class="otp-input" id="otp3">
                        <input type="text" maxlength="1" class="otp-input" id="otp4">
                        <input type="text" maxlength="1" class="otp-input" id="otp5">
                        <input type="text" maxlength="1" class="otp-input" id="otp6">
                    </div>
                </div>
                <input type="email" id="verifyEmail" style="display:none;">
                <button type="submit" id="verifyBtn" class="btn-primary">
                    <i class="fas fa-check"></i> Verify Code
                </button>
            </form>
            
            <div class="resend-section" id="resendSection">
                <p>Didn't receive the code? <a href="#" class="resend-link" onclick="resendOTP(event)">Resend Code</a></p>
                <div class="countdown" id="countdown"></div>
            </div>
        </div>
    </div>

    <script>
        let resendCooldown = 0;
        const urlParams = new URLSearchParams(window.location.search);
        const email = decodeURIComponent(urlParams.get('email') || '');
        document.getElementById('verifyEmail').value = email;
        document.getElementById('emailDisplay').textContent = `Enter the 6-digit code sent to ${email}`;
        
        // OTP input handlers
        const otpInputs = ['otp1', 'otp2', 'otp3', 'otp4', 'otp5', 'otp6'];
        otpInputs.forEach((id, index) => {
            const input = document.getElementById(id);
            input.addEventListener('input', (e) => {
                if (e.target.value.length === 1 && /[0-9]/.test(e.target.value)) {
                    if (index < 5) {
                        document.getElementById(otpInputs[index + 1]).focus();
                    } else {
                        document.getElementById('verifyBtn').focus();
                    }
                } else if (e.target.value.length > 1) {
                    e.target.value = e.target.value.slice(-1);
                } else {
                    e.target.value = '';
                }
                updateVerifyBtn();
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !input.value && index > 0) {
                    document.getElementById(otpInputs[index - 1]).focus();
                }
            });
        });
        
        function updateVerifyBtn() {
            const otp = otpInputs.map(id => document.getElementById(id).value).join('');
            document.getElementById('verifyBtn').disabled = otp.length !== 6;
        }
        
        document.getElementById('otpForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const otp = otpInputs.map(id => document.getElementById(id).value).join('');
            const verifyBtn = document.getElementById('verifyBtn');
            
            if (otp.length !== 6) return;
            
            verifyBtn.disabled = true;
            verifyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';
            
            try {
                const response = await fetch('../backend/api/auth_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=verify_otp&email=' + encodeURIComponent(email) + '&otp=' + encodeURIComponent(otp)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showAlert('success', data.message);
                    setTimeout(() => window.location.href = 'login.php', 2000);
                } else {
                    showAlert('error', data.message);
                    otpInputs.forEach(id => document.getElementById(id).value = '');
                    document.getElementById('otp1').focus();
                }
            } catch (error) {
                showAlert('error', 'Network error. Please try again.');
            }
            
            verifyBtn.disabled = false;
            verifyBtn.innerHTML = '<i class="fas fa-check"></i> Verify Code';
        });
        
        function showAlert(type, message) {
            const container = document.getElementById('alertContainer');
            container.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> <span>${message}</span>`;
            container.className = `alert show alert-${type}`;
            setTimeout(() => container.classList.remove('show'), 5000);
        }
        
        function resendOTP(e) {
            e.preventDefault();
            if (resendCooldown > 0) return;
            
            resendCooldown = 60;
            updateResendCooldown();
            
            // Simulate resend (call register again or dedicated endpoint)
            fetch('../backend/api/auth_api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=resend_otp&email=' + encodeURIComponent(email)
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                } else {
                    showAlert('error', data.message);
                }
            });
        }
        
        function updateResendCooldown() {
            if (resendCooldown > 0) {
                document.querySelector('.resend-link').style.opacity = '0.5';
                document.querySelector('.resend-link').innerHTML = `Resend in ${resendCooldown}s`;
                resendCooldown--;
                setTimeout(updateResendCooldown, 1000);
            } else {
                document.querySelector('.resend-link').style.opacity = '1';
                document.querySelector('.resend-link').innerHTML = 'Resend Code';
            }
        }
        
        updateVerifyBtn();
    </script>
</body>
</html>
