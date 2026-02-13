<?php
session_start();
require_once('../../includes/db_connection.php');
require_once('../../includes/auth_check.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: ../assign_tablet.php");
    exit;
}

$id = $_GET['id'];

try {
    $stmt = $pdo->prepare("DELETE FROM tablet_assignments WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['success'] = "Tablet assignment deleted successfully.";
} catch (PDOException $e) {
    $_SESSION['error'] = "Delete failed.";
}

header("Location: ../assign_tablet.php");
exit;