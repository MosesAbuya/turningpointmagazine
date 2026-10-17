<?php include('connection2.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="description" content="Terms and Conditions for Turning Point Magazine" />
    <title>Terms and Conditions - Turning Point Magazine</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>assets/favicon/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>assets/favicon/apple-touch-icon.png">
    <link rel="manifest" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>assets/favicon/site.webmanifest">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="includes/new-navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="global.css">
    
    <style>
        @font-face {
            font-family: florania;
            src: url("<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>assets/fonts/florania.otf") format("opentype");
        }
        :root {
            --brand-red: #ff0000;
            --brand-teal: #008080;
            --brand-pink: #E6007E;
            --bg-off-white: #f8f9fa;
        }
        body { padding-top: 0 !important; font-family: 'Poppins', sans-serif; background: var(--bg-off-white); }
        .legal-container { max-width: 900px; margin: 120px auto 60px auto; padding: 40px; background: #fff; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .legal-title { font-family: 'florania', sans-serif; color: var(--brand-teal); font-size: 3rem; margin-bottom: 20px; text-align: center; }
        .legal-content h2 { font-size: 1.5rem; color: #333; margin-top: 30px; }
        .legal-content p { color: #555; line-height: 1.8; margin-bottom: 15px; }
    </style>
</head>
<body>
    <?php include 'includes/preloader.php'; ?>
    <?php include 'includes/new-navbar.php'; ?>

    <main class="legal-container">
        <h1 class="legal-title">Terms and Conditions</h1>
        <div class="legal-content">
            <p><strong>Last Updated: October 2026</strong></p>
            <p>Welcome to Turning Point Magazine. By accessing or using our website, you agree to be bound by these Terms and Conditions.</p>
            <h2>1. Use of Website</h2>
            <p>You agree to use this website for lawful purposes only and in a way that does not infringe the rights of, restrict, or inhibit anyone else's use and enjoyment of the website.</p>
            <h2>2. Intellectual Property</h2>
            <p>All content on this website, including articles, images, logos, and design, is the property of Turning Point Magazine or its content suppliers and is protected by copyright laws.</p>
            <h2>3. User Submissions</h2>
            <p>When you submit stories, images, or feedback to us, you grant Turning Point Magazine a non-exclusive, royalty-free license to use, reproduce, and publish that material across our platforms.</p>
            <h2>4. Disclaimer</h2>
            <p>The materials on Turning Point Magazine's website are provided on an 'as is' basis. We make no warranties, expressed or implied, and hereby disclaim and negate all other warranties.</p>
            <h2>5. Changes to Terms</h2>
            <p>We may revise these Terms and Conditions at any time without notice. By using this website, you agree to be bound by the current version of these Terms and Conditions.</p>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
