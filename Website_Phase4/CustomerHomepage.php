<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include 'db.php';

if (!isset($_SESSION['userID'])) {
    header("Location: log in.html");
    exit;
}

$userID = $_SESSION['userID'];
$stmt = $conn->prepare("SELECT username, email FROM user WHERE userID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$username = $user['username'];
$email = $user['email'];

include 'layout.php';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>PlanPerfect - الصفحة الرئيسية</title>
  <style>
    main {
      max-width: 1000px;
      margin: auto;
      padding: 20px;
    }

    .section-container {
      background: #fff;
      border-radius: 12px;
      padding: 25px;
      margin-bottom: 30px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }

    .section-title {
      font-size: 24px;
      margin-bottom: 20px;
      text-align: center;
      color: #2c3e50;
    }

    .category-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr); 
        gap: 15px;
        justify-items: center;
        padding: 10px 20px;
    }
    
    .category-grid button {
        padding: 10px 20px;
        height:45px;
        width:190px;
        font-size: 19px;
        font-family: 'Segoe UI', sans-serif;
        color: #2c3e50;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: 0.3s;
      }
      
      #eventsContainer button{
          background-color: #309dc2;
      }
      
      #eventsContainer button:hover {
            background-color: #23748f;
      }
      
      #servicesContainer button{
            background-color: #c3e1eb;
      }
      
      #servicesContainer button:hover {
            background-color: #9bbac4;
      }
      
    .category-card {
      background: #ecf0f1;
      text-align: center;
      padding: 15px;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .category-card:hover {
      background-color: #dce3e6;
      transform: scale(1.05);
    }

    .browse-stores {
      display: block;
      width: fit-content;
      margin: 30px auto;
      padding: 12px 25px;
      background-color: #c0b7ae;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 18px;
      font-family: 'Segoe UI', sans-serif;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .browse-stores:hover {
      background-color: #937e6c;
      transform: scale(1.05);
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

  <div class="section-container">
    <h3 class="section-title">اختر نوع الحدث الخاص بك</h3>
    <div class="category-grid" id="eventsContainer"></div>
  </div>

  <div class="section-container">
    <h3 class="section-title">الخدمات المتاحة</h3>
    <div class="category-grid" id="servicesContainer"></div>
  </div>

  <button class="browse-stores" onclick="window.location.href='Services.php'">تصفح جميع الخدمات</button>
</main>

<footer>
    <div class="footer-content">
        <p>جميع الحقوق محفوظة © PlanPerfect 2025</p>
        <p>للتواصل: <a href="mailto:info@planperfect.com">info@planperfect.com</a></p>
        <p><a href="#">شروط الاستخدام</a> | <a href="#">سياسة الخصوصية</a></p>
    </div>
</footer>


<script>
function navigateToServices(serviceType) {
    window.location.href = `Services.php?eventType=all&serviceType=${encodeURIComponent(serviceType)}`;
  }

  function navigateToProducts(eventType) {
    window.location.href = `Services.php?eventType=${encodeURIComponent(eventType)}&serviceType=all`;
  }

  fetch("get_categories.php")
    .then(res => res.json())
    .then(data => {
      const eventsContainer = document.getElementById("eventsContainer");
      const servicesContainer = document.getElementById("servicesContainer");

      data.forEach(category => {
        const div = document.createElement("button");
        div.textContent = category.categoryname;
        div.className = "category-card";
        div.onclick = () => {
          if (category.categorytype === "event") {
            navigateToProducts(category.categoryname);
          } else {
            navigateToServices(category.categoryname);
          }
        };

        if (category.categorytype === "event") {
          eventsContainer.appendChild(div);
        } else {
          servicesContainer.appendChild(div);
        }
      });
    })
    .catch(err => {
      console.error("Error fetching categories:", err);
    });
</script>

</body>
</html>
