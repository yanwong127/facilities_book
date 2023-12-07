<?php
include_once('db.php');
include_once('header.php');

$user_id = $_SESSION['user_id'];
$records_per_page = 3;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $records_per_page;

// $statusUpdated = false;  

$current_time = date('Y-m-d H:i:s');

// Query for items
$item_query = "
    SELECT 'item' as type, ia.itembook_id as book_id, ia.item_img as img, ia.item_name as name, ia.booking_date, ia.start_time, ia.end_time, ia.status
    FROM `item_appointment` ia
    WHERE ia.`user_id` = $user_id AND ia.`status` = 'Approve'
    LIMIT $offset, $records_per_page
";

$item_result = mysqli_query($conn, $item_query);
$alertShown = false; 

while ($row = mysqli_fetch_array($item_result)) {
    $end_time = $row['end_time'];
    $expiration_time = strtotime($end_time);

    if ($current_time > $expiration_time) {
        $itembook_id = $row['book_id'];
        $update_query = "UPDATE `item_appointment` SET `status` = 'Expired' WHERE `itembook_id` = $itembook_id";
        mysqli_query($conn, $update_query);

        if (!$alertShown) {
            echo "<script>alert('Appointment with item has expired.'); location.reload();</script>";
            $alertShown = true; 
        }
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

<body>
    <br>
    <br>
    <br>
    <br>

    <div style="display: flex;">
        <a class="button-48" href="result_item.php" role="button"><span class="text">Item</span></a>
        <a class="button-48" href="result_place.php" role="button"><span class="text">Place</span></a>
    </div>

    <div class="ctable">
        <?php if (mysqli_num_rows($item_result) > 0) { ?>
            <table>

            </table>
        <?php } else { ?>
            <div class="no-appointments">
                <p>No appointments found.</p>
                <p>Feel free to schedule new appointments!</p>
            </div>
        <?php } ?>

        <table>
            <?php while ($row = mysqli_fetch_array($item_result)) { ?>
            
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

    <dialog id="editDialog">
        <button autofocus>Close</button>
        <h2 id="editDialogTitle"></h2>
        <div class="container">
            <form class="form-horizontal" action="result_item.php" method="post">
                <input type="hidden" name="itembook_id" id="editItembookId">
                <div>
                    <label>Booking Date:</label>
                    <div>
                        <input type="date" name="booking_date" id="editBookingDate" required>
                    </div>
                </div>
                <div>
                    <label>Start Time:</label>
                    <div>
                        <input type="time" name="start_time" id="editStartTime" required>
                    </div>
                </div>
                <div>
                    <label>End Time:</label>
                    <div>
                        <input type="time" name="end_time" id="editEndTime" required>
                    </div>
                </div>
                <br />
                <div>

                </div>
            </form>
        </div>
    </dialog>



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