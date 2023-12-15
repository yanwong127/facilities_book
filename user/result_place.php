<?php
include_once('db.php');
include_once('header.php');

$user_id = $_SESSION['user_id'];
$records_per_page = 3;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $records_per_page;

// Query for items
$place_query = "
    SELECT 'place' as type, ia.placebook_id as book_id, ia.place_img as img, ia.place_name as name, ia.booking_date, ia.start_time, ia.end_time, ia.status
    FROM `place_appointment` ia
    WHERE ia.`user_id` = $user_id AND ia.`status` = 'Approve'
    LIMIT $offset, $records_per_page
";

$place_result = mysqli_query($conn, $place_query);
$alertShown = false; 
$current_datetime = date('Y-m-d H:i:s');

while ($row = mysqli_fetch_array($place_result)) {
    $end_datetime = $row['end_time'];
    
    // Combine booking_date and end_time to create a datetime string for comparison
    $appointment_datetime = $row['booking_date'] . ' ' . $end_datetime;

    if ($current_datetime > $appointment_datetime) {
        $placebook_id = $row['book_id'];
        $update_query = "UPDATE `place_appointment` SET `status` = 'Expired' WHERE `placebook_id` = $placebook_id";
        mysqli_query($conn, $update_query);

        if (!$alertShown) {
            echo "<script>alert('The venue you had booked has expired.'); location.reload();</script>";
            $alertShown = true; 
        }
    }
}

mysqli_data_seek($place_result, 0);
$place_count_query = "SELECT COUNT(*) FROM `place_appointment` WHERE `user_id` = $user_id AND `status` = 'Approve'";
$place_count_result = mysqli_query($conn, $place_count_query);
$place_row = mysqli_fetch_row($place_count_result);
$place_records = $place_row[0];
$total_place_pages = ceil($place_records / $records_per_page);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place result</title>
 </head>
 <header class="w3-container w3-xlarge">
    <p class="w3-left">Result (Place)</p>
    <p class="w3-right">
        <a href="result_item.php">Item</a>
        <a href="resutlt_place.php">Place</a>
    </p>
  </header>
 <body>
    
 <!-- Display Places Table -->
 <div class="ctable">
 <?php if (mysqli_num_rows($place_result) > 0) { ?>
        <table>
            <!-- ... (Your existing table rows) -->
        </table>
    <?php } else { ?>
        <div class="no-appointments">
            <p>No appointments found.</p>
            <p>Feel free to schedule new appointments!</p>
        </div>
    <?php } ?>

        <table>
            <?php while ($row = mysqli_fetch_array($place_result)) { ?>

                <tr>
                    <td>
                        <img class="rounded-image" src="<?= $row['img'] ?>">
                    </td>
                    <td>
                        <?= $row['name'] ?>
                    </td>
                    <td>
                        <?= $row['booking_date'] ?>
                    </td>
                    <td>
                        <?= $row['start_time'] ?>
                    </td>
                    <td>
                        <?= $row['end_time'] ?>
                    </td>
                    <td>
                        <?= $row['status'] ?>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <!-- Places Pagination -->
        <div class="pagination justify-content-center">
            <?php
            if ($page > 1) {
                echo "<a href='result_place.php?page=" . ($page - 1) . "&type=item'>Prev</a>";
            }

            for ($i = 1; $i <= $total_place_pages; $i++) {
                echo "<a " . ($i == $page ? "class='active'" : "") . " href='result_place.php?page=" . $i . "&type=item'>" . $i . "</a>";
            }

            // Check if there are records in the next page
            $hasNextPage = ($page < $total_place_pages);

            if ($hasNextPage) {
                echo "<a href='result_place.php?page=" . ($page + 1) . "&type=item'>Next</a>";
            } elseif ($page >= $total_place_pages && $total_place_pages > 0) {
                echo "<a class='disabled'>Next</a>";
            }
            ?>
        </div>
    </div>


</body>

</html>

<script>
 

</script>

<style>
    .ctable {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 50vh;
    }

    .rounded-image {
        border-radius: 20px;
        width: 200px;
        height: auto;
    }

    .pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
        margin-top: 20px;
    }

    .pagination a {
        color: black;
        padding: 8px 16px;
        text-decoration: none;
        transition: background-color 0.3s;
    }

    .pagination a.active {
        background-color: dodgerblue;
        color: white;
    }

    .pagination a:hover:not(.active) {
        background-color: #ddd;
    }

    .no-appointments {
        text-align: center;
        padding: 20px;
        border: 2px solid #ccc;
        border-radius: 10px;
        background-color: #f9f9f9;
        margin: 20px;
    }

    .no-appointments p {
        margin: 10px 0;
        font-size: 18px;
        color: #555;
    }
</style>