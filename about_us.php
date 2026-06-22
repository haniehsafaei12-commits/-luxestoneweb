<?php
session_start();
include 'connection.php';

$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>About Us | LuxeStone</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0A0A0A 0%, #1A120B 50%, #0D0D1A 100%);
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Rotating diamond background */
        .bg-diamond {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            overflow: hidden;
        }

        .bg-diamond::before {
            content: '💎';
            position: absolute;
            font-size: 400px;
            opacity: 0.03;
            bottom: -100px;
            right: -100px;
            animation: rotateSlow 40s linear infinite;
        }

        .bg-diamond::after {
            content: '✨';
            position: absolute;
            font-size: 250px;
            opacity: 0.02;
            top: -80px;
            left: -80px;
            animation: rotateSlowReverse 35s linear infinite;
        }

        @keyframes rotateSlow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes rotateSlowReverse {
            from { transform: rotate(0deg); }
            to { transform: rotate(-360deg); }
        }

        /* Gold particles */
        .gold-particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: radial-gradient(circle, #FFD700, #C6A43F);
            border-radius: 50%;
            opacity: 0;
            box-shadow: 0 0 8px #C6A43F;
            animation: floatParticle 12s ease-in-out infinite;
        }

        @keyframes floatParticle {
            0% {
                opacity: 0;
                transform: translateY(100vh) scale(0);
            }
            20% {
                opacity: 0.6;
            }
            80% {
                opacity: 0.3;
            }
            100% {
                opacity: 0;
                transform: translateY(-100vh) scale(1);
            }
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Glass header */
        .glass-header {
            background: rgba(10, 10, 10, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(198, 164, 63, 0.35);
            border-radius: 70px;
            padding: 18px 35px;
            margin: 20px 0 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 30px;
            font-weight: 800;
            background: linear-gradient(135deg, #C6A43F, #FFD700, #FFF8DC, #C6A43F);
            background-size: 300% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: logoShine 4s ease infinite;
            text-decor

ation: none;
        }

        @keyframes logoShine {
            0% { background-position: 0% center; }
            100% { background-position: 200% center; }
        }

        .nav-links {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: #CCCCCC;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        .nav-links a:hover {
            color: #C6A43F;
        }

        /* Hero section */
        .about-hero {
            text-align: center;
            margin-bottom: 50px;
            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .about-hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            background: linear-gradient(135deg, #C6A43F, #FFD700);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 15px;
        }

        .about-hero p {
            color: #AAAAAA;
            font-size: 16px;
        }

        /* About card */
        .about-card {
            background: rgba(20, 20, 30, 0.6);
            backdrop-filter: blur(15px);
            border-radius: 32px;
            border: 1px solid rgba(198, 164, 63, 0.3);
            overflow: hidden;
            animation: fadeInUp 0.6s ease;
            transition: all 0.3s;
            margin-bottom: 40px;
        }

        .about-card:hover {
            transform: translateY(-5px);
            border-color: rgba(198,164,63,0.6);
        }

        .card-header {
            background: linear-gradient(135deg, rgba(198,164,63,0.15), rgba(198,164,63,0.05));
            padding: 22px 28px;
            border-bottom: 1px solid rgba(198,164,63,0.2);
        }

        .card-header h2 {
            color: #C6A43F;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-body {
            padding: 35px;
        }

        /* Welcome message */
        .welcome-message {
            font-size: 22px;
            color: #C6A43F;
            margin-bottom: 25px;
            font-family: 'Playfair Display', serif;
            border-left: 3px solid #C6A43F;
            padding-left: 20px;
        }

        .welcome-message span {
            color: #FFD700;
        }

        .about-text {
            color: #CCCCCC;
            line-height: 1.8;
            margin-bottom: 20px;
            font-size: 15px;
        }

        .signature {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(198,164,63,0.2);
            font-style: italic;
            color: #C6A43F;
            font-family: 'Playfair Display', serif;
            font-size: 18px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 30px;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            background: rgba(0,0,0,0.3);
            border-radius: 20px;
            transition: 0.3s;
        }

        .stat-item:hover {
            background: rgba(198,164,63,0.1);
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 32px;
            font-weight: 800;
            color: #C6A43F;
        }

        .stat-label {
            font-size: 12px;
            color: #888;
            margin-top: 8px;
        }

        .footer {
            text-align: center;
            padding: 40px;
            color: #666;
            border-top: 1px solid rgba(198,164,63,0.2);
            margin-top: 60px;
        }

        @media (max-width: 768px) {
            .gl

ass-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            .about-hero h1 {
                font-size: 36px;
            }
            .welcome-message {
                font-size: 18px;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .card-body {
                padding: 25px;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="bg-diamond"></div>
<div class="gold-particles" id="particles"></div>

<div class="glass-header">
    <a href="products.php" class="logo">💎 LUXESTONE</a>
    <div class="nav-links">
        <a href="products.php">SHOP</a>
        <a href="cart.php">CART</a>
        <a href="order_details.php">ORDERS</a>
        <a href="about_us.php" style="color:#C6A43F;">ABOUT US</a>
        <?php if(isset($_SESSION['user_name']) || isset($_SESSION['username'])): ?>
            <a href="logout.php">LOGOUT</a>
        <?php else: ?>
            <a href="login.php">LOGIN</a>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <div class="about-hero">
        <h1><i class="fas fa-gem"></i> About LuxeStone</h1>
        <p>Discover the story behind our passion for luxury jewelry</p>
    </div>

    <div class="about-card">
        <div class="card-header">
            <h2><i class="fas fa-crown"></i> Our Story</h2>
        </div>
        <div class="card-body">
            <div class="welcome-message">
                Dear <span><?php echo htmlspecialchars($user_name); ?></span>,
            </div>
            
            <p class="about-text">
                Welcome to LuxeStone, where timeless elegance meets exceptional craftsmanship. 
                Founded with a passion for the world's finest gemstones and precious metals, 
                LuxeStone has been curating an exclusive collection of luxury jewelry for 
                discerning clients around the globe.
            </p>
            
            <p class="about-text">
                Our journey began with a simple belief: that every piece of jewelry tells a story. 
                Whether it's a diamond engagement ring symbolizing eternal love, a pair of emerald 
                earrings passed down through generations, or a modern rose gold bracelet marking 
                a personal milestone — we believe in the power of jewelry to capture life's most 
                precious moments.
            </p>
            
            <p class="about-text">
                At LuxeStone, we work directly with the world's most renowned brands including 
                Tiffany & Co., Cartier, Bvlgari, Chopard, and Van Cleef & Arpels. Every piece in 
                our collection is authenticated and sourced ethically, ensuring that you receive 
                nothing but the highest quality.
            </p>
            
            <p class="about-text">
                Our team of jewelry experts is dedicated to helping you find the perfect piece — 
                whether you're searching for a timeless classic or a unique statement piece. 
                We invite you to explore our collection and experience the LuxeStone difference.
            </p>
            
            <div class="signature">
                <i class="fas fa-gem"></i> The LuxeStone Team
            </div>
        </div>
    </div>

    <div class="about-card">
        <div class="card-header">
            <h2><i class="fas fa-chart-line"></i> LuxeStone by Numbers</h2>
        </div>
        <div class="card-body">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Exclusive Pieces</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">25+</div>

<div class="stat-label">Luxury Brands</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">10,000+</div>
                    <div class="stat-label">Happy Customers</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Expert Support</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="footer">
    <p>© 2026 LuxeStone - Timeless Elegance, Brilliant Moments</p>
</div>

<script>
    function createParticles() {
        const container = document.getElementById('particles');
        for (let i = 0; i < 80; i++) {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 12 + 's';
            particle.style.animationDuration = 6 + Math.random() * 10 + 's';
            particle.style.width = (Math.random() * 6 + 3) + 'px';
            particle.style.height = (Math.random() * 6 + 3) + 'px';
            container.appendChild(particle);
        }
    }
    createParticles();
</script>
</body>
</html>