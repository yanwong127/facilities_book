<?php
include_once('db.php');

if (isset($_GET['id'])) {
    $place_id = $_GET['id'];

    $query = "SELECT * FROM `place` WHERE place_id = $place_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $placename = $row['placename'];
        $place_img = $row['place_img'];

        echo "<h1>Place Details</h1>";
        echo "<p>Place Name: $placename</p>";
        echo "<img src='img/$place_img' alt='$placename'>";

    } else {
        echo "Place not found.";
    }
} else {
    echo "Place ID not provided.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PLace Details</title>
</head>
<body>


</body>
</html>
