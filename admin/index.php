<?php

include_once('db.php');

$sql = "SELECT * FROM `admin`";
$home = mysqli_query($conn, $sql);


if($_SESSION['true'] != true){
    echo 'not true';
    header("location:login.php");
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body>
     
    <style>
        body,h1,h2,h3,h4,h5,h6 {font-family: "Lato", sans-serif}
        .w3-bar,h1,button {font-family: "Montserrat", sans-serif}
        .fa-anchor,.fa-coffee {font-size:200px}
    </style>
    
    <header class="w3-container w3-black w3-center"style="padding:150px">

    
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<div>
    <?php while($row= mysqli_fetch_array($home)) {?>
        <h1 class="w3-margin w3-jumbo">Welcome <?=$row['admin_name']?></h1>
    <?php    }?>
        <p class="w3-xlarge">Can start your work</p>
        <button class="w3-button w3-grey w3-padding-large w3-large w3-margin-top" onclick="window.location.href='home.php'">Get Started</button>
</header>
</div>


</body>
</html>