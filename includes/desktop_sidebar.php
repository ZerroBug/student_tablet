<nav class="sidebar d-none d-md-flex flex-column" style='z-index:2000'>
    <div class='d-flex flex-column justify-content-center align-items-center mb-3'>
        <img src="../assets/images/logo.png" alt="Logo" style='width:100px'>
        <h4 class="px-4 mb-2 m-2">Admin</h4>
    </div>

    <a href="dashboard.php" class="active">
        <i class="fa fa-home me-2 mx-2"></i> Dashboard
    </a>

    <!-- Tablet Management Dropdown -->
    <div class="dropdown">
        <a class="dropdown-toggle d-flex align-items-center mx-2" href="#" id="tabletDropdown" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fa fa-tablet-alt me-2 mx-2  "></i> Tablet Info
        </a>
        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm ms-3" aria-labelledby="tabletDropdown">
            <li><a class="dropdown-item text-primary" href="add_tablet.php"><i class="fa fa-plus me-2"></i> Add
                    Tablet</a></li>
            <li><a class="dropdown-item text-primary" href="assign_tablet.php"><i class="fa fa-user-plus me-2"></i>
                    Assign Tablet</a>
            </li>
            <li><a class="dropdown-item text-primary" href="return_tablet.php"><i class="fa fa-undo me-2"></i> Return
                    Tablet</a></li>
            <li><a class="dropdown-item text-primary" href="#"><i class="fa fa-tools me-2"></i> Maintenance</a></li>
        </ul>
    </div>
    <!-- School Info Dropdown -->
    <div class="dropdown">
        <a class="dropdown-toggle d-flex align-items-center mx-2" href="#" id="schoolDropdown" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fa fa-school me-2 mx-2"></i> School Info
        </a>
        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm ms-3" aria-labelledby="schoolDropdown">
            <li>
                <a class="dropdown-item text-primary" href="add_class.php">
                    <i class="fa fa-plus me-2"></i> Add Class
                </a>
            </li>
            <li>
                <a class="dropdown-item text-primary" href="add_house.php">
                    <i class="fa fa-home me-2"></i> Add House
                </a>
            </li>
            <li>
                <a class="dropdown-item text-primary" href="add_admin.php">
                    <i class="fa fa-home me-2"></i> Add Admin
                </a>
            </li>
        </ul>
    </div>

    <a href="#"><i class="fa fa-chart-bar me-2 mx-2"></i> Reports</a>
    <a href="#"><i class="fa fa-cog me-2 mx-2"></i> Settings</a>
</nav>