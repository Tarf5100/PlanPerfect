<?php
include 'db.php';

$sql = "SELECT * FROM categories";
$result = mysqli_query($conn, $sql);

$categories = [];

while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
}

header('Content-Type: application/json');
echo json_encode($categories);
?>
