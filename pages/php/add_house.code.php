<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../../includes/db_connection.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addHouse_Btn'])) {

    // Collect form data
    $house_Name   = $_POST['house_Name'] ?? null;
    $house_Master         = $_POST['house_Master'] ?? null;
    
  

    // Basic validation
    if (!$house_Name || !$house_Master) {
        echo "All fields are required.";
        exit;
    }

    // 🔍 Check if class exist
    $checkSql = "SELECT * FROM house WHERE house_Name = :house_Name AND house_Master = :house_Master";
    $checkStmt = $pdo->prepare($checkSql);
     $checkStmt->bindParam(':house_Name', $house_Name);
    $checkStmt->bindParam(':house_Master', $house_Master);
   
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        echo "<script>
                alert('House already exists. ');
                window.location.href = '../add_house.php'; // Adjust path if needed
              </script>";
        exit;
    }

    // ✅ Insert if no duplicates
    $sql = "INSERT INTO house (house_Name, house_Master) 
            VALUES (:house_Name, :house_Master)";

    try {
        $stmt = $pdo->prepare($sql);
         $stmt->bindParam(':house_Name', $house_Name);
        $stmt->bindParam(':house_Master', $house_Master);
    
       

        $stmt->execute();

        echo "<script>
                alert('House added successfully!');
                window.location.href = '../add_house.php'; // Adjust path if needed
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