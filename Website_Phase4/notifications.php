<?php
session_start();
include 'db.php';  

function insertNotification($conn, $userID, $message, $type = 'general') {
    $sql = "INSERT INTO notifications (userID, message, notificationType, status) VALUES (?, ?, ?, 'unread')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $userID, $message, $type);
    if (!$stmt->execute()) {
        return "Error: " . $stmt->error;
    }
    $stmt->close();
    return true;
}


function fetchNotifications($conn, $userID) {
    $sql = "SELECT notificationID, message, notificationType, timestamp FROM notifications WHERE userID = ? AND status = 'unread' ORDER BY timestamp DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $notifications = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $notifications;
}


function markNotificationAsRead($conn, $notificationID) {
    $sql = "UPDATE notifications SET status = 'read' WHERE notificationID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $notificationID);
    if (!$stmt->execute()) {
        return "Error: " . $stmt->error;
    }
    $stmt->close();
    return true;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'insert':
        $userID = $_POST['userID'] ?? null;
        $message = $_POST['message'] ?? '';
        $type = $_POST['notificationType'] ?? 'general';
        $response = insertNotification($conn, $userID, $message, $type);
        break;

    case 'fetch':
        if (isset($_SESSION['userID'])) {
            $userID = $_SESSION['userID'];
            $response = fetchNotifications($conn, $userID);
        } else {
            $response = ["error" => "Not authenticated"];
        }
        break;


    case 'markRead':
        $notificationID = $_POST['notificationID'] ?? null;
        if ($notificationID !== null) {
            $response = markNotificationAsRead($conn, $notificationID);
        } else {
            $response = ["error" => "Missing notificationID"];
        }
        break;

    default:
        $response = ["error" => "Invalid action"];
        break;
}

header('Content-Type: application/json');
echo json_encode($response);
?>