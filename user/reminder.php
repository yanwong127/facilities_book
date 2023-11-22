<?php
include_once('db.php');

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'db2.php';

#Item
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mailTo = $email;
    // $email2 = "SELECT * FROM `user` WHERE email = :email";
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $verificationCode = generateToken(6);

        $qry="SELECT *,DATEDIFF(`start_time`,'".date("Y-m-d")."') as day FROM `item_appointment` where DATE(start_time)>='".date("Y-m-d")."' ORDER BY start_time asc";
        $sttr=mysqli_query($conn,$qry);

        $updateStmt = $pdo->prepare("UPDATE `item_appointment` SET email_sent = :time = NOW() + INTERVAL 10 MINUTE WHERE email = :email");

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


// $body = "Your Booking TIme is Going To End ";
// function generateToken($length = 20) {
//     return bin2hex(random_bytes($length));
// }


#Place
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mailTo2 = $email2;
    // $email2 = "SELECT * FROM `user` WHERE email = :email";
    $email2 = $_POST['email'];

    $stmt2 = $pdo->prepare("SELECT * FROM user WHERE email = :email");
    $stmt2->bindParam(':email', $email);
    $stmt2->execute();

    if ($stmt2->rowCount() > 0) {
        $verificationCode2 = generateToken(6);

        $updateStmt2 = $pdo->prepare("UPDATE `place_appointment` SET email_sent = :time = NOW() + INTERVAL 10 MINUTE WHERE email = :email");

        $updateStmt2->bindParam(':code', $verificationCode2);
        $updateStmt2->bindParam(':email', $email2);

        if ($updateStmt->execute()) {
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail2->isSMTP();
                $mail2->Host       = 'smtp.gmail.com';
                $mail2->SMTPAuth   = true;
                $mail2->Username   = 'chankelvin53@gmail.com';  
                $mail2->Password   = 'iiezptdcgwhjteyy';   
                $mail2->SMTPSecure = 'tls';
                $mail2->Port       = 587;

                //Recipients
               $mail2->setFrom('chankelvin53@gmail.com', 'Chan'); 
                $mail2->addAddress($email); 

                // Content
                $mail2->isHTML(true);
                $mail2->Subject = 'Password Reset Verification Code';
                $mail2->Body    = "Your verification code is: $verificationCode";

                $mail2->send();

           
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