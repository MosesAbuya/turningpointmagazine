<?php
include('connection2.php');
$pdo = connect();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Organization ID is required.');
}

$org_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM organizations WHERE id = ? AND status = 'Approved'");
$stmt->execute([$org_id]);
$organization = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$organization) {
    die('Organization not found or not approved.');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($organization['name']) ?> - Turning Point Magazine</title>

    <link rel="apple-touch-icon" sizes="180x180" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>favicon-16x16.png">
    <link rel="manifest" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>site.webmanifest">

    <link rel="stylesheet" href="includes/tp-navbar.css">
    <link rel="stylesheet" href="tp-design-system.css">
    <link rel="stylesheet" href="global.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&display=swap" rel="stylesheet">

    <style>
    @font-face {
        font-family: florania;
        src: url(assets/fonts/florania.otf) format("opentype");
    }

    /* --- Page-specific override --- */
    body {
        padding-top: 0 !important;
    }

    /* --- ANIMATIONS --- */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes drawUnderline {
        from {
            width: 0;
        }

        to {
            width: 100%;
        }
    }

    .fade-in-up {
        opacity: 0;
        animation: fadeInUp 0.6s ease-out forwards;
    }

    /* --- 1. Navbar Background Image Layer --- */
    .breadcrumb-container {
        /* Image and Dark Overlay */
        background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/breadcrumbs/bread.jpeg');
        background-size: cover;
        background-position: center;
        position: absolute;
        z-index: 1;
        top: 0;
        width: 100%;
        height: 150px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
    }

    /* --- 2. NEW "Fun Intro" Section --- */
    .tp-fun-intro {
        position: relative;
        z-index: 2;
        margin-top: 0;
        background: var(--brand-white);
        padding: 100px 20px 60px 20px;
        text-align: center;
        border-bottom: 1px solid #eee;
    }

    .tp-intro-main {
        font-family: 'florania', sans-serif;
        font-size: 3.5rem;
        font-weight: 700;
        color: var(--brand-teal, #008080);
        margin: 0;
        line-height: 1.2;
    }

    .tp-intro-sub {
        font-family: 'Caveat', cursive;
        font-size: 3.8rem;
        color: var(--brand-pink, #E6007E);
        margin: -10px 0 15px 0;
        line-height: 1;
    }

    .tp-intro-nav {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-light, #666);
    }

    .tp-intro-nav a {
        color: var(--brand-red, #ff0000);
        text-decoration: none;
        transition: opacity 0.3s ease;
    }

    .tp-intro-nav a:hover {
        opacity: 0.7;
    }

    .tp-intro-nav .fa-chevron-right {
        font-size: 0.8em;
        margin: 0 8px;
        color: var(--text-light, #666);
    }

    .directory-detail-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.05);
    }

    .directory-header {
        display: flex;
        align-items: center;
        margin-bottom: 40px;
        padding-bottom: 30px;
        border-bottom: 2px solid #f0f0f0;
    }

    .directory-logo-wrapper {
        flex: 0 0 180px;
        height: 180px;
        margin-right: 40px;
        padding: 15px;
        border: 1px solid #eee;
        border-radius: 12px;
        background: #f9f9f9;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .directory-logo {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .directory-title h1 {
        font-family: 'florania', sans-serif;
        font-size: 3rem;
        margin: 0 0 10px 0;
        color: #333;
        position: relative;
        display: inline-block;
    }

    .directory-title h1::after {
        content: "";
        position: absolute;
        bottom: 5px;
        left: 0;
        width: 100%;
        height: 8px;
        background-color: rgba(255, 215, 0, 0.4);
        z-index: -1;
        border-radius: 4px;
        transform: rotate(-1deg);
    }

    .directory-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 15px;
    }

    .meta-tag {
        background: #f0f0f0;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.9rem;
        color: #555;
        display: flex;
        align-items: center;
    }

    .meta-tag i {
        color: #ff0000;
        margin-right: 8px;
    }

    .content-section {
        margin-bottom: 35px;
    }

    .section-title {
        font-family: 'florania', sans-serif;
        font-size: 2rem;
        color: #008080;
        margin-bottom: 15px;
        position: relative;
        display: inline-block;
    }

    .section-title::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        height: 4px;
        background-color: #E6007E;
        width: 0;
        animation: drawUnderline 0.8s ease-out forwards;
        animation-delay: 0.5s;
    }

    .directory-content p {
        line-height: 1.8;
        color: #444;
        font-size: 1.05rem;
        margin-bottom: 15px;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        background: #f9f9f9;
        padding: 25px;
        border-radius: 10px;
        border: 1px solid #eee;
    }

    .contact-item {
        display: flex;
        align-items: flex-start;
    }

    .contact-icon {
        width: 40px;
        height: 40px;
        background: rgba(255, 0, 0, 0.1);
        color: #ff0000;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        flex-shrink: 0;
    }

    .contact-details h4 {
        margin: 0 0 5px 0;
        font-size: 0.9rem;
        color: #777;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .contact-details p,
    .contact-details a {
        margin: 0;
        font-size: 1rem;
        color: #333;
        text-decoration: none;
        font-weight: 600;
        word-break: break-word;
    }

    .contact-details a:hover {
        color: #ff0000;
        text-decoration: underline;
    }

    .social-links {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }

    .social-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #f0f0f0;
        color: #555;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }

    .social-btn:hover {
        background: #ff0000;
        color: white;
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(255, 0, 0, 0.3);
    }

    /* --- RESPONSIVE STYLES --- */
    @media (max-width: 768px) {
        .breadcrumb-container {
            height: 75px;
        }

        .tp-fun-intro {
            padding: 80px 20px 40px 20px;
        }

        .tp-intro-main {
            font-size: 2.8rem;
        }

        .tp-intro-sub {
            font-size: 3rem;
        }

        .directory-header {
            flex-direction: column;
            text-align: center;
        }

        .directory-logo-wrapper {
            margin-right: 0;
            margin-bottom: 25px;
        }

        .directory-meta {
            justify-content: center;
        }

        .directory-title h1 {
            font-size: 2.5rem;
        }

        .contact-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .tp-fun-intro {
            padding: 60px 20px 30px 20px;
        }

        .tp-intro-main {
            font-size: 2.2rem;
        }

        .tp-intro-sub {
            font-size: 2.5rem;
        }

        .directory-title h1 {
            font-size: 2rem;
        }

        .section-title {
            font-size: 1.8rem;
        }

        .directory-content p {
            font-size: 1rem;
        }
    }
    </style>
</head>

<body>
    <?php include 'includes/preloader.php'; ?>
    <?php include 'includes/new-navbar.php'; ?>

    <section class="tp-fun-intro fade-in-up" style="animation-delay: 0.2s;">
        <h1 class="tp-intro-main">Directory</h1>
        <h2 class="tp-intro-sub"><?= htmlspecialchars($organization['name']) ?></h2>
        <nav class="tp-intro-nav">
            <a href="index.php">Home</a>
            <i class="fas fa-chevron-right"></i>
            <a href="directories.php">Directories</a>
            <i class="fas fa-chevron-right"></i>
            <span>Details</span>
        </nav>
    </section>

    <main class="directory-detail-container fade-in-up" style="animation-delay: 0.2s;">
        <div class="directory-header">
            <div class="directory-logo-wrapper">
                <?php if (!empty($organization['logo_url'])): ?>
                <img loading="lazy" src="<?= htmlspecialchars($organization['logo_url']) ?>"
                    alt="<?= htmlspecialchars($organization['name']) ?> Logo" class="directory-logo">
                <?php else: ?>
                <i class="fas fa-building" style="font-size: 4rem; color: #ddd;"></i>
                <?php endif; ?>
            </div>
            <div class="directory-title">
                <h1><?= htmlspecialchars($organization['name']) ?></h1>
                <div class="directory-meta">
                    <?php if(!empty($organization['type'])): ?>
                    <div class="meta-tag"><i class="fas fa-tag"></i> <?= htmlspecialchars($organization['type']) ?>
                    </div>
                    <?php endif; ?>
                    <?php if(!empty($organization['sector'])): ?>
                    <div class="meta-tag"><i class="fas fa-briefcase"></i>
                        <?= htmlspecialchars($organization['sector']) ?></div>
                    <?php endif; ?>
                    <?php if(!empty($organization['country'])): ?>
                    <div class="meta-tag"><i class="fas fa-map-marker-alt"></i>
                        <?= htmlspecialchars($organization['country']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="directory-content">
            <?php if (!empty($organization['description'])): ?>
            <div class="content-section fade-in-up" style="animation-delay: 0.3s;">
                <h2 class="section-title">About Us</h2>
                <p><?= nl2br(htmlspecialchars($organization['description'])) ?></p>
            </div>
            <?php endif; ?>

            <?php if (!empty($organization['mission'])): ?>
            <div class="content-section fade-in-up" style="animation-delay: 0.4s;">
                <h2 class="section-title">Our Mission</h2>
                <p><?= nl2br(htmlspecialchars($organization['mission'])) ?></p>
            </div>
            <?php endif; ?>

            <?php if (!empty($organization['vision'])): ?>
            <div class="content-section fade-in-up" style="animation-delay: 0.5s;">
                <h2 class="section-title">Our Vision</h2>
                <p><?= nl2br(htmlspecialchars($organization['vision'])) ?></p>
            </div>
            <?php endif; ?>

            <?php if (!empty($organization['services'])): ?>
            <div class="content-section fade-in-up" style="animation-delay: 0.6s;">
                <h2 class="section-title">Our Services</h2>
                <p><?= nl2br(htmlspecialchars($organization['services'])) ?></p>
            </div>
            <?php endif; ?>

            <div class="content-section fade-in-up" style="animation-delay: 0.7s;">
                <h2 class="section-title">Get in Touch</h2>
                <div class="contact-grid">
                    <?php if (!empty($organization['email'])): ?>
                    <div class="contact-item">
                        <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                        <div class="contact-details">
                            <h4>Email</h4>
                            <a
                                href="mailto:<?= htmlspecialchars($organization['email']) ?>"><?= htmlspecialchars($organization['email']) ?></a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($organization['phone'])): ?>
                    <div class="contact-item">
                        <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                        <div class="contact-details">
                            <h4>Phone</h4>
                            <p><?= htmlspecialchars($organization['phone']) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($organization['website'])): ?>
                    <div class="contact-item">
                        <div class="contact-icon"><i class="fas fa-globe"></i></div>
                        <div class="contact-details">
                            <h4>Website</h4>
                            <a href="<?= htmlspecialchars($organization['website']) ?>" target="_blank"
                                rel="noopener noreferrer">Visit Website</a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($organization['address'])): ?>
                    <div class="contact-item">
                        <div class="contact-icon"><i class="fas fa-map-pin"></i></div>
                        <div class="contact-details">
                            <h4>Address</h4>
                            <p><?= nl2br(htmlspecialchars($organization['address'])) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($organization['facebook_url']) || !empty($organization['twitter_url']) || !empty($organization['linkedin_url']) || !empty($organization['instagram_url'])): ?>
            <div class="content-section fade-in-up" style="animation-delay: 0.8s;">
                <h2 class="section-title">Connect With Us</h2>
                <div class="social-links">
                    <?php if (!empty($organization['facebook_url'])): ?>
                    <a href="<?= htmlspecialchars($organization['facebook_url']) ?>" target="_blank" class="social-btn"
                        title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($organization['twitter_url'])): ?>
                    <a href="<?= htmlspecialchars($organization['twitter_url']) ?>" target="_blank" class="social-btn"
                        title="Twitter / X"><i class="fab fa-twitter"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($organization['linkedin_url'])): ?>
                    <a href="<?= htmlspecialchars($organization['linkedin_url']) ?>" target="_blank" class="social-btn"
                        title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($organization['instagram_url'])): ?>
                    <a href="<?= htmlspecialchars($organization['instagram_url']) ?>" target="_blank" class="social-btn"
                        title="Instagram"><i class="fab fa-instagram"></i></a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>

</html>

