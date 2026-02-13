<?php
include_once('../includes/auth_check.php');
include_once ('../includes/db_connection.php');
/* HANDLE STATUS UPDATE */
if (isset($_POST['update_status']) && isset($_POST['tablet_id'], $_POST['status'])) {
    $tablet_id = intval($_POST['tablet_id']);
    $new_status = trim($_POST['status']);

    $valid_statuses = ['Available', 'Under Repair', 'Seized', 'Repaired'];

    if (in_array($new_status, $valid_statuses)) {
        $stmt = $pdo->prepare("UPDATE tablet SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $tablet_id]);
        echo "<script>
                alert('Tablet status updated ✅');
                window.location.href = window.location.href;
              </script>";
        exit;
    } else {
        echo "<script>alert('Invalid status selected ❌');</script>";
    }
}

/* FETCH STUDENTS */
$students = $pdo->query("
    SELECT s.id, s.full_name, s.class_id, c.class_Name 
    FROM students s
    JOIN class c ON s.class_id = c.id
    ORDER BY s.full_name ASC
")->fetchAll(PDO::FETCH_ASSOC);

/* HANDLE FORM SUBMISSION */
if (isset($_POST['submit_return'])) {
    $assignment_id = trim($_POST['tablet_id']); 
    $student_id    = $_POST['student_id'];
    $class_id      = $_POST['class_id'];
    $reason        = $_POST['reason'];
    $action        = $_POST['action_taken'];
    $description   = $_POST['description'];
    $received_by   = $_SESSION['username'] ?? 'Admin';

    $stmt = $pdo->prepare("SELECT tablet_id FROM tablet_assignments WHERE id = ?");
    $stmt->execute([$assignment_id]);
    $assignment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$assignment) {
        echo "<script>alert('Invalid Tablet Assignment ❌');</script>";
    } else {
        $tablet_auto_id = $assignment['tablet_id'];

        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("
                INSERT INTO tablet_returns
                (tablet_id, student_id, class_id, reason, description, action_taken, received_by)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $tablet_auto_id,
                $student_id,
                $class_id,
                $reason,
                $description,
                $action,
                $received_by
            ]);

            $status = ($action === 'Seized') ? 'Seized' : (in_array($reason, ['Damaged','Faulty']) ? 'Under Repair' : 'Available');

            $stmt = $pdo->prepare("UPDATE tablet SET status = ?, is_assigned = 0 WHERE id = ?");
            $stmt->execute([$status, $tablet_auto_id]);

            $stmt = $pdo->prepare("DELETE FROM tablet_assignments WHERE id = ?");
            $stmt->execute([$assignment_id]);

            $pdo->commit();

            echo "<script>
                alert('Tablet processed successfully ✅');
                window.location.href = window.location.href;
            </script>";

        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<script>alert('Operation failed ❌');</script>";
        }
    }
}

