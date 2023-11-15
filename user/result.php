<?php
include_once('db.php');
include_once('header.php');

if ($_SESSION['true'] != true) {
    echo 'not gg';
    header("location: logout.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$item_query = "SELECT * FROM `item_appointment` WHERE user_id = $user_id AND status = 'active'";
$item_result = mysqli_query($conn, $item_query);

$place_query = "SELECT * FROM `place_appointment` WHERE user_id = $user_id AND status = 'active'";
$place_result = mysqli_query($conn, $place_query);

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
                </tr>
            <?php } ?>


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
                </tr>
            <?php } ?>

        </table>
    </div>




</body>

</html>

<style>
    .ctable {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 50vh;sd
    }

    .rounded-image {
        border-radius: 20px;
        width: 200px;
        height: auto;
    }
</style>