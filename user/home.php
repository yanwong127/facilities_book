<?php
include_once('db.php');
include_once('header.php');

if ($_SESSION['true'] !== true) {
   header("location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM `item` WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    header("location: unauthorized.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        .place {
            margin-left: 60px;
        }

        .title {
            text-align: center;
        }

        #place-content {
            display: none;
        }
    </style>
</head>

<body>

    <br>
    <div class="place">
        <button type="button" id="showProduct">Product</button>
        <button id="showPlace">Place</button>
    </div>
    <br>

    <div id="item-content">

        <?php
        if (isset($message)) {
            echo $message;
        }
        include_once('item.php');
        include('footer.php');

        ?>
    </div>

    <div id="place-content">
        <?php
        if (isset($message)) {
            echo $message;
        }
        include_once('place.php');
        include('footer.php');
        ?>
    </div>

</body>

</html>

<script>
        var showPlaceButton = document.getElementById('showPlace');
        var showProductButton = document.getElementById('showProduct');

        var itemContent = document.getElementById('item-content');
        var placeContent = document.getElementById('place-content');

        showProductButton.addEventListener('click', function () {
            itemContent.style.display = 'block';
            placeContent.style.display = 'none';
        });

        showPlaceButton.addEventListener('click', function () {
            itemContent.style.display = 'none';
            placeContent.style.display = 'block';
        });
    </script>