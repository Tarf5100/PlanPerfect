<?php
session_start();
header('Content-Type: application/json');

try {
    if (isset($_SESSION['userID'])) {
        echo json_encode(['userID' => $_SESSION['userID']]);
    } else {
        echo json_encode(['error' => 'User not logged in']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>