<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartID = $_POST['cartID'];
    $quantity = $_POST['quantity'];
    $userID = $_SESSION['userID'];

    if ($quantity < 1) $quantity = 1;

    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cartID = ? AND userID = ?");
    $stmt->bind_param("iii", $quantity, $cartID, $userID);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update quantity"]);
    }

    $stmt->close();
    exit();
}
?>
