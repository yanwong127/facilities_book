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
    <link rel="stylesheet" href="your_stylesheet.css"> <!-- 替换为您的样式表路径 -->
    <title>Login</title>
</head>
<body>
    <div class="login-container">
        <form action="login.php" method="post">
            <div class="box">
                <i class="fa fa-user-circle" id="user" aria-hidden="true"></i>
                <br>
                <div class="input-container">
                    <label for="username" class="fa fa-user"> Name: </label>
                    <input type="text" name="username" class="login-text" required>
                </div>
                <br>
                <div class="input-container">
                    <label for="userpass" class="fa fa-lock"> Password: </label>
                    <input type="password" class="login-text" name="password" required>
                </div>
            
                <div class="btn-container">
                    <input type="submit" name="submit" value="Login" class="custom-button">
                </div>
                <p class="text-center"><a href="forgot_password.php">Froget Password?</a></p>
            </div>
        </form>
        <p class="text-center">Don't have an account? <a href="signup.php">Register here</a></p>
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

<style>
    /* 添加半透明背景 */
    .login-container {
        background-color: rgba(0, 0, 0, 0.5) !important; /* 设置背景颜色和透明度，使用 !important 以确保最高优先级 */
        padding: 20px;
        border-radius: 10px;
        max-width: 400px;
        margin: auto;
        margin-top: 200px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    /* 样式修饰 - 可根据需要进行调整 */
    .box {
        text-align: center;
    }

    .login-text {
        width: 100%;
        padding: 8px;
        margin: 8px 0;
        box-sizing: border-box;
        border: none;
        border-bottom: 1px solid #aaa; /* 一条底边线 */
        outline: none;
        background-color: transparent; /* 透明背景 */
        color: white;
    }

    .custom-button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    /* 添加背景图或者调整颜色以适应您的设计 */
    body {
        background-image: url('img/login.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        margin: 0;
    }

    /* 输入框样式，类似于 "New Password" 的输入框 */
    .input-container {
        position: relative;
        margin-bottom: 20px;
    }

    .input-container label {
        position: absolute;
        top: 0;
        left: 0;
        transition: 0.3s;
        pointer-events: none;
    }

    .input-container.active label {
        top: -20px; /* 向上移动的距离 */
        font-size: 12px; /* 调整字体大小 */
    }
    .fa-user {
    color: white;
}

/* 对应 Password: 的样式 */
.fa-lock {
    color: white;
}

/* 对应 Forget Password 的样式 */
.text-center {
    color: white;
}
.text-center a {
    font-size: 20px;
    color : #f73650;
}
</style>

