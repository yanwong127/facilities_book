<?php

include_once('db.php');
include_once('home.php');


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
	  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	  <meta name="description" content="">
	  <meta name="author" content="">
	  <meta name="theme-color" content="#3e454c">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Header</title>



	<!-- Admin Stye -->
	<link rel="stylesheet" href="css/style.css">
</head>     
<body>
    

<div class="brand clearfix">
    <a href="home.php" class="w3-bar-item w3-button" style="font-size: 22px;">Home</a>
    <div class="w3-dropdown-hover">
      <button class="w3-button" style="font-size: 22px;">Item</button>
      <div class="w3-dropdown-content w3-bar-block w3-card-4">
      <a href="item.php" class="w3-bar-item w3-button" style="font-size: 20px;">Item List</a>
        <a href="add_item.php" class="w3-bar-item w3-button" style="font-size: 20px;">Add Item</a>
        <a href="edit_item.php" class="w3-bar-item w3-button" style="font-size: 20px;">Edit Item</a>
      </div>
    </div>
    <div class="w3-dropdown-hover">
      <button class="w3-button" style="font-size: 22px;">Place</button>
      <div class="w3-dropdown-content w3-bar-block w3-card-4">
      <a href="place.php" class="w3-bar-item w3-button" style="font-size: 20px;">Place List</a>
        <a href="add_place.php" class="w3-bar-item w3-button" style="font-size: 20px;">Add Place</a>
        <a href="edit_place.php" class="w3-bar-item w3-button" style="font-size: 20px;">Edit Place</a>
      </div>
    </div>
    <a href="#" class="w3-bar-item w3-button" style="font-size: 22px;">Student Info</a>
    <div class="w3-dropdown-hover">
      <button class="w3-button" style="font-size: 22px;">Booking</button>
      <div class="w3-dropdown-content w3-bar-block w3-card-4">
        <a href="#" class="w3-bar-item w3-button" style="font-size: 20px;">Booking History</a>
        <a href="#" class="w3-bar-item w3-button" style="font-size: 20px;">Booking Status</a>
      </div>
</div>
<ul class="ts-profile-nav">
			
			<li class="ts-account">
				<a href="#"><img src="img/ts-avatar.jpg" class="ts-avatar hidden-side" alt=""> Account <i class="fa fa-angle-down hidden-side"></i></a>
				<ul>
					<li><a href="change-password.php">Change Password</a></li>
					<li><a href="logout.php">Logout</a></li>
				</ul>
			</li>
		</ul>
  </div>
</div>
</div>

</body>
</html>