<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    echo json_encode(['count' => 0]);
    exit;
}

$userID = $_SESSION['userID'];

$query = "SELECT SUM(quantity) AS total FROM cart WHERE userID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$count = $row['total'] ?? 0;
echo json_encode(['count' => $count]);
?>
