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
    <link rel="stylesheet" href="styles.css">
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
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
