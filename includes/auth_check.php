<?php
session_start();

if (!isset($_SESSION['id'])) {
    // Not logged in
    header("Location: ../index.php");
    exit();
}
?>