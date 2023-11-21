<?php
include_once('db.php');
include_once('header.php');

if ($_SESSION['true'] != true) {
    echo 'not gg';
    header("location:logout.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$user_query = "SELECT * FROM user WHERE `user_id` = $user_id";
$user_result = mysqli_query($conn, $user_query);

if (!$user_result) {
    die("Error: " . mysqli_error($conn));
}

// Server-side validation
$error_message = '';
if (isset($_POST['change_password'])) {
    $new_password = $_POST['new_password'];

    if (empty($new_password)) {
        $error_message = 'Please enter a new password.';
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $update_query = "UPDATE user SET `password` = '$hashed_password' WHERE `user_id` = $user_id";
        $update_result = mysqli_query($conn, $update_query);

        if (!$update_result) {
            die("Error: " . mysqli_error($conn));
        }

        echo '<script>alert("Password changed successfully!");</script>';
    }
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
    <br>
    <br>
    <div class="ctable">
        <div class="profile-panel">
            <?php while ($row = mysqli_fetch_array($user_result)) { ?>
                <div class="profile-details">
                    <img src="img/<?= $row['user_img'] ?>">
                    <div class="profile-name">
                        <?= $row['username'] ?>
                    </div>
                    <div class="profile-info email">
                        <label>Email:</label>
                        <span>
                            <?= $row['email'] ?>
                        </span>
                    </div>
                    <div class="profile-info phone">
                        <label>Phone:</label>
                        <span>
                            <?= $row['phone'] ?>
                        </span>
                    </div>
                    <div class="profile-info address">
                        <label>Address:</label>
                        <span>
                            <?= $row['address'] ?>
                        </span>
                    </div>
                    <form method="post">
                        <input type="password" name="new_password" placeholder="New Password" required>
                        <!-- Display error message if any -->
                        <p style="color: red;">
                            <?= $error_message ?>
                        </p>
                        <button class="btn" type="submit" name="change_password">Change Password</button>
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>
</body>

</html>