<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/UserModel.php';

require_once __DIR__ . '/../includes/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../includes/PHPMailer/SMTP.php';
require_once __DIR__ . '/../includes/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$env = require __DIR__ . '/../.env.php';

$userModel = new UserModel($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set JSON response header
    header('Content-Type: application/json');
    
    $email = $_POST['email'] ?? '';
    
    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Please provide an email address.']);
        exit;
    }
    
    // Get user by email first
    $user = $userModel->getByEmail($email);
    
    if (!$user) {
        // Don't reveal if email exists or not for security - but don't send email or start cooldown
        echo json_encode([
            'success' => true,
            'message' => 'If an account with this email exists and is unverified, a new verification email has been sent.'
        ]);
        exit;
    }
    
    // Check if already verified
    if ($user['email_verified'] == 1) {
        echo json_encode([
            'success' => false, 
            'message' => 'This email address is already verified. You can sign in to your account.'
        ]);
        exit;
    }
    
    // Only check cooldown for unverified users who actually exist
    $remainingTime = $userModel->getCooldownTimeRemainingByEmail($email);
    if ($remainingTime > 0) {
        $minutes = floor($remainingTime / 60);
        $seconds = $remainingTime % 60;
        $timeMessage = $minutes > 0 ? "{$minutes} minute(s) and {$seconds} second(s)" : "{$seconds} second(s)";
        echo json_encode([
            'success' => false, 
            'message' => "Please wait {$timeMessage} before requesting another verification email.",
            'cooldown' => $remainingTime
        ]);
        exit;
    }
    
    // Generate new token and save
    $token = bin2hex(random_bytes(16));
    $result = $userModel->resendVerificationToken($user['id'], $token);
    
    if ($result) {
        $verifyLink = "https://venusia.great-site.net/actions/verify.php?user={$user['id']}&token=$token";
        
        // local testing
        // $verifyLink = "http://localhost/PHP-Final-Project/actions/verify.php?user={$user['id']}&token=$token";
        
        // Send email using PHPMailer
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $env['SMTP_USER'];
            $mail->Password   = $env['SMTP_PASS'];
            $mail->SMTPSecure = 'tls';
            $mail->Port       = $env['SMTP_PORT'];
            
            // Recipients
            $mail->setFrom($env['SMTP_USER'], 'Venusia Clothing Store');
            $mail->addAddress($user['email'], $user['first_name']);
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Verify your Venusia account - New Link';
            $mail->Body    = "
                <html>
                <body>
                    <p>Hello {$user['first_name']},</p>
                    <p>You requested a new verification link for your Venusia account.</p>
                    <p>Click the link below to verify your account (valid for 5 minutes):</p>
                    <p><a href='$verifyLink'>$verifyLink</a></p>
                    <p><strong>Important:</strong> This link will expire in 5 minutes for security reasons.</p>
                    <p>If you didn't request this, please ignore this email.</p>
                </body>
                </html>
            ";
            
            $mail->send();
            echo json_encode([
                'success' => true,
                'message' => 'A new verification email has been sent to your inbox.',
                'cooldown' => 300 // 5 minutes
            ]);
            exit;
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to send verification email. Please try again later.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to generate new verification link. Please try again later.']);
        exit;
    }
}
?>
