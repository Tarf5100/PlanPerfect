<?php
include 'db.php';
session_start();

header('Content-Type: application/json');

$username = $_POST['username'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = $_POST['accountType'];

$response = [];

$checkUser = $conn->prepare("SELECT * FROM user WHERE username = ? OR email = ?");
$checkUser->bind_param("ss", $username, $email);
$checkUser->execute();
$result = $checkUser->get_result();

if ($result && $result->num_rows > 0) {
    $response = [
        "success" => false,
        "message" => "البريد الإلكتروني أو اسم المستخدم مسجل مسبقاً."
    ];
    echo json_encode($response);
    exit();
}

// Insert into `user`
$insertUser = $conn->prepare("INSERT INTO user (username, email, phone, password, role) VALUES (?, ?, ?, ?, ?)");
$insertUser->bind_param("sssss", $username, $email, $phone, $password, $role);
if ($insertUser->execute()) {
    $userID = $insertUser->insert_id;
    $_SESSION['userID'] = $userID;
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role;

    if ($role === 'vendor') {
        $businessName = $_POST['businessName'];
        $address = $_POST['address'];
        $insertVendor = $conn->prepare("INSERT INTO vendor (vendorID, businessname, address) VALUES (?, ?, ?)");
        $insertVendor->bind_param("iss", $userID, $businessName, $address);
        $insertVendor->execute();
    }

    $response = [
        "success" => true,
        "role" => $role,
        "redirect" => $role === 'vendor' ? 'VenderHomepage.php' : 'CustomerHomepage.php'
    ];
} else {
    $response = [
        "success" => false,
        "message" => "حدث خطأ أثناء التسجيل. حاول مرة أخرى."
    ];
}

echo json_encode($response);
?>
