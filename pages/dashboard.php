<?php
include_once('../includes/auth_check.php');
include_once('../includes/db_connection.php');

/* ==================================================
   TABLET STATISTICS
================================================== */

// Total Tablets
$totalTablets = $pdo->query("
    SELECT COUNT(*)
    FROM tablet
")->fetchColumn();

// Issued Tablets
$issuedTablets = $pdo->query("
    SELECT COUNT(*)
    FROM tablet
    WHERE is_assigned = 1
")->fetchColumn();

// Available Tablets
$availableTablets = $pdo->query("
    SELECT COUNT(*)
    FROM tablet
    WHERE status = 'Available'
")->fetchColumn();

// Tablets Under Repair
$inRepair = $pdo->query("
    SELECT COUNT(*)
    FROM tablet
    WHERE status = 'Under Repair'
")->fetchColumn();

// Seized Tablets
$seizedTablets = $pdo->query("
    SELECT COUNT(*)
    FROM tablet
    WHERE status = 'Seized'
")->fetchColumn();

/* ==================================================
   TABLET REPORT STATISTICS
================================================== */

// Total Reports
$totalReports = $pdo->query("
    SELECT COUNT(*)
    FROM tablet_reports
")->fetchColumn();

// Missing Tablets
$missingTablets = $pdo->query("
    SELECT COUNT(*)
    FROM tablet_reports
    WHERE incident_type = 'Missing'
")->fetchColumn();

// Damaged Tablets
$damagedTablets = $pdo->query("
    SELECT COUNT(*)
    FROM tablet_reports
    WHERE incident_type = 'Damaged'
")->fetchColumn();

// Faulty Tablets
$faultyTablets = $pdo->query("
    SELECT COUNT(*)
    FROM tablet_reports
    WHERE incident_type = 'Faulty'
")->fetchColumn();

// Pending Reports
$pendingReports = $pdo->query("
    SELECT COUNT(*)
    FROM tablet_reports
    WHERE status = 'Pending'
")->fetchColumn();

// Under Investigation
$underInvestigation = $pdo->query("
    SELECT COUNT(*)
    FROM tablet_reports
    WHERE status = 'Under Investigation'
")->fetchColumn();

// Under Repair Reports
$repairReports = $pdo->query("
    SELECT COUNT(*)
    FROM tablet_reports
    WHERE status = 'Under Repair'
")->fetchColumn();

// Closed Reports
$closedReports = $pdo->query("
    SELECT COUNT(*)
    FROM tablet_reports
    WHERE status = 'Closed'
")->fetchColumn();

// Reports Submitted Today
$todayReports = $pdo->query("
    SELECT COUNT(*)
    FROM tablet_reports
    WHERE DATE(report_date) = CURDATE()
")->fetchColumn();

// Reports This Month
$thisMonthReports = $pdo->query("
    SELECT COUNT(*)
    FROM tablet_reports
    WHERE MONTH(report_date) = MONTH(CURDATE())
      AND YEAR(report_date) = YEAR(CURDATE())
")->fetchColumn();


/* ==================================================
   STUDENTS ASSIGNED PER CLASS
================================================== */

$classData = $pdo->query("
    SELECT
        c.class_Name,
        COUNT(DISTINCT s.id) AS students_assigned
    FROM class c
    LEFT JOIN students s
        ON s.class_id = c.id
    LEFT JOIN tablet_assignments ta
        ON ta.student_id = s.id
    LEFT JOIN tablet t
        ON t.id = ta.tablet_id
       AND t.is_assigned = 1
    GROUP BY c.id
    ORDER BY c.class_Name
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
    /* ================= GLOBAL ================= */
    body {
        font-family: "Poppins", sans-serif;
        background: #f4f7fb;
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
        box-shadow: 4px 0 15px rgba(0, 0, 0, .15);
    }

    .sidebar h4 {
        color: #fff;
        font-size: 1.4rem;
        font-weight: 600;
        padding-left: 20px;
    }

    .sidebar a {
        color: #fff;
        display: block;
        text-decoration: none;
        padding: 12px 20px;
        margin: 6px 10px;
        border-radius: 10px;
        transition: .3s;
    }

    .sidebar a:hover,
    .sidebar a.active {
        background: rgba(255, 255, 255, .15);
        transform: translateX(5px);
    }

    /* ================= MAIN CONTENT ================= */

    .main-content {
        margin-left: 230px;
        padding: 25px;
        transition: .3s;
    }

    /* ================= DASHBOARD CARDS ================= */

    .dashboard-card {
        border: none;
        border-radius: 14px;
        height: 90px;
        padding: 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
        transition: .25s;
        color: #fff;
        overflow: hidden;
        position: relative;
    }

    .dashboard-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 22px rgba(0, 0, 0, .15);
    }

    .dashboard-card .icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: rgba(255, 255, 255, .18);
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 22px;
    }

    .dashboard-card .content {
        text-align: right;
    }

    .dashboard-card .content h6 {
        margin: 0;
        font-size: 13px;
        font-weight: 500;
        opacity: .9;
    }

    .dashboard-card .content h3 {
        margin-top: 4px;
        font-size: 25px;
        font-weight: 700;
    }

    /* ================= CARD COLORS ================= */

    .bg-primary-gradient {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
    }

    .bg-success-gradient {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .bg-warning-gradient {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .bg-danger-gradient {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .bg-purple-gradient {
        background: linear-gradient(135deg, #7c3aed, #6d28d9);
    }

    .bg-dark-gradient {
        background: linear-gradient(135deg, #374151, #111827);
    }

    .bg-info-gradient {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
    }

    .bg-secondary-gradient {
        background: linear-gradient(135deg, #64748b, #475569);
    }

    /* ================= CHART ================= */

    .chart-card {
        background: #fff;
        border: none;
        border-radius: 14px;
        padding: 25px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
    }

    /* ================= FOOTER ================= */

    .footer {
        position: fixed;
        bottom: 0;
        left: 230px;
        width: calc(100% - 230px);
        background: #1d3557;
        color: #fff;
        text-align: center;
        padding: 10px;
        font-size: .85rem;
    }

    /* ================= RESPONSIVE ================= */

    @media(max-width:768px) {

        .main-content {
            margin-left: 0;
            padding: 20px;
        }

        .footer {
            left: 0;
            width: 100%;
        }

        .dashboard-card {
            height: 80px;
            padding: 15px;
        }

        .dashboard-card .icon {
            width: 46px;
            height: 46px;
            font-size: 20px;
        }

        .dashboard-card .content h3 {
            font-size: 22px;
        }
    }
    </style>
</head>

<body>

    <?php include_once('../includes/mobile_sidebar.php'); ?>
    <?php include_once('../includes/desktop_sidebar.php'); ?>
    <?php include_once('../includes/topbar.php'); ?>

    <div class="main-content">

        <div class="row g-3 mb-4">

            <!-- Total Tablets -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="dashboard-card bg-primary-gradient">
                    <div class="icon">
                        <i class="fa-solid fa-tablet-screen-button"></i>
                    </div>
                    <div class="content">
                        <h6>Total Tablets</h6>
                        <h3><?= $totalTablets; ?></h3>
                    </div>
                </div>
            </div>

            <!-- Issued Tablets -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="dashboard-card bg-success-gradient">
                    <div class="icon">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <div class="content">
                        <h6>Issued Tablets</h6>
                        <h3><?= $issuedTablets; ?></h3>
                    </div>
                </div>
            </div>

            <!-- Available Tablets -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="dashboard-card bg-warning-gradient">
                    <div class="icon">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <div class="content">
                        <h6>Available</h6>
                        <h3><?= $availableTablets; ?></h3>
                    </div>
                </div>
            </div>

            <!-- Under Repair -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="dashboard-card bg-danger-gradient">
                    <div class="icon">
                        <i class="fa-solid fa-screwdriver-wrench"></i>
                    </div>
                    <div class="content">
                        <h6>Under Repair</h6>
                        <h3><?= $inRepair; ?></h3>
                    </div>
                </div>
            </div>

            <!-- Total Reports -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="dashboard-card bg-info-gradient">
                    <div class="icon">
                        <i class="fa-solid fa-file-circle-exclamation"></i>
                    </div>
                    <div class="content">
                        <h6>Total Reports</h6>
                        <h3><?= $totalReports; ?></h3>
                    </div>
                </div>
            </div>

            <!-- Missing -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="dashboard-card bg-purple-gradient">
                    <div class="icon">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div class="content">
                        <h6>Missing</h6>
                        <h3><?= $missingTablets; ?></h3>
                    </div>
                </div>
            </div>

            <!-- Damaged -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="dashboard-card bg-warning-gradient">
                    <div class="icon">
                        <i class="fa-solid fa-tablet-button"></i>
                    </div>
                    <div class="content">
                        <h6>Damaged</h6>
                        <h3><?= $damagedTablets; ?></h3>
                    </div>
                </div>
            </div>

            <!-- Faulty -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="dashboard-card bg-secondary-gradient">
                    <div class="icon">
                        <i class="fa-solid fa-bug"></i>
                    </div>
                    <div class="content">
                        <h6>Faulty</h6>
                        <h3><?= $faultyTablets; ?></h3>
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="dashboard-card bg-warning-gradient">
                    <div class="icon">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                    <div class="content">
                        <h6>Pending</h6>
                        <h3><?= $pendingReports; ?></h3>
                    </div>
                </div>
            </div>

            <!-- Investigation -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="dashboard-card bg-info-gradient">
                    <div class="icon">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <div class="content">
                        <h6>Investigation</h6>
                        <h3><?= $underInvestigation; ?></h3>
                    </div>
                </div>
            </div>

            <!-- Seized -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="dashboard-card bg-dark-gradient">
                    <div class="icon">
                        <i class="fa-solid fa-ban"></i>
                    </div>
                    <div class="content">
                        <h6>Seized</h6>
                        <h3><?= $seizedTablets; ?></h3>
                    </div>
                </div>
            </div>

            <!-- Closed -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="dashboard-card bg-success-gradient">
                    <div class="icon">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div class="content">
                        <h6>Closed Reports</h6>
                        <h3><?= $closedReports; ?></h3>
                    </div>
                </div>
            </div>

        </div>

        <!-- Students Assigned Per Class Chart -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="chart-card p-4">
                    <h6 class="mb-3">Students Assigned Tablets Per Class</h6>
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
                label: 'Students Assigned Tablets',
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
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw + ' student(s)';
                        }
                    }
                }
            }
        }
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
    <script>
    < /body>

    <
    /html>