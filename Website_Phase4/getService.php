<?php
include 'db.php';
header('Content-Type: application/json');

$serviceID = $_GET['id'] ?? null;

if (!$serviceID) {
    echo json_encode(['status' => 'error', 'message' => 'رقم المنتج غير موجود']);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM service WHERE serviceID = ?");
$stmt->bind_param("i", $serviceID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'المنتج غير موجود']);
    exit;
}

$service = $result->fetch_assoc();

$catStmt = $conn->prepare("SELECT categoryID FROM servicecategories WHERE serviceID = ?");
$catStmt->bind_param("i", $serviceID);
$catStmt->execute();
$catResult = $catStmt->get_result();

$categoryIDs = [];
while ($row = $catResult->fetch_assoc()) {
    $categoryIDs[] = $row['categoryID'];
}

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

$categoryNames = array_map(function($id) use ($map) {
    return $map[$id] ?? null;
}, $categoryIDs);

echo json_encode([
    'status' => 'success',
    'service' => $service,
    'categories' => $categoryNames
]);
