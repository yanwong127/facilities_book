<?php
include_once('db.php');

$item_id = $_GET['id'];
$item_id = $_POST['item_id'];

if (isset($_GET['id'])) {
    $item_id = $_GET['id'];

    $query = "SELECT * FROM `item` WHERE item_id = $item_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $item_id = $row["item_id"];
        $item_name = $row['item_name'];
        $item_img = $row['item_img'];

        echo "<h1>Item Details</h1>";
        echo "<p>Item Name: $item_name</p>";
        echo "<img src='img/$item_img' alt='$item_name'>";

    } else {
        echo "Item not found.";
    }
} else {
    echo "Item ID not provided.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Item Details</title>
</head>
<body>
    
</body>
</html>
