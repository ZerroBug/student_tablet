<?php
// Check if the form is submitted
if (isset($_POST['import'])) {
    include_once '../../includes/db_connection.php'; // Should define $pdo (PDO object)

    if ($_FILES['file']['name']) {
        $fileName = $_FILES['file']['tmp_name'];
        $handle = fopen($fileName, "r");

        // Skip first two rows (headers)
        fgetcsv($handle, 1000, ",");
        fgetcsv($handle, 1000, ",");

        try {
            while (($column = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (array_filter($column, 'trim')) {
                    // Clean and assign columns
                    $academic_year = isset($column[0]) ? trim($column[0]) : null;
                    $level         = isset($column[1]) ? trim($column[1]) : null;
                    $tablet_id     = isset($column[2]) ? trim($column[2]) : null;
                    $serial_No     = isset($column[3]) ? trim($column[3]) : null;
                    $imei_No       = isset($column[4]) ? trim($column[4]) : null;
                    $accessories   = isset($column[5]) ? trim($column[5]) : '';

                    // Ensure required fields are not empty
                    if ($academic_year && $tablet_id && $serial_No && $imei_No && $level) {
                        // Check if tablet_id or serial_No already exists
                        $check_sql = "SELECT COUNT(*) FROM tablet WHERE tablet_id = :tablet_id OR serial_No = :serial_No";
                        $check_stmt = $pdo->prepare($check_sql);
                        $check_stmt->execute([
                            ':tablet_id' => $tablet_id,
                            ':serial_No' => $serial_No
                        ]);
                        $exists = $check_stmt->fetchColumn();

                        if (!$exists) {
                            // Insert new record
                            $insert_sql = "INSERT INTO tablet (academic_year, level, tablet_id, serial_No, imei_No, accessories)
                                           VALUES (:academic_year, :level, :tablet_id, :serial_No, :imei_No, :accessories)";
                            $insert_stmt = $pdo->prepare($insert_sql);
                            $insert_stmt->execute([
                                ':academic_year' => $academic_year,
                                ':level'         => $level,
                                ':tablet_id'     => $tablet_id,
                                ':serial_No'     => $serial_No,
                                ':imei_No'       => $imei_No,
                                ':accessories'   => $accessories
                            ]);
                        } else {
                            echo "Tablet with ID '$tablet_id' or Serial '$serial_No' already exists. Skipping...<br>";
                        }
                    }
                }
            }

            fclose($handle);

            // Redirect on success
            header("Location: ../add_tablet.php?import=success");
            exit;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit;
        }
    } else {
        echo "Error: Please upload a file.";
    }
}
?>