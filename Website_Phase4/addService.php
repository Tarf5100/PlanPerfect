<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'vendor') {
    echo json_encode(['status' => 'error', 'message' => 'غير مصرح']);
    exit;
}

$vendorID = $_SESSION['userID'];

$name = $_POST['name'] ?? '';
$price = $_POST['price'] ?? '';
$description = $_POST['description'] ?? '';
$categories = $_POST['categories'] ?? [];

if (empty($name) || empty($price) || empty($description)) {
    echo json_encode(['status' => 'error', 'message' => 'جميع الحقول مطلوبة']);
    exit;
}

$imagePath = 'uploads/placeholder.jpg'; // default
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

// Insert into service table
$stmt = $conn->prepare("INSERT INTO service (vendorID, serviceName, description, price, imageURL) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issds", $vendorID, $name, $description, $price, $imagePath);

if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'فشل في إدخال المنتج: ' . $stmt->error]);
    exit;
}

$serviceID = $stmt->insert_id;

// Insert categories
if (!empty($categories)) {
    $catStmt = $conn->prepare("INSERT INTO servicecategories (serviceID, categoryID) VALUES (?, ?)");
    foreach ($categories as $cat) {
        $categoryID = categoryStringToID($cat); 
        if ($categoryID) {
            $catStmt->bind_param("ii", $serviceID, $categoryID);
            $catStmt->execute();
        }
    }
}

echo json_encode([
    'status' => 'success',
    'message' => 'تمت إضافة المنتج بنجاح',
    'imageURL' => $imagePath  // send image path back
]);

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
?>
