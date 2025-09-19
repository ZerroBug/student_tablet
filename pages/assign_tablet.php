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
        <div class="card shadow-sm  p-4 mb-5">
            <form>
                <!-- Tablet Details -->
                <h5 class="text-primary p-3">Assign Tablet</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="tabletId" class="form-label">Tablet ID</label>
                        <select class="form-select" id="tabletId">
                            <option selected disabled>Select ID</option>
                            <option>345678900</option>
                            <option>890976544</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="serialNumber" class="form-label">S/N</label>
                        <select class="form-select" id="serialNumber">
                            <option selected disabled>Select Serial Number</option>
                            <option>SN345678900</option>
                            <option>SN890976544</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="imei" class="form-label">IMEI</label>
                        <select class="form-select" id="imei">
                            <option selected disabled>Select IMEI</option>
                            <option>SN345678900</option>
                            <option>SN890976544</option>
                        </select>
                    </div>
                </div>

                <!-- Student Info -->
                <h5 class="text-success mt-4">Student Information</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="studentName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="studentName" placeholder="Enter student's full name"
                            required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="studentIndex" class="form-label">BECE Index Number</label>
                        <input type="text" class="form-control" id="studentIndex"
                            placeholder="Enter student's index number" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="level" class="form-label">Residence status</label>
                        <select class="form-select" id="level">
                            <option selected disabled>Select Status</option>
                            <option>Boarding</option>
                            <option>Day</option>

                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="level" class="form-label">Level</label>
                        <select class="form-select" id="level">
                            <option selected disabled>Select Level</option>
                            <option>SHS_1</option>
                            <option>SHS_2</option>
                            <option>SHS_3</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">

                        <label for="imei" class="form-label">Class</label>
                        <select class="form-select" id="imei">
                            <option selected disabled>Select Class</option>
                            <option>SN345678900</option>
                            <option>SN890976544</option>
                        </select>

                    </div>
                    <div class="col-md-4 mb-3">

                        <label for="imei" class="form-label">House</label>
                        <select class="form-select" id="imei">
                            <option selected disabled>Select House</option>
                            <option>SN345678900</option>
                            <option>SN890976544</option>
                        </select>

                    </div>
                </div>

                <!-- Guardian Info -->
                <h5 class="text-warning mt-4 ">Guardian Information</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="guardianName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="guardianName"
                            placeholder="Enter guardian's full name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="ghanaCard" class="form-label">Ghana Card Number</label>
                        <input type="text" class="form-control" id="ghanaCard" placeholder="Enter Ghana card number"
                            required>
                    </div>
                </div>

                <!-- Assignment Details -->
                <h5 class="text-info mt-4">Assignment Details</h5>
                <div class="row">
                    <div class="col-md-6">
                        <label for="dateIssued" class="form-label">Date Issued</label>
                        <input type="date" class="form-control" id="dateIssued">
                    </div>
                </div>

                <!-- Agreement -->
                <div class="form-check mt-3 mb-4">
                    <input class="form-check-input" type="checkbox" id="agreement">
                    <label class="form-check-label" for="agreement">
                        I acknowledge receipt of the tablet and agree to the school’s device policy.
                    </label>
                </div>

                <!-- Submit -->
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Assign Tablet</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        &copy; 2025 Senior High School Tablet Management. All Rights Reserved.
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
    <script>
    document.querySelector('.card form').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Tablet assigned successfully!');
        this.reset();
    });
    </script>
</body>

</html>