<?php

include_once("db.php");

echo $sql = "DELETE FROM `item_appointment` WHERE `itembook_id`='" . $_GET["itembook_id"] . "'";
if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Delete Success'); window.location.href='booking_item.php';</script>";
} else {
    echo "Error deleting record: " . mysqli_error($conn);
}

mysqli_close($conn);

?>