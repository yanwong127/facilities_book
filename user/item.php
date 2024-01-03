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
        $insertQuery = "INSERT INTO `item_appointment` (item_id, item_name, item_img, user_id, start_time, end_time, booking_date, status, quantity) VALUES ('$item_id', '$item_name', '$item_img', '$user_id', '$start_time', '$end_time', '$mysqlDateFormat', 'Unactive', '$quantity')";
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
    <title>Equipment Page</title>
    <link rel="stylesheet" href="bookingpage.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/smoothness/jquery-ui.css">

</head>
<style>
    #itemCard {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 400px; /* 适当调整最大宽度 */
        max-height: 70vh; /* 适当调整最大高度 */
        overflow-y: auto; /* 添加滚动条 */
    }
</style>
<body>
    <header class="w3-container w3-xlarge">
        <p class="w3-left">Equipment</p>
        <p class="w3-right">
        <button class="btn" id="itemButton">Equipment</button>
        <button class="btn" id="placeButton">Place</button>
        </p>
    </header>
    <!-- This is show out a data of item list -->
    <div class="custom-table" id="clickable-div">

        <?php
        $i = 0;
        while ($row = mysqli_fetch_array($result)) {
            $item = "SELECT SUM(quantity) FROM item_appointment WHERE item_id='$row[item_id]' AND status='Approve' GROUP BY item_id";

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
                    <a href="item.php?id=<?= $row['item_id'] ?>" data-item-overview="<?= $row['item_overview'] ?>">
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
            <button name="item_book">Book</button><br>
        </form>
    </div>

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
// Is a navigation to other page.
document.getElementById("itemButton").addEventListener("click", function() {
    location.href = 'item.php';
    });
    document.getElementById("placeButton").addEventListener("click", function() {
    location.href = 'place.php';
    });

//A Card DIV

var clickableElement = document.querySelector('a[data-item-overview]');


    // 获取卡片
    var itemCard = document.getElementById('itemCard');

    // 添加点击事件监听器
    clickableElement.addEventListener('click', function(event) {
    event.preventDefault();

    var clickedElement = event.target;
    var itemOverview = clickedElement.getAttribute('data-item-overview');
    var href = clickedElement.getAttribute('href');
    var itemId = href.split('=')[1];

    // 获取item_id
    var item_id = itemId;
    document.getElementById('card-item_id').value = itemId;
    document.getElementById('card-overview').innerText = itemOverview;


    // 显示对应卡片
    var cardDiv = document.getElementById('itemCard');
    cardDiv.style.display = 'block';

        // 显示卡片
        // itemCard.style.display = 'block';
    });

// Calendar display
    $(function() {
            $("#booking_date").datepicker({
                minDate: 0, // Disable dates before today
                beforeShowDay: function(date) {
                    var day = date.getDay();
                    // Disable Saturdays (6) and Sundays (0)
                    console.log($("#booking_date"))
                    return [(day !== 0 && day !== 6)];
                },
                onClose: function(selectedDate) {
                    var selected = new Date(selectedDate);
                    var today = new Date();
                    today.setHours(0, 0, 0, 0); // Set hours to 0 for accurate comparison

                    // Check if the selected date is before today, if so, reset the date
                    if (selected < today) {
                        $(this).val('');
                    }
                }
            });
        });
            
       
//Close a div card

    // 获取关闭按钮和卡片元素
    var closeButton = document.getElementById('closeButton'); // 假设关闭按钮的ID是 closeButon
    var cardDiv = document.getElementById('itemCard'); // 假设卡片的ID是 itemCard

    // 添加点击事件监听器到关闭按钮
    closeButton.addEventListener("click", () => {
        // 关闭卡片
        cardDiv.style.display = 'none';
    });

    // 添加点击事件监听器到卡片上的所有元素，防止点击卡片内容时关闭卡片
    cardDiv.addEventListener("click", (e) => {
        e.stopPropagation(); // 阻止事件传播，确保点击卡片内容不会关闭卡片
    });


// Get the equipment id
    // 获取所有的链接元素
    var clickableLinks = document.querySelectorAll('a[data-item-overview]');

    // 添加点击事件监听器到每个链接元素
    clickableLinks.forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // 阻止链接的默认行为，即不进行页面跳转

            // 获取对应的卡片数据
            var itemOverview = link.getAttribute('data-item-overview');
            var href = link.getAttribute('href');
            var itemId = href.split('=')[1]; // 假设链接格式为 testing.php?id=123，这里将分割字符串以获取ID部分

            console.log("Equipment ID:", itemId);

            // 显示对应卡片
            var cardDiv = document.getElementById('itemCard');
            cardDiv.style.display = 'block';

            document.getElementById('card-item_id').value = itemId;

            // 这里你可能可以根据需要，填充卡片中的内容
        });
    });

