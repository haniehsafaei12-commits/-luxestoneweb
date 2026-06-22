<?php
session_start();
include 'connection.php';

if (empty($_SESSION['cart'])) {
    header("Location: products.php");
    exit();
}

$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    foreach ($_SESSION['cart'] as $item) {
        $product_id = $item['id'];
        $quantity = $item['quantity'];
        mysqli_query($conn, "UPDATE products_jewellery SET stock = stock - $quantity WHERE id = $product_id");
    }
    
    foreach ($_SESSION['cart'] as $item) {
        $product_id = $item['id'];
        $product_name = mysqli_real_escape_string($conn, $item['name']);
        $price = $item['price'];
        $date = date('Y-m-d');
        
        $query = "INSERT INTO orders (fullname, product_id, product_name, phone, address, price, date) 
                  VALUES ('$fullname', '$product_id', '$product_name', '$phone', '$address', '$price', '$date')";
        mysqli_query($conn, $query);
    }
    
    $_SESSION['cart'] = [];
    
    echo "<script>window.location.href='/luxestone/order_success.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | LuxeStone</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #0A0A0A;
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            background: radial-gradient(circle, #C6A43F, transparent);
            border-radius: 50%;
            opacity: 0;
            animation: floatParticle 8s ease-in-out infinite;
        }

        @keyframes floatParticle {
            0% {
                opacity: 0;
                transform: translateY(100vh) scale(0);
            }
            10% {
                opacity: 0.5;
            }
            90% {
                opacity: 0.3;
            }
            100% {
                opacity: 0;
                transform: translateY(-100vh) scale(1);
            }
        }

        .rotating-diamond {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 400px;
            opacity: 0.02;
            z-index: -1;
            animation: rotateDiamond 40s linear infinite;
            pointer-events: none;
        }

        @keyframes rotateDiamond {
            from { transform: translate(-50%, -50%) rotate(0deg); }
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }

        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background: linear-gradient(270deg, #0A0A0A, #1A120B, #0D0D1A, #1A0A2E, #2D1B3A, #0D0D1A, #1A120B, #0A0A0A);
            background-size: 500% 500%;
            animation: gradientShift 12s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            25% { background-position: 50% 50%; }
            50% { background

-position: 100% 50%; }
            75% { background-position: 50% 50%; }
            100% { background-position: 0% 50%; }
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
            position: relative;
            z-index: 1;
        }

        .header {
            background: rgba(10, 10, 10, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(198, 164, 63, 0.3);
            padding: 20px 40px;
            margin: 20px 30px 50px;
            border-radius: 80px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            transform-style: preserve-3d;
            transform: perspective(1000px) translateZ(10px);
        }

        .header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            padding: 0;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 800;
            background: linear-gradient(135deg, #C6A43F, #FFD700, #C6A43F);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
            animation: shimmer 3s ease infinite;
        }

        @keyframes shimmer {
            0% { background-position: 0% center; }
            100% { background-position: 200% center; }
        }

        .nav-links a {
            color: #CCCCCC;
            text-decoration: none;
            margin-left: 30px;
            transition: 0.3s;
            font-weight: 500;
            position: relative;
        }

        .nav-links a::before {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #C6A43F, #FFD700);
            transition: 0.3s;
        }

        .nav-links a:hover::before {
            width: 100%;
        }

        .nav-links a:hover {
            color: #C6A43F;
        }

        .hero-checkout {
            text-align: center;
            margin-bottom: 60px;
            perspective: 1000px;
        }

        .hero-checkout h1 {
            font-family: 'Playfair Display', serif;
            font-size: 64px;
            font-weight: 800;
            background: linear-gradient(135deg, #C6A43F, #FFD700, #FFF8DC, #C6A43F);
            background-size: 300% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 4s ease infinite;
            letter-spacing: 2px;
        }

        .hero-checkout p {
            color: #999;
            font-size: 18px;
            margin-top: 15px;
            letter-spacing: 1px;
        }

        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 40px;
            perspective: 1000px;
        }

        .form-card, .order-summary {
            background: rgba(15, 15, 25, 0.7);
            backdrop-filter: blur(15px);
            border-radius: 32px;
            border: 1px solid rgba(198, 164, 63, 0.3);
            overflow: hidden;
            transition: all 0.5s;
            transform-style: preserve-3d;
            animation: cardFloat 0.8s ease-out;
        }

        @keyframes cardFloat {
            from {
                opacity: 0;
                transform: translateY(50px) rotateX(-10deg);
            }
            to {
                opacity: 1;
                transform: translateY(0) rotateX(0);
            }
        }

        .form-card:hover, .order-summary:hover {
            transform: translateY(-10px) scale(1.02);
            border-color: #C6A43F;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4), 0 0 30px rgba(198,164,63,0.2);
        }

        .card-header {
            background: linear-gradient(135deg, rgba(198,164,63,0.2), rgba(198,164,63,0.05));
            padding

: 25px 30px;
            border-bottom: 1px solid rgba(198,164,63,0.3);
        }

        .card-header h2 {
            color: #C6A43F;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-header h2 i {
            font-size: 28px;
            animation: iconPulse 2s ease infinite;
        }

        @keyframes iconPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* Sticker for Shipping Details */
        .shipping-sticker {
            position: absolute;
            top: -15px;
            right: -15px;
            background: linear-gradient(135deg, #C6A43F, #FFD700);
            color: #0A0A0A;
            padding: 8px 20px;
            border-radius: 40px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            animation: stickerBounce 2s ease infinite;
            z-index: 10;
        }

        @keyframes stickerBounce {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-5px) rotate(3deg); }
        }

        .form-card {
            position: relative;
            overflow: visible;
        }

        .card-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: #DDD;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .form-group label i {
            color: #C6A43F;
            margin-right: 8px;
        }

        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 16px 18px;
            background: rgba(0,0,0,0.6);
            border: 1px solid rgba(198,164,63,0.3);
            border-radius: 20px;
            color: white;
            font-size: 15px;
            transition: all 0.3s;
        }

        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            outline: none;
            border-color: #C6A43F;
            box-shadow: 0 0 15px rgba(198,164,63,0.3);
            background: rgba(0,0,0,0.8);
        }

        .order-items {
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 25px;
            padding-right: 10px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            transition: 0.3s;
        }

        .order-item:hover {
            background: rgba(198,164,63,0.05);
            transform: translateX(5px);
        }

        .order-item-info {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .order-item-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, rgba(198,164,63,0.2), rgba(198,164,63,0.05));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            transition: 0.3s;
        }

        .order-item:hover .order-item-icon {
            transform: scale(1.1);
            background: rgba(198,164,63,0.3);
        }

        .order-item-name {
            font-weight: 600;
            color: white;
        }

        .order-item-name small {
            font-size: 11px;
            color: #C6A43F;
            display: block;
            margin-top: 3px;
        }

        .order-item-price {
            color: #C6A43F;
            font-weight: 700;
            font-size: 18px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px 0 15px;

border-top: 2px solid rgba(198,164,63,0.4);
            margin-top: 10px;
        }

        .total-row span:first-child {
            color: #C6A43F;
            font-size: 20px;
            font-weight: 700;
        }

        .total-row span:last-child {
            font-size: 36px;
            font-weight: 900;
            background: linear-gradient(135deg, #C6A43F, #FFD700);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: pricePulse 2s ease infinite;
        }

        @keyframes pricePulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }

        .btn-submit {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #C6A43F, #FFD700);
            border: none;
            border-radius: 60px;
            color: #0A0A0A;
            font-size: 18px;
            font-weight: 800;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 20px;
            position: relative;
            overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: 0.5s;
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .btn-submit:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(198,164,63,0.5);
            letter-spacing: 2px;
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            width: 100%;
            color: #888;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
        }

        .btn-back:hover {
            color: #C6A43F;
            transform: translateX(-5px);
        }

        ::-webkit-scrollbar {
            width: 10px;
        }
        ::-webkit-scrollbar-track {
            background: #1A1A1A;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #C6A43F, #FFD700);
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }
            .hero-checkout h1 {
                font-size: 36px;
            }
            .header {
                margin: 10px;
                padding: 15px 20px;
            }
            .nav-links {
                margin-top: 15px;
            }
            .shipping-sticker {
                font-size: 10px;
                padding: 5px 15px;
                top: -10px;
                right: -10px;
            }
        }
    </style>
