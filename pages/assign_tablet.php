<?php
include_once('../includes/auth_check.php');
include_once('../includes/db_connection.php');

// Fetch available (unassigned) tablets
$tablets_stmt = $pdo->query("SELECT id, tablet_id, serial_No, imei_No FROM tablet WHERE is_assigned = 0 ORDER BY tablet_id ASC");
$tablets = $tablets_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch classes and houses
$classes_stmt = $pdo->query("SELECT id, class_name FROM class ORDER BY class_name ASC");
$classes = $classes_stmt->fetchAll(PDO::FETCH_ASSOC);

$houses_stmt = $pdo->query("SELECT id, house_name FROM house ORDER BY house_name ASC");
$houses = $houses_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all assigned tablets with student and guardian info
$assigned_stmt = $pdo->query("
    SELECT ta.id AS assignment_id,
           t.tablet_id, t.serial_No, t.imei_No, 
           s.index_no AS bece_index_no,
           s.full_name AS student_name, s.level,
           c.class_name, h.house_name,
           g.full_name AS guardian_name, g.phone_no, ta.date_issued
    FROM tablet t
    INNER JOIN tablet_assignments ta ON t.id = ta.tablet_id
    INNER JOIN students s ON s.id = ta.student_id
    INNER JOIN class c ON s.class_id = c.id
    INNER JOIN house h ON s.house_id = h.id
    INNER JOIN guardians g ON g.student_id = s.id
    ORDER BY ta.date_issued DESC
");

$assigned_tablets = $assigned_stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Tablet | Dashboard</title>
    <link rel="icon" href="assets/images/logo.jpg" type="image/jpeg">
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

        <!-- Display success/error messages -->
        <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <!-- Assign Tablet Form -->
        <div class="card shadow-sm p-4 mb-5">
            <form method="POST" action="../pages/php/assign_tablet.code.php">
                <h5 class="text-primary p-3">Assign Tablet</h5>

                <!-- Tablet Selection -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="tabletId" class="form-label">Tablet ID</label>
                        <select class="form-select" id="tabletId" name="tablet_id" required>
                            <option selected disabled>Select Tablet ID</option>
                            <?php foreach ($tablets as $tablet): ?>
                            <option value="<?= $tablet['tablet_id'] ?>"
                                data-serial="<?= htmlspecialchars($tablet['serial_No']) ?>"
                                data-imei="<?= htmlspecialchars($tablet['imei_No']) ?>">
                                <?= htmlspecialchars($tablet['tablet_id']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="serialNumber" class="form-label">Serial Number</label>
                        <input type="text" class="form-control" id="serialNumber" name="serial_no" readonly required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="imei" class="form-label">IMEI Number</label>
                        <input type="text" class="form-control" id="imei" name="imei_no" readonly required>
                    </div>
                </div>

                <!-- Student Info -->
                <h5 class="text-success mt-4">Student Information</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="studentName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="studentName" name="student_name" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="studentIndex" class="form-label">BECE Index Number</label>
                        <input type="text" class="form-control" id="studentIndex" name="index_no" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="residenceStatus" class="form-label">Residence Status</label>
                        <select class="form-select" id="residenceStatus" name="residence_status">
                            <option selected disabled>Select Status</option>
                            <option value="Boarding">Boarding</option>
                            <option value="Day">Day</option>
                        </select>
                    </div>
                </div>

                <!-- Additional Student Info -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="level" class="form-label">Level</label>
                        <select class="form-select" id="level" name="level">
                            <option selected disabled>Select Level</option>
                            <option value="SHS_1">SHS 1</option>
                            <option value="SHS_2">SHS 2</option>
                            <option value="SHS_3">SHS 3</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="classGroup" class="form-label">Class</label>
                        <select class="form-select" id="classGroup" name="class_id">
                            <option selected disabled>Select Class</option>
                            <?php foreach ($classes as $class): ?>
                            <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['class_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="house" class="form-label">House</label>
                        <select class="form-select" id="house" name="house_id">
                            <option selected disabled>Select House</option>
                            <?php foreach ($houses as $house): ?>
                            <option value="<?= $house['id'] ?>"><?= htmlspecialchars($house['house_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Guardian Info -->
                <h5 class="text-warning mt-4">Guardian Information</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="guardianName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="guardianName" name="guardian_name">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="ghanaCard" class="form-label">Ghana Card Number</label>
                        <input type="text" class="form-control" id="ghanaCard" name="ghana_card_no">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="parentContact" class="form-label">Parent's Contact</label>
                        <input type="text" class="form-control" id="parentContact" name="phone_no"
                            placeholder="e.g., 024XXXXXXXX">
                    </div>
                </div>

                <!-- Assignment Details -->
                <h5 class="text-info mt-4">Assignment Details</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="dateIssued" class="form-label">Date Issued</label>
                        <input type="date" class="form-control" id="dateIssued" name="date_issued">
                    </div>
                </div>

                <!-- Agreement -->
                <div class="form-check mt-3 mb-4">
                    <input class="form-check-input" type="checkbox" id="agreement">
                    <label class="form-check-label" for="agreement">
                        I acknowledge receipt of the tablet and agree to the school’s device policy.
                    </label>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-2"></i>Assign Tablet
                    </button>
                </div>
            </form>
        </div>


        <!-- Assigned Tablets Table -->
        <div class="card shadow-sm p-4 mt-5">
            <h5 class="text-primary p-3">Assigned Tablets</h5>
            <div class="table-responsive">
                <div class="row mb-3">
                    <div class="col-md-4 ms-auto">
                        <input type="text" id="tableSearch" class="form-control"
                            placeholder="Search by Tablet ID, Name, Index, Class...">
                    </div>
                </div>

                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Tablet ID</th>
                            <th>Serial No</th>
                            <th>IMEI No</th>
                            <th>BECE Index</th>
                            <th>Name</th>

                            <th>Class</th>
                            <th>House</th>

                            <th>Contact</th>

                            <th>Actions</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($assigned_tablets) > 0): ?>
                        <?php foreach($assigned_tablets as $i => $row): ?>
                        <tr>
                            <td><?= $i+1 ?></td>
                            <td><?= htmlspecialchars($row['tablet_id']) ?></td>
                            <td><?= htmlspecialchars($row['serial_No']) ?></td>
                            <td><?= htmlspecialchars($row['imei_No']) ?></td>
                            <td><?= htmlspecialchars($row['bece_index_no']) ?></td>
                            <td><?= htmlspecialchars($row['student_name']) ?></td>

                            <td><?= htmlspecialchars($row['class_name']) ?></td>
                            <td><?= htmlspecialchars($row['house_name']) ?></td>

                            <td><?= htmlspecialchars($row['phone_no']) ?></td>
                            <td>
                                <!-- EDIT -->
                                <a href="edit_assignment.php?id=<?= $row['assignment_id'] ?>"
                                    class="btn btn-sm btn-warning">
                                    <i class="fa fa-edit"></i>
                                </a>

                                <!-- DELETE -->
                                <button class="btn btn-sm btn-danger"
                                    onclick="confirmDelete(<?= $row['assignment_id'] ?>)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="12" class="text-center">No tablets have been assigned yet.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <footer class="footer">
        &copy; 2025 Senior High School Tablet Management. All Rights Reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Auto-fill Serial & IMEI -->
    <script>
    document.getElementById('tabletId').addEventListener('change', function() {
        const selectedOption = this.selectedOptions[0];
        document.getElementById('serialNumber').value = selectedOption.getAttribute('data-serial');
        document.getElementById('imei').value = selectedOption.getAttribute('data-imei');
    });
    </script>

    <script>
    document.getElementById("tableSearch").addEventListener("keyup", function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll("table tbody tr");

        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? "" : "none";
        });
    });
    </script>
    <script>
    function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this tablet assignment?")) {
            window.location.href = "php/delete_assignment.php?id=" + id;
        }
    }
    </script>

</body>

</html>