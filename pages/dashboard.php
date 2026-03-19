<?php
include_once('../includes/auth_check.php');
include_once('../includes/db_connection.php');

/* ========================
   GENERAL STATISTICS
======================== */

// Total tablets
$totalTablets = $pdo->query("SELECT COUNT(*) FROM tablet")->fetchColumn();

// Issued tablets
$issuedTablets = $pdo->query("SELECT COUNT(*) FROM tablet WHERE is_assigned = 1")->fetchColumn();

// Available tablets
$availableTablets = $pdo->query("SELECT COUNT(*) FROM tablet WHERE is_assigned = 0")->fetchColumn();

// Under repair
$inRepair = $pdo->query("SELECT COUNT(*) FROM tablet WHERE status = 'Under Repair'")->fetchColumn();

// Returned
$returnedTablets = $pdo->query("SELECT COUNT(*) FROM tablet_returns WHERE action_taken = 'Returned'")->fetchColumn();

// Seized
$seizedTablets = $pdo->query("SELECT COUNT(*) FROM tablet WHERE status = 'Seized'")->fetchColumn();

// ✅ Missing Tablets (NEW)
$missingTablets = $pdo->query("SELECT COUNT(*) FROM tablet_returns WHERE action_taken = 'Missing'")->fetchColumn();

/* ========================
   STUDENTS PER CLASS
======================== */

$classData = $pdo->query("
    SELECT 
        c.class_Name,
        COUNT(DISTINCT s.id) AS students_assigned
    FROM class c
    LEFT JOIN students s ON s.class_id = c.id
    LEFT JOIN tablet_assignments ta ON ta.student_id = s.id
    LEFT JOIN tablet t ON t.id = ta.tablet_id AND t.is_assigned = 1
    GROUP BY c.id
    ORDER BY c.id ASC
")->fetchAll(PDO::FETCH_ASSOC);

$classLabels = [];
$classCounts = [];

foreach ($classData as $row) {
    $classLabels[] = $row['class_Name'];
    $classCounts[] = $row['students_assigned'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
    body {
        font-family: Poppins, sans-serif;
        background: linear-gradient(135deg, #eef2f7, #dde6f2);
    }

    .main-content {
        margin-left: 230px;
        padding: 30px;
    }

    .card {
        border-radius: 18px;
        color: #fff;
        text-align: center;
        padding: 20px;
        margin: 15px;
    }

    .card-primary {
        background: #667eea;
    }

    .card-success {
        background: #38ef7d;
    }

    .card-warning {
        background: #ffcc33;
    }

    .card-danger {
        background: #ff4b2b;
    }

    .card-secondary {
        background: #6c757d;
    }

    .footer {
        position: fixed;
        bottom: 0;
        left: 230px;
        width: calc(100% - 230px);
        background: #1d3557;
        color: white;
        text-align: center;
        padding: 10px;
    }
    </style>
</head>

<body>

    <?php include_once('../includes/mobile_sidebar.php'); ?>
    <?php include_once('../includes/desktop_sidebar.php'); ?>
    <?php include_once('../includes/topbar.php'); ?>

    <div class="main-content">

        <div class="row">

            <!-- Total -->
            <div class="col-sm-6 col-lg-4">
                <div class="card card-primary">
                    <i class="fa fa-tablet fa-2x"></i>
                    <h6>Total Tablets</h6>
                    <h4><?= $totalTablets ?></h4>
                </div>
            </div>

            <!-- Issued -->
            <div class="col-sm-6 col-lg-4">
                <div class="card card-success">
                    <i class="fa fa-check fa-2x"></i>
                    <h6>Issued Tablets</h6>
                    <h4><?= $issuedTablets ?></h4>
                </div>
            </div>

            <!-- Available -->
            <div class="col-sm-6 col-lg-4">
                <div class="card card-warning">
                    <i class="fa fa-box-open fa-2x"></i>
                    <h6>Available</h6>
                    <h4><?= $availableTablets ?></h4>
                </div>
            </div>

            <!-- Repair -->
            <div class="col-sm-6 col-lg-4">
                <div class="card card-danger">
                    <i class="fa fa-tools fa-2x"></i>
                    <h6>In Repair</h6>
                    <h4><?= $inRepair ?></h4>
                </div>
            </div>

            <!-- Returned -->
            <div class="col-sm-6 col-lg-4">
                <div class="card card-success">
                    <i class="fa fa-rotate-left fa-2x"></i>
                    <h6>Returned Tablets</h6>
                    <h4><?= $returnedTablets ?></h4>
                </div>
            </div>

            <!-- Seized -->
            <div class="col-sm-6 col-lg-4">
                <div class="card card-danger">
                    <i class="fa fa-ban fa-2x"></i>
                    <h6>Seized Tablets</h6>
                    <h4><?= $seizedTablets ?></h4>
                </div>
            </div>

            <!-- ✅ Missing Tablets -->
            <div class="col-sm-6 col-lg-4">
                <div class="card card-secondary">
                    <i class="fa fa-triangle-exclamation fa-2x"></i>
                    <h6>Missing Tablets</h6>
                    <h4><?= $missingTablets ?></h4>
                </div>
            </div>

        </div>

        <!-- Chart -->
        <div class="row mt-4">
            <div class="col-12">
                <canvas id="classChart"></canvas>
            </div>
        </div>

    </div>

    <footer class="footer">
        &copy; 2026 Senior High School Tablet Management System
    </footer>

    <script>
    new Chart(document.getElementById('classChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($classLabels) ?>,
            datasets: [{
                data: <?= json_encode($classCounts) ?>,
                backgroundColor: '#457b9d'
            }]
        }
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>