<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php';

// Function to check if user is logged in
function isLoggedIn() {
    // Check if session exists
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
        return true;
    }

    // Check for remember me token
    if (isset($_COOKIE['remember_token'])) {
        global $conn;
        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE remember_token = :token");
            $stmt->execute(['token' => $_COOKIE['remember_token']]);
            $user = $stmt->fetch();

            if ($user) {
                // Set session variables
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                return true;
            }
        } catch (Exception $e) {
            error_log("Session check error: " . $e->getMessage());
        }
    }

    return false;
}

// Check if request is AJAX
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// Return session status
if ($isAjax) {
    echo json_encode([
        'logged_in' => isLoggedIn(),
        'user' => isLoggedIn() ? [
            'username' => $_SESSION['username'],
            'email' => $_SESSION['email']
        ] : null
    ]);
    exit;
}

// Redirect to login if not logged in for non-AJAX requests
if (!isLoggedIn() && !in_array(basename($_SERVER['PHP_SELF']), ['Login.php', 'login_process.php', 'signup_process.php'])) {
    header('Location: Login.php');
    exit;
}