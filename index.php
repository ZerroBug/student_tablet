<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login</title>

    <link rel="icon" href="./assets/images/logo.jpg" type="image/jpeg" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="assets/vendors/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: "Poppins", sans-serif;
        margin: 0;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        overflow: hidden;
    }

    /* Glass card */
    .login-card {
        width: 100%;
        max-width: 1100px;
        display: flex;
        border-radius: 25px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(18px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
    }

    /* LEFT SIDE */
    .left-panel {
        flex: 1;
        background: url("./assets/images/tab1.jpg") center/cover no-repeat;
        position: relative;
        min-height: 600px;
    }

    .left-panel::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom right, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.3));
    }

    .left-content {
        position: absolute;
        z-index: 2;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: #fff;
    }

    .left-content img {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        border: 4px solid #fff;
        margin-bottom: 20px;
    }

    .left-content h3 {
        font-weight: 600;
        margin-bottom: 10px;
    }

    .left-content p {
        font-size: 0.95rem;
        opacity: 0.9;
    }

    /* RIGHT SIDE */
    .right-panel {
        flex: 1;
        background: #ffffff;
        padding: 60px 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-container {
        width: 100%;
        max-width: 400px;
    }

    .form-title {
        font-weight: 700;
        margin-bottom: 5px;
    }

    .form-subtitle {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 30px;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 6px;
    }

    .form-control {
        border-radius: 14px;
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #2a5298;
        box-shadow: 0 0 0 4px rgba(42, 82, 152, 0.15);
    }

    /* Gradient button */
    .btn-primary {
        border-radius: 14px;
        padding: 12px;
        font-weight: 600;
        border: none;
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        transition: 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(42, 82, 152, 0.4);
    }

    .forgot-link {
        font-size: 0.85rem;
        color: #2a5298;
        text-decoration: none;
    }

    .forgot-link:hover {
        text-decoration: underline;
    }

    @media (max-width: 992px) {
        .login-card {
            flex-direction: column;
            max-width: 500px;
        }

        .left-panel {
            min-height: 200px;
        }

        .right-panel {
            padding: 40px 30px;
        }
    }
    </style>
</head>

<body>

    <div class="login-card">

        <!-- LEFT PANEL -->
        <div class="left-panel">
            <div class="left-content">
                <img src="./assets/images/logo.jpg" alt="Logo">
                <h3>Welcome Back</h3>
                <p>Securely manage SM1 tablets with confidence and ease.</p>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="right-panel">
            <div class="form-container text-center">

                <h4 class="form-title">Admin Login</h4>
                <p class="form-subtitle">Access the management dashboard</p>

                <form method="post" action="./pages/PHP/login.code.php" autocomplete="on">

                    <div class="mb-3 text-start">
                        <label class="form-label">
                            <i class="fa-solid fa-user me-1"></i> Email
                        </label>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>

                    <div class="mb-4 text-start">
                        <label class="form-label">
                            <i class="fa-solid fa-lock me-1"></i> Password
                        </label>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password"
                            required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Log In
                    </button>

                    <a href="#" class="forgot-link">
                        <i class="fa-solid fa-key me-1"></i> Forgot Password?
                    </a>

                </form>
            </div>
        </div>

    </div>

</body>

</html>