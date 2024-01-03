<?php
// $host = "localhost:3306";
// $dbname = "hrrejuve_facilities";
// $username = "cpses_hrzxz2ir5o";
// $password = "";

$host = "localhost";
$username   = "root";
$password   = "";
$dbname   ="facilities_book";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!-- <?php
$host = "localhost:3306";
$dbname = "hrrejuve_facilities";
$username = "cpses_hrzxz2ir5o";
$password = "your_secure_password"; // Set a strong password here

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // For production, you might want to comment out the following line
    // or log errors to a secure location instead of displaying them.
    echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?> -->