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
     
    <title>Login</title>
    <style>
        body {
            background: #F4CE14;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: orange;
            text-align: center;
            margin: 0;
            /* border: 2px solid #ccc;  */
            padding: 20px; 
            border-radius: 10px;
        }

        .login-text {
          border-radius: 10px;
          height: 20px
        }

        #user {
          font-size: 50px;
          margin: 10px;
        }                                                                                                                                                                                                      

        .forgot-password {
            margin-top: 10px;
            text-align: left;
        }

        label {
        color: white;
        }

        .custom-button {
        background-color: #CE5A67; 
        width: 100%;
        color: white; 
        border: none; 
        border-radius: 20px; 
        padding: 10px 20px; 
        cursor: pointer; 
        }

        .custom-button:hover {
        background-color: #FF0000; 
        }

    </style>
</head>

<body>
    <div class="login-container">
        <form action="login.php" method="post">
            <table>
                <i class="fa fa-user-circle" id="user" aria-hidden="true"></i>

                <tr>
                    <td><label for="username">Name: </label></td>
                    <td><input type="text" name="username" class="login-text" required></td>
                </tr>
              
                <tr>
                    <td><label for="userpass">Password: </label></td>
                    <td><input type="password" class="login-text" name="password" required></td>
                </tr>

            </table>

        <div class="forgot-password">
            <a href="forget.php">Forgot Password</a>
        </div>

        <div>
            <p>Don't have an account? <a href="signup.php">Register here</a></p>
        </div>

          <td>
              <input type="submit" name="submit" value="Login" class="custom-button">
          </td>


    </div>
    </form>
</body>
</html>
