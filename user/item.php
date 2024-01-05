<?php

include_once('db.php');
include_once('header.php');

$records_per_page = 6;
if (isset($_GET['item_page'])) {
    $page = $_GET['item_page'];
} else {
    $page = 1;
}

$set = ($page - 1) * $records_per_page;
$jj = "SELECT * FROM item WHERE availability <> 'Not Working' LIMIT $set,$records_per_page";
$result = mysqli_query($conn, $jj);

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
        $mysqlDateFormat = date("Y-m-d", strtotime($booking_date));

        // Insert the appointment
        $insertQuery = "INSERT INTO item_appointment (item_id, item_name, item_img, user_id, start_time, end_time, booking_date, status, quantity) VALUES ('$item_id', '$item_name', '$item_img', '$user_id', '$start_time', '$end_time', '$mysqlDateFormat', 'Unactive', '$quantity')";
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
<style>
    #itemCard {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 400px;
        max-height: 70vh;
        overflow-y: auto;
    }
    .header {
    position: relative;
    z-index: 1;
}
    .btn {
        background-color: #fff;
    }
    .card {
        flex-direction: column;
        align-items: center;
        padding: 20px;
        background-color: #fff;
        border: 2px solid #0f0e0e;
        border-radius: 10px;
    }

    /* 共用的表格样式改进 */
    .custom-table {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-gap: 16px;
        justify-content: center; /* Center alignment */
    }

    .td {
        text-align: center;
    }

    .custom-table .td .item-container,.place-container{
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: transform 0.3s ease;
        /* border: 1px solid #ccc; */
        border-radius: 10px;
        margin-bottom: 16px;
        overflow: hidden; /* 隐藏溢出的内容 */
        border: 2px solid #ccc;
        padding: 10px 10px;
    }
    
    .item-container:hover,
    .place-container:hover {
        transform: translateY(-10px);
        cursor: pointer;
    }
    

    .item-container img,
    .place-container img {
        width: 280px;
        height: 180px;
        border-radius: 5px;
        object-fit: cover; /* 尝试使用scale-down */
    }
    
    

    .item-name,
    .place-name {
        font-weight: bold;
        margin-top: 10px;
    }

    a {
        text-decoration: none !important;
    }

    #clickable-div:hover {
        cursor: pointer;
    }


    #card-image,
    #place-card-image {
        width: 350px;  /* 设置宽度 */
        height: 150px; /* 设置高度 */
    }



    /* New styles for the form inside the dialog */
    .card {
        max-width: 500px;
        margin: 0 auto;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .card label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }

    .card input {
        width: 80%;
        padding: 8px;
        margin-bottom: 16px;
        box-sizing: border-box;
    }

    .card button {
        background-color: #343634;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .card button:hover {
        background-color: #484848;
    }

    .pagination {
    display: flex;
    justify-content: center;
    list-style: none;
    padding: 0;
    margin-top: 20px;
}

    .pagination a {
        color: #6d6f6d;
        padding: 8px 16px;
        text-decoration: none;
        transition: background-color 0.3s;
        border: 1px solid #ddd;
        margin: 0 4px;
    }

    .pagination a.active {
        background-color: #3d3e3d;
        color: white;
    }

    .pagination a:hover:not(.active) {
        background-color: #ddd;
    }
    .pagination {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: #fff;
        z-index: 1;
        display: flex;
        justify-content: center;
        padding: 10px 0;
        border-top: 1px solid #ccc;
    }
    #itemCard {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 400px;
        max-height: 70vh;
        overflow-y: auto;
    }
</style>

