<?php
include_once('../includes/auth_check.php');
include_once('../includes/db_connection.php');

// ================= PAGINATION =================
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $limit;

// Fetch available (unassigned) tablets
$tablets_stmt = $pdo->query("SELECT id, tablet_id, serial_No, imei_No FROM tablet WHERE is_assigned = 0 ORDER BY tablet_id ASC");
$tablets = $tablets_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch classes and houses
$classes_stmt = $pdo->query("SELECT id, class_name FROM class ORDER BY class_name ASC");
$classes = $classes_stmt->fetchAll(PDO::FETCH_ASSOC);

$houses_stmt = $pdo->query("SELECT id, house_name FROM house ORDER BY house_name ASC");
$houses = $houses_stmt->fetchAll(PDO::FETCH_ASSOC);

// Count total records
$total_stmt = $pdo->query("
    SELECT COUNT(*) 
    FROM tablet_assignments ta
    INNER JOIN students s ON s.id = ta.student_id
");
$total_records = $total_stmt->fetchColumn();
$total_pages = ceil($total_records / $limit);

// Fetch paginated assigned tablets
$assigned_stmt = $pdo->prepare("
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
    LIMIT :limit OFFSET :offset
");

$assigned_stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$assigned_stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$assigned_stmt->execute();

$assigned_tablets = $assigned_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Tablet | Dashboard</title>
    <link rel="icon" href="assets/images/logo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
    body {
        font-family: Poppins, sans-serif;
        background: #f8f9fa;
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
        background: #1d3557;
        color: white;
        text-align: center;
        padding: 12px;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }
    </style>
</head>

<body>

    <?php include_once('../includes/mobile_sidebar.php'); ?>
    <?php include_once('../includes/desktop_sidebar.php'); ?>

    <div class="main-content">
        <?php include_once('../includes/topbar.php'); ?>

        <!-- Messages -->
        <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- FORM (UNCHANGED) -->
        <div class="card p-4 mb-5">
            <form method="POST" action="../pages/php/assign_tablet.code.php">

                <h5>Assign Tablet</h5>

                <select id="tabletId" name="tablet_id" class="form-select mb-3">
                    <option disabled selected>Select Tablet</option>
                    <?php foreach ($tablets as $tablet): ?>
                    <option value="<?= $tablet['tablet_id'] ?>" data-serial="<?= $tablet['serial_No'] ?>"
                        data-imei="<?= $tablet['imei_No'] ?>">
                        <?= $tablet['tablet_id'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>

                <input id="serialNumber" class="form-control mb-2" readonly>
                <input id="imei" class="form-control mb-2" readonly>

                <input name="student_name" class="form-control mb-2" placeholder="Student Name">
                <input name="index_no" class="form-control mb-2" placeholder="Index No">

                <button class="btn btn-primary">Assign</button>
            </form>
        </div>

        <!-- TABLE -->
        <div class="card p-4">

            <h5>Assigned Tablets</h5>

            <input type="text" id="tableSearch" class="form-control mb-3" placeholder="Search...">

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Tablet</th>
                        <th>Serial</th>
                        <th>IMEI</th>
                        <th>Index</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>House</th>
                        <th>Contact</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if(count($assigned_tablets)): ?>
                    <?php foreach($assigned_tablets as $i => $row): ?>
                    <tr>
                        <td><?= $offset + $i + 1 ?></td>
                        <td><?= $row['tablet_id'] ?></td>
                        <td><?= $row['serial_No'] ?></td>
                        <td><?= $row['imei_No'] ?></td>
                        <td><?= $row['bece_index_no'] ?></td>
                        <td><?= $row['student_name'] ?></td>
                        <td><?= $row['class_name'] ?></td>
                        <td><?= $row['house_name'] ?></td>
                        <td><?= $row['phone_no'] ?></td>
                        <td>
                            <a href="edit_assignment.php?id=<?= $row['assignment_id'] ?>"
                                class="btn btn-warning btn-sm">Edit</a>
                            <button onclick="confirmDelete(<?= $row['assignment_id'] ?>)"
                                class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="10">No data</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- PAGINATION -->
            <nav>
                <ul class="pagination justify-content-center">

                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page-1 ?>">Prev</a>
                    </li>

                    <?php for($i=1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>

                    <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page+1 ?>">Next</a>
                    </li>

                </ul>
            </nav>

        </div>
    </div>

    <footer class="footer">
        &copy; 2025 Tablet System
    </footer>

    <script>
    document.getElementById('tabletId').addEventListener('change', function() {
        let opt = this.selectedOptions[0];
        document.getElementById('serialNumber').value = opt.dataset.serial;
        document.getElementById('imei').value = opt.dataset.imei;
    });

    function confirmDelete(id) {
        if (confirm("Delete?")) {
            window.location = "php/delete_assignment.php?id=" + id;
        }
    }

    document.getElementById("tableSearch").addEventListener("keyup", function() {
        let filter = this.value.toLowerCase();
        document.querySelectorAll("tbody tr").forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(filter) ? "" : "none";
        });
    });
    </script>

</body>

</html>