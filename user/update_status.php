// update_status.php

<?php
include_once('db.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
$user_id = $_SESSION['user_id'];

// Query for items
$item_query = "
    SELECT ia.itembook_id
    FROM `item_appointment` ia
    WHERE ia.`user_id` = $user_id AND ia.`status` = 'Approve'
";

$item_result = mysqli_query($conn, $item_query);

$statusUpdated = false;  // Flag to check if statuses are updated

// Loop through items and check for expiration
while ($row = mysqli_fetch_array($item_result)) {
    $currentTime = date('Y-m-d H:i:s');
    $endTimeQuery = "SELECT `booking_date`, `end_time` FROM `item_appointment` WHERE `itembook_id` = {$row['itembook_id']}";
    $endTimeResult = mysqli_query($conn, $endTimeQuery);
    $endTimeRow = mysqli_fetch_array($endTimeResult);
    $endTime = $endTimeRow['booking_date'] . ' ' . $endTimeRow['end_time'];

    if ($endTime < $currentTime && $row['status'] !== 'Expired') {
        // Update status to "Expired"
        $updateStatusQuery = "UPDATE `item_appointment` SET `status` = 'Expired' WHERE `itembook_id` = {$row['book_id']}";
        mysqli_query($conn, $updateStatusQuery);
        $statusUpdated = true;
    }    
}

// Return a JSON response
header('Content-Type: application/json');
echo json_encode(['updated' => $statusUpdated]);
?>
