<?php 

session_start();

$server = "localhost";
$user   = "root";
$pass   = "ak6403012";
$data   ="facilities_book";

$conn =mysqli_connect($server,$user,$pass,$data);

if($conn-> connect_error){
	die("Connection failed: ".$conn->connect_error);
}

?>