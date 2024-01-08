<?php
include_once('db.php');
include_once('header.php');

$user_id = $_SESSION['user_id'];
$records_per_page = 3;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $records_per_page;

// Query for items
$item_query = "
    SELECT 'item' as type, ia.itembook_id as book_id, ia.item_img as img, ia.item_name as name, ia.booking_date, ia.start_time, ia.end_time, ia.status ,  ia.quantity
    FROM `item_appointment` ia
    WHERE ia.`user_id` = $user_id AND ia.`status` = 'unactive'
    LIMIT $offset, $records_per_page
";

$item_result = mysqli_query($conn, $item_query);

// Calculate total pages for items
$item_count_query = "SELECT COUNT(*) FROM `item_appointment` WHERE `user_id` = $user_id AND `status` = 'unactive'";
$item_count_result = mysqli_query($conn, $item_count_query);
$item_row = mysqli_fetch_row($item_count_result);
$item_records = $item_row[0];
$total_item_pages = ceil($item_records / $records_per_page);


if (isset($_POST['edit']) && isset($_POST['itembook_id'])) {
    $itembook_id = $_POST['itembook_id'];
    $booking_date = $_POST['booking_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $query1 = "UPDATE `item_appointment` SET booking_date=?, start_time=?, end_time=? WHERE itembook_id=?";

    $stmt = mysqli_prepare($conn, $query1);
    mysqli_stmt_bind_param($stmt, "sssi", $booking_date, $start_time, $end_time, $itembook_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>window.location.href = 'booking_item.php';alert('Record Successfully Edited');</script>";
    } else {
        echo "<script>alert('Record Fails to Edit')</script>";
    }

    mysqli_stmt_close($stmt);
}




?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="booking_page.css">
<header class="w3-container w3-xlarge">
    <p class="w3-left">Booking Detail</p>
    <p class="w3-right">
        <button class="btn" onclick="location.href='booking_item.php'">Equipment</button>
        <button class="btn" onclick="location.href='booking_place.php'">Place</button>
    </p>
  </header>
<body>
    <div class="ctable">

        <?php if (mysqli_num_rows($item_result) > 0) { ?>
            <table class="w3-table-all w3-card-4">
            <thead>
                <tr class="w3-grey">
                    <th>Image</th>
                    <th>Place Name</th>
                    <th>Booking Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <?php while ($row = mysqli_fetch_array($item_result)) { ?>
                <tr class="<?= $row['status'] === 'unactive' ? 'unactive-row' : 'active-row' ?>">
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
                        <?= $row['quantity'] ?>
                    </td>
                    <td>
                        <?= $row['status'] ?>
                    </td>
                    <td>
                        <a class="" href="#" class="edit-link" data-itembook-id="<?= $row['book_id'] ?>">Edit</a>
                    </td>
                    <td>
                        <a class="bta" href="cancel_item.php?itembook_id=<?= $row['book_id'] ?>">Cancel</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <div class="no-appointments">
            <p>No appointments found.</p>
            <p>Feel free to schedule new appointments!</p>
        </div>
    <?php } ?>

        <div class="pagination justify-content-center">
    <?php
    if ($page > 1) {
        echo "<a href='booking_item.php?page=" . ($page - 1) . "&type=item'>Prev</a>";
    }

    for ($i = 1; $i <= $total_item_pages; $i++) {
        echo "<a " . ($i == $page ? "class='active'" : "") . " href='booking_item.php?page=" . $i . "&type=item'>" . $i . "</a>";
    }

    // Check if there are records in the next page
    $hasNextPage = ($page < $total_item_pages);

    if ($hasNextPage) {
        echo "<a href='booking_item.php?page=" . ($page + 1) . "&type=item'>Next</a>";
    } elseif ($page >= $total_item_pages && $total_item_pages > 0) {
        echo "<a class='disabled'>Next</a>";
    }
    ?>
</div>


    </div>

    <form action="form-horizontal" action="booking_item.php" method="post" class="dialog-form">
    <dialog id="editDialog">
        <i class="fa fa-close" style="float: right;" autofocus></i>
        <h2 id="editDialogTitle"></h2>
        <div class="container">
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
                    <div>
                        <button type="submit" name="edit" value="edit">Submit</button>
                        <!-- <button type="button" onclick="window.location.href='booking_item.php'">Back</button> -->
                    </div>
                </div>

        </div>
    </dialog>
    </form>

  

</body>

</html>

<script>
        const closeButton = document.querySelector(".fa-close");
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

        closeButton.addEventListener("click", () => {
            dialog.close();
        });

        function redirectToURL(url) {
        window.location.href = url;
        }
    </script>