<?php
include('connection2.php');
$pdo = connect();

// --- 1. PAGINATION LOGIC ---
$posts_per_page = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $posts_per_page;

// --- 2. GET TOTAL POSTS FOR PAGINATION ---
$total_posts_stmt = $pdo->query("SELECT COUNT(*) FROM spotlight_posts");
$total_posts = $total_posts_stmt->fetchColumn();
$total_pages = ceil($total_posts / $posts_per_page);

// --- 3. GET POSTS FOR THE CURRENT PAGE ---
$stmt = $pdo->prepare("SELECT * FROM spotlight_posts ORDER BY post_date DESC LIMIT :limit OFFSET :offset");
$stmt->bindParam(':limit', $posts_per_page, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Partner Spotlight - Turning Point Magazine</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>favicon-16x16.png">
    <link rel="manifest" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>site.webmanifest">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="includes/new-navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="global.css">

    <style>
    @font-face {
        font-family: florania;
        src: url(assets/fonts/florania.otf) format("opentype");
    }

    body {
        padding-top: 0 !important;
    }

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
        margin-top: 0;
        background: #ffffff;
        padding: 100px 20px 60px 20px;
        text-align: center;
        border-bottom: 1px solid #eee;
    }

    .tp-intro-main {
        font-family: 'florania', sans-serif;
        font-size: 3.5rem;
        font-weight: 700;
        color: #008080;
        margin: 0;
        line-height: 1.2;
    }

    .tp-intro-sub {
        font-family: 'Caveat', cursive;
        font-size: 3.8rem;
        color: #E6007E;
        margin: -10px 0 15px 0;
        line-height: 1;
    }

    .tp-intro-nav {
        font-size: 0.9rem;
        font-weight: 600;
        color: #666;
    }

    .tp-intro-nav a {
        color: #ff0000;
        text-decoration: none;
        transition: opacity 0.3s ease;
    }

    .tp-intro-nav a:hover {
        opacity: 0.7;
    }

    .tp-intro-nav .fa-chevron-right {
        font-size: 0.8em;
        margin: 0 8px;
        color: #666;
    }

    main {
        position: relative;
        z-index: 2;
        background: #fff;
    }

    .spotlight-container {
        padding: 40px 20px;
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
    }

    .spotlight-card {
        display: flex;
        flex-direction: column;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }

    .spotlight-card:hover {
        box-shadow: 0 6px 18px -4px rgba(0, 0, 0, 0.15);
        transform: translateY(-5px);
    }

    .spotlight-card .thumbnail-wrapper {
        width: 100%;
        height: 200px;
        overflow: hidden;
    }

    .spotlight-card .thumbnail {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .spotlight-card:hover .thumbnail {
        transform: scale(1.05);
    }

    .spotlight-card .card-content {
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex-grow: 1;
    }

    .spotlight-card h3 {
        margin-top: 0;
        font-size: 1.5rem;
    }

    .spotlight-card .description {
        font-size: 0.9em;
        color: #555;
        margin-top: 10px;
        line-height: 1.5;
        flex-grow: 1;
    }

    .spotlight-card .read-more {
        margin-top: 15px;
        text-decoration: none;
        color: #ff0000;
        font-weight: 700;
        align-self: flex-start;
    }

    .spotlight-card .read-more .fa-arrow-right {
        transition: transform 0.3s ease;
    }

    .spotlight-card .read-more:hover .fa-arrow-right {
        transform: translateX(5px);
    }

    .spotlight-card .read-more:hover {
        text-decoration: underline;
    }

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        padding: 40px 20px;
        margin-top: 20px;
    }

    .pagination a {
        color: #333;
        text-decoration: none;
        padding: 10px 15px;
        margin: 5px;
        border-radius: 5px;
        border: 1px solid #ddd;
        transition: all 0.3s ease;
    }

    .pagination a:hover {
        background-color: #f5f5f5;
    }

    .pagination a.active {
        background-color: #ff0000;
        color: #fff;
        border-color: #ff0000;
        font-weight: 700;
    }

    .pagination a.disabled {
        color: #aaa;
        pointer-events: none;
        border-color: #eee;
    }

    @media (max-width: 768px) {
        .spotlight-container {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            padding: 30px 15px;
        }

        .pagination a {
            padding: 8px 12px;
            margin: 3px;
            font-size: 0.9rem;
        }
    }
    </style>
</head>

<body>
    <?php include 'includes/preloader.php'; ?>
    <?php include 'includes/new-navbar.php';?>

    <section class="tp-fun-intro fade-in-up" style="animation-delay: 0.2s;">
        <h1 class="tp-intro-main">Partner Spotlight</h1>
        <h2 class="tp-intro-sub">Highlighting Impactful Stories</h2>
        <nav class="tp-intro-nav">
            <a href="index.php">Home</a> <span><i class="fas fa-chevron-right"></i> Spotlight</span>
        </nav>
    </section>

    <main>
        <section id="spotlight">
            <div class="spotlight-container">
                <?php if (empty($posts)): ?>
                <p style="text-align: center; grid-column: 1 / -1;">No spotlight posts found.</p>
                <?php else: ?>
                <?php foreach ($posts as $post): ?>
                <a href="spotlight-detail.php?id=<?= $post['id'] ?>" class="spotlight-card">
                    <div class="thumbnail-wrapper">
                        <img loading="lazy" src="admin/<?= htmlspecialchars($post['thumbnail_image']) ?>"
                            alt="<?= htmlspecialchars($post['post_title']) ?>" class="thumbnail" loading="lazy">
                    </div>
                    <div class="card-content">
                        <div>
                            <h3><?= htmlspecialchars($post['post_title']) ?></h3>
                            <div class="description">
                                <?= substr(strip_tags($post['post_description']), 0, 220) ?>...
                            </div>
                        </div>
                        <span class="read-more">Read More <i class="fas fa-arrow-right"></i></span>
                    </div>
                </a>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <nav class="pagination">
                <?php if ($page > 1): ?>
                <a href="spotlight.php?page=<?= $page - 1 ?>">Previous</a>
                <?php else: ?>
                <a href="#" class="disabled">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="spotlight.php?page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                <a href="spotlight.php?page=<?= $page + 1 ?>">Next</a>
                <?php else: ?>
                <a href="#" class="disabled">Next</a>
                <?php endif; ?>
            </nav>

        </section>
    </main>

    <?php include 'includes/footer.php';?>
</body>

</html>

