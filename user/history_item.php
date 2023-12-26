<?php
include_once('db.php');
include_once('header.php');

$user_id = $_SESSION['user_id'];
$records_per_page = 3;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $records_per_page;

$item_query = "
    SELECT 'item' as type, ia.itembook_id as book_id, ia.item_img as img, ia.item_name as name, ia.booking_date, ia.start_time, ia.end_time, ia.status, ia.quantity
    FROM `item_appointment` ia
    WHERE ia.`user_id` = $user_id AND (ia.`status` = 'Expired' OR ia.`status` = 'Returned' OR ia.`status` = 'Cancelled')
    LIMIT $offset, $records_per_page
";


$item_result = mysqli_query($conn, $item_query);

$item_count_query = "SELECT COUNT(*) FROM `item_appointment` WHERE `user_id` = $user_id AND `status` = 'Expired'";
$item_count_result = mysqli_query($conn, $item_count_query);
$item_row = mysqli_fetch_row($item_count_result);
$item_records = $item_row[0];
$total_item_pages = ceil($item_records / $records_per_page);


if (isset($_POST['return']) && isset($_POST['itembook_id'])) {
    $returnedItembookId = $_POST['itembook_id'];

    // Update the database status to indicate that the item has been returned
    $updateQuery = "UPDATE `item_appointment` SET `status` = 'Returned' WHERE `itembook_id` = $returnedItembookId";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        // Alert box JavaScript code
        echo "<script>alert('Item returned successfully!');";
        // Redirect to the same page after the update
        echo "window.location.href = 'history_item.php?page=$page&type=item';";
        echo "</script>";
        exit();
    } else {
        // Handle the error, if any
        echo "Error updating database: " . mysqli_error($conn);
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<body>
<header class="w3-container w3-xlarge">
    <p class="w3-left">Place History</p>
    <p class="w3-right">
        <button class="btn" onclick="location.href='history_item.php'">ITEM</button>
        <button class="btn" onclick="location.href='history_place.php'">PLACE</button>
    </p>
  </header>

    <!-- Display Items Table -->
    <div class="ctable">
        <?php if (mysqli_num_rows($item_result) > 0) { ?>
            <table>
                <!-- ... (Your existing table rows) -->
            </table>
        <?php } else { ?>
            <div class="no-appointments">
                <p>No appointments found.</p>
                <p>Feel free to schedule new appointments!</p>
            </div>
        <?php } ?>

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
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['booking_date'] ?></td>
                            <td><?= $row['start_time'] ?></td>
                            <td><?= $row['end_time'] ?></td>
                            <td><?= $row['quantity'] ?></td>
                            <td><?= $row['status'] ?></td>
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
                echo "<a href='history_item.php?page=" . ($page - 1) . "&type=item'>Prev</a>";
            }

            for ($i = 1; $i <= $total_item_pages; $i++) {
                echo "<a " . ($i == $page ? "class='active'" : "") . " href='history_item.php?page=" . $i . "&type=item'>" . $i . "</a>";
            }

            // Check if there are records in the next page
            $hasNextPage = ($page < $total_item_pages);

            if ($hasNextPage) {
                echo "<a href='history_item.php?page=" . ($page + 1) . "&type=item'>Next</a>";
            } elseif ($page >= $total_item_pages && $total_item_pages > 0) {
                echo "<a class='disabled'>Next</a>";
            }
            ?>
        </div>


    </div>

</body>

</html>

<!-- <script>
    const editLinks = document.querySelectorAll(".edit-link");
    const dialog = document.getElementById('editDialog');
    const dialogTitle = document.getElementById('editDialogTitle');
    const itembookIdInput = document.getElementById('editItembookId');
    const bookingDateInput = document.getElementById('editBookingDate');
    const startTimeInput = document.getElementById('editStartTime');
    const endTimeInput = document.getElementById('editEndTime');

    editLinks.forEach((link) => {
        link.addEventListener("click", (event) => {
            event.preventDefault();

            const itembook_id = link.getAttribute('data-itembook-id');
            const booking_date = link.getAttribute('data-booking-date');
            const start_time = link.getAttribute('data-start-time');
            const end_time = link.getAttribute('data-end-time');


            itembookIdInput.value = itembook_id;
            bookingDateInput.value = booking_date;
            startTimeInput.value = start_time;
            endTimeInput.value = end_time;

            dialog.showModal();
        });
    });

    dialog.querySelector("button").addEventListener("click", () => {
        dialog.close();
    });
</script> -->



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
</style>