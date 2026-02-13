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

    .preview-img {
        max-width: 150px;
        margin-top: 10px;
        display: block;
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

        <div class="container mt-4">
            <div class="card shadow-sm p-4">
                <h4 class="mb-3 text-primary"><i class="fa fa-home me-2"></i>Add Admin</h4>

                <form id="addAdminForm" action="../pages/php/add_admin.code.php" method="POST"
                    enctype="multipart/form-data">
                    <div class="row">
                        <!-- Full Name -->
                        <div class="col-md-6 mb-3">
                            <label for="full_Name" class="form-label fw-semibold">Full Name</label>
                            <input type="text" class="form-control" id="full_Name" name="full_Name"
                                placeholder="Enter Full Name" required>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email"
                                required>
                        </div>

                        <!-- Contact -->
                        <div class="col-md-6 mb-3">
                            <label for="contact" class="form-label fw-semibold">Contact</label>
                            <input type="text" class="form-control" id="contact" name="contact"
                                placeholder="Enter Contact" required>
                        </div>

                        <!-- Password -->
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Enter Password" required>
                        </div>

                        <!-- Profile Picture -->
                        <div class="col-md-6 mb-3">
                            <label for="profilePicture" class="form-label fw-semibold">Profile Picture</label>
                            <input type="file" class="form-control" id="profilePicture" name="profile_picture"
                                accept="image/*">
                            <img id="previewImage" class="preview-img" src="#" alt="Preview" style="display: none;" />
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4" name="addAdmin_Btn">
                            <i class="fa fa-save me-2"></i>Save
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            &copy; 2025 Senior High School Tablet Management. All Rights Reserved.
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Profile Picture Preview Script -->
    <script>
    document.getElementById('profilePicture').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('previewImage');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
    </script>

</body>

</html>