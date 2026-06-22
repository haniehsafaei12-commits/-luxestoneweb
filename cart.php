<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$cart_items=$_SESSION['cart'];
$total=0;
foreach($cart_items as $item){
	$total+=$item['price']*$item['quantity'];
}

if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
    header("Location: cart.php");
    exit();
}
?>
<?php if (isset($_GET['error'])): ?>
    <div style="background: rgba(255,0,0,0.2); border: 1px solid #FF4444; color: #FF8888; padding: 15px; border-radius: 12px; margin-bottom: 20px; text-align: center;">
        <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
<?php endif; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart | LuxeStone</title>
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
            z-index: -1;
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

        @keyframes rotateDiamond {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(198, 164, 63, 0.2);
            padding: 20px 40px;
            margin-bottom: 40px;
            border-radius: 50px;
            margin-top: 20px;
        }

        .header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, #C6A43F, #FFD700);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }

        .nav-links a {
            color: #CCCCCC;
            text-decoration: none;
            margin-left: 25px;
            transition: 0.3s;
        }

        .nav-links a:hover {
            color: #C6A43F;
        }

        .cart-card {
            background: rgba(20, 20, 30, 0.6);
            backdrop-filter: blur(15px);
            border-radius: 32px;
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

.cart-header {
            background: linear-gradient(135deg, rgba(198,164,63,0.15), rgba(198,164,63,0.05));
            padding: 25px 30px;
            border-bottom: 1px solid rgba(198,164,63,0.2);
        }

        .cart-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #C6A43F;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .cart-header h1 i {
            font-size: 36px;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cart-table th {
            text-align: left;
            padding: 20px 30px;
            color: #C6A43F;
            font-weight: 600;
            border-bottom: 1px solid rgba(198,164,63,0.2);
        }

        .cart-table td {
            padding: 25px 30px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            vertical-align: middle;
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .product-icon {
            width: 60px;
            height: 60px;
            background: rgba(198,164,63,0.1);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
        }

        .product-name {
            font-weight: 600;
            color: white;
        }

        .product-price {
            color: #C6A43F;
            font-weight: 600;
        }

        .quantity-input {
            width: 80px;
            padding: 8px 12px;
            background: rgba(0,0,0,0.5);
            border: 1px solid rgba(198,164,63,0.5);
            border-radius: 12px;
            color: white;
            font-size: 14px;
            text-align: center;
        }

        .quantity-input:focus {
            outline: none;
            border-color: #C6A43F;
        }

        .subtotal {
            color: #FFD700;
            font-weight: 700;
        }

        .btn-remove {
            background: rgba(255,68,68,0.15);
            border: 1px solid #FF4444;
            color: #FF8888;
            padding: 8px 15px;
            border-radius: 25px;
            font-size: 12px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-remove:hover {
            background: #FF4444;
            color: white;
        }

        .cart-footer {
            background: rgba(0,0,0,0.3);
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .total-box {
            text-align: right;
        }

        .total-label {
            font-size: 14px;
            color: #888;
            letter-spacing: 1px;
        }

        .total-amount {
            font-size: 36px;
            font-weight: 800;
            color: #C6A43F;
        }

        .total-amount small {
            font-size: 14px;
            font-weight: normal;
        }

        .btn-update {
            background: rgba(198,164,63,0.15);
            border: 1px solid #C6A43F;
            color: #C6A43F;
            padding: 12px 25px;
            border-radius: 40px;
            cursor: pointer;
            transition: 0.3s;
            font-weight: 600;
        }

        .btn-update:hover {
            background: #C6A43F;
            color: #0A0A0A;
        }

        .btn-checkout {
            background: linear-gradient(135deg, #C6A43F, #FFD700);
            border: none;
            color: #0A0A0A;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-checkout:hover {
            transform: translateY(-

3px);
            box-shadow: 0 5px 20px rgba(198,164,63,0.4);
        }

        .empty-cart {
            text-align: center;
            padding: 60px 30px;
        }

        .empty-cart i {
            font-size: 80px;
            color: rgba(198,164,63,0.3);
            margin-bottom: 20px;
        }

        .empty-cart p {
            color: #888;
            font-size: 18px;
            margin-bottom: 30px;
        }

        .btn-shop {
            background: transparent;
            border: 2px solid #C6A43F;
            color: #C6A43F;
            padding: 12px 30px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-shop:hover {
            background: #C6A43F;
            color: #0A0A0A;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #1A1A1A;
        }
        ::-webkit-scrollbar-thumb {
            background: #C6A43F;
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .cart-table th, .cart-table td {
                padding: 15px;
            }
            .product-info {
                flex-direction: column;
                text-align: center;
            }
            .cart-footer {
                flex-direction: column;
                text-align: center;
            }
            .nav-links {
                margin-top: 15px;
            }
        }
    </style>
</head>
<body>
<div class="bg-diamonds"></div>

<div class="header">
    <div class="container">
        <a href="products.php" class="logo">💎 LUXESTONE</a>
        <div class="nav-links">
            <a href="products.php"><i class="fas fa-gem"></i> Shop</a>
            <a href="cart.php" style="color:#C6A43F;"><i class="fas fa-shopping-cart"></i> Cart</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="cart-card">
        <div class="cart-header">
            <h1><i class="fas fa-shopping-cart"></i> Your Shopping Cart</h1>
        </div>

        <?php if (empty($cart_items)): ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-basket"></i>
                <p>Your cart is empty</p>
                <a href="products.php" class="btn-shop">✨ Start Shopping ✨</a>
            </div>
        <?php else: ?>
            <form method="POST" action="update_cart.php">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $id => $item): 
                            $subtotal = $item['price'] * $item['quantity'];
                        ?>
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <div class="product-icon">
                                            <?php if ($item['image'] && file_exists("images/".$item['image'])): ?>
                                                <img src="images/<?php echo $item['image']; ?>" style="width:50px; height:50px; object-fit:cover; border-radius:12px;">
                                            <?php else: ?>
                                                💎
                                            <?php endif; ?>
                                        </div>
                                        <div class="product-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                    </div>
                                </td>
                                <td class="product-price">$<?php echo number_format($item['price'

]); ?></td>
                                <td>
                                    <input type="number" name="quantity[<?php echo $id; ?>]" value="<?php echo $item['quantity']; ?>" min="1" class="quantity-input">
                                </td>
                                <td class="subtotal">$<?php echo number_format($subtotal); ?></td>
                                <td>
                                    <a href="?remove=<?php echo $id; ?>" class="btn-remove" onclick="return confirm('Remove this item?')"><i class="fas fa-trash-alt"></i> Remove</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="cart-footer">
                    <button type="submit" name="update_cart" class="btn-update">
                        <i class="fas fa-sync-alt"></i> Update Cart
                    </button>
                    <div class="total-box">
                        <div class="total-label">Total Amount</div>
                        <div class="total-amount">$<?php echo number_format($total); ?> <small>USD</small></div>
                    </div>
                </div>
            </form>

            <div style="padding: 0 30px 30px 30px; text-align: right;">
                <a href="checkout.php" class="btn-checkout">
                    Proceed to Checkout <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>