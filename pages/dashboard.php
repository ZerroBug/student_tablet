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

        <!-- Tablet Stats -->
        <!-- Tablet Stats -->
        <div class="row g-3">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-stat shadow-sm p-3 d-flex align-items-center">
                    <div class="text-primary mb-2"><i class="fa-solid fa-tablet-screen-button fa-2x"></i></div>
                    <h6>Total Tablets</h6>
                    <h4>200</h4>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-stat shadow-sm p-3 d-flex align-items-center">
                    <div class="text-success mb-2"><i class="fa-solid fa-check fa-2x"></i></div>
                    <h6>Issued Tablets</h6>
                    <h4>85</h4>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-stat shadow-sm p-3 d-flex align-items-center">
                    <div class="text-warning mb-2"><i class="fa-solid fa-box-open fa-2x"></i></div>
                    <h6>Available</h6>
                    <h4>30</h4>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-stat shadow-sm p-3 d-flex align-items-center">
                    <div class="text-danger mb-2"><i class="fa-solid fa-tools fa-2x"></i></div>
                    <h6>In Repair</h6>
                    <h4>5</h4>
                </div>
            </div>
        </div>


        <!-- People Stats -->
        <div class="row g-3 mt-2">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-stat shadow-sm p-3 text-center">
                    <div class="text-success mb-2"><i class="fa-solid fa-rotate-left fa-2x"></i></div>
                    <h6>Returned Tablets</h6>
                    <h4>40</h4>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card card-stat shadow-sm p-3 text-center">
                    <div class="text-primary mb-2"><i class="fa-solid fa-user fa-2x"></i></div>
                    <h6>Male Students Issued</h6>
                    <h4>120</h4>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-stat shadow-sm p-3 text-center">
                    <div class="text-danger mb-2"><i class="fa-solid fa-user-graduate fa-2x"></i></div>
                    <h6>Female Students Issued</h6>
                    <h4>95</h4>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-stat shadow-sm p-3 text-center">
                    <div class="text-secondary mb-2"><i class="fa-solid fa-chalkboard-teacher fa-2x"></i></div>
                    <h6>Admins</h6>
                    <h4>15</h4>
                </div>
            </div>

        </div>

        <!-- Charts -->
        <div class="row mt-4 g-3">
            <div class="col-lg-6">
                <div class="card shadow-sm p-3">
                    <h6>Tablet Status Overview</h6>
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow-sm p-3">
                    <h6>Usage by Class</h6>
                    <canvas id="classChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Tablets Table -->
        <div class="card shadow-sm p-3 my-4">
            <h6>Recent Tablets</h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tablet ID</th>
                            <th>Serial Number</th>
                            <th>Assigned To</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>T001</td>
                            <td>SN12345</td>
                            <td>John Doe</td>
                            <td><span class="badge bg-success">Available</span></td>
                        </tr>
                        <tr>
                            <td>T002</td>
                            <td>SN67890</td>
                            <td>Mary Smith</td>
                            <td><span class="badge bg-warning">In Repair</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer">
            &copy; 2025 Okomfo Anokye Senior High School Tablet Management. All Rights Reserved.
        </footer>

    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    const ctx1 = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: ['Available', 'Issued', 'In Repair'],
            datasets: [{
                data: [30, 85, 5],
                backgroundColor: ['#2a9d8f', '#e9c46a', '#e76f51']
            }]
        }
    });

    const ctx2 = document.getElementById('classChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Form 1', 'Form 2', 'Form 3'],
            datasets: [{
                label: 'Tablets in Use',
                data: [25, 35, 25],
                backgroundColor: '#457b9d'
            }]
        }
    });
    </script>
</body>

</html>