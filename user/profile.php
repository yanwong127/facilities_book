<?php

include_once('db.php');
include_once('header.php');

if ($_SESSION['true'] != true) {
    echo 'not gg';
    header("location:logout.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$user = "SELECT * FROM user WHERE `user_id` = $user_id";
$sql = mysqli_query($conn, $user);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to send verification email
function sendVerificationEmail($recipient_email, $verification_code)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0; // Set to 2 for debugging
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Your SMTP server details
        $mail->SMTPAuth = true;
        $mail->Username = 'your_username';
        $mail->Password = 'your_password';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        //Sender
        $mail->setFrom('your_email@example.com', 'Your Name');

        //Recipient
        $mail->addAddress($recipient_email);

        //Email content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Verification';
        $mail->Body = 'Click the following link to verify your password reset: <a href="http://yourwebsite.com/verify_password.php?code=' . $verification_code . '">Verify Password Reset</a>';

        $mail->send();
    } catch (Exception $e) {
        // Handle any exceptions here, e.g., log an error message
    }
}

// Check if the user clicked the "Change Password" button
if (isset($_POST['change_password'])) {
    $new_password = $_POST['new_password'];
    // Generate a unique verification code, store it in the database, and send an email
    $verification_code = md5(uniqid(rand(), true));
    // Store the verification_code in the database for this user
    $user_id = $_SESSION['user_id'];
    // Perform a database update to store the verification_code for this user

    // Send the verification email
    $user_email = $row['email']; // You need to fetch the user's email from the database
    sendVerificationEmail($user_email, $verification_code);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>

<body>


    <div class="ctable">
        <?php while ($row = mysqli_fetch_array($sql)) { ?>
            <?= $row['username'] ?>
            <?= $row['address'] ?>
            <?= $row['email'] ?>
            <?= $row['phone'] ?>
        <?php } ?>

        <div>
            <form method="post">
                <input type="password" name="new_password" placeholder="New Password">
                <button type="submit" name="change_password">Change Password</button>
            </form>
        </div>

    </div>


    <?php
    include_once('footer.php');
    ?>

</body>

</html>

<style>
    .ctable {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 50vh;
    }
</style>