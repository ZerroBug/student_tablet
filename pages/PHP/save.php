<?php
// Database connection
$host = "localhost";
$dbname = "your_database";
$username = "your_db_user";
$password = "your_db_password";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect form data securely
$user = trim($_POST['username']);
$pass = trim($_POST['password']);

// Hash the password before storing/checking
$hashedPass = password_hash($pass, PASSWORD_DEFAULT);

// Example: Insert user into database
$sql = "INSERT INTO users (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user, $hashedPass);

if ($stmt->execute()) {
    echo "User successfully saved.";
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
