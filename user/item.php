<?php
include_once('db.php');
include_once('header.php');

$item = "SELECT * FROM `item`";
$qry = mysqli_query($conn, $item);

if (isset($_REQUEST['item_book'])) {
    if (isset($_POST["item_book"])) {
        $item_id = $_POST['item_id'];
        $user_id = $_POST['user_id'];
        $item_name = trim($_POST['item_name']);
        $item_img = $_POST['item_img'];

        $booking_date = $_POST['booking_date'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];

        $booking_date = date('Y/m/d', strtotime($booking_date));
        $start_time = date('H:i:s', strtotime($start_time));
        $end_time = date('H:i:s', strtotime($end_time));

        // Check availability before inserting the booking
        $availabilityCheckQuery = "SELECT * FROM `item_appointment` WHERE item_id = '$item_id' AND 
(
    ('$start_time' >= start_time AND '$start_time' < end_time) OR 
    ('$end_time' > start_time AND '$end_time' <= end_time) OR
    ('$start_time' <= start_time AND '$end_time' >= end_time)
) AND
booking_date = '$booking_date'";
        $availabilityCheckResult = mysqli_query($conn, $availabilityCheckQuery);

        if (mysqli_num_rows($availabilityCheckResult) > 0) {
            // Item is already booked at the selected date and time
            $message = "Sorry, the item is not available at the selected date and time.";
            echo "<script>alert('$message'); window.location.href = 'item.php';</script>";
            exit();
        }


        // Proceed with the booking
        $insertQuery = "INSERT INTO `item_appointment` (item_id, item_name, item_img, user_id, start_time, end_time, booking_date, status) VALUES ('$item_id', '$item_name', '$item_img', '$user_id', '$start_time', '$end_time', '$booking_date', 'Unactive')";
        $result = mysqli_query($conn, $insertQuery);

        if ($result) {
            $message = "Booking successful!";
            echo "<script>alert('$message'); window.location.href = 'item.php';</script>";
            exit();
        } else {
            error_log("Error: " . mysqli_error($conn));
        }
    }
}

$records_per_page = 6;
if (isset($_GET['item_page'])) {
    $page = $_GET['item_page'];
} else {
    $page = 1;
}

$set = ($page - 1) * $records_per_page;
$jj = "SELECT * FROM `item` WHERE availability <> 'Not Working' LIMIT $set,$records_per_page";
$result = mysqli_query($conn, $jj);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Page</title>
    <link rel="stylesheet" href="bookingpage.css">
</head>
<header class="w3-container w3-xlarge">
    <p class="w3-left">ITEM</p>
    <p class="w3-right">
        <a href="item.php">Item</a>
        <a href="place.php">Place</a>
    </p>
  </header>
<body>
    <div class="custom-table" id="clickable-div">

        <?php while ($row = mysqli_fetch_array($result)) { ?>
            <div class="td">
                <div class="item-container" id="none">
                    <a href="place.php?id=<?= $row['item_id'] ?>">
                        <img class="rounded-image" src="img/<?= $row['item_img'] ?>" alt="<?= $row['item_name'] ?>">
                        <div class="item-name">
                            <?= $row['item_name'] ?>
                        </div>
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>

    <form action="item.php" method="post" class="dialog-form">
        <dialog class="dialog">
            <i class="fa fa-close" style="float: right;" autofocus></i>
            <h2 id="dialog-title"></h2>
            <h2 id="dialog-item-name"></h2>
            <img id="dialog-image" src="" alt="Item Image">
            <p id="dialog-description"></p>
            <input type="hidden" name="item_id" id="item_id">
            <input type="hidden" name="item_name" id="item_name">
            <input type="hidden" name="item_img" id="item_img">
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
            <label for="booking_date">Booking Date:</label>
            <input type="date" name="booking_date" id="booking_date" required>
            <label for="start_time">Start Time:</label>
            <input type="time" name="start_time" id="start_time" required>
            <label for="end_time">End Time:</label>
            <input type="time" name="end_time" id="end_time" required>
            <button name="item_book">Book</button>
        </dialog>
    </form>

    <div class="pagination justify-content-center">
        <?php
        $SQL = "SELECT COUNT(*) FROM item WHERE availability = 'Still Working'";
        $result_count = mysqli_query($conn, $SQL);
        $row = mysqli_fetch_row($result_count);
        $records = $row[0];

        $total_pages = ceil($records / $records_per_page);
        $pagelink = "";

        if ($page >= 2) {
            echo "<a href='item.php?item_page=" . ($page - 1) . "'>Prev</a>";
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                $pagelink .= "<a class='active' href='item.php?item_page=" . $i . "'>" . $i . "</a>";
            } else {
                $pagelink .= "<a href='item.php?item_page=" . $i . "'>" . $i . "</a>";
            }
        }

        echo $pagelink;

        if ($page < $total_pages) {
            echo "<a href='item.php?item_page=" . ($page + 1) . "'>Next</a>";
        }
        ?>
    </div>
</body>

</html>

<script>
    const dialog = document.querySelector("dialog");
    const closeButton = document.querySelector(".fa-close");
    const dialogTitle = document.getElementById("dialog-title");
    const dialogImage = document.getElementById("dialog-image");
    const dialogDescription = document.getElementById("dialog-description");

    document.querySelectorAll(".item-container a").forEach((link) => {
        link.addEventListener("click", (e) => {
            e.preventDefault();

            const itemTitle = link.querySelector(".item-name").textContent;
            const itemImageSrc = "img/" + link.querySelector("img").src.split('/').pop();
            const itemUrl = link.getAttribute('href');
            const item_id = itemUrl.split('=')[1];

            document.getElementById("item_id").value = item_id;
            document.getElementById("item_name").value = itemTitle;
            document.getElementById("item_img").value = itemImageSrc;

            dialogTitle.textContent = itemTitle;
            dialogImage.src = itemImageSrc;
            dialogDescription.textContent = "Description of the item";
            dialog.showModal();
        });
    });

    closeButton.addEventListener("click", () => {
        dialog.close();
    });

    dialog.querySelector("*").addEventListener("click", (e) => {
        e.stopPropagation();
    });
</script>