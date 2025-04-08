<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

$userID = $_SESSION['userID'];
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';

if (!$username || !$email || !$phone) {
    echo json_encode(["status" => "error", "message" => "All fields except password are required"]);
    exit;
}

if (!empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE user SET username = ?, email = ?, phone = ?, password = ? WHERE userID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $username, $email, $phone, $hashedPassword, $userID);
} else {
    $query = "UPDATE user SET username = ?, email = ?, phone = ? WHERE userID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $username, $email, $phone, $userID);
}

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "تم التحديث بنجاح"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}
?>
