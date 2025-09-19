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

        <!-- Form Card -->
        <div class="card shadow-sm mb-5">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                    <div class="mb-2">
                        <h5 class="text-primary mb-0">Add New Tablet</h5>
                    </div>

                    <form class="d-flex align-items-center gap-2" action="#" method="post"
                        enctype="multipart/form-data">
                        <input type="file" class="form-control" id="formFile" name="file" required
                            style="max-width: 250px;" />
                        <button type="submit" class="btn btn-success btn-md">
                            <i class="fa fa-file-excel me-2"></i>Import
                        </button>
                    </form>
                </div>


                <form action="#" method="post">
                    <!-- Tablet ID, Serial Number, IMEI -->

                    <div class="row mb-4 g-3">
                        <div class="col-md-4 ">
                            <label for="level" class="form-label fw-semibold">Academic Level</label>
                            <select class="form-select" id="level" required>
                                <option selected disabled>Select Level</option>
                                <option value="SHS_1">SHS 1</option>
                                <option value="SHS_2">SHS 2</option>
                                <option value="SHS_3">SHS 3</option>
                            </select>

                        </div>
                        <div class="col-md-4">
                            <label for="tabletId" class="form-label">Tablet ID</label>
                            <input type="text" class="form-control" id="tabletId" name="tablet_id"
                                placeholder="Enter Tablet ID" required>
                        </div>
                        <div class="col-md-4">
                            <label for="serialNumber" class="form-label">Serial Number</label>
                            <input type="text" class="form-control" id="serialNumber" name="serial_number"
                                placeholder="Enter Serial Number" required>
                        </div>
                        <div class="col-md-4">
                            <label for="imeiNumber" class="form-label">IMEI</label>
                            <input type="text" class="form-control" id="imeiNumber" name="imei" placeholder="Enter IMEI"
                                required>
                        </div>
                        <!-- Accessories -->
                        <div class="col-md-4">
                            <label class="form-label d-block">Accessories</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="accessoryHeadset"
                                    name="accessories[]" value="Headset">
                                <label class="form-check-label" for="accessoryHeadset">Headset</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="accessoryPowerBank"
                                    name="accessories[]" value="Power Bank">
                                <label class="form-check-label" for="accessoryPowerBank">Power Bank</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="accessoryCharger"
                                    name="accessories[]" value="Charger">
                                <label class="form-check-label" for="accessoryCharger">Charger</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="accessoryBackpack"
                                    name="accessories[]" value="Backpack">
                                <label class="form-check-label" for="accessoryBackpack">Backpack</label>
                            </div>
                        </div>
                    </div>



                    <!-- Submit -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4 py-2">Add Tablet</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm mb-5">
            <div class="card-body">

                <h5 class="mb-4 text-primary">Registered Tablets</h5>




            </div>
        </div>
    </div>




    <!-- Footer -->
    <footer class="footer">
        &copy; 2025 Senior High School Tablet Management. All Rights Reserved.
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Form submission alert
    document.querySelector('.card form').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Tablet added successfully!');
        this.reset();
    });
    </script>
</body>

</html>