</head>
<body>
<div class="animated-bg"></div>
<div class="rotating-diamond">💎</div>
<div class="particles" id="particles"></div>

<div class="header">
    <div class="container">
        <a href="products.php" class="logo">💎 LUXESTONE</a>
        <div class="nav-links">
            <a href="products.php"><i class="fas fa-gem"></i> Shop</a>
            <a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="hero-checkout">
        <h1><i class="fas fa-credit-card"></i> Secure Checkout</h1>
        <p>Complete your order with confidence and elegance</p>
    </div>

    <div class="checkout-grid">
        <div class="form-card">
            <div class="shipping-sticker">
                <i class="fas fa-star"></i> EXPRESS SHIPPING <i class="fas fa-star"></i>
            </div>
            <div class="card-header">
                <h2><i class="fas fa-user-astronaut"></i> Shipping Details</h2>
            </div>
            <div class="card-body">
                <form method=

"POST">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Full Name</label>
                        <input type="text" name="fullname" placeholder="John Doe" required>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-phone-alt"></i> Phone Number</label>
                        <input type="tel" name="phone" placeholder="+1 234 567 8900" required>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-map-marker-alt"></i> Delivery Address</label>
                        <textarea name="address" rows="3" placeholder="Street, City, Postal Code, Country" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-check-circle"></i> Confirm Order
                    </button>
                    <a href="cart.php" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Cart</a>
                </form>
            </div>
        </div>

        <div class="order-summary">
            <div class="card-header">
                <h2><i class="fas fa-receipt"></i> Order Summary</h2>
            </div>
            <div class="card-body">
                <div class="order-items">
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                        <div class="order-item">
                            <div class="order-item-info">
                                <div class="order-item-icon">💎</div>
                                <div class="order-item-name">
                                    <?php echo htmlspecialchars($item['name']); ?>
                                    <small>Quantity: <?php echo $item['quantity']; ?></small>
                                </div>
                            </div>
                            <div class="order-item-price">
                                $<?php echo number_format($item['price'] * $item['quantity']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="total-row">
                    <span>Total Amount</span>
                    <span>$<?php echo number_format($total); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function createParticles() {
        const particlesContainer = document.getElementById('particles');
        const particleCount = 50;
        
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            
            const size = Math.random() * 8 + 2;
            particle.style.width = size + 'px';
            particle.style.height = size + 'px';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 8 + 's';
            particle.style.animationDuration = 5 + Math.random() * 5 + 's';
            
            particlesContainer.appendChild(particle);
        }
    }
    
    createParticles();
</script>
</body>
</html>