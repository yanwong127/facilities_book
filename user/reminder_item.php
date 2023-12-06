<?php
// Connect to the database
include_once("db.php");

require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

date_default_timezone_set('Asia/Kuala_Lumpur');

$userQuery = "SELECT * FROM user";
$userResult = $conn->query($userQuery);

if ($userResult && $userResult->num_rows > 0) {
    while ($userRow = $userResult->fetch_assoc()) {
        $userId = $userRow['user_id'];

       echo  $qry = "SELECT * FROM item_appointment
            WHERE user_id = $userId
            AND status = 'Approve'
            AND DATE(booking_date) = CURDATE()
            ORDER BY start_time ASC";

        // Execute the query
        $sttr = $conn->query($qry);

        // Check if there are appointments
        if ($sttr && $sttr->num_rows > 0) {
            while ($row = $sttr->fetch_assoc()) {
                $appointmentTime = strtotime($row['start_time']);
                $currentTime = time();
                $eightHoursBefore = strtotime("-1 hours", $appointmentTime);

                echo 'Appointment Time: ' . date('Y-m-d H:i:s', $appointmentTime) . "<br>";
                echo 'Current Time: ' . date('Y-m-d H:i:s', $currentTime) . "<br>";
                echo 'half Hours Before: ' . date('Y-m-d H:i:s', $eightHoursBefore) . "<br>";

                if (time() >= $eightHoursBefore && time() <= $appointmentTime) {
                    // Perform actions like sending an email
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'chankelvin53@gmail.com';
                        $mail->Password = 'iiezptdcgwhjteyy';
                        $mail->SMTPSecure = 'tls';
                        $mail->Port = 587;

                        $userEmail = $userRow['email'];
                        $mail->setFrom('chankelvin53@gmail.com', 'Chan');
                        $mail->addAddress($userEmail);
                        $mail->isHTML(true);
                        $mail->Subject = 'Booking Confirmation';
                        $mail->Body = 'Testing' . '<br>';
                        $mail->Body .= 'Item: ' . $row['item_name'] . '<br>';

                        $mail->send();
                        // Update the appointment record, marking it as sent
                        $updateStmt = $conn->prepare("UPDATE item_appointment SET email_sent = NOW() WHERE id = ?");
                        $updateStmt->bind_param('i', $row['id']);
                        $updateStmt->execute();
                    } catch (Exception $e) {
                        echo '<script>alert("Error sending email. Please try again later.");</script>';
                    }
                } else {
                    echo 'Not yet time to send email.';
                }
            }
        } else {
            echo 'No appointments within the one-hour range.';
        }
    }
}
?>
