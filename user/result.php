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
    WHERE ia.`user_id` = $user_id AND ia.`status` = 'Active')

    UNION

    (SELECT 'place' as type, pa.placebook_id as book_id, pa.place_img as img, pa.place_name as name, pa.booking_date, pa.start_time, pa.end_time, pa.status
    FROM `place_appointment` pa
    INNER JOIN `item_appointment` ia ON pa.user_id = ia.user_id
    WHERE pa.`user_id` = $user_id AND pa.`status` = 'Active')
    LIMIT $offset, $records_per_page
";

$result = mysqli_query($conn, $query);


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
    <?php if (mysqli_num_rows($result) > 0) { ?>
        <table>
            <!-- ... (Your existing table rows) -->
        </table>
    <?php } else { ?>
        <div class="no-appointments">
            <p>No appointments found.</p>
            <p>Feel free to schedule new appointments!</p>
        </div>
    <?php } ?>
        <table>
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


    <div class="pagination justify-content-center">
        <?php
        $item_count_query = "SELECT COUNT(*) FROM `item_appointment` WHERE `user_id` = $user_id AND `status` = 'Active'";
        $item_count_result = mysqli_query($conn, $item_count_query);
        $item_row = mysqli_fetch_row($item_count_result);
        $item_records = $item_row[0];

        $place_count_query = "SELECT COUNT(*) FROM `place_appointment` WHERE `user_id` = $user_id AND `status` = 'Active'";
        $place_count_result = mysqli_query($conn, $place_count_query);
        $place_row = mysqli_fetch_row($place_count_result);
        $place_records = $place_row[0];

        $total_records = $item_records + $place_records;
        $total_pages = ceil($total_records / $records_per_page);

        if ($page > 1) {
            echo "<a href='result.php?page=" . ($page - 1) . "'>Prev</a>";
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            echo "<a " . ($i == $page ? "class='actActiveive'" : "") . " href='result.php?page=" . $i . "'>" . $i . "</a>";
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

    .pagination a.Active {
        background-color: dodgerblue;
        color: white;
    }

    .pagination a:hover:not(.Active) {
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

</style>