<?php
include_once('db.php');

if($_SESSION['true'] != true){
  echo 'not true';
  header("location:login.php");
  exit;
}

if(!empty($_POST["submit_otp"])) {
    $result = mysqli_query($conn,"SELECT * FROM email WHERE otp='" . $_POST["otp"] . "' AND is_expired!=1 AND NOW() <= DATE_ADD(create_at, INTERVAL 24 HOUR)");
    $count  = mysqli_num_rows($result);
    if(!empty($count)) {
        $result = mysqli_query($conn,"UPDATE email SET is_expired = 1 WHERE otp = '" . $_POST["otp"] . "'");
        $success = 2;	
    } else {
        $success =1;
        $error_message = "Invalid OTP!";
    }	
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'kcc304650@gmail.com';                     //SMTP username
    $mail->Password   = 'iodnslaygkcvjijd';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom($_POST['email'] ,'Mailer');
    $mail->addAddress( $_POST['email'],'Sohai');     //Add a recipient              //Name is optional
    $mail->addReplyTo('kcc304650@gmail.com', 'Information');
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mail Function</title>
</head>
<body>

<form name="frmUser" method="post" action="">
	<div class="tblLogin">
		<div class="tableheader">Enter OTP</div>
		<p style="color:#31ab00;">Check your email for the OTP</p>
			
		<div class="tablerow">
			<input type="text" name="otp" placeholder="One Time Password" class="login-input" required>
		</div>
		<div class="tableheader"><input type="submit" name="submit_otp" value="Submit" class="btnSubmit"></div>
	
		<p style="color:#31ab00;">Welcome, You have successfully loggedin!</p>
	</div>
</form>

</body>
</html>