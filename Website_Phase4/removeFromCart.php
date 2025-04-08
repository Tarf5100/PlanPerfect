<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$userID = $_SESSION['userID'];
$serviceID = $_POST['serviceID'] ?? null;

if (!$serviceID) {
    echo json_encode(['status' => 'error', 'message' => 'Missing serviceID']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM cart WHERE userID = ? AND serviceID = ?");
$stmt->bind_param("ii", $userID, $serviceID);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete item']);
}

$stmt->close();
$conn->close();
?>
