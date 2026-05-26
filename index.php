<?php
// Main entry point - Login/Landing Page
// This file serves as the home page where users can log in or register

session_start();

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List - Login/Register</title>
    <!-- Bootstrap CSS for responsive design and styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row w-100">
            <div class="col-md-8 col-lg-6 mx-auto">
                <!-- Header section -->
                <div class="text-center mb-5">
                    <h1 class="display-4 fw-bold text-primary">My Tasks</h1>
                    <p class="text-muted fs-5">Organize your day with our simple To-Do List</p>
                </div>

                <!-- Authentication container with tabs for login and register -->
                <div class="card shadow-lg border-0">
                    <!-- Tab navigation -->
                    <div class="card-header bg-primary">
                        <ul class="nav nav-tabs nav-fill card-header-tabs border-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active text-white" data-bs-toggle="tab" href="#login" role="tab">
                                    Login
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" data-bs-toggle="tab" href="#register" role="tab">
                                    Register
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Tab content -->
                    <div class="card-body p-5">
                        <div class="tab-content">
                            <!-- LOGIN TAB -->
                            <div class="tab-pane fade show active" id="login" role="tabpanel">
                                <form method="POST" action="auth/login.php">
                                    <!-- Email input -->
                                    <div class="mb-3">
                                        <label for="login_email" class="form-label fw-bold">Email Address</label>
                                        <input 
                                            type="email" 
                                            class="form-control form-control-lg" 
                                            id="login_email" 
                                            name="email" 
                                            required
                                            placeholder="Enter your email"
                                        >
                                    </div>

                                    <!-- Password input -->
                                    <div class="mb-4">
                                        <label for="login_password" class="form-label fw-bold">Password</label>
                                        <input 
                                            type="password" 
                                            class="form-control form-control-lg" 
                                            id="login_password" 
                                            name="password" 
                                            required
                                            placeholder="Enter your password"
                                        >
                                    </div>

                                    <!-- Login button -->
                                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">
                                        Login
                                    </button>
                                </form>
                            </div>

                            <!-- REGISTER TAB -->
                            <div class="tab-pane fade" id="register" role="tabpanel">
                                <form method="POST" action="auth/register.php">
                                    <!-- Username input -->
                                    <div class="mb-3">
                                        <label for="register_username" class="form-label fw-bold">Username</label>
                                        <input 
                                            type="text" 
                                            class="form-control form-control-lg" 
                                            id="register_username" 
                                            name="username" 
                                            required
                                            placeholder="Choose a username"
                                        >
                                    </div>

                                    <!-- Email input -->
                                    <div class="mb-3">
                                        <label for="register_email" class="form-label fw-bold">Email Address</label>
                                        <input 
                                            type="email" 
                                            class="form-control form-control-lg" 
                                            id="register_email" 
                                            name="email" 
                                            required
                                            placeholder="Enter your email"
                                        >
                                    </div>

                                    <!-- Password input -->
                                    <div class="mb-3">
                                        <label for="register_password" class="form-label fw-bold">Password</label>
                                        <input 
                                            type="password" 
                                            class="form-control form-control-lg" 
                                            id="register_password" 
                                            name="password" 
                                            required
                                            placeholder="Create a password"
                                        >
                                    </div>

                                    <!-- Confirm Password input -->
                                    <div class="mb-4">
                                        <label for="register_confirm_password" class="form-label fw-bold">Confirm Password</label>
                                        <input 
                                            type="password" 
                                            class="form-control form-control-lg" 
                                            id="register_confirm_password" 
                                            name="confirm_password" 
                                            required
                                            placeholder="Confirm your password"
                                        >
                                    </div>

                                    <!-- Register button -->
                                    <button type="submit" class="btn btn-success btn-lg w-100 fw-bold">
                                        Create Account
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <p class="text-center text-muted mt-4">
                    &copy; 2026 My Tasks. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS for interactive components -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
