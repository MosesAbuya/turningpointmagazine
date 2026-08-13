<?php 
include('connection2.php'); 

// --- 1. INITIALIZE DATA --- //
$story_id = isset($_GET['id']) ? $_GET['id'] : null;
$edition_id = isset($_GET['edition_id']) ? $_GET['edition_id'] : null;
$slug = isset($_GET['slug']) ? $_GET['slug'] : null;

$pdo = connect();

if ($slug) {
    $stmt = $pdo->query("SELECT id, title, edition_id FROM articles");
    $all_articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($all_articles as $a) {
        if (generate_slug($a['title']) === $slug) {
            $story_id = $a['id'];
            $edition_id = $a['edition_id'];
            break;
        }
    }
}

if (!$story_id) {
    header("Location: /");
    exit();
}

// A. Fetch Article
$articleQuery = "
    SELECT a.id, a.title, a.catch_phrase, a.writer, a.category_id, a.edition_id, a.top_image, a.story_content, a.other_images
    FROM articles a
    WHERE a.id = :story_id
";
$stmt = $pdo->prepare($articleQuery);
$stmt->bindParam(':story_id', $story_id, PDO::PARAM_INT);
$stmt->execute();
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    echo "Article not found.";
    exit();
}

// B. Fetch Category
$categoryQuery = "SELECT name FROM categories WHERE id = :category_id";
$stmtCat = $pdo->prepare($categoryQuery);
$stmtCat->bindParam(':category_id', $article['category_id'], PDO::PARAM_INT);
$stmtCat->execute();
$category = $stmtCat->fetch(PDO::FETCH_ASSOC);

// C. Fetch Edition
$editionQuery = "SELECT edition_name FROM editions WHERE id = :edition_id";
$stmtEd = $pdo->prepare($editionQuery);
$stmtEd->bindParam(':edition_id', $article['edition_id'], PDO::PARAM_INT);
$stmtEd->execute();
$edition = $stmtEd->fetch(PDO::FETCH_ASSOC);

// Set Variables
$title = $article['title'];
$catch_phrase = $article['catch_phrase'];
$writer = $article['writer'];
$story_content = $article['story_content'];
$top_image = "admin/" . $article['top_image'];
$category_name = $category['name'];
$edition_name = $edition ? $edition['edition_name'] : '';
$current_edition_id = $article['edition_id']; 

// Generate Meta Description Excerpt
$excerpt = strip_tags(html_entity_decode($story_content ?? ''));
$excerpt = trim(preg_replace('/\s+/', ' ', $excerpt));
$meta_description = mb_strlen($excerpt) > 155 ? mb_substr($excerpt, 0, 155) . '...' : $excerpt;

// D. Fetch Ads (Partners)
$adsList = [];
if ($current_edition_id) {
    $adsQuery = "SELECT id, ad_banner_image, ad_company_name, catch_phrase FROM ads WHERE edition_id = :edition_id";
    $stmtAds = $pdo->prepare($adsQuery);
    $stmtAds->bindParam(':edition_id', $current_edition_id, PDO::PARAM_INT);
    $stmtAds->execute();
    $adsList = $stmtAds->fetchAll(PDO::FETCH_ASSOC);
}

// E. Fetch Other Articles for Carousel
$articleListQuery = "SELECT a.id, a.top_image, a.title, c.name FROM articles a INNER JOIN categories c ON a.category_id = c.id WHERE a.id != :current_id LIMIT 8";
$stmtList = $pdo->prepare($articleListQuery);
$stmtList->bindParam(':current_id', $story_id, PDO::PARAM_INT);
$stmtList->execute();
$articlesList = $stmtList->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= htmlspecialchars($title) ?> - Turning Point Magazine</title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= htmlspecialchars($meta_description) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://www.turningpointmagazine.africa/article/<?php echo generate_slug($article['title']); ?>">

    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>favicon-16x16.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="includes/new-navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="global.css">

    <!-- Open Graph & Twitter Cards -->
    <meta property="og:title" content="<?= htmlspecialchars($title) ?> - Turning Point Magazine" />
    <meta property="og:description" content="<?= htmlspecialchars($meta_description) ?>" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="https://www.turningpointmagazine.africa/article/<?php echo generate_slug($article['title']); ?>" />
    <meta property="og:image" content="https://www.turningpointmagazine.africa/<?= htmlspecialchars($top_image) ?>" />
    <meta name="twitter:card" content="summary_large_image" />

    <!-- JSON-LD Article Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Article",
      "headline": <?= json_encode($title) ?>,
      "image": "https://www.turningpointmagazine.africa/<?= htmlspecialchars($top_image) ?>",
      "author": {
        "@type": "Person",
        "name": <?= json_encode($writer) ?>
      },
      "publisher": {
        "@type": "Organization",
        "name": "Turning Point Magazine",
        "logo": {
          "@type": "ImageObject",
          "url": "https://www.turningpointmagazine.africa/assets/logo.png"
        }
      },
      "description": <?= json_encode($meta_description) ?>
    }
    </script>
