<?php
include_once('../includes/auth_check.php');
include_once('../includes/db_connection.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>
            alert('Invalid Report ID.');
            window.location='reports.php';
          </script>";
    exit;
}

$report_id = intval($_GET['id']);

try {

    // Check if the report exists
    $stmt = $pdo->prepare("
        SELECT id
        FROM tablet_reports
        WHERE id = ?
    ");
    $stmt->execute([$report_id]);

    if ($stmt->rowCount() == 0) {
        echo "<script>
                alert('Report not found.');
                window.location='reports.php';
              </script>";
        exit;
    }

    // Delete the report
    $stmt = $pdo->prepare("
        DELETE FROM tablet_reports
        WHERE id = ?
    ");

    $stmt->execute([$report_id]);

    echo "<script>
            alert('Report deleted successfully.');
            window.location='reports.php';
          </script>";

} catch (PDOException $e) {

    echo "<script>
            alert('Unable to delete report.');
            window.location='reports.php';
          </script>";
}
?>