<body>
    <header class="w3-container w3-xlarge">
        <p class="w3-left">Equipment</p>
        <p class="w3-right">
            <button class="btn" id="itemButton">Equipment</button>
            <button class="btn" id="placeButton">PLACE</button>
        </p>
    </header>
    <form action="item.php" method="post">
        <div class="custom-table" id="clickable-div">

            <?php
            $i = 0;
            while ($row = mysqli_fetch_array($result)) {
                $item = "SELECT SUM(quantity) FROM item_appointment WHERE item_id='$row[item_id]' AND status='Approve' GROUP BY item_id";

                $qry = mysqli_query($conn, $item);
                $num = mysqli_num_rows($qry);
                $at = mysqli_fetch_array($qry);
                $sub = 0;
                if ($num !== 0) {
                    $sub = $at[0];
                } else {
                    $sub = 0;
                }
                $total = $row["quantity"] - $sub;


                ?>
                <div class="td">
                    <div class="item-container" id="none">
                        <a href="item.php?id=<?= $row['item_id'] ?>" data-item-overview="<?= $row['item_img'] ?>"
                            data-item-name="<?= $row['item_name'] ?>">
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


        <!-- This is a container where user click a item list div -->
        <div class="card text-center" id="itemCard" style="display: none;">
            <i class="fa fa-close" id="closeButton" style="float: right;"></i>
            <h2 id="card-title"></h2>
            <h2 id="card-item-name"></h2>
            <img id="card-image" src="img/<?= $row['item_img'] ?>" alt="Item Image">
            <p id="card-overview"></p>
            <form action="item.php" method="post" class="card-form">
                <input type="hidden" name="item_id" id="card-item_id">
                <input type="hidden" name="item_name" id="card-item_name">
                <input type="hidden" name="item_img" id="card-item_img">
                <p id="card-overview"></p>

                <input type="hidden" name="item_overview" id="card-item_overview">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                <label for="booking_date">Booking Date:</label><br>
                <input type="text" id="booking_date" name="booking_date" required><br>
                <label for="start_time">Start Time:</label><br>
                <input type="time" name="start_time" id="start_time" required min="08:00" max="16:00"><br>
                <label for="end_time">End Time:</label><br>
                <input type="time" name="end_time" id="end_time" required min="09:00" max="17:00"><br>
                <label for="quantity">Quantity: </label><br>
                <input type="number" name="quantity" id="card-quantity" value="1" min="1" max="10" required><br>
                <button name="item_book">Book</button>
            </form>
        </div>
    </form>
    <!-- Pagination Navigation -->
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
    document.getElementById("itemButton").addEventListener("click", function () {
    location.href = 'item.php';
});
document.getElementById("placeButton").addEventListener("click", function () {
    location.href = 'place.php';
});

  
    var clickableElement = document.getElementById('clickable-div');

    var itemCard = document.getElementById('itemCard');

    clickableElement.addEventListener('click', function (event) {

        itemCard.style.display = 'block';
    });

    // Calendar display
    $(function () {
        $("#booking_date").datepicker({
            minDate: 0,
            beforeShowDay: function (date) {
                var day = date.getDay();
                console.log($("#booking_date"))
                return [(day !== 0 && day !== 6)];
            },
            onClose: function (selectedDate) {
                var selected = new Date(selectedDate);
                var today = new Date();
                today.setHours(0, 0, 0, 0);

                if (selected < today) {
                    $(this).val('');
                }
            }
        });
    });


    var closeButton = document.getElementById('closeButton');
    var cardDiv = document.getElementById('itemCard');

    closeButton.addEventListener("click", () => {
        cardDiv.style.display = 'none';
    });

    cardDiv.addEventListener("click", (e) => {
        e.stopPropagation();
    });



    var clickableLinks = document.querySelectorAll('a[data-item-overview]');

    clickableLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
            event.preventDefault();

            var itemOverview = link.getAttribute('data-item-overview');
            var itemImg = link.querySelector('img').getAttribute('src');
            var itemName = link.getAttribute('data-item-name');
            var href = link.getAttribute('href');
            var itemId = href.split('=')[1];

            console.log("Equipment ID:", itemId);

            document.getElementById('card-item_img').value = itemImg;
            document.getElementById('card-item_name').value = itemName;
            document.getElementById('card-item_overview').textContent = itemOverview;

            var cardImage = document.getElementById('card-image');
            cardImage.src = itemImg;

            var cardDiv = document.getElementById('itemCard');
            cardDiv.style.display = 'block';
        });
    });





    $(function () {
        var currentTime = new Date();
        var currentHour = currentTime.getHours();
        var currentMinute = currentTime.getMinutes();


        var minTime = currentHour + ':' + (currentMinute < 10 ? '0' + currentMinute : currentMinute);

        $('#start_time, #end_time').timepicker({
            timeFormat: 'HH:mm',
            interval: 60,
            minTime: minTime,
            maxTime: '17:00',
            startTime: minTime,
            dynamic: true,
            dropdown: true,
            scrollbar: true,
            change: function (time) {
                var selectedStartTime = $('#start_time').val();
                var selectedEndTime = $('#end_time').val();

                if (selectedStartTime >= selectedEndTime) {
                    var newEndTime = new Date('2000-01-01T' + selectedStartTime + ':00');
                    newEndTime.setHours(newEndTime.getHours() + 1);
                    $('#end_time').timepicker('setTime', newEndTime.toTimeString().slice(0, 5));
                    console.log($('#start_time, #end_time'))
                }
            }
        });
    })


</script>