<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_name'];

$query = "SELECT * FROM orders WHERE fullname = '$user_name' ORDER BY date DESC";
$result = mysqli_query($conn, $query);

$orders = [];
$total_sum = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
    $total_sum += $row['price'];
}

$address = !empty($orders) ? $orders[0]['address'] : '';
$first_date = !empty($orders) ? $orders[0]['date'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders | LuxeStone</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            padding: 40px 20px;
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
            font-size: 400px;
            opacity: 0.03;
            bottom: -100px;
            right: -100px;
            animation: rotateDiamond 40s linear infinite;
        }
        @keyframes rotateDiamond {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
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
            0% { opacity: 0; transform: translateY(100vh) scale(0); }
            20% { opacity: 0.6; }
            80% { opacity: 0.3; }
            100% { opacity: 0; transform: translateY(-100vh) scale(1); }
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .glass-header {
            background: rgba(10, 10, 10, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(198, 164, 63, 0.3);
            padding: 20px 40px;
            margin: 20px 30px 50px;
            border-radius: 80px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
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
        }
        .nav-links a:hover {
            color: #C6A43F;
        }
        .orders-card {

background: rgba(20, 20, 30, 0.6);
            backdrop-filter: blur(15px);
            border-radius: 32px;
            border: 1px solid rgba(198, 164, 63, 0.3);
            overflow: hidden;
            animation: fadeInUp 0.6s ease;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .card-header {
            background: linear-gradient(135deg, rgba(198,164,63,0.15), rgba(198,164,63,0.05));
            padding: 25px 30px;
            border-bottom: 1px solid rgba(198,164,63,0.2);
        }
        .card-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #C6A43F;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .card-body {
            padding: 30px;
        }
        .user-info {
            background: rgba(0,0,0,0.3);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }
        .info-item {
            color: #CCCCCC;
        }
        .info-item i {
            color: #C6A43F;
            margin-right: 8px;
        }
        .info-item strong {
            color: #C6A43F;
        }
        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }
        .orders-table th {
            text-align: left;
            padding: 15px;
            color: #C6A43F;
            border-bottom: 1px solid rgba(198,164,63,0.3);
        }
        .orders-table td {
            padding: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            color: #F5F5F5;
        }
        .orders-table tr:hover {
            background: rgba(198,164,63,0.05);
        }
        .status-badge {
            display: inline-block;
            background: rgba(0,255,0,0.15);
            border: 1px solid #00FF88;
            color: #88FF88;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
        }
        .total-box {
            margin-top: 30px;
            padding: 20px;
            background: rgba(0,0,0,0.3);
            border-radius: 20px;
            text-align: right;
            font-size: 24px;
            font-weight: bold;
            color: #C6A43F;
        }
        .btn-back {
            display: inline-block;
            margin-top: 30px;
            background: transparent;
            border: 2px solid #C6A43F;
            color: #C6A43F;
            padding: 12px 30px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-back:hover {
            background: #C6A43F;
            color: #0A0A0A;
            transform: translateY(-3px);
        }
        .empty-orders {
            text-align: center;
            padding: 60px;
            color: #888;
        }
        @media (max-width: 768px) {
            .glass-header { flex-direction: column; gap: 15px; text-align: center; }
            .orders-table th, .orders-table td { padding: 10px; font-size: 12px; }
            .total-box { font-size: 18px; }
        }
    </style>
</head>
<body>
<div class="bg-diamonds"></div>
<div class="gold-particles" id="particles"></div>

<div class="glass-header">
    <a href="products.php" class="logo">💎 LUXESTONE</a>
    <div class="nav-links">
        <a href="products.php">SHOP</a>
        <a href="cart.php">CART</a>
        <a href="order_details.php" style="color:#C6A43F;">ORDERS</a>
        <a href="logout.php">LOGOUT</a>
    </div>
</div>

<div class="container">
    <div class="orders-card">
        <div class="card-header">
            <h1><i class="fas fa-receipt"></i> My Orders</h1>
        </div>
        <div class="card-body">
            <?php if (empty($orders)): ?>
                <div class="empty-orders">

<i class="fas fa-shopping-basket" style="font-size: 60px; opacity: 0.5;"></i>
                    <p style="margin-top: 20px;">You haven't placed any orders yet.</p>
                    <a href="products.php" class="btn-back">Start Shopping →</a>
                </div>
            <?php else: ?>
                <div class="user-info">
                    <div class="info-item"><i class="fas fa-user"></i> <strong><?php echo htmlspecialchars($user_name); ?></strong></div>
                    <div class="info-item"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($address); ?></div>
                    <div class="info-item"><i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars($first_date); ?></div>
                </div>

                <table class="orders-table">
                    <thead>
                        <tr><th>Product</th><th>Price</th><th>Date</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td>$<?php echo number_format($order['price']); ?></td>
                            <td><?php echo htmlspecialchars($order['date']); ?></td>
                            <td><span class="status-badge"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($order['status']); ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="total-box">
                    💰 Total Amount Paid: $<?php echo number_format($total_sum); ?>
                </div>

                <div style="text-align: center; margin-top: 20px;">
                    <a href="products.php" class="btn-back">← Continue Shopping</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function createParticles() {
        const container = document.getElementById('particles');
        for (let i = 0; i < 80; i++) {
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