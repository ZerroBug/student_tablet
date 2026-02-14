<?php
include_once('../includes/auth_check.php');
include_once('../includes/db_connection.php');

// Dynamic card counts
$totalTablets = $pdo->query("SELECT COUNT(*) FROM tablet")->fetchColumn();
$issuedTablets = $pdo->query("SELECT COUNT(*) FROM tablet WHERE is_assigned = 1")->fetchColumn();
$availableTablets = $pdo->query("SELECT COUNT(*) FROM tablet WHERE is_assigned = 0")->fetchColumn();

// Status-based counts
$inRepair = $pdo->query("SELECT COUNT(*) FROM tablet WHERE status = 'Under Repair'")->fetchColumn();
$returnedTablets = $pdo->query("SELECT COUNT(*) FROM tablet_returns WHERE action_taken = 'Returned'")->fetchColumn();
$seizedTablets = $pdo->query("SELECT COUNT(*) FROM tablet WHERE status = 'Seized'")->fetchColumn();

$adminCount = $pdo->query("SELECT COUNT(*) FROM admin")->fetchColumn();

// Dynamic tablet counts per class
$classes = $pdo->query("SELECT id, class_Name FROM class ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
$classLabels = [];
$classCounts = [];
foreach ($classes as $cls) {
    $classLabels[] = $cls['class_Name'];
    $count = $pdo->prepare("
        SELECT COUNT(DISTINCT ta.student_id) 
        FROM tablet_assignments ta
        JOIN students s ON s.id = ta.student_id
        WHERE s.class_id = ?
    ");
    $count->execute([$cls['id']]);
    $classCounts[] = $count->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Dashboard</title>
    <link rel="icon" href="assets/images/logo.jpg" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
    /* =========================
   GLOBAL STYLES
========================= */

    body {
        background-color: #f4f6f9;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
    }

    /* =========================
   ROW SPACING FIX
========================= */

    .row.g-3 {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    /* =========================
   FORCE 3 CARDS PER ROW
========================= */

    .col-sm-6.col-lg-3 {
        flex: 0 0 33.3333%;
        max-width: 33.3333%;
        padding: 15px;
    }

    /* Tablet */
    @media (max-width: 992px) {
        .col-sm-6.col-lg-3 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

    /* Mobile */
    @media (max-width: 576px) {
        .col-sm-6.col-lg-3 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }

    /* =========================
   CARD DESIGN
========================= */

    .card {
        border-radius: 14px;
        transition: all 0.3s ease;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-top: 4px solid #d1d5db;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        min-height: 130px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;

        /* Remove Bootstrap default card spacing if any */
        padding: 25px 15px;
    }

    /* Hover Effect */
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
    }

    /* =========================
   SOFT ACCENT COLORS
========================= */

    .card-primary {
        border-top-color: #3b82f6;
    }

    .card-success {
        border-top-color: #10b981;
    }

    .card-warning {
        border-top-color: #f59e0b;
    }

    .card-danger {
        border-top-color: #ef4444;
    }

    .card-secondary {
        border-top-color: #6b7280;
    }

    /* =========================
   ICON STYLING
========================= */

    .card-stat .fa-2x {
        margin-bottom: 15px;
        padding: 14px;
        font-size: 22px;
        border-radius: 50%;
    }

    /* Icon Background Colors */
    .card-primary .fa-2x {
        background: rgba(59, 130, 246, 0.12);
        color: #3b82f6;
    }

    .card-success .fa-2x {
        background: rgba(16, 185, 129, 0.12);
        color: #10b981;
    }

    .card-warning .fa-2x {
        background: rgba(245, 158, 11, 0.15);
        color: #f59e0b;
    }

    .card-danger .fa-2x {
        background: rgba(239, 68, 68, 0.12);
        color: #ef4444;
    }

    .card-secondary .fa-2x {
        background: rgba(107, 114, 128, 0.12);
        color: #6b7280;
    }

    /* =========================
   TEXT STYLING
========================= */

    .card-stat h6 {
        font-size: 0.9rem;
        color: #6b7280;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .card-stat h4 {
        font-size: 1.8rem;
        font-weight: 600;
        color: #1f2937;
    }

    /* =========================
   OPTIONAL: SMOOTHER FONT RENDERING
========================= */

    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    </style>
</head>

<body>
    <?php include_once('../includes/mobile_sidebar.php'); ?>
    <?php include_once('../includes/desktop_sidebar.php'); ?>
    <div class="main-content">
        <?php include_once('../includes/topbar.php'); ?>

        <div class="row g-3">
            <div class="col-sm-6 col-lg-4">
                <div class="card card-stat card-primary">
                    <div class="mb-2"><i class="fa-solid fa-tablet-screen-button fa-2x"></i></div>
                    <h6>Total Tablets</h6>
                    <h4><?= htmlspecialchars($totalTablets) ?></h4>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card card-stat card-success">
                    <div class="mb-2"><i class="fa-solid fa-check fa-2x"></i></div>
                    <h6>Issued Tablets</h6>
                    <h4><?= htmlspecialchars($issuedTablets) ?></h4>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card card-stat card-warning">
                    <div class="mb-2"><i class="fa-solid fa-box-open fa-2x"></i></div>
                    <h6>Available</h6>
                    <h4><?= htmlspecialchars($availableTablets) ?></h4>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card card-stat card-danger">
                    <div class="mb-2"><i class="fa-solid fa-tools fa-2x"></i></div>
                    <h6>In Repair</h6>
                    <h4><?= htmlspecialchars($inRepair) ?></h4>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card card-stat card-success">
                    <div class="mb-2"><i class="fa-solid fa-rotate-left fa-2x"></i></div>
                    <h6>Returned Tablets</h6>
                    <h4><?= htmlspecialchars($returnedTablets) ?></h4>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card card-stat card-danger">
                    <div class="mb-2"><i class="fa-solid fa-ban fa-2x"></i></div>
                    <h6>Seized Tablets</h6>
                    <h4><?= htmlspecialchars($seizedTablets) ?></h4>
                </div>
            </div>
            <!-- <div class="col-sm-6 col-lg-4">
                <div class="card card-stat card-secondary">
                    <div class="mb-2"><i class="fa-solid fa-chalkboard-teacher fa-2x"></i></div>
                    <h6>Admins</h6>
                    <h4><?= htmlspecialchars($adminCount) ?></h4>
                </div>
            </div> -->
        </div>

        <!-- Full-width Class Usage Chart -->
        <div class="row mt-4 mb-4 g-3">
            <div class="col-12">
                <div class="card shadow-sm p-3">
                    <h6>Usage by Class</h6>
                    <canvas id="classChart"></canvas>
                </div>
            </div>
        </div>

        <footer class="footer">
            &copy; 2025 Senior High School Tablet Management. All Rights Reserved.
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Class Bar Chart
    const ctx2 = document.getElementById('classChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: <?= json_encode($classLabels) ?>,
            datasets: [{
                label: 'Students Who Received Tablets',
                data: <?= json_encode($classCounts) ?>,
                backgroundColor: '#457b9d',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1
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