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
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
        }

        .form-container {
            text-align: center;
        }

        h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            color: #555;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 12px 20px;
            text-transform: uppercase;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background-color: #45a049;
        }

        p {
            font-size: 14px;
            color: #555;
            margin-top: 15px;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <form action="" method="post">
                <h2>Forgot Password</h2>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <button type="submit">Send Verification Code</button><br><br>
                <button type="button" onclick="window.location.href='login.php'">Back</button>
            </form>
        </div>
    </div>
</body>
</html>
