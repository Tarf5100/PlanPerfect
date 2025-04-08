<?php
session_start();
include 'layout.php';
include 'db.php';

$userID = $_SESSION['userID'];
$fav_sql = "
    SELECT s.serviceID, s.serviceName, s.price, s.imageURL 
    FROM favorites f 
    JOIN service s ON f.serviceID = s.serviceID 
    WHERE f.userID = ?";
$stmt = $conn->prepare($fav_sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$favorites = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>PlanPerfect - المفضلة</title>
    <style>
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

        .favorites-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }

        .favorite-item {
            background-color: rgba(44, 62, 80, 0.08);
            border: 1px solid rgba(44, 62, 80, 0.2);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            width: 200px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

            .favorite-item:hover {
                transform: translateY(-3px);
                box-shadow: 0 5px 15px rgba(44, 62, 80, 0.15);
            }

            .favorite-item img {
                width: 100%;
                height: 120px;
                object-fit: cover;
                border-radius: 8px;
            }

        .item-title {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
            color: #2c3e50;
        }

        .item-price {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
        }

        .add-btn {
            margin-top: 10px;
            padding: 8px 15px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

            .add-btn:hover {
                background-color: #1f2e40;
            }

        .popup-message {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            font-size: 16px;
            text-align: center;
            z-index: 1000;
        }
        .remove-btn {
            margin-top: 10px;
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
        }

        .remove-btn:hover {
            background-color: #c0392b;
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
    <h2 class="page-title">المفضلة</h2>
    <div class="favorites-container">
        <?php if ($favorites->num_rows === 0): ?>
            <p style="text-align:center; font-size: 18px; color: #555; margin-top: 40px;">
                قائمة المفضلة فارغة حاليا.. أضف لمستك وابدأ باختيار ما تحب!
            </p>
        <?php else: ?>
            <?php while($row = $favorites->fetch_assoc()): ?>
                <div class="favorite-item" id="fav-<?= $row['serviceID'] ?>">
                    <img src="<?= $row['imageURL'] ?: 'images/placeholder.png' ?>" alt="صورة المنتج">
                    <div class="item-title"><?= htmlspecialchars($row['serviceName']) ?></div>
                    <div class="item-price"><?= number_format($row['price'], 2) ?> ريال</div>
                    <button class="add-btn" onclick="addToCart(<?= $row['serviceID'] ?>)">إضافة إلى السلة</button>
                    <button class="remove-btn" onclick="removeFavorite(<?= $row['serviceID'] ?>)">إزالة</button>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
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
function addToCart(serviceID) {
    const formData = new FormData();
    formData.append('serviceID', serviceID);

    fetch('addToCart.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            alert("تمت إضافة المنتج إلى السلة");
        } else {
            alert("حدث خطأ أثناء الإضافة");
        }
    });
}

function removeFavorite(serviceID) {
    if (!confirm("هل أنت متأكد من إزالة المنتج من المفضلة؟")) return;

    const formData = new FormData();
    formData.append('serviceID', serviceID);

    fetch('removeFromFavorites.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            document.getElementById("fav-" + serviceID).remove();
        } else {
            alert("حدث خطأ أثناء الحذف");
        }
    });
}
</script>

</body>
</html>
