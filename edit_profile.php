<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: Login.php');
    exit;
}

require_once 'config.php';

// Get user data
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';

    // Validate data
    if (empty($username)) {
        $error_message = 'Username is required';
    } elseif (empty($email)) {
        $error_message = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Invalid email format';
    } else {
        // Check if email is already taken by another user
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email AND id != :user_id");
        $stmt->execute([
            'email' => $email,
            'user_id' => $user_id
        ]);
        if ($stmt->rowCount() > 0) {
            $error_message = 'Email is already taken by another user';
        } else {
            // Handle avatar upload
            $avatar = $user['avatar'] ?? 'images/default-avatar.png';

            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                try {
                    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                    $file_type = $_FILES['avatar']['type'];

                    if (!in_array($file_type, $allowed_types)) {
                        $error_message = 'Only JPG, PNG, and GIF files are allowed';
                    } else {
                        $upload_dir = 'uploads/avatars/';

                        // Create directory if it doesn't exist
                        if (!file_exists($upload_dir)) {
                            if (!mkdir($upload_dir, 0777, true)) {
                                throw new Exception('Failed to create avatar upload directory');
                            }
                            // Set directory permissions
                            chmod($upload_dir, 0777);
                        }

                        $file_name = $user_id . '_' . time() . '_' . basename($_FILES['avatar']['name']);
                        $target_file = $upload_dir . $file_name;

                        // Check if file was uploaded via HTTP POST
                        if (!is_uploaded_file($_FILES['avatar']['tmp_name'])) {
                            throw new Exception('File was not uploaded via HTTP POST');
                        }

                        // Check file size (limit to 5MB)
                        if ($_FILES['avatar']['size'] > 5000000) {
                            throw new Exception('File is too large (max 5MB)');
                        }

                        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target_file)) {
                            // Set file permissions
                            chmod($target_file, 0644);
                            $avatar = $target_file;
                        } else {
                            $upload_error = error_get_last();
                            throw new Exception('Failed to move uploaded avatar file: ' . ($upload_error ? $upload_error['message'] : 'Unknown error'));
                        }
                    }
                } catch (Exception $e) {
                    $error_message = 'Avatar upload error: ' . $e->getMessage();
                    error_log('Avatar upload error: ' . $e->getMessage());
                }
            }

            if (empty($error_message)) {
                // Validate phone number
                if (!empty($phone) && !preg_match('/^[0-9]{10}$/', $phone)) {
                    $error_message = 'Phone number must be 10 digits';
                } else {
                    try {
                        // Make sure the avatar column exists
                        try {
                            $conn->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS avatar VARCHAR(255) DEFAULT 'images/default-avatar.png'");
                        } catch (PDOException $e) {
                            // If the database doesn't support IF NOT EXISTS, try a different approach
                            try {
                                // Check if avatar column exists
                                $stmt = $conn->prepare("SHOW COLUMNS FROM users LIKE 'avatar'");
                                $stmt->execute();
                                if ($stmt->rowCount() == 0) {
                                    // Add column if it doesn't exist
                                    $conn->exec("ALTER TABLE users ADD COLUMN avatar VARCHAR(255) DEFAULT 'images/default-avatar.png'");
                                }
                            } catch (PDOException $e2) {
                                error_log("Avatar column check error: " . $e2->getMessage());
                            }
                        }

                        // Update user data
                        $stmt = $conn->prepare("UPDATE users SET username = :username, email = :email, phone = :phone, address = :address, avatar = :avatar WHERE id = :user_id");
                        $result = $stmt->execute([
                            'username' => $username,
                            'email' => $email,
                            'phone' => $phone,
                            'address' => $address,
                            'avatar' => $avatar,
                            'user_id' => $user_id
                        ]);
                    } catch (PDOException $e) {
                        error_log("Profile update error: " . $e->getMessage());

                        // If the address or phone column doesn't exist, try updating without them
                        try {
                            $stmt = $conn->prepare("UPDATE users SET username = :username, email = :email, avatar = :avatar WHERE id = :user_id");
                            $result = $stmt->execute([
                                'username' => $username,
                                'email' => $email,
                                'avatar' => $avatar,
                                'user_id' => $user_id
                            ]);
                        } catch (PDOException $e2) {
                            error_log("Fallback update error: " . $e2->getMessage());
                            $error_message = 'Failed to update profile: ' . $e2->getMessage();
                            $result = false;
                        }
                    }
                }

                if ($result) {
                    // Update session data
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;

                    $success_message = 'Profile updated successfully';

                    // Refresh user data
                    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
                    $stmt->execute(['user_id' => $user_id]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    $error_message = 'Failed to update profile';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - AgroBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            padding-top: 60px;
            background-color: #f8f9fa;
        }

        .profile-container {
            max-width: 800px;
            margin: 30px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .avatar-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 20px;
        }

        .avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .avatar-upload {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #4CAF50;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .btn-success {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }

        .btn-success:hover {
            background-color: #3e8e41;
            border-color: #3e8e41;
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <div class="profile-container">
            <div class="profile-header">
                <h2>Edit Profile</h2>
                <p class="text-muted">Update your personal information</p>
            </div>

            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data">
                <div class="avatar-container">
                    <img src="<?php echo htmlspecialchars($user['avatar'] ?? 'images/default-avatar.png'); ?>" alt="Profile Avatar" class="avatar" id="avatar-preview">
                    <label for="avatar-upload" class="avatar-upload">
                        <i class="fas fa-camera"></i>
                    </label>
                    <input type="file" id="avatar-upload" name="avatar" style="display: none;" accept="image/*">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="dashboard.php" class="btn btn-outline-secondary me-md-2">Cancel</a>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preview avatar image before upload
        document.getElementById('avatar-upload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('avatar-preview').src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
