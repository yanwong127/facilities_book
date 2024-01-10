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
    <link rel="stylesheet" href="loginsignup.css">
    <title>Login</title>
    <style>
        #togglePassword {
            cursor: pointer;
            position: absolute;
            right: 560px;
            top: 56.5%;
            transform: translateY(-50%);
            font-size: 16px; 
        }
    </style>
</head>
<body>
    <div>
        <form action="login.php" method="post">
            <div>
            <h1 class="fa" id="user" aria-hidden="true">S.Booking</h1>
            <br>
            <br>
                <div class="input-container">
                    <label for="username"> Name: </label>
                    <input type="text" name="username" class="text" required>
                </div>
                <div class="input-container">
                    <label for="userpass"> Password: </label>
                    <input type="password" name="password" id="passwordField" required>
                    <i class="fa fa-eye" id="togglePassword"></i>
                </div>
                <div>
                    <input type="submit" name="submit" value="Login">
                </div>
                <p><a href="forgot_password.php">Forget Password?</a></p>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputContainers = document.querySelectorAll('.input-container');
            const passwordField = document.getElementById('passwordField');
            const togglePasswordIcon = document.getElementById('togglePassword');

            togglePasswordIcon.addEventListener('click', function() {
                const type = passwordField.type === 'password' ? 'text' : 'password';
                passwordField.type = type;
                togglePasswordIcon.classList.toggle('fa-eye-slash');
            });

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