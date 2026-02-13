<?php
include_once('../../includes/db_connection.php');
header('Content-Type: application/json');

if(isset($_POST['student_id'])){
    $student_id = $_POST['student_id'];

    // Get tablets assigned to this student
    $stmt = $pdo->prepare("
        SELECT t.tablet_id
        FROM tablet_assignments ta
        JOIN tablet t ON ta.tablet_id = t.id
        WHERE ta.student_id = ?
    ");
    $stmt->execute([$student_id]);
    $tablets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($tablets);
    exit;
}

echo json_encode([]);
?>