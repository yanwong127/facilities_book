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

<style>
    body {
            background: #F4CE14;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
         }

         
        .signup-container {
            background: orange;
            text-align: center;
            margin: 0;
            padding: 20px; 
            border-radius: 10px;
        }

        .signup-text {
          border-radius: 10px;
          height: 20px
        }

        label {
        color: white;
        }
        
        .loginpage {
            margin-top: 10px;
            text-align: left;
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

<body>
    
  
<form action="signup.php" method='post'>
<div class="signup-container">
    <table>
        <tr>
            <td><label for="username">Name:</label></td>
            <td><input type="text" name="username" class="signup-text" required><br></td>
        </tr>
        <tr>
            <td><label for="username">Password:</label></td>
            <td><input type="password" name="password" class="signup-text" required><br></td>
        </tr>
        
        <tr>
            <td><label for="username">Address:</label></td>
            <td><input type="text" name="address" class="signup-text" required><br></td>
        </tr>
        
        <tr>
            <td><label for="username">Email:</label></td>
            <td><input type="text" name="email" class="signup-text" required><br></td>
        </tr>
        
        <tr>
            <td><label for="username">Phone Number:</label></td>
            <td><input type="text" name="phone" class="signup-text" required><br></td>
        </tr>
        
    </table>

        <div>
            <p class="loginpage">Click here to login. <a href="login.php">Login here</a></p>
        </div>

        <tr>
            <td><input type="submit" name="signup" value="SignUp" class="custom-button"></td>
        </tr>


</div>

</form>




</body>
</html>
