<?php
session_start();
include 'db.php';

if (!isset($_SESSION['userID']) || !isset($_POST['serviceID'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing data']);
    exit;
}

$userID = $_SESSION['userID'];
$serviceID = $_POST['serviceID'];

$stmt = $conn->prepare("DELETE FROM favorites WHERE userID = ? AND serviceID = ?");
$stmt->bind_param("ii", $userID, $serviceID);
$stmt->execute();

echo json_encode(['status' => 'success']);
?>
