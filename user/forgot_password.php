<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'db2.php';

function generateToken($length = 20) {
    return bin2hex(random_bytes($length));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $verificationCode = generateToken(6);

        $updateStmt = $pdo->prepare("UPDATE user SET verification_code = :code, verification_code_expiration = NOW() + INTERVAL 10 MINUTE WHERE email = :email");

        $updateStmt->bindParam(':code', $verificationCode);
        $updateStmt->bindParam(':email', $email);

        if ($updateStmt->execute()) {
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'chankelvin53@gmail.com';  
                $mail->Password   = 'iiezptdcgwhjteyy';   
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                //Recipients
               $mail->setFrom('chankelvin53@gmail.com', 'Chan'); 
                $mail->addAddress($email); 

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
    <link rel="stylesheet" href="loginsignup.css">

</head>
<body>
    <div>
        <form action="forget_password" method="post">
            <h2>Forgot Password</h2>
            <div class="input-container">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <input type="submit" name="submit" value="Send Verification Code"></input>
            </div>
            <br>
            <div>
                <button type="submit" class="button" onclick="window.location.href='login.php'">Back</button>
            </div>
            
            
        </form>
        </div>
    </div>
</body>
</html>
