<?php
include_once('db.php');
include_once('header.php');

if($_SESSION['true'] != true){
    echo 'not gg';
    header("location:logout.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<style>
    .place {
        margin-left: 10px;
    }
    .title {
        text-align: center;
    }

    /* 隐藏 "Place" 内容 */
    #place-content {
        display: none;
    }
</style>
</head>
<body>
    <div class="title">
        <h1>HOME</h1>
    </div>
    
    <div class="place">
        <button id="showProduct">Product</button>
        <button id="showPlace">Place</button>
    </div>
    <br>

    <div id="item-content">
        <?php include_once('item.php'); ?>
    </div>

    <div id="place-content">
        <?php include_once('place.php'); ?>
    </div>

    <?php
    include_once('footer.php');
    ?>
</body>
</html>

<script>
var showPlaceButton = document.getElementById('showPlace');
var showProductButton = document.getElementById('showProduct');

var itemContent = document.getElementById('item-content');
var placeContent = document.getElementById('place-content');

showProductButton.addEventListener('click', function() {
    itemContent.style.display = 'block';
    placeContent.style.display = 'none';
});

showPlaceButton.addEventListener('click', function() {
    itemContent.style.display = 'none';
    placeContent.style.display = 'block';
});
</script>
