<?php
include_once('db.php');

if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (empty($username) || empty($password) || empty($address) || empty($email) || empty($phone)) {
        echo "<script>alert('Please fill in all the fields.');</script>";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO `user` (`username`, `password`, `address`, `email`, `phone`) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssss", $username, $hashed, $address, $email, $phone);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>window.location.href = 'login.php';
                alert('Successfully Sign Up.');</script>";
        } else {
            echo "<script>alert('Failed to Sign Up.');</script>";
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
    <title>SignUp</title>
</head>
<body>
<div class="login-container">
        <form action="signup.php" method="post">
            <div class="box">
                <i class="fa fa-user-circle" id="user" aria-hidden="true"></i>
                <br>
                <div class="input-container">
                <div class="input-fort">
                    <label for="username" class="fa fa-user"> Name: </label>
                    <input type="text" name="username" class="login-text" required>
                </div>
                <div class="input-fort">
                    <label for="userpass" class="fa fa-lock"> Password: </label>
                    <input type="password" class="login-text" name="password" required>
                </div>
                <div class="input-fort">
                    <label for="address" class="fa fa-user"> Address: </label>
                    <input type="text" name="address" class="login-text" required>
                </div>
                <div class="input-fort">
                    <label for="email" class="fa fa-user"> Email: </label>
                    <input type="text" name="email" class="login-text" required>
                </div>
                <div class="input-fort">
                    <label for="phone" class="fa fa-user"> Phone Number: </label>
                    <input type="text" name="phone" class="login-text" required>
                </div>
                </div>
            
                <div class="forget-password">
                    <a href="forget.php">Forget Password</a>
                </div>
                <div class="btn-container">
                <input type="submit" name="signup" value="SignUp" class="custom-button">
                </div>
            </div>
        </form>
        <div>
            <p class="loginpage">Click here to login. <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>