//Timer
    $(function(){
        var currentTime = new Date();
        var currentHour = currentTime.getHours();
        var currentMinute = currentTime.getMinutes();

        // 将当前时间设置为最小时间
        var minTime = currentHour + ':' + (currentMinute < 10 ? '0' + currentMinute : currentMinute);

        // 初始化时间选择器
        $('#start_time, #end_time').timepicker({
            timeFormat: 'HH:mm',
            interval: 60,
            minTime: minTime, // 设置最小时间为当前时间
            maxTime: '17:00',
            startTime: minTime, // 设置开始时间为当前时间
            dynamic: true,
            dropdown: true,
            scrollbar: true,
            change: function(time) {        
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

// let startTimeInput = document.getElementById('start_time');
// let endTimeInput = document.getElementById('end_time');

// startTimeInput.addEventListener('input', function() {
//     let selectedStartTime = startTimeInput.value;
//     let selectedEndTime = endTimeInput.value;

//     let minStartTime = '08:00';
//     let maxStartTime = '16:00';

//     // 检查当前时间是否在允许的预订时间之前
//     let currentTime = new Date();
//     let hours = currentTime.getHours();
//     let minutes = currentTime.getMinutes();
//     let formattedCurrentTime = ${hours < 10 ? '0' + hours : hours}:${minutes < 10 ? '0' + minutes : minutes};

//     if (formattedCurrentTime < minStartTime) {
//         startTimeInput.value = minStartTime;
//     }

//     // 检查选择的开始时间是否超出范围
//     if (selectedStartTime < minStartTime || selectedStartTime > maxStartTime) {
//         startTimeInput.value = minStartTime;
//     }

//     // 检查选择的结束时间是否在开始时间之前，如果是则设置结束时间为开始时间后一小时
//     if (selectedEndTime <= selectedStartTime) {
//         let newEndTime = new Date('2000-01-01T' + selectedStartTime + ':00');
//         newEndTime.setHours(newEndTime.getHours() + 1);
//         endTimeInput.value = newEndTime.toTimeString().slice(0, 5);
//     }
// });

// endTimeInput.addEventListener('input', function() {
//     let selectedStartTime = startTimeInput.value;
//     let selectedEndTime = endTimeInput.value;

//     let minEndTime = '09:00';
//     let maxEndTime = '17:00';

//     // 检查选择的结束时间是否超出范围
//     if (selectedEndTime < minEndTime || selectedEndTime > maxEndTime) {
//         endTimeInput.value = minEndTime;
//     }

//     // 检查选择的结束时间是否在开始时间之前，如果是则设置结束时间为开始时间后一小时
//     if (selectedEndTime <= selectedStartTime) {
//         let newEndTime = new Date('2000-01-01T' + selectedStartTime + ':00');
//         newEndTime.setHours(newEndTime.getHours() + 1);
//         endTimeInput.value = newEndTime.toTimeString().slice(0, 5);
//     }
// });

</script>