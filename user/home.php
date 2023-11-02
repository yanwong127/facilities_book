<?php

session_start(); 

include_once('db.php');
include_once('sidebar.php');

if($_SESSION['true'] != true){
    echo 'not gg';
    header("location:logout.php");
    exit;
}

$item = "SELECT * FROM `item`";
$qry = mysqli_query($conn, $item);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    
<style>

    body {
        margin-left: 300px;
    }

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

    .item-img img {
        width: 100%; 
        height: auto; 
    }

    .item-name {
        font-weight: bold;
    }

    .title {
        text-align: center;
    }

    #clickable-div:hover {
    cursor: pointer; 
    }

    a {
        text-decoration-line : none;
        color: black;
    }
    
    .place {
        margin-left: 10px;
    }

</style>

</head>
<body>

    <div class="title">
        <h1>HOME</h1>
    </div>

    <div class="place">
        <button>Product</button>
        <button>Place</button>
    </div>
    <br>

<div class="custom-table" id="clickable-div">
    <?php while ($row = mysqli_fetch_array($qry)) { ?>
        <div class="td"> 
            <div class="item-row">
            <!-- data-itemid="<?= $row['item_id'] ?>" -->
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
