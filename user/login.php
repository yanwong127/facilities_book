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
    <link rel="stylesheet" href="loginsignup.css">
    <title>Login</title>
</head>
<body>
    <div class="login-container">
        <form action="login.php" method="post">
            <div class="box">
            <i class="fa fa-user-circle" id="user" aria-hidden="true"></i>
                <br>
                <div class="input-container">
                    <label for="username" class="fa"> Name: </label>
                    <input type="text" name="username" class="text" required>
                </div>
                <br>
                <div class="input-container">
                    <label for="userpass" class="fa"> Password: </label>
                    <input type="password" class="text" name="password" required>
                </div>
            
                <div class="btn-container">
                    <input type="submit" name="submit" value="Login" class="custom-button">
                </div>
                <p class="fa"><a href="forgot_password.php">Froget Password?</a></p>
            </div>
        </form>
        <p class="fa">Don't have an account? <a href="signup.php">Register here</a></p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputContainers = document.querySelectorAll('.input-container');

            inputContainers.forEach(container => {
                const input = container.querySelector('input');

                input.addEventListener('focus', () => {
                    container.classList.add('active');
                });

                input.addEventListener('blur', () => {
                    if (input.value === '') {
                        container.classList.remove('active');
                    }
                });
            });
        });
    </script>
</body>
</html>