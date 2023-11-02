<?php
// 连接到数据库
include_once('db.php');

// 检查是否提供了 id 参数
if (isset($_GET['id'])) {
    $item_id = $_GET['id'];

    // 查询数据库以获取所选项目的详细信息
    $query = "SELECT * FROM `item` WHERE item_id = $item_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $item_name = $row['item_name'];
        $item_img = $row['item_img'];
        // 这里可以继续获取其他项目属性

        // 显示项目详细信息
        echo "<h1>Item Details</h1>";
        echo "<p>Item Name: $item_name</p>";
        echo "<img src='img/$item_img' alt='$item_name'>";
        // 添加其他项目属性的显示

    } else {
        echo "Item not found.";
    }
} else {
    echo "Item ID not provided.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Item Details</title>
</head>
<body>
    <!-- 你可以在这里添加更多页面内容，如样式、按钮、其他信息等 -->
</body>
</html>
