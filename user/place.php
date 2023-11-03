<?php

// session_start(); 

include_once('db.php');
include_once('header.php');

$place = "SELECT * FROM `place`";
$qry = mysqli_query($conn, $place);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>    

<style>

       .custom-table {
        display: grid;
        grid-template-columns: repeat(3, 1fr); 
        grid-gap: 16px; 
    }

    .custom-table .td {
        display: grid;
        grid-template-columns: 1fr;
        grid-template-rows: auto auto; 
        text-align: center;
    }

    .place-row {
        align-items: center;
        justify-content: center;
    }

    .item-img img {
        width: 100%; 
        height: auto; 
    }

    .place-name {
        font-weight: bold;
    }

  

    #clickable-div:hover {
    cursor: pointer; 
    }

    a {
        text-decoration-line : none;
        color: black;
    }
    
</style>

<body>

<div class="custom-table" id="clickable-div">
    <?php while ($row = mysqli_fetch_array($qry)) { ?>
        <div class="td"> 
            <div class="place-row">
            <div class="item-img" id="none">
                <!-- <a href="itemDetail.php?id=<?= $row['place_id'] ?>"> -->
                <img src="img/<?= $row['place_img'] ?>">
                <div class="place-name"><?= $row['placename'] ?></div>
                 <!-- </a> -->
            </div>

            </div>
        </div>
    <?php } ?>
</div>

</body>
</html>