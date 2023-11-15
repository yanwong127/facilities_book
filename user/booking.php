<?php
include_once('db.php');
include_once('header.php');

if ($_SESSION['true'] != true) {
    echo 'not gg';
    header("location: logout.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$item_query = "SELECT * FROM `item_appointment` WHERE `user_id` = $user_id AND `status` != 'active'";
$item_result = mysqli_query($conn, $item_query);

$place_query = "SELECT * FROM `place_appointment` WHERE `user_id` = $user_id AND `status` != 'active'";
$place_result = mysqli_query($conn, $place_query);

if (isset($_POST['edit']) && isset($_POST['itembook_id'])) {
    $itembook_id = $_POST['itembook_id'];
    $booking_date = $_POST['booking_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $query = "UPDATE `item_appointment` SET booking_date=?, start_time=?, end_time=? WHERE itembook_id=?";

    $stmt = mysqli_prepare($conn, $query);
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

    $query = "UPDATE `place_appointment` SET booking_date=?, start_time=?, end_time=? WHERE placebook_id=?";

    $stmt = mysqli_prepare($conn, $query);
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
</head>

<body>

    <br>
    <br>

    <div class="ctable">
        <table>
            <?php while ($row = mysqli_fetch_array($item_result)) { ?>
                <tr>
                    <td>
                        <img class="rounded-image" src="<?= $row['item_img'] ?>">
                    </td>
                    <td>
                        <?= $row['item_name'] ?>
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
                    <td>
                        <a href="#" class="edit-link" data-itembook-id="<?= $row['itembook_id'] ?>">Edit</a>
                    </td>
                    <td>
                        <a href="cancel_item.php?itembook_id=<?= $row['itembook_id'] ?>">Cancel</a>
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

            <?php while ($row = mysqli_fetch_array($place_result)) { ?>
                <tr>
                    <td>
                        <img class="rounded-image" src="<?= $row['place_img'] ?>">
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
                    <td>
                        <a href="#" class="edit-place-link" data-placebook-id="<?= $row['placebook_id'] ?>">Edit</a>
                    </td>
                    <td>
                        <a href="cancel_place.php?placebook_id=<?= $row['placebook_id'] ?>">Cancel</a>
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

    <br>
    <br>


</body>

</html>



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
</style>