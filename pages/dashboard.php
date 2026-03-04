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

/* ========================
   TABLETS ASSIGNED PER CLASS
======================== */

$classData = $pdo->query("
    SELECT 
        c.class_Name,
        COUNT(t.id) AS tablets_assigned
    FROM class c
    LEFT JOIN students s ON s.class_id = c.id
    LEFT JOIN tablet_assignments ta ON ta.student_id = s.id
    LEFT JOIN tablet t ON t.id = ta.tablet_id
    WHERE t.is_assigned = 1
    GROUP BY c.id
    ORDER BY c.id ASC
")->fetchAll(PDO::FETCH_ASSOC);

$classLabels = [];
$classCounts = [];

foreach ($classData as $row) {
    $classLabels[] = $row['class_Name'];
    $classCounts[] = $row['tablets_assigned'];
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
    /* ================= GLOBAL ================= */
    body {
        font-family: "Poppins", sans-serif;
        background: linear-gradient(135deg, #eef2f7, #dde6f2);
        overflow-x: hidden;
    }

    /* ================= SIDEBAR ================= */
    .sidebar {
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        width: 250px;
        background: linear-gradient(180deg, #1d3557, #243b55);
        padding-top: 1rem;
        z-index: 1040;
        box-shadow: 4px 0 15px rgba(0, 0, 0, 0.15);
    }

    .sidebar h4 {
        font-size: 1.4rem;
        font-weight: 600;
        color: #fff;
        padding-left: 20px;
        letter-spacing: 0.5px;
    }

    .sidebar a {
        color: #fff;
        text-decoration: none;
        display: block;
        padding: 12px 20px;
        margin: 6px 10px;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .sidebar a.active,
    .sidebar a:hover {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(6px);
        transform: translateX(5px);
    }

    /* ================= MAIN CONTENT ================= */
    .main-content {
        margin-left: 230px;
        padding: 30px;
        transition: margin-left 0.3s ease;
    }

    /* ================= CARDS ================= */
    .card {
        border-radius: 18px;
        transition: all 0.35s ease;
        color: #fff;
        min-height: 140px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin: 15px;
        position: relative;
        overflow: hidden;
    }

    /* Shine effect */
    .card::before {
        content: "";
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(120deg,
                rgba(255, 255, 255, 0.15) 0%,
                rgba(255, 255, 255, 0.05) 40%,
                rgba(255, 255, 255, 0.15) 60%);
        transform: rotate(25deg);
    }

    /* Hover effect */
    .card:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 20px 35px rgba(0, 0, 0, 0.18);
    }

    /* ================= CARD COLORS ================= */
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

    .card-secondary {
        background: linear-gradient(135deg, #757f9a, #d7dde8);
        color: #2c3e50;
    }

    /* ================= CARD TEXT ================= */
    .card-stat h6 {
        font-size: 0.95rem;
        margin-bottom: 6px;
        opacity: 0.9;
    }

    .card-stat h4 {
        font-size: 1.6rem;
        font-weight: 600;
    }

    /* ================= ICON STYLE ================= */
    .card-stat .fa-2x {
        margin-bottom: 12px;
        padding: 16px;
        font-size: 26px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(4px);
    }

    /* ================= CHART CARD ================= */
    .chart-card {
        background: #fff;
        border-radius: 18px;
        padding: 25px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    /* ================= FOOTER ================= */
    .footer {
        position: fixed;
        bottom: 0;
        left: 230px;
        width: calc(100% - 230px);
        background: linear-gradient(90deg, #1d3557, #243b55);
        color: #fff;
        text-align: center;
        padding: 10px 0;
        font-size: 0.85rem;
        box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.15);
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 768px) {

        .main-content {
            margin-left: 0;
            padding: 20px;
        }

        .footer {
            left: 0;
            width: 100%;
        }

        .sidebar {
            width: 200px;
        }
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