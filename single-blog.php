<?php
require_once('connection2.php');
$pdo = connect();

// 1. GET THE BLOG POST BASED ON THE SLUG
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$current_blog = null;

if ($slug) {
    // Fetch all titles to find the matching slug (since we don't have a slug column)
    $stmt = $pdo->query("SELECT id, title FROM blog");
    $all_blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($all_blogs as $b) {
        // Recreate the slug logic to match the URL
        $s = strtolower(preg_replace('/\s+/', '-', $b['title']));
        $s = preg_replace('/[^a-z0-9\-]/', '', $s);
        $s = preg_replace('/-+/', '-', $s);
        $s = trim($s, '-');

        if ($s === $slug) {
            $current_id = $b['id'];
            break;
        }
    }

    if (isset($current_id)) {
        // Fetch the full post details
        $stmt = $pdo->prepare("SELECT * FROM blog WHERE id = :id");
        $stmt->execute(['id' => $current_id]);
        $current_blog = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Redirect if not found
if (!$current_blog) {
    header("Location: /blog");
    exit;
}

// Generate Meta Description Excerpt
$excerpt = strip_tags(html_entity_decode($current_blog['description'] ?? ''));
$excerpt = trim(preg_replace('/\s+/', ' ', $excerpt));
$meta_description = mb_strlen($excerpt) > 155 ? mb_substr($excerpt, 0, 155) . '...' : $excerpt;

// 2. FETCH RECENT POSTS (For the Sidebar)
// Get 4 recent posts, excluding the current one
$stmt = $pdo->prepare("SELECT * FROM blog WHERE id != :id ORDER BY date DESC LIMIT 4");
$stmt->execute(['id' => $current_blog['id']]);
$recent_blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

closeConnection($pdo);

// Helper for generating links
function make_slug($string) {
    $s = strtolower(preg_replace('/\s+/', '-', $string));
    $s = preg_replace('/[^a-z0-9\-]/', '', $s);
    $s = preg_replace('/-+/', '-', $s);
    return trim($s, '-');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://www.turningpointmagazine.africa/blog/<?php echo htmlspecialchars($slug); ?>">
    <title><?= htmlspecialchars($current_blog['title']) ?> - Turning Point Magazine</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="includes/new-navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="global.css">

    <!-- Open Graph & Twitter Cards -->
    <meta property="og:title" content="<?= htmlspecialchars($current_blog['title']) ?> - Turning Point Magazine" />
    <meta property="og:description" content="<?= htmlspecialchars($meta_description) ?>" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="https://www.turningpointmagazine.africa/blog/<?= htmlspecialchars($slug) ?>" />
    <meta property="og:image" content="https://www.turningpointmagazine.africa/admin/<?= htmlspecialchars($current_blog['top_photo']) ?>" />
    <meta name="twitter:card" content="summary_large_image" />

    <!-- JSON-LD Article Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Article",
      "headline": <?= json_encode($current_blog['title']) ?>,
      "image": "https://www.turningpointmagazine.africa/admin/<?= htmlspecialchars($current_blog['top_photo']) ?>",
      "author": {
        "@type": "Person",
        "name": "Turning Point Editorial"
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

    <style>
    /* --- THEME VARS --- */
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

    body {
        padding-top: 0 !important;
        background-color: #fff;
        font-family: 'Poppins', sans-serif;
    }

    /* --- HERO / BREADCRUMB --- */
    .breadcrumb-container {
        background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/breadcrumbs/bread.jpeg');
        background-size: cover;
        background-position: center;
        position: absolute;
        z-index: 1;
        top: 0;
        width: 100%;
        height: 150px;
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
        font-size: 3rem;
        color: var(--brand-teal);
        margin: 0;
        line-height: 1.2;
    }

    .tp-intro-nav {
        font-size: 0.9rem;
        font-weight: 600;
        color: #666;
        margin-top: 10px;
    }
    .tp-intro-nav a { color: var(--brand-red); text-decoration: none; }

    /* --- MAIN LAYOUT --- */
    .single-blog-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 60px 20px;
        display: grid;
        grid-template-columns: 2.5fr 1fr; /* Main Content vs Sidebar */
        gap: 60px;
    }

    /* --- ARTICLE STYLES --- */
    .article-header { margin-bottom: 30px; }

    .article-title {
        font-family: 'florania', sans-serif;
        font-size: 2.8rem;
        line-height: 1.1;
        color: var(--text-dark);
        margin-bottom: 15px;
    }

    .article-meta {
        display: flex;
        gap: 20px;
        font-size: 0.9rem;
        color: var(--text-light-gray);
        border-bottom: 1px solid var(--border-light);
        padding-bottom: 20px;
        margin-bottom: 30px;
    }
    .article-meta i { color: var(--brand-pink); margin-right: 5px; }

    .main-image {
        width: 100%;
        height: auto;
        max-height: 500px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        margin-bottom: 40px;
    }

    .article-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #444;
    }
    .article-content p { margin-bottom: 25px; }
    .article-content h3 { color: var(--brand-teal); margin-top: 30px; font-family: 'florania', sans-serif; }
    .article-content img { max-width: 100%; height: auto; border-radius: 8px; margin: 20px 0; }

    /* --- DOCUMENT DOWNLOAD CTA --- */
    .doc-cta {
        background: #fff0f7; /* Light Pink Bg */
        border-left: 5px solid var(--brand-pink);
        padding: 30px;
        margin: 40px 0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
    }

    .doc-cta-text h4 { margin: 0 0 5px 0; color: var(--brand-pink); font-family: 'florania', sans-serif; font-size: 1.5rem; }
    .doc-cta-text p { margin: 0; font-size: 0.95rem; color: #666; }

    .btn-download {
        background: var(--brand-red);
        color: #fff;
        padding: 12px 25px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 5px 15px rgba(255, 0, 0, 0.2);
    }
    .btn-download:hover { background: #cc0000; transform: translateY(-3px); color: #fff; }
    .btn-download i { margin-right: 10px; }

    /* --- GALLERY GRID --- */
    .mini-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 15px;
        margin-top: 40px;
        padding-top: 40px;
        border-top: 1px solid var(--border-light);
    }
    .gallery-img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        transition: transform 0.3s;
    }
    .gallery-img:hover { transform: scale(1.05); }

    /* --- SIDEBAR --- */
    .sidebar-widget {
        background: var(--bg-off-white);
        padding: 30px;
        border-radius: 12px;
        position: sticky;
        top: 100px; /* Sticky scroll */
    }

    .widget-title {
        font-family: 'florania', sans-serif;
        font-size: 1.8rem;
        color: var(--brand-teal);
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--brand-gold);
    }

    .side-post {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        align-items: center;
    }
    .side-post img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }
    .side-post-info h5 {
        margin: 0 0 5px 0;
        font-size: 1rem;
        line-height: 1.3;
    }
    .side-post-info h5 a {
        text-decoration: none;
        color: var(--text-dark);
        transition: color 0.3s;
    }
    .side-post-info h5 a:hover { color: var(--brand-red); }
    .side-post-date {
        font-size: 0.8rem;
        color: #888;
    }

    /* --- RESPONSIVE --- */
    @media (max-width: 900px) {
        .single-blog-wrapper { grid-template-columns: 1fr; }
        .sidebar-widget { margin-top: 40px; position: static; }
    }
    @media (max-width: 600px) {
        .article-title { font-size: 2rem; }
        .doc-cta { flex-direction: column; text-align: center; }
    }
    </style>
