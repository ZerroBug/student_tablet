<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../../includes/db_connection.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addClass_Btn'])) {

    // Collect form data
    $year_Added   = $_POST['year_Added'] ?? null;
    $level         = $_POST['level'] ?? null;
    $class_Name    = $_POST['class_Name'] ?? null;

  

    // Basic validation
    if (!$level || !$class_Name || !$year_Added) {
        echo "All fields are required.";
        exit;
    }

    // 🔍 Check if class exist
    $checkSql = "SELECT * FROM class WHERE year_Added = :year_Added AND class_Name = :class_Name AND level = :level";
    $checkStmt = $pdo->prepare($checkSql);
     $checkStmt->bindParam(':year_Added', $year_Added);
    $checkStmt->bindParam(':level', $level);
    $checkStmt->bindParam(':class_Name', $class_Name);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        echo "<script>
                alert('Class_Name or Level already exists. ');
                window.location.href = './add_class.php'; // Adjust path if needed
              </script>";
        exit;
    }

    // ✅ Insert if no duplicates
    $sql = "INSERT INTO class (year_Added, level, class_Name) 
            VALUES (:year_Added, :level, :class_Name)";

    try {
        $stmt = $pdo->prepare($sql);
         $stmt->bindParam(':year_Added', $year_Added);
        $stmt->bindParam(':level', $level);
        $stmt->bindParam(':class_Name', $class_Name);
       

        $stmt->execute();

        echo "<script>
                alert('Class added successfully!');
                window.location.href = '../add_class.php'; // Adjust path if needed
              </script>";
        exit;

    } catch (PDOException $e) {
        echo "Insert failed: " . $e->getMessage();
        exit;
    }

} else {
    echo "Invalid access.";
    exit;
}