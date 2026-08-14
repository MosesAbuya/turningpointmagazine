<?php
include('connection2.php');
$ad_id = isset($_GET['id']) ? $_GET['id'] : null;
$edition_id = isset($_GET['edition_id']) ? $_GET['edition_id'] : null;

$pdo = connect();

// --- Set Defaults ---
$adCompanyName = 'Our Partners';
$adCatchPhrase = 'Meet the organizations that support our mission.';
$adImage = 'assets/breadcrumbs/bread.jpeg'; // Default image
$adsList = [];
$articlesList = [];

// --- 1. Fetch Specific Partner (if ID exists) ---
if ($ad_id) {
    $adQuery = "SELECT ad_banner_image, ad_company_name, catch_phrase FROM ads WHERE id = :id";
    $stmtAd = $pdo->prepare($adQuery);
    $stmtAd->bindParam(':id', $ad_id, PDO::PARAM_INT);
    $stmtAd->execute();
    $ad = $stmtAd->fetch(PDO::FETCH_ASSOC);
    if ($ad) {
        $adImage = "admin/" . htmlspecialchars($ad['ad_banner_image']);
        $adCompanyName = htmlspecialchars($ad['ad_company_name']);
        $adCatchPhrase = htmlspecialchars($ad['catch_phrase']);
    }
}

// --- 2. Fetch Edition Partners (if Edition ID exists) ---
if ($edition_id) {
    $adsQuery = "SELECT id, ad_banner_image, ad_company_name, catch_phrase FROM ads WHERE edition_id = :edition_id";
    $stmtAds = $pdo->prepare($adsQuery);
    $stmtAds->bindParam(':edition_id', $edition_id, PDO::PARAM_INT);
    $stmtAds->execute();
    $adsList = $stmtAds->fetchAll(PDO::FETCH_ASSOC);
}