</head>

    <style>
    /* --- THEME FONTS --- */
    @font-face {
        font-family: florania;
        src: url(assets/fonts/florania.otf) format("opentype");
    }

    :root {
        --brand-teal: #008080;
        --brand-pink: #E6007E;
        --brand-red: #ff0000;
        --text-dark: #333;
        --text-grey: #555;
        --bg-light: #f8f9fa;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg-light);
        padding-top: 0 !important;
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

    /* --- HEADER / HERO --- */
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
        padding: 100px 20px 40px 20px;
        text-align: center;
        border-bottom: 1px solid #eee;
    }

    .tp-article-title {
        font-family: 'florania', sans-serif;
        font-size: 3.5rem;
        font-weight: 700;
        color: var(--brand-teal);
        margin: 0;
        line-height: 1.1;
        max-width: 900px;
        margin: 0 auto;
    }

    .tp-article-meta {
        font-family: 'Caveat', cursive;
        font-size: 1.8rem;
        color: var(--brand-pink);
        margin: 10px 0 0 0;
    }

    .tp-intro-nav {
        font-size: 0.9rem;
        font-weight: 600;
        color: #666;
        margin-top: 15px;
    }

    .tp-intro-nav a {
        color: var(--brand-red);
        text-decoration: none;
    }

    .tp-intro-nav i {
        font-size: 0.8em;
        margin: 0 8px;
    }

    /* --- MAIN LAYOUT (Grid) --- */
    main {
        position: relative;
        z-index: 2;
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
        display: grid;
        grid-template-columns: 2.5fr 1fr;
        /* Main Content vs Sidebar */
        gap: 40px;
    }

    /* --- ARTICLE CONTENT --- */
    .article-container {
        background: white;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    /* --- UPDATED: Featured Image Logic --- */
    .featured-image-wrapper {
        width: 100%;
        display: flex;
        /* Center the image */
        justify-content: center;
        /* Center horizontally */
        align-items: center;
        /* Center vertically */
        background-color: #fafafa;
        /* Light background to frame weird aspect ratios */
        border-radius: 8px;
        margin-bottom: 30px;
        padding: 10px;
        /* Small padding acts like a frame */
        border: 1px solid #eee;
    }

    .featured-image-wrapper img {
        max-width: 100%;
        /* Never overflow width */
        max-height: 600px;
        /* Never overflow vertical screen space */
        width: auto;
        /* Allow natural width */
        height: auto;
        /* Allow natural height */
        object-fit: contain;
        /* Ensure full image is shown, no cropping */
        border-radius: 4px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }

    /* Writer Info */
    .writer-block {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }

    .writer-name {
        color: var(--text-grey);
        font-size: 0.95rem;
        font-weight: 600;
        display: block;
        margin-bottom: 10px;
    }

    .writer-name i {
        color: var(--brand-red);
        margin-right: 8px;
    }

    /* --- UPDATED: Playful Quote / Punchline --- */
    .article-catchphrase {
        display: block;
        font-family: 'Caveat', cursive;
        font-size: 2rem;
        /* Much bigger */
        color: var(--brand-pink);
        /* Distinct color */
        line-height: 1.3;
        margin-top: 5px;
    }

    .article-catchphrase i {
        font-size: 0.8em;
        opacity: 0.6;
        margin-right: 5px;
    }

    /* The actual text content */
    .story-content {
        color: #333;
        font-size: 1.05rem;
        line-height: 1.8;
    }

    .story-content p {
        margin-bottom: 20px;
    }

    .story-content img {
        max-width: 100%;
        height: auto;
        border-radius: 5px;
        margin: 20px 0;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    .story-content h2,
    .story-content h3 {
        font-family: 'florania', sans-serif;
        color: var(--brand-teal);
        margin-top: 30px;
    }

    .story-content blockquote {
        border-left: 4px solid var(--brand-pink);
        margin: 30px 0;
        padding: 10px 20px;
        background: #fff0f7;
        font-style: italic;
        color: #555;
    }

    /* --- SIDEBAR --- */
    .sidebar {
        position: sticky;
        top: 120px;
        height: fit-content;
    }

    .sidebar-title {
        font-family: 'florania', sans-serif;
        font-size: 2rem;
        color: var(--brand-teal);
        margin-bottom: 20px;
        position: relative;
        display: inline-block;
    }

    .sidebar-title::after {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        bottom: -5px;
        height: 12px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 20'%3E%3Cpath d='M5 15 Q 25 5 50 15 T 95 5' stroke='%23FFD700' stroke-width='5' fill='none'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-size: 100% 100%;
    }

    .partner-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 25px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease;
        border: 1px solid #eee;
        text-decoration: none;
        display: block;
    }

    .partner-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .partner-img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }

    .partner-info {
        padding: 15px;
        text-align: center;
    }

    .partner-info h4 {
        margin: 0;
        color: #333;
        font-weight: 700;
    }

    .partner-info p {
        margin: 5px 0 0 0;
        font-family: 'Caveat', cursive;
        font-size: 1.2rem;
        color: var(--brand-pink);
    }

    /* --- CAROUSEL SECTION --- */
    .more-stories-section {
        background: #fff;
        padding: 60px 20px;
        margin-top: 60px;
        text-align: center;
    }

    .section-title {
        font-family: 'florania', sans-serif;
        font-size: 2.5rem;
        color: var(--brand-teal);
        margin-bottom: 30px;
        position: relative;
        display: inline-block;
    }

    .section-title::after {
        content: "";
        position: absolute;
        left: -10%;
        right: -10%;
        bottom: -8px;
        height: 16px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 20'%3E%3Cpath d='M5 15 Q 25 5 50 15 T 95 5' stroke='%23E6007E' stroke-width='5' fill='none'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-size: 100% 100%;
    }

    .edition-container {
        position: relative;
        overflow: hidden;
        max-width: 1200px;
        margin: 40px auto 0 auto;
    }

    .edition-cards {
        display: flex;
        gap: 20px;
        transition: transform 0.5s ease-in-out;
    }

    .edition-card {
        flex: 0 0 calc(25% - 15px);
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        text-align: left;
        overflow: hidden;
        text-decoration: none;
        transition: transform 0.3s ease;
        border: 1px solid #eee;
    }

    .edition-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .edition-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .edition-card-content {
        padding: 15px;
    }

    .edition-cat {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: var(--brand-red);
        font-weight: 700;
        letter-spacing: 1px;
    }

    .edition-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        color: #333;
        font-size: 1rem;
        margin: 5px 0 0 0;
        line-height: 1.4;
    }

    .nav-buttons {
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

    /* --- RESPONSIVE --- */
    @media (max-width: 900px) {
        main {
            grid-template-columns: 1fr;
        }

        .sidebar {
            position: static;
            margin-top: 40px;
        }

        .partner-card {
            display: flex;
            align-items: center;
            height: auto;
        }

        .partner-img {
            width: 120px;
            height: 120px;
        }

        .partner-info {
            text-align: left;
            padding-left: 20px;
        }

        .tp-article-title {
            font-size: 2.5rem;
        }
    }

    @media (max-width: 768px) {
        .breadcrumb-container {
            height: 75px;
        }

        .tp-fun-intro {
            padding-top: 80px;
        }

        .article-container {
            padding: 20px;
        }

        .edition-card {
            flex: 0 0 calc(50% - 10px);
        }

        .tp-article-title {
            font-size: 2.2rem;
        }

        .tp-article-meta {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 480px) {
        .edition-card {
            flex: 0 0 100%;
        }
    }
    </style>
</head>

<body>
    <?php include 'includes/preloader.php'; ?>
    <?php include 'includes/new-navbar.php'; ?>

    <div class="breadcrumb-container fade-in-up"></div>

    <section class="tp-fun-intro fade-in-up" style="animation-delay: 0.2s;">
        <h1 class="tp-article-title"><?= htmlspecialchars($title); ?></h1>
        <div class="tp-article-meta">
            <?= htmlspecialchars($category_name); ?> • <?= htmlspecialchars($edition_name); ?>
        </div>
        <nav class="tp-intro-nav">
            <a href="index.php">Home</a> <span><i class="fas fa-chevron-right"></i> Articles</span> <span><i
                    class="fas fa-chevron-right"></i> <?= htmlspecialchars($category_name); ?></span>
        </nav>
    </section>

    <main>
        <article class="article-container fade-in-up" style="animation-delay: 0.4s;">

            <?php if(!empty($article['top_image'])): ?>
            <div class="featured-image-wrapper">
                <img loading="lazy" src="<?= htmlspecialchars($top_image); ?>" alt="<?= htmlspecialchars($title); ?>">
            </div>
            <?php endif; ?>

            <div class="writer-block">
                <span class="writer-name"><i class="fas fa-user-edit"></i> Story By
                    <strong><?= htmlspecialchars($writer); ?></strong></span>

                <?php if(!empty($catch_phrase)): ?>
                <span class="article-catchphrase">
                    <i class="fas fa-quote-left"></i> <?= htmlspecialchars($catch_phrase); ?>
                </span>
                <?php endif; ?>
            </div>

            <div class="story-content">
                <?php
                    $basePath = "admin/";
                    $updatedContent = preg_replace_callback('/<img loading="lazy"[^>]+src="([^"]+)"/i', function($matches) use ($basePath) {
                        $relativeSrc = $matches[1];
                        if (strpos($relativeSrc, 'admin/') === false) {
                            $relativeSrc = $basePath . $relativeSrc; 
                        }
                        return str_replace($matches[1], $relativeSrc, $matches[0]);
                    }, $story_content);

                    echo $updatedContent;
                ?>
            </div>
        </article>

        <aside class="sidebar fade-in-up" style="animation-delay: 0.6s;">
            <div style="text-align: center;">
                <h3 class="sidebar-title">Our Partners</h3>
            </div>
            <br>

            <?php if (!empty($adsList)): ?>
            <?php foreach ($adsList as $ad): ?>
            <a href="partners.php?id=<?= $ad['id']; ?>&edition_id=<?= $current_edition_id; ?>" class="partner-card">
                <img loading="lazy" src="admin/<?= htmlspecialchars($ad['ad_banner_image']); ?>" class="partner-img" alt="Partner">
                <div class="partner-info">
                    <h4><?= htmlspecialchars($ad['ad_company_name']); ?></h4>
                    <p><?= htmlspecialchars($ad['catch_phrase']); ?></p>
                </div>
            </a>
            <?php endforeach; ?>
            <?php else: ?>
            <div class="partner-card" style="padding: 20px; text-align:center;">
                <p style="color:#999;">Advertise Here</p>
            </div>
            <?php endif; ?>
        </aside>

    </main>

    <section class="more-stories-section">
        <h2 class="section-title">More Good Reads</h2>

        <div class="edition-container">
            <div class="edition-cards">
                <?php if (!empty($articlesList)): ?>
                <?php foreach ($articlesList as $art): ?>
                <a href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>article/<?= generate_slug($art['title']); ?>" class="edition-card">
                    <img loading="lazy" src="admin/<?= htmlspecialchars($art['top_image']); ?>" alt="Article">
                    <div class="edition-card-content">
                        <div class="edition-cat"><?= htmlspecialchars($art['name']); ?></div>
                        <div class="edition-title"><?= htmlspecialchars($art['title']); ?></div>
                    </div>
                </a>
                <?php endforeach; ?>
                <?php else: ?>
                <p>No other articles found.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="nav-buttons">
            <button id="prev-btn" class="nav-btn"><i class="fas fa-arrow-left"></i></button>
            <button id="next-btn" class="nav-btn"><i class="fas fa-arrow-right"></i></button>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script>
    const editionCards = document.querySelector('.edition-cards');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');

    let currentIndex = 0;
    let cardsPerPage = 4;
    let totalCards = document.querySelectorAll('.edition-card').length;

    function updateCardsPerPage() {
        if (window.innerWidth <= 480) {
            cardsPerPage = 1;
        } else if (window.innerWidth <= 768) {
            cardsPerPage = 2;
        } else {
            cardsPerPage = 4;
        }
        updateCardPosition();
    }

    function updateCardPosition() {
        const offset = -(currentIndex * (100 / cardsPerPage));
        editionCards.style.transform = `translateX(${offset}%)`;

        const maxIndex = totalCards - cardsPerPage;
        prevBtn.style.opacity = currentIndex <= 0 ? '0.5' : '1';
        nextBtn.style.opacity = currentIndex >= maxIndex ? '0.5' : '1';
    }

    nextBtn.addEventListener('click', () => {
        const maxIndex = totalCards - cardsPerPage;
        if (currentIndex < maxIndex) {
            currentIndex++;
            updateCardPosition();
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateCardPosition();
        }
    });

    window.addEventListener('load', () => {
        updateCardsPerPage();
        updateCardPosition();
    });
    window.addEventListener('resize', updateCardsPerPage);
    </script>

</body>

</html>
