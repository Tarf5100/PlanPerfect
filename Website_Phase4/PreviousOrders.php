<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'db.php';

session_start();
if (isset($_GET['cancel']) && $_GET['cancel'] === 'success') {
    echo "<script>alert('تم إلغاء الطلب بنجاح.');</script>";
}

if (!isset($_SESSION['userID'])) {
    header("Location: log%20in.html");
    exit();
}

$userID = $_SESSION['userID'];

$orderQuery = "SELECT * FROM orders WHERE userID = ? ORDER BY createdAt DESC";
$stmt = $conn->prepare($orderQuery);
$stmt->bind_param("i", $userID);
$stmt->execute();
$ordersResult = $stmt->get_result();

$orders = [];

while ($order = $ordersResult->fetch_assoc()) {
    $orderID = $order['orderID'];

    $detailQuery = "SELECT od.serviceID, s.serviceName, od.quantity, s.price 
                    FROM orderdetails od 
                    JOIN service s ON od.serviceID = s.serviceID 
                    WHERE od.orderID = ?";
    $detailStmt = $conn->prepare($detailQuery);
    $detailStmt->bind_param("i", $orderID);
    $detailStmt->execute();
    $detailResult = $detailStmt->get_result();

    $services = [];
    $calculatedTotal = 0;

    while ($detail = $detailResult->fetch_assoc()) {
        $services[] = $detail;
        $calculatedTotal += $detail['price'] * $detail['quantity'];
    }

    $order['services'] = $services;
    $order['totalPrice'] = $calculatedTotal;
    $orders[] = $order;
}
include 'layout.php';

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>طلباتي السابقة - PlanPerfect</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .order-card {
      background: #fff;
      border-radius: 8px;
      padding: 20px;
      margin: 30px auto;
      max-width: 800px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .order-card h3 {
      margin: 0 0 10px;
    }

    .service-item {
      margin-right: 10px;
      margin-bottom: 5px;
      padding: 6px 10px;
      background: #ecf0f1;
      border-radius: 5px;
      display: inline-block;
    }

    .status {
      padding: 6px 12px;
      border-radius: 5px;
      font-weight: bold;
      display: inline-block;
      margin-top: 10px;
    }

    .status.تم { background: #2ecc71; color: white; }
    .status.قيد { background: #f39c12; color: white; }
    .status.ملغى { background: #e74c3c; color: white; }

    .review-button {
        background-color: #2980b9;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s;
        font-size: 14px;
        margin-right: 10px;
      }

    .review-button:hover {
        background-color: #1c5985;
        transform: scale(1.05);
      }

    h2 {
      text-align: center;
      margin-top: 40px;
      font-size: 30px;
      color: #2c3e50;
    }

    footer {
      text-align: center;
      padding: 20px;
      background-color: #ecf0f1;
      color: #555;
      font-size: 14px;
      margin-top: 40px;
    }
  </style>
</head>
<body>
    <main>
      <h2>طلباتي السابقة</h2>
      <br>
      <?php if (count($orders) === 0): ?>
        <p style="text-align:center;">لا توجد طلبات سابقة.. ابدأ رحلتك معنا ودعنا نبدع في مناسباتك القادمة!</p>
      <?php else: ?>
        <?php foreach ($orders as $order): ?>
          <div class="order-card">
            <h3>طلب رقم: <?= $order['orderID'] ?></h3>
            <p>تاريخ الطلب: <?= $order['createdAt'] ?></p>
            <p>السعر الإجمالي: <?= number_format($order['totalPrice'], 2) ?> ريال</p>
            <p class="status <?= strtok($order['status'], ' ') ?>">الحالة: <?= $order['status'] ?></p>

            <?php if ($order['status'] === 'قيد التجهيز'): ?>
                <form method="POST" action="cancel_order.php" onsubmit="return confirm('هل أنت متأكد من إلغاء الطلب؟');" style="margin-top: 10px;">
                    <input type="hidden" name="orderID" value="<?= $order['orderID'] ?>">
                    <button type="submit" style="background-color:#e74c3c; color:white; padding: 8px 15px; border:none; border-radius:5px; cursor:pointer;">إلغاء الطلب</button>
                </form>
            <?php endif; ?>

            <h4>الخدمات:</h4>
            <?php foreach ($order['services'] as $service): ?>
              <div class="service-item">
                <?= $service['serviceName'] ?> - <?= number_format($service['price']) ?> ريال × <?= $service['quantity'] ?>
                <?php if ($order['status'] === 'تم التوصيل'): ?>
                    <button class="review-button" onclick="openReviewPopup(<?= $service['serviceID'] ?>)">تقييم</button>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>

        <div id="reviewPopup" style="display:none; position:fixed; top:20%; left:35%; background:#fff; border:1px solid #ccc; padding:20px; z-index:999;">
        <h3>أضف تقييمك</h3>
        <form id="reviewForm">
            <input type="hidden" name="serviceID" id="reviewServiceID">
            <label for="rating">التقييم (1-5):</label><br>
            <input type="number" name="rating" id="rating" min="1" max="5" required><br><br>
            <label for="comment">تعليقك:</label><br>
            <textarea name="comment" id="comment" rows="4" required></textarea><br><br>
            <button type="submit">إرسال</button>
            <button type="button" onclick="closeReviewPopup()">إلغاء</button>
        </form>
    </div>

    </main>

    <footer>
    <div class="footer-content">
        <p>جميع الحقوق محفوظة © PlanPerfect 2025</p>
        <p>للتواصل: <a href="mailto:info@planperfect.com">info@planperfect.com</a></p>
        <p><a href="#">شروط الاستخدام</a> | <a href="#">سياسة الخصوصية</a></p>
    </div>
    </footer>
    
    <script>
    function openReviewPopup(serviceID) {
        document.getElementById('reviewServiceID').value = serviceID;
        document.getElementById('reviewPopup').style.display = 'block';
    }

    function closeReviewPopup() {
        document.getElementById('reviewPopup').style.display = 'none';
    }

    document.getElementById('reviewForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('submit_review.php', {
            method: 'POST',
            body: formData
        }).then(res => res.text())
        .then(data => {
            alert(data);
            closeReviewPopup();
        }).catch(err => {
            console.error('Error submitting review:', err);
        });
    });
    </script>

</body>
</html>