// --- 3. Fetch "You May Also Like" Articles ---
$articleQuery = "SELECT a.id, a.top_image, a.title, a.edition_id, c.name FROM articles a INNER JOIN categories c ON a.category_id = c.id ORDER BY a.id DESC LIMIT 8";
$stmtArticle = $pdo->prepare($articleQuery);
$stmtArticle->execute();
$articlesList = $stmtArticle->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Partners - Turning Point Magazine</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>favicon-16x16.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="includes/tp-navbar.css">
    <link rel="stylesheet" href="tp-design-system.css">
    <link rel="stylesheet" href="global.css">

    <style>
    /* --- THEME FONTS & VARS --- */
    :root {
        --brand-red: #ff0000;
        --brand-teal: #008080;
        --brand-pink: #E6007E;
        --text-dark: #333;
        --text-light-gray: #555;
        --bg-off-white: #f8f9fa;
        --border-light: #eee;
    }

    @font-face {
        font-family: florania;
        src: url(assets/fonts/florania.otf) format("opentype");
    }

    body {
        padding-top: 0 !important;
        font-family: 'Poppins', sans-serif;
        background: var(--bg-off-white);
        /* Light BG for the page */
    }

    /* --- ANIMATIONS --- */
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

    .fade-in-up {
        opacity: 0;
        animation: fadeInUp 0.6s ease-out forwards;
    }

    /* --- 1. SIGNATURE HEADER --- */
    .breadcrumb-container {
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

    .tp-fun-intro {
        position: relative;
        z-index: 2;
        background: #ffffff;
        padding: 100px 20px 60px 20px;
        text-align: center;
        border-bottom: 1px solid var(--border-light);
    }

    .tp-intro-main {
        font-family: 'florania', sans-serif;
        font-size: 3.5rem;
        font-weight: 700;
        color: var(--brand-teal);
        margin: 0;
        line-height: 1.2;
    }

    .tp-intro-sub {
        font-family: 'Caveat', cursive;
        font-size: 3.8rem;
        color: var(--brand-pink);
        margin: -10px 0 15px 0;
        line-height: 1;
    }

    .tp-intro-nav {
        font-size: 0.9rem;
        font-weight: 600;
        color: #666;
    }

    .tp-intro-nav a {
        color: var(--brand-red);
        text-decoration: none;
    }

    .tp-intro-nav i {
        font-size: 0.8em;
        margin: 0 8px;
    }

    /* --- 2. MAIN CONTENT & SECTIONS --- */
    main {
        position: relative;
        z-index: 2;
        background: #fff;
        /* White bg for main content area */
    }

    .page-section {
        padding: 60px 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Add a divider */
    .page-section+.page-section {
        border-top: 1px solid var(--border-light);
    }

    /* --- UPDATED: Playful Section Title --- */
    .section-title {
        text-align: center;
        font-family: 'florania', sans-serif;
        font-size: 2.8rem;
        color: var(--brand-teal);
        margin-bottom: 50px;
        /* Increased margin */
        position: relative;
        display: block;
        /* Changed from inline-block */
    }

    .section-title::after {
        content: "";
        position: absolute;
        left: 50%;
        /* Center the squiggle */
        transform: translateX(-50%);
        width: 150px;
        /* Fixed width for squiggle */
        bottom: -15px;
        /* Position below title */
        height: 16px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 20'%3E%3Cpath d='M5 15 Q 25 5 50 15 T 95 5' stroke='%23E6007E' stroke-width='5' fill='none'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-size: 100% 100%;
    }


    /* --- 3. PARTNER DETAILS BLOCK --- */
    .partner-details {
        display: flex;
        gap: 40px;
        align-items: center;
        background: var(--bg-off-white);
        padding: 40px;
        border-radius: 12px;
    }

    .partner-image-wrapper {
        flex: 0 0 40%;
        max-width: 400px;
    }

    .partner-image {
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    /* --- UPDATED: Partner Typography --- */
    .partner-info h1 {
        font-family: 'florania', sans-serif;
        /* Theme font */
        font-size: 3rem;
        color: var(--text-dark);
        margin-top: 0;
    }

    .partner-info p {
        font-family: 'Caveat', cursive;
        /* Theme font */
        font-size: 2rem;
        /* Bigger */
        color: var(--brand-pink);
        /* Theme color */
        line-height: 1.4;
    }

    /* --- 4. CAROUSEL STYLES (UNIFIED) --- */
    .card-slider {
        position: relative;
    }

    .slider-container {
        overflow: hidden;
        max-width: 1200px;
        margin: 0 auto;
    }

    .slider-track {
        display: flex;
        gap: 20px;
        /* Use gap instead of margin */
        transition: transform 0.5s ease-in-out;
    }

    /* --- UPDATED: Card Styling (matches cat.php) --- */
    .slide-card {
        flex: 0 0 calc(25% - 15px);
        /* 4 cards view */
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        text-align: left;
        overflow: hidden;
        text-decoration: none;
        transition: transform 0.3s ease;
        border: 1px solid var(--border-light);
    }

    .slide-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .card-image img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .card-content {
        padding: 15px;
    }

    /* For "Partners" Card */
    .card-content h3 {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        color: #333;
        font-size: 1.1rem;
        margin: 0 0 5px 0;
    }

    .card-content p {
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        color: var(--text-light-gray);
        margin: 0;
    }

    /* For "Articles" Card */
    .card-content p.cat-name {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: var(--brand-red);
        font-weight: 700;
        letter-spacing: 1px;
    }

    /* --- UPDATED: Nav Buttons (matches cat.php) --- */
    .slider-nav {
        text-align: center;
        margin-top: 30px;
    }

    .nav-btn {
        background: var(--brand-red);
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        margin: 0 10px;
        transition: background 0.3s;
    }

    .nav-btn:hover {
        background: #cc0000;
    }

    .nav-btn.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* --- Responsive --- */
    @media (max-width: 992px) {
        .slide-card {
            flex-basis: calc(33.33% - 14px);
            /* 3 cards */
        }

        .partner-details {
            flex-direction: column;
            text-align: center;
        }
    }

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

        .slide-card {
            flex-basis: calc(50% - 10px);
            /* 2 cards */
        }
    }

    @media (max-width: 576px) {
        .slide-card {
            flex-basis: 100%;
            /* 1 card */
        }
    }
    </style>
</head>

<body>
    <?php include 'includes/preloader.php'; ?>
    <?php include 'includes/new-navbar.php';?>

    <section class="tp-fun-intro fade-in-up" style="animation-delay: 0.2s;">
        <h1 class="tp-intro-main">Our Partners</h1>
        <h2 class="tp-intro-sub">Building a Brighter Future Together</h2>
        <nav class="tp-intro-nav">
            <a href="index.php">Home</a> <span><i class="fas fa-chevron-right"></i> Partners</span>
        </nav>
    </section>

    <main>
        <section class="page-section">
            <div class="partner-details fade-in-up" style="animation-delay: 0.4s;">
                <div class="partner-image-wrapper">
                    <img loading="lazy" src="<?= $adImage; ?>" alt="<?= $adCompanyName; ?>" class="partner-image">
                </div>
                <div class="partner-info">
                    <h1><?= $adCompanyName; ?></h1>
                    <p><?= $adCatchPhrase; ?></p>
                </div>
            </div>
        </section>

        <?php if (!empty($adsList)): ?>
        <section class="page-section fade-in-up" style="animation-delay: 0.6s;">
            <h2 class="section-title">Partners In This Edition</h2>

            <div class="card-slider" data-slider="partners-slider">
                <div class="slider-container">
                    <div class="slider-track">
                        <?php foreach ($adsList as $adItem): ?>
                        <a href="partners.php?id=<?= $adItem['id']; ?>&edition_id=<?= $edition_id; ?>"
                            class="slide-card">
                            <div class="card-image">
                                <img loading="lazy" src="admin/<?= htmlspecialchars($adItem['ad_banner_image']); ?>"
                                    alt="<?= htmlspecialchars($adItem['ad_company_name']); ?>">
                            </div>
                            <div class="card-content">
                                <h3><?= htmlspecialchars($adItem['ad_company_name']); ?></h3>
                                <p><?= htmlspecialchars($adItem['catch_phrase']); ?></p>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="slider-nav">
                    <button class="nav-btn" data-action="prev"><i class="fas fa-arrow-left"></i></button>
                    <button class="nav-btn" data-action="next"><i class="fas fa-arrow-right"></i></button>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <?php if (!empty($articlesList)): ?>
        <section class="page-section fade-in-up" style="animation-delay: 0.8s;">
            <h2 class="section-title">You May Also Like</h2>

            <div class="card-slider" data-slider="articles-slider">
                <div class="slider-container">
                    <div class="slider-track">
                        <?php foreach ($articlesList as $articleData): ?>
                        <a href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>article/<?= generate_slug($articleData['title']); ?>"
                            class="slide-card">
                            <div class="card-image">
                                <img loading="lazy" src="admin/<?= htmlspecialchars($articleData['top_image']); ?>"
                                    alt="<?= htmlspecialchars($articleData['title']); ?>">
                            </div>
                            <div class="card-content">
                                <p class="cat-name"><?= htmlspecialchars($articleData['name']); ?></p>
                                <h3><?= htmlspecialchars($articleData['title']); ?></h3>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="slider-nav">
                    <button class="nav-btn" data-action="prev"><i class="fas fa-arrow-left"></i></button>
                    <button class="nav-btn" data-action="next"><i class="fas fa-arrow-right"></i></button>
                </div>
            </div>
        </section>
        <?php endif; ?>

    </main>

    <?php include 'includes/footer.php' ?>

    <script>
    document.querySelectorAll('.card-slider').forEach(initSlider);

    function initSlider(sliderWrapper) {
        const track = sliderWrapper.querySelector('.slider-track');
        const prevBtn = sliderWrapper.querySelector('[data-action="prev"]');
        const nextBtn = sliderWrapper.querySelector('[data-action="next"]');
        const cards = track.querySelectorAll('.slide-card');

        if (!track || !prevBtn || !nextBtn || cards.length === 0) {
            // If slider is empty or missing parts, hide nav
            if (sliderWrapper.querySelector('.slider-nav')) {
                sliderWrapper.querySelector('.slider-nav').style.display = 'none';
            }
            return;
        }

        let currentIndex = 0;
        let cardsPerPage = 4;

        function updateSlider() {
            // 1. Update Cards Per Page based on width
            if (window.innerWidth <= 576) cardsPerPage = 1;
            else if (window.innerWidth <= 768) cardsPerPage = 2;
            else if (window.innerWidth <= 992) cardsPerPage = 3;
            else cardsPerPage = 4;

            const totalCards = cards.length;
            const maxIndex = Math.max(0, totalCards - cardsPerPage);

            // 2. Clamp index
            currentIndex = Math.min(Math.max(0, currentIndex), maxIndex);

            // 3. Calculate offset
            // We use the 'gap' (20px) and card width (25%)
            // A simpler way is to just slide by pages.
            // Let's use the simple translateX by % for "pages"

            const cardWidthPercent = 100 / cardsPerPage;
            const offset = -(currentIndex * cardWidthPercent);

            // This is a simpler page-slide logic
            // For per-card slide logic, it's more complex
            // Let's stick to simple "per-card" logic from cat.php

            const totalSlides = cards.length;
            const maxSlides = totalSlides - cardsPerPage;

            currentIndex = Math.min(Math.max(0, currentIndex), maxSlides);

            // Calculate the width of one card + gap
            const cardWidth = cards[0].offsetWidth;
            const cardGap = 20; // As defined in our CSS 'gap'

            // Move the track by (card width + gap) * index
            track.style.transform = `translateX(-${currentIndex * (cardWidth + cardGap)}px)`;

            // 4. Update Button States
            prevBtn.classList.toggle('disabled', currentIndex === 0);
            nextBtn.classList.toggle('disabled', currentIndex >= maxSlides);
        }

        nextBtn.addEventListener('click', () => {
            currentIndex++;
            updateSlider();
        });

        prevBtn.addEventListener('click', () => {
            currentIndex--;
            updateSlider();
        });

        window.addEventListener('resize', updateSlider);
        updateSlider(); // Initial call
    }
    </script>
</body>

</html>

