<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AgroBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="loading.css">
    <style>
        .auth-container {
            max-width: 400px;
            margin: 80px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background: white;
        }
        .form-floating {
            margin-bottom: 1rem;
        }
        .alert {
            display: none;
            margin-bottom: 1rem;
        }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 10;
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="loading-overlay">
        <div class="d-flex flex-column align-items-center">
            <div class="spinner"></div>
            <div class="loading-text">Logging in...</div>
        </div>
    </div>

    <div class="container">
        <div class="auth-container">
            <div class="text-center mb-4">
                <img src="images/agrobuddy.png" alt="AgroBuddy Logo" height="60">
                <h2 class="mt-3">Welcome Back</h2>
            </div>

            <div class="alert alert-danger" id="error-alert" role="alert"></div>
            <div class="alert alert-success" id="success-alert" role="alert"></div>

            <form id="loginForm" novalidate>
                <div class="form-floating">
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                    <label for="email">Email address</label>
                </div>
                <div class="form-floating position-relative">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('password')"></i>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-success w-100 mb-3">Login</button>
                <div class="text-center">
                    <p class="mb-0">Don't have an account? <a href="#" onclick="showSignupForm()">Sign up</a></p>
                </div>
            </form>

            <form id="signupForm" style="display: none;" novalidate>
                <div class="form-floating">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                    <label for="username">Username</label>
                </div>
                <div class="form-floating">
                    <input type="email" class="form-control" id="signup-email" name="email" placeholder="name@example.com" required>
                    <label for="signup-email">Email address</label>
                </div>
                <div class="form-floating position-relative">
                    <input type="password" class="form-control" id="signup-password" name="password" placeholder="Password" required>
                    <label for="signup-password">Password</label>
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('signup-password')"></i>
                </div>
                <div class="form-floating position-relative">
                    <input type="password" class="form-control" id="confirm-password" name="confirm_password" placeholder="Confirm Password" required>
                    <label for="confirm-password">Confirm Password</label>
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('confirm-password')"></i>
                </div>
                <button type="submit" class="btn btn-success w-100 mb-3">Sign Up</button>
                <div class="text-center">
                    <p class="mb-0">Already have an account? <a href="#" onclick="showLoginForm()">Login</a></p>
                </div>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.nextElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function showError(message) {
            const alert = document.getElementById('error-alert');
            alert.textContent = message;
            alert.style.display = 'block';
            setTimeout(() => alert.style.display = 'none', 5000);
        }

        function showSuccess(message) {
            const alert = document.getElementById('success-alert');
            alert.textContent = message;
            alert.style.display = 'block';
            setTimeout(() => alert.style.display = 'none', 5000);
        }

        function showLoginForm() {
            document.getElementById('signupForm').style.display = 'none';
            document.getElementById('loginForm').style.display = 'block';
        }

        function showSignupForm() {
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('signupForm').style.display = 'block';
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            document.querySelector('.loading-overlay').style.display = 'flex';

            fetch('login_process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('Login successful! Redirecting...');
                    setTimeout(() => window.location.href = 'dashboard.php', 1000);
                } else {
                    showError(data.error || 'Login failed. Please try again.');
                }
            })
            .catch(error => {
                showError('An error occurred. Please try again.');
                console.error('Login error:', error);
            })
            .finally(() => {
                document.querySelector('.loading-overlay').style.display = 'none';
            });
        });

        document.getElementById('signupForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            if (formData.get('password') !== formData.get('confirm_password')) {
                showError('Passwords do not match');
                return;
            }

            document.querySelector('.loading-overlay').style.display = 'flex';

            fetch('signup_process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess(data.message);
                    setTimeout(() => window.location.href = 'dashboard.php', 1000);
                } else {
                    showError(data.error || 'Registration failed. Please try again.');
                }
            })
            .catch(error => {
                showError('An error occurred. Please try again.');
                console.error('Signup error:', error);
            })
            .finally(() => {
                document.querySelector('.loading-overlay').style.display = 'none';
            });
        });
    </script>
</body>
</html>