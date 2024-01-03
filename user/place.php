<?php

include_once('db.php');
include_once('header.php');

$records_per_page = 6;
if (isset($_GET['place_page'])) {
    $page = $_GET['place_page'];
} else {
    $page = 1;
}

$set = ($page - 1) * $records_per_page;
$jj = "SELECT * FROM place WHERE availability <> 'Not Working' LIMIT $set,$records_per_page";
$result = mysqli_query($conn, $jj);

if (isset($_REQUEST['place_book'])) {
    if (isset($_POST["place_book"])) {
        $place_id = $_POST['place_id'];
        $user_id = $_POST['user_id'];
        $place_name = trim($_POST['place_name']);
        $place_img = $_POST['place_img'];
        $place_overview = trim($_POST['place_overview']);
        $booking_date = $_POST['booking_date'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $mysqlDateFormat = date("Y-m-d", strtotime($booking_date));

        // Insert the appointment
        $insertQuery = "INSERT INTO `place_appointment` (place_id, place_name, place_img, user_id, start_time, end_time, booking_date, status) VALUES ('$place_id', '$place_name', '$place_img', '$user_id', '$start_time', '$end_time', '$mysqlDateFormat', 'Unactive')";
        $result = mysqli_query($conn, $insertQuery);

        if ($result) {
            $message = "Booking successful!";
            echo "<script>alert('$message'); window.location.href = 'place.php';</script>";
            exit();
        } else {
            // Handle error if the insert fails
            error_log("Error inserting appointment: " . mysqli_error($conn));
            // Optionally, you can roll back the previous quantity update here
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bookingpage.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/smoothness/jquery-ui.css">
    <title>Equipment</title>
</head>
<header class="w3-container w3-xlarge">
    <p class="w3-left">PLACE</p>
    <p class="w3-right">
        <button class="btn" id="itemButton">Equipment</button>
        <button class="btn" id="placeButton">Place</button>
    </p>
</header>

<body>
    <div class="custom-table" id="clickable-div">
        <?php while ($row = mysqli_fetch_array($result2)) { ?>
            <div class="td">
                <div class="place-container" id="none">
                    <a href="place.php?id=<?= $row['place_id'] ?>" data-place-overview="<?= $row['place_overview'] ?>">
                        <img class="rounded-image" src="img/<?= $row['place_img'] ?>" alt="<?= $row['place_name'] ?>">
                        <div class="place-name">
                            <?= $row['place_name'] ?>
                        </div>
                    </a>
                </div>
                <?php $i++;
            } ?>
        </div>


        <!-- This is a container where user click a item list div -->
        <div class="card text-center" id="placeCard" style="display: none;">
            <i class="fa fa-close" id="closeButton" style="float: right;"></i>
            <h2 id="card-title"></h2>
            <h2 id="card-place-name"></h2>
            <img id="card-image" src="img/<?= $row['place_img'] ?>" alt="Place Image">
            <p id="card-overview"></p>
            <form action="place.php" method="post" class="card-form">
                <input type="hidden" name="place_id" id="card-place_id">
                <input type="hidden" name="place_name" id="card-place_name">
                <input type="hidden" name="place_img" id="card-place_img">

                <input type="hidden" name="place_overview" id="card-place_overview">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                <label for="booking_date">Booking Date:</label><br>
                <input type="text" id="booking_date" name="booking_date" required><br>
                <label for="start_time">Start Time:</label><br>
                <input type="time" name="start_time" id="start_time" required min="08:00" max="16:00"><br>
                <label for="end_time">End Time:</label><br>
                <input type="time" name="end_time" id="end_time" required min="09:00" max="17:00"><br>
                <label for="quantity">Quantity: </label><br>
                <input type="number" name="quantity" id="card-quantity" value="1" min="1" max="10" required><br>
                <button name="place_book">Book</button>
            </form>
        </div>
    </form>
    <!-- Pagination Navigation -->
    <div class="pagination justify-content-center">
        <?php
        $SQL = "SELECT COUNT(*) FROM place WHERE availability = 'Still Working'";
        $result_count = mysqli_query($conn, $SQL);
        $row = mysqli_fetch_row($result_count);
        $records = $row[0];

        $total_pages = ceil($records / $records_per_page);
        $pagelink = "";

        if ($page >= 2) {
            echo "<a href='place.php?place_page=" . ($page - 1) . "'>Prev</a>";
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                $pagelink .= "<a class='active' href='place.php?place_page=" . $i . "'>" . $i . "</a>";
            } else {
                $pagelink .= "<a href='place.php?place_page=" . $i . "'>" . $i . "</a>";
            }
        }

        echo $pagelink;

        if ($page < $total_pages) {
            echo "<a href='place.php?place_page=" . ($page + 1) . "'>Next</a>";
        }
        ?>
    </div>

</body>

</html>
<script>
    // Is a navigation to other page.
document.getElementById("itemButton").addEventListener("click", function() {
    location.href = 'item.php';
    });
    document.getElementById("placeButton").addEventListener("click", function() {
    location.href = 'place.php';
    });


    const placeDialog = document.querySelector("#place-dialog");
    const closeButton = document.querySelector(".fa-close");
    const placeDialogTitle = document.getElementById("place-dialog-title");
    const placeDialogImage = document.getElementById("place-dialog-image");
    const placeDialogOverview = document.getElementById("place-dialog-overview");

    document.querySelectorAll(".place-container a").forEach((link) => {
        link.addEventListener("click", (e) => {
            e.preventDefault();

            const placeTitle = link.querySelector(".place-name").textContent;
            const placeImageSrc = "img/" + link.querySelector("img").src.split('/').pop();
            const placeUrl = link.getAttribute('href');
            const place_id = placeUrl.split('=')[1];
            const placeOverview = link.getAttribute('data-place-overview');

            document.getElementById("place_id").value = place_id;
            document.getElementById("place_name").value = placeTitle;
            document.getElementById("place_img").value = placeImageSrc;

            placeDialogTitle.textContent = placeTitle;
            placeDialogImage.src = placeImageSrc;
            placeDialogOverview.textContent = placeOverview;
            placeDialog.showModal();
        });
    });


    var closeButton = document.getElementById('closeButton');
    var cardDiv = document.getElementById('PlaceCard');

    closeButton.addEventListener("click", () => {
        cardDiv.style.display = 'none';
    });

    cardDiv.addEventListener("click", (e) => {
        e.stopPropagation();
    });

    document.addEventListener('DOMContentLoaded', function() {
            var today = new Date();
            var dayOfWeek = today.getDay();

            // Calculate the number of days to add to reach the next Friday
            var daysToAdd = dayOfWeek === 5 ? 7 : (5 - dayOfWeek + (dayOfWeek < 5 ? 0 : 7));

            // Calculate the next Friday's date
            var nextFriday = new Date(today);
            nextFriday.setDate(today.getDate() + daysToAdd);

            // Calculate the next Monday's date
            var nextMonday = new Date(today);
            nextMonday.setDate(today.getDate() + (1 + 7 - dayOfWeek) % 7);

            // Set the min attribute of the date input to the next Monday
            document.getElementById('booking_date').setAttribute('min', formatDate(nextMonday));

            // Set the max attribute of the date input to the next Sunday
            var nextSunday = new Date(today);
            nextSunday.setDate(today.getDate() + (7 - dayOfWeek) % 7);
            document.getElementById('booking_date').setAttribute('max', formatDate(nextSunday));
        });

        // Function to format the date as 'YYYY-MM-DD'
        function formatDate(date) {
            var year = date.getFullYear();
            var month = ('0' + (date.getMonth() + 1)).slice(-2);
            var day = ('0' + date.getDate()).slice(-2);
            return year + '-' + month + '-' + day;
        }

</script>