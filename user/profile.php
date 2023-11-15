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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

<div class="ctable">
    <div class="profile-panel">
        <?php while ($row = mysqli_fetch_array($sql)) { ?>
            <div class="profile-details">
                    <div class="profile-picture"></div>
                    <div class="profile-name"><?= $row['username'] ?></div>
                </div>
                <div class="right-column">
                    <div class="profile-info email">
                        <label>Email:</label>
                        <span><?= $row['email'] ?></span>
                    </div>
                    <div class="profile-info phone">
                        <label>Phone:</label>
                        <span><?= $row['phone'] ?></span>
                    </div>
                    <div class="profile-info address">
                        <label>Address:</label>
                        <span><?= $row['address'] ?></span>
                    </div>
                    <form method="post">
                        <input type="password" name="new_password" placeholder="New Password">
                        <button class="btn" type="submit" name="change_password">Change Password</button>
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>

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
    .profile-box {
        background-color: #f8f9fa; 
        padding: 20px; 
        border-radius: 10px; 
        max-width: 50%; 
    }
    
</style>