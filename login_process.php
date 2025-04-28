<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            // Set remember me cookie if requested
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                $expires = time() + (30 * 24 * 60 * 60); // 30 days

                // Store token in database
                $stmt = $conn->prepare("UPDATE users SET remember_token = :token WHERE id = :id");
                $stmt->execute([
                    'token' => $token,
                    'id' => $user['id']
                ]);

                // Set cookie
                setcookie('remember_token', $token, $expires, '/', '', true, true);
            }

            // Return success response
            echo json_encode(['success' => true]);
            exit;
        } else {
            throw new Exception('Invalid email or password');
        }
    } catch (Exception $e) {
        http_response_code(401);
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