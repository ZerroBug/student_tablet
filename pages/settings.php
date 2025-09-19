<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | Dashboard</title>
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="assets/images/logo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f8f9fa;
    }

    /* Sidebar */
    .sidebar {
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        width: 220px;
        background-color: #1d3557;
        padding-top: 1rem;
        transition: background 0.3s ease;
    }

    .sidebar h4 {
        font-size: 1.2rem;
        font-weight: 600;
    }

    .sidebar a {
        color: white;
        text-decoration: none;
        display: block;
        padding: 10px 20px;
        margin-bottom: 2px;
        border-radius: 5px;
        font-size: 0.95rem;
    }

    .sidebar a.active,
    .sidebar a:hover {
        background-color: #457b9d;
    }

    /* Main content */
    .main-content {
        margin-left: 230px;
        padding: 20px;
    }

    /* Card */
    .form-card {
        max-width: 950px;
        margin: 0 auto;
        border-radius: 10px;
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
        padding: 10px 0;
        z-index: 1030;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .footer {
            left: 0;
            width: 100%;
        }
    }

    .footer a {
        color: white;
        text-decoration: none;
    }

    .footer a:hover {
        text-decoration: underline;
    }

    /* Form */
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

    /* Custom Toast (mind-blowing style) */
    .custom-toast {
        position: fixed;
        top: 1rem;
        right: 1rem;
        min-width: 260px;
        max-width: 350px;
        padding: 15px 20px;
        border-radius: 12px;
        background: linear-gradient(135deg, #1d3557, #457b9d);
        color: #fff;
        font-weight: 500;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideIn 0.5s ease, fadeOut 0.5s ease 3s forwards;
        z-index: 3000;
    }

    .custom-toast i {
        font-size: 1.3rem;
    }

    @keyframes slideIn {
        from {
            transform: translateX(120%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        to {
            transform: translateX(120%);
            opacity: 0;
        }
    }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <nav class="sidebar d-flex flex-column">
        <h4 class="text-white px-3 mb-4 brand">📱 Tablet Admin</h4>
        <a href="dashboard.php"><i class="fa fa-home me-2"></i> Dashboard</a>
        <a href="tablets.php"><i class="fa fa-tablet-alt me-2"></i> Tablets</a>
        <a href="assign.php"><i class="fa fa-user-plus me-2"></i> Assign Tablet</a>
        <a href="return.php"><i class="fa fa-undo me-2"></i> Return Tablet</a>
        <a href="maintenance.php"><i class="fa fa-tools me-2"></i> Maintenance</a>
        <a href="reports.php"><i class="fa fa-chart-bar me-2"></i> Reports</a>
        <a href="settings.php" class="active"><i class="fa fa-cog me-2"></i> Settings</a>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0"><i class="fa fa-cog me-2"></i>Settings</h2>
            <div>
                <i class="fa fa-bell me-3 fs-5"></i>
                <i class="fa fa-user-circle fs-4"></i>
            </div>
        </div>

        <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
            <div class="topbar d-flex justify-content-between align-items-center p-3">
                <h5 class="m-0">Settings</h5>
            </div>

            <div class="row g-3 my-3">
                <div class="col-lg-6">
                    <div class="card shadow-sm p-3">
                        <div class="fw-semibold mb-2">School Branding</div>
                        <form class="row g-3" onsubmit="event.preventDefault();applyBranding()">
                            <div class="col-12">
                                <label class="form-label">School Name</label>
                                <input id="schoolName" class="form-control" value="Okomfo Anokye SHS">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Primary Color</label>
                                <input id="primaryColor" type="color" class="form-control form-control-color"
                                    value="#1d3557">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Logo</label>
                                <input type="file" class="form-control">
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card shadow-sm p-3">
                        <div class="fw-semibold mb-2">User Management</div>
                        <div class="input-group mb-2">
                            <input class="form-control" placeholder="Enter email">
                            <button class="btn btn-outline-primary"
                                onclick="showToast('Invite sent successfully','info')">
                                <i class="fa fa-paper-plane"></i> Invite
                            </button>
                        </div>
                        <ul class="list-group small">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>admin@school.edu (Admin)</span>
                                <button class="btn btn-sm btn-outline-danger"
                                    onclick="showToast('User removed successfully','error')">Remove</button>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>teacher@school.edu (Teacher)</span>
                                <button class="btn btn-sm btn-outline-danger"
                                    onclick="showToast('User removed successfully','error')">Remove</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="footer">
        &copy; 2025 Okomfo Anokye Senior High School Tablet Management. All Rights Reserved.
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    /* Custom Toast System */
    function showToast(message, type = "success") {
        const icons = {
            success: "fa-circle-check",
            error: "fa-circle-xmark",
            warning: "fa-triangle-exclamation",
            info: "fa-circle-info"
        };

        const colors = {
            success: "linear-gradient(135deg, #06d6a0, #118ab2)",
            error: "linear-gradient(135deg, #ef476f, #d62828)",
            warning: "linear-gradient(135deg, #ffd166, #f77f00)",
            info: "linear-gradient(135deg, #219ebc, #023047)"
        };

        const toast = document.createElement("div");
        toast.className = "custom-toast";
        toast.style.background = colors[type] || colors.success;
        toast.innerHTML = `
    <i class="fa ${icons[type] || icons.success}"></i>
    <span>${message}</span>
  `;

        document.body.appendChild(toast);

        // Remove after animation ends
        setTimeout(() => {
            toast.remove();
        }, 4000);
    }

    /* Branding Apply */
    function applyBranding() {
        const schoolName = document.getElementById("schoolName").value;
        const color = document.getElementById("primaryColor").value;

        // Update sidebar + footer colors
        document.querySelector(".sidebar").style.background = color;
        document.querySelector(".footer").style.background = color;

        // Update brand text
        document.querySelector(".brand").textContent = `📱 ${schoolName} Admin`;

        showToast("Branding saved successfully ✅", "success");
    }
    </script>
</body>

</html>