<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login</title>

    <!-- Favicon -->
    <link rel="icon" href="./assets/images/logo.jpg" type="image/jpeg" />

    <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="assets/vendors/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    /* Typography & global styles */
    body,
    html {
        font-family: "Poppins", sans-serif;
        height: 100%;
        background: linear-gradient(135deg, #f0f4f8, #e8ecf1);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }

    .login-card {
        display: flex;
        flex-wrap: wrap;
        max-width: 1020px;
        width: 100%;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        background: #fff;
        overflow: hidden;
    }

    .left-panel {
        flex: 1;
        min-width: 520px;
        background: url("./assets/images/tab.jpg") center/cover no-repeat;
        position: relative;
    }

    .overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.35);
    }

    .left-panel-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: #fff;
    }

    .left-panel-content img {
        width: 95px;
        height: 95px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #fff;
        margin-bottom: 1rem;
    }

    .right-panel {
        flex: 1;
        padding: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-container {
        width: 100%;
        max-width: 380px;
    }

    h4 {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .subtitle {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 2rem;
    }

    .form-label i {
        color: #0d6efd;
    }

    .form-control {
        border-radius: 12px;
        padding: 0.65rem 1rem;
    }

    .btn-primary {
        border-radius: 12px;
        padding: 0.7rem;
        font-weight: 500;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
    }

    .forgot-link {
        font-size: 0.875rem;
        text-decoration: none;
    }

    @media (max-width: 768px) {
        .login-card {
            flex-direction: column;
        }

        .left-panel {
            height: 200px;
        }

        .overlay,
        .left-panel-content {
            display: none;
        }
    }
    </style>
</head>

<body>
    <div class="login-card">
        <!-- Left side -->
        <div class="left-panel">
            <div class="overlay"></div>
            <div class="left-panel-content">
                <img src="./assets/images/logo.jpg" alt="Logo">
                <h5 class="fw-semibold">Welcome Back!</h5>
                <p class="small">Securely manage SM1 tablets with ease</p>
            </div>
        </div>

        <!-- Right side -->
        <div class="right-panel">
            <div class="form-container">
                <div class="text-center mb-4">
                    <h4 class="fw-bold">Admin</h4>
                    <p class="subtitle">Log in to manage student tablets</p>
                </div>

                <form id="loginForm" method="post" action="./pages/PHP/login.code.php" autocomplete="on" novalidate>
                    <div class="mb-3">
                        <label for="username" class="form-label">
                            <i class="fa-solid fa-user me-1"></i> Username
                        </label>
                        <input type="text" id="username" name="username" class="form-control"
                            placeholder="Enter your username" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="fa-solid fa-lock me-1"></i> Password
                        </label>
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Enter your password" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3" id="loginBtn" name="login-btn">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Log In
                    </button>

                    <div class="text-center">
                        <a href="#" class="forgot-link">
                            <i class="fa-solid fa-key me-1"></i> Forgot Password?
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>