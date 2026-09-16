<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Turning Point Magazine - Payment Status</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../global.css">
    <style>
        .status-container {
            width: 90%;
            max-width: 600px;
            margin: 50px auto;
            text-align: center;
            padding: 40px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
        }
        .status-container h1 {
            color: #28a745;
            font-size: 32px;
            margin-bottom: 20px;
        }
        .status-container p {
            font-size: 18px;
            color: #555;
            margin-bottom: 15px;
        }
        .status-container a {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background: #ff6600;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: 0.3s;
        }
        .status-container a:hover {
            background: #cc5500;
        }
    </style>
</head>
<body class="bg-light">
    <?php require_once ('inc/header.php'); ?>
    
    <div class="status-container">
        <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            <h1>Payment Successful! 🎉</h1>
            <p>Thank you for your purchase.</p>
            <?php if (isset($_GET['invoice'])): ?>
                <p>Your Invoice ID is: <strong><?php echo htmlspecialchars($_GET['invoice']); ?></strong></p>
            <?php endif; ?>
            <p>We've received your order and will contact you shortly.</p>
        <?php else: ?>
            <h1 style="color: #dc3545;">Payment Failed ❌</h1>
            <p>Unfortunately, your payment could not be processed.</p>
            <p>Please try again or contact support.</p>
        <?php endif; ?>
        
        <a href="index.php">Return to Shop</a>
    </div>
</body>
</html>
