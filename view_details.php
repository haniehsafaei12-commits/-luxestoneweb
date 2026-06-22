<?php
session_start();
include 'connection.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id == 0) {
    header("Location: products.php");
    exit();
}

$query = "SELECT p.*, b.name as brand_name, g.name as gemstone_name 
          FROM products_jewellery p
          LEFT JOIN brands b ON p.brand_id = b.id
          LEFT JOIN gemstone g ON p.gemstone_id = g.id
          WHERE p.id = $product_id";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    header("Location: products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> | LuxeStone</title>
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
        }

        .royal-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background: radial-gradient(circle at 30% 20%, #0A0A0F, #050508);
        }

        .royal-bg::before {
            content: '💎';
            position: absolute;
            font-size: 450px;
            opacity: 0.03;
            bottom: -120px;
            right: -120px;
            animation: rotateSlow 45s linear infinite;
        }

        .royal-bg::after {
            content: '👑';
            position: absolute;
            font-size: 300px;
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
            background: #C6A43F;
            border-radius: 50%;
            opacity: 0;
            box-shadow: 0 0 8px #C6A43F;
            animation: floatParticle 10s ease-in-out infinite;
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

        .glass-header {
            background: rgba(10, 10, 10, 0.75);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(198, 164, 63, 0.35);
            border-radius: 70px;
            padding: 18px 35px;
            margin: 30px;
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
            text-decoration: none;
        }

        @keyframes logoShine {
            0% { background-position: 0% center; }
            100% { background-position: 200% center; }
        }

        .nav-links {
            display: flex;
            gap: 30px;
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

        .detail-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 30px;
        }

        .detail-card {
            background: rgba(15, 15, 25, 0.7);
            backdrop-filter: blur(18px);
            border-radius: 48px;
            border: 1px solid rgba(198, 164, 63, 0.3);
            overflow: hidden;
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

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            padding: 50px;
        }

        .image-section {
            background: linear-gradient(135deg, rgba(0,0,0,0.4), rgba(198,164,63,0.05));
            border-radius: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            min-height: 400px;
        }

        .product-image {
            font-size: 180px;
            text-align: center;
        }

        .product-image img {
            max-width: 100%;
            max-height: 350px;
            object-fit: contain;
            border-radius: 20px;
        }

        .diamond-badge-lg {
            display: inline-block;
            background: linear-gradient(135deg, #C6A43F, #FFD700);
            padding: 8px 20px;
            border-radius: 40px;
            font-size: 14px;
            font-weight: bold;
            color: #0A0A0A;
            margin-bottom: 20px;
        }

        /* بخش اطلاعات */
        .info-section h1 {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            color: #C6A43F;
            margin-bottom: 20px;
        }

        .info-details {
            margin: 25px 0;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .detail-label {
            color: #C6A43F;
            font-weight: 600;
            width: 130px;
        }

        .detail-value {
            color: #FFFFFF;
            flex: 1;
        }

        .product-price-lg {
            font-size: 42px;
            font-weight: 800;
            color: #C6A43F;
            margin: 30px 0;
        }

        .description-box {
            background: rgba(0,0,0,0.3);
            border-radius: 24px;
            padding: 20px;
            margin: 25px 0;
            line-height: 1.6;
            color: #CCCCCC;
        }

        .button-group {
            display: flex;
            gap: 20px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .btn-add-to-cart {
            flex: 1;
            background: linear-gradient(135deg, #C6A43F, #FFD700);
            border: none;
            padding: 16px 25px;
            border-radius: 60px;
            color: #0A0A0A;
            font-weight: 800;
            font-size: 18px;
            cursor: pointer;

transition: all 0.3s;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }

        .btn-add-to-cart:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(198,164,63,0.5);
            letter-spacing: 1px;
        }

        .btn-add-to-cart.disabled, .btn-add-to-cart:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .btn-back {
            flex: 0.5;
            background: rgba(198,164,63,0.15);
            border: 1px solid #C6A43F;
            padding: 16px 25px;
            border-radius: 60px;
            color: #C6A43F;
            text-decoration: none;
            text-align: center;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-back:hover {
            background: #C6A43F;
            color: #0A0A0A;
        }

        /* موجودی کم */
        .low-stock {
            color: #FFA500;
            font-size: 12px;
            margin-top: 5px;
        }

        /* فوتر */
        .footer {
            text-align: center;
            padding: 40px;
            color: #666;
            border-top: 1px solid rgba(198,164,63,0.2);
            margin-top: 50px;
        }

        @media (max-width: 900px) {
            .detail-grid {
                grid-template-columns: 1fr;
                padding: 30px;
            }
            .glass-header {
                flex-direction: column;
                gap: 15px;
                margin: 15px;
            }
            .product-price-lg {
                font-size: 32px;
            }
            .info-section h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
<div class="royal-bg"></div>
<div class="gold-particles" id="particles"></div>

<div class="glass-header">
    <a href="products.php" class="logo">💎 LUXESTONE</a>
    <div class="nav-links">
        <a href="index.php">HOME</a>
        <a href="products.php">COLLECTION</a>
        <a href="cart.php"><i class="fas fa-shopping-cart"></i> CART</a>
		<a href="about_us.php">ABOUT US</a>
		<a href="contact_us.php"> CONTACT US</a>
        <?php if(isset($_SESSION['username'])): ?>
            <a href="logout.php">LOGOUT</a>
        <?php else: ?>
            <a href="logout.php">LOGOUT</a>
        <?php endif; ?>
    </div>
</div>

<div class="detail-container">
    <div class="detail-card">
        <div class="detail-grid">
            <div class="image-section">
                <div class="product-image">
                    <?php if($product['is_diamond'] == 1): ?>
                    <?php endif; ?>
                    <?php 
                    $image_path = "images/" . $product['image'];
                    if(!empty($product['image']) && file_exists($image_path)): 
                    ?>
                        <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <?php else: ?>
                        <?php if($product['category'] == 'ring'): ?>💍
                        <?php elseif($product['category'] == 'necklace'): ?>📿
                        <?php elseif($product['category'] == 'earrings'): ?>✨
                        <?php elseif($product['category'] == 'bracelet'): ?>💫
                        <?php else: ?>💎<?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="info-section">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <div class="info-details">
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-building"></i> Brand</span>
                        <span class="detail-value"><?php echo htmlspecialchars($product['brand_name']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-gem"></i> Gemstone</span>
                        <span class="detail-value" style="color: #C6A3F;"><?php echo htmlspecialchars($product['gemstone_name']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-tag"></i> Category</span>
                        <span class="detail-value"><?php echo ucfirst($product['category']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-coins"></i> Metal</span>
                        <span class="detail-value"><?php echo strtoupper(str_replace('_', ' ', $product['metal'])); ?></span>
                    </div>
                    <?php if($product['carat'] > 0): ?>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-weight-hanging"></i> Carat</span>
                        <span class="detail-value"><?php echo $product['carat']; ?> ct</span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-boxes"></i> Stock</span>
                        <span class="detail-value">
                            <?php if($product['stock'] > 0): ?>
                                <span style="color: #88FF88;">✅ In Stock (<?php echo $product['stock']; ?> available)</span>
                                <?php if($product['stock'] <= 5): ?>
                                    <div class="low-stock">⚠️ Only <?php echo $product['stock']; ?> left!</div>
                                <?php endif; ?>
                            <?php else: ?>
                                <span style="color: #FF8888;">❌ Sold Out</span>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>

                <div class="product-price-lg">
                    $<?php echo number_format($product['price']); ?> <small style="font-size: 14px;">USD</small>
                </div>

                <div class="description-box">
                    <i class="fas fa-align-left" style="color:#C6A43F; margin-right:10px;"></i>
                    <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                </div>

                <div class="button-group">
                    <?php if($product['stock'] > 0): ?>
                        <a href="add_to_cart.php?id=<?php echo $product['id']; ?>" class="btn-add-to-cart">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </a>
                    <?php else: ?>
                        <button class="btn-add-to-cart" disabled style="opacity:0.5; cursor:not-allowed;">
                            <i class="fas fa-times-circle"></i> Sold Out
                        </button>
                    <?php endif; ?>
                    <a href="products.php" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Back to Products
                    </a>
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
        for(let i = 0; i < 100; i++) {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 10 + 's';
            particle.style.animationDuration = 5 + Math.random() * 8 + 's';
            container.appendChild(particle);
        }
    }
    createParticles();
</script>
</body>
</html>