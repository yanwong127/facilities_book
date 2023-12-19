<?php
include_once('db.php');
include_once('header.php');

if (isset($_REQUEST['item_book'])) {
    if (isset($_POST["item_book"])) {
        $item_id = $_POST['item_id'];
        $user_id = $_POST['user_id'];
        $item_name = trim($_POST['item_name']);
        $item_img = $_POST['item_img'];
        $item_overview = trim($_POST['item_overview']);
        $quantity = $_POST['quantity'];
        $booking_date = $_POST['booking_date'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];

        // $booking_date = date('Y/m/d', strtotime($booking_date));
        // $start_time = date('H:i:s', strtotime($start_time));
        // $end_time = date('H:i:s', strtotime($end_time));

        // $availabilityCheckQuery = "SELECT * FROM `item_appointment` WHERE item_id = '$item_id' AND 
        //     (
        //         ('$start_time' >= start_time AND '$start_time' < end_time) OR 
        //         ('$end_time' > start_time AND '$end_time' <= end_time) OR
        //         ('$start_time' <= start_time AND '$end_time' >= end_time)
        //     ) AND
        //     booking_date = '$booking_date'";
        // $availabilityCheckResult = mysqli_query($conn, $availabilityCheckQuery);

        // if (mysqli_num_rows($availabilityCheckResult) > 0) {
        //     $message = "Sorry, the item is not available at the selected date and time.";
        //     echo "<script>alert('$message'); window.location.href = 'item.php';</script>";
        //     exit();
        // }

        // Update the item table quantity
        // $updateQuantityQuery = "UPDATE `item` SET quantity = quantity - $quantity WHERE item_id = '$item_id'";
        // $updateQuantityResult = mysqli_query($conn, $updateQuantityQuery);

        // if (!$updateQuantityResult) {
        //     // Handle error if the quantity update fails
        //     error_log("Error updating quantity: " . mysqli_error($conn));
        //     // Optionally, you can roll back the previous insert operation here
        // }

        // Insert the appointment
        $insertQuery = "INSERT INTO `item_appointment` (item_id, item_name, item_img, user_id, start_time, end_time, booking_date, status, quantity) VALUES ('$item_id', '$item_name', '$item_img', '$user_id', '$start_time', '$end_time', '$booking_date', 'Unactive', '$quantity')";
        $result = mysqli_query($conn, $insertQuery);

        if ($result) {
            $message = "Booking successful!";
            echo "<script>alert('$message'); window.location.href = 'item.php';</script>";
            exit();
        } else {
            // Handle error if the insert fails
            error_log("Error inserting appointment: " . mysqli_error($conn));
            // Optionally, you can roll back the previous quantity update here
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

        <?php
        $i = 0;
        while ($row = mysqli_fetch_array($result)) {
            $item = "SELECT SUM(quantity) FROM `item_appointment` WHERE item_id='$row[item_id]' AND status='Approve' GROUP BY item_id";

            $qry = mysqli_query($conn, $item);
            $num = mysqli_num_rows($qry);
            $at=mysqli_fetch_array($qry);
            $sub=0;
            if($num!==0){
                $sub=$at[0];
            }else{
                $sub=0;
            }
            $total=$row["quantity"]-$sub;
            
            ?>
            <div class="td">
                <div class="item-container" id="none">
                    <a href="place.php?id=<?= $row['item_id'] ?>" data-item-overview="<?= $row['item_overview'] ?>"
                        data-item-quantity="<?=$total?>">
                        <img class="rounded-image" src="img/<?= $row['item_img'] ?>" alt="<?= $row['item_name'] ?>">
                        <div class="item-name">
                            <?= $row['item_name'] ?>
                        </div>
                    </a>
                </div>
            </div>
            <?php $i++;
        } ?>
    </div>

    <form action="item.php" method="post" class="dialog-form">
        <dialog class="dialog">
            <i class="fa fa-close" style="float: right;" autofocus></i>
            <h2 id="dialog-title"></h2>
            <h2 id="dialog-item-name"></h2>
            <img id="dialog-image" src="" alt="Item Image">
            <p id="dialog-overview"></p>
            <input type="hidden" name="item_id" id="item_id">
            <input type="hidden" name="item_name" id="item_name">
            <input type="hidden" name="item_img" id="item_img">
            <input type="hidden" name="item_overview" id="item_overview">
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
            <label for="booking_date">Booking Date:</label>
            <input type="date" name="booking_date" id="booking_date" required>
            <label for="start_time">Start Time:</label>
            <input type="time" name="start_time" id="start_time" required>
            <label for="end_time">End Time:</label>
            <input type="time" name="end_time" id="end_time" required>

            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" value="1" min="1" max="10" required>

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
    const dialogOverview = document.getElementById("dialog-overview");
    // const quantity = document.getElementById("quantity").value;


    document.querySelectorAll(".item-container a").forEach((link) => {
        link.addEventListener("click", (e) => {
            e.preventDefault();

            const itemTitle = link.querySelector(".item-name").textContent;
            const itemImageSrc = "img/" + link.querySelector("img").src.split('/').pop();
            const itemUrl = link.getAttribute('href');
            const item_id = itemUrl.split('=')[1];
            const itemOverview = link.getAttribute('data-item-overview');
            const itemQuantity = link.getAttribute('data-item-quantity');

            document.getElementById("item_id").value = item_id;
            document.getElementById("item_name").value = itemTitle;
            document.getElementById("item_img").value = itemImageSrc;
            document.getElementById("item_overview").value = itemOverview;
            // document.getElementById("quantity").value = itemQuantity;

            document.getElementById("quantity").setAttribute("max", itemQuantity);

            // Set the quantity input value to 1 or the available quantity, whichever is smaller
            document.getElementById("quantity").value = Math.min(1, itemQuantity);


            dialogTitle.textContent = itemTitle;
            dialogImage.src = itemImageSrc;
            dialogOverview.textContent = itemOverview;
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