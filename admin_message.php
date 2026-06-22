<?php
session_start();
include 'connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php");
    exit();
}

// Delete message
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM contacts WHERE id = $id");
    header("Location: admin_messages.php");
    exit();
}

// Get all messages
$query = "SELECT * FROM contacts ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Contact Messages | Admin</title>
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

        @keyframes rotateSlow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
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
            width: 3px;
            height: 3px;
            background: radial-gradient(circle, #FFD700, #C6A43F);
            border-radius: 50%;
            opacity: 0;
            box-shadow: 0 0 6px #C6A43F;
            animation: floatGold 12s ease-in-out infinite;
        }

        @keyframes floatGold {
            0% {
                opacity: 0;
                transform: translateY(100vh) scale(0);
            }
            15% {
                opacity: 0.6;
            }
            85% {
                opacity: 0.3;
            }
            100% {
                opacity: 0;
                transform: translateY(-100vh) scale(1);
            }
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .glass-header {
            background: rgba(10, 10, 10, 0.75);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(198, 164, 63, 0.35);
            border-radius: 70px;
            padding: 18px 35px;
            margin-bottom: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
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
            gap: 25px;
            flex-wrap: wrap;
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

        .premium-card {
            background: rgba(8, 8, 12, 0.75);
            backdrop-filter: blur(18px);
            border-radius: 36px;
            border: 1px solid rgba(198, 164, 63, 0.25);
            overflow: hidden;
            margin-bottom: 40px;
            transition: all 0.4s;
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
            padding: 25px;
        }

        .table-wrapper {
            overflow-x: auto;
            border-radius: 20px;
            -webkit-overflow-scrolling: touch;
        }

        .messages-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
        }

        .messages-table th {
            text-align: left;
            padding: 16px 12px;
            color: #C6A43F;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid rgba(198,164,63,0.25);
        }

        .messages-table td {
            padding: 14px 12px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            vertical-align: top;
            color: #CCCCCC;
            font-size: 13px;
        }

        .messages-table tr:hover {
            background: rgba(198,164,63,0.05);
        }

        .message-subject {
            color: white;
            font-weight: 600;
        }

        .delete-btn {
            background: rgba(255,68,68,0.15);
            border: 1px solid #FF4444;
            color: #FF8888;
            padding: 6px 14px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
        }

        .delete-btn:hover {
            background: #FF4444;
            color: white;
            transform: translateY(-2px);
        }

        .empty-message {
            text-align: center;
            padding: 60px;
            color: #888;
        }

        .badge-new {
            background: #C6A43F;
            color: #0A0A0A;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .glass-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            .card-gold-header h2 {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
<div class="royal-bg"></div>
<div class="gold-dust" id="goldDust"></div>

<div class="container">
    <div class="glass-header">
        <a href="admin.php" class="logo">💎 LUXESTONE ADMIN</a>
        <div class="nav-links">
            <a href="admin.php"><i class="fas fa-dashboard"></i>Home</a>
            <a href="admin_messages.php" style="color:#C6A43F;"><i class="fas fa-envelope"></i> Messages</a>
            <a href="?logout=1" style="color:#FF8888;"><i class="fas fa-sign-out-alt"></i> Exit</a>
        </div>
    </div>

    <div class="premium-card">
        <div class="card-gold-header">
            <h2><i class="fas fa-envelope"></i> Contact Messages</h2>
        </div>
        <div class="card-body-luxury">
            <div class="table-wrapper">
                <?php if (mysqli_num_rows($result) == 0): ?>
                    <div class="empty-message">
                        <i class="fas fa-inbox" style="font-size: 50px; opacity: 0.5; margin-bottom: 15px; display: block;"></i>
                        No messages yet.
                    </div>
                <?php else: ?>
                    <table class="messages-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td class="message-subject"><?php echo nl2br(htmlspecialchars(substr($row['message'], 0, 100))); ?><?php echo strlen($row['message']) > 100 ? '...' : ''; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <a href="?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Delete this message?')">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    function createGoldDust() {
        const container = document.getElementById('goldDust');
        for (let i = 0; i < 80; i++) {
            const dust = document.createElement('div');
            dust.classList.add('dust');
            dust.style.left = Math.random() * 100 + '%';
            dust.style.animationDelay = Math.random() * 12 + 's';
            dust.style.animationDuration = 6 + Math.random() * 10 + 's';
            container.appendChild(dust);
        }
    }
    createGoldDust();
</script>
</body>
</html>