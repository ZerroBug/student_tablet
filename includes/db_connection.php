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