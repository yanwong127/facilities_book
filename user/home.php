<?php
include_once('db.php');
include_once('header.php');

if ($_SESSION['true'] !== true) {
    header("location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM `user` WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    header("location: unauthorized.php");
    exit;
}

$itemQuery = "SELECT * FROM `item` LIMIT 4";
$itemResult = mysqli_query($conn, $itemQuery);

$placeQuery = "SELECT * FROM `place` LIMIT 4";
$placeResult = mysqli_query($conn, $placeQuery);


$records_per_page = 4;
if (isset($_GET['item_page'])) {
    $page = $_GET['item_page'];
} else {
    $page = 1;
}
$set = ($page - 1) * $records_per_page;
$jj = "SELECT * FROM `item` WHERE availability <> 'Not Working' LIMIT $set,$records_per_page";
$result = mysqli_query($conn, $jj);

$records_per_page1 = 4;
if (isset($_GET['place_page'])) {
    $place_page = $_GET['place_page'];
} else {
    $place_page = 1;
}
$set2 = ($place_page - 1) * $records_per_page1;
$jj2 = "SELECT * FROM `place` WHERE availability <> 'Not Working' LIMIT $set2,$records_per_page1";
$result2 = mysqli_query($conn, $jj2);

// Reset the data pointer to the beginning of the result set
mysqli_data_seek($itemResult, 0);
mysqli_data_seek($placeResult, 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    .w3-sidebar a {font-family: "Roboto", sans-serif}
    body,h1,h2,h3,h4,h5,h6,.w3-wide {font-family: "Montserrat", sans-serif;}
  </style>
  <title>Your Title</title>
</head>
<div style="border-left: 3px solid #000;">
<header class="w3-container w3-xlarge">
    <p class="w3-left">HOME</p>
    <p class="w3-right">

    </p>
  </header>
  <div style="width: 99%; margin-left: auto;">
<body class="w3-content" style="max-width:1200px">

<div class="w3-panel w3-border w3-round-xlarge w3-content w3-display-container" style="width:100%; height: 300px;">
  <img class="mySlides" src="img/basketball.jpg">
  <img class="mySlides" src="img/科学室.jpg">
  <img class="mySlides" src="img/游泳池.jpg">
  <img class="mySlides" src="img/键盘.jpg">

  <button class="w3-button w3-black w3-display-left" onclick="plusDivs(-1)">&#10094;</button>
  <button class="w3-button w3-black w3-display-right" onclick="plusDivs(1)">&#10095;</button>
</div>

<div class="w3-panel w3-border w3-round-xlarge">
  <div class="w3-container w3-text-grey" id="jeans">
    <p>Item</p>
  </div>

  <div class="w3-row">
    <?php while ($row = mysqli_fetch_array($itemResult)) { ?>
      <div class="w3-col l3 s6">
        <div class="w3-container">
          <a href="item.php">
            <img class="rounded-image" src="img/<?= $row['item_img'] ?>" alt="<?= $row['item_name'] ?>" style="width: 200px; height: 200px;">
            <div class="item-name">
              <?= $row['item_name'] ?>  
            </div>
          </a>
        </div>
      </div>
    <?php } ?>
  </div>
</div>

<div class="w3-panel w3-border w3-round-xlarge">
  <div class="w3-container w3-text-grey" id="jeans">
    <p>Place</p>
  </div>

  <div class="w3-row">
    <?php while ($row = mysqli_fetch_array($placeResult)) { ?>
      <div class="w3-col l3 s6">
        <div class="w3-container">
          <a href="place.php">
            <img class="rounded-image" src="img/<?= $row['place_img'] ?>" alt="<?= $row['place_name'] ?>" style="width: 200px; height: 200px;">
            <div class="place-name">
              <?= $row['place_name'] ?>  
            </div>
          </a>
        </div>
      </div>
    <?php } ?>
  </div>
</div>
</div>  
</div>
<script>
  var slideIndex = 1;
  showDivs(slideIndex);

  setInterval(function() {
    plusDivs(1);
  }, 3000);

  function plusDivs(n) {
    showDivs(slideIndex += n);
  }

  function showDivs(n) {
    var i;
    var x = document.getElementsByClassName("mySlides");
    if (n > x.length) { slideIndex = 1; }
    if (n < 1) { slideIndex = x.length; }
    for (i = 0; i < x.length; i++) {
      x[i].style.display = "none";
    }
    x[slideIndex - 1].style.display = "block";
  }

  function navigateTo(destination) {
    window.location.href = destination;
  }
</script>
</body>
</html>
<style>
 .w3-content-container {
    border: 2px solid #ccc;
    object-fit:cover;
    width: 80%;
    margin: 0 auto;
  }

  .mySlides {
    transition: opacity 3s ease-in-out;
    width: 100%;
    height: 100%;
    object-fit: cover; /* 裁剪并填充，确保照片完全覆盖框 */
  }

  .w3-container img {
    width: 100%;
    height: 300px;
    object-fit: cover; /* 裁剪并填充，确保照片完全覆盖框 */
  }

  .rounded-image {
    border-radius: 10px; /* 圆角样式，根据需要调整 */
  }
</style>