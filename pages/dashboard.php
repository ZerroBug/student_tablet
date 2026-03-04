<?php
include_once('../includes/auth_check.php');
include_once('../includes/db_connection.php');

/* ========================
   GENERAL STATISTICS
======================== */

// Total tablets
$totalTablets = $pdo->query("SELECT COUNT(*) FROM tablet")->fetchColumn();

// Issued tablets (currently assigned)
$issuedTablets = $pdo->query("SELECT COUNT(*) FROM tablet WHERE is_assigned = 1")->fetchColumn();

// Available tablets
$availableTablets = $pdo->query("SELECT COUNT(*) FROM tablet WHERE is_assigned = 0")->fetchColumn();

// Under repair
$inRepair = $pdo->query("SELECT COUNT(*) FROM tablet WHERE status = 'Under Repair'")->fetchColumn();

// Returned
$returnedTablets = $pdo->query("SELECT COUNT(*) FROM tablet_returns WHERE action_taken = 'Returned'")->fetchColumn();

// Seized
$seizedTablets = $pdo->query("SELECT COUNT(*) FROM tablet WHERE status = 'Seized'")->fetchColumn();

/* ========================
   TABLETS ISSUED PER CLASS
======================== */

$classData = $pdo->query("
    SELECT 
        c.class_Name,
        COUNT(t.id) AS total_issued
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
    $classCounts[] = $row['total_issued'];
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
        font-family: "Poppins", sans-serif;
        background: linear-gradient(135deg, #eef2f7, #dde6f2);
    }

    .main-content {
        margin-left: 230px;
        padding: 30px;
    }

    .card {
        border-radius: 18px;
        transition: 0.3s ease;
        color: #fff;
        min-height: 140px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin: 15px;
    }

    .card:hover {
        transform: translateY(-6px);
    }

    .card-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .card-success {
        background: linear-gradient(135deg, #11998e, #38ef7d);
    }

    .card-warning {
        background: linear-gradient(135deg, #f7971e, #ffcc33);
    }

    .card-danger {
        background: linear-gradient(135deg, #ff416c, #ff4b2b);
    }

    .footer {
        background: #1d3557;
        color: #fff;
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

            <div class="col-sm-6 col-lg-4">
                <div class="card card-primary">
                    <i class="fa-solid fa-tablet-screen-button fa-2x mb-2"></i>
                    <h6>Total Tablets</h6>
                    <h4><?= htmlspecialchars($totalTablets) ?></h4>
                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card card-success">
                    <i class="fa-solid fa-check fa-2x mb-2"></i>
                    <h6>Issued Tablets</h6>
                    <h4><?= htmlspecialchars($issuedTablets) ?></h4>
                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card card-warning">
                    <i class="fa-solid fa-box-open fa-2x mb-2"></i>
                    <h6>Available</h6>
                    <h4><?= htmlspecialchars($availableTablets) ?></h4>
                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card card-danger">
                    <i class="fa-solid fa-tools fa-2x mb-2"></i>
                    <h6>In Repair</h6>
                    <h4><?= htmlspecialchars($inRepair) ?></h4>
                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card card-success">
                    <i class="fa-solid fa-rotate-left fa-2x mb-2"></i>
                    <h6>Returned Tablets</h6>
                    <h4><?= htmlspecialchars($returnedTablets) ?></h4>
                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card card-danger">
                    <i class="fa-solid fa-ban fa-2x mb-2"></i>
                    <h6>Seized Tablets</h6>
                    <h4><?= htmlspecialchars($seizedTablets) ?></h4>
                </div>
            </div>

        </div>

        <!-- Chart -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card p-4 text-dark bg-white shadow">
                    <h6 class="mb-3">Tablets Issued Per Class</h6>
                    <canvas id="classChart"></canvas>
                </div>
            </div>
        </div>

    </div>

    <footer class="footer">
        &copy; 2026 Senior High School Tablet Management System
    </footer>

    <script>
    const ctx = document.getElementById('classChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($classLabels) ?>,
            datasets: [{
                label: 'Number of Tablets Issued',
                data: <?= json_encode($classCounts) ?>,
                backgroundColor: '#457b9d',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    </script>

</body>

</html>