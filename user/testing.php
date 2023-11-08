<?php 
include_once("db.php");

$stock = "SELECT  * FROM item";
$sql= mysqli_query($conn,$stock);

if (isset($_POST['new'])) { 
    $filename = $_FILES['myfile']['name'];

    $destination = 'img/' . $filename;

    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    $file = $_FILES['myfile']['tmp_name'];

    if (!in_array($extension, ['jpg', 'png', 'jpeg'])) {
        echo "You file extension must be .jpg, .png or .jpeg";
    } elseif ($_FILES['myfile']['size'] > 100000000) { 
        echo "File too large!";
    } else {
        if (move_uploaded_file($file, $destination)) {
            // $quantity=$_POST['quantity'];
            // $name=$_POST['name'];
            // $price=$_POST['price'];
            $sql = "INSERT INTO place(`place_img`) VALUES ('$filename')";
            if (mysqli_query($conn, $sql)) {
                // $name=$_POST['name']; 
                // $abc = "INSERT INTO stock_history(`item_id`,`quantity`,`item_name`) VALUES ((SELECT `item_id` FROM `item` WHERE `item_name` = '$name'), '$quantity','$name')"; 
                // $result = mysqli_query($conn,$abc); 
                // echo "<script>window.location.href ='index.php';
                // alert('Successfully Instock.');</script>";     
            }
        } else {
                echo "Stock to upload file.";
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
     
<form action="testing.php" method="post" enctype="multipart/form-data" >
    <div>
        <label>Image:</label><br>
          <input type="file" name='myfile'> <br>
    </div>
  <!-- <div>
        <label>Item Name:</label><br>
        <input type="text" name='name'>
    </div>
 
 <div>
        <label>Price:</label><br>
        <input type="varchar" name='price'>
    </div>

  <div>
        <label>Quantity:</label><br>
        <input type="number" name='quantity'>
    </div> -->

    <div><br>
        <input type="submit" name="new" value="Stock">
    </div>
    
</form>
 </body>
 </html>