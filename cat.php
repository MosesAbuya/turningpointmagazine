<?php 
include('connection2.php'); 
$pdo = connect();

// --- 1. GET CATEGORY INFO --- //
$category_id = isset($_GET['id']) ? $_GET['id'] : null;
$slug = isset($_GET['slug']) ? $_GET['slug'] : null;

if ($slug) {
    $stmt = $pdo->query("SELECT id, name FROM categories");
    $all_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($all_categories as $c) {
        if (generate_slug($c['name']) === $slug) {
            $category_id = $c['id'];
            break;
        }
    }
}

if (!$category_id) {
    echo "No category specified.";
    exit();
}

// Fetch category name
$categoryQuery = "SELECT name FROM categories WHERE id = :id";
$stmtCategory = $pdo->prepare($categoryQuery);
$stmtCategory->bindParam(':id', $category_id, PDO::PARAM_INT);
$stmtCategory->execute();
$category = $stmtCategory->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    die("Category not found.");
}
$category_name = $category['name'];

// --- 2. GET SUB-CATEGORIES --- //
// Fetch ALL subcategories for the new nav bar
$subCategoryQuery = "SELECT id, name FROM sub_category WHERE category_id = :category_id ORDER BY id";
$stmtSubCategory = $pdo->prepare($subCategoryQuery);
$stmtSubCategory->bindParam(':category_id', $category_id, PDO::PARAM_INT);
$stmtSubCategory->execute();
$subcategories = $stmtSubCategory->fetchAll(PDO::FETCH_ASSOC);

// Determine the active sub-category
$sub_category_id = isset($_GET['sub_category_id']) ? $_GET['sub_category_id'] : null;
if (!$sub_category_id && !empty($subcategories)) {
    // If none is selected, default to the first one
    $sub_category_id = $subcategories[0]['id'];
}

// --- 3. GET CAROUSEL ARTICLES ("You May Also Like") --- //
// Get 8 random articles, excluding the current category
$articleQuery = "
    SELECT a.id, a.top_image, a.title, a.edition_id, c.name
    FROM articles a
    INNER JOIN categories c ON a.category_id = c.id
    WHERE a.category_id != :current_category_id
    ORDER BY RAND()
    LIMIT 8
