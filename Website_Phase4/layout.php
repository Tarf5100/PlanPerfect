<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';

if (!isset($_SESSION['userID'])) {
    header("Location: log in.html");
    exit;
}

$userID = $_SESSION['userID'];
$stmt = $conn->prepare("SELECT username, email, phone, password FROM user WHERE userID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$username = $user['username'];
$email = $user['email'];
$phone = $user['phone'];
$password = $user['password'];
$role = $_SESSION['role'];

?>

<link rel="stylesheet" href="styles.css">
<!-- Header -->
<header>
  <div class="header-container">
    <div class="logo-container">
      <a href="CustomerHomepage.php">
        <img src="images/logo.png" alt="الرئيسية" class="logo">
      </a>
      <h1>PlanPerfect</h1>
    </div>
     <div class="icons-container">
                <a href="PreviousOrders.php">
                    <img src="images/pre.png" alt="الطلبات السابقة" class="pre-icon" style="width: 30px; height: 30px;">
                </a>
                <a href="Favorite.php">
                    <img src="images/fav.png" alt="المفضلة" class="fav-icon" style="width: 30px; height: 30px;">
                </a>
                <a href="cart.php">
                    <img src="images/cart.png" alt="عربة التسوق" class="cart-icon" style="width: 30px; height: 30px;">
                </a>

                <div class="profile-container" id="profileButton">
                    <img src="images/profile.png" alt="البروفايل" class="profile-icon" style="width: 30px; height: 30px;">
                </div>
            </div>
  </div>
</header>

<!-- Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <button class="sidebar-close" id="sidebarClose">×</button>
    <img src="images/profile.png" class="profile-pic" alt="صورة الملف الشخصي">
    <h2 class="user-name"><?= htmlspecialchars($username) ?></h2>
    <p class="user-email"><?= htmlspecialchars($email) ?></p>
  </div>
  <div class="profile-form">
    <form id="profileForm">
        <div class="form-group">
            <label for="userName">الاسم</label>
            <input type="text" id="userName" name="username" class="form-control" value="<?= htmlspecialchars($username) ?>">
          </div>

          <div class="form-group">
            <label for="userEmail">البريد الإلكتروني</label>
            <input type="email" id="userEmail" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>">
          </div>

          <div class="form-group">
            <label for="userPhone">رقم الجوال</label>
            <input type="text" id="userPhone" name="phone" class="form-control" value="<?= htmlspecialchars($phone) ?>">
          </div>

          <div class="form-group">
            <label for="userPassword">كلمة المرور</label>
            <input type="password" id="userPassword" name="password" class="form-control" placeholder="********" autocomplete="new-password">
          </div>

      <button type="submit" class="save-button">حفظ التغييرات</button>
      <button type="button" class="logout-button" id="logoutButton">تسجيل الخروج</button>
        <?php if ($role === 'vendor'): ?>
        <button type="button" class="vendor-home-button" onclick="window.location.href='VenderHomepage.php'">العودة إلى الصفحة الرئيسية للبائع</button>
        <?php endif; ?>
    </form>

  </div>
</div>


<!-- Sidebar Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebarOverlay');
  const openBtn = document.getElementById('profileButton');
  const closeBtn = document.getElementById('sidebarClose');
  const logoutBtn = document.getElementById('logoutButton');

  openBtn.addEventListener('click', () => {
    sidebar.classList.add('open');
    overlay.style.display = 'block';
  });

  closeBtn.addEventListener('click', () => {
    sidebar.classList.remove('open');
    overlay.style.display = 'none';
  });

  overlay.addEventListener('click', () => {
    sidebar.classList.remove('open');
    overlay.style.display = 'none';
  });

  logoutBtn.addEventListener('click', () => {
    if (confirm("هل أنت متأكد من تسجيل الخروج؟")) {
      window.location.href = "log in.html";
    }
  });

  document.getElementById('profileForm').addEventListener('submit', function(e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch('updateProfile.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    alert(data.message);
    if (data.status === "success") {
      sidebar.classList.remove('open');
      overlay.style.display = 'none';
    }
  })
  .catch(err => {
    console.error("Update error:", err);
    alert("حدث خطأ أثناء التحديث");
  });
});

});
</script>
