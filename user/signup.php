<?php
include_once('db.php');

if (isset($_POST['signup'])) {
    $name = $_POST['name']; 
    $username = $_POST['username'];
    $password = $_POST['password'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (empty($name) || empty($username) || empty($password) || empty($address) || empty($email) || empty($phone)) {
        echo "<script>alert('Please fill in all the fields.');</script>";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO `user` (`name`, `username`, `password`, `address`, `email`, `phone`) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssssss", $name, $username, $hashed, $address, $email, $phone);

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
    <link rel="stylesheet" href="loginsignup.css">
    <link rel="stylesheet" href="loginsignup.css">
    <title>SignUp</title>
</head>

<body>
    <div class="login-container">
    <form action="signup.php" method='post'>
        <div class="box">
            <i class="fa fa-user-circle" id="user" aria-hidden="true"></i>
            <div class="input-container">
                <label for="username" class="fa">Userame:</label>
                <input type="text" name="username" class="text" required>
            </div>
            <div class="input-container">
                <label for="username" class="fa">Password:</label>
                <input type="password" name="password" class="text" required pattern="^(?=.*[A-Z]).{8,}$"
                    title="Password must be at least 8 characters long and contain at least one uppercase letter">
            </div>
            <div class="input-container">
                <label for="username" class="fa">Address:</label>
                <input type="text" name="address" class="text" required>
            </div>
            <div class="input-container">
                <label for="username" class="fa">Full Name:</label>
                <input type="text" name="name" class="text" required>
            </div>
            <div class="input-container">
                <label for="email" class="fa">Email:</label>
                <input type="email" name="email" class="text" required
                    pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}"
                    title="Please enter a valid email address (e.g., yourname@example.com)">
            </div>
            <div class="input-container">
                <label for="phone" class="fa">Phone Number:</label>
                <input type="tel" name="phone" id="phone" class="text" required
                    title="Please enter a valid Malaysian mobile phone number" oninput="formatPhoneNumber(this.value)">
            </div>

            <div>
                <p class="fa">Click here to login. <a href="login.php">Login here</a></p>
            </div>

            <div class="btn-container">
                <input type="submit" name="signup" value="SignUp" class="custom-button">
            </div>
        </div>
    </form>
    </div>


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