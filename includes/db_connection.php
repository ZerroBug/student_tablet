<?php
// Database connection
$host = 'localhost';
$dbname = 'u421860731_tablet_db';
$username = 'u421860731_oashs';
$password = 'Ananeeric@172090';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}


// $conn= new mysqli('localhost','u687023860_juass98','Ananeeric172090','u687023860_juass98_db')or die("Could not connect to mysql".mysqli_error($con));
?>