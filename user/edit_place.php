<?php
include_once('db.php');

if(isset($_POST['edit']) && isset($_GET['id'])){
    $booking_date = $_POST['booking_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $Query="UPDATE `place_appointment` SET booking_date='$booking_date', start_time='$start_time', end_time='$end_time' WHERE placebook_id = '".$_GET['id']."'";

    if($result = mysqli_query($conn, $Query)){     
        echo "<script>window.location.href = 'booking.php';alert('Record Successfully Edited');</script>";     
    }else{          
        echo "<script>alert('Record Fails to Edit')</script>";
    }
}

?>

<?php

$qry = "SELECT * FROM `place_appointment` WHERE placebook_id='".$_GET['placebook_id']."'";
$sql = mysqli_query($conn, $qry);
$row = mysqli_fetch_array($sql);

?>

<div class="container">
    <form class="form-horizontal" action="edit_place.php?id=<?=$_GET['placebook_id']?>" method="post">
        <div>
            <label>Booking Date:</label>
            <div>
                <input type="date" name="booking_date" value="<?=$row['booking_date']?>" required>
            </div>
        </div>
        <div>
            <label>Start Time:</label>
            <div>
                <input type="time" name="start_time" value="<?=$row['start_time']?>" required>
            </div>
        </div>
        <div>
            <label>End Time:</label>
            <div>
                <input type="time" name="end_time" value="<?=$row['end_time']?>" required>
            </div>
        </div>
        <br />
        <div>
            <div>
                <button type="submit" name="edit" value="edit">Submit</button>
                <button type="button" onclick="window.location.href='booking.php'">Back</button>
            </div>
        </div>
    </form>
</div>
