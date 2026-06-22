<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id == 0) {
    header("Location: products.php");
    exit();
}


$query = "SELECT id, name, price, image FROM products_jewellery WHERE id = $product_id";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    header("Location: products.php");
    exit();
}

if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += 1;
} else {
    $_SESSION['cart'][$product_id] = [
        'id'       => $product['id'],
        'name'     => $product['name'],
        'price'    => $product['price'],
        'quantity' => 1,
        'image'    => $product['image']
    ];
}

$product_name = $product['name'];
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            background: #0A0A0A;
            font-family: 'Montserrat', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .cart-popup {
            background: rgba(20,20,30,0.95);
            backdrop-filter: blur(10px);
            border: 2px solid #C6A43F;
            border-radius: 24px;
            padding: 30px;
            text-align: center;
            max-width: 300px;
        }
        .cart-popup h3 {
            color: #C6A43F;
            margin-bottom: 20px;
        }
        .cart-icon {
            font-size: 60px;
            margin: 20px 0;
            animation: bounce 0.5s ease;
        }
        @keyframes bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
        .btn-cart {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            background: #C6A43F;
            color: #0A0A0A;
            text-decoration: none;
            border-radius: 40px;
            font-weight: bold;
        }
        .btn-continue {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            background: transparent;
            border: 1px solid #C6A43F;
            color: #C6A43F;
            text-decoration: none;
            border-radius: 40px;
        }
    </style>
</head>
<body>
<div class="cart-popup">
    <div class="cart-icon">🎁🛒✨</div>
    <h3>Added to Cart!</h3>
    <p><?php echo htmlspecialchars($product_name); ?> has been added.</p>
    <a href="cart.php" class="btn-cart">View Cart</a>
    <a href="products.php" class="btn-continue">Continue Shopping</a>
</div>
</body>
</html>