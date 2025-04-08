<!DOCTYPE html>
<?php
session_start();
include 'db.php';

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'vendor') {
    header("Location: log in.html");
    exit;
}

$userID = $_SESSION['userID'];

// Fetch user info
$stmt = $conn->prepare("SELECT username, email, phone, password FROM user WHERE userID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch vendor info
$vendorStmt = $conn->prepare("SELECT businessname, address FROM vendor WHERE vendorID = ?");
$vendorStmt->bind_param("i", $userID);
$vendorStmt->execute();
$vendorResult = $vendorStmt->get_result();
$vendor = $vendorResult->fetch_assoc();

// Fetch vendor's services
$servicesStmt = $conn->prepare("SELECT * FROM service WHERE vendorID = ?");
$servicesStmt->bind_param("i", $userID);
$servicesStmt->execute();
$servicesResult = $servicesStmt->get_result();
$services = $servicesResult->fetch_all(MYSQLI_ASSOC);
$serviceIDs = array_column($services, 'serviceID');
$reviewData = [];

if (!empty($serviceIDs)) {
    $placeholders = implode(',', array_fill(0, count($serviceIDs), '?'));
    $types = str_repeat('i', count($serviceIDs));

    $reviewQuery = "SELECT * FROM reviews WHERE serviceID IN ($placeholders)";
    $stmt = $conn->prepare($reviewQuery);
    $stmt->bind_param($types, ...$serviceIDs);
    $stmt->execute();
    $reviewsResult = $stmt->get_result();
    $reviewData = $reviewsResult->fetch_all(MYSQLI_ASSOC);
}


?>

<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanPerfect</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--primary-color);
            padding: 15px;
            color: var(--light-color);
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo {
            height: 40px;
            margin-left: 10px;
        }

        .icons-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .profile-icon, .notificationIcon{
            width: 30px;
            height: 30px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .profile-icon:hover, .notificationIcon:hover {
            transform: scale(1.1);
        }
        
        .store-name {
            font-size: 20px;
            font-weight: bold;
            background-color: var(--primary-color);
            color: var(--light-color);
            padding: 10px;
            border-radius: var(--border-radius);
            margin: 10px 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 18px;
            margin: 15px 0 10px;
            color: var(--primary-color);
            text-align: right;
        }

        .products-container {
            background-color: var(--primary-color);
            padding: 20px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }
        
        .items-row {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .item {
            width: 200px;
            background: var(--card-bg-color, #f5f5f5);
            padding: 15px;
            border-radius: var(--border-radius, 8px);
            text-align: right;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .item:hover {
            transform: translateY(-3px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .item-image img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: var(--border-radius, 8px);
        }

        .item-info {
            margin-top: 8px;
        }

        .item-title {
            font-size: 16px;
            margin-bottom: 3px;
            font-weight: bold;
        }

        .item-price {
            margin: 5px 0;
            font-weight: bold;
            color: #2c3e50;
            font-size: 13px;
        }

        .tag-container {
            display: flex;
            flex-wrap: wrap;
            gap: 3px;
            margin: 3px 0;
        }

        .tag {
            display: inline-block;
            background: var(--primary-color);
            color: var(--light-color);
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 8px;
        }

        .edit-button, .delete-button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 3px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .edit-button:hover, .delete-button:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }

        .edit-button img, .delete-button img {
            width: 16px;
            height: 16px;
        }

        .reviews-section {
            background-color: var(--primary-color);
            padding: 20px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
        }
        
        .reviews-row {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .review {
            width: 200px;
            background: var(--card-bg-color, #f5f5f5);
            padding: 15px;
            border-radius: var(--border-radius, 8px);
            text-align: right;
            min-height: 100px;
            transition: transform 0.2s;
        }

        .review:hover {
            transform: translateY(-2px);
        }

        .order-number {
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 3px;
            font-size: 12px;
        }

        .rating {
            color: #f39c12;
            font-size: 16px;
            margin: 3px 0;
        }

        .review-text {
            margin-top: 3px;
            line-height: 1.3;
            font-size: 12px;
        }

        .browse-stores, .add-product-button {
            display: block;
            width: 100%;
            max-width: 250px;
            margin: 15px auto;
            padding: 10px;
            color: #2c3e50;
            border: none;
            border-radius: var(--border-radius);
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            font-weight: bold;
            background-color: #c3e1eb;
        }

        .browse-stores:hover, .add-product-button:hover {
            background-color: #9bbac4;
            transform: translateY(-2px);
        }
            
        .notification-wrapper {
            position: relative;
            overflow: visible; 
            z-index: 1000;
        }
        
        .notification-dropdown {
            position: absolute;
            top: 40px;
            right: -220px;
            min-width: 280px;
            max-width: 90vw;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 1001;
            display: none;
        }

        .notification-header {
            background-color: var(--primary-color, #2c3e50);
            color: white;
            padding: 10px 15px;
            font-weight: bold;
            border-bottom: 1px solid #eee;
        }

        .notification-content {
            max-height: 350px;
            overflow-y: auto;
            color: #2c3e50;
        }

        .notification-dropdown ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .notification-dropdown li {
            padding: 10px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }

        .notification-dropdown li:hover {
            background-color: #f5f5f5;
        }

        .notification-dropdown li:last-child {
            border-bottom: none;
        }
        
        #notificationCount {
            position: absolute;
            top: -5px;
            right: -5px;
            background: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 10px;
            display: none;
            min-width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
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
    <header>
        <div class="header-container">
            <div class="logo-container">
                <a href="VenderHomepage.php">
                    <img src="images/logo.png" alt="الرئيسية" class="logo">
                </a>
                <h1>PlanPerfect</h1>
            </div>
            <div class="icons-container">
                <div class="notification-wrapper">
                    <img src="images/notification.png" id="notificationIcon" style="width: 30px; cursor: pointer;">
                    <span id="notificationCount">0</span>
                    <div id="notificationDropdown" class="notification-dropdown">
                        <div class="notification-header">الإشعارات</div>
                        <div class="notification-content">
                            <ul id="notificationList">
                                <li>جاري التحميل...</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="profile-container" id="profileButton">
                    <img src="images/profile.png" alt="البروفايل" class="profile-icon">
                </div>
            </div>
        </div>
    </header>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <button class="sidebar-close" id="sidebarClose">×</button>
            <img src="images/profile.png" alt="صورة الملف الشخصي" class="profile-pic">
            <h2 class="user-name"><?= htmlspecialchars($vendor['businessname']) ?></h2>
            <p class="user-email"><?= htmlspecialchars($user['email']) ?></p>
        </div>
        <div class="profile-form">
            <form id="profileForm">
                <div class="form-group">
                    <label for="userName">الاسم</label>
                    <input type="text" id="userName" name="businessname" class="form-control" value="<?= htmlspecialchars($vendor['businessname']) ?>" required>
                </div>
                
                <div class="form-group">
                <label for="userEmail">البريد الإلكتروني</label>
                <input type="email" id="userEmail" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>">
                </div>
                
                <div class="form-group">
                    <label for="userPhone">رقم الجوال</label>
                    <input type="tel" id="userPhone" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="userCity">المدينة</label>
                    <input type="text" id="userCity" name="address" class="form-control" value="<?= htmlspecialchars($vendor['address']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="userUsername">اسم المستخدم</label>
                    <input type="text" id="userUsername" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="userPassword">كلمة المرور</label>
                    <input type="password" id="userPassword" name="password" class="form-control"placeholder="********" autocomplete="new-password">
                </div>

                <button type="submit" class="save-button">حفظ التغييرات</button>
                <button type="button" class="logout-button" id="logoutButton">تسجيل الخروج</button>
            </form>
        </div>
    </div>

    <main>
        <h2 class="store-name"><?= htmlspecialchars($vendor['businessname']) ?></h2>

        <section class="products-container">
            <h2 class="section-title" style="color: white; margin-top: 0;">المنتجات</h2>
            <div class="items-row">
                <?php if (count($services) === 0): ?>
                    <p style="text-align:center; font-size: 18px; color: white; margin-top: 20px;">
                        لم تقم بإضافة أي خدمات بعد... <br>
                        ابدأ الآن، دع العالم يرى تميزك!
                    </p>
                <?php else: ?>
                    <?php foreach ($services as $service): ?>
                    <div class="item">
                        <div class="item-image">
                            <img src="<?= htmlspecialchars($service['imageURL'] ?: 'images/placeholder.png') ?>" alt="<?= htmlspecialchars($service['serviceName']) ?>">
                        </div>
                        <div class="item-info">
                            <h3 class="item-title"><?= htmlspecialchars($service['serviceName']) ?></h3>
                            <p class="item-price">السعر: <?= number_format($service['price'], 2) ?> ريال</p>
                            <div class="action-buttons">
                                <button class="edit-button" onclick="window.location.href='edit.html?id=<?= $service['serviceID'] ?>'">
                                    <img src="images/edit_1.png" alt="تعديل">
                                </button>

                                <button class="delete-button" onclick="deleteService(<?= $service['serviceID'] ?>, this)">
                                    <img src="images/delete_1.png" alt="حذف">
                                </button>

                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <button class="add-product-button" onclick="window.location.href='add.html'">إضافة منتج جديد</button>
        </section>

        <section class="reviews-section">
            <h2 class="section-title" style="color: white; margin-top: 0;">التقييمات</h2>
            <div class="reviews-row">
                <?php if (count($reviewData) === 0): ?>
                    <p style="color: white; text-align: center;">لم يتم تقييم خدماتك بعد.. قدم أفضل ما لديك والتقييمات الرائعة قادمة!</p>
                <?php else: ?>
                    <?php foreach ($reviewData as $review): ?>
                    <div class="review">
                        <p class="order-number">الخدمة #<?= htmlspecialchars($review['serviceID']) ?></p>
                        <div class="rating">
                            <?= str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']) ?>
                        </div>
                        <p class="review-text"><?= htmlspecialchars($review['comment']) ?></p>
                        <small style="font-size: 11px; color: gray;"><?= htmlspecialchars($review['reviewDate']) ?></small>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <button class="browse-stores" onclick="window.location.href='CustomerHomepage.php'">تصفح المتاجر</button>
    </main>
<footer>
    <div class="footer-content">
        <p>جميع الحقوق محفوظة © PlanPerfect 2025</p>
        <p>للتواصل: <a href="mailto:info@planperfect.com">info@planperfect.com</a></p>
        <p><a href="#">شروط الاستخدام</a> | <a href="#">سياسة الخصوصية</a></p>
    </div>
</footer>
<script>
        function confirmDelete() {
            if (confirm('هل أنت متأكد من حذف هذا المنتج؟')) {
                alert('تم حذف المنتج بنجاح');
            }
        }
        function deleteService(serviceID, button) {
            if (!confirm('هل أنت متأكد من حذف هذا المنتج؟')) return;

            const formData = new FormData();
            formData.append('id', serviceID);

            fetch('deleteService.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') {
                    const item = button.closest('.item');
                    if (item) item.remove();
                }
            })
            .catch(err => {
                console.error('Error deleting service:', err);
                alert('حدث خطأ أثناء حذف المنتج.');
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const profileButton = document.getElementById('profileButton');
            const sidebar = document.getElementById('sidebar');
            const sidebarClose = document.getElementById('sidebarClose');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const logoutButton = document.getElementById('logoutButton');
            const profileForm = document.getElementById('profileForm');

            const notificationIcon = document.getElementById('notificationIcon');
            const notificationDropdown = document.getElementById('notificationDropdown');
            const notificationList = document.getElementById('notificationList');
            const notificationCount = document.getElementById('notificationCount');


            profileButton.addEventListener('click', function () {
                sidebar.classList.add('open');
                sidebarOverlay.style.display = 'block';
                document.body.classList.add('sidebar-open');
            });

            function closeSidebar() {
                sidebar.classList.remove('open');
                sidebarOverlay.style.display = 'none';
                document.body.classList.remove('sidebar-open');
            }

            sidebarClose.addEventListener('click', closeSidebar);

            sidebarOverlay.addEventListener('click', closeSidebar);

            logoutButton.addEventListener('click', function () {
                if (confirm('هل أنت متأكد من تسجيل الخروج؟')) {
                    console.log('User logged out');
                    window.location.href = 'log in.html'; 
                }
            });
             document.getElementById('profileForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch('updateProfileVendor.php', {
                  method: 'POST',
                  body: formData
                })
                .then(res => res.json())
                .then(data => {
                  if (data.status === "success") {
                    alert(data.message);
                    sidebar.classList.remove('open');
                    sidebarOverlay.style.display = 'none';
                    document.body.classList.remove('sidebar-open');
                  } else {
                    alert(data.message);
                  }
                })
                .catch(err => {
                  console.error("Update error:", err);
                  alert("حدث خطأ أثناء التحديث");
                });
              });

              
            //Notification functions
                notificationIcon.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent click from propagating
                console.log("Notification icon clicked");
                
                if (notificationDropdown.style.display === 'none' || notificationDropdown.style.display === '') {
                    notificationDropdown.style.display = 'block';
                    fetchNotifications();
                } else {
                    notificationDropdown.style.display = 'none';
                }
            });
            
            document.addEventListener('click', function(e) {
                if (notificationDropdown.style.display === 'block' && 
                    e.target !== notificationIcon && 
                    !notificationDropdown.contains(e.target)) {
                    notificationDropdown.style.display = 'none';
                }
            });

            // Fetch notifications from server
            function fetchNotifications() {
                const userID = 111; 
                
                fetch(`notifications.php?action=fetch&userID=${userID}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log("Notifications received:", data);
                        notificationList.innerHTML = ''; 

                        if (data.length === 0) {
                            notificationList.innerHTML = '<li>لا توجد إشعارات جديدة</li>';
                            notificationCount.style.display = 'none';
                        } else {
                            data.forEach(notification => {
                                const li = document.createElement('li');
                                li.innerHTML = `
                                    <strong>${notification.notificationType}</strong><br>
                                    ${notification.message}<br>
                                    <small style="color:gray">${notification.timestamp}</small>
                                    <button 
                                        onclick="event.stopPropagation(); markAsRead(${notification.notificationID}, this.parentElement)"
                                        style="display: block; margin-top: 5px; padding: 3px 8px; background: #f0f0f0; border: none; cursor: pointer; border-radius: 4px;"
                                    >
                                        تم القراءة
                                    </button>
                                `;
                                notificationList.appendChild(li);
                            });

                            notificationCount.textContent = data.length;
                            notificationCount.style.display = 'flex';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching notifications:', error);
                        notificationList.innerHTML = '<li>خطأ في تحميل الإشعارات</li>';
                    });
            }

            window.markAsRead = function(notificationID, element) {
                fetch('notifications.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=markRead&notificationID=${notificationID}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data === true) {
                        element.remove();
                        
                        const remainingNotifications = notificationList.querySelectorAll('li').length - 
                            notificationList.querySelectorAll('li:empty').length;
                        
                        if (remainingNotifications <= 0 || notificationList.innerHTML.includes('لا توجد إشعارات')) {
                            notificationList.innerHTML = '<li>لا توجد إشعارات جديدة</li>';
                            notificationCount.style.display = 'none';
                        } else {
                            notificationCount.textContent = remainingNotifications;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error marking notification as read:', error);
                });
            };

            // Check for new notifications on page load
            fetchNotifications();
            
            // Periodically check for new notifications (every 30 seconds)
            setInterval(fetchNotifications, 30000);
        });
    </script>
</body>
</html>