<?php

// session_start(); 

include_once('db.php');
include_once('header.php');

$item = "SELECT * FROM `item`";
$qry = mysqli_query($conn, $item);

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

    .item-row {
        align-items: center;
        justify-content: center;
    }

    .item-container img {
        max-width: 300px; 
        max-height: 300px; 
    }

    .item-name {
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
            <div class="item-row">
            <div class="item-container" id="none">
                <a href="itemDetail.php?id=<?= $row['item_id'] ?>">
                <img src="img/<?= $row['item_img'] ?>">
                <div class="item-name"><?= $row['item_name'] ?></div>
                 </a>
            </div>

            </div>
        </div>
    <?php } ?>
</div>

</body>
</html>