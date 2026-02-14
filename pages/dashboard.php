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
    body {
        font-family: "Poppins", sans-serif;
        background: #eef2f7;
        overflow-x: hidden;
    }

    /* Sidebar (UNCHANGED) */
    .sidebar {
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        width: 250px;
        background: linear-gradient(180deg, #1d3557, #2a4964);
        padding-top: 1rem;
        z-index: 1040;
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
    }

    .sidebar h4 {
        font-size: 1.3rem;
        font-weight: 600;
        color: #fff;
        padding-left: 20px;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
    }

    .sidebar a {
        color: white;
        text-decoration: none;
        display: block;
        padding: 12px 20px;
        margin-bottom: 4px;
        border-radius: 8px;
        font-size: 1rem;
        transition: 0.3s;
    }

    .sidebar a.active,
    .sidebar a:hover {
        background-color: #3b6b9a;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    /* Main Content (UNCHANGED) */
    .main-content {
        margin-left: 230px;
        padding: 30px;
        transition: margin-left 0.3s ease;
    }

    /* =========================
   UPDATED PROFESSIONAL CARDS
   ========================= */

    .card {
        border-radius: 14px;
        transition: all 0.3s ease;
        background: #ffffff;
        /* Clean white */
        color: #2c3e50;
        border: 1px solid #e5e7eb;
        /* Soft border */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        min-height: 120px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin: 15px;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    /* Remove all colored gradients */
    .card-primary,
    .card-success,
    .card-warning,
    .card-danger,
    .card-secondary {
        background: #ffffff !important;
    }

    /* Card text styling */
    .card-stat h6 {
        font-size: 0.9rem;
        color: #6b7280;
        /* Soft grey */
        margin-bottom: 6px;
        font-weight: 500;
    }

    .card-stat h4 {
        font-size: 1.6rem;
        font-weight: 600;
        color: #1f2937;
    }

    /* Icon styling */
    .card-stat .fa-2x {
        margin-bottom: 12px;
        padding: 14px;
        font-size: 22px;
        border-radius: 50%;
        background: #f3f4f6;
        /* Light grey circle */
        color: #3b6b9a;
        /* Subtle professional blue */
    }

    /* Footer (UNCHANGED) */
    .footer {
        position: fixed;
        bottom: 0;
        left: 230px;
        width: calc(100% - 230px);
        background: #1d3557;
        color: #fff;
        text-align: center;
        padding: 10px 0;
        font-size: 0.85rem;
        box-shadow: 0 -3px 10px rgba(0, 0, 0, 0.1);
    }

    .card canvas {
        margin-top: 15px;
    }

    /* Responsive */
    @media (max-width:768px) {
        .main-content {
            margin-left: 0;
            padding: 20px;
        }

        .footer {
            left: 0;
            width: 100%;
        }
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