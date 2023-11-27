<?php
include_once('db.php');
include_once('header.php');

if ($_SESSION['true'] != true) {
    echo 'not gg';
    header("location: logout.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$records_per_page = 3;

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

$offset = ($page - 1) * $records_per_page;


$query = "
    (SELECT 'item' as type, ia.itembook_id as book_id, ia.item_img as img, ia.item_name as name, ia.booking_date, ia.start_time, ia.end_time, ia.status
    FROM `item_appointment` ia
    INNER JOIN `place_appointment` pa ON ia.user_id = pa.user_id
    WHERE ia.`user_id` = $user_id AND ia.`status` = 'active')

    UNION

    (SELECT 'place' as type, pa.placebook_id as book_id, pa.place_img as img, pa.place_name as name, pa.booking_date, pa.start_time, pa.end_time, pa.status
    FROM `place_appointment` pa
    INNER JOIN `item_appointment` ia ON pa.user_id = ia.user_id
    WHERE pa.`user_id` = $user_id AND pa.`status` = 'active')
    LIMIT $offset, $records_per_page
";

$result = mysqli_query($conn, $query);



if (isset($_POST['edit']) && isset($_POST['itembook_id'])) {
    $itembook_id = $_POST['itembook_id'];
    $booking_date = $_POST['booking_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $query1 = "UPDATE `item_appointment` SET booking_date=?, start_time=?, end_time=? WHERE itembook_id=?";

    $stmt = mysqli_prepare($conn, $query1);
    mysqli_stmt_bind_param($stmt, "sssi", $booking_date, $start_time, $end_time, $itembook_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>window.location.href = 'booking.php';alert('Record Successfully Edited');</script>";
    } else {
        echo "<script>alert('Record Fails to Edit')</script>";
    }

    mysqli_stmt_close($stmt);
}


if (isset($_POST['edit_place']) && isset($_POST['placebook_id'])) {
    $placebook_id = $_POST['placebook_id'];
    $booking_date = $_POST['place_booking_date'];
    $start_time = $_POST['place_start_time'];
    $end_time = $_POST['place_end_time'];

    $query2 = "UPDATE `place_appointment` SET booking_date=?, start_time=?, end_time=? WHERE placebook_id=?";

    $stmt = mysqli_prepare($conn, $query2);
    mysqli_stmt_bind_param($stmt, "sssi", $booking_date, $start_time, $end_time, $placebook_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>window.location.href = 'booking.php';alert('Record Successfully Edited');</script>";
    } else {
        echo "<script>alert('Record Fails to Edit')</script>";
    }

    mysqli_stmt_close($stmt);
}


?>




<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Status</title>
    <link rel="stylesheet" href="bookresult.css">
</head>

<body>

    <br>
    <br>

    <div class="ctable">
        <table class="w3-table w3-striped w3-bordered w3-table-all w3-hoverable">
                <tr class="w3-light-grey">
                    <th>Image</th>
                    <th>Place/Item</th>
                    <th>Booking Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Status</th>
                </tr>
            <?php while ($row = mysqli_fetch_array($result)) { ?>
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

            <dialog id="editDialog">
                <button autofocus>Close</button>
                <h2 id="editDialogTitle"></h2>
                <div class="container">
                    <form class="form-horizontal" action="booking.php" method="post">
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
                                <button type="button" onclick="window.location.href='booking.php'">Back</button>
                            </div>
                        </div>
                    </form>
                </div>
            </dialog>

            <script>
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

                        // dialogTitle.textContent = `Edit Booking for Item ID: ${itembook_id}`;

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
            </script>

            <?php while ($row = mysqli_fetch_array($result)) { ?>
                <tr>
                    <td>
                        <img class="rounded-image" src="<?= $row['img'] ?>">
                    </td>
                    <td>
                        <?= $row['place_name'] ?>
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

    </div>

    <dialog id="editPlaceDialog">
        <button autofocus>Close</button>
        <h2 id="editPlaceDialogTitle"></h2>
        <div class="container">
            <form class="form-horizontal" action="booking.php" method="post">
                <input type="hidden" name="placebook_id" id="editPlacebookId">
                <div>
                    <label>Booking Date:</label>
                    <div>
                        <input type="date" name="place_booking_date" id="editPlaceBookingDate" required>
                    </div>
                </div>
                <div>
                    <label>Start Time:</label>
                    <div>
                        <input type="time" name="place_start_time" id="editPlaceStartTime" required>
                    </div>
                </div>
                <div>
                    <label>End Time:</label>
                    <div>
                        <input type="time" name="place_end_time" id="editPlaceEndTime" required>
                    </div>
                </div>
                <br />
                <div>
                    <div>
                        <button type="submit" name="edit_place" value="edit_place">Submit</button>
                        <button type="button" onclick="window.location.href='booking.php'">Back</button>
                    </div>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        const editPlaceLinks = document.querySelectorAll(".edit-place-link");
        const placeDialog = document.getElementById('editPlaceDialog');
        const placeDialogTitle = document.getElementById('editPlaceDialogTitle');
        const placebookIdInput = document.getElementById('editPlacebookId');
        const placeBookingDateInput = document.getElementById('editPlaceBookingDate');
        const placeStartTimeInput = document.getElementById('editPlaceStartTime');
        const placeEndTimeInput = document.getElementById('editPlaceEndTime');

        editPlaceLinks.forEach((link) => {
            link.addEventListener("click", (event) => {
                event.preventDefault();

                const placebook_id = link.getAttribute('data-placebook-id');
                const place_booking_date = link.getAttribute('data-place-booking-date');
                const place_start_time = link.getAttribute('data-place-start-time');
                const place_end_time = link.getAttribute('data-place-end-time');

                if (placebook_id) {
                    // placeDialogTitle.textContent = `Edit Booking for Place ID: ${placebook_id}`;
                } else {
                    placeDialogTitle.textContent = '';
                }

                placebookIdInput.value = placebook_id;
                placeBookingDateInput.value = place_booking_date;
                placeStartTimeInput.value = place_start_time;
                placeEndTimeInput.value = place_end_time;

                placeDialog.showModal();
            });
        });

        placeDialog.querySelector("button[autofocus]").addEventListener("click", () => {
            placeDialog.close();
        });

        // placeDialog.addEventListener("click", (event) => {
        //     if (event.target === placeDialog) {
        //         placeDialog.close();
        //     }
        // });

    </script>

    <div class="pagination justify-content-center">
        <?php
        $item_count_query = "SELECT COUNT(*) FROM `item_appointment` WHERE `user_id` = $user_id AND `status` = 'active'";
        $item_count_result = mysqli_query($conn, $item_count_query);
        $item_row = mysqli_fetch_row($item_count_result);
        $item_records = $item_row[0];

        $place_count_query = "SELECT COUNT(*) FROM `place_appointment` WHERE `user_id` = $user_id AND `status` = 'active'";
        $place_count_result = mysqli_query($conn, $place_count_query);
        $place_row = mysqli_fetch_row($place_count_result);
        $place_records = $place_row[0];

        $total_records = $item_records + $place_records;
        $total_pages = ceil($total_records / $records_per_page);

        if ($page > 1) {
            echo "<a href='result.php?page=" . ($page - 1) . "'>Prev</a>";
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            echo "<a " . ($i == $page ? "class='active'" : "") . " href='result.php?page=" . $i . "'>" . $i . "</a>";
        }

        if ($page < $total_pages) {
            echo "<a href='result.php?page=" . ($page + 1) . "'>Next</a>";
        } elseif ($page >= $total_pages) {
            echo "<a class='disabled'>Next</a>";
        }
        ?>



    </div>


    <br>
    <br>


</body>

</html>