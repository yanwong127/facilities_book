<?php



$place = "SELECT * FROM `place`";
$qry = mysqli_query($conn, $place);

if (isset($_REQUEST['place_book'])) {
    if (isset($_POST["place_book"])) {
        $place_id = $_POST['place_id'];
        $user_id = $_POST['user_id'];
        $place_name = trim($_POST['place_name']);
        $place_img = $_POST['place_img'];

        $booking_date = $_POST['booking_date'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];

        $booking_date = date('Y/m/d', strtotime($booking_date));
        $start_time = date('H:i:s', strtotime($start_time));
        $end_time = date('H:i:s', strtotime($end_time));

        $insertQuery = "INSERT INTO `place_appointment`(place_id, place_name, place_img, user_id, start_time, end_time, booking_date,  status) VALUES ('$place_id', '$place_name', '$place_img', '$user_id', '$start_time', '$end_time', '$booking_date', 'Active')";
        $result = mysqli_query($conn, $insertQuery);

        if ($result) {
            $message = "Booking successful!";
            echo "<script>window.location.href='home.php';</script>";
            exit();
        } else {
            error_log("Error: " . mysqli_error($conn)); // Log the error
            // Handle the error or redirect as needed without generating output
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="custom-table" id="clickable-div">
        <?php while ($row = mysqli_fetch_array($qry)) { ?>
            <div class="td">
                <div class="place-row">
                    <div class="place-container" id="none">
                        <a href="placeDetail.php?id=<?= $row['place_id'] ?>">
                            <img class="rounded-image" src="img/<?= $row['place_img'] ?>">
                            <div class="place-name">
                                <?= $row['place_name'] ?>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <form action="home.php" method="post">
        <dialog id="place-dialog">
            <button autofocus>Close</button>
            <h2 id="place-dialog-title"></h2>
            <h2 id="place-dialog-item-name" style="text-align: center;"></h2>
            <img id="place-dialog-image" src="" alt="Place Image">
            <p id="place-dialog-description"></p>
            <input type="hidden" name="place_id" id="place_id">
            <input type="hidden" name="place_name" id="place_name">
            <input type="hidden" name="place_img" id="place_img">
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
            <label for="booking_date">Booking Date:</label>
            <input type="date" name="booking_date" id="booking_date" required>
            <label for="start_time">Start Time:</label>
            <input type="time" name="start_time" id="start_time" required>
            <label for="end_time">End Time:</label>
            <input type="time" name="end_time" id="end_time" required>
            <button name="place_book">Book</button>
        </dialog>
    </form>



</body>

</html>

<script>
    const placeDialog = document.querySelector("#place-dialog");
    const placeDialogTitle = document.getElementById("place-dialog-title");
    const placeDialogImage = document.getElementById("place-dialog-image");
    const placeDialogDescription = document.getElementById("place-dialog-description");

    document.querySelectorAll(".place-container a").forEach((link) => {
        link.addEventListener("click", (e) => {
            e.preventDefault();

            const placeTitle = link.querySelector(".place-name").textContent;
            const placeImageSrc = "img/" + link.querySelector("img").src.split('/').pop();
            const placeUrl = link.getAttribute('href');
            const place_id = placeUrl.split('=')[1];

            document.getElementById("place_id").value = place_id;
            document.getElementById("place_name").value = placeTitle;
            document.getElementById("place_img").value = placeImageSrc;

            placeDialogTitle.textContent = placeTitle;
            placeDialogImage.src = placeImageSrc;
            placeDialogDescription.textContent = "Description of the place";
            placeDialog.showModal();
        });
    });

    placeDialog.querySelector("button").addEventListener("click", () => {
        placeDialog.close();
    });
</script>

<style>
    .custom-table {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-gap: 16px;
    }

    .custom-table .td {
        display: grid;
        grid-template-columns: 1fr;
        grid-template-rows: auto auto;
        text-align: center;
    }

    .place-row {
        align-items: center;
        justify-content: center;
    }

    .place-container img {
        max-width: 300px;
        max-height: 300px;
    }

    .place-name {
        font-weight: bold;
    }

    #clickable-div:hover {
        cursor: pointer;
    }

    a {
        text-decoration: none;
        color: black;
    }

    .rounded-image {
        border-radius: 10px;
    }

    #place-dialog-image {
        width: 400px;
        height: 200px;
    }
</style>