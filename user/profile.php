<?php
include_once('db.php');
include_once('header.php');

if ($_SESSION['true'] != true) {
    echo 'not gg';
    header("location:logout.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$user_query = "SELECT * FROM user WHERE `user_id` = $user_id";
$user_result = mysqli_query($conn, $user_query);

if (!$user_result) {
    die("Error: " . mysqli_error($conn));
}

// Server-side validation
$error_message = '';
if (isset($_POST['change_password'])) {
    $new_password = $_POST['new_password'];

    if (empty($new_password)) {
        $error_message = 'Please enter a new password.';
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $update_query = "UPDATE user SET `password` = '$hashed_password' WHERE `user_id` = $user_id";
        $update_result = mysqli_query($conn, $update_query);

        if (!$update_result) {
            die("Error: " . mysqli_error($conn));
        }

        echo '<script>alert("Password changed successfully!");</script>';
    }
}

if (isset($_POST['newImg'])) {  
    $filename = $_FILES['myfile']['name'];

    $destination1 = 'user_img/' . $filename;
    $destination2 = '../admin/img/' . $filename;

    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $file = $_FILES['myfile']['tmp_name'];

    if (!in_array($extension, ['jpg', 'png', 'jpeg'])) {
        echo "You file extension must be .jpg, .png or .jpeg";
    } elseif ($_FILES['myfile']['size'] > 100000000) {
        echo "File too large!";
    } else {
        if (move_uploaded_file($file, $destination1)) {
            if (file_exists($destination1)) {
                if (copy($destination1, $destination2)) {
                    $sql = "UPDATE user SET user_img = '$filename' WHERE user_id = $user_id";
                    if (mysqli_query($conn, $sql)) {
                        // Echo the new image path to update the src attribute
                        header("Location: profile.php");
                    }
                } else {
                    echo "Failed to copy file to the second folder.";
                }
            } else {
                echo "Source file does not exist in the first folder.";
            }
        } else {
            echo "Failed to upload file.";
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>

<body>
    <br>
    <br>
    <div class="ctable">
        <div class="profile-panel">
            <?php while ($row = mysqli_fetch_array($user_result)) { ?>
                <div class="profile-details">

                <form method="post" enctype="multipart/form-data">
    <div class="profile-picture" onclick="triggerFileInput()">
        <img class="rounded-image" id="profileImage" src="user_img/<?= $row['user_img'] ?>">
        <input type="file" id="fileInput" name="myfile" style="display: none;"
            accept=".jpg, .png, .jpeg" onchange="previewImage()">
    </div>
    <button type="submit" name="newImg">Update</button>
</form>


                    <div class="profile-name">
                        <?= $row['username'] ?>
                    </div>
                    <div class="profile-info email">
                        <label>Email:</label>
                        <span>
                            <?= $row['email'] ?>
                        </span>
                    </div>
                    <div class="profile-info phone">
                        <label>Phone:</label>
                        <span>
                            <?= $row['phone'] ?>
                        </span>
                    </div>
                    <div class="profile-info address">
                        <label>Address:</label>
                        <span>
                            <?= $row['address'] ?>
                        </span>
                    </div>
                    <form method="post" class="form">
                        <input type="password" name="new_password" autocomplete="off" required />
                        <label for="text" class="label-name">
                            <span class="content-name">
                                Your New Password
                            </span>

                        </label>
                        <button type="submit" name="change_password">Change Password</button>

                    </form>


                </div>
            <?php } ?>
        </div>
    </div>
</body>

</html>

<script>

function triggerFileInput() {
        document.getElementById('fileInput').click();
    }

    function previewImage() {
        var input = document.getElementById('fileInput');
        var image = document.getElementById('profileImage');
        var reader = new FileReader();

        reader.onload = function (e) {
            image.src = e.target.result;
        };

        reader.readAsDataURL(input.files[0]);
    }

</script>

<style>
    body {
        background-image: url('img/profile.jpg');
        /* 替换为您的图片路径 */
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        margin: 0;
    }

    .header {
        position: relative;
        z-index: 1;
        /* Set a value smaller than varbar's z-index */
    }

    .ctable {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 50vh;
    }

    .profile-panel {
        max-width: 400px;
        margin: 0 auto;
        border: 2px solid #525352;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, rgba(255, 255, 255, 0.08) 0px 1px 0px inset;

    }

    .profile-picture {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        margin: auto;
        background-color: #ddd;
        overflow: hidden;
        /* Ensure the image doesn't overflow the container */
    }

    .profile-picture img {
        width: 100%;
        /* Make the image fill the container */
        height: auto;
        border-radius: 50%;
    }

    .profile-name {
        font-size: 1.8em;
        /* Increased font size for emphasis */
        font-weight: bold;
        text-align: center;
        margin: 15px 0;
        /* Increased margin for spacing */
    }

    .right-column {
        display: flex;
        flex-direction: column;
        align-items: center;
        /* Center align profile info */
    }

    .profile-info {
        margin-bottom: 15px;
        /* Increased margin for spacing */
        text-align: ;
        /* Center align profile info */
    }

    .profile-info label {
        font-weight: bold;
    }

    .profile-info span {
        margin-left: 5px;
        /* Adjusted margin for spacing */
    }

    .btn {
        background-color: #525352;
        width: 100%;
        border: none;
        color: #fff;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin-top: 15px;
        /* Adjusted margin for spacing */
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s;
        /* Added transition for a smoother hover effect */
    }

    .btn:hover {
        background-color: #525352;
    }


    .form {
  width: 100%;
  position: relative;
  height: 60px;
  overflow: hidden; 
}

.form input {
  width: 100%;
  height: 100%;
  color:;
  padding-top: 20px;
  border: none;
}
    .form label {
        position: absolute;
        bottom: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
        pointer-events: none;
        border-bottom: 1px solid white;
    }

    .form label::after {
        content: "";
        position: absolute;
        bottom: -1px;
        left: 0px;
        width: 100%;
        height: 100%;
        border-bottom: 3px solid #fce38a;
        transform: translateX(-100%);
        transition: all 0.3s ease;
    }

    .content-name {
        position: absolute;
        bottom: 0px;
        left: 0px;
        padding-bottom: 5px;
        transition: all 0.3s ease;
    }

    .form input:focus {
        outline: none;
    }

    .form input:focus+.label-name .content-name,
    .form input:valid+.label-name .content-name {
        transform: translateY(-150%);
        font-size: 14px;
        left: 0px;
        color: #fce38a;
    }

    .form input:focus+.label-name::after,
    .form input:valid+.label-name::after {
        transform: translateX(0%);
    }
</style>