<?php
session_start();

include_once('db.php');

if (isset($_POST['submit'])) {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $userpass = $_POST['password'];

        $query = "SELECT user_id, password FROM user WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($userpass, $row['password'])) {
                $_SESSION['true'] = true;
                $_SESSION['user_id'] = $row['user_id'];

                header("location: home.php");
                exit;
            } else {
                echo "<script>alert('Login Fail! Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Login Fail! User not found.');</script>";
        }
    } else {
        echo "<script>alert('Please insert both username and password.');</script>";
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
    <title>Login</title>
</head>

<body>
    <div class="login-container">
        <form action="login.php" method="post">
            <div class="box">
                <i class="fa fa-user-circle" id="user" aria-hidden="true"></i>
                <br>
                <div class="input-container">
                <div>
                    <label for="username" class="fa fa-user"> Name: </label>
                    <input type="text" name="username" class="login-text" required>
                </div>
                <br>
                <div>
                    <label for="userpass" class="fa fa-lock"> Password: </label>
                    <input type="password" class="login-text" name="password" required>
                </div>
                </div>
            
                <div class="forget-password">
                    <a href="forget.php">Forget Password</a>
                </div>
                <div class="btn-container">
                    <input type="submit" name="submit" value="Login" class="custom-button">
                </div>
            </div>
        </form>
        <p class="text-center">Don't have an account? <a href="signup.php">Register here</a></p>
    </div>
</body>

</html>
