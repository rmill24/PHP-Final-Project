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
    // Basic validation
    $errors = [];
    
    // Check if email is already registered
    $email = trim($_POST['email'] ?? '');
    if (!empty($email)) {
        $existingUser = $userModel->getByEmail($email);
        if ($existingUser) {
            $errors[] = 'This email is already registered. Please use a different email or try signing in.';
        }
    }
    
    // Validate phone number
    $phoneNumber = trim($_POST['phone'] ?? '');
    if (empty($phoneNumber)) {
        $errors[] = 'Phone number is required';
    } else {
        // Remove all non-digit characters for validation
        $digitsOnly = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        if (strlen($digitsOnly) !== 11) {
            $errors[] = 'Phone number must be exactly 11 digits';
        } elseif (!preg_match('/^[0-9+\-\s()]+$/', $phoneNumber)) {
            $errors[] = 'Phone number contains invalid characters';
        }
    }
    
    // If there are validation errors, redirect back with error message
    if (!empty($errors)) {
        $errorMessage = implode(', ', $errors);
        header("Location: ../index.php?page=sign_up&error=" . urlencode($errorMessage));
        exit;
    }
    
    // Combine address fields into a single address string
    $addressParts = [
        $_POST['street'],
        $_POST['city'],
        $_POST['state'],
        $_POST['zipCode'],
        $_POST['country']
    ];
    $combinedAddress = implode(', ', array_filter($addressParts));
    
    $data = [
        'first_name' => $_POST['firstName'],
        'last_name'  => $_POST['lastName'],
        'email'      => $_POST['email'],
        'phone'      => $phoneNumber,
        'address'    => $combinedAddress,
        'password'   => $_POST['password']
    ];

    $userId = $userModel->register($data);
    $token = bin2hex(random_bytes(16));
    $userModel->saveVerificationToken($userId, $token);

    // $verifyLink = "https://venusia.great-site.net/actions/verify.php?user=$userId&token=$token";

    // local testing
    $verifyLink = "http://localhost/PHP-Final-Project/actions/verify.php?user=$userId&token=$token";

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
        $mail->addAddress($data['email'], $data['first_name']);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Verify your Venusia account';
        $mail->Body    = "
            <html>
            <body>
                <p>Thank you for registering with Venusia!</p>
                <p>Click the link below to verify your account (valid for 5 minutes):</p>
                <p><a href='$verifyLink'>$verifyLink</a></p>
                <p><strong>Important:</strong> This link will expire in 5 minutes for security reasons.</p>
            </body>
            </html>
        ";

        $mail->send();
        header('Location: ../index.php?page=email_sent');
        exit;
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";

        // log failure for debugging
        file_put_contents(__DIR__ . '/../mail_error.log', $mail->ErrorInfo . "\n", FILE_APPEND);
    }
}
