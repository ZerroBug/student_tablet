<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Tablet | Dashboard</title>

    <!-- Favicon -->
    <link rel="icon" href="assets/images/logo.jpg" type="image/jpeg">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
    body {
        font-family: "Poppins", sans-serif;
        background-color: #f8f9fa;
        overflow-x: hidden;
    }

    /* Sidebar */
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

    .sidebar h4 {
        font-size: 1.3rem;
        font-weight: 600;
        color: white;
        padding-left: 20px;
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

    /* Main Content */
    .main-content {
        margin-left: 230px;
        padding: 30px;
        transition: margin-left 0.3s ease;
    }

    /* Footer */
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

    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
            padding: 20px;
        }

        .footer {
            left: 0;
            width: 100%;
        }
    }

    /* Form Card */
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }

    .card-body {
        padding: 30px;
    }

    /* Form Fields */
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

    /* Offcanvas Mobile Sidebar */
    .offcanvas-body nav {
        background-color: #1d3557;
        height: 100%;
        padding-top: 1rem;
    }

    .offcanvas-body nav a {
        display: flex;
        align-items: center;
        padding: 14px 20px;
        color: #fff;
        font-weight: 500;
        text-decoration: none;
        font-size: 1rem;
    }

    .offcanvas-body nav a i {
        margin-right: 14px;
        font-size: 1.3rem;
    }

    .offcanvas-body nav a:hover {
        background-color: #457b9d;
        border-radius: 0 25px 25px 0;
    }

    .offcanvas-body nav a.active-menu {
        background-color: #2a9d8f;
        font-weight: 600;
        border-radius: 0 25px 25px 0;
    }

    .offcanvas {
        box-shadow: 5px 0 15px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease-in-out;
    }
    </style>
</head>

<body>

    <!-- Mobile Offcanvas Sidebar -->
    <?php include_once('../includes/mobile_sidebar.php'); ?>


    <!-- Desktop Sidebar -->
    <?php include_once('../includes/desktop_sidebar.php'); ?>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Topbar -->
        <?php include_once('../includes/topbar.php'); ?>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Topbar -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0"><i class="fa fa-chart-bar me-2""></i>Reports</h2>
    <div>
      <i class=" fa fa-bell me-3 fs-5"></i>
                    <i class="fa fa-user-circle fs-4"></i>
            </div>
        </div>
        <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
            <div class="topbar d-flex justify-content-between align-items-center p-3">
                <h5 class="m-0">Reports</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm" onclick="window.print()"><i
                            class="fa fa-print me-1"></i>Print</button>
                    <button class="btn btn-outline-success btn-sm" onclick="exportCSV()"><i
                            class="fa fa-file-csv me-1"></i>Export CSV</button>
                </div>
            </div>

            <div class="row g-3 my-3">
                <div class="col-lg-6">
                    <div class="card shadow-sm p-3">
                        <div class="fw-semibold mb-2">Issued vs Available</div>
                        <canvas id="issuedChart" height="160"></canvas>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow-sm p-3">
                        <div class="fw-semibold mb-2">Repairs Over Time</div>
                        <canvas id="repairTrend" height="160"></canvas>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm p-3 my-3">
                <div class="fw-semibold mb-2">Inventory Snapshot</div>
                <div class="table-responsive">
                    <table class="table table-striped" id="reportTable">
                        <thead>
                            <tr>
                                <th>Tablet ID</th>
                                <th>Serial</th>
                                <th>Status</th>
                                <th>Holder</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>T001</td>
                                <td>SN12345</td>
                                <td>Issued</td>
                                <td>John Doe</td>
                            </tr>
                            <tr>
                                <td>T002</td>
                                <td>SN67890</td>
                                <td>In Repair</td>
                                <td>—</td>
                            </tr>
                            <tr>
                                <td>T003</td>
                                <td>SN77777</td>
                                <td>Available</td>
                                <td>—</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
    </div>
    <!-- Footer -->
    <footer class="footer">
        &copy; 2025 Okomfo Anokye Senior High School Tablet Management. All Rights Reserved.
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="../assets/js/app.js"></script>
    <script>
    new Chart(document.getElementById('issuedChart'), {
        type: 'doughnut',
        data: {
            labels: ['Issued', 'Available', 'In Repair'],
            datasets: [{
                data: [85, 30, 5]
            }]
        }
    });
    new Chart(document.getElementById('repairTrend'), {
        type: 'line',
        data: {
            labels: ['Apr', 'May', 'Jun', 'Jul', 'Aug'],
            datasets: [{
                label: 'Repairs',
                data: [2, 4, 3, 6, 5]
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    function exportCSV() {
        const rows = [...document.querySelectorAll('#reportTable tr')].map(tr => [...tr.children].map(td => td
            .innerText));
        const csv = rows.map(r => r.map(v => `"${v.replace(/"/g,'""')}"`).join(',')).join('\n');
        const blob = new Blob([csv], {
            type: 'text/csv;charset=utf-8;'
        });
        const url = URL.createObjectURL(blob);
        const a = Object.assign(document.createElement('a'), {
            href: url,
            download: 'inventory_report.csv'
        });
        a.click();
        URL.revokeObjectURL(url);
        showToast('CSV exported');
    }
    </script>
</body>

</html>