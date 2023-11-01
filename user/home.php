<?php

session_start(); 

include_once('db.php');
include_once('header.php');

if ($_SESSION['true'] !== true) {
    header("location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];asdaldhb

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

</head>
<body>
    <header class="bgimg w3-display-container w3-grayscale-min">
    </header>

    <table>
        <?php while($row = mysqli_fetch_array($result)) { ?>
            <tr>
                <td><?= $row['item_id'] ?></td>
                <td><?= $row['item_name'] ?></td>
                <td><?= $row['quantity'] ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>