<?php
include_once('../includes/auth_check.php');
include_once('../includes/db_connection.php');

if (isset($_POST['submit_report'])) {

    $assignment_id = intval($_POST['tablet_id']);
    $student_id    = intval($_POST['student_id']);
    $class_id      = intval($_POST['class_id']);

    $incident_type = trim($_POST['incident_type']);
    $action_taken  = trim($_POST['action_taken']);
    $status        = trim($_POST['status']);
    $description   = trim($_POST['description']);

    $reported_by = $_SESSION['username'] ?? 'Admin';

    // Get tablet internal ID
    $stmt = $pdo->prepare("
        SELECT tablet_id
        FROM tablet_assignments
        WHERE id = ?
    ");

    $stmt->execute([$assignment_id]);
    $assignment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$assignment) {
        echo "<script>
                alert('Invalid Tablet Assignment!');
                window.history.back();
              </script>";
        exit;
    }

    $tablet_auto_id = $assignment['tablet_id'];

    try {

        $pdo->beginTransaction();

        $stmt = $pdo->prepare("
            INSERT INTO tablet_reports
            (
                tablet_id,
                student_id,
                class_id,
                incident_type,
                action_taken,
                status,
                description,
                reported_by
            )
            VALUES
            (
                ?,?,?,?,?,?,?,?
            )
        ");

        $stmt->execute([
            $tablet_auto_id,
            $student_id,
            $class_id,
            $incident_type,
            $action_taken,
            $status,
            $description,
            $reported_by
        ]);

        $pdo->commit();

        echo "
        <script>

            alert('Tablet report submitted successfully.');

            window.location='report_tablet.php';

        </script>
        ";

    } catch(PDOException $e){

        $pdo->rollBack();

        echo "
        <script>

            alert('".$e->getMessage()."');

            window.history.back();

        </script>
        ";

    }

}
?>