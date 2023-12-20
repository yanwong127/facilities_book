 <?php 

include_once("db.php");
include_once("header.php");

$user_id = $_SESSION['user_id'];
$records_per_page = 3;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $records_per_page;

// Query for places
$place_query = "
    SELECT 'place' as type, pa.placebook_id as book_id, pa.place_img as img, pa.place_name as name, pa.booking_date, pa.start_time, pa.end_time, pa.status
    FROM `place_appointment` pa
    WHERE pa.`user_id` = $user_id AND pa.`status` = 'unactive'
    LIMIT $offset, $records_per_page
";

$place_result = mysqli_query($conn, $place_query);

// Calculate total pages for places
$place_count_query = "SELECT COUNT(*) FROM `place_appointment` WHERE `user_id` = $user_id AND `status` = 'unactive'";
$place_count_result = mysqli_query($conn, $place_count_query);
$place_row = mysqli_fetch_row($place_count_result);
$place_records = $place_row[0];
$total_place_pages = ceil($place_records / $records_per_page);

if (isset($_POST['edit_place']) && isset($_POST['placebook_id'])) {
    $placebook_id = $_POST['placebook_id'];
    $booking_date2 = $_POST['place_booking_date'];
    $start_time2 = $_POST['place_start_time'];
    $end_time2 = $_POST['place_end_time'];

    $query2 = "UPDATE `place_appointment` SET booking_date=?, start_time=?, end_time=? WHERE placebook_id=?";

    $stmt2 = mysqli_prepare($conn, $query2);
    mysqli_stmt_bind_param($stmt2, "sssi", $booking_date2, $start_time2, $end_time2, $placebook_id);

    if (mysqli_stmt_execute($stmt2)) {
        echo "<script>window.location.href = 'booking_place.php';alert('Record Successfully Edited');</script>";
    } else {
        echo "<script>alert('Record Fails to Edit')</script>";
    }

    mysqli_stmt_close($stmt2);
}
?>
 
 
 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place result</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="booking_page.css">
 </head>
 <header class="w3-container w3-xlarge">
    <p class="w3-left">YOUR BOOKING (PLACE)</p>
    <p class="w3-right">
        <button class="btn" onclick="location.href='booking_item.php'">ITEM</button>
        <button class="btn" onclick="location.href='booking_place.php'">PLACE</button>
    </p>
  </header>
 <body>
 <!-- Display Places Table -->
 <div class="ctable">

        <table class="w3-table-all">
            <thead>
                <tr class="w3-grey">
                    <th>Image</th>
                    <th>Place Name</th>
                    <th>Booking Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
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
                    <td>
                        <a href="#" class="edit-place-link" data-placebook-id="<?= $row['book_id'] ?>">Edit</a>
                    </td>
                    <td>
                        <a href="cancel_place.php?placebook_id=<?= $row['book_id'] ?>">Cancel</a>
                    </td>
                </tr>
            <?php } ?>
        </table>

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

        <!-- Places Pagination -->
        <div class="pagination justify-content-center">
    <?php
    if ($page > 1) {
        echo "<a href='booking_place.php?page=" . ($page - 1) . "&type=item'>Prev</a>";
    }

    for ($i = 1; $i <= $total_place_pages; $i++) {
        echo "<a " . ($i == $page ? "class='active'" : "") . " href='booking_place.php?page=" . $i . "&type=item'>" . $i . "</a>";
    }

    // Check if there are records in the next page
    $hasNextPage = ($page < $total_place_pages);

    if ($hasNextPage) {
        echo "<a href='booking_place.php?page=" . ($page + 1) . "&type=item'>Next</a>";
    } elseif ($page >= $total_place_pages && $total_place_pages > 0) {
        echo "<a class='disabled'>Next</a>";
    }
    ?>
</div>
    </div>

    <dialog id="editPlaceDialog">
        <button autofocus>Close</button>
        <h2 id="editPlaceDialogTitle"></h2>
        <div class="container">
            <form class="form-horizontal" action="booking_place.php" method="post">
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
                        <!-- <button type="button" onclick="window.location.href='booking_place.php'">Back</button> -->
                    </div>
                </div>
            </form>
        </div>
    </dialog>

 </body>
 </html>

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

        function redirectToURL(url) {
        window.location.href = url;
        }

        
    </script>

<!-- <style>
    .header {
        position: relative;
        z-index: 1;
        /* Set a value smaller than varbar's z-index */
    }
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
        height: 120px;
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
    .btn {
        background-color: #fff;
    }

</style> -->