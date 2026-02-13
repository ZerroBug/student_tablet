<div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
    <!-- Header -->
    <div class="offcanvas-header p-3" style="background-color: #1d3557;">
        <h5 class="offcanvas-title text-white fw-bold fs-5" id="mobileSidebarLabel">Tablet Management</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <!-- Body -->
    <div class="offcanvas-body p-0">
        <nav class="d-flex flex-column vh-100 px-3 pt-3">
            <!-- Dashboard -->
            <a href="dashboard.php" class="mb-2"><i class="fa fa-home me-2"></i>Dashboard</a>

            <!-- Tablet Management Dropdown -->
            <div class="dropdown mb-2">
                <a class="dropdown-toggle text-decoration-none d-block" href="#" id="tabletDropdownMobile"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-tablet-alt me-2"></i>Tablet Info
                </a>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm ms-3"
                    aria-labelledby="tabletDropdownMobile">
                    <li><a class="dropdown-item text-primary" href="add_tablet.php"><i class="fa fa-plus me-2"></i>Add
                            Tablet</a></li>
                    <li><a class="dropdown-item text-primary" href="assign_tablet.php"><i
                                class="fa fa-user-plus me-2"></i>Assign
                            Tablet</a></li>
                    <li><a class="dropdown-item text-primary" href="return.php"><i class="fa fa-undo me-2"></i>Return
                            Tablet</a></li>
                    <li><a class="dropdown-item text-primary" href="maintenance.php"><i
                                class="fa fa-tools me-2"></i>Maintenance</a>
                    </li>
                </ul>
            </div>

            <!-- School Info Dropdown -->
            <div class="dropdown mb-2">
                <a class="dropdown-toggle d-flex align-items-center" href="#" id="schoolDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-school me-2 "></i> School Info
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

            <!-- Other Links -->
            <a href="repo.php" class="mb-2"><i class="fa fa-chart-bar me-2"></i>Reports</a>
            <a href="settings.php"><i class="fa fa-cog me-2"></i>Settings</a>
        </nav>
    </div>
</div>