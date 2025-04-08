<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start(); 
include 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}
$userID = $_SESSION['userID'];

$serviceID = $_POST['serviceID'] ?? null;

if (!$serviceID) {
    echo json_encode(["status" => "error", "message" => "Missing serviceID"]);
    exit;
}

// Check for duplicates
$checkQuery = "SELECT * FROM favorites WHERE userID = ? AND serviceID = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("ii", $userID, $serviceID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["status" => "exists"]);
    exit;
}

// Insert into favorites
$insertQuery = "INSERT INTO favorites (userID, serviceID) VALUES (?, ?)";
$stmt = $conn->prepare($insertQuery);
$stmt->bind_param("ii", $userID, $serviceID);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}
?>
