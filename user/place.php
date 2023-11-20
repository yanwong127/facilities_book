<?php

include_once('db.php');
include_once('header.php');

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

        $insertQuery = "INSERT INTO `place_appointment`(place_id, place_name, place_img, user_id, start_time, end_time, booking_date,  status) VALUES ('$place_id', '$place_name', '$place_img', '$user_id', '$start_time', '$end_time', '$booking_date', 'Unactive')";
        $result = mysqli_query($conn, $insertQuery);

        if ($result) {
            $message = "Booking successful!";
            echo "<script>window.location.href = 'place.php';
            alert('Booking Sucessful.');</script>";
            exit();
        } else {
            error_log("Error: " . mysqli_error($conn));
        }
    }
}


$records_per_page = 3;
if (isset($_GET['place_page'])) {
    $place_page = $_GET['place_page'];
} else {
    $place_page = 1;
}
$set2 = ($place_page - 1) * $records_per_page;
$jj2 = "SELECT * FROM `place` LIMIT $set2,$records_per_page";
$result2 = mysqli_query($conn, $jj2);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <br>
    <div>
    <h1 style="font-family: Clarkson,Helvetica,sans-serif;">Item Page</h1>
    <a class="button-1" href="item.php" role="button"><span class="text">Item</span></a>
    <a class="button-1" href="place.php" role="button"><span class="text">Place</span></a>
    </div>
    <div class="custom-table" id="clickable-div">
        <?php while ($row = mysqli_fetch_array($result2)) { ?>
            <div class="td">
                <div class="place-row">
                    <div class="place-container" id="none">
                        <a href="place.php?id=<?= $row['place_id'] ?>">
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

    <form action="place.php" method="post">
    <dialog id="place-dialog">
        <i class="fa fa-close" onclick="document.getElementById('place-dialog').close()"></i>
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


    </table>
    <div class="pagination justify-content-center">
        <?php
        $SQL = "SELECT COUNT(*) FROM place";
        $result_count = mysqli_query($conn, $SQL);
        $row = mysqli_fetch_row($result_count);
        $records = $row[0];

        $total_pages = ceil($records / $records_per_page);
        $pagelink = "";

        if ($place_page >= 2) {
            echo "<a href='place.php?place_page=" . ($place_page - 1) . "'>Prev</a>";
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $place_page) {
                $pagelink .= "<a class='active' href='place.php?place_page=" . $i . "'>" . $i . "</a>";
            } else {
                $pagelink .= "<a href='place.php?place_page=" . $i . "'>" . $i . "</a>";
            }
        }

        echo $pagelink;

        if ($place_page < $total_pages) {
            echo "<a href='place.php?place_page=" . ($place_page + 1) . "'>Next</a>";
        }
        ?>
    </div>
    </table>


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
</script>


<style>
.home_page {
    display: flex; 
    justify-content: space-between; 
    align-items: center;"
}

/* 对话框样式 */
.dialog {
  flex-direction: column;
  align-items: center;
  padding: 20px;
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: 8px;
}

.dialog i {
  margin-left: auto; /* Move the <i> element to the right */
}

button {
  background-color: #8d8f8d;
  color: #fff;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  margin-top: 10px;
}

button:hover {
  background-color: #525352;
}

/* 原有表格样式的改进 */
.custom-table {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  grid-gap: 16px;
  justify-content: center; /* 居中对齐 */
}

.td {
  text-align: center;
}

.place-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  transition: transform 0.3s ease; /* 添加移动效果 */
}

.place-container:hover {
  transform: translateY(-10px); /* 悬停时上移一些 */
  cursor: pointer;
}

.place-container img {
  max-width: 200px;
  max-height: 200px;
  border-radius: 20px;
}

.place-name {
  font-weight: bold;
  margin-top: 10px;
}

#clickable-div:hover {
  cursor: pointer;
}

        
</style>