/* FETCH ASSIGNED TABLETS */
$assignments = $pdo->query("
    SELECT ta.id AS assignment_id, ta.student_id, t.tablet_id AS human_tablet_id
    FROM tablet_assignments ta
    JOIN tablet t ON ta.tablet_id = t.id
    WHERE t.is_assigned = 1
")->fetchAll(PDO::FETCH_ASSOC);

$tabletsByStudent = [];
foreach ($assignments as $a) {
    $tabletsByStudent[$a['student_id']][] = [
        'assignment_id' => $a['assignment_id'],
        'tablet_id'     => $a['human_tablet_id']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return / Seize Tablet | Dashboard</title>

    <link rel="icon" href="assets/images/logo.jpg" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <style>
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
        background: #1d3557;
        padding-top: 1rem;
        z-index: 1040;
    }

    .sidebar a {
        color: white;
        display: block;
        padding: 12px 20px;
        border-radius: 5px;
        margin-bottom: 4px;
        text-decoration: none;
        transition: 0.3s;
    }

    .sidebar a:hover,
    .sidebar a.active {
        background: #457b9d;
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
        background: #1d3557;
        color: white;
        text-align: center;
        padding: 12px 0;
        font-size: 0.9rem;
    }

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

    .table-responsive {
        margin-top: 15px;
    }
    </style>
</head>

<body>
    <?php include_once('../includes/mobile_sidebar.php'); ?>
    <?php include_once('../includes/desktop_sidebar.php'); ?>
    <div class="main-content">
        <?php include_once('../includes/topbar.php'); ?>

        <!-- 🔹 WRAPPER -->
        <div class="container-fluid p-0">

            <!-- RETURN / SEIZE FORM -->
            <div class="card shadow-sm mb-5">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                        <h5 class="text-primary mb-0"><i class="fa fa-tablet-alt me-2"></i>Return / Seize Tablet</h5>
                    </div>

                    <form method="POST" action="process_return.php" autocomplete="off">

                        <!-- Optional CSRF Token (Recommended) -->
                        <!-- <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>"> -->

                        <div class="row g-3">

                            <!-- Student -->
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label fw-semibold">Student Name</label>
                                <select name="student_id" id="student_select" class="form-select" required>
                                    <option value="" disabled selected>Select Student</option>
                                    <?php foreach($students as $student): ?>
                                    <option value="<?= $student['id']; ?>" data-class-id="<?= $student['class_id']; ?>"
                                        data-class-name="<?= htmlspecialchars($student['class_Name']); ?>"
                                        data-tablets='<?= json_encode($tabletsByStudent[$student['id']] ?? []); ?>'>
                                        <?= htmlspecialchars($student['full_name']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="class_id" id="class_id">
                            </div>

                            <!-- Tablet -->
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label fw-semibold">Tablet ID</label>
                                <select name="tablet_id" id="tablet_id" class="form-select" required>
                                    <option value="" disabled selected>Select Tablet</option>
                                </select>
                            </div>

                            <!-- Class -->
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label fw-semibold">Class</label>
                                <input type="text" id="class_name" class="form-control bg-light" readonly>
                            </div>

                            <!-- Action -->
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label fw-semibold">Action Taken</label>
                                <select name="action_taken" class="form-select" required>
                                    <option value="" disabled selected>Select Action</option>
                                    <option value="Returned">Returned</option>

                                </select>
                            </div>

                            <!-- Reason -->
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label fw-semibold">Reason</label>
                                <select name="reason" class="form-select" required>
                                    <option value="" disabled selected>Select Reason</option>
                                    <option value="damaged">Damaged</option>
                                    <option value="faulty">Faulty</option>
                                    <option value="lost_accessories">Lost Accessories</option>
                                    <option value="password_reset">Password Reset</option>
                                    <option value="Seized">Seized</option>

                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <!-- Remarks -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Remarks</label>
                                <textarea name="description" class="form-control" rows="3"
                                    placeholder="Optional remarks..."></textarea>
                            </div>

                            <!-- Submit -->
                            <div class="col-12 text-end mt-3">
                                <button type="submit" name="submit_return" class="btn btn-danger px-4">
                                    <i class="fa fa-save me-2"></i>Process Tablet
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>

            <!-- RETURNED TABLETS TABLE -->
            <div class="card shadow-sm mb-5">
                <div class="card-body">
                    <h5 class="text-primary mb-4">Returned / Seized Tablets</h5>
                    <div class="table-responsive">
                        <table id="returnsTable" class="table table-hover table-bordered table-striped">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Tablet ID</th>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Reason</th>
                                    <th>Action</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                        $stmt = $pdo->query("
                         SELECT 
    t.id AS tablet_internal_id,
    t.tablet_id AS tablet_code,
    t.status,
    s.full_name,
    c.class_Name,
    tr.reason,
    tr.action_taken,
    tr.return_date
FROM tablet_returns tr
JOIN tablet t ON tr.tablet_id = t.id
JOIN students s ON tr.student_id = s.id
JOIN class c ON tr.class_id = c.id
ORDER BY tr.return_date DESC


                        ");
                        $i = 1;
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                        ?>
                                <tr class="text-center">
                                    <td><?= $i++; ?></td>
                                    <td><?= htmlspecialchars($row['tablet_code']); ?></td>
                                    <td><?= htmlspecialchars($row['full_name']); ?></td>
                                    <td><?= htmlspecialchars($row['class_Name']); ?></td>
                                    <td><?= htmlspecialchars($row['reason']); ?></td>
                                    <td>
                                        <span
                                            class="badge bg-<?= $row['action_taken']=='Seized'?'danger':'success'; ?>">
                                            <?= $row['action_taken']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" style="min-width:150px;">
                                            <input type="hidden" name="tablet_id"
                                                value="<?= $row['tablet_internal_id']; ?>">

                                            <select name="status" class="form-select form-select-sm"
                                                onchange="confirmStatusChange(this.form)">
                                                <?php
    $statuses = ['Available', 'Under Repair', 'Seized', 'Repaired'];
    foreach ($statuses as $status_option):
    ?>
                                                <option value="<?= $status_option; ?>"
                                                    <?= $row['status'] === $status_option ? 'selected' : ''; ?>>
                                                    <?= $status_option; ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>


                                            <input type="hidden" name="update_status" value="1">
                                        </form>
                                    </td>

                                    <td><?= date('d M Y', strtotime($row['return_date'])); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div> <!-- END WRAPPER -->
    </div>

    <footer class="footer">&copy; <?= date('Y'); ?> Tablet Management System</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#returnsTable').DataTable({
            paging: true,
            searching: true,
            info: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            pageLength: 10
        });

        $('#student_select').change(function() {
            let selected = $(this).find('option:selected');
            $('#class_name').val(selected.data('class-name'));
            $('#class_id').val(selected.data('class-id'));
            let tablets = selected.data('tablets');
            let $tabletSelect = $('#tablet_id');
            $tabletSelect.empty();
            if (tablets.length > 0) {
                $tabletSelect.append('<option disabled selected>Select Tablet</option>');
                tablets.forEach(t => {
                    $tabletSelect.append('<option value="' + t.assignment_id + '">' + t
                        .tablet_id + '</option>');
                });
            } else {
                $tabletSelect.append('<option disabled>No tablets assigned</option>');
            }
        });
    });
    </script>

    <!-- Confirmation before changing tablet status -->
    <script>
    function confirmStatusChange(form) {
        let confirmChange = confirm("Do you want to make changes to this tablet status?");
        if (confirmChange) {
            form.submit();
        } else {
            // Reload page to reset dropdown to previous value
            window.location.reload();
        }
    }
    </script>

</body>

</html>