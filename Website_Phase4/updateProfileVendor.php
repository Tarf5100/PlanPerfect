<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

ob_start();

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'vendor') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$userID = $_SESSION['userID'];

$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? null;
$businessname = $_POST['businessname'] ?? '';
$address = $_POST['address'] ?? '';

if (empty($username) || empty($email) || empty($phone) || empty($businessname) || empty($address)) {
    echo json_encode(["status" => "error", "message" => "جميع الحقول مطلوبة"]);
    exit;
}

if (!empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE user SET username = ?, email = ?, phone = ?, password = ? WHERE userID = ?");
    $stmt->bind_param("ssssi", $username, $email, $phone, $hashedPassword, $userID);
} else {
    $stmt = $conn->prepare("UPDATE user SET username = ?, email = ?, phone = ? WHERE userID = ?");
    $stmt->bind_param("sssi", $username, $email, $phone, $userID);
}

if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'فشل تحديث معلومات المستخدم: ' . $stmt->error]);
    exit;
}

$stmt2 = $conn->prepare("UPDATE vendor SET businessname = ?, address = ? WHERE vendorID = ?");
$stmt2->bind_param("ssi", $businessname, $address, $userID);

if (!$stmt2->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'فشل تحديث معلومات المتجر: ' . $stmt2->error]);
    exit;
}

ob_end_clean();
echo json_encode(['status' => 'success', 'message' => 'تم التحديث بنجاح']);
exit;
?>
