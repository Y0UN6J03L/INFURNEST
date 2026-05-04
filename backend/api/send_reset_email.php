<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

// Load .env from project root
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

function sendResetEmail($toEmail, $resetLink) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USERNAME'];
        $mail->Password   = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = (int)$_ENV['MAIL_PORT'];

        $mail->setFrom($_ENV['MAIL_USERNAME'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress($toEmail);
        $mail->Subject = 'Password Reset Request';
        $mail->isHTML(true);
        $mail->Body = "
            <div style='font-family:Arial,sans-serif;max-width:500px;margin:0 auto;'>
                <h2 style='color:#E8922A;'>INFURNEST Password Reset</h2>
                <p>Hi, we received a request to reset your password.</p>
                <p>Click the button below to reset it. This link expires in <b>1 hour</b>.</p>
                <a href='$resetLink' 
                   style='display:inline-block;padding:12px 24px;background:#E8922A;color:#fff;
                          text-decoration:none;border-radius:8px;font-weight:bold;margin:20px 0;'>
                   Reset Password
                </a>
                <p style='color:#888;font-size:0.85rem;'>
                    If you did not request this, ignore this email. Your password won't change.
                </p>
            </div>
        ";

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("Mail error: {$mail->ErrorInfo}");
        return false;
    }
}
?>