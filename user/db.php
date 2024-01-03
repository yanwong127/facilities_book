<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// $server = "localhost:3306";
// $user   = "cpses_hrzxz2ir5o";
// $pass   = "";
// $data   ="hrrejuve_facilities";

$server = "localhost";
$user   = "root";
$pass   = "ak6403012";
$data   ="facilities_book";

$conn = mysqli_connect($server, $user, $pass, $data);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
