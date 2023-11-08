<?php
require 'vendor/autoload.php'; 

$mail = new PHPMailer\PHPMailer\PHPMailer();

$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls'; 
$mail->Host = 'smtp.gmail.com'; 
$mail->Port = 587; 
$mail->Username = 'chankelvin53@gmail.com'; 
$mail->Password = 'your-password'; 

$mail->setFrom('chankelvin53@gmail.com', 'Your Name');

$recipientEmail = 'recipient@example.com';

$mail->addAddress($recipientEmail);

$mail->Subject = 'Password Reset';
$mail->Body = 'Click the link to reset your password: https://yourwebsite.com/reset_password.php?token=YOUR_TOKEN';

if (!$mail->send()) {
    echo 'Email could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Email has been sent.';
}
?>