";
$stmtArticle = $pdo->prepare($articleQuery);
$stmtArticle->bindParam(':current_category_id', $category_id, PDO::PARAM_INT);
$stmtArticle->execute();
$articlesList = $stmtArticle->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= htmlspecialchars($category_name) ?> - Turning Point Magazine</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>favicon-16x16.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="includes/new-navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="global.css">

    <style>
    /* --- THEME FONTS & VARS --- */
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
        padding: 100px 20px 40px 20px;
        text-align: center;
        border-bottom: 1px solid #eee;
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

    /* --- 2. SUB-CATEGORY NAV BAR --- */
    .category-nav-bar {
        position: relative;
        z-index: 2;
        background: #fff;
        padding: 15px 20px;
        text-align: center;
        border-bottom: 1px solid #eee;
        overflow-x: auto;
        white-space: nowrap;
    }

    .category-nav-bar a {
        display: inline-block;
        text-decoration: none;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text-grey);
        padding: 10px 20px;
        border-radius: 30px;
        margin: 0 5px;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .category-nav-bar a:hover {
        background-color: #f0f0f0;
        color: #333;
    }

    .category-nav-bar a.active {
        background-color: var(--brand-red);
        color: #fff;
        border-color: var(--brand-red);
    }

    /* --- 3. ARTICLE LIST CONTAINER --- */
    .article-list-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }

    /* --- 4. STYLES FOR AJAX-LOADED CARDS --- */
    .article-list-card {
        display: flex;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        overflow: hidden;
        text-decoration: none;
        color: var(--text-dark);
        transition: all 0.3s ease;
    }

    .article-list-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    /* ---
    **HERE IS THE CHANGE (Desktop)** I've added a max-height of 250px to the image wrapper on desktop.
    --- */
    .article-image-wrapper {
        width: 35%;
        flex-shrink: 0;
        background: #f0f0f0;
        max-height: 250px;
        /* Limits the height */
    }

    .article-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .article-content {
        padding: 30px;
        width: 65%;
        display: flex;
        flex-direction: column;
    }

    .article-content h3 {
        font-family: 'florania', sans-serif;
        font-size: 2.2rem;
        color: #333;
        margin: 0 0 10px 0;
        line-height: 1.2;
    }

    .article-content p.catchphrase {
        font-size: 1.1rem;
        color: #555;
        line-height: 1.6;
        margin: 0 0 15px 0;
        flex-grow: 1;
    }

    .article-meta {
        font-size: 0.9rem;
        color: #777;
        font-weight: 500;
    }

    .article-meta span {
        margin-right: 15px;
    }

    .article-meta i {
        color: var(--brand-red);
        margin-right: 5px;
    }

    @media (max-width: 768px) {
        .article-list-card {
            flex-direction: column;
        }

        /* ---
        **HERE IS THE CHANGE (Mobile)** I've reduced the mobile height from 250px to 220px.
        --- */
        .article-image-wrapper {
            width: 100%;
            height: 220px;
            /* Reduced from 250px */
            max-height: 220px;
            /* Match the height */
        }

        .article-content {
            width: 100%;
            padding: 20px;
        }

        .article-content h3 {
            font-size: 1.8rem;
        }
    }

    /* --- 5. CAROUSEL SECTION --- */
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

    @media (max-width: 768px) {
        .edition-card {
            flex: 0 0 calc(50% - 10px);
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
        <div class="tp-intro-main">Turning Point</div>
        <h2 class="tp-intro-sub"><?= htmlspecialchars($category_name) ?></h2>
        <nav class="tp-intro-nav">
            <a href="index.php">Home</a> <span><i class="fas fa-chevron-right"></i>
                <?= htmlspecialchars($category_name) ?></span>
        </nav>
    </section>

    <?php if (!empty($subcategories)): ?>
    <nav class="category-nav-bar fade-in-up" style="animation-delay: 0.4s;">
        <?php foreach ($subcategories as $sub): ?>
        <a href="javascript:void(0)" data-sub-category-id="<?= $sub['id'] ?>"
            class="<?= $sub['id'] == $sub_category_id ? 'active' : '' ?>">
            <?= htmlspecialchars($sub['name']) ?>
        </a>
        <?php endforeach; ?>
    </nav>
    <?php endif; ?>

    <section class="article-list-container fade-in-up" id="cat-container" style="animation-delay: 0.6s;">
    </section>

    <section class="more-stories-section">
        <h2 class="section-title">You May Also Like</h2>

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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function() {
        // Function to load articles
        function loadArticles(category_id, sub_category_id) {
            $.ajax({
                url: 'load_article.php', // This is the new file you need
                method: 'GET',
                data: {
                    id: category_id,
                    sub_category_id: sub_category_id
                },
                beforeSend: function() {
                    $('#cat-container').html(
                        "<p style='text-align:center;'>Loading articles...</p>");
                },
                success: function(response) {
                    $('#cat-container').html(response);
                },
                error: function() {
                    $('#cat-container').html(
                        "<p style='text-align:center; color:red;'>Failed to load articles.</p>");
                }
            });
        }

        // Click handler for new sub-category nav
        $('.category-nav-bar a').click(function() {
            var category_id = <?= $category_id ?>;
            var sub_category_id = $(this).data('sub-category-id');

            // Update active class
            $('.category-nav-bar a').removeClass('active');
            $(this).addClass('active');

            // Load articles
            loadArticles(category_id, sub_category_id);
        });

        // Initial page load (loads the default active sub-category)
        loadArticles(<?= $category_id ?>, <?= $sub_category_id ?>);
    });
    </script>

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
        if (!editionCards) return; // Safety check
        const offset = -(currentIndex * (100 / (cardsPerPage || 1)));
        editionCards.style.transform = `translateX(${offset}%)`;

        const maxIndex = Math.max(0, totalCards - cardsPerPage);
        if (prevBtn) prevBtn.style.opacity = currentIndex <= 0 ? '0.5' : '1';
        if (nextBtn) nextBtn.style.opacity = currentIndex >= maxIndex ? '0.5' : '1';
    }

    if (nextBtn) nextBtn.addEventListener('click', () => {
        const maxIndex = Math.max(0, totalCards - cardsPerPage);
        if (currentIndex < maxIndex) {
            currentIndex++;
            updateCardPosition();
        }
    });

    if (prevBtn) prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateCardPosition();
        }
    });

    window.addEventListener('load', () => {
        totalCards = document.querySelectorAll('.edition-card').length;
        updateCardsPerPage();
        updateCardPosition();
    });
    window.addEventListener('resize', updateCardsPerPage);
    </script>
</body>

</html>
