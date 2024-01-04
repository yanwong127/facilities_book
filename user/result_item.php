<?php
include_once('db.php');
include_once('header.php');

// Set the timezone to Malaysia
date_default_timezone_set('Asia/Kuala_Lumpur');

$user_id = $_SESSION['user_id'];
$records_per_page = 3;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $records_per_page;

// Query for items
$item_query = "
    SELECT 'item' as type, ia.itembook_id as book_id, ia.item_img as img, ia.item_name as name, ia.booking_date, ia.start_time, ia.end_time, ia.status ,  ia.quantity
    FROM `item_appointment` ia
    WHERE ia.`user_id` = $user_id AND (ia.`status` = 'Approve' OR ia.`status` = 'Cancelled')
    LIMIT $offset, $records_per_page
";

$item_result = mysqli_query($conn, $item_query);
$alertShown = false;
$current_datetime = date('Y-m-d H:i:s');
$one_hour_before_current_time = date('Y-m-d H:i:s', strtotime('-1 hour', strtotime($current_datetime)));

$expiredFound = false;

while ($row = mysqli_fetch_array($item_result)) {

    $end_datetime = $row['end_time'];
    $appointment_datetime = $row['booking_date'] . ' ' . $end_datetime;

    if ($current_datetime > $appointment_datetime) {
        $itembook_id = $row['book_id'];
        $update_query = "UPDATE item_appointment SET status = 'Expired' WHERE itembook_id = $itembook_id";
        mysqli_query($conn, $update_query);

        if (!$alertShown) {
            echo "<script>alert('Items in your booking have expired.'); location.reload();</script>";
            $alertShown = true;
        }

        if ($expiredFound) {
            break;
        }
    }

    $start_datetime = $row['start_time'];
    $reminder_datetime = $row['booking_date'] . ' ' . $start_datetime;

    "Current Datetime (Malaysia): " . date('Y-m-d H:i:s') . "<br>";
    "Reminder Datetime (Malaysia): $reminder_datetime<br>";

    $one_hour_before_booking_time = date('Y-m-d H:i:s', strtotime('-1 hour', strtotime($reminder_datetime)));
    "One Hour Before Booking Datetime (Malaysia): $one_hour_before_booking_time<br>";

    if (!$alertShown && $current_datetime > $one_hour_before_booking_time && $current_datetime < $reminder_datetime) {
        $itemName = $row['name'];
        echo "<script>alert('Reminder: Your item booking for $itemName is starting soon.');</script>";
        // $alertShown = true;
    }


    if ($expiredFound) {
        break;
    }
}

mysqli_data_seek($item_result, 0);
$item_count_query = "SELECT COUNT(*) FROM `item_appointment` WHERE `user_id` = $user_id AND `status` = 'Approve'";
$item_count_result = mysqli_query($conn, $item_count_query);
$item_row = mysqli_fetch_row($item_count_result);
$item_records = $item_row[0];
$total_item_pages = ceil($item_records / $records_per_page);
?>




<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<header class="w3-container w3-xlarge">
    <p class="w3-left">Result Item</p>
    <p class="w3-right">
        <button class="btn" onclick="location.href='result_item.php'">ITEM</button>
        <button class="btn" onclick="location.href='result_place.php'">PLACE</button>
    </p>
</header>

<body>

    <div class="ctable">
        <?php if (mysqli_num_rows($item_result) > 0) { ?>
            <table class="w3-table-all w3-card-4">
                <thead>
                    <tr class="w3-light-grey">
                        <th>Image</th>
                        <th>Name</th>
                        <th>Booking Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Quantity</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_array($item_result)) { ?>
                        <tr>
                            <td><img class="rounded-image" src="<?= $row['img'] ?>" alt="<?= $row['name'] ?>"></td>
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
                                <?= $row['quantity'] ?>
                            </td>
                            <td>
                                <?= $row['status'] ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="no-appointments">
                <p>No appointments found.</p>
                <p>Feel free to schedule new appointments!</p>
            </div>
        <?php } ?>

        </table>

        <!-- Items Pagination -->
        <div class="pagination justify-content-center">
            <?php
            if ($page > 1) {
                echo "<a href='result_item.php?page=" . ($page - 1) . "&type=item'>Prev</a>";
            }

            for ($i = 1; $i <= $total_item_pages; $i++) {
                echo "<a " . ($i == $page ? "class='active'" : "") . " href='result_item.php?page=" . $i . "&type=item'>" . $i . "</a>";
            }

            $hasNextPage = ($page < $total_item_pages);

            if ($hasNextPage) {
                echo "<a href='result_item.php?page=" . ($page + 1) . "&type=item'>Next</a>";
            } elseif ($page >= $total_item_pages && $total_item_pages > 0) {
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

    .btn {
        background-color: #fff;
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





    .w3-sidebar a {
        font-family: "Roboto", sans-serif
    }

    body,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    .w3-wide {
        font-family: "Montserrat", sans-serif;
    }
</style>