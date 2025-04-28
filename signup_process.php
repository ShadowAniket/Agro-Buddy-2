<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    try {
        // Validate input
        if (empty($username) || empty($email) || empty($password)) {
            throw new Exception('All fields are required');
        }

        if ($password !== $confirm_password) {
            throw new Exception('Passwords do not match');
        }

        if (strlen($password) < 6) {
            throw new Exception('Password must be at least 6 characters long');
        }

        // Check if email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->rowCount() > 0) {
            throw new Exception('Email already in use');
        }

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hashed_password
        ]);

        $user_id = $conn->lastInsertId();

        // Set session variables
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;

        // Return success response
        echo json_encode([
            'success' => true,
            'message' => 'Registration successful! Redirecting to dashboard...'
        ]);
        exit;
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
        exit;
    }
}

// Invalid request method
http_response_code(405);
echo json_encode([
    'success' => false,
    'error' => 'Invalid request method'
]);