<?php include('connection2.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="description" content="Privacy Policy for Turning Point Magazine" />
    <title>Privacy Policy - Turning Point Magazine</title>
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
        <h1 class="legal-title">Privacy Policy</h1>
        <div class="legal-content">
            <p><strong>Last Updated: October 2026</strong></p>
            <p>Welcome to Turning Point Magazine. We respect your privacy and are committed to protecting your personal data.</p>
            <h2>1. Information We Collect</h2>
            <p>We may collect personal information such as your name, email address, and demographic information when you subscribe to our newsletter, contact us, or submit a story.</p>
            <h2>2. How We Use Your Information</h2>
            <p>We use your information to provide updates on our publications, respond to your inquiries, and improve our website's user experience. We do not sell your personal data to third parties.</p>
            <h2>3. Cookies and Tracking</h2>
            <p>Our website may use cookies to enhance your browsing experience. You can choose to disable cookies through your browser settings.</p>
            <h2>4. Data Security</h2>
            <p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>
            <h2>5. Contact Us</h2>
            <p>If you have any questions about this Privacy Policy, please contact us via our <a href="contact.php" style="color:var(--brand-red);">Contact Page</a>.</p>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
