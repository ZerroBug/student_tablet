<?php
include_once('../includes/auth_check.php');
include_once('../includes/db_connection.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid assignment ID.";
    header("Location: assign_tablet.php");
    exit;
}

$assignment_id = $_GET['id'];

/* ================= FETCH ASSIGNMENT ================= */
$stmt = $pdo->prepare("
    SELECT 
        ta.id AS assignment_id,
        ta.date_issued,

        t.id AS tablet_db_id,
        t.tablet_id, t.serial_No, t.imei_No,

        s.id AS student_id,
        s.full_name AS student_name,
        s.index_no,
        s.level,
        s.class_id,
        s.house_id,

        g.id AS guardian_id,
        g.full_name AS guardian_name,
        g.phone_no,
        g.ghana_card_no
    FROM tablet_assignments ta
    INNER JOIN tablet t ON t.id = ta.tablet_id
    INNER JOIN students s ON s.id = ta.student_id
    INNER JOIN guardians g ON g.student_id = s.id
    WHERE ta.id = ?
");
$stmt->execute([$assignment_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    $_SESSION['error'] = "Assignment not found.";
    header("Location: assign_tablet.php");
    exit;
}

/* ================= FETCH CLASSES & HOUSES ================= */
$classes = $pdo->query("SELECT id, class_name FROM class ORDER BY class_name ASC")->fetchAll(PDO::FETCH_ASSOC);
$houses  = $pdo->query("SELECT id, house_name FROM house ORDER BY house_name ASC")->fetchAll(PDO::FETCH_ASSOC);

/* ================= UPDATE HANDLER ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $pdo->beginTransaction();

        // Update student
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
            $_POST['student_name'],
            $_POST['index_no'],
            $_POST['level'],
            $_POST['class_id'],
            $_POST['house_id'],
            $data['student_id']
        ]);

        // Update guardian
        $stmt = $pdo->prepare("
            UPDATE guardians SET
                full_name = ?,
                phone_no = ?,
                ghana_card_no = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $_POST['guardian_name'],
            $_POST['phone_no'],
            $_POST['ghana_card_no'],
            $data['guardian_id']
        ]);

        // Update assignment date
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
        header("Location: assign_tablet.php");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Update failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Tablet Assignment | Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
    body {
        font-family: "Poppins", sans-serif;
        background-color: #f8f9fa;
        overflow-x: hidden;
    }

    .sidebar {
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        width: 250px;
        background-color: #1d3557;
        padding-top: 1rem;
        z-index: 1040;
    }

    .sidebar a {
        color: white;
        text-decoration: none;
        display: block;
        padding: 12px 20px;
        margin-bottom: 4px;
        border-radius: 5px;
        font-size: 1rem;
        transition: 0.3s;
    }

    .sidebar a.active,
    .sidebar a:hover {
        background-color: #457b9d;
    }

    .main-content {
        margin-left: 230px;
        padding: 30px;
    }

    .footer {
        position: fixed;
        bottom: 0;
        left: 230px;
        width: calc(100% - 230px);
        background-color: #1d3557;
        color: white;
        text-align: center;
        padding: 12px 0;
        font-size: 0.9rem;
        z-index: 1030;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }

    .card-body {
        padding: 30px;
    }

    .form-label {
        font-weight: 500;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #457b9d;
    }

    .form-check-input:checked {
        background-color: #457b9d;
        border-color: #457b9d;
    }
    </style>
</head>

<body>

    <?php include_once('../includes/mobile_sidebar.php'); ?>
    <?php include_once('../includes/desktop_sidebar.php'); ?>

    <div class="main-content">
        <?php include_once('../includes/topbar.php'); ?>

        <div class="card shadow-sm p-4 mb-5">
            <form method="POST">

                <h5 class="text-warning p-3">Edit Tablet Assignment</h5>

                <!-- Tablet Info -->
                <h6 class="text-primary">Tablet Information</h6>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tablet ID</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($data['tablet_id']) ?>"
                            readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Serial Number</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($data['serial_No']) ?>"
                            readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">IMEI Number</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($data['imei_No']) ?>"
                            readonly>
                    </div>
                </div>

                <!-- Student Info -->
                <h5 class="text-success mt-4">Student Information</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="student_name" class="form-control"
                            value="<?= htmlspecialchars($data['student_name']) ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">BECE Index Number</label>
                        <input type="text" name="index_no" class="form-control"
                            value="<?= htmlspecialchars($data['index_no']) ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Level</label>
                        <select name="level" class="form-select" required>
                            <?php foreach(['SHS_1','SHS_2','SHS_3'] as $lvl): ?>
                            <option value="<?= $lvl ?>" <?= $data['level']===$lvl?'selected':'' ?>>
                                <?= str_replace('_',' ', $lvl) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Class</label>
                        <select name="class_id" class="form-select" required>
                            <?php foreach($classes as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= $data['class_id']==$c['id']?'selected':'' ?>>
                                <?= htmlspecialchars($c['class_name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">House</label>
                        <select name="house_id" class="form-select" required>
                            <?php foreach($houses as $h): ?>
                            <option value="<?= $h['id'] ?>" <?= $data['house_id']==$h['id']?'selected':'' ?>>
                                <?= htmlspecialchars($h['house_name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Guardian Info -->
                <h5 class="text-warning mt-4">Guardian Information</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="guardian_name" class="form-control"
                            value="<?= htmlspecialchars($data['guardian_name']) ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Ghana Card Number</label>
                        <input type="text" name="ghana_card_no" class="form-control"
                            value="<?= htmlspecialchars($data['ghana_card_no']) ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Parent's Contact</label>
                        <input type="text" name="phone_no" class="form-control"
                            value="<?= htmlspecialchars($data['phone_no']) ?>">
                    </div>
                </div>

                <!-- Assignment -->
                <h5 class="text-info mt-4">Assignment Details</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date Issued</label>
                        <input type="date" name="date_issued" class="form-control"
                            value="<?= htmlspecialchars($data['date_issued']) ?>">
                    </div>
                </div>

                <div class="text-end">
                    <a href="assign_tablet.php" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fa fa-save me-2"></i>Update Assignment
                    </button>
                </div>

            </form>
        </div>
    </div>

    <footer class="footer">
        &copy; 2025 Senior High School Tablet Management. All Rights Reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>