</head>

<body>
    <?php include 'includes/preloader.php'; ?>
    <?php include 'includes/new-navbar.php';?>

    <div class="breadcrumb-container"></div>
    <section class="tp-fun-intro">
        <h2 class="tp-intro-main">Article Details</h2>
        <nav class="tp-intro-nav">
            <a href="index.php">Home</a> / <a href="blog.php">Blog</a> / <span>Reading</span>
        </nav>
    </section>

    <div class="single-blog-wrapper">
        
        <article>
            <div class="article-header">
                <h1 class="article-title"><?= htmlspecialchars($current_blog['title']) ?></h1>
                <div class="article-meta">
                    <span><i class="fas fa-calendar-alt"></i> <?= date('F j, Y', strtotime($current_blog['date'])) ?></span>
                    <?php if(!empty($current_blog['venue'])): ?>
                        <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($current_blog['venue']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <img loading="lazy" src="admin/<?= htmlspecialchars($current_blog['top_photo']) ?>" 
                 alt="<?= htmlspecialchars($current_blog['title']) ?>" 
                 class="main-image">

            <?php if (!empty($current_blog['document'])): ?>
            <div class="doc-cta">
                <div class="doc-cta-text">
                    <h4>Detailed Report Available</h4>
                    <p>Access the full documentation and resources for this event.</p>
                </div>
                <a href="admin/<?= htmlspecialchars($current_blog['document']) ?>" class="btn-download" target="_blank">
                    <i class="fas fa-file-pdf"></i> Read The Full Report
                </a>
            </div>
            <?php endif; ?>

            <div class="article-content">
                <?= $current_blog['story'] ?>
            </div>

            <?php 
            $gallery = json_decode($current_blog['other_photos'], true);
            if(is_array($gallery) && !empty($gallery)): 
            ?>
            <div class="mini-gallery">
                <?php foreach($gallery as $img): ?>
                <img loading="lazy" src="admin/<?= htmlspecialchars($img) ?>" class="gallery-img" alt="Gallery">
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </article>

        <aside>
            <div class="sidebar-widget">
                <h3 class="widget-title">Latest Stories</h3>
                
                <?php foreach($recent_blogs as $rb): 
                    $r_slug = make_slug($rb['title']);
                ?>
                <div class="side-post">
                    <a href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>blog/<?= $r_slug ?>">
                        <img loading="lazy" src="admin/<?= htmlspecialchars($rb['top_photo']) ?>" alt="Thumb">
                    </a>
                    <div class="side-post-info">
                        <h5><a href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>blog/<?= $r_slug ?>"><?= htmlspecialchars($rb['title']) ?></a></h5>
                        <div class="side-post-date"><?= date('M d, Y', strtotime($rb['date'])) ?></div>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </aside>

    </div>

    <?php include 'includes/footer.php'?>

</body>
</html>
