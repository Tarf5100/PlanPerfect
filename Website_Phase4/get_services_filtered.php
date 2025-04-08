<?php
include 'db.php';

$eventType = isset($_GET['eventType']) && $_GET['eventType'] !== 'all' ? $_GET['eventType'] : null;
$serviceType = isset($_GET['serviceType']) && $_GET['serviceType'] !== 'all' ? $_GET['serviceType'] : null;

$conditions = [];
$joins = [];

if ($eventType) {
    $joins[] = "JOIN servicecategories sc_event ON s.serviceID = sc_event.serviceID";
    $joins[] = "JOIN categories c_event ON c_event.categoryID = sc_event.categoryID";
    $conditions[] = "c_event.categorytype = 'event' AND c_event.categoryname = '" . mysqli_real_escape_string($conn, $eventType) . "'";
}

if ($serviceType) {
    $joins[] = "JOIN servicecategories sc_service ON s.serviceID = sc_service.serviceID";
    $joins[] = "JOIN categories c_service ON c_service.categoryID = sc_service.categoryID";
    $conditions[] = "c_service.categorytype = 'service' AND c_service.categoryname = '" . mysqli_real_escape_string($conn, $serviceType) . "'";
}

$maxPrice = isset($_GET['maxPrice']) && is_numeric($_GET['maxPrice']) ? (float)$_GET['maxPrice'] : null;
if ($maxPrice !== null) {
    $conditions[] = "s.price <= " . mysqli_real_escape_string($conn, $maxPrice);
}

$sql = "SELECT s.*, v.businessname FROM service s JOIN vendor v ON s.vendorID = v.vendorID";

if (!empty($joins)) {
    $sql .= " " . implode(" ", array_unique($joins));
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$result = mysqli_query($conn, $sql);
$services = [];

while ($row = mysqli_fetch_assoc($result)) {
    // Get categories for each service
    $service_id = $row['serviceID'];
    $cat_sql = "SELECT c.categoryname, c.categorytype
                FROM servicecategories sc
                JOIN categories c ON c.categoryID = sc.categoryID
                WHERE sc.serviceID = $service_id";
    $cat_result = mysqli_query($conn, $cat_sql);

    $eventTypes = [];
    $serviceTypes = [];

    while ($cat = mysqli_fetch_assoc($cat_result)) {
        if ($cat['categorytype'] === 'event') {
            $eventTypes[] = $cat['categoryname'];
        } else {
            $serviceTypes[] = $cat['categoryname'];
        }
    }

    $row['eventTypes'] = $eventTypes;
    $row['serviceTypes'] = $serviceTypes;
    // Get average rating for this service
        $rating_sql = "SELECT AVG(rating) as avgRating FROM reviews WHERE serviceID = ?";
        $rating_stmt = $conn->prepare($rating_sql);
        $rating_stmt->bind_param("i", $service_id);
        $rating_stmt->execute();
        $rating_result = $rating_stmt->get_result();
        $rating_row = $rating_result->fetch_assoc();
    $row['avgRating'] = $rating_row['avgRating'] ? round($rating_row['avgRating'], 1) : null;

    $services[] = $row;
}

header('Content-Type: application/json');
echo json_encode($services);
?>
