<?php
session_start();
require_once('../../includes/auth_check.php');
require_once('../../includes/db_connection.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../assign_tablet.php");
    exit;
}

/* ================= VALIDATE INPUT ================= */
$assignment_id = $_POST['assignment_id'] ?? null;

if (!$assignment_id || !is_numeric($assignment_id)) {
    $_SESSION['error'] = "Invalid update request.";
    header("Location: ../assign_tablet.php");
    exit;
}

try {
    $pdo->beginTransaction();

    /* ================= GET LINKED IDS ================= */
    $stmt = $pdo->prepare("
        SELECT student_id 
        FROM tablet_assignments 
        WHERE id = ?
    ");
    $stmt->execute([$assignment_id]);
    $link = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$link) {
        throw new Exception("Assignment not found.");
    }

    $student_id = $link['student_id'];

    /* ================= UPDATE STUDENT ================= */
    $stmt = $pdo->prepare("
        UPDATE students SET
            full_name = ?,
            index_no = ?,
            level = ?,
            class_id = ?,
            house_id = ?
        WHERE id = ?
    ");
    $stmt->execute([
        trim($_POST['student_name']),
        trim($_POST['index_no']),
        $_POST['level'],
        $_POST['class_id'],
        $_POST['house_id'],
        $student_id
    ]);

    /* ================= UPDATE GUARDIAN ================= */
    $stmt = $pdo->prepare("
        UPDATE guardians SET
            full_name = ?,
            phone_no = ?,
            ghana_card_no = ?
        WHERE student_id = ?
    ");
    $stmt->execute([
        trim($_POST['guardian_name']),
        trim($_POST['phone_no']),
        trim($_POST['ghana_card_no']),
        $student_id
    ]);

    /* ================= UPDATE ASSIGNMENT ================= */
    $stmt = $pdo->prepare("
        UPDATE tablet_assignments
        SET date_issued = ?
        WHERE id = ?
    ");
    $stmt->execute([
        $_POST['date_issued'],
        $assignment_id
    ]);

    $pdo->commit();

    $_SESSION['success'] = "Tablet assignment updated successfully.";
    header("Location: ../assign_tablet.php");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = "Update failed. Please try again.";
    header("Location: ../edit_assignment.php?id=".$assignment_id);
    exit;
}