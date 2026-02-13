<?php
session_start();
require_once('../../includes/db_connection.php');
require_once('../../includes/auth_check.php');

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../assign_tablet.php");
    exit;
}

/* ===============================
   SANITIZE & COLLECT FORM DATA
================================ */
$tablet_id         = trim($_POST['tablet_id']);
$serial_no         = trim($_POST['serial_no']);
$imei_no           = trim($_POST['imei_no']);

$student_name      = trim($_POST['student_name']);
$index_no          = trim($_POST['index_no']);
$residence_status  = trim($_POST['residence_status']);
$level             = trim($_POST['level']);
$class_id          = trim($_POST['class_id']);  // Will come from dropdown
$house_id          = trim($_POST['house_id']);  // Will come from dropdown

$guardian_name     = trim($_POST['guardian_name']);
$ghana_card_no     = trim($_POST['ghana_card_no']);
$phone_no          = trim($_POST['phone_no']);
$date_issued       = trim($_POST['date_issued']);

/* ===========================
   BASIC VALIDATION
=========================== */
if (
 
    empty($student_name) || empty($index_no) ||
    empty($class_id) || empty($house_id) ||
    empty($guardian_name) || empty($ghana_card_no) || empty($phone_no) ||   
    empty($date_issued)
) {
    $_SESSION['error'] = "All fields are required.";
    header("Location: ../assign_tablet.php");
    exit;
}

try {
    $pdo->beginTransaction();

    /* ===========================
       CHECK IF TABLET IS AVAILABLE
    ============================ */
    $checkTablet = $pdo->prepare("
        SELECT * FROM tablet
        WHERE tablet_id = ? AND serial_No = ? AND imei_No = ? AND is_assigned = 0
        LIMIT 1
    ");
    $checkTablet->execute([$tablet_id, $serial_no, $imei_no]);
    $tablet = $checkTablet->fetch(PDO::FETCH_ASSOC);

    if (!$tablet) {
        throw new Exception("Tablet is already assigned or does not exist.");
    }

    $tablet_db_id = $tablet['id'];

    /* ===========================
       INSERT STUDENT
    ============================ */
    $insertStudent = $pdo->prepare("
        INSERT INTO students
        (full_name, index_no, residence_status, level, class_id, house_id)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $insertStudent->execute([
        $student_name,
        $index_no,
        $residence_status,
        $level,
        $class_id,
        $house_id
    ]);

    $student_id = $pdo->lastInsertId();

    /* ===========================
       INSERT GUARDIAN
    ============================ */
    $insertGuardian = $pdo->prepare("
        INSERT INTO guardians
        (student_id, full_name, ghana_card_no, phone_no)
        VALUES (?, ?, ?, ?)
    ");
    $insertGuardian->execute([
        $student_id,
        $guardian_name,
        $ghana_card_no,
        $phone_no
    ]);

    /* ===========================
       INSERT TABLET ASSIGNMENT
    ============================ */
    $assignTablet = $pdo->prepare("
        INSERT INTO tablet_assignments
        (tablet_id, student_id, date_issued)
        VALUES (?, ?, ?)
    ");
    $assignTablet->execute([
        $tablet_db_id,
        $student_id,
        $date_issued
    ]);

    /* ===========================
       UPDATE TABLET STATUS
    ============================ */
    $updateTablet = $pdo->prepare("
        UPDATE tablet
        SET is_assigned = 1
        WHERE id = ?
    ");
    $updateTablet->execute([$tablet_db_id]);

    $pdo->commit();

    $_SESSION['success'] = "Tablet successfully assigned to student.";
    header("Location: ../assign_tablet.php");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = $e->getMessage();
    header("Location: ../assign_tablet.php");
    exit;
}