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

    <div class="container mt-5 profile-box">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <?php while ($row = mysqli_fetch_array($sql)) { ?>
                    <p><?= $row['username'] ?></p>
                    <p><?= $row['address'] ?></p>
                    <p><?= $row['email'] ?></p>
                    <p><?= $row['phone'] ?></p>
                <?php } ?>
            </div>
        </div>

        <div class="row justify-content-center mt-3">
            <div class="col-md-4">
                <!-- Add your form here -->
                <form method="post" class="text-center">
                    <input type="password" name="new_password" placeholder="New Password" class="form-control">
                    <button type="submit" name="change_password" class="btn btn-primary mt-2">Change Password</button>
                </form>
            </div>
        </div>
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