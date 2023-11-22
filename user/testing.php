<?php
include_once("db.php");

$stock = "SELECT  * FROM item";
$sql = mysqli_query($conn, $stock);

if (isset($_POST['new'])) {
    $filename = $_FILES['myfile']['name'];

    $destination1 = 'img/' . $filename;  // First folder
    $destination2 = '../admin/img/' . $filename;  // Second folder

    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $file = $_FILES['myfile']['tmp_name'];

    if (!in_array($extension, ['jpg', 'png', 'jpeg'])) {
        echo "You file extension must be .jpg, .png or .jpeg";
    } elseif ($_FILES['myfile']['size'] > 100000000) {
        echo "File too large!";
    } else {
        if (move_uploaded_file($file, $destination1)) {
            // Move to the second folder
            if (file_exists($destination1)) {
                if (copy($destination1, $destination2)) {
                    $sql = "INSERT INTO item(`item_img`) VALUES ('$filename')";
                    if (mysqli_query($conn, $sql)) {
                        // Additional logic if needed
                        // $name=$_POST['name']; 
                        // $abc = "INSERT INTO stock_history(`item_id`,`quantity`,`item_name`) VALUES ((SELECT `item_id` FROM `item` WHERE `item_name` = '$name'), '$quantity','$name')"; 
                        // $result = mysqli_query($conn,$abc); 
                        // echo "<script>window.location.href ='index.php';
                        // alert('Successfully Instock.');</script>";
                    }
                } else {
                    echo "Failed to copy file to the second folder.";
                }
            } else {
                echo "Source file does not exist in the first folder.";
            }
        } else {
            echo "Failed to upload file.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock</title>
</head>
<body>
    <form action="testing.php" method="post" enctype="multipart/form-data">
        <div>
            <label>Image:</label><br>
            <input type="file" name='myfile'> <br>
        </div>
        <div><br>
            <input type="submit" name="new" value="Stock">
        </div>
    </form>
</body>
</html>
