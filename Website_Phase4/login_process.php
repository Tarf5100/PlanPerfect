<?php
include 'db.php';
session_start();

header('Content-Type: application/json');

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM user WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['userID'] = $user['userID'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        echo json_encode([
            'success' => true,
            'role' => $user['role']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'كلمة المرور غير صحيحة.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'اسم المستخدم غير مسجل.'
    ]);
}
?>
