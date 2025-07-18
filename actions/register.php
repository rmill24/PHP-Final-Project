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
    $data = [
        'first_name' => $_POST['firstName'],
        'last_name'  => $_POST['lastName'],
        'email'      => $_POST['email'],
        'phone'      => $_POST['phone'],
        'address'    => $_POST['address'],
        'password'   => $_POST['password']
    ];

    $userId = $userModel->register($data);
    $token = bin2hex(random_bytes(16));
    $userModel->saveVerificationToken($userId, $token);

    // $verifyLink = "https://venusia.great-site.net/actions/verify.php?user=$userId&token=$token";

    // local testing
    $verifyLink = "localhost/PHP-Final-Project/actions/verify.php?user=$userId&token=$token";

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
                <p>Click the link below to verify your account:</p>
                <p><a href='$verifyLink'>$verifyLink</a></p>
            </body>
            </html>
        ";

        $mail->send();
        echo "✅ Verification email sent! Check your inbox.";
    } catch (Exception $e) {
        echo "❌ Email could not be sent. Mailer Error: {$mail->ErrorInfo}";

        // log failure for debugging
        file_put_contents(__DIR__ . '/../mail_error.log', $mail->ErrorInfo . "\n", FILE_APPEND);
    }
}
