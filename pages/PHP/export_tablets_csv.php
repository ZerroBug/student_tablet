<?php
include_once('../../includes/db_connection.php');

if (!isset($_GET['class_id'])) {
    die("Invalid request");
}

$class_id = $_GET['class_id'];

// Fetch class name
$class_stmt = $pdo->prepare("SELECT class_name FROM class WHERE id = ?");
$class_stmt->execute([$class_id]);
$class = $class_stmt->fetch(PDO::FETCH_ASSOC);

$class_name = $class ? $class['class_name'] : 'class';

// Fetch assigned tablets for selected class
$stmt = $pdo->prepare("
    SELECT 
        t.tablet_id,
        t.serial_No,
        t.imei_No,
        s.index_no,
        s.full_name AS student_name,
        s.level,
        c.class_name,
        h.house_name,
        g.full_name AS guardian_name,
        g.phone_no,
        ta.date_issued
    FROM tablet t
    INNER JOIN tablet_assignments ta ON t.id = ta.tablet_id
    INNER JOIN students s ON s.id = ta.student_id
    INNER JOIN class c ON s.class_id = c.id
    INNER JOIN house h ON s.house_id = h.id
    INNER JOIN guardians g ON g.student_id = s.id
    WHERE s.class_id = ?
    ORDER BY s.full_name ASC
");

$stmt->execute([$class_id]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="assigned_tablets_' . $class_name . '.csv"');

// Open output stream
$output = fopen("php://output", "w");

// CSV Headers
fputcsv($output, [
    'Tablet ID',
    'Serial No',
    'IMEI No',
    'Index No',
    'Student Name',
    'Level',
    'Class',
    'House',
    'Guardian',
    'Contact',
    'Date Issed'
]);

// CSV Data
foreach ($data as $row) {
    fputcsv($output, [
        $row['tablet_id'],
        $row['serial_No'],
        $row['imei_No'],
        $row['index_no'],
        $row['student_name'],
        $row['level'],
        $row['class_name'],
        $row['house_name'],
        $row['guardian_name'],
        $row['phone_no'],
        $row['date_issued']
    ]);
}

fclose($output);
exit;