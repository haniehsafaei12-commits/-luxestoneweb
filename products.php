<?php
session_start();
include 'connection.php';

$query = "SELECT p.*, b.name as brand_name, g.name as gemstone_name 
          FROM products_jewellery p
          LEFT JOIN brands b ON p.brand_id = b.id
          LEFT JOIN gemstone g ON p.gemstone_id = g.id
          ORDER BY p.id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LuxeStone | Our Collection</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            overflow-x: hidden;
        }

        .bg-diamonds {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            overflow: hidden;
        }

        .bg-diamonds::before {
            content: '💎';
            position: absolute;
            font-size: 300px;
            opacity: 0.03;
            bottom: -100px;
            right: -100px;
            animation: rotateDiamond 30s linear infinite;
        }

        .bg-diamonds::after {
            content: '✨';
            position: absolute;
            font-size: 200px;
            opacity: 0.03;
            top: -50px;
            left: -80px;
            animation: rotateDiamond 25s linear infinite reverse;
        }

        @keyframes rotateDiamond {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* هدر */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(20px);
            z-index: 1000;
            padding: 20px 40px;
            border-bottom: 1px solid rgba(198, 164, 63, 0.2);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .header.scrolled {
            padding: 12px 40px;
            background: rgba(10, 10, 10, 0.98);
            box-shadow: 0 5px 30px rgba(0,0,0,0.5);
        }

        .header .container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, #C6A43F, #FFD700, #C6A43F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
            letter-spacing: 2px;
            transition: 0.3s;
        }

        .logo:hover {
            letter-spacing: 4px;
        }

        .nav-links {
            display: flex;
            gap: 35px;
            align-items: center;
        }

        .nav-links a {
            color: #FFFFFF;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #C6A43F, #FFD700);
            transition: 0.3s;
        }

        .nav-links a:hover::after {

            width: 100%;
        }

        .nav-links a:hover {
            color: #C6A43F;
        }

        /* Hero 3D */
        .hero-3d {
            height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            margin-top: 80px;
            perspective: 1000px;
        }

        .hero-content {
            transform-style: preserve-3d;
            animation: float3d 6s ease-in-out infinite;
        }

        @keyframes float3d {
            0%, 100% { transform: translateZ(0px) rotateX(0deg); }
            50% { transform: translateZ(30px) rotateX(3deg); }
        }

        .hero-content h1 {
            font-family: 'Playfair Display', serif;
            font-size: 80px;
            font-weight: 800;
            background: linear-gradient(135deg, #C6A43F 0%, #FFD700 30%, #C6A43F 60%, #FFD700 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 3s ease infinite;
            background-size: 200% auto;
        }

        @keyframes shimmer {
            0% { background-position: 0% center; }
            100% { background-position: 200% center; }
        }

        .hero-content p {
            color: #AAAAAA;
            font-size: 18px;
            margin-top: 20px;
        }

        /* آمار */
        .stats-bar {
            max-width: 1200px;
            margin: -30px auto 40px;
            display: flex;
            justify-content: center;
            gap: 50px;
            position: relative;
            z-index: 10;
        }

        .stat-item {
            background: rgba(20, 20, 30, 0.7);
            backdrop-filter: blur(10px);
            padding: 15px 30px;
            border-radius: 50px;
            border: 1px solid rgba(198,164,63,0.3);
            text-align: center;
        }

        .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: #C6A43F;
        }

        .stat-label {
            font-size: 12px;
            color: #888;
        }

        .filters-section {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 40px;
        }

        .filter-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 50px;
        }

        .filter-btn {
            padding: 12px 28px;
            background: rgba(20, 20, 30, 0.6);
            border: 1px solid rgba(198, 164, 63, 0.3);
            border-radius: 40px;
            color: #CCCCCC;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            font-size: 14px;
        }

        .filter-btn i {
            margin-right: 8px;
        }

        .filter-btn:hover, .filter-btn.active {
            background: linear-gradient(135deg, #C6A43F, #E2B84D);
            color: #0A0A0A;
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(198,164,63,0.3);
        }

        .products-section {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px 80px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 40px;
        }

        .product-card {
            background: rgba(15, 15, 25, 0.5);
            backdrop-filter: blur(10px);
            border-radius: 28px;
            overflow: hidden;
            border: 1px solid rgba(198, 164, 63, 0.2);
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-15px) scale(1.02);
            border-color: #C6A43F;
            box-shadow: 0 30px 50px rgba(0,0,0,0.5), 0 0 30px rgba(198,164,63,0.2);
        }

        .product-image {
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 100px;
            background: linear-gradient(135deg, rgba(0,0,0,0.4), rgba(198,164,63,0.05));
            position: relative;
            overflow: hidden;
        }

        .product-image img {
            transition: transform 0.5s;
        }

        .product-card:hover .product-image img {
            transform: scale(1.1);
        }

        .diamond-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, #C6A43F, #FFD700);
            padding: 6px 14px;
            border-radius: 25px;
            font-size: 11px;
            font-weight: bold;
            color: #0A0A0A;
            display: flex;
            align-items: center;
            gap: 6px;
            z-index: 10;
        }

        .product-info {
            padding: 25px;
        }

        .product-category {
            display: inline-block;
            padding: 5px 14px;
            background: rgba(198, 164, 63, 0.15);
            border-radius: 25px;
            font-size: 11px;
            color: #C6A43F;
            margin-bottom: 12px;
            letter-spacing: 1px;
        }

        .product-name {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 700;
            color: #FFFFFF;
            margin-bottom: 12px;
        }

        .product-details {
            color: #999999;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .product-details span i {
            color: #C6A43F;
            margin-right: 6px;
        }

        .product-price {
            font-size: 32px;
            font-weight: 800;
            background: linear-gradient(135deg, #C6A43F, #FFD700);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 15px 0;
        }

        .product-price small {
            font-size: 13px;
            color: #666;
            font-weight: normal;
        }

        .btn-details {
            display: inline-flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 14px 24px;
            background: linear-gradient(135deg, rgba(198,164,63,0.1), rgba(198,164,63,0.05));
            border: 1.5px solid rgba(198, 164, 63, 0.4);
            border-radius: 50px;
            color: #C6A43F;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            gap: 10px;
        }

        .btn-details:hover {
            background: linear-gradient(135deg, #C6A43F, #E2B84D);
            color: #0A0A0A;
            gap: 20px;
            border-color: transparent;
        }

        .parallax-footer {
            background: linear-gradient(135deg, #050505, #0A0A0A);
            padding: 50px;
            text-align: center;
            border-top: 1px solid rgba(198, 164, 63, 0.2);
            position: relative;
            overflow: hidden;
        }

        .parallax-footer::before {
            content: '💎✨💍👑';
            position: absolute;
            font-size: 100px;
            opacity: 0.03;
            white-space: nowrap;
            animation: slideText 20s linear infinite;
        }

        @keyframes slideText {
            from { transform: translateX(-100%); }
            to { transform: translateX(100%); }
        }

        .footer-content {
            position: relative;
            z-index: 2;
        }

        .footer-content p {
            color: #666;
            margin: 10px 0;
        }

        .social-icons {
            margin-top: 20px;
        }

        .social-icons a {
            color: #C6A43F;
            margin: 0 10px;
            font-size: 20px;
            transition: 0.3s;
        }

        .social-icons a:hover {
            transform: translateY(-3px);
            color: #FFD700;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1A1A1A;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #C6A43F, #FFD700);
            border-radius: 10px;
        }

        .product-card {
            opacity: 0;
            animation: cardGlow 0.8s ease forwards;
        }

        @keyframes cardGlow {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .header .container {
                flex-direction: column;
                gap: 15px;
            }
            .hero-content h1 {
                font-size: 40px;
            }
            .stats-bar {
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }
            .products-section {
                padding: 0 20px 40px;
            }
            .products-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="bg-diamonds"></div>

<header class="header" id="header">
    <div class="container">
        <a href="dashboard.php" class="logo">💎 LUXESTONE</a>
        <div class="nav-links">
            <a href="index.php"><i class="fas fa-home"></i> HOME</a>
            <a href="products.php" style="color:#C6A43F;"><i class="fas fa-gem"></i> COLLECTION</a>
			<a href="cart.php"><i class="fas fa-shopping-cart"></i> CART</a>
			<a href="about_us.php"> ABOUT US</a>
			<a href="contact_us.php"> CONTACT US</a>
            <?php if(isset($_SESSION['username'])): ?>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> LOGOUT</a>
            <?php else: ?>
                <a href="logout.php"><i class="fas fa-user"></i> LOGOUT</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<section class="hero-3d">
    <div class="hero-content">
        <h1>Timeless<br>Elegance</h1>
        <p>Discover our exclusive collection of luxury jewelry</p>
    </div>
</section>

<div class="stats-bar">
    <div class="stat-item">
        <div class="stat-number">500+</div>
        <div class="stat-label">Exclusive Pieces</div>
    </div>
    <div class="stat-item">
        <div class="stat-number">25+</div>
        <div class="stat-label">Luxury Brands</div>
    </div>
    <div class="stat-item">
        <div class="stat-number">100%</div>
        <div class="stat-label">Authentic</div>
    </div>
    <div class="stat-item">
        <div class="stat-number">24/7</div>
        <div class="stat-label">Support</div>
    </div>
</div>

<div class="filters-section">
    <div class="filter-buttons">
        <button class="filter-btn active" data-filter="all"><i class="fas fa-gem"></i> ALL</button>
        <button class="filter-btn" data-filter="ring"><i class="fas fa-ring"></i> RINGS</button>
        <button class="filter-btn" data-filter="necklace"><i class="fas fa-necklace"></i> NECKLACES</button>
        <button class="filter-btn" data-filter="earrings"><i class="fas fa-earrings"></i> EARRINGS</button>
        <button class="filter-btn" data-filter="bracelet"><i class="fas fa-hand-peace"></i> BRACELETS</button>
        <button class="filter-btn" data-filter="diamond"><i class="fas fa-gem"></i> DIAMONDS</button>
    </div>
</div>

<section class="products-section">
    <div class="products-grid" id="productsGrid">
        <?php 
        $delay = 0;
        $total_products = 0;
        $total_diamonds = 0;
        
        $products_array = [];
        while($product = mysqli_fetch_assoc($result)) {
            $products_array[] = $product;
            $total_products++;
            if($product['is_diamond'] == 1) $total_diamonds++;
        }
        ?>
        
        <script>
            window.productStats = {
                total: <?php echo $total_products; ?>,
                diamonds:<?php echo $total_diamonds; ?>
            };
        </script>
        
        <?php foreach($products_array as $product): 
            $delay += 0.08;
        ?>
            <div class="product-card" data-category="<?php echo $product['category']; ?>" data-diamond="<?php echo $product['is_diamond']; ?>" style="animation-delay: <?php echo min($delay, 0.8); ?>">
                <div class="product-image">
                    <?php if($product['is_diamond'] == 1): ?>
                        <div class="diamond-badge">
                            <i class="fas fa-gem"></i> 💎DIAMOND
                        </div>
                    <?php endif; ?>
                    <?php 
                    $image_path = "images/" . $product['image'];
                    if(!empty($product['image']) && file_exists($image_path)): 
                    ?>
                        <img src="<?php echo $image_path; ?>" style="width:100%; height:100%; object-fit:cover;">
                    <?php else: ?>
                        <?php if($product['is_diamond'] == 1): ?>
                            💎
                        <?php elseif($product['category'] == 'ring'): ?>
                            💍
                        <?php elseif($product['category'] == 'necklace'): ?>
                            📿
                        <?php elseif($product['category'] == 'earrings'): ?>
                            ✨
                        <?php else: ?>
                            💫
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="product-info">
                    <span class="product-category"><i class="fas fa-tag"></i> <?php echo strtoupper($product['category']); ?></span>
                    <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                    <div class="product-details">
                        <span><i class="fas fa-building"></i> <?php echo $product['brand_name']; ?></span>
                        <span><i class="fas fa-gem"></i> <?php echo $product['gemstone_name']; ?></span>
                        <span><i class="fas fa-coins"></i> <?php echo strtoupper(str_replace('_', ' ', $product['metal'])); ?></span>
                        <?php if($product['carat'] > 0): ?>
                            <span><i class="fas fa-weight-hanging"></i> <?php echo $product['carat']; ?> ct</span>
                        <?php endif; ?>
                    </div>
                    <div class="product-price">
                        $<?php echo number_format($product['price']); ?> <small>USD</small>
                    </div>
                    <a href="view_details.php?id=<?php echo $product['id']; ?>" class="btn-details">
                        DISCOVER MORE <i class="fas fa-arrow-right"></i>
                    </a>
					
					
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<footer class="parallax-footer">
    <div class="footer-content">
        <p>✨ LUXESTONE - TIMELESS ELEGANCE, BRILLIANT MOMENTS ✨</p>
        <p>© 2026 LuxeStone | All Rights Reserved</p>
        <div class="social-icons">
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-pinterest"></i></a>
        </div>
    </div>
</footer>

<script>
    window.addEventListener('scroll', function() {
        const header = document.getElementById('header');
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    const filterBtns = document.querySelectorAll('.filter-btn');
    const products = document.querySelectorAll('.product-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');

            products.forEach(product => {
                const category = product.getAttribute('data-category');
                const isDiamond = product.getAttribute('data-diamond');
                
                if (filter === 'all') {
                    product.style.display = 'block';
                    product.style.opacity = '1';
                } else if (filter === 'diamond') {
                    if (isDiamond === '1') {
                        product.style.display = 'block';
                        product.style.opacity = '1';
                    } else {
                        product.style.display = 'none';
                    }
                } else {
                    if (category === filter) {
                        product.style.display = 'block';
                        product.style.opacity = '1';
                    } else {
                        product.style.display = 'none';
                    }
                }
            });
        });
    });

    console.log(`✨ LuxeStone Loaded: ${window.productStats.total} products, ${window.productStats.diamonds} diamonds`);
</script>

</body>
</html>