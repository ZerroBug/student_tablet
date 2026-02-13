<?php
include_once('../includes/auth_check.php');
include_once '../includes/db_connection.php';

if (isset($_POST['update_tablet'])) {

    $stmt = $pdo->prepare("
        UPDATE tablet 
        SET serial_No = ?, imei_No = ?, accessories = ?
        WHERE tablet_id = ?
    ");

    if ($stmt->execute([
        $_POST['serial_No'],
        $_POST['imei_No'],
        $_POST['accessories'],
        $_POST['tablet_id']
    ])) {

        echo "
        <script>
            alert('Tablet details updated successfully ✅');
            window.location.href = window.location.href;
        </script>
        ";

    } else {

        echo "
        <script>
            alert('Failed to update tablet ❌');
            window.location.href = window.location.href;
        </script>
        ";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Tablet | Dashboard</title>

    <link rel="icon" href="assets/images/logo.jpg" type="image/jpeg">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <style>
    /* 🔒 UNCHANGED CSS — EXACTLY AS YOU SENT */
    body {
        font-family: "Poppins", sans-serif;
        background-color: #f8f9fa;
        overflow-x: hidden;
    }

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

    .main-content {
        margin-left: 230px;
        padding: 30px;
        transition: margin-left 0.3s ease;
    }

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

    .card {
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }

    .card-body {
        padding: 30px;
    }

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

    <?php include_once('../includes/mobile_sidebar.php'); ?>
    <?php include_once('../includes/desktop_sidebar.php'); ?>

    <div class="main-content">
        <?php include_once('../includes/topbar.php'); ?>

        <!-- 🔒 EVERYTHING ABOVE UNCHANGED -->
        <!-- Form Card -->
        <div class="card shadow-sm mb-5">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                    <div class="mb-2">
                        <h5 class="text-primary mb-0">Add New Tablet</h5>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <!-- Import Form -->
                        <form class="d-flex align-items-center gap-2" action="../pages/php/import_tablet.code.php"
                            method="post" enctype="multipart/form-data"> <input type="file" class="form-control"
                                id="formFile" name="file" required style="max-width: 250px;" /> <button type="submit"
                                name='import' class="btn btn-success btn-md"> <i
                                    class="fa fa-file-excel me-2"></i>Upload </button> </form>
                        <!-- Download Template Button --> <a href="../assets/templates/tablet-template.csv" download
                            class="btn btn-outline-primary btn-md"> <i class="fa fa-download me-2"></i>Download Template
                        </a>
                    </div>
                </div>
                <form action="../pages/php/add_tablet.code.php" method="POST">
                    <!-- Tablet ID, Serial Number, IMEI -->
                    <div class="row mb-4 g-3">
                        <!-- Academic Year -->
                        <div class="col-md-4"> <label for="academicYear" class="form-label fw-semibold">Academic
                                Year</label> <input type="text" class="form-control" id="academicYear"
                                name="academic_year" placeholder="e.g., 2024/2025" required> </div>
                        <!-- Academic Level -->
                        <div class="col-md-4"> <label for="level" class="form-label fw-semibold">Academic Level</label>
                            <select class="form-select" name="level" id="level" required>
                                <option selected disabled>Select Level</option>
                                <option value="SHS_1">SHS 1</option>
                                <option value="SHS_2">SHS 2</option>
                                <option value="SHS_3">SHS 3</option>
                            </select>
                        </div> <!-- Tablet ID -->
                        <div class="col-md-4"> <label for="tabletId" class="form-label">Tablet ID</label> <input
                                type="text" class="form-control" id="tabletId" name="tablet_id"
                                placeholder="Enter Tablet ID" required> </div> <!-- Serial Number -->
                        <div class="col-md-4"> <label for="serialNumber" class="form-label">Serial Number</label> <input
                                type="text" class="form-control" id="serialNumber" name="serial_No"
                                placeholder="Enter Serial Number" required> </div> <!-- IMEI -->
                        <div class="col-md-4"> <label for="imeiNumber" class="form-label">IMEI</label> <input
                                type="text" class="form-control" id="imeiNumber" name="imei_No" placeholder="Enter IMEI"
                                required> </div> <!-- Accessories -->
                        <div class="col-md-4"> <label class="form-label d-block">Accessories</label>
                            <div class="form-check form-check-inline"> <input class="form-check-input" type="checkbox"
                                    id="accessoryHeadset" name="accessories[]" value="Headset"> <label
                                    class="form-check-label" for="accessoryHeadset">Headset</label> </div>
                            <div class="form-check form-check-inline"> <input class="form-check-input" type="checkbox"
                                    id="accessoryPowerBank" name="accessories[]" value="Power Bank"> <label
                                    class="form-check-label" for="accessoryPowerBank">Power Bank</label> </div>
                            <div class="form-check form-check-inline"> <input class="form-check-input" type="checkbox"
                                    id="accessoryCharger" name="accessories[]" value="Charger"> <label
                                    class="form-check-label" for="accessoryCharger">Charger</label> </div>
                            <div class="form-check form-check-inline"> <input class="form-check-input" type="checkbox"
                                    id="accessoryBackpack" name="accessories[]" value="Backpack"> <label
                                    class="form-check-label" for="accessoryBackpack">Backpack</label> </div>
                        </div>
                    </div> <!-- Submit -->
                    <div class="text-end"> <button type="submit" class="btn btn-primary px-4 py-2" name='addTablet_Btn'>
                            <i class="fa fa-save me-2"></i>Add Tablet</button> </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm mb-5">
            <div class="card-body">
                <h5 class="mb-4 text-primary">Registered Tablets</h5>
                <div id="rowCount"></div>

                <div class="table-responsive">
                    <table id="dataTable" class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr class="bg-dark text-light text-center">
                                <th>#</th>
                                <th>Tablet ID</th>
                                <th>Serial No</th>
                                <th>IMEI No</th>
                                <th>Accessories</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody id="tableBody">
                            <?php
                $stmt = $pdo->prepare("SELECT * FROM tablet ORDER BY tablet_id ASC");
                $stmt->execute();
                $i = 1;
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                ?>
                            <tr class="text-center">
                                <td><?= $i++; ?></td>
                                <td><?= htmlspecialchars($row['tablet_id']); ?></td>
                                <td><?= htmlspecialchars($row['serial_No']); ?></td>
                                <td><?= htmlspecialchars($row['imei_No']); ?></td>
                                <td><?= htmlspecialchars($row['accessories']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary editBtn" data-id="<?= $row['tablet_id']; ?>"
                                        data-serial="<?= $row['serial_No']; ?>" data-imei="<?= $row['imei_No']; ?>"
                                        data-accessories="<?= $row['accessories']; ?>">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                </td>
                            </tr>

                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        &copy; 2025 Senior High School Tablet Management. All Rights Reserved.
    </footer>


    <!-- edit modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Tablet</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="tablet_id" id="editTabletId">

                        <div class="mb-3">
                            <label class="form-label">Serial No</label>
                            <input type="text" name="serial_No" id="editSerial" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">IMEI No</label>
                            <input type="text" name="imei_No" id="editIMEI" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Accessories</label>
                            <input type="text" name="accessories" id="editAccessories" class="form-control">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" name="update_tablet" class="btn btn-success">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ✅ ONLY FIX: SINGLE, CLEAN DataTables INIT -->
    <script>
    $(document).ready(function() {

        var table = $('#dataTable').DataTable({
            paging: true,
            ordering: true,
            info: true,
            searching: true, // ✅ ENABLE SEARCH
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            pageLength: 10
        });

        $('#rowCount').text(table.rows().count() + " total tablets");
    });
    </script>
    <script>
    $(document).on('click', '.editBtn', function() {
        $('#editTabletId').val($(this).data('id'));
        $('#editSerial').val($(this).data('serial'));
        $('#editIMEI').val($(this).data('imei'));
        $('#editAccessories').val($(this).data('accessories'));

        $('#editModal').modal('show');
    });
    </script>





</body>

</html>