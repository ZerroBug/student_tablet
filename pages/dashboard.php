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
        color: white;
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

    /* ================= BEAUTIFUL CARDS ================= */

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

    /* ================= MODERN GRADIENT COLORS ================= */

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

    /* ================= TEXT STYLING ================= */

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

    /* ================= FOOTER ================= */

    .footer {
        position: fixed;
        bottom: 0;
        left: 230px;
        width: calc(100% - 230px);
        background: linear-gradient(90deg, #1d3557, #243b55);
        color: #fff;
        text-align: center;
        padding: 1 </head> <body> <?php include_once('../includes/mobile_sidebar.php');
        ?><?php include_once('../includes/desktop_sidebar.php');
        ?><div class="main-content"><?php include_once('../includes/topbar.php');
        ?><div class="row g-3"><div class="col-sm-6 col-lg-4"><div class="card card-stat card-primary"><div class="mb-2"><i class="fa-solid fa-tablet-screen-button fa-2x"></i></div><h6>Total Tablets</h6><h4><?=htmlspecialchars($totalTablets) ?></h4></div></div><div class="col-sm-6 col-lg-4"><div class="card card-stat card-success"><div class="mb-2"><i class="fa-solid fa-check fa-2x"></i></div><h6>Issued Tablets</h6><h4><?=htmlspecialchars($issuedTablets) ?></h4></div></div><div class="col-sm-6 col-lg-4"><div class="card card-stat card-warning"><div class="mb-2"><i class="fa-solid fa-box-open fa-2x"></i></div><h6>Available</h6><h4><?=htmlspecialchars($availableTablets) ?></h4></div></div><div class="col-sm-6 col-lg-4"><div class="card card-stat card-danger"><div class="mb-2"><i class="fa-solid fa-tools fa-2x"></i></div><h6>In Repair</h6><h4><?=htmlspecialchars($inRepair) ?></h4></div></div><div class="col-sm-6 col-lg-4"><div class="card card-stat card-success"><div class="mb-2"><i class="fa-solid fa-rotate-left fa-2x"></i></div><h6>Returned Tablets</h6><h4><?=htmlspecialchars($returnedTablets) ?></h4></div></div><div class="col-sm-6 col-lg-4"><div class="card card-stat card-danger"><div class="mb-2"><i class="fa-solid fa-ban fa-2x"></i></div><h6>Seized Tablets</h6><h4><?=htmlspecialchars($seizedTablets) ?></h4></div></div>< !-- <div class="col-sm-6 col-lg-4"><div class="card card-stat card-secondary"><div class="mb-2"><i class="fa-solid fa-chalkboard-teacher fa-2x"></i></div><h6>Admins</h6><h4><?=htmlspecialchars($adminCount) ?></h4></div></div>--></div>< !-- Full-width Class Usage Chart --><div class="row mt-4 mb-4 g-3"><div class="col-12"><div class="card shadow-sm p-3"><h6>Usage by Class</h6><canvas id="classChart"></canvas></div></div></div><footer class="footer">&copy;
        2025 Senior High School Tablet Management. All Rights Reserved. </footer></div><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script><script src="https://cdn.jsdelivr.net/npm/chart.js"></script><script> // Class Bar Chart
        const ctx2=document.getElementById('classChart').getContext('2d');

        new Chart(ctx2, {

                type: 'bar',
                data: {

                    labels: <?=json_encode($classLabels) ?>,
                    datasets: [ {
                        label: 'Students Who Received Tablets',
                        data: <?=json_encode($classCounts) ?>,
                        backgroundColor: '#457b9d',
                        borderRadius: 6,
                    }

                    ]
                }

                ,
                options: {

                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            stepSize: 1
                        }
                    }

                    ,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            }

        );
        </script></body></html>