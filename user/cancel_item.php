<?php

include_once("db.php");

echo $sql = "DELETE FROM `item_appointment` WHERE `item_id`='" . $_GET["item_id"] . "'";
if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Delete Success'); window.location.href='home.php';</script>";
} else {
    echo "Error deleting record: " . mysqli_error($conn);
}

mysqli_close($conn);

?>