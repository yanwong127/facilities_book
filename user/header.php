<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <title>Facilities Book</title>
</head>
<body>

<div class="header">
    <h3>Facilities Book</h3>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="profile.php">User Profile</a></li>
        <li><a href="booking_item.php">Booking Progress</a></li>
        <li><a href="result_item.php">Booking Result</a></li>
        <li><a href="history_item.php">History</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

</body>
</html>

<style>
/* Your existing styles */

.header {
  background-color: #333;
  color: #fff;
  padding: 20px;
  display: flex;
  align-items: center;
  position: fixed;
  width: 100%;
  z-index: 1000; /* Set a higher value to ensure it's on top */
}

.header h3 {
  margin-right: auto;
}

.header ul {
  list-style-type: none;
  display: flex;
  gap: 20px;
  margin: 0;
  padding: 0; /* 添加这行以移除默认的内边距 */
}

.header a {
  text-decoration: none;
  color: #fff;
}

.header a:hover {
  color: #FFA500; 
}

/* Your button styles */

.button-1 {
  /* Your existing styles */
}
</style>
