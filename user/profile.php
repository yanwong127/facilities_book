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
 
</head>

<body>
<br>
<br>
<div class="ctable">
    <div class="profile-panel">
        <?php while ($row = mysqli_fetch_array($sql)) { ?>
            <div class="profile-details">
                    <div class="profile-picture"><img  src="img/8TeOoJYXcFmL2x5jDuFdPwpsh351X9N_iphzhFVpJM8hLmuvVEyh-CCWkrVZHCD83BQ.webp"></div>
                    <div class="profile-name"><?= $row['username'] ?></div>
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
                    <form method="post" class="form">
                    <input type="text" name="text" autocomplete="off" required />
                    <label for="text" class="label-name">
                        <span class="content-name">
                        Your New Password
                        </span>
                    
                    </label>
                    </form>
                    <button class="btn" type="submit" name="change_password" value="New Password">Change Password</button>

                </div>
            </div>
        <?php } ?>
    </div>
</body>

</html>
<style>
    body {
    background-image: url('img/profile.jpg'); /* 替换为您的图片路径 */
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
    margin: 0;
}

    .header {
    position: relative;
     z-index: 1; /* Set a value smaller than varbar's z-index */
}
    .ctable {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 50vh;
}

.profile-panel {
    max-width: 400px;
    margin: 0 auto;
    border: 2px solid #525352;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, rgba(255, 255, 255, 0.08) 0px 1px 0px inset;

}

.profile-picture {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    margin: auto;
    background-color: #ddd;
    overflow: hidden; /* Ensure the image doesn't overflow the container */
}

.profile-picture img {
    width: 100%; /* Make the image fill the container */
    height: auto;
    border-radius: 50%;
}

.profile-name {
    font-size: 1.8em; /* Increased font size for emphasis */
    font-weight: bold;
    text-align: center;
    margin: 15px 0; /* Increased margin for spacing */
}

.right-column {
    display: flex;
    flex-direction: column;
    align-items: center; /* Center align profile info */
}

.profile-info {
    margin-bottom: 15px; /* Increased margin for spacing */
    text-align: ; /* Center align profile info */
}

.profile-info label {
    font-weight: bold;
}

.profile-info span {
    margin-left: 5px; /* Adjusted margin for spacing */
}

.btn {
    background-color: #525352;
    width: 100%;
    border: none;
    color: #fff;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin-top: 15px; /* Adjusted margin for spacing */
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s; /* Added transition for a smoother hover effect */
}

.btn:hover {
    background-color: #525352;
}


.form {
  width: 100%;
  position: relative;
  height: 60px;
  overflow: hidden;
}

.form input {
  width: 100%;
  height: 100%;
  color:;
  padding-top: 20px;
  border: none;
}
.form label {
  position: absolute;
  bottom: 0px;
  left: 0px;
  width: 100%;
  height: 100%;
  pointer-events: none;
  border-bottom: 1px solid white;
}
.form label::after {
  content: "";
  position: absolute;
  bottom: -1px;
  left: 0px;
  width: 100%;
  height: 100%;
  border-bottom: 3px solid #fce38a;
  transform: translateX(-100%);
  transition: all 0.3s ease;
}

.content-name {
  position: absolute;
  bottom: 0px;
  left: 0px;
  padding-bottom: 5px;
  transition: all 0.3s ease;
}
.form input:focus {
  outline: none;
}
.form input:focus + .label-name .content-name,
.form input:valid + .label-name .content-name {
  transform: translateY(-150%);
  font-size: 14px;
  left: 0px;
  color: #fce38a;
}
.form input:focus + .label-name::after,
.form input:valid + .label-name::after {
  transform: translateX(0%);
}

</style>
