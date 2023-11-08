<?php

include_once("db.php");

$sql = "DELETE FROM `place_appointment` WHERE `placebook_id`='" . $_GET["placebook_id"] . "'";
if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Delete Success');
    window.location.href='home.php';</script>";
} else {
    echo "Error deleting record: " . mysqli_error($conn);
}
mysqli_close($conn);

?>