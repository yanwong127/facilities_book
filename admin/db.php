<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// $server = "sv77.ifastnet.com";
// $user   = "hrrejuve_facilities";
// $pass   = "Taz+O,cBK;^0";
// $data   ="hrrejuve_facilities";


$server = "localhost";
$user   = "root";
$pass   = "";
$data   ="facilities_book";

$conn = mysqli_connect($server, $user, $pass, $data);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
