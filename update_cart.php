<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$error = '';

if (isset($_POST['update_cart']) && isset($_POST['quantity'])) {
    foreach ($_POST['quantity'] as $id => $qty) {
        $id = (int)$id;
        $qty = (int)$qty;
        
        $stock_query = "SELECT stock FROM products_jewellery WHERE id = $id";
        $stock_result = mysqli_query($conn, $stock_query);
        $stock_row = mysqli_fetch_assoc($stock_result);
        $available_stock = $stock_row['stock'];
        
        if ($qty > $available_stock) {
            $product_name = $_SESSION['cart'][$id]['name'];
            $error = "❌ Only $available_stock units available for " . $product_name . "!";
            break;
        }
        
        if ($qty > 0 && isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] = $qty;
        } else {
            unset($_SESSION['cart'][$id]);
        }
    }
    
    if (!empty($error)) {
        header("Location: cart.php?error=" . urlencode($error));
        exit();
    }
}

header("Location: cart.php");
exit();
?>