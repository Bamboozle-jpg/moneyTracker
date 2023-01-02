<?php
// include 'dbconfig.php';
$host = '127.0.0.1';
$dbname = 'myTestDatabase';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    echo "Connected to $dbname at $host successfully.";
} catch (PDOException $pe) {
    die("Could not connect to the database $dbname :" . $pe->getMessage());
}
    exit;
?>