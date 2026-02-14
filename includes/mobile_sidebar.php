<!-- MOBILE SIDEBAR -->
<div class="offcanvas offcanvas-start d-md-none mobile-sidebar" tabindex="-1" id="mobileSidebar">

    <!-- Header -->
    <div class="offcanvas-header mobile-header">
        <div class="d-flex align-items-center gap-2">
            <div class="logo-circle">
                <i class="fa fa-tablet-alt"></i>
            </div>
            <h5 class="offcanvas-title mb-0">Tablet Management</h5>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>

    <!-- Body -->
    <div class="offcanvas-body">

        <nav class="mobile-nav">

            <a href="dashboard.php" class="nav-link active">
                <i class="fa fa-home"></i>
                <span>Dashboard</span>
            </a>

            <!-- Tablet Dropdown -->
            <div class="dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    <i class="fa fa-tablet-alt"></i>
                    <span>Tablet Info</span>
                </a>

                <ul class="dropdown-menu shadow">
                    <li><a class="dropdown-item" href="add_tablet.php">
                            <i class="fa fa-plus me-2"></i>Add Tablet</a></li>
                    <li><a class="dropdown-item" href="assign_tablet.php">
                            <i class="fa fa-user-plus me-2"></i>Assign Tablet</a></li>
                    <li><a class="dropdown-item" href="return.php">
                            <i class="fa fa-undo me-2"></i>Return Tablet</a></li>
                    <li><a class="dropdown-item" href="maintenance.php">
                            <i class="fa fa-tools me-2"></i>Maintenance</a></li>
                </ul>
            </div>

            <!-- School Dropdown -->
            <div class="dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    <i class="fa fa-school"></i>
                    <span>School Info</span>
                </a>

                <ul class="dropdown-menu shadow">
                    <li><a class="dropdown-item" href="add_class.php">
                            <i class="fa fa-plus me-2"></i>Add Class</a></li>
                    <li><a class="dropdown-item" href="add_house.php">
                            <i class="fa fa-home me-2"></i>Add House</a></li>
                    <li><a class="dropdown-item" href="add_admin.php">
                            <i class="fa fa-user me-2"></i>Add Admin</a></li>
                </ul>
            </div>

            <a href="repo.php" class="nav-link">
                <i class="fa fa-chart-bar"></i>
                <span>Reports</span>
            </a>

            <a href="settings.php" class="nav-link">
                <i class="fa fa-cog"></i>
                <span>Settings</span>
            </a>

        </nav>
    </div>
</div>

<style>
/* ======================================
   PROFESSIONAL MOBILE SIDEBAR DESIGN
====================================== */

.mobile-sidebar {
    width: 280px;
    background: linear-gradient(180deg, #1d3557, #243b55);
    color: #fff;
    border: none;
}

/* Header */
.mobile-header {
    padding: 18px;
    background: linear-gradient(90deg, #1d3557, #27496d);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.mobile-header .offcanvas-title {
    font-size: 1rem;
    font-weight: 600;
    letter-spacing: 0.5px;
}

/* Logo Circle */
.logo-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

/* Navigation Layout */
.mobile-nav {
    display: flex;
    flex-direction: column;
    gap: 6px;
    padding: 15px;
}

/* Nav Links */
.mobile-nav .nav-link {
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    padding: 12px 14px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    position: relative;
}

/* Icon styling */
.mobile-nav i {
    width: 20px;
    text-align: center;
    font-size: 14px;
}

/* Hover animation */
.mobile-nav .nav-link:hover {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(6px);
    transform: translateX(6px);
    color: #ffffff;
}

/* Active link */
.mobile-nav .nav-link.active {
    background: rgba(255, 255, 255, 0.18);
    font-weight: 500;
}

/* Dropdown Menu */
.dropdown-menu {
    border-radius: 12px;
    padding: 8px;
    border: none;
}

.dropdown-item {
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 0.9rem;
    transition: all 0.25s ease;
}

.dropdown-item:hover {
    background: #f1f5f9;
    transform: translateX(4px);
}

/* Smooth slide animation */
.offcanvas-start {
    transition: transform 0.35s ease-in-out;
}

/* Scrollable content */
.offcanvas-body {
    overflow-y: auto;
}

/* Responsive width for small phones */
@media (max-width: 576px) {
    .mobile-sidebar {
        width: 85%;
    }
}
</style>