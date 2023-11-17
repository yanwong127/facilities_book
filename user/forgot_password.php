<?php
require 'vendor/autoload.php';

// Use the PHPMailer classes from Composer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include the database connection script
include 'db2.php';

// Function to generate a unique token
function generateToken($length = 20) {
    return bin2hex(random_bytes($length));
}

// Handle the form submission for sending verification code
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Email exists, generate a unique verification code
        $verificationCode = generateToken(6);

        // Update the user's record with the verification code and a timestamp
        $updateStmt = $pdo->prepare("UPDATE user SET verification_code = :code, verification_code_expiration = NOW() + INTERVAL 10 MINUTE WHERE email = :email");

        $updateStmt->bindParam(':code', $verificationCode);
        $updateStmt->bindParam(':email', $email);

        if ($updateStmt->execute()) {
            // Send an email with the verification code using PHPMailer
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'chankelvin53@gmail.com';  // Replace with your Gmail email address
                $mail->Password   = 'iiezptdcgwhjteyy';   // Replace with the generated App Password
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                //Recipients
               $mail->setFrom('chankelvin53@gmail.com', 'Chan'); // Replace with your name
                $mail->addAddress($email); // Use the user's email from the database

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Verification Code';
                $mail->Body    = "Your verification code is: $verificationCode";

                $mail->send();

           
    header('Location: reset_password_confirm.php');

                exit();
            } catch (Exception $e) {
                echo '<script>alert("Error sending email. Please try again later.");</script>';
            }
        } else {
            echo '<script>alert("Error updating verification code. Please try again.");</script>';
        }
    } else {
        echo '<script>alert("Email not found. Please check your email address.");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <form action="" method="post">
                <h2>Forgot Password</h2>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <button type="submit">Send Verification Code</button>
                <p>Remember your password? <a href="login.php">Login here</a></p>
            </form>
        </div>
    </div>
</body>
</html>
