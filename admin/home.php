<?php

include_once('db.php');
include_once('header.php');

if($_SESSION['true'] != true){
    echo 'not true';
    header("location:login.php");
    exit;
}

$gg = "SELECT * FROM `booking`";
$booking = mysqli_query($conn,$gg);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}
  
</style>

</head>
<body>
  

  <table>
        <tr>
          <th>User ID</th>
          <th>Facilities Name</th>
          <th>Facilities Picture</th>
          <th>Booked By</th>
          <th>Start Time</th>
          <th>End Time</th>
          <th>Status</th>
        </tr>

  <?php while($row=mysqli_fetch_array($booking)) {?>
        <tr>
          <td><?=$row['user_id']?></td>
          <td><?=$row['facilities_name']?></td>
          <td><?=$row['pic']?></td>
          <td><?=$row['booked_by']?></td>
          <td><?=$row['start_time']?></td>
          <td><?=$row['end_time']?></td>
          <td><?=$rom['status']?></td>
        </tr>
  <?php }?>
  </table>

</body>
</html>