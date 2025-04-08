<?php
include 'layout.php';
include 'db.php';

if (!isset($_GET['serviceID']) || !is_numeric($_GET['serviceID'])) {
    echo "<p style='text-align:center; color:red;'>لم يتم تحديد الخدمة بشكل صحيح.</p>";
    exit;
}

$serviceID = (int)$_GET['serviceID'];

// Get service name and overall rating
$serviceStmt = $conn->prepare("
    SELECT s.serviceName, 
           IFNULL(AVG(r.rating), 0) AS averageRating, 
           COUNT(r.reviewID) AS totalReviews
    FROM service s
    LEFT JOIN reviews r ON s.serviceID = r.serviceID
    WHERE s.serviceID = ?
    GROUP BY s.serviceID
");
$serviceStmt->bind_param("i", $serviceID);
$serviceStmt->execute();
$serviceResult = $serviceStmt->get_result();
$service = $serviceResult->fetch_assoc();

if (!$service) {
    echo "<p style='text-align:center; color:red;'>الخدمة غير موجودة.</p>";
    exit;
}

// Get individual reviews
$reviewsStmt = $conn->prepare("
    SELECT r.rating, r.comment, r.reviewDate, u.username 
    FROM reviews r
    JOIN user u ON r.userID = u.userID
    WHERE r.serviceID = ?
    ORDER BY r.reviewDate DESC
");
$reviewsStmt->bind_param("i", $serviceID);
$reviewsStmt->execute();
$reviews = $reviewsStmt->get_result();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقييمات الخدمة - <?= htmlspecialchars($service['serviceName']) ?></title>
    <style>
        .reviews-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }

        .review-card {
            background-color: #fdfdfd;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .review-rating {
            color: #f39c12;
            font-size: 20px;
        }

        .no-reviews {
            text-align: center;
            font-size: 16px;
            color: #888;
            margin-top: 40px;
        }

        .back-button {
            display: block;
            width: fit-content;
            margin: 30px auto 10px;
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            text-align: center;
            font-weight: bold;
        }

        .back-button:hover {
            background-color: #1f2e40;
        }

        .service-summary {
            text-align: center;
            margin-bottom: 30px;
        }

        .overall-rating {
            font-size: 24px;
            color: #f39c12;
            font-weight: bold;
        }
    </style>
</head>
<body>

<main class="reviews-container">
    <div class="service-summary">
        <h2>تقييمات الخدمة: <?= htmlspecialchars($service['serviceName']) ?></h2>
        <p class="overall-rating">التقييم العام: <?= number_format($service['averageRating'], 1) ?> ★ من 5 (<?= $service['totalReviews'] ?> تقييم)</p>
    </div>

    <?php if ($reviews->num_rows > 0): ?>
        <?php while ($review = $reviews->fetch_assoc()): ?>
            <div class="review-card">
                <div class="review-header">
                    <strong><?= htmlspecialchars($review['username']) ?></strong>
                    <span class="review-rating"><?= str_repeat('★', $review['rating']) ?></span>
                </div>
                <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                <small><?= $review['reviewDate'] ?></small>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-reviews">لا توجد تقييمات لهذه الخدمة بعد.</div>
    <?php endif; ?>

    <a href="Services.php" class="back-button">← العودة إلى الخدمات</a>
</main>

</body>
</html>
