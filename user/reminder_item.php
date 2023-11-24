<?php 

include_once("db.php");

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'db2.php';

# Item
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Get the current time
        $currentTime = date("Y-m-d H:i:s");

        // Calculate the time 8 hours from now
        $reminderTime = date("Y-m-d H:i:s", strtotime("+8 hours", strtotime($currentTime)));

        // Fetch appointments where start_time is within the 8-hour range
        $qry = "SELECT * FROM `item_appointment` WHERE email = :email AND start_time > '$currentTime' AND start_time <= '$reminderTime' AND email_sent IS NULL ORDER BY start_time ASC";
        $sttr = $pdo->prepare($qry);
        $sttr->bindParam(':email', $email);
        $sttr->execute();

        if ($sttr) {
            while ($row = $sttr->fetch(PDO::FETCH_ASSOC)) {
                // The appointment is within the 8-hour range and email not sent, send the email
                $updateStmt = $pdo->prepare("UPDATE `item_appointment` SET email_sent = NOW() + INTERVAL 10 MINUTE WHERE id = :id");

                $updateStmt->bindParam(':id', $row['id']);
                $updateStmt->execute();

                $verificationCode = generateToken(6);

                $mail = new PHPMailer(true);

                try {
                    // Your email sending code ...

                    // Remove the redirection inside the loop, as it might not be the desired behavior

                } catch (Exception $e) {
                    echo '<script>alert("Error sending email. Please try again later.");</script>';
                }
            }
        } else {
            echo '<script>alert("No appointments within the 8-hour range.");</script>';
        }
    } else {
        echo '<script>alert("Email not found. Please check your email address.");</script>';
    }
}



?>