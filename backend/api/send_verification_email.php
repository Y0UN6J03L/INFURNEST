<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

// Load .env from project root
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();


function sendVerificationEmail($toEmail, $otpCode, $userName = 'Customer') {
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration (same as reset)
        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USERNAME'];
        $mail->Password   = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = (int)$_ENV['MAIL_PORT'];

        $mail->setFrom('your-verification@infurnest.com', 'INFURNEST Verification');
        $mail->addAddress($toEmail);
        $mail->Subject = 'Your INFURNEST Verification Code';
        $mail->isHTML(true);
        $mail->Body = "
            <div style='font-family:Arial,sans-serif;max-width:500px;margin:0 auto;padding:20px;background:#f8f9fa;border-radius:12px;'>
                <h2 style='color:#E8922A;font-family:\"Playfair Display\",serif;'>Welcome to INFURNEST, {$userName}!</h2>
                <p>Your verification code is:</p>
                <div style='background:#E8922A;color:white;font-size:32px;font-weight:bold;letter-spacing:8px;padding:20px 10px;margin:24px 0;border-radius:12px;text-align:center;font-family:monospace;'>
                    {$otpCode}
                </div>
                <p style='color:#666;'>
                    Enter this code on the verification page to activate your account.<br>
                    <strong>This code expires in 5 minutes.</strong>
                </p>
                <hr style='border:none;height:1px;background:#eee;margin:30px 0;'>
                <p style='color:#888;font-size:14px;'>
                    Need help? <a href='mailto:support@infurnest.com' style='color:#E8922A;'>Contact support</a><br>
                    © 2024 INFURNEST. All rights reserved.
                </p>
            </div>
        ";

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("Verification Mail Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>
