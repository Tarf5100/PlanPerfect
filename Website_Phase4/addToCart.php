<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
session_start();
include 'db.php';

if (!isset($_SESSION['userID'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

if (!isset($_POST['serviceID'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing serviceID']);
    exit;
}

$userID = $_SESSION['userID'];
$serviceID = $_POST['serviceID'];
$quantity = 1;

// Check if this service is already in the cart
$check = $conn->prepare("SELECT quantity FROM cart WHERE userID = ? AND serviceID = ?");
$check->bind_param("ii", $userID, $serviceID);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    // Update quantity
    $existing = $result->fetch_assoc();
    $newQuantity = $existing['quantity'] + 1;
    $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE userID = ? AND serviceID = ?");
    $update->bind_param("iii", $newQuantity, $userID, $serviceID);
    $update->execute();
} else {
    $stmt = $conn->prepare("INSERT INTO cart (userID, serviceID, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $userID, $serviceID, $quantity);
    $stmt->execute();
}

echo json_encode(['status' => 'success']);
?>
