<?php
include_once('db.php');

// 执行查询以选择用户
$query = "SELECT user_id, username FROM `user`"; // 选择ID和用户名字段
$result = mysqli_query($conn, $query);

?>


<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">   

    <title>Facilities Book</title>
    <style>
        /* 样式侧边栏 */
        .sidebar {
            width: 250px;
            background-color: #333;
            color: #fff;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
        }

        /* 样式主内容区域 */
        .main-content {
            margin-left: 270px; /* 留出侧边栏的宽度 */
            padding: 20px;
        }

        /* 样式链接 */
        .sidebar a {
            text-decoration: none;
            color: #fff;
            display: block;
            margin: 10px 0;
        }

        /* 鼠标悬停链接时的样式 */
        .sidebar a:hover {
            color: #FFA500; /* 橙色 */
        }
        
        @media only screen and (max-width: 460px) {
        
        }

    </style>
</head>
<body>
    
<div class="sidebar">   
<?php while($row = mysqli_fetch_array($result)) { ?>
    <h1><?= $row['username'] ?></h1>
    <ul style="display: flex; flex-direction: column; gap: 20px;">
        <li class="text-grey-900"><a href="home.php">Home</a></li>
        <li><a href="profile.php">User Profile</a></li>
        <li><a href="booking.php">Booking Progress</a></li>
        <li><a href="contact.php">Contact</a></li> 
        <li style="margin-top: auto;"><a href="logout.php">Logout</a></li> 
    </ul>
    <?php } ?>
</div>



    
</body>
</html>
