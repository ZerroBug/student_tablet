<?php
session_start();

// Database connection
require_once '../../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login-btn'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if email and password are provided
    if (empty($email) || empty($password)) {
        echo "<script>alert('Please enter both email and password'); window.location.href='../../index.php';</script>";
        exit();
    }

    try {
        // Check for admin with this email
        $stmt = $pdo->prepare("SELECT id, email, password FROM admin WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // If admin exists
        if ($stmt->rowCount() === 1) {
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check password (assuming hashed with password_hash)
            if (password_verify($password, $admin['password'])) {
                $_SESSION['id'] = $admin['id'];
                $_SESSION['email'] = $admin['email'];

                header("Location: ../dashboard.php");
                exit();
            } else {
                echo "<script>alert('Invalid password'); window.location.href='../../index.php';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Admin not found'); window.location.href='../../index.php';</script>";
            exit();
        }

    } catch (PDOException $e) {
        echo "Login error: " . $e->getMessage();
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}
?>