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
    <link rel="stylesheet" href="home.css">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Noto+Sans:400,400i,700,700i&subset=greek-ext');

        body {
            background-color: rgba(121, 120, 120, 0.221);
            background-position: center;
            background-origin: content-box;
            background-repeat: no-repeat;
            background-size: cover;
            min-height: 100vh;
            font-family: 'Noto Sans', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .fa-user-circle {
            font-size: 46px;
        }

        .signup-container {
            background-color: rgba(0, 0, 0, 0.221);
            border-radius: 3px;
            padding: 70px 100px;
            display: inline-block;
            text-align: center;
            margin: 50px;
            /* Adjust the margin as needed */
        }

        .input-container {
            position: relative;
            margin-bottom: 25px;
            padding: 20px 0px 0px 0px;
        }

        .input-container label {
            font-size: 16px;
            color: #090707;
            pointer-events: none;
            transition: all 0.5s ease-in-out;
            float: left;
        }

        .input-container input {
            border: 0;
            border-bottom: 1px solid #131111;
            background: transparent;
            width: 100%;
            padding: 8px 0 5px 0;
            font-size: 16px;
            color: #120e0e;
            outline: none;
        }

        .input-container input:focus {
            border-bottom: 1px solid #eae0df;
        }

        .btn-container {
            margin-top: 50px;
        }

        .custom-button {
            color: #ede5e5;
            background-color: #454343;
            outline: none;
            border: 0;
            padding: 10px 20px;
            text-transform: uppercase;
            border-radius: 2px;
            cursor: pointer;
            width: 100%;
        }

        .text-center {
            color: #0b0a0a;
            text-transform: uppercase;
            font-size: 15px;
        }
    </style>
    <title>SignUp</title>
</head>

<body>
    <form action="signup.php" method='post'>
        <div class="signup-container">
            <i class="fa fa-user-circle" id="user" aria-hidden="true"></i>
            <div class="input-container">
                <label for="username">Name:</label>
                <input type="text" name="username" class="signup-text" required>
            </div>
            <div class="input-container">
                <label for="username">Password:</label>
                <input type="password" name="password" class="signup-text" required pattern="^(?=.*[A-Z]).{8,}$"
                    title="Password must be at least 8 characters long and contain at least one uppercase letter">
            </div>
            <div class="input-container">
                <label for="username">Address:</label>
                <input type="text" name="address" class="signup-text" required>
            </div>
            <div class="input-container">
                <label for="email">Email:</label>
                <input type="email" name="email" class="signup-text" required
                    pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}"
                    title="Please enter a valid email address (e.g., yourname@example.com)">
            </div>
            <div class="input-container">
                <label for="phone">Phone Number:</label>
                <input type="tel" name="phone" id="phone" class="signup-text" required
                    title="Please enter a valid Malaysian mobile phone number" oninput="formatPhoneNumber(this.value)">
            </div>

            <div>
                <p class="loginpage">Click here to login. <a href="login.php">Login here</a></p>
            </div>

            <div class="btn-container">
                <input type="submit" name="signup" value="SignUp" class="custom-button">
            </div>
        </div>
    </form>


</body>

</html>

<script>
    function formatPhoneNumber(input) {
        var cleanedInput = input.replace(/[-\s]/g, '');

        var match = cleanedInput.match(/^(01[0-46-9]|011|012|013|014|015|016|017|018|019)(\d{7,9})$/);

        if (match) {
            var formattedNumber = match[1] + '-' + match[2];
            document.getElementById('phone').setCustomValidity('');
            document.getElementById('phone').value = formattedNumber;
            document.getElementById('phone').pattern = "^(01[011|012|013|014|015|016|017|018|019)\\d{7,9}$";
        } else {
            document.getElementById('phone').setCustomValidity('Please enter a valid Malaysian mobile phone number');
        }
    }
</script>