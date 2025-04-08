<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userID = $_SESSION['userID'];
    $serviceID = $_POST['serviceID'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $stmt = $conn->prepare("INSERT INTO reviews (userID, serviceID, rating, comment, reviewDate) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiis", $userID, $serviceID, $rating, $comment);

    if ($stmt->execute()) {
        echo "تم إرسال التقييم بنجاح!";
    } else {
        echo "حدث خطأ، يرجى المحاولة لاحقًا.";
    }
}
?>
