<?php
session_start();
include 'connection.php';

$error = '';

if (isset($_POST['login_btn'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = 'Please fill all fields';
    } else {
        $query = "SELECT * FROM user_jewellery WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_name'] = $user['fullname'];
            $_SESSION['user_email'] = $user['email'];
            
            header("Location: products.php");
            exit();
        } else {
            $error = 'Invalid username or password';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Login | LuxeStone</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        .bg-diamond {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            overflow: hidden;
        }

        .bg-diamond::before {
            content: '💎';
            position: absolute;
            font-size: 400px;
            opacity: 0.03;
            bottom: -100px;
            right: -100px;
            animation: rotateSlow 40s linear infinite;
        }

        .bg-diamond::after {
            content: '✨';
            position: absolute;
            font-size: 250px;
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
            background: radial-gradient(circle, #FFD700, #C6A43F);
            border-radius: 50%;
            opacity: 0;
            box-shadow: 0 0 20px #C6A43F, 0 0 8px #FFD700;
            animation: floatParticle 12s ease-in-out infinite;
        }

        @keyframes floatParticle {
            0% {
                opacity: 0;
                transform: translateY(100vh) scale(0);
            }
            15% {
                opacity: 0.8;
            }
            85% {
                opacity: 0.4;
            }
            100% {
                opacity: 0;
                transform: translateY(-100vh) scale(1.5);
            }

        }

        .login-container {
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
            a

nimation: fadeInUp 0.7s ease-out;
            position: relative;
            z-index: 2;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-box {
            background: rgba(20, 20, 30, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(198, 164, 63, 0.4);
            border-radius: 32px;
            padding: 45px 40px;
            transition: all 0.4s ease;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .login-box:hover {
            border-color: rgba(198, 164, 63, 0.8);
            box-shadow: 0 25px 50px rgba(0,0,0,0.4), 0 0 25px rgba(198,164,63,0.1);
            transform: translateY(-3px);
        }

        .login-box h2 {
            color: #C6A43F;
            font-family: 'Playfair Display', serif;
            font-size: 34px;
            font-weight: 700;
            margin-bottom: 35px;
            text-align: center;
            letter-spacing: 1px;
        }

        .login-box h2 i {
            margin-right: 10px;
            font-size: 32px;
            animation: iconPulse 2s ease infinite;
        }

        @keyframes iconPulse {
            0%, 100% { transform: scale(1); text-shadow: 0 0 0 #C6A43F; }
            50% { transform: scale(1.05); text-shadow: 0 0 10px #C6A43F; }
        }

        .input-group {
            margin-bottom: 22px;
        }

        .input-group input {
            width: 100%;
            padding: 15px 20px;
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(198, 164, 63, 0.3);
            border-radius: 28px;
            color: #F5F5F5;
            font-size: 15px;
            font-family: 'Montserrat', sans-serif;
            transition: all 0.3s;
        }

        .input-group input:focus {
            outline: none;
            border-color: #C6A43F;
            box-shadow: 0 0 15px rgba(198, 164, 63, 0.25);
            background: rgba(0, 0, 0, 0.7);
        }

        .input-group input::placeholder {
            color: #888;
            font-weight: 400;
        }

        button {
            width: 100%;
            padding: 15px;
            background: rgba(198, 164, 63, 0.2);
            border: 2px solid #C6A43F;
            border-radius: 40px;
            color: #C6A43F;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 15px;
            position: relative;
            overflow: hidden;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }

        button:hover::before {
            left: 100%;
        }

        button:hover {
            background: #C6A43F;
            color: #0A0A0A;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(198, 164, 63, 0.3);
            letter-spacing: 1px;
        }

        .error {
            background: rgba(255, 68, 68, 0.12);
            border: 1px solid #FF4444;
            color: #FF8888;
            padding: 14px 18px;
            border-radius: 28px;
            margin-bottom: 25px;
            font-size: 14px;
            text-align: center;
            backdrop-filter: blur(4px);
        }

        .register-link {
            margin-top: 28px;
            color: #888;
            font-size: 14px;
            text-align: center;
        }


        .register-link a {
            color: #C6A43F;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .register-link a:hover {
            color: #FFD700;
            text-decoration: underline;
        }

        

        @media (max-width: 550px) {
            .login-box {
                padding: 30px 25px;
            }
            .login-box h2 {
                font-size: 28px;
                margin-bottom: 28px;
            }
            .input-group input {
                padding: 12px 18px;
                font-size: 14px;
            }
            button {
                padding: 13px;
                font-size: 15px;
            }
        }

        @media (max-width: 400px) {
            .login-box {
                padding: 25px 20px;
            }
            .login-box h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
<div class="bg-diamond"></div>
<div class="gold-particles" id="particles"></div>

<div class="login-container">
    <div class="login-box">
        <h2><i class="fas fa-unlock-alt"></i> Welcome Back</h2>
        
        <?php if($error): ?>
            <div class="error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="input-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" name="login_btn">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>
        
        <div class="register-link">
            Don't have an account? <a href="register.php">Create one here <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</div>

<script>
    function createParticles() {
        const container = document.getElementById('particles');
        const particleCount = 100;
        
        for(let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            
            particle.style.left = Math.random() * 100 + '%';
            particle.style.top = Math.random() * 100 + '%';
            
            const size = Math.random() * 12 + 8;
            particle.style.width = size + 'px';
            particle.style.height = size + 'px';
            
            particle.style.animationDelay = Math.random() * 15 + 's';
            particle.style.animationDuration = 8 + Math.random() * 14 + 's';
            
            container.appendChild(particle);
        }
    }
    createParticles();
</script>
</body>
</html>