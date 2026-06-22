<?php
session_start();
include 'connection.php';

$is_admin_logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

if ($is_admin_logged_in && isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $brand_id = (int)$_POST['brand_id'];
    $gemstone_id = (int)$_POST['gemstone_id'];
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $metal = mysqli_real_escape_string($conn, $_POST['metal']);
    $carat = (float)$_POST['carat'];
    $price = (float)$_POST['price'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $image = mysqli_real_escape_string($conn, $_POST['image']);
    $is_diamond = isset($_POST['is_diamond']) ? 1 : 0;
    $stock = (int)$_POST['stock'];

    $query = "INSERT INTO products_jewellery (name, brand_id, gemstone_id, category, metal, carat, price, description, image, is_diamond, stock) 
              VALUES ('$name', '$brand_id', '$gemstone_id', '$category', '$metal', '$carat', '$price', '$description', '$image', '$is_diamond', '$stock')";
    
    if (mysqli_query($conn, $query)) {
        $success = "✅ Product added successfully!";
    } else {
        $error = "❌ Error: " . mysqli_error($conn);
    }
}

if ($is_admin_logged_in && isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM products_jewellery WHERE id = $id");
    header("Location: admin.php");
    exit();
}

if (isset($_POST['admin_login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM user_jewellery WHERE username = '$username' AND is_admin = 1";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && $password == $user['password']) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $user['username'];
        header("Location: admin.php");
        exit();
    } else {
        $login_error = "❌ Invalid admin credentials!";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit();
}

$brands_list = mysqli_query($conn, "SELECT * FROM brands ORDER BY name");
$gemstones_list = mysqli_query($conn, "SELECT * FROM gemstone ORDER BY name");
$products_list = mysqli_query($conn, "SELECT * FROM products_jewellery ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | LuxeStone</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #050508;
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        .royal-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background: radial-gradient(circle at 30% 20%, #0A0A0F, #010103);
        }

        .royal-bg::before {
            content: '💎';
            position: absolute;
            font-size: 400px;
            opacity: 0.03;
            bottom: -100px;
            right: -100px;
            animation: rotateSlow 40s linear infinite;
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

        .gold-dust {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
        }

        .dust {
            position: absolute;
            background: radial-gradient(circle, #FFD700, #C6A43F);
            border-radius: 50%;
            opacity: 0;
            box-shadow: 0 0 12px #C6A43F, 0 0 4px #FFD700;
            animation: floatGold 14s ease-in-out infinite;
        }

        @keyframes floatGold {
            0% {
                opacity: 0;
                transform: translateY(100vh) scale(0);
            }
            15% {
                opacity: 0.7;
            }
            85% {
                opacity: 0.4;
            }
            100% {
                opacity: 0;
                transform: translateY(-100vh) scale(1.3);
            }
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px;
            position: relative;
            z-index: 1;
        }

        .glass-header {
            background: rgba(10, 10, 10, 0.75);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(198, 164, 63, 0.35);
            border-radius: 70px;
            padding: 18px 35px;
            margin-bottom: 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
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

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 30px;
            font-weight: 800;
            background: linear-gradient(135deg, #C6A43F, #FFD700, #FFF8DC, #C6A43F);
            background-size: 300% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: logoShine 4s ease infinite;
        }

        @keyframes logoShine {
            0% { background-position: 0% center; }
            100% { background-position: 200% center; }
        }

        .admin-stats {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .crown-badge {
            background: linear-gradient(135deg, rgba(198,164,63,0.15), rgba(198,164,63,0.05));
            border: 1px solid rgba(198,164,63,0.4);
            padding: 8px 24px;
            border-radius: 50px;
            color: #C6A43F;
            font-weight: 600;
            backdrop-filter: blur(5px);
        }

        .logout-btn {
            background: rgba(255, 68, 68, 0.12);
            border: 1px solid rgba(255, 68, 68, 0.5);
            color: #FF8888;
            padding: 8px 28px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: #FF4444;
            color: #050508;
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(255,68,68,0.3);
        }

        .premium-card {
            background: rgba(8, 8, 12, 0.75);
            backdrop-filter: blur(18px);
            border-radius: 36px;
            border: 1px solid rgba(198, 164,

63, 0.25);
            overflow: hidden;
            margin-bottom: 45px;
            transition: all 0.4s;
            animation: fadeInUp 0.6s ease;
        }

        .premium-card:hover {
            border-color: rgba(198, 164, 63, 0.6);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4), 0 0 20px rgba(198,164,63,0.1);
        }

        .card-gold-header {
            background: linear-gradient(135deg, rgba(198,164,63,0.12), rgba(198,164,63,0.03));
            padding: 22px 30px;
            border-bottom: 1px solid rgba(198,164,63,0.25);
            position: relative;
        }

        .card-gold-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 15%;
            width: 70%;
            height: 1px;
            background: linear-gradient(90deg, transparent, #C6A43F, #FFD700, #C6A43F, transparent);
        }

        .card-gold-header h2 {
            color: #C6A43F;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-body-luxury {
            padding: 32px;
        }

        .form-luxury-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 22px;
        }

        .input-luxury {
            margin-bottom: 5px;
        }

        .input-luxury label {
            display: block;
            margin-bottom: 8px;
            color: #C6A43F;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .input-luxury label i {
            margin-right: 8px;
        }

        .input-luxury input, 
        .input-luxury select, 
        .input-luxury textarea {
            width: 100%;
            padding: 14px 18px;
            background: rgba(0, 0, 0, 0.55);
            border: 1px solid rgba(198, 164, 63, 0.3);
            border-radius: 24px;
            color: #F5F5F5;
            font-size: 14px;
            transition: all 0.3s;
        }

        .input-luxury input:focus, 
        .input-luxury select:focus, 
        .input-luxury textarea:focus {
            outline: none;
            border-color: #C6A43F;
            box-shadow: 0 0 20px rgba(198, 164, 63, 0.2);
            background: rgba(0, 0, 0, 0.75);
        }

        .diamond-check {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 15px;
        }

        .diamond-check input {
            width: 20px;
            height: 20px;
            accent-color: #C6A43F;
            cursor: pointer;
        }

        .diamond-check label {
            color: white;
            font-weight: 500;
        }

        .btn-gold {
            background: linear-gradient(135deg, #C6A43F, #FFD700);
            border: none;
            padding: 14px 35px;
            border-radius: 50px;
            color: #050508;
            font-weight: 800;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 20px;
            position: relative;
            overflow: hidden;
        }

        .btn-gold::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: 0.5s;
        }

        .btn-gold:hover::before {
            left: 100%;
        }

        .btn-gold:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(198,164,63,0.5);
            letter-spacing: 1px;
        }

        .table-wrapper {
            overflow-x: auto;
            border-radius: 20px;
        }

        .royal-table {
            width: 100%;
            border-collapse: collapse;
        }

        .royal-table th {
            text-align: left;
            padding: 18p

x 16px;
            color: #C6A43F;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border-bottom: 2px solid rgba(198,164,63,0.25);
        }

        .royal-table td {
            padding: 16px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            vertical-align: middle;
        }

        .royal-table tr {
            transition: all 0.3s;
        }

        .royal-table tr:hover {
            background: rgba(198,164,63,0.08);
        }

        .white-name {
            color: white;
            font-weight: 600;
        }

        .id-number {
            color: white;
            font-weight: 600;
            font-size: 15px;
        }

        .price-gold {
            color: #C6A43F;
            font-weight: 700;
        }

        .action-group {
            display: flex;
            gap: 10px;
        }

        .edit-action {
            background: rgba(198,164,63,0.15);
            border: 1px solid #C6A43F;
            color: #C6A43F;
            padding: 6px 16px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .edit-action:hover {
            background: #C6A43F;
            color: #050508;
            transform: translateY(-2px);
        }

        .delete-action {
            background: rgba(255,68,68,0.1);
            border: 1px solid rgba(255,68,68,0.5);
            color: #FF8888;
            padding: 6px 16px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .delete-action:hover {
            background: #FF4444;
            color: white;
            transform: translateY(-2px);
        }

        .login-royal {
            max-width: 500px;
            margin: 120px auto;
        }

        .login-card-royal {
            background: rgba(8, 8, 12, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(198, 164, 63, 0.4);
            border-radius: 48px;
            padding: 50px 45px;
            text-align: center;
            animation: fadeInScale 0.6s ease;
            position: relative;
            overflow: hidden;
        }

        /* ذرات درشت برای صفحه لاگین */
        .login-particle {
            position: absolute;
            width: 8px;
            height: 8px;
            background: radial-gradient(circle, #FFD700, #C6A43F);
            border-radius: 50%;
            opacity: 0;
            box-shadow: 0 0 15px #C6A43F, 0 0 5px #FFD700;
            animation: loginFloat 12s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes loginFloat {
            0% {
                opacity: 0;
                transform: translateY(60px) scale(0);
            }
            20% {
                opacity: 0.8;
            }
            80% {
                opacity: 0.4;
            }
            100% {
                opacity: 0;
                transform: translateY(-60px) scale(1.3);
            }
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .login-icon-crown {

            font-size: 75px;
            margin-bottom: 20px;
            animation: crownFloat 3s ease infinite;
        }

        @keyframes crownFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); text-shadow: 0 0 15px #C6A43F; }
        }

        .login-input-royal {
            width: 100%;
            padding: 15px 22px;
            margin: 12px 0;
            background: rgba(0,0,0,0.6);
            border: 1px solid rgba(198,164,63,0.3);
            border-radius: 50px;
            color: white;
            font-size: 15px;
            transition: all 0.3s;
        }

        .login-input-royal:focus {
            outline: none;
            border-color: #C6A43F;
            box-shadow: 0 0 20px rgba(198,164,63,0.2);
        }

        .btn-login-royal {
            width: 100%;
            background: linear-gradient(135deg, #C6A43F, #FFD700);
            border: none;
            padding: 14px;
            border-radius: 50px;
            color: #050508;
            font-weight: 800;
            font-size: 17px;
            cursor: pointer;
            margin-top: 25px;
            transition: all 0.3s;
        }

        .btn-login-royal:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(198,164,63,0.4);
        }

        .alert-royal-success {
            background: rgba(0,255,136,0.1);
            border: 1px solid #00FF88;
            color: #88FF88;
            padding: 12px 18px;
            border-radius: 30px;
            margin-bottom: 25px;
        }

        .alert-royal-error {
            background: rgba(255,68,68,0.1);
            border: 1px solid #FF4444;
            color: #FF8888;
            padding: 12px 18px;
            border-radius: 30px;
            margin-bottom: 25px;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #0A0A0F;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #C6A43F, #FFD700);
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .glass-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            .container {
                padding: 20px;
            }
            .action-group {
                flex-direction: column;
            }
			
        }
    </style>
</head>
<body>
<div class="royal-bg"></div>
<div class="gold-dust" id="goldDust"></div>

<div class="container">
    <?php if (!$is_admin_logged_in): ?>
        <!-- لاگین رویال -->
        <div class="login-royal">
            <div class="login-card-royal" id="loginCard">
                <div class="login-icon-crown">👑</div>
                <h3 style="color:#C6A43F; margin-bottom:20px;">Admin Portal</h3>
                <?php if(isset($login_error)): ?>
                    <div class="alert-royal-error"><?php echo $login_error; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <input type="text" name="username" class="login-input-royal" placeholder="Username" required>
                    <input type="password" name="password" class="login-input-royal" placeholder="Password" required>
                    <button type="submit" name="admin_login" class="btn-login-royal">
                        <i class="fas fa-unlock-alt"></i> Enter Portal
                    </button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="glass-header">
            <div class="logo">💎 LUXESTONE ADMIN</div>
            <div class="admin-stats">
                <div class="crown-badge"><i class="fas fa-crown"></i> <?php echo $_SESSION['admin_username']; ?></div>
                <a href="?logout=1" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Exit</a>
				                <a href="admin_message.php" class="crown-badge"><i class="fas fa-envelope"></i> MESSAGES</a>

            </div>
        </div>

        <div class="premium-card">
            <div class="card-gold-header">
                <h2><i class="fas fa-plus-circle"></i> Create New Product</h2>
            </div>
            <div class="card-body-luxury">
                <?php if(isset($success)): ?>
                    <div class="alert-royal-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <?php if(isset($error)): ?>
                    <div class="alert-royal-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-luxury-grid">
                        <div class="input-luxury">
                            <label><i class="fas fa-gem"></i> Product Name</label>
                            <input type="text" name="name" required>
                        </div>
                        <div class="input-luxury">
                            <label><i class="fas fa-building"></i> Brand</label>
                            <select name="brand_id" required>
                                <option value="">Select Brand</option>
                                <?php while($b = mysqli_fetch_assoc($brands_list)): ?>
                                    <option value="<?php echo $b['id']; ?>"><?php echo $b['name']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="input-luxury">
                            <label><i class="fas fa-gem"></i> Gemstone</label>
                            <select name="gemstone_id" required>
                                <option value="">Select Gemstone</option>
                                <?php 
                                mysqli_data_seek($gemstones_list, 0);
                                while($g = mysqli_fetch_assoc($gemstones_list)): ?>
                                    <option value="<?php echo $g['id']; ?>"><?php echo $g['name']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="input-luxury">
                            <label><i class="fas fa-tag"></i> Category</label>
                            <select name="category" required>
                                <option value="ring">💍 Ring</option>
                                <option value="necklace">📿 Necklace</option>
                                <option value="earring">✨ Earring</option>
                                <option value="bracelet">💫 Bracelet</option>
                            </select>
                        </div>
                        <div class="input-luxury">
                            <label><i class="fas fa-coins"></i> Metal</label>
                            <select name="metal" required>
                                <option value="gold_18k">18K Gold</option>
                                <option value="gold_22k">22K Gold</option>
                                <option value="platinum">Platinum</option>
                                <option value="silver">Silver</option>
                                <option value="rose_gold">Rose Gold</option>
                            </select>
                        </div>
                        <div class="input-luxury">
                            <label><i class="fas fa-weight-hanging"></i> Carat</label>
                            <input type="number" step="0.01" name="carat" value="0">
                        </div>
                        <div class="input-luxury">
                            <label><i class="fas fa-dollar-sign"></i> Price (USD)</label>
                            <input type="number" step="0.01" name="price" required>
                        </div>
                        <div class="input-luxury">
                            <label><i class="fas fa-image"></i> Image</label>
                            <input type="text" name="image" placeholder="filename.jpg">
                        </div>
                        <div class="input-luxury full-width">
                            <label><i class="fas fa-align-left"></i> Description</label>
                            <textarea name="description" rows="3"></textarea>
                        </div>
                        <div class="diamond-check">
                            <input type="checkbox" name="is_diamond" id="diamond">
                            <label for="diamond"><i class="fas fa-gem"></i> This is a Diamond product</label>
                        </div>
                        <div class="input

-luxury">
                            <label for="stock" style="color: #C6A43F;"><i class="fas fa-boxes"></i> Stock Quantity</label>
                            <input type="number" name="stock" value="10" min="0" required>
                        </div>
                    </div>
                    <button type="submit" name="add_product" class="btn-gold">
                        <i class="fas fa-save"></i> Add Product
                    </button>
                </form>
            </div>
        </div>

        <div class="premium-card">
            <div class="card-gold-header">
                <h2><i class="fas fa-database"></i> Product Inventory</h2>
            </div>
            <div class="card-body-luxury">
                <div class="table-wrapper">
                    <table class="royal-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product Name</th>
                                <th>Brand</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($p = mysqli_fetch_assoc($products_list)): ?>
                                <tr>
                                    <td class="id-number"><?php echo $p['id']; ?></td>
                                    <td class="white-name"><?php echo htmlspecialchars($p['name']); ?></td>
                                    <td style="color: #FFFFFF; font-weight: 500;"><?php 
                                        $bq = "SELECT name FROM brands WHERE id = ".$p['brand_id'];
                                        $br = mysqli_query($conn, $bq);
                                        $bn = mysqli_fetch_assoc($br);
                                        echo $bn ? htmlspecialchars($bn['name'] ): '-';
                                    ?></td>
                                    <td class="price-gold">$<?php echo number_format($p['price']); ?></td>
                                    <td>
                                        <div class="action-group">
                                            <a href="edit_products.php?id=<?php echo $p['id']; ?>" class="edit-action">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="?delete=<?php echo $p['id']; ?>" class="delete-action" onclick="return confirm('⚠️ Delete permanently?')">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    function createGoldDust() {
        const container = document.getElementById('goldDust');
        for (let i = 0; i < 120; i++) {
            const dust = document.createElement('div');
            dust.classList.add('dust');
            
            const size = Math.random() * 8 + 6;
            dust.style.width = size + 'px';
            dust.style.height = size + 'px';
            
            dust.style.left = Math.random() * 100 + '%';
            dust.style.animationDelay = Math.random() * 15 + 's';
            dust.style.animationDuration = 8 + Math.random() * 12 + 's';
            container.appendChild(dust);
        }
    }
    createGoldDust();
    
    function createLoginParticles() {
        const loginCard = document.getElementById('loginCard');
        if (loginCard) {
            for (let i = 0; i < 40; i++) {
                const particle = document.createElement('div');
                particle.classList.

add('login-particle');
                
                const size = Math.random() * 8 + 8;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 12 + 's';
                particle.style.animationDuration = 8 + Math.random() * 10 + 's';
                loginCard.appendChild(particle);
            }
        }
    }
    createLoginParticles();
</script>
</body>
</html>