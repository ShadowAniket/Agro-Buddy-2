<?php
session_start();

if (isset($_GET['lat']) && isset($_GET['lon'])) {
    $_SESSION['lat'] = floatval($_GET['lat']);
    $_SESSION['lon'] = floatval($_GET['lon']);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Missing coordinates']);
}
?>