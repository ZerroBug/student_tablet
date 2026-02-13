<?php
// Include your DB connection
include_once '../includes/db_connection.php';
// Fetch latest 5 notifications ordered by newest first
$notifStmt = $pdo->query("SELECT * FROM notifications ORDER BY created_at DESC LIMIT 5");
$notifications = $notifStmt->fetchAll(PDO::FETCH_ASSOC);

// Count unread notifications
$unreadCountStmt = $pdo->query("SELECT COUNT(*) FROM notifications WHERE is_read = 0");
$unreadCount = $unreadCountStmt->fetchColumn();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <!-- Left: Hamburger & Title -->
    <div class="d-flex align-items-center">
        <!-- Hamburger -->
        <button class="btn btn-outline-primary d-md-none me-3" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
            <i class="fa fa-bars"></i>
        </button>
        <h2 class="mb-0"><i class="fa fa-home me-2"></i>Dashboard</h2>
    </div>

    <!-- Right: Notifications & User -->
    <div class="d-flex align-items-center">
        <!-- Notifications Dropdown -->
        <div class="dropdown me-3">
            <a class="position-relative text-dark" href="#" role="button" id="notificationDropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell fs-5"></i>
                <?php if ($unreadCount > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?= htmlspecialchars($unreadCount) ?>
                    <span class="visually-hidden">unread notifications</span>
                </span>
                <?php endif; ?>
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notificationDropdown"
                style="min-width: 320px; max-height: 400px; overflow-y: auto;">
                <li class="dropdown-header fw-semibold">Notifications</li>
                <li>
                    <hr class="dropdown-divider">
                </li>

                <?php if (empty($notifications)): ?>
                <li class="dropdown-item text-center small text-muted">No notifications</li>
                <?php else: ?>
                <?php foreach ($notifications as $notif): ?>
                <li>
                    <a class="dropdown-item d-flex align-items-start" href="notifications.php">
                        <i class="fa <?= htmlspecialchars($notif['icon'] ?? 'fa-bell') ?> me-2 text-primary fs-5"></i>
                        <div>
                            <div><?= htmlspecialchars($notif['title'] ?? 'Notification') ?></div>
                            <?php if (!empty($notif['message'])): ?>
                            <small class="text-muted"><?= htmlspecialchars($notif['message']) ?></small>
                            <?php endif; ?>
                        </div>
                    </a>
                </li>
                <?php endforeach; ?>
                <?php endif; ?>

                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-center small" href="notifications.php">View all notifications</a></li>
            </ul>
        </div>

        <!-- User Dropdown -->
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
                id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-user-circle fs-4 me-2"></i>
                <span class="d-none d-md-inline fw-semibold">Admin</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a class="dropdown-item text-danger" href="../pages/PHP/logout.code.php">
                        <i class="fa fa-sign-out-alt me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>