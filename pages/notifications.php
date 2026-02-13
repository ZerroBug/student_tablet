<?php
include_once('../includes/auth_check.php');
include_once('../includes/db_connection.php');

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit;
}

// Bulk actions
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['mark_all'])) {
        $pdo->query("UPDATE notifications SET is_read = 1");
        header("Location: notifications.php");
        exit;
    }

    if (isset($_GET['delete_all'])) {
        $pdo->query("DELETE FROM notifications");
        header("Location: notifications.php");
        exit;
    }

    if (isset($_GET['read_id'])) {
        $id = filter_var($_GET['read_id'], FILTER_VALIDATE_INT);
        if ($id) {
            $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
            $stmt->execute([$id]);
        }
        header("Location: notifications.php");
        exit;
    }
}

$notifications = $pdo->query("SELECT * FROM notifications ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Notifications | Dashboard</title>

    <link rel="icon" href="assets/images/logo.jpg" type="image/jpeg" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />

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

    table.dataTable thead th {
        background-color: #1d3557;
        color: white;
    }

    .btn-sm {
        font-size: 0.8rem;
    }
    </style>
</head>

<body>

    <!-- Sidebar Components -->
    <?php include_once('../includes/mobile_sidebar.php'); ?>
    <?php include_once('../includes/desktop_sidebar.php'); ?>

    <!-- Main Content -->
    <div class="main-content">
        <?php include_once('../includes/topbar.php'); ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-primary mb-0">
                <i class="fa fa-bell me-2"></i>Notifications
            </h4>

            <div class="d-flex gap-2 flex-wrap">
                <a href="notifications.php?mark_all=1" class="btn btn-sm btn-success"
                    onclick="return confirm('Mark all notifications as read?');">
                    <i class="fa fa-check-double me-1"></i>Mark All as Read
                </a>
                <a href="notifications.php?delete_all=1" class="btn btn-sm btn-danger"
                    onclick="return confirm('Delete all notifications? This action cannot be undone.');">
                    <i class="fa fa-trash-alt me-1"></i>Delete All
                </a>
                <a href="add_notification.php" class="btn btn-sm btn-primary">
                    <i class="fa fa-plus me-1"></i>Add Notification
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="notificationsTable" class="table table-striped table-hover align-middle mb-0">
                        <thead class="text-center">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Title</th>
                                <th>Message</th>
                                <th style="width: 100px;">Status</th>
                                <th style="width: 160px;">Created At</th>
                                <th style="width: 140px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($notifications)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No notifications found.</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($notifications as $index => $note): ?>
                            <tr>
                                <td class="text-center"><?= $index + 1 ?></td>
                                <td>
                                    <i
                                        class="fa <?= htmlspecialchars($note['icon']) ?> text-<?= htmlspecialchars($note['type'] ?? 'info') ?> me-2"></i>
                                    <?= htmlspecialchars($note['title']) ?>
                                </td>
                                <td>
                                    <?php if (!empty($note['message'])): ?>
                                    <?= htmlspecialchars($note['message']) ?>
                                    <?php else: ?>
                                    <em class="text-muted">No message</em>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($note['is_read']): ?>
                                    <span class="badge bg-success">Read</span>
                                    <?php else: ?>
                                    <span class="badge bg-warning text-dark">Unread</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?= date('d M Y, H:i', strtotime($note['created_at'])) ?></td>
                                <td class="text-center">
                                    <?php if (!$note['is_read']): ?>
                                    <a href="notifications.php?read_id=<?= $note['id'] ?>"
                                        class="btn btn-sm btn-outline-success" title="Mark as Read">
                                        <i class="fa fa-check"></i> Mark as Read
                                    </a>
                                    <?php else: ?>
                                    <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        &copy; 2025 Senior High School Tablet Management. All Rights Reserved.
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
    $(document).ready(function()


            <
            script >
            $(document).ready(function() {
                $('#notificationsTable').DataTable({
                    paging: true,
                    ordering: true,
                    info: true,
                    lengthMenu: [
                        [10, 25, 50, 100],
                        [10, 25, 50, 100]
                    ],
                    pageLength: 10,
                    order: [
                        [4, 'desc']
                    ] // order by 'Created At' column descending
                });
            });
    </script>

</body>

</html>