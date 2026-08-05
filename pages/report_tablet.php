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
    <title>Report Tablet Issue | Dashboard</title>

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
                        <h5 class="text-primary mb-0">
                            <i class="fa fa-tablet-alt me-2"></i>Report Tablet Issue
                        </h5>
                    </div>

                    <form method="POST" action="process_report.php" autocomplete="off">

                        <div class="row g-3">

                            <!-- Student -->
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label fw-semibold">Student Name</label>
                                <select name="student_id" id="student_select" class="form-select" required>
                                    <option value="" selected disabled>Select Student</option>
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
                                    <option value="" selected disabled>Select Tablet</option>
                                </select>
                            </div>

                            <!-- Class -->
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label fw-semibold">Class</label>
                                <input type="text" id="class_name" class="form-control bg-light" readonly>
                            </div>

                            <!-- Incident Type -->
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-exclamation-circle text-danger me-1"></i>
                                    Incident Type
                                </label>
                                <select name="incident_type" class="form-select" required>
                                    <option value="" selected disabled>Select Incident</option>
                                    <option value="Missing">Missing Tablet</option>
                                    <option value="Damaged">Damaged</option>
                                    <option value="Faulty">Faulty</option>
                                    <option value="Screen Broken">Screen Broken</option>
                                    <option value="Battery Problem">Battery Problem</option>
                                    <option value="Charging Problem">Charging Problem</option>
                                    <option value="Password Reset">Password Reset</option>
                                    <option value="Missing Powerbank">Missing Powerbank</option>
                                    <option value="Seized">Seized</option>
                                    <option value="Returned">Returned</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <!-- Action Taken -->
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-tools text-primary me-1"></i>
                                    Action Taken
                                </label>
                                <select name="action_taken" class="form-select" required>
                                    <option value="" selected disabled>Select Action</option>
                                    <option value="Received">Received from Student</option>
                                    <option value="Returned to Store">Returned to Store</option>
                                    <option value="Sent for Repair">Sent for Repair</option>
                                    <option value="Repaired">Repaired</option>
                                    <option value="Reissued">Reissued</option>
                                    <option value="Replaced">Replaced</option>
                                    <option value="Recovered">Recovered</option>
                                    <option value="Escalated">Escalated</option>
                                    <option value="Under Investigation">Under Investigation</option>
                                    <option value="Student Advised">Student Advised</option>
                                    <option value="No Action Yet">No Action Yet</option>
                                </select>
                            </div>

                            <!-- Current Status -->
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-flag text-success me-1"></i>
                                    Current Status
                                </label>
                                <select name="status" class="form-select" required>
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="Reported">Reported</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Under Repair">Under Repair</option>
                                    <option value="Repaired">Repaired</option>
                                    <option value="Returned">Returned</option>
                                    <option value="Closed">Closed</option>
                                </select>
                            </div>

                            <!-- Remarks -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-comment-dots me-1"></i>
                                    Remarks
                                </label>
                                <textarea name="description" class="form-control" rows="4"
                                    placeholder="Enter any additional information about the incident..."></textarea>
                            </div>

                            <!-- Submit -->
                            <div class="col-12 text-end">
                                <button type="submit" name="submit_report" class="btn btn-primary px-4">
                                    <i class="fa fa-paper-plane me-2"></i>
                                    Submit Report
                                </button>
                            </div>

                        </div>

                    </form>

                </div>
            </div>

            <div class="card shadow-sm mb-5">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-primary mb-0">
                            <i class="fa fa-list me-2"></i>Tablet Incident Reports
                        </h5>
                    </div>

                    <div class="table-responsive">
                        <table id="reportsTable" class="table table-bordered table-hover table-striped align-middle">

                            <thead class="table-dark text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Tablet ID</th>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Incident Type</th>
                                    <th>Action Taken</th>
                                    <th>Status</th>
                                    <th>Reported By</th>
                                    <th width="140">Actions</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php

                $stmt = $pdo->query("
                    SELECT
                        tr.id,
                        tr.report_date,
                        tr.incident_type,
                        tr.action_taken,
                        tr.status,
                        tr.received_by,

                        t.tablet_id,

                        s.full_name,

                        c.class_Name

                    FROM tablet_reports tr

                    INNER JOIN tablet t
                    ON tr.tablet_id=t.id

                    INNER JOIN students s
                    ON tr.student_id=s.id

                    INNER JOIN class c
                    ON tr.class_id=c.id

                    ORDER BY tr.report_date DESC
                ");

                $i=1;

                while($row=$stmt->fetch(PDO::FETCH_ASSOC)):
                ?>

                                <tr>

                                    <td class="text-center"><?= $i++; ?></td>

                                    <td>
                                        <?= date('d M Y',strtotime($row['report_date'])); ?>
                                    </td>

                                    <td>
                                        <strong><?= htmlspecialchars($row['tablet_id']); ?></strong>
                                    </td>

                                    <td><?= htmlspecialchars($row['full_name']); ?></td>

                                    <td><?= htmlspecialchars($row['class_Name']); ?></td>

                                    <td>

                                        <?php

                        switch($row['incident_type']){

                            case "Missing":
                                echo '<span class="badge bg-danger">Missing</span>';
                                break;

                            case "Damaged":
                                echo '<span class="badge bg-warning text-dark">Damaged</span>';
                                break;

                            case "Faulty":
                                echo '<span class="badge bg-secondary">Faulty</span>';
                                break;

                            case "Returned":
                                echo '<span class="badge bg-success">Returned</span>';
                                break;

                            case "Seized":
                                echo '<span class="badge bg-dark">Seized</span>';
                                break;

                            default:
                                echo '<span class="badge bg-info">'.$row['incident_type'].'</span>';
                        }

                        ?>

                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['action_taken']); ?>
                                    </td>

                                    <td>

                                        <?php

                        switch($row['status']){

                            case "Reported":
                                echo '<span class="badge bg-primary">Reported</span>';
                                break;

                            case "Pending":
                                echo '<span class="badge bg-warning text-dark">Pending</span>';
                                break;

                            case "Under Repair":
                                echo '<span class="badge bg-info">Under Repair</span>';
                                break;

                            case "Repaired":
                                echo '<span class="badge bg-success">Repaired</span>';
                                break;

                            case "Closed":
                                echo '<span class="badge bg-dark">Closed</span>';
                                break;

                            default:
                                echo '<span class="badge bg-secondary">'.$row['status'].'</span>';
                        }

                        ?>

                                    </td>

                                    <td><?= htmlspecialchars($row['received_by']); ?></td>

                                    <td class="text-center">

                                        <a href="view_report.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-info">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <a href="edit_report.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <a href="delete_report.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this report?');">
                                            <i class="fa fa-trash"></i>
                                        </a>

                                    </td>

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