<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// $server = "sv77.ifastnet.com";
// $user   = "cpses_hrzxz2ir5o";
// $pass   = "";
// $data   ="hrrejuve_facilities";

// $host = "sv77.ifastnet.com";
// $dbname   = "hrrejuve_facilities";
// $password   = "Taz+O,cBK;^0";
// $username   ="hrrejuve_facilities";


$server = "localhost";
$user   = "root";
$pass   = "ak6403012";
$data   ="facilities_book";

$conn = mysqli_connect($server, $user, $pass, $data);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
