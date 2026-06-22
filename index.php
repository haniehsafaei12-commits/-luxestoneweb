<?php
include 'connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LuxeStone | Luxury Jewelry Store</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
	<style>
		body {
    background: linear-gradient(135deg, #0A0A0A 0%, #1A120B 50%, #0D0D1A 100%);
    min-height: 100vh;
    margin: 0;
    padding: 0;
    font-family: 'Playfair Display', 'Montserrat', sans-serif;
}

.welcome-section {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 80vh;
    text-align: center;
}

.welcome-text h1 {
    color: #C6A43F;
    font-size: 56px;
    font-family: 'Playfair Display', serif;
    margin-bottom: 20px;
    letter-spacing: 2px;
}

.welcome-text p {
    color: #CCCCCC;
    font-size: 20px;
    font-family: 'Montserrat', sans-serif;
    letter-spacing: 1px;
}

.gold-line {
    width: 80px;
    height: 2px;
    background: #C6A43F;
    margin: 20px auto;
}body {
    background: linear-gradient(135deg, #0A0A0A 0%, #1A120B 50%, #0D0D1A 100%);
    min-height: 100vh;
    margin: 0;
    padding: 0;
    font-family: 'Playfair Display', 'Montserrat', sans-serif;
}



.welcome-text h1 {
    color: #C6A43F;
    font-size: 56px;
    font-family: 'Playfair Display', serif;
    margin-bottom: 20px;
    letter-spacing: 2px;
}

.welcome-text p {
    color: #CCCCCC;
    font-size: 20px;
    font-family: 'Montserrat', sans-serif;
    letter-spacing: 1px;
}

.gold-line {
    width: 80px;
    height: 2px;
    background: #C6A43F;
    margin: 20px auto;
}body {
    background: linear-gradient(135deg, #0A0A0A 0%, #1A120B 50%, #0D0D1A 100%);
    min-height: 100vh;
    margin: 0;
    padding: 0;
    font-family: 'Playfair Display', 'Montserrat', sans-serif;
}

/* متن خوش‌آمدگویی وسط صفحه */
.welcome-section {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 80vh;
    text-align: center;
}

.welcome-text h1 {
    color: #C6A43F;
    font-size: 56px;
    font-family: 'Playfair Display', serif;
    margin-bottom: 20px;
    letter-spacing: 2px;
}

.welcome-text p {
    color: #CCCCCC;
    font-size: 20px;
    font-family: 'Montserrat', sans-serif;
    letter-spacing: 1px;
}

.gold-line {
    width: 80px;
    height: 2px;
    background: #C6A43F;
    margin: 20px auto;
}/* CSS Document */
/* دکمه ثبت نام متحرک */
.diamond-link {
    text-decoration: none;
    display: inline-block;
}

.diamond-wrapper {
    text-align: center;
}

.golden-diamond {
    font-size: 80px;
    display: inline-block;
    animation: bounce 1.5s ease-in-out infinite;
    filter: drop-shadow(0 0 8px #C6A43F);
    text-shadow: 0 0 10px rgba(198, 164, 63, 0.8);
	margin-top: 100px
}

.static-text {
    color: #C6A43F;
    font-size: 18px;
    font-weight: 600;
    letter-spacing: 2px;
    margin-top: 50px;
}

@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-15px);
    }
}</style>

<body>

    <div class="welcome-section">
        <div class="welcome-text">
            <h1>💎LuxeStone</h1>
            <div class="gold-line"></div>
            <p>Discover Timeless Elgance & Brilliance.</p>
            
  <a href="register.php" class="diamond-link">
    <div class="diamond-wrapper">
        <div class="golden-diamond">👑</div>
        <div class="static-text">CREATE AN ACCOUNT</div>
    </div>
</a>
</body>
</html>