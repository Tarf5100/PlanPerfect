<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'db.php';
session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: login.html");
    exit;
}

$userID = $_SESSION['userID'];

$sql = "SELECT serviceID, quantity FROM cart WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$cartResult = $stmt->get_result();

if ($cartResult->num_rows === 0) {
    echo "Your cart is empty.";
    exit;
}

$cartItems = $cartResult->fetch_all(MYSQLI_ASSOC);


// Insert into orders 
$status='انتظار قبول الطلب';
$insertOrder = $conn->prepare("INSERT INTO orders (userID, status, createdAt) VALUES (?, ?, NOW())");
$insertOrder->bind_param("is", $userID, $status);

$insertOrder->execute();
$orderID = $conn->insert_id;

// Insert into orderdetails
$insertDetail = $conn->prepare("INSERT INTO orderdetails (orderID, serviceID, quantity) VALUES (?, ?, ?)");

foreach ($cartItems as $item) {
    $insertDetail->bind_param("iii", $orderID, $item['serviceID'], $item['quantity']);
    $insertDetail->execute();
}

// Clear cart
$clearCart = $conn->prepare("DELETE FROM cart WHERE userID = ?");
$clearCart->bind_param("i", $userID);
$clearCart->execute();

header("Location: order_confirmation.php");
exit;
?>
