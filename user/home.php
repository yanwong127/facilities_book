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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <title>半透明框与背景图</title>
</head>
<body>
  <div class="body">
    <div class="overlay-container">
      <div class="transparent-box">
        <div class="text-above">
          <p>Welcome</p>
        </div>
        <div class="content">
          <div class="left-content" onclick="navigateTo('item.php')">
            <p class="item">Item</p>
          </div>
          <div class="divider"></div>
          <div class="right-content" onclick="navigateTo('place.php')">
            <p class="place">Place</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function navigateTo(destination) {
      window.location.href = destination;
    }
  </script>
</body>
</html>

<style>
.body {
  margin: 0;
  padding: 0;
  background: url('img/home.jpg') center/cover no-repeat;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow-Y: hidden;
}

.overlay-container {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.transparent-box {
  background-color: rgba(0, 0, 0, 0.7);
  padding: 80px;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}

.text-above {
  text-align: center;
  margin-bottom: 20px;
}

.text-above p {
  color: #fff;
  font-size: 24px;
}

.content {
  display: flex;
  align-items: center;
}

.divider {
  width: 2px;
  height: 60px;
  background-color: #fff;
  margin: 0 20px;
}

.left-content,
.right-content {
  flex: 1;
  text-align: center;
  cursor: pointer;
}

.item,
.place {
  color: #fff;
  transition: font-size 0.3s ease;
}

.item:hover,
.place:hover {
  font-size: 18px;
}
</style>
