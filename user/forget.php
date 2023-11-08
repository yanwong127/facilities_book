<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
          body {
            background: #F4CE14;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        h2 {
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #CE5A67; 
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <p>Enter your email address to reset your password.</p>
        <form action="#" method="post">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" required>
            <input type="submit" value="Reset Password">
            <input type="submit" value="Back" onclick="window.location.href='login.php'">
        </form>
    </div>
</body>
</html>
