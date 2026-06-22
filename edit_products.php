<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php");
    exit();
}

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id == 0) {
    header("Location: admin.php");
    exit();
}

$query = "SELECT * FROM products_jewellery WHERE id = $product_id";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    header("Location: admin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
	$stock=(int)$_POST['stock'];
    $update_query = "UPDATE products_jewellery SET 
                     name = '$name',
                     brand_id = $brand_id,
                     gemstone_id = $gemstone_id,
                     category = '$category',
                     metal = '$metal',
                     carat = $carat,
                     price = $price,
                     description = '$description',
                     image = '$image',
                     is_diamond = $is_diamond,
					 stock=$stock
                     WHERE id = $product_id";

    if (mysqli_query($conn, $update_query)) {
        header("Location: admin.php");
        exit();
    } else {
        $error = "Error updating product: " . mysqli_error($conn);
    }
}

$brands_list = mysqli_query($conn, "SELECT * FROM brands ORDER BY name");
$gemstones_list = mysqli_query($conn, "SELECT * FROM gemstone ORDER BY name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product | LuxeStone Admin</title>
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
            padding: 40px 20px;
        }

        .royal-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background: radial-gradient(circle at 20% 30%, #0A0A0F, #020205);
        }

        .royal-bg::before {
            content: '💎';
            position: absolute;
            font-size: 400px;
            opacity: 0.02;
            bottom: -100px;
            right: -100px;
            animation: slowRotate 40s linear infinite;
        }

        @keyframes slowRotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .edit-card {
            background: rgba(8, 8, 12, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(198, 164, 63, 0.4);
            border-radius: 40px;
            overflow: hidden;
            animation: fadeUp 0.6s ease;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;

transform: translateY(0);
            }
        }

        .card-header {
            background: linear-gradient(135deg, rgba(198,164,63,0.15), rgba(198,164,63,0.05));
            padding: 25px 30px;
            border-bottom: 1px solid rgba(198,164,63,0.3);
            text-align: center;
        }

        .card-header h1 {
            color: #C6A43F;
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .card-body {
            padding: 35px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .input-group {
            margin-bottom: 5px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: #C6A43F;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .input-group label i {
            margin-right: 8px;
        }

        .input-group input, .input-group select, .input-group textarea {
            width: 100%;
            padding: 14px 18px;
            background: rgba(0,0,0,0.6);
            border: 1px solid rgba(198,164,63,0.3);
            border-radius: 24px;
            color: white;
            font-size: 14px;
            transition: all 0.3s;
        }

        .input-group input:focus, .input-group select:focus, .input-group textarea:focus {
            outline: none;
            border-color: #C6A43F;
            box-shadow: 0 0 15px rgba(198,164,63,0.2);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 15px;
        }

        .checkbox-group input {
            width: 20px;
            height: 20px;
            accent-color: #C6A43F;
        }

        .checkbox-group label {
            color: white;
            font-weight: 500;
        }

        .btn-group {
            display: flex;
            gap: 20px;
            margin-top: 30px;
        }

        .btn-save {
            background: linear-gradient(135deg, #C6A43F, #FFD700);
            border: none;
            padding: 14px 30px;
            border-radius: 50px;
            color: #050508;
            font-weight: 800;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            flex: 1;
        }

        .btn-save:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(198,164,63,0.4);
        }

        .btn-cancel {
            background: rgba(255,68,68,0.15);
            border: 1px solid #FF4444;
            padding: 14px 30px;
            border-radius: 50px;
            color: #FF8888;
            text-decoration: none;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s;
            flex: 1;
        }

        .btn-cancel:hover {
            background: #FF4444;
            color: white;
            transform: translateY(-3px);
        }

        .error-message {
            background: rgba(255,68,68,0.1);
            border: 1px solid #FF4444;
            color: #FF8888;
            padding: 12px;
            border-radius: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 25px;
            }
            .btn-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
<div class="royal-bg"></div>

<div class="container">
    <div class="edit-card">
        <div class="card-header">
            <h1><i class="fas fa-edit"></i> Edit Product</h1>
        </div>
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php

endif; ?>
            
            <form method="POST">
                <div class="form-grid">
                    <div class="input-group">
                        <label><i class="fas fa-gem"></i> Product Name</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                    </div>
                    
                    <div class="input-group">
                        <label><i class="fas fa-building"></i> Brand</label>
                        <select name="brand_id" required>
                            <?php while($b = mysqli_fetch_assoc($brands_list)): ?>
                                <option value="<?php echo $b['id']; ?>" <?php echo ($b['id'] == $product['brand_id']) ? 'selected' : ''; ?>>
                                    <?php echo $b['name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="input-group">
                        <label><i class="fas fa-gem"></i> Gemstone</label>
                        <select name="gemstone_id" required>
                            <?php while($g = mysqli_fetch_assoc($gemstones_list)): ?>
                                <option value="<?php echo $g['id']; ?>" <?php echo ($g['id'] == $product['gemstone_id']) ? 'selected' : ''; ?>>
                                    <?php echo $g['name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="input-group">
                        <label><i class="fas fa-tag"></i> Category</label>
                        <select name="category" required>
                            <option value="ring" <?php echo ($product['category'] == 'ring') ? 'selected' : ''; ?>>💍 Ring</option>
                            <option value="necklace" <?php echo ($product['category'] == 'necklace') ? 'selected' : ''; ?>>📿 Necklace</option>
                            <option value="earring" <?php echo ($product['category'] == 'earring') ? 'selected' : ''; ?>>✨ Earring</option>
                            <option value="bracelet" <?php echo ($product['category'] == 'bracelet') ? 'selected' : ''; ?>>💫 Bracelet</option>
                        </select>
                    </div>
                    
                    <div class="input-group">
                        <label><i class="fas fa-coins"></i> Metal</label>
                        <select name="metal" required>
                            <option value="gold_18k" <?php echo ($product['metal'] == 'gold_18k') ? 'selected' : ''; ?>>18K Gold</option>
                            <option value="gold_22k" <?php echo ($product['metal'] == 'gold_22k') ? 'selected' : ''; ?>>22K Gold</option>
                            <option value="platinum" <?php echo ($product['metal'] == 'platinum') ? 'selected' : ''; ?>>Platinum</option>
                            <option value="silver" <?php echo ($product['metal'] == 'silver') ? 'selected' : ''; ?>>Silver</option>
                            <option value="rose_gold" <?php echo ($product['metal'] == 'rose_gold') ? 'selected' : ''; ?>>Rose Gold</option>
                        </select>
                    </div>
                    
                    <div class="input-group">
                        <label><i class="fas fa-weight-hanging"></i> Carat</label>
                        <input type="number" step="0.01" name="carat" value="<?php echo $product['carat']; ?>">
                    </div>
                    
                    <div class="input-group">
                        <label><i class="fas fa-dollar-sign"></i> Price (USD)</label>
                        <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>
                    </div>
                    
                    <div class="input-group">

<label><i class="fas fa-image"></i> Image</label>
                        <input type="text" name="image" value="<?php echo htmlspecialchars($product['image']); ?>" placeholder="filename.jpg">
                    </div>
                    
                    <div class="input-group">
                        <label><i class="fas fa-align-left"></i> Description</label>
                        <textarea name="description" rows="3"><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" name="is_diamond" id="is_diamond" <?php echo ($product['is_diamond'] == 1) ? 'checked' : ''; ?>>
                        <label for="is_diamond">💎 This is a Diamond product</label>
                    </div>
                </div>
								<div class="input-luxury">
    <label for="stock" style="color: #C6A43F;"><i class="fas fa-boxes"></i> Stock Quantity:</label>
    <input type="number" name="stock" value="10" min="0" required>
</div>
                
                <div class="btn-group">
                    <button type="submit" class="btn-save"><i class="fas fa-save"></i> Save Changes</button>
                    <a href="admin.php" class="btn-cancel"><i class="fas fa-times"></i> Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>