<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login</title>

    <link rel="icon" href="./assets/images/logo.jpg" type="image/jpeg" />

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="assets/vendors/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    body {
        font-family: "Poppins", sans-serif;
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        margin: 0;
    }

    .login-card {
        width: 100%;
        max-width: 380px;
        background: #ffffff;
        padding: 35px 30px;
        border-radius: 16px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .logo {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 15px;
    }

    h4 {
        font-weight: 600;
        margin-bottom: 5px;
    }

    .subtitle {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 25px;
    }

    .form-label {
        font-weight: 500;
        font-size: 0.85rem;
    }

    .form-control {
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 0.9rem;
    }

    .form-control:focus {
        border-color: #2a5298;
        box-shadow: 0 0 0 3px rgba(42, 82, 152, 0.15);
    }

    .btn-primary {
        border-radius: 10px;
        padding: 10px;
        font-weight: 500;
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        border: none;
    }

    .btn-primary:hover {
        opacity: 0.9;
    }

    .forgot-link {
        font-size: 0.8rem;
        color: #2a5298;
        text-decoration: none;
    }

    .forgot-link:hover {
        text-decoration: underline;
    }
    </style>
</head>

<body>

    <div class="login-card text-center">

        <img src="./assets/images/logo.jpg" alt="Logo" class="logo">

        <h4>Admin Login</h4>
        <p class="subtitle">Access the management dashboard</p>

        <form method="post" action="./pages/PHP/login.code.php">

            <div class="mb-3 text-start">
                <label class="form-label">
                    <i class="fa-solid fa-user me-1"></i> Email
                </label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>

            <div class="mb-3 text-start">
                <label class="form-label">
                    <i class="fa-solid fa-lock me-1"></i> Password
                </label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                <i class="fa-solid fa-right-to-bracket me-1"></i> Log In
            </button>

            <a href="#" class="forgot-link">
                Forgot Password?
            </a>

        </form>

    </div>

</body>

</html>