<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orderID'])) {
    $orderID = $_POST['orderID'];
    $userID = $_SESSION['userID'];

    $check = $conn->prepare("SELECT * FROM orders WHERE orderID = ? AND userID = ?");
    $check->bind_param("ii", $orderID, $userID);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $orderData = $result->fetch_assoc();
        $check->close();

        $details = $conn->prepare("SELECT s.vendorID, s.serviceName, u.username 
                                   FROM orderdetails od
                                   JOIN service s ON od.serviceID = s.serviceID
                                   JOIN user u ON u.userID = ?
                                   WHERE od.orderID = ?");
        $details->bind_param("ii", $userID, $orderID);
        $details->execute();
        $resultDetails = $details->get_result();
        $info = $resultDetails->fetch_assoc();
        $details->close();

        $vendorID = $info['vendorID'];
        $serviceName = $info['serviceName'];
        $customerName = $info['username'];

        $stmt = $conn->prepare("UPDATE orders SET status = 'ملغى' WHERE orderID = ?");
        $stmt->bind_param("i", $orderID);
        if ($stmt->execute()) {

            // Send vendor notification 
            $message = "قام $customerName بإلغاء حجز خدمة $serviceName.";
            $type = "cancellation";

            $notif = $conn->prepare("INSERT INTO notifications (userID, message, notificationType, status) VALUES (?, ?, ?, 'unread')");
            $notif->bind_param("iss", $vendorID, $message, $type);
            $notif->execute();
            $notif->close();

            header("Location: PreviousOrders.php?cancel=success");
            exit();
        } else {
            echo "حدث خطأ أثناء إلغاء الطلب.";
        }
    } else {
        echo "هذا الطلب غير موجود أو لا يخصك.";
    }
}
?>