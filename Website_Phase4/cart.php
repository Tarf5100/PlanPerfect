<?php
include 'layout.php';

if (!isset($_SESSION['userID'])) {
    header("Location: login.html");
    exit;
}

$userID = $_SESSION['userID'];

$query = "
    SELECT c.cartID, c.quantity, s.serviceID, s.serviceName, s.price, s.imageURL, v.businessname
    FROM cart c
    JOIN service s ON c.serviceID = s.serviceID
    JOIN vendor v ON s.vendorID = v.vendorID
    WHERE c.userID = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
$total = 0;

while ($row = $result->fetch_assoc()) {
    $row['subtotal'] = $row['quantity'] * $row['price'];
    $total += $row['subtotal'];
    $cartItems[] = $row;
}

$tax = $total * 0.15;
$delivery = 25;
$grandTotal = $total + $tax + $delivery;
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanPerfect - عربة التسوق</title>
    <style>
        /* Navigation Links */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-link {
            color: var(--light-color);
            text-decoration: none;
            font-size: 16px;
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: #f0f0f0;
        }

        main {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .page-title {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            color: #2c3e50; 
        }

        .cart-container {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .cart-items {
            flex: 1;
            min-width: 60%;
        }

        .cart-summary {
            flex: 0 0 30%;
            min-width: 250px;
        }

        .section-container {
            border: 2px solid rgba(44, 62, 80, 0.5); 
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 40px;
            background-color: rgba(44, 62, 80, 0.1); 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .section-title {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #2c3e50; 
        }

        .cart-item {
            background-color: rgba(44, 62, 80, 0.08); 
            border: 1px solid rgba(44, 62, 80, 0.2);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            display: flex;
            position: relative;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .cart-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(44, 62, 80, 0.15);
        }

        .cart-item-image {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            background-color: white;
            object-fit: cover;
            margin-left: 20px;
        }

        .cart-item-details {
            flex: 1;
        }

        .cart-item-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .cart-item-vendor {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 10px;
        }

        .cart-item-price {
            font-size: 16px;
            color: #2c3e50;
            font-weight: bold;
        }

        .cart-item-actions {
            display: flex;
            align-items: center;
            margin-top: 15px;
            justify-content: space-between;
        }

        .quantity-control {
            display: flex;
            align-items: center;
        }

        .quantity-btn {
            width: 30px;
            height: 30px;
            border: 1px solid #3498db;
            background: white;
            color: #3498db;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .quantity-btn:hover {
            background-color: #3498db;
            color: white;
        }

        .quantity-display {
            margin: 0 10px;
            font-size: 16px;
            min-width: 30px;
            text-align: center;
            font-weight: bold;
        }

        .remove-btn {
            color: #e74c3c;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            padding: 5px 10px;
            border: 1px solid #e74c3c;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .remove-btn:hover {
            background-color: #e74c3c;
            color: white;
        }

        .cart-item-subtotal {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 15px;
            text-align: left;
            border-top: 1px solid rgba(44, 62, 80, 0.2);
            padding-top: 10px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .summary-row.total {
            font-size: 20px;
            font-weight: bold;
            border-top: 1px solid #ddd;
            padding-top: 15px;
            margin-top: 15px;
        }

        .checkout-btn {
            width: 100%;
            padding: 15px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 20px;
        }

        .checkout-btn:hover {
            background-color: #3498db;
        }

        .continue-shopping {
            display: block;
            width: 200px;
            margin: 30px auto;
            padding: 15px;
            text-align: center;
            background-color: white;
            color: #2c3e50;
            border: 2px solid #2c3e50;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }

        .continue-shopping:hover {
            background-color: #f5f5f5;
        }

        .empty-cart-message {
            text-align: center;
            padding: 30px;
            font-size: 18px;
            color: #7f8c8d;
        }

        .navigation-links {
            text-align: center;
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .nav-button {
            padding: 12px 20px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .nav-button:hover {
            background-color: #3498db;
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
        <h2 class="page-title">عربة التسوق</h2>

        <div class="cart-container">
            <div class="cart-items section-container">
                <h2 class="section-title">المنتجات المضافة</h2>

                <div id="cart-items-container">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="cart-item" id="item-<?= $item['serviceID'] ?>">
                            <img src="<?= htmlspecialchars($item['imageURL']) ?>" alt="صورة المنتج" class="cart-item-image">
                            <div class="cart-item-details">
                                <div class="cart-item-title"><?= htmlspecialchars($item['serviceName']) ?></div>
                                <div class="cart-item-vendor">المتجر: <?= htmlspecialchars($item['businessname']) ?></div>
                                <div class="cart-item-price" id="price-<?= $item['cartID'] ?>" data-price="<?= $item['price'] ?>">
                                    <?= number_format($item['price'], 2) ?> ريال
                                </div>

                                <div class="cart-item-actions">
                                    <div class="quantity-control">
                                        <div class="quantity-btn" onclick="updateQuantity(<?= $item['cartID'] ?>, -1)">-</div>
                                        <div class="quantity-display" id="quantity-<?= $item['cartID'] ?>"><?= $item['quantity'] ?></div>
                                        <div class="quantity-btn" onclick="updateQuantity(<?= $item['cartID'] ?>, 1)">+</div>
                                    </div>
                                    <div class="remove-btn" onclick="removeItem(<?= $item['serviceID'] ?>)">إزالة</div>

                                </div>

                                <div class="cart-item-subtotal" id="subtotal-<?= $item['cartID'] ?>">
                                    <?= number_format($item['subtotal'], 2) ?> ريال
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="cart-summary section-container" id="cart-summary">
                <h2 class="section-title">ملخص الطلب</h2>

                <div class="summary-row">
                    <div>إجمالي المنتجات</div>
                    <div id="product-total"><?= number_format($total, 2) ?> ريال</div>
                </div>

                <div class="summary-row">
                    <div>ضريبة القيمة المضافة (15%)</div>
                    <div id="vat"><?= number_format($tax, 2) ?> ريال</div>
                </div>

                <div class="summary-row">
                    <div>رسوم التوصيل</div>
                    <div id="delivery"><?= number_format($delivery, 2) ?> ريال</div>
                </div>

                <div class="summary-row total">
                    <div>المجموع الكلي</div>
                    <div id="cart-total"><?= number_format($grandTotal, 2) ?> ريال</div>
                </div>

                <form method="POST" action="place_order.php">
                    <button type="submit" class="checkout-btn">إتمام الطلب</button>
                </form>

            </div>
        </div>

        <div class="navigation-links">
            <a href="CustomerHomepage.php" class="nav-button">الصفحة الرئيسية</a>
            <a href="Services.php" class="nav-button">متابعة التسوق</a>
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
        function updateQuantity(cartID, change) {
            const quantityElement = document.getElementById('quantity-' + cartID);
            let quantity = parseInt(quantityElement.innerText);
            quantity += change;

            if (quantity < 1) quantity = 1;
            quantityElement.innerText = quantity;

            updateSubtotal(cartID, quantity);
            updateTotal();

            fetch('updateCartQuantity.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `cartID=${cartID}&quantity=${quantity}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.status !== "success") {
                    alert("فشل في تحديث الكمية .");
                }
            })
            .catch(err => {
                console.error("Error updating quantity:", err);
            });
        }

        function updateSubtotal(itemId, quantity) {
            const priceElement = document.getElementById('price-' + itemId);
            const subtotalElement = document.getElementById('subtotal-' + itemId);
            
            const price = parseFloat(priceElement.getAttribute('data-price'));
            const subtotal = price * quantity;
            
            subtotalElement.innerText = subtotal.toFixed(2) + ' ريال';
        }

        function removeItem(serviceID) {
            fetch('removeFromCart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `serviceID=${serviceID}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    const item = document.getElementById('item-' + serviceID);
                    if (item) item.remove();
                    updateTotal(); 
                    checkIfCartEmpty(); // hide summary if empty
                } else {
                    alert("خطأ في إزالة العنصر من السلة.");
                }
            })
            .catch(err => {
                console.error("Remove item error:", err);
            });
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.cart-item').forEach(item => {
                const subtotalText = item.querySelector('.cart-item-subtotal');
                if (subtotalText) {
                    const value = parseFloat(subtotalText.textContent.replace(/[^\d.]/g, ""));
                    if (!isNaN(value)) total += value;
                }
            });

            const vat = total * 0.15;
            const delivery = total > 0 ? 25 : 0;
            const grandTotal = total + vat + delivery;

            document.getElementById('product-total').textContent = total.toFixed(2) + ' ريال';
            document.getElementById('vat').textContent = vat.toFixed(2) + ' ريال';
            document.getElementById('delivery').textContent = delivery.toFixed(2) + ' ريال';
            document.getElementById('cart-total').textContent = grandTotal.toFixed(2) + ' ريال';
        }

        function checkIfCartEmpty() {
            if (document.querySelectorAll('.cart-item').length === 0) {
                document.getElementById('cart-items-container').innerHTML =
                    '<div class="empty-cart-message">العربة فارغة.. لكن نحن هنا لنساعدك في صنع لحظات لا تنسى!</div>';
                document.getElementById('cart-summary').style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            checkIfCartEmpty(); // hide summary if empty on page load
        });
    </script>
</body>
</html>