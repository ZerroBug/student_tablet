<?php
// Enable error reporting (for debugging only, remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../../includes/db_connection.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addTablet_Btn'])) {

    // Collect form data safely
    $level         = $_POST['level'] ?? null;
    $tablet_id     = $_POST['tablet_id'] ?? null;
    $serial_number = $_POST['serial_No'] ?? null;    // Match HTML name attribute
    $imei          = $_POST['imei_No'] ?? null;      // Match HTML name attribute
    $academic_year = $_POST['academic_year'] ?? null;
    $accessories   = isset($_POST['accessories']) ? implode(', ', $_POST['accessories']) : '';

    // Basic validation
    if (!$level || !$tablet_id || !$serial_number || !$imei || !$academic_year) {
        echo "<script>
                alert('Please fill in all required fields.');
                window.location.href = '../add_tablet.php';
              </script>";
        exit;
    }

    // Check if tablet_id OR serial_No already exists in the table (for *any* academic year)
    $checkSql = "SELECT * FROM tablet WHERE tablet_id = :tablet_id OR serial_No = :serial_No";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->bindParam(':tablet_id', $tablet_id);
    $checkStmt->bindParam(':serial_No', $serial_number);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        // Duplicate found — show error alert and redirect
        echo "<script>
                alert('Tablet ID or Serial Number already exists. Please use unique values.');
                window.location.href = '../add_tablet.php';
              </script>";
        exit;
    }

    // Insert new tablet record
    $sql = "INSERT INTO tablet (level, tablet_id, serial_No, imei_No, accessories, academic_year) 
            VALUES (:level, :tablet_id, :serial_No, :imei_No, :accessories, :academic_year)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':level', $level);
        $stmt->bindParam(':tablet_id', $tablet_id);
        $stmt->bindParam(':serial_No', $serial_number);
        $stmt->bindParam(':imei_No', $imei);
        $stmt->bindParam(':accessories', $accessories);
        $stmt->bindParam(':academic_year', $academic_year);

        $stmt->execute();

        // Insert notification about the new tablet
        $notify_sql = "INSERT INTO notifications (title, message, icon, type) VALUES (?, ?, ?, ?)";
        $pdo->prepare($notify_sql)->execute([
            "New tablet added",
            "Tablet ID: $tablet_id was added.",
            "fa-tablet-alt",
            "info"
        ]);

        // Success message and redirect back to add tablet page
        echo "<script>
                alert('Tablet successfully added!');
                window.location.href = '../add_tablet.php';
              </script>";
        exit;

    } catch (PDOException $e) {
        // Database error
        echo "<script>
                alert('Insert failed: " . addslashes($e->getMessage()) . "');
                window.location.href = '../add_tablet.php';
              </script>";
        exit;
    }

} else {
    // Invalid access or direct URL access
    echo "<script>
            alert('Invalid access.');
            window.location.href = '../add_tablet.php';
          </script>";
    exit;
}