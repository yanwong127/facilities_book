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
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Header</title>
</head>     
<body>
    


  <div class="w3-bar w3-light-grey">
    <a href="#" class="w3-bar-item w3-button">Home</a>
    <div class="w3-dropdown-hover">
      <button class="w3-button">Item</button>
      <div class="w3-dropdown-content w3-bar-block w3-card-4">
        <a href="#" class="w3-bar-item w3-button">Add Item</a>
        <a href="#" class="w3-bar-item w3-button">Edit Item</a>
      </div>
    </div>
    <div class="w3-dropdown-hover">
      <button class="w3-button">Place</button>
      <div class="w3-dropdown-content w3-bar-block w3-card-4">
        <a href="#" class="w3-bar-item w3-button">Add Place</a>
        <a href="#" class="w3-bar-item w3-button">Edit Place</a>
      </div>
    </div>
    <a href="#" class="w3-bar-item w3-button">Student Info</a>
    <div class="w3-dropdown-hover">
      <button class="w3-button">Booking</button>
      <div class="w3-dropdown-content w3-bar-block w3-card-4">
        <a href="#" class="w3-bar-item w3-button">Booking History</a>
        <a href="#" class="w3-bar-item w3-button">Booking Status</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>