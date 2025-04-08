<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanPerfect - خطط مناسباتك بطريقة مثالية</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            margin: 0;
            padding: 0;
            background-image: url('https://images.unsplash.com/photo-1469371670807-013ccf25f16a?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.8), rgba(44, 62, 80, 0.9));
            z-index: -1;
        }
        
        .navbar {
            background: linear-gradient(135deg, #2c3e50 0%, #1a5276 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
            width: 100%;
        }
        
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
        }
        
        .logo {
            width: 80px;
            height: 40px;
            margin-left: 10px;
            fill: none;
            stroke: white;
            stroke-width: 2;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }
        
        .brand-name {
            font-size: 24px;
            font-weight: bold;
            color: white;
            margin: 0;
        }
        
        .tagline {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 2px;
        }
        
        .auth-buttons {
            display: flex;
            align-items: center;
        }
        
        .auth-buttons a {
            text-decoration: none;
            margin-right: 15px;
        }
        
        .login-btn {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.7);
            color: white;
            padding: 8px 20px;
            border-radius: 4px;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-block;
        }
        
        .login-btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: white;
        }
        
        .signup-btn {
            background-color: #3498db;
            color: white;
            padding: 8px 20px;
            border-radius: 4px;
            font-weight: 500;
            transition: background-color 0.3s;
            border: 1px solid #3498db;
            display: inline-block;
        }
        
        .signup-btn:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        
        .hero {
            flex: 1;
            display: flex;
            align-items: center;
            padding: 40px 0;
        }
        
        .hero-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .hero-content {
            flex: 1;
            color: white;
            padding-left: 20px;
        }
        
        .hero-image {
            flex: 1;
            text-align: center;
        }
        
        .hero-image img {
            max-width: 100%;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .hero-title {
            font-size: 3rem;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 30px;
            line-height: 1.4;
            opacity: 0.9;
        }
        
        .hero-cta {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: bold;
            text-decoration: none;
            transition: transform 0.3s, box-shadow 0.3s;
            margin-top: 20px;
        }
        
        .hero-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        .features {
            background-color: white;
            padding: 60px 0;
        }
        
        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 50px;
            color: #2c3e50;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }
        
        .feature-card {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
        }
        
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #3498db;
        }
        
        .feature-title {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        
        .feature-description {
            color: #7f8c8d;
            line-height: 1.6;
        }
        
        .reviews {
            background-color: #f8f9fa;
            padding: 60px 0;
        }
        
        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }
        
        .review-card {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: right;
        }
        
        .review-stars {
            color: #f1c40f;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        
        .review-text {
            color: #555;
            line-height: 1.6;
            margin-bottom: 15px;
            font-style: italic;
        }
        
        .reviewer {
            font-weight: bold;
            color: #2c3e50;
        }
        
        footer {
          text-align: center;
          padding: 20px;
          color: white;
          font-size: 14px;
          margin-top: 40px;
        }
        
        footer a{
            color: #937e5a;
        }
        @media (max-width: 768px) {
            .hero-container {
                flex-direction: column;
            }
            
            .hero-content {
                text-align: center;
                margin-bottom: 40px;
                padding-left: 0;
            }
            
            .hero-title {
                font-size: 2.2rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container nav-container">
            <div class="logo-container">
                <img class="logo" src="images/logo.png" viewBox="0 0 100 100" fill="none" stroke="white" stroke-width="2">
                    <circle cx="50" cy="50" r="40"></circle>
                    <path d="M30 50 L45 65 L70 35"></path>
                </svg>
                <div>
                    <div class="brand-name">PlanPerfect</div>
                    <div class="tagline">خطط مناسباتك بطريقة مثالية</div>
                </div>
            </div>
            
            <div class="auth-buttons">
                <a href="log in.html" class="login-btn">تسجيل الدخول</a>
                <a href="sign up.html" class="signup-btn">إنشاء حساب</a>
            </div>
        </div>
    </nav>
    
    <section class="hero">
        <div class="container hero-container">
            <div class="hero-content">
                <h1 class="hero-title">خطط مناسباتك بطريقة مثالية</h1>
                <p class="hero-subtitle">منصة متكاملة تساعدك على تنظيم أفضل المناسبات والحفلات بكل سهولة ويسر</p>
                <a href="log in.html" class="hero-cta">ابدأ الآن</a>
            </div>
            
            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?q=80&w=2069&auto=format&fit=crop" alt="تخطيط الحفلات والمناسبات" style="max-width: 100%; border-radius: 10px; box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);">
            </div>
        </div>
    </section>
    
    <section class="features">
        <div class="container">
            <h2 class="section-title">مميزات خدماتنا</h2>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🎉</div>
                    <h3 class="feature-title">تنوع الخدمات</h3>
                    <p class="feature-description">قاعات، منظمي حفلات، تصوير، ضيافة، وكل ما تحتاجه لمناسبتك</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">💰</div>
                    <h3 class="feature-title">أسعار مناسبة</h3>
                    <p class="feature-description">مقارنة أسعار مزودي الخدمات واختيار ما يناسب ميزانيتك</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">⭐</div>
                    <h3 class="feature-title">مزودين موثوقين</h3>
                    <p class="feature-description">نختار لك أفضل مزودي الخدمات المعتمدين مع تقييمات حقيقية</p>
                </div>
            </div>
        </div>
    </section>
    
    <section class="reviews">
        <div class="container">
            <h2 class="section-title">آراء العملاء</h2>
            
            <div class="reviews-grid">
                <div class="review-card">
                    <div class="review-stars">★★★★★</div>
                    <p class="review-text">"استخدمت PlanPerfect لتنظيم حفل زفافي، وكانت التجربة رائعة! وفرت الكثير من الوقت والجهد، ووجدت أفضل مزودي الخدمات بأسعار مناسبة."</p>
                    <div class="reviewer">سارة محمد</div>
                </div>
                
                <div class="review-card">
                    <div class="review-stars">★★★★★</div>
                    <p class="review-text">"منصة متميزة ساعدتني في تنظيم حفل تخرج ابني بكل سهولة. التواصل مع مزودي الخدمات كان مباشر وسريع، والنتيجة كانت أكثر من رائعة!"</p>
                    <div class="reviewer">فهد العتيبي</div>
                </div>
                
                <div class="review-card">
                    <div class="review-stars">★★★★★</div>
                    <p class="review-text">"أنا مزود خدمة ضيافة، وانضمامي لـ PlanPerfect ساعدني في الوصول لعملاء جدد وزيادة مبيعاتي بشكل ملحوظ. منصة احترافية بكل المقاييس!"</p>
                    <div class="reviewer">خالد السالم</div>
                </div>
            </div>
        </div>
    </section>
    
<footer>
    <div class="footer-content">
        <p>جميع الحقوق محفوظة © PlanPerfect 2025</p>
        <p>للتواصل: <a href="mailto:info@planperfect.com">info@planperfect.com</a></p>
        <p><a href="#">شروط الاستخدام</a> | <a href="#">سياسة الخصوصية</a></p>
    </div>
</footer>
    
</body>
</html>