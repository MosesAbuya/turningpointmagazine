<?php
// Include the connection file
include('connection2.php');

// Check if 'edition_id' or 'slug' is present in the URL
if (isset($_GET['edition_id']) || isset($_GET['slug'])) {
    $edition_id = isset($_GET['edition_id']) ? $_GET['edition_id'] : null;
    $slug = isset($_GET['slug']) ? $_GET['slug'] : null;

    // Establish database connection
    $pdo = connect();

    if ($slug) {
        $stmt = $pdo->query("SELECT id, edition_name FROM editions");
        $all_editions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($all_editions as $e) {
            if (generate_slug($e['edition_name']) === $slug) {
                $edition_id = $e['id'];
                break;
            }
        }
    }

    // Prepare SQL to get article data where is_top_story = 1
    $articleQuery = "
        SELECT a.id, a.title, a.catch_phrase, a.writer, a.top_image, a.edition_id
        FROM articles a
        WHERE a.is_top_story = 1 AND a.edition_id = :edition_id
    ";
    $stmt = $pdo->prepare($articleQuery);
    $stmt->bindParam(':edition_id', $edition_id, PDO::PARAM_INT);
    $stmt->execute();
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    // Prepare SQL to get edition images based on the edition_id
    $editionQuery = "
        SELECT e.front_page_image, e.back_page_image, e.edition_name, e.date
        FROM editions e
        WHERE e.id = :edition_id
    ";
    $stmtEdition = $pdo->prepare($editionQuery);
    $stmtEdition->bindParam(':edition_id', $edition_id, PDO::PARAM_INT);
    $stmtEdition->execute();
    $edition = $stmtEdition->fetch(PDO::FETCH_ASSOC);

    // Retrieve the necessary values
    $edition_name = $edition['edition_name'];
    $date = $edition['date']; // Defined for use later

    if ($article) {
        $title = $article['title'];
        $catch_phrase = $article['catch_phrase'];
        $writer = $article['writer'];
        $top_image = "admin/" . $article['top_image']; 
        $article_id = $article['id'];
    } else {
        $title = "No Top Story";
        $catch_phrase = "";
        $writer = "";
        $top_image = "";
        $article_id = 0;
    }

    // Edition images
    $left_image = "admin/" . $edition['front_page_image']; 
    $overlay_image = "admin/" . $edition['front_page_image']; 
    $right_image = "admin/" . $edition['back_page_image']; 
    
} else {
    echo "Edition ID not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Issue Details - Turning Point Magazine</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://www.turningpointmagazine.africa/edition/<?php echo generate_slug($edition_name ?? ''); ?>">

    <link rel="apple-touch-icon" sizes="180x180" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>favicon-16x16.png">
    <link rel="manifest" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>site.webmanifest">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="includes/new-navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="global.css">

    <style>
    @font-face {
        font-family: florania;
        src: url(assets/fonts/florania.otf) format("opentype");
    }

    :root {
        --brand-red: #ff0000;
        --brand-teal: #008080;
        --brand-pink: #E6007E;
        --text-dark: #333;
        --text-light: #666;
        --bg-off-white: #f8f9fa;
    }

    body {
        padding-top: 0 !important;
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg-off-white);
    }

    /* --- 1. NAVBAR BACKGROUND --- */
    .breadcrumb-container {
        background-image: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url('assets/breadcrumbs/bread.jpeg');
        background-size: cover;
        background-position: center;
        position: absolute;
        z-index: 1;
        top: 0;
        left: 0;
        width: 100%;
        height: 140px;
        /* Height for navbar area */
    }

    .issue-container {
        position: relative;
        z-index: 2;
        padding-top: 140px;
        max-width: 1300px;
        margin: 0 auto;
        padding-bottom: 60px;
    }

    /* ========================================= */
    /* --- 2. PRESERVED TOP STORY STYLING ---    */
    /* ========================================= */
    /* Copied from your issue_1.css to maintain exact behavior */

    .top-story {
        height: 90vh;
        /* Adjusted slightly to fit viewport better */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        /* Centered vertically */
        width: 100%;
        margin-bottom: 50px;
    }

    .top-story-row {
        height: 100%;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        /* Removed border-bottom/box-shadow from row to let it breathe in new layout */
    }

    .top-story-text {
        height: fit-content;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 20px;
        width: 50%;
    }

    .top-story-text img {
        display: none;
    }

    /* Hide static image on desktop */

    .top-story-text h1 {
        font-size: 5rem;
        font-weight: 900;
        width: 100%;
        color: red;
        font-family: "florania";
        text-align: right;
        padding-left: 10px;
        line-height: 1;
        margin: 10px 0;
    }

    .top-story-text p {
        font-size: 1rem;
        font-weight: lighter;
        text-align: left;
        padding-left: 10px;
        color: #413d3d;
    }

    .top-story-text h3 {
        padding-left: 10px;
        text-align: right;
    }

    .top-story-text h2 {
        font-size: medium;
        padding: 0;
        text-align: right;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .aut {
        color: rgb(172, 172, 0);
        font-family: 'Caveat', cursive;
        font-size: 1.5rem;
    }

    .top-story-image {
        position: relative;
        width: 50%;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        height: 100%;
        right: 0;
        padding: 70px;
        transition: all 0.5s ease;
    }

    .top-story-image img {
        right: 0;
        width: auto;
        max-height: 90%;
        transition: all 0.5s ease;
    }

    #left {
        position: absolute;
        top: 5%;
        left: 19.5%;
        transform: translate(-50%, -50%);
        z-index: 5;
        transform: rotate(3deg);
        box-shadow: 0 6px 18px -4px rgba(0, 0, 0, 0.739);
    }

    #overllay {
        position: absolute;
        z-index: 6;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        transition: all 0.5s ease-in-out;
    }

    #top {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 16;
        box-shadow: 0 6px 18px -4px rgba(0, 0, 0, 0.739);
        transition: all 0.5s ease-in-out;
        opacity: 1;
    }

    #right {
        position: absolute;
        top: 5%;
        left: 25%;
        transform: translate(-50%, -50%);
        z-index: 5;
        transform: rotate(-3deg);
        box-shadow: 0 6px 18px -4px rgba(0, 0, 0, 0.739);
    }

    /* Hover Animation */
    .top-story-image:hover #left {
        transform: translate(-50%, -50%) rotate(0deg);
        top: 50%;
        left: 50%;
    }

    .top-story-image:hover #right {
        transform: translate(-50%, -50%) rotate(0deg);
        top: 50%;
        left: 50%;
    }

    .top-story-image:hover #top {
        opacity: 0;
        transform: translate(-50%, -50%) scale(1.15);
    }

    .top-story-image:hover #overllay {
        z-index: 16;
        transform: translate(-50%, -50%) scale(1.15);
        box-shadow: 0 6px 18px -4px rgba(0, 0, 0, 0.839);
    }

    .read-btn-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        margin-top: -50px;
        /* Pull up slightly */
    }

    .read-btn-wrapper {
        width: 35%;
        height: 70px;
        background-color: #ffffff;
        margin-top: -30px;
        box-shadow: 0 6px 18px -4px rgba(0, 0, 0, 0.139);
        transition: all 0.5s ease;
        border-bottom-right-radius: 5px;
        border-bottom-left-radius: 5px;
    }

    .read-btn-section a {
        transition: all 0.5s ease;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        background-color: #000000;
        width: 25%;
        margin-top: -40px;
        color: #ffffff;
        height: 60px;
        text-decoration: none;
        z-index: 5;
        border-radius: 5px;
        font-weight: bold;
    }

    .read-btn-section a:hover {
        background-color: #ff0000;
        transform: scaleX(1.1);
    }

    .read-btn-section:hover .read-btn-wrapper {
        transform: scaleX(1.2);
    }

    .read-btn-section:hover a {
        transform: translateY(10px);
    }

    /* ========================================= */
    /* --- 3. NEW THEME STYLING (REST OF PAGE) - */
    /* ========================================= */

    .section-divider {
        text-align: center;
        margin: 80px 0 40px;
        position: relative;
    }

    .section-title {
        font-family: 'florania', sans-serif;
        font-size: 2.5rem;
        color: var(--brand-teal);
        display: inline-block;
        position: relative;
        margin-bottom: 10px;
    }

    /* Pink Tilted Underline */
    .section-title::after {
        content: "";
        position: absolute;
        left: -5%;
        bottom: -5px;
        width: 110%;
        height: 5px;
        background: var(--brand-pink);
        transform: rotate(-1deg);
    }

    /* Articles Grid */
    .articles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 30px;
        padding: 0 20px;
    }

    .article-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
    }

    .article-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .article-img {
        height: 220px;
        width: 100%;
        object-fit: cover;
        object-position: top;
        /* <-- Add this line */
        border-bottom: 3px solid var(--brand-red);
    }

    .article-content {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .article-title {
        font-family: 'florania', sans-serif;
        font-size: 1.6rem;
        color: var(--brand-red);
        margin-bottom: 10px;
        line-height: 1.2;
    }

    .article-meta {
        font-size: 0.85rem;
        color: var(--text-light);
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        font-weight: 600;
    }

    .article-excerpt {
        font-size: 0.95rem;
        color: var(--text-dark);
        line-height: 1.5;
        flex: 1;
    }

    .article-footer {
        margin-top: 15px;
        font-weight: 700;
        color: var(--brand-teal);
        font-size: 0.9rem;
    }

    /* Partners/Ads */
    .partners-section {
        margin-top: 80px;
        background: #fff;
        padding: 40px 20px;
        border-top: 1px solid #eee;
    }

    .partners-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }

    .partner-ad-card {
        border: 1px solid #eee;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: #f9f9f9;
    }

    .partner-ad-card:hover {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .partner-ad-card img {
        width: 100%;
        height: 150px;
        object-fit: contain;
        padding: 20px;
        background: #fff;
    }

    .partner-ad-info {
        padding: 15px;
        text-align: center;
    }

    .partner-ad-info h4 {
        font-size: 1.1rem;
        margin-bottom: 5px;
        font-weight: 700;
    }

    /* Other Editions */
    .editions-section {
        margin-top: 60px;
        padding: 0 20px;
        margin-bottom: 60px;
    }

    .edition-cards-wrapper {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        padding-bottom: 20px;
        scrollbar-width: none;
    }

    .edition-cards-wrapper::-webkit-scrollbar {
        display: none;
    }

    .edition-item {
        flex: 0 0 220px;
        text-decoration: none;
        color: inherit;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
    }

    .edition-item:hover {
        transform: scale(1.05);
    }

    .edition-item img {
        width: 100%;
        height: 300px;
        object-fit: cover;
    }

    .edition-item-info {
        padding: 10px;
        text-align: center;
        font-weight: 700;
    }

    /* --- RESPONSIVE --- */
    @media (max-width: 992px) {
        .top-story-row {
            flex-direction: column-reverse;
        }

        .top-story-text {
            text-align: center;
            padding-right: 0;
            width: 100%;
        }

        .top-story-image {
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .issue-container {
            padding-top: 100px;
        }

        .top-story {
            margin-top: 300px;
        }

        /* Mobile: Hide complex animation, show static image */
        .top-story-image {
            display: none;
        }

        .top-story-text img#tops {
            display: block;
            width: 100%;
            max-width: 400px;
            margin: 0 auto 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .top-story-text h1 {
            text-align: center;
            font-size: 3rem;
        }

        .top-story-text h2,
        .top-story-text h3 {
            text-align: center;
        }

        .read-btn-section .read-btn-wrapper {
            width: 80%;
        }

        .read-btn-section a {
            width: 50%;
        }

        .articles-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>
</head>

<body>
    <?php include 'includes/preloader.php'; ?>
    <?php include 'includes/new-navbar.php'; ?>

    <div class="breadcrumb-container fade-in-up"></div>

    <div class="issue-container fade-in-up">

        <div class="top-story" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);">
            <div class="top-story-row" style="margin-bottom: 150px;">
                <div class="top-story-text">
                    <img loading="lazy" id="tops" src="<?php echo htmlspecialchars($top_image); ?>" alt="Top Story">
                    <h2 class="h2l" id="a-edition"><?php echo htmlspecialchars($edition_name); ?></h2>
                    <h1 id="a-title"><?php echo htmlspecialchars($title); ?></h1>
                    <p class="" id="a-catch"><?php echo htmlspecialchars($catch_phrase); ?></p>
                    <h3 class="aut" id="a-author"><?php echo htmlspecialchars($writer); ?></h3>
                </div>
                <div class="top-story-image">
                    <img loading="lazy" id="left" src="<?php echo htmlspecialchars($left_image); ?>" alt="Left Edition Image">
                    <img loading="lazy" id="top" src="<?php echo htmlspecialchars($top_image); ?>" alt="Top Story Image">
                    <img loading="lazy" id="overllay" src="<?php echo htmlspecialchars($overlay_image); ?>" alt="Overlay Image">
                    <img loading="lazy" id="right" src="<?php echo htmlspecialchars($right_image); ?>" alt="Right Edition Image">
                </div>
            </div>
            <div class="read-btn-section">
                <div class="read-btn-wrapper"></div>
                <a
                    href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>article/<?php echo generate_slug($top_story['title']); ?>">Read
                    More</a>
            </div>
        </div>

        <div class="section-divider">
            <h2 class="section-title">In This Edition</h2>
        </div>

        <?php
        // Fetch articles
        if (isset($_GET['edition_id'])) {
            $edition_id = $_GET['edition_id'];
            $stmtArticles = $pdo->prepare("SELECT a.id, a.title, a.catch_phrase, a.writer, a.top_image FROM articles a WHERE a.edition_id = :edition_id AND a.is_top_story != 1");
            $stmtArticles->execute(['edition_id' => $edition_id]);
            $articlesList = $stmtArticles->fetchAll(PDO::FETCH_ASSOC);
        }
        ?>

        <div class="articles-grid">
            <?php if (!empty($articlesList)): ?>
            <?php foreach ($articlesList as $art): ?>
            <a href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>article/<?= generate_slug($art['title']); ?>" class="article-card">
                <img loading="lazy" src="admin/<?= htmlspecialchars($art['top_image']) ?>" class="article-img"
                    alt="<?= htmlspecialchars($art['title']) ?>">
                <div class="article-content">
                    <div class="article-meta">
                        <span><?= $date ?></span>
                        <span><?= $edition_name ?></span>
                    </div>
                    <h3 class="article-title"><?= htmlspecialchars($art['title']) ?></h3>
                    <p class="article-excerpt"><?= htmlspecialchars($art['catch_phrase']) ?></p>
                    <div class="article-footer">
                        Read Article <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
            <?php else: ?>
            <p style="grid-column: 1/-1; text-align: center; color: #666;">No other articles found for this edition.</p>
            <?php endif; ?>
        </div>

        <?php
        $stmtAds = $pdo->prepare("SELECT id, ad_banner_image, ad_company_name, catch_phrase FROM ads WHERE edition_id = :edition_id");
        $stmtAds->execute(['edition_id' => $edition_id]);
        $adsList = $stmtAds->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <?php if(!empty($adsList)): ?>
        <div class="partners-section">
            <div class="section-divider" style="margin-top:0;">
                <h2 class="section-title">Our Partners For This Edition</h2>
            </div>
            <div class="partners-grid">
                <?php foreach ($adsList as $ad): ?>
                <div class="partner-ad-card">
                    <a href="partners.php?id=<?= $ad['id'] ?>&edition_id=<?= $edition_id ?>">
                        <img loading="lazy" src="admin/<?= htmlspecialchars($ad['ad_banner_image']) ?>" alt="Ad">
                        <div class="partner-ad-info">
                            <h4><?= htmlspecialchars($ad['ad_company_name']) ?></h4>
                            <p><?= htmlspecialchars($ad['catch_phrase']) ?></p>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php
        $stmtEd = $pdo->query("SELECT e.front_page_image, e.edition_name, e.date, e.id FROM editions e");
        $editionsList = $stmtEd->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <div class="editions-section">
            <div class="section-divider">
                <h2 class="section-title">Other Editions</h2>
            </div>
            <div class="edition-cards-wrapper">
                <?php foreach ($editionsList as $ed): ?>
                <a href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>edition/<?= generate_slug($ed['edition_name']) ?>" class="edition-item">
                    <img loading="lazy" src="admin/<?= htmlspecialchars($ed['front_page_image']) ?>" alt="Cover">
                    <div class="edition-item-info">
                        <?= htmlspecialchars($ed['edition_name']) ?>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <?php include 'includes/footer.php'; ?>

</body>

</html>
