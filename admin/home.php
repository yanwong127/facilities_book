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
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="theme-color" content="#3e454c">
    
  <title>Home</title>


	<!-- Font awesome -->
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<!-- Sandstone Bootstrap CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<!-- Bootstrap Datatables -->
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<!-- Bootstrap social button library -->
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<!-- Bootstrap select -->
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<!-- Bootstrap file input -->
	<link rel="stylesheet" href="css/fileinput.min.css">
	<!-- Awesome Bootstrap checkbox -->
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<!-- Admin Stye -->
	<link rel="stylesheet" href="css/style.css">

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


  <script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>
</body>
</html>