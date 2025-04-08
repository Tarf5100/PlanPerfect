<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'vendor') {
    echo json_encode(['status' => 'error', 'message' => 'غير مصرح']);
    exit;
}

$serviceID = $_POST['id'] ?? null;
$name = $_POST['name'] ?? '';
$price = $_POST['price'] ?? '';
$categories = $_POST['categories'] ?? [];

if (!$serviceID || empty($name) || empty($price)) {
    echo json_encode(['status' => 'error', 'message' => 'الرجاء تعبئة جميع الحقول']);
    exit;
}

$imagePath = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $newName = uniqid('img_', true) . "." . $ext;
    $targetDir = 'uploads/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
    $targetPath = $targetDir . $newName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
        $imagePath = $targetPath;
    }
}

// Update service table
if ($imagePath) {
    $stmt = $conn->prepare("UPDATE service SET serviceName = ?, price = ?, imageURL = ? WHERE serviceID = ?");
    $stmt->bind_param("sdsi", $name, $price, $imagePath, $serviceID);
} else {
    $stmt = $conn->prepare("UPDATE service SET serviceName = ?, price = ? WHERE serviceID = ?");
    $stmt->bind_param("sdi", $name, $price, $serviceID);
}

if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'فشل في تحديث المنتج: ' . $stmt->error]);
    exit;
}

// Update categories
$conn->query("DELETE FROM servicecategories WHERE serviceID = $serviceID");

if (!empty($categories)) {
    $catStmt = $conn->prepare("INSERT INTO servicecategories (serviceID, categoryID) VALUES (?, ?)");

    foreach ($categories as $catName) {
        $catID = categoryStringToID($catName);
        if ($catID) {
            $catStmt->bind_param("ii", $serviceID, $catID);
            $catStmt->execute();
        }
    }
}

echo json_encode(['status' => 'success', 'message' => 'تم تحديث المنتج بنجاح']);

function categoryStringToID($categoryName) {
    $map = [
        'graduation' => 1,
        'gathering' => 2,
        'wedding' => 3,
        'ghabgah' => 4,
        'birthday' => 5,
        'reception' => 6,
        'chocolate' => 7,
        'dinner' => 8,
        'flowers' => 9,
        'music' => 10,
        'coffee' => 11,
        'organizer' => 12,
    ];
    return $map[$categoryName] ?? null;
}
