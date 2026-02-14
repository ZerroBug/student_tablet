<?php
include_once('../includes/auth_check.php');
include_once('../includes/db_connection.php');

// Dynamic card counts
$totalTablets = $pdo->query("SELECT COUNT(*) FROM tablet")->fetchColumn();
$issuedTablets = $pdo->query("SELECT COUNT(*) FROM tablet WHERE is_assigned = 1")->fetchColumn();
$availableTablets = $pdo->query("SELECT COUNT(*) FROM tablet WHERE is_assigned = 0")->fetchColumn();

$inRepair = $pdo->query("SELECT COUNT(*) FROM tablet WHERE status = 'Under Repair'")->fetchColumn();
$returnedTablets = $pdo->query("SELECT COUNT(*) FROM tablet_returns WHERE action_taken = 'Returned'")->fetchColumn();
$seizedTablets = $pdo->query("SELECT COUNT(*) FROM tablet WHERE status = 'Seized'")->fetchColumn();

// Class Chart
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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f4f6f9;
    }

    .main-content {
        margin-left: 230px;
        padding: 30px;
    }

    .dashboard-title {
        font-weight: 600;
        margin-bottom: 25px;
        color: #2c3e50;
    }

    .stat-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 20px;
        border: 1px solid #e9ecef;
        transition: 0.3s ease;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        background: #eef2f7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #3b6b9a;
    }

    .stat-title {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .stat-value {
        font-size: 1.6rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .chart-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 20px;
        border: 1px solid #e9ecef;
    }

    footer {
        margin-top: 40px;
        text-align: center;
        font-size: 0.85rem;
        color: #888;
    }

    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
            padding: 20px;
        }
    }
    </style>
</head>

<body>

    <?php include_once('../includes/mobile_sidebar.php'); ?>
    <?php include_once('../includes/desktop_sidebar.php'); ?>

    <div class="main-content">

        <?php include_once('../includes/topbar.php'); ?>

        <h4 class="dashboard-title">Dashboard Overview</h4>

        <div class="row g-4">

            <!-- Total Tablets -->
            <div class="col-md-6 col-lg-4">
                <div class="stat-card d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-title">Total Tablets</div>
                        <div class="stat-value"><?= htmlspecialchars($totalTablets) ?></div>
                    </div>
                    <div class="stat-icon">
                        <i class="fa-solid fa-tablet-screen-button"></i>
                    </div>
                </div>
            </div>

            <!-- Issued -->
            <div class="col-md-6 col-lg-4">
                <div class="stat-card d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-title">Issued Tablets</div>
                        <div class="stat-value"><?= htmlspecialchars($issuedTablets) ?></div>
                    </div>
                    <div class="stat-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>
                </div>
            </div>

            <!-- Available -->
            <div class="col-md-6 col-lg-4">
                <div class="stat-card d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-title">Available Tablets</div>
                        <div class="stat-value"><?= htmlspecialchars($availableTablets) ?></div>
                    </div>
                    <div class="stat-icon">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                </div>
            </div>

            <!-- In Repair -->
            <div class="col-md-6 col-lg-4">
                <div class="stat-card d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-title">Under Repair</div>
                        <div class="stat-value"><?= htmlspecialchars($inRepair) ?></div>
                    </div>
                    <div class="stat-icon">
                        <i class="fa-solid fa-tools"></i>
                    </div>
                </div>
            </div>

            <!-- Returned -->
            <div class="col-md-6 col-lg-4">
                <div class="stat-card d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-title">Returned Tablets</div>
                        <div class="stat-value"><?= htmlspecialchars($returnedTablets) ?></div>
                    </div>
                    <div class="stat-icon">
                        <i class="fa-solid fa-rotate-left"></i>
                    </div>
                </div>
            </div>

            <!-- Seized -->
            <div class="col-md-6 col-lg-4">
                <div class="stat-card d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-title">Seized Tablets</div>
                        <div class="stat-value"><?= htmlspecialchars($seizedTablets) ?></div>
                    </div>
                    <div class="stat-icon">
                        <i class="fa-solid fa-ban"></i>
                    </div>
                </div>
            </div>

        </div>

        <!-- Chart Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="chart-card">
                    <h6 class="mb-3">Tablet Distribution by Class</h6>
                    <canvas id="classChart"></canvas>
                </div>
            </div>
        </div>

        <footer>
            &copy; 2025 Senior High School Tablet Management System
        </footer>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    const ctx = document.getElementById('classChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($classLabels) ?>,
            datasets: [{
                label: 'Students with Tablets',
                data: <?= json_encode($classCounts) ?>,
                backgroundColor: '#3b6b9a',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    </script>

</body>

</html>