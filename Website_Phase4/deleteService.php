<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'vendor') {
    echo json_encode(['status' => 'error', 'message' => 'غير مصرح']);
    exit;
}

$serviceID = $_POST['id'] ?? null;

if (!$serviceID) {
    echo json_encode(['status' => 'error', 'message' => 'رقم المنتج غير صالح']);
    exit;
}

// Delete related categories first
$delCats = $conn->prepare("DELETE FROM servicecategories WHERE serviceID = ?");
$delCats->bind_param("i", $serviceID);
$delCats->execute();

//delete the service itself
$stmt = $conn->prepare("DELETE FROM service WHERE serviceID = ?");
$stmt->bind_param("i", $serviceID);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'تم حذف المنتج بنجاح']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'فشل في الحذف: ' . $stmt->error]);
}
?>
