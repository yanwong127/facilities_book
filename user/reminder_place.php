<?php
// 连接数据库
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
        $qry = "SELECT * FROM place_appointment
        WHERE email_sent IS NULL AND status = 'Approve'
        AND DATE(booking_date) = CURDATE()
        ORDER BY start_time ASC";
        
        // 假设 $conn 是你的数据库连接
        $sttr = $conn->query($qry);
        
        if ($sttr && $sttr->num_rows > 0) {
            while ($row = $sttr->fetch_assoc()) {
                $appointmentTime = strtotime($row['start_time']);
                $currentTime = time(); // 获取当前时间的时间戳
                $eightHoursBefore = strtotime("-8 hours", $appointmentTime);
                
                echo 'Appointment Time: ' . date('Y-m-d H:i:s', $appointmentTime) . "<br>";
                echo 'Current Time: ' . date('Y-m-d H:i:s', $currentTime) . "<br>";
                echo 'Half Hour Before: ' . date('Y-m-d H:i:s', $eightHoursBefore) . "<br>";
                if (time() >= $eightHoursBefore && time() <= $appointmentTime) {
                    // 进行邮件提醒等操作
                    $mail = new PHPMailer(true);
                    try {
                            // 设置邮件内容、收件人地址等
                            $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'chankelvin53@gmail.com';
                            $mail->Password = 'iiezptdcgwhjteyy';
                            $mail->SMTPSecure = 'tls';
                            $mail->Port = 587;
                                        
                            // 发送邮件
                            $userEmail = $userRow['email'];
                            $mail->setFrom('chankelvin53@gmail.com', 'Chan');
                            $mail->addAddress($userEmail);
                            $mail->isHTML(true);    
                            $mail->Subject = 'Booking Confirmation';
                            $mail->Body = 'Testing' . '<br>';
                            $mail->Body .= 'Place: ' . $row['place_name'] . '<br>';

                            $mail->send();
                                        
                            // 更新预约记录，标记为已发送邮件
                            $updateStmt = $conn->prepare("UPDATE place_appointment SET email_sent = NOW()");
                            $updateStmt->bindParam('i', $row['id']);
                            $updateStmt->execute();
                        } catch (Exception $e) {
                            // 错误处理
                            echo '<script>alert("Error sending email. Please try again later.");</script>';
                        }
                    } else {
                    echo 'Not yet time to send email.';
                }
            }
        } else {
            echo 'No appointments within the half-hour range.';
        }
        
    }
  

}


?>