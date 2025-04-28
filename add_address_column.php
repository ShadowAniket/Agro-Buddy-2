<?php
require_once 'config.php';

try {
    // Check if the address column already exists
    $stmt = $conn->prepare("SHOW COLUMNS FROM users LIKE 'address'");
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        // Add the address column if it doesn't exist
        $conn->exec("ALTER TABLE users ADD COLUMN address TEXT");
        echo "Successfully added address column to users table.";
    } else {
        echo "Address column already exists in users table.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
