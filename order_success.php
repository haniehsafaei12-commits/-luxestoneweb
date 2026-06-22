<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success | LuxeStone</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-x: hidden;
            position: relative;
        }

        /* انیمیشن پس‌زمینه */
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background: linear-gradient(135deg, #0A0A0A, #0D2B1A, #0A0A0A);
            background-size: 400% 400%;
            animation: gradientShift 10s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* الماس چرخان */
        .rotating-diamond {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 400px;
            opacity: 0.03;
            z-index: -1;
            animation: rotateDiamond 30s linear infinite;
            pointer-events: none;
        }

        @keyframes rotateDiamond {
            from { transform: translate(-50%, -50%) rotate(0deg); }
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }

        /* کنفتی (جشن) */
        .confetti {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1000;
        }

        .confetti-piece {
            position: absolute;
            width: 10px;
            height: 20px;
            background: linear-gradient(135deg, #C6A43F, #FFD700);
            opacity: 0;
            animation: confettiFall 4s ease-out forwards;
        }

        @keyframes confettiFall {
            0% {
                opacity: 1;
                transform: translateY(-100vh) rotate(0deg);
            }
            100% {
                opacity: 0;
                transform: translateY(100vh) rotate(720deg);
            }
        }

        /* ذرات سبز درخشان */
        .green-sparkles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 999;
        }

        .sparkle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: #00FF88;
            border-radius: 50%;
            box-shadow: 0 0 10px #00FF88, 0 0 20px #00FF88;
            opacity: 0;
            animation: sparkleGlow 2s ease-in-out infinite;
        }

        @keyframes sparkleGlow {
            0%, 100% {
                opacity: 0;
                transform: scale(0);
            }
            50% {
                opacity: 1;
                transform: scale(1.5);
            }
        }

        /* باکس اصلی موفقیت */
        .success-container {
            max-width: 600px;
            margin: 20px;
            animation: floatIn 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        @keyframes floatIn {
            0% {
                opacity: 0;
                transform: scale(0.3) translateY(-100px);
            }
            100% {
                opacity

: 1;
                transform: scale(1) translateY(0);
            }
        }

        .success-card {
            background: rgba(15, 25, 20, 0.8);
            backdrop-filter: blur(20px);
            border-radius: 48px;
            border: 2px solid rgba(0, 255, 136, 0.5);
            padding: 50px 40px;
            text-align: center;
            box-shadow: 0 0 40px rgba(0, 255, 136, 0.2), 0 20px 40px rgba(0,0,0,0.4);
            transition: all 0.5s;
            animation: borderGlow 2s ease-in-out infinite alternate;
        }

        @keyframes borderGlow {
            0% {
                border-color: rgba(0, 255, 136, 0.3);
                box-shadow: 0 0 20px rgba(0, 255, 136, 0.1);
            }
            100% {
                border-color: rgba(0, 255, 136, 0.9);
                box-shadow: 0 0 50px rgba(0, 255, 136, 0.4);
            }
        }

        .success-card:hover {
            transform: translateY(-10px) scale(1.02);
            border-color: #00FF88;
            box-shadow: 0 0 60px rgba(0, 255, 136, 0.5);
        }

        /* آیکون موفقیت */
        .icon-circle {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, rgba(0, 255, 136, 0.2), rgba(0, 255, 136, 0.05));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            border: 2px solid #00FF88;
            animation: iconPulse 1.5s ease-in-out infinite;
        }

        @keyframes iconPulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(0, 255, 136, 0.4);
            }
            50% {
                transform: scale(1.1);
                box-shadow: 0 0 0 20px rgba(0, 255, 136, 0);
            }
        }

        .icon-circle i {
            font-size: 60px;
            color: #00FF88;
            text-shadow: 0 0 20px #00FF88;
            animation: iconRotate 0.5s ease;
        }

        @keyframes iconRotate {
            from {
                transform: rotate(0deg) scale(0);
            }
            to {
                transform: rotate(360deg) scale(1);
            }
        }

        /* متن سبز درخشان */
        .success-message {
            font-size: 36px;
            font-weight: 800;
            background: linear-gradient(135deg, #00FF88, #00CC66, #00FF88);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: greenShimmer 2s ease infinite;
            margin-bottom: 20px;
            letter-spacing: 2px;
        }

        @keyframes greenShimmer {
            0% { background-position: 0% center; }
            100% { background-position: 200% center; }
        }

        .success-subtitle {
            color: #CCCCCC;
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .order-id-box {
            background: rgba(0, 255, 136, 0.1);
            border-radius: 20px;
            padding: 15px;
            margin: 25px 0;
            border: 1px solid rgba(0, 255, 136, 0.3);
        }

        .order-id-box p {
            color: #00FF88;
            font-size: 18px;
            font-weight: 600;
        }

        .order-id-box small {
            color: #888;
            font-size: 12px;
        }

        /* دکمه‌ها */
        .btn-group {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #00FF88, #00CC66);
            border: none;
            padding: 14px 35px;
            border-radius: 50px;
            color: #0A0A0A;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary:hover {

transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 255, 136, 0.4);
            letter-spacing: 1px;
        }

        .btn-secondary {
            background: transparent;
            border: 2px solid #00FF88;
            padding: 12px 35px;
            border-radius: 50px;
            color: #00FF88;
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s;
            display: inline-block;
        }

        .btn-secondary:hover {
            background: rgba(0, 255, 136, 0.1);
            transform: translateY(-3px);
        }

        /* ذرات درخشان اطراف */
        .glow-dots {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .glow-dot {
            position: absolute;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(0,255,136,0.1), transparent);
            border-radius: 50%;
            animation: glowDotMove 8s ease-in-out infinite;
        }

        @keyframes glowDotMove {
            0%, 100% {
                transform: translate(0, 0) scale(1);
                opacity: 0.3;
            }
            50% {
                transform: translate(50px, 30px) scale(1.2);
                opacity: 0.6;
            }
        }

        @media (max-width: 768px) {
            .success-message {
                font-size: 24px;
            }
            .success-card {
                padding: 30px 20px;
            }
            .icon-circle {
                width: 80px;
                height: 80px;
            }
            .icon-circle i {
                font-size: 40px;
            }
        }
    </style>
</head>
<body>
<div class="animated-bg"></div>
<div class="rotating-diamond">💎</div>
<div class="confetti" id="confetti"></div>
<div class="green-sparkles" id="sparkles"></div>
<div class="glow-dots">
    <div class="glow-dot" style="top: 10%; left: 5%;"></div>
    <div class="glow-dot" style="top: 70%; left: 85%;"></div>
    <div class="glow-dot" style="top: 40%; left: 15%;"></div>
    <div class="glow-dot" style="top: 80%; left: 30%;"></div>
</div>

<div class="success-container">
    <div class="success-card">
        <div class="icon-circle">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1 class="success-message">✓ ORDER CONFIRMED!</h1>
        <p class="success-subtitle">Your luxurious piece is on its way to you.<br>Thank you for choosing LuxeStone.</p>
        
        <?php if (isset($_GET['order_id'])): ?>
        <div class="order-id-box">
            <p>✨ Order #: <?php echo htmlspecialchars($_GET['order_id']); ?> ✨</p>
            <small>Save this order number for tracking</small>
        </div>
        <?php endif; ?>
        
        <div class="btn-group">
            <a href="products.php" class="btn-primary">
                <i class="fas fa-gem"></i> Continue Shopping
            </a>
            <a href="order_details.php" class="btn-secondary">
                <i class="fas fa-receipt"></i> View Orders
            </a>
        </div>
    </div>
</div>

<script>
    // ساخت کنفتی (جشن)
    function createConfetti() {
        const confettiContainer = document.getElementById('confetti');
        const colors = ['#C6A43F', '#FFD700', '#00FF88', '#FF6B6B', '#4ECDC4'];
        
        for (let i = 0; i < 150; i++) {
            const confetti = document.createElement('div');
            confetti.classList.add('confetti-piece');
            confetti.style.left = Math.random() * 100 + '%';
            confetti.style.animationDelay = Math.random() * 4 + 's';
            confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            confetti.style.width = (Math.random() * 8 + 4) + 'px';
            confetti.style.height = (Math.random() * 12 + 6) + 'px';
            confettiContainer.appendChild(confetti);
        }
    }
    
    // ساخت ذرات سبز درخشان
    function createSpark

les() {
        const sparklesContainer = document.getElementById('sparkles');
        
        for (let i = 0; i < 50; i++) {
            const sparkle = document.createElement('div');
            sparkle.classList.add('sparkle');
            sparkle.style.left = Math.random() * 100 + '%';
            sparkle.style.top = Math.random() * 100 + '%';
            sparkle.style.animationDelay = Math.random() * 3 + 's';
            sparkle.style.animationDuration = 1 + Math.random() * 2 + 's';
            sparklesContainer.appendChild(sparkle);
        }
    }
    
    createConfetti();
    createSparkles();
</script>
</body>
</html>