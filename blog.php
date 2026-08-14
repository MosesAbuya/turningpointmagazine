<!DOCTYPE html>
<html lang="en">
<?php include('connection2.php'); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="canonical" href="https://www.turningpointmagazine.africa/blog">
    <title>Turning Point Magazine Blog - Africa's News & Culture</title>
    <meta name="description"
        content="Read the latest grassroots stories, news, and insights on positive change across Africa on the Turning Point Magazine blog." />

    <link rel="apple-touch-icon" sizes="180x180" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>favicon-16x16.png">
    <link rel="manifest" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>site.webmanifest">

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
    /* --- 1. THEME FONTS & VARS --- */
    :root {
        --brand-red: #ff0000;
        --brand-teal: #008080;
        --brand-pink: #E6007E;
        --brand-gold: #FFD700;
        --text-dark: #333;
        --text-light-gray: #555;
        --bg-off-white: #f8f9fa;
        --border-light: #eee;
    }

    @font-face {
        font-family: florania;
        src: url(assets/fonts/florania.otf) format("opentype");
    }

    /* --- 2. PAGE OVERRIDES & ANIMATIONS --- */
    body {
        padding-top: 0 !important;
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg-off-white);
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

    .fade-in-up {
        opacity: 0;
        animation: fadeInUp 0.6s ease-out forwards;
    }

    /* --- 3. HERO & INTRO SECTION (Standard) --- */
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

    .tp-intro-nav .fa-chevron-right {
        font-size: 0.8em;
        margin: 0 8px;
    }

    /* --- 4. NEW BLOG GRID LAYOUT --- */
    main {
        position: relative;
        z-index: 2;
        padding: 60px 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .blog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 40px;
    }

    /* Blog Card Styles */
    .blog-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
        border: 1px solid var(--border-light);
    }

    .blog-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        border-color: var(--brand-pink);
    }

    .card-image-wrapper {
        position: relative;
        height: 240px;
        overflow: hidden;
    }

    .card-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .blog-card:hover .card-image {
        transform: scale(1.08);
    }

    .card-date-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: var(--brand-red);
        color: #fff;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        z-index: 2;
    }

    .card-content {
        padding: 25px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .card-meta {
        font-size: 0.8rem;
        color: var(--brand-teal);
        margin-bottom: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
    }

    .card-meta i {
        margin-right: 6px;
    }

    .card-title {
        font-family: 'florania', sans-serif;
        font-size: 1.6rem;
        line-height: 1.2;
        color: var(--text-dark);
        margin: 0 0 12px 0;
        transition: color 0.3s ease;
    }
    
    .blog-card:hover .card-title {
        color: var(--brand-pink);
    }

    .card-excerpt {
        color: var(--text-light-gray);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 20px;
        flex-grow: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .card-footer {
        border-top: 1px solid var(--border-light);
        padding-top: 15px;
        margin-top: auto;
    }

    .read-more-btn {
        text-decoration: none;
        color: var(--brand-red);
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        width: fit-content;
    }

    .read-more-btn i {
        margin-left: 8px;
        transition: transform 0.3s ease;
    }

    .read-more-btn:hover i {
        transform: translateX(5px);
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
        
        .blog-grid {
            grid-template-columns: 1fr;
            gap: 30px;
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
    }
    </style>
    <!-- Open Graph & Twitter Cards -->
    <meta property="og:title" content="Turning Point Magazine Blog - Africa's News & Culture" />
    <meta property="og:description" content="Read the latest grassroots stories, news, and insights on positive change across Africa on the Turning Point Magazine blog." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.turningpointmagazine.africa/blog" />
    <meta property="og:image" content="https://www.turningpointmagazine.africa/assets/logo.png" />
    <meta name="twitter:card" content="summary_large_image" />

    <!-- JSON-LD Organization Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Turning Point Magazine",
      "url": "https://www.turningpointmagazine.africa/",
      "logo": "https://www.turningpointmagazine.africa/assets/logo.png"
    }
    </script>
</head>

<body>
    <?php include 'includes/preloader.php'; ?>
    <?php include 'includes/new-navbar.php';?>

    <section class="tp-fun-intro fade-in-up" style="animation-delay: 0.2s;">
        <h1 class="tp-intro-main">Turning Point Magazine</h1>
        <h2 class="tp-intro-sub">Blog</h2>
        <nav class="tp-intro-nav">
            <a href="index.php">Home</a> <span><i class="fas fa-chevron-right"></i> Blog</span>
        </nav>
    </section>

    <main>
        <?php
        $pdo = connect();
        $stmt = $pdo->query("SELECT * FROM blog ORDER BY id DESC");
        $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        closeConnection($pdo);

        // Helper function for excerpt
        function get_excerpt($text, $limit = 120) {
            $text = strip_tags($text);
            if (strlen($text) > $limit) {
                $text = substr($text, 0, $limit) . '...';
            }
            return $text;
        }
        ?>

        <div class="blog-grid">
            <?php foreach($blogs as $index => $blog):
                // --- SLUG GENERATION LOGIC ---
                // 1. Lowercase and replace spaces with hyphens
                $slug = strtolower(preg_replace('/\s+/', '-', $blog['title']));
                // 2. Remove all non-alphanumeric characters except hyphens
                $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
                // 3. Remove multiple consecutive hyphens
                $slug = preg_replace('/-+/', '-', $slug);
                // 4. Trim hyphens from start/end
                $slug = trim($slug, '-');

                // Create link using the slug
                $link = "/blog/" . $slug;
            ?>
            
            <article class="blog-card fade-in-up" style="animation-delay: <?= 0.2 + ($index * 0.1) ?>s;">
                <div class="card-image-wrapper">
                    <div class="card-date-badge">
                        <?= date('M d, Y', strtotime($blog['date'])) ?>
                    </div>
                    <a href="<?= $link ?>">
                        <img loading="lazy" src="admin/<?= htmlspecialchars($blog['top_photo']) ?>" 
                             alt="<?= htmlspecialchars($blog['title']) ?>" 
                             class="card-image">
                    </a>
                </div>

                <div class="card-content">
                    <div class="card-meta">
                        <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($blog['venue']) ?>
                    </div>

                    <a href="<?= $link ?>" style="text-decoration: none;">
                        <h3 class="card-title"><?= htmlspecialchars($blog['title']) ?></h3>
                    </a>

                    <div class="card-excerpt">
                        <?= get_excerpt($blog['story']) ?>
                    </div>

                    <div class="card-footer">
                        <a href="<?= $link ?>" class="read-more-btn">
                            Read Full Story <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </article>
            
            <?php endforeach; ?>
        </div>

        <?php if (empty($blogs)): ?>
            <div style="text-align: center; padding: 60px; color: var(--text-light-gray);">
                <h3>No stories found yet.</h3>
                <p>Check back soon for our latest updates!</p>
            </div>
        <?php endif; ?>

    </main>

    <?php include 'includes/footer.php'?>

</body>
</html>

