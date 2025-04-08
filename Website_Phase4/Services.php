<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'layout.php';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanPerfect - الخدمات</title>
    <style>
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .product-card {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            position: relative;
        }

            .product-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }

        .product-image {
            height: 180px;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-info {
            padding: 15px;
        }

        .vendor-name {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .product-name {
            margin: 0 0 10px 0;
            font-size: 18px;
        }

        .product-description {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
            height: 80px;
            overflow: hidden;
        }

        .product-price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }

        .product-price {
            font-weight: bold;
            font-size: 18px;
            color: #333;
        }

        .add-to-cart {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }

            .add-to-cart:hover {
                background-color: #c0392b;
            }

        .category-tags {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        .tag {
            color: white;
            font-size: 12px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 3px 8px;
            border-radius: 12px;
            display: inline-block;
            margin: 2px;
        }

        .event-tag {
            background-color: #3498db; 
        }

        .service-tag {
            background-color: #2ecc71; 
        }
        .highlighted {
            font-weight: bold;
            box-shadow: 0 0 3px rgba(0,0,0,0.3);
        }
        .inactive {
            background-color: #95a5a6; 
            color: white;
            opacity: 0.7;
        }

        .no-results {
            grid-column: 1 / -1;
            text-align: center;
            padding: 50px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .main-nav {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .nav-list {
            display: flex;
            list-style-type: none;
            padding: 0;
            margin: 0;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .nav-item {
            margin: 0;
        }

        .nav-link {
            display: block;
            padding: 15px 20px;
            color: #2c3e50;
            text-decoration: none;
            transition: all 0.3s;
            font-weight: bold;
        }

        .nav-link:hover, .nav-link.active {
            background-color: #ecf0f1;
            color: #e74c3c;
        }


        /* Cart shortcut button */
        .cart-shortcut {
            position: fixed;
            bottom: 30px;
            left: 30px;
            width: 60px;
            height: 60px;
            background-color: #e74c3c;
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            cursor: pointer;
            z-index: 100;
            transition: transform 0.3s, background-color 0.3s;
        }

        .cart-shortcut:hover {
            transform: scale(1.1);
            background-color: #c0392b;
        }

        .cart-shortcut-icon {
            font-size: 24px;
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #3498db;
            color: white;
            font-size: 12px;
            min-width: 20px;
            height: 20px;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            padding: 0 5px;
        }
        .empty-cart-message {
            padding: 20px;
            text-align: center;
            color: #7f8c8d;
        }

        .add-confirmation {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #2ecc71;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            animation: slideDown 0.3s forwards;
            opacity: 1;
            transition: opacity 0.3s;
        }

        .add-confirmation.hide {
            opacity: 0;
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
    <div class="container">
        <br>
        <br>
        <div style="display: flex; justify-content: space-between; align-items: center; margin: 20px;">
    <h2 class="section-title" id="servicesTitle" style="margin: 0;">الخدمات المتاحة</h2>
    <button onclick="window.location.href='CustomerHomepage.php'" style="
        padding: 8px 16px;
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    ">
        ← العودة للرئيسية
    </button>
</div>
        <form method="GET" style="margin-bottom: 20px; text-align: center;">
            <label for="maxPrice">البحث حسب السعر:</label>
            <input type="number" name="maxPrice" id="maxPrice" placeholder="أدخل الحد الأقصى للسعر" min="0" style="padding: 8px; margin: 0 10px;">
            <button type="submit" style="padding: 8px 12px;">بحث</button>
        </form>

        <br>
        <div class="products-grid" id="productsGrid">
            <!-- Product cards will be dynamically generated here -->
        </div>
    </div>

    <!-- Cart shortcut button -->
    <a href="cart.php" class="cart-shortcut" id="cartShortcut">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="cart-shortcut-icon">
            <path d="M9 20a1 1 0 1 0 0 2 1 1 0 1 0 0-2z"></path>
            <path d="M20 20a1 1 0 1 0 0 2 1 1 0 1 0 0-2z"></path>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
        </svg>
        <span class="cart-badge" id="cartShortcutBadge">0</span>
    </a>
    
<footer>
    <div class="footer-content">
        <p>جميع الحقوق محفوظة © PlanPerfect 2025</p>
        <p>للتواصل: <a href="mailto:info@planperfect.com">info@planperfect.com</a></p>
        <p><a href="#">شروط الاستخدام</a> | <a href="#">سياسة الخصوصية</a></p>
    </div>
</footer>
    <script>
        let products = [];

        document.addEventListener('DOMContentLoaded', () => {
            const { eventType, serviceType, maxPrice } = getUrlParams();
            const query = `get_services_filtered.php?eventType=${eventType}&serviceType=${serviceType}&maxPrice=${maxPrice}`;

            fetch(query)
                .then(res => res.json())
                .then(data => {
                    products = data;
                    renderProducts();
                    updateFilterDisplay();
                    updateCartBadge();
                })
                .catch(error => {
                    console.error("Error loading services:", error);
                });
        });

        function getUrlParams() {
            const params = new URLSearchParams(window.location.search);
            return {
                eventType: params.get('eventType') || 'all',
                serviceType: params.get('serviceType') || 'all',
                maxPrice: params.get('maxPrice') || ''
            };
        }


        function renderProducts() {
            const productsGrid = document.getElementById('productsGrid');
            productsGrid.innerHTML = '';

            const { eventType, serviceType } = getUrlParams();
            let visibleProducts = 0;

            products.forEach(product => {
                const productCard = document.createElement('div');
                productCard.className = 'product-card';

                const eventMatch = eventType === 'all' || product.eventTypes.includes(eventType);
                const serviceMatch = serviceType === 'all' || product.serviceTypes.includes(serviceType);

                if (!eventMatch || !serviceMatch) {
                    productCard.classList.add('hide-product');
                } else {
                    visibleProducts++;
                }

                const categoryTagsHtml = product.eventTypes.map(type => {
                    let classes = 'tag event-tag';
                    if (eventType !== 'all') {
                        classes += type === eventType ? ' highlighted' : ' inactive';
                    }
                    return `<span class="${classes}">${type}</span>`;
                }).join('');

                const serviceTagsHtml = product.serviceTypes.map(type => {
                    let classes = 'tag service-tag';
                    if (serviceType !== 'all') {
                        classes += type === serviceType ? ' highlighted' : ' inactive';
                    }
                    return `<span class="${classes}">${type}</span>`;
                }).join('');

                productCard.innerHTML = `
                    <div class="category-tags">
                        ${categoryTagsHtml}
                        ${serviceTagsHtml}
                    </div>
                    <div class="product-image">
                        <img src="${product.imageURL || 'images/placeholder.png'}" alt="${product.serviceName}">
                    </div>
                    <div class="product-info">
                        <div class="vendor-name">${product.businessname}</div>
                            <div style="margin: 8px 0;">
                            <button onclick="window.location.href='service_reviews.php?serviceID=${product.serviceID}'" style="background: none; border: none; color: #f1c40f; font-weight: bold; font-size: 14px; cursor: pointer;">
                                 ${product.avgRating ? `✮ ${product.avgRating} / 5` : '✮ لا يوجد تقييم'}
                            </button>
                            </div>

                        <h3 class="product-name">${product.serviceName}</h3>
                        <div class="product-description">${product.description || 'لا يوجد وصف'}</div>
                        <div class="product-price-row">
                            <div class="product-price">${Number(product.price).toFixed(2)} ريال</div>
                            <button class="favorite-btn" onclick="addToFavorites(this)" data-id="${product.serviceID}" style="background: none; border: none; cursor: pointer;"><img src="images/emptyHeart.png" alt="إضافة إلى المفضلة" style="width:24px;height:24px;"></button>
                            <button onclick="addToCart(${product.serviceID}, '${product.serviceName}', '${product.businessname}', ${product.price})" class="add-to-cart" style="background: none; border: none; cursor: pointer;"><img src="images/add-to-cart.png" alt="إضافة إلى السلة" style="width:24px;height:24px;"></button>
                        </div>
                    </div>
                `;

                productsGrid.appendChild(productCard);
            });

            if (visibleProducts === 0) {
                const noResults = document.createElement('div');
                noResults.className = 'no-results';
                noResults.innerHTML = '<h3>لا توجد نتائج مطابقة للتصفية الحالية</h3><p>يرجى تغيير معايير التصفية للعثور على خدمات مناسبة.</p>';
                productsGrid.appendChild(noResults);
            }
        }
        function addToCart(serviceID, serviceName, vendorName, price) {
            const formData = new FormData();
            formData.append('serviceID', serviceID);

            fetch('addToCart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    showAddConfirmation(`${serviceName} تمت إضافته إلى السلة`);
                    updateCartBadge(); 
                } else {
                    console.error("Add to cart failed:", data.message);
                }
            })
            .catch(err => {
                console.error("Add to cart error:", err);
            });
        }

        function showAddConfirmation(message) {
            const confirmBox = document.createElement("div");
            confirmBox.className = "add-confirmation";
            confirmBox.innerText = message;
            document.body.appendChild(confirmBox);
            setTimeout(() => {
                confirmBox.classList.add("hide");
                setTimeout(() => document.body.removeChild(confirmBox), 300);
            }, 1500);
        }

        function updateCartBadge() {
            fetch('getCartCount.php')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('cartShortcutBadge').textContent = data.count || 0;
                });
        }
        function addToFavorites(button) {
            const serviceID = button.getAttribute("data-id");

            fetch("addToFavorites.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `serviceID=${serviceID}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    showAddConfirmation("تمت الإضافة إلى المفضلة");
                } else if (data.status === "exists") {
                    showAddConfirmation("هذه الخدمة موجودة بالفعل في المفضلة");
                } else {
                    console.error("Favorite error:", data.message);
                }
            })
            .catch(error => {
                console.error("Add to favorites error:", error);
            });
        }


    </script>

</body>
</html>