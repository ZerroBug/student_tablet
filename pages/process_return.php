<?php
include_once('../includes/auth_check.php');
include_once '../includes/db_connection.php';

if (isset($_POST['submit_return'])) {

    $assignment_id = intval($_POST['tablet_id']); 
    $student_id    = intval($_POST['student_id']);
    $class_id      = intval($_POST['class_id']);
    $reason        = strtolower(trim($_POST['reason']));
    $action        = $_POST['action_taken'];
    $description   = trim($_POST['description']);
    $received_by   = $_SESSION['username'] ?? 'Admin';

    // Validate action
    if (!in_array($action, ['Returned', 'Seized'])) {
        echo "<script>alert('Invalid action selected ❌');</script>";
        exit;
    }

    // Fetch tablet internal ID from assignment
    $stmt = $pdo->prepare("SELECT tablet_id FROM tablet_assignments WHERE id = ?");
    $stmt->execute([$assignment_id]);
    $assignment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$assignment) {
        echo "<script>alert('Invalid Tablet Assignment ❌');</script>";
        exit;
    }

    $tablet_auto_id = $assignment['tablet_id'];

    try {
        $pdo->beginTransaction();

        // Insert return record (record only, no status update)
        $stmt = $pdo->prepare("
            INSERT INTO tablet_returns
            (tablet_id, student_id, class_id, reason, description, action_taken, received_by)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $tablet_auto_id,
            $student_id,
            $class_id,
            $reason,
            $description,
            $action,
            $received_by
        ]);

        // ✅ Do NOT update tablet status
        // ✅ Do NOT delete assignment

        $pdo->commit();

        echo "<script>
            alert('Tablet return recorded successfully ✅');
            window.location.href = window.location.href;
        </script>";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<script>alert('Operation failed ❌');</script>";
    }
}
?>