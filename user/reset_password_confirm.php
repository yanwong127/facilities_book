<?php
include 'db2.php';

function isVerificationCodeValid($email, $code) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email AND verification_code = :code AND verification_code_expiration > NOW()");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':code', $code);
    $stmt->execute();
    return $stmt->rowCount() > 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $code = $_POST['code'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (isVerificationCodeValid($email, $code)) {
        if ($password === $confirmPassword) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $updateStmt = $pdo->prepare("UPDATE user SET password = :password, verification_code = NULL, verification_code_expiration = NULL WHERE email = :email");
            $updateStmt->bindParam(':password', $hashedPassword);
            $updateStmt->bindParam(':email', $email);

            if ($updateStmt->execute()) {
                $confirmationMessage = "Password reset successfully. You can now login with your new password.";
            } else {
                $errorMessage = "Error updating password. Please try again.";
            }
        } else {
            $errorMessage = "Passwords do not match.";
        }
    } else {
        $errorMessage = "Invalid verification code. Please check your code or request a new one.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Confirmation</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans:400,400i,700,700i&subset=greek-ext">
    <style>
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
            height: 100vh;
        }

        .fa-user-circle {
            font-size: 46px;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.221);
            border-radius: 3px;
            padding: 15px 50px;
            display: inline-block;
            text-align: center;
        }

        .form-container {
            max-width: 300px;
            margin: 0 auto;
        }

        h2 {
            color: #333333;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input {
            border: 0;
            border-bottom: 1px solid #131111;
            background: transparent;
            width: 100%;
            padding: 8px 0 5px 0;
            font-size: 16px;
            color: #120e0e;
            outline: none;
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        button {
            color: #ede5e5;
            background-color: #454343;
            outline: none;
            border: 0;
            padding: 10px 20px;
            text-transform: uppercase;
            border-radius: 2px;
            cursor: pointer;
            width: 100%;
            background-color: #4caf50;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .confirmation-message {
            color: #4caf50;
            font-weight: bold;
        }

        .error-message {
            color: #ff0000;
            font-weight: bold;
        }

        a {
            text-decoration: none;
            color: #1e87f0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <?php if (isset($confirmationMessage)) : ?>
                <p class="confirmation-message"><?php echo $confirmationMessage; ?></p>
                <p>You can now <a href="login.php">login with your new password</a>.</p>
            <?php else : ?>
                <?php if (isset($errorMessage)) : ?>
                    <p class="error-message"><?php echo $errorMessage; ?></p>
                <?php endif; ?>
                <form action="" method="post">
                    <h2>Reset Password Confirmation</h2>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>" required>
                    <label for="code">Verification Code:</label>
                    <input type="text" id="code" name="code" required>
                    <label for="password">New Password:</label>
                    <input type="password" id="password" name="password" required>
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <button type="submit">Reset Password</button>
                    <button type="button" style="margin-top: 10px;" onclick="window.location.href='login.php'">Back</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>