<?php
include('connection2.php');
$pdo = connect();

if (!isset($_GET['id']) || empty($_GET['id'])) {
  die('Spotlight ID is required.');
}

$post_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM spotlight_posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
  die('Spotlight not found.');
}

// --- Fetch other posts ---
$other_stmt = $pdo->prepare("SELECT * FROM spotlight_posts WHERE id != ? ORDER BY post_date DESC LIMIT 3");
$other_stmt->execute([$post_id]);
$other_posts = $other_stmt->fetchAll(PDO::FETCH_ASSOC);

$files = json_decode($post['file_upload'], true);

// --- Format Post Date ---
$post_date_formatted = !empty($post['post_date']) ? date("F j, Y", strtotime($post['post_date'])) : null;

// --- Helper Function for File Types ---
function get_file_details($filename) {
  $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
  $image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
  
  $icon_class = 'fa-file-alt'; // Default icon
  $color_class = 'doc-color-default'; // Default color
  
  switch ($extension) {
    case 'pdf':
      $icon_class = 'fa-file-pdf';
      $color_class = 'doc-color-pdf';
      break;
    case 'doc':
    case 'docx':
      $icon_class = 'fa-file-word';
      $color_class = 'doc-color-word';
      break;
    case 'xls':
    case 'xlsx':
      $icon_class = 'fa-file-excel';
      $color_class = 'doc-color-excel';
      break;
    case 'ppt':
    case 'pptx':
      $icon_class = 'fa-file-powerpoint';
      $color_class = 'doc-color-ppt';
      break;
    case 'mp4':
    case 'webm':
    case 'mov':
      $icon_class = 'fa-file-video';
      $color_class = 'doc-color-video';
      break;
    case 'zip':
    case 'rar':
      $icon_class = 'fa-file-archive';
      $color_class = 'doc-color-archive';
      break;
  }

  return [
    'is_image' => in_array($extension, $image_extensions),
    'icon' => $icon_class,
    'color_class' => $color_class,
    'filename' => basename($filename)
  ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['post_title']) ?> - Turning Point Magazine</title>

    <link rel="stylesheet" href="includes/tp-navbar.css">
    <link rel="stylesheet" href="tp-design-system.css">
    <link rel="stylesheet" href="global.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
    /* --- 1. FONTS & VARS --- */
    @font-face {
        font-family: florania;
        src: url(assets/fonts/florania.otf) format("opentype");
    }

    :root {
        --brand-red: #ff0000;
        --brand-red-dark: #cc0000;
        --brand-teal: #008080;
        --brand-pink: #E6007E;
        --brand-gold: #FFD700;
        --text-dark: #333;
        --text-light-gray: #555;
        --bg-off-white: #f8f9fa;
        --border-light: #eee;
    }

    body {
        padding-top: 0 !important;
        font-family: 'Poppins', sans-serif;
        background: #fff;
        color: var(--text-dark);
    }

    /* --- 2. INTRO & BREADCRUMB --- */
    .breadcrumb-container {
        background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/breadcrumbs/bread.jpeg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        /* Playful parallax */
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

    /* --- 2.5 NEW META TAGS (POST TYPE, DATE) --- */
    .tp-post-meta {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .tp-meta-tag {
        background: #eee;
        color: var(--text-light-gray);
        padding: 8px 15px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        line-height: 1;
    }

    .tp-meta-tag i {
        margin-right: 8px;
    }

    /* Playful colors for post types */
    .tp-meta-tag.type-story {
        background: var(--brand-teal);
        color: #fff;
    }

    .tp-meta-tag.type-pdf {
        background: #E74C3C;
        color: #fff;
    }

    .tp-meta-tag.type-image {
        background: var(--brand-pink);
        color: #fff;
    }

    .tp-meta-tag.type-advertisement {
        background: var(--brand-gold);
        color: var(--text-dark);
    }


    /* --- 3. MAIN CONTENT & TYPOGRAPHY --- */
    main {
        position: relative;
        z-index: 2;
        background: #fff;
        padding: 60px 20px;
    }

    /* Main Content Body */
    .spotlight-content-body {
        max-width: 800px;
        margin: 0 auto 60px auto;
        font-size: 1.1rem;
        line-height: 1.8;
        color: var(--text-light-gray);
    }

    .spotlight-content-body h1,
    .spotlight-content-body h2,
    .spotlight-content-body h3,
    .spotlight-content-body h4 {
        font-family: 'florania', sans-serif;
        color: var(--brand-teal);
        margin-top: 1.5em;
        margin-bottom: 0.5em;
        line-height: 1.3;
    }

    .spotlight-content-body h1 {
        font-size: 2.5rem;
    }

    .spotlight-content-body h2 {
        font-size: 2.2rem;
    }

    .spotlight-content-body h3 {
        font-size: 1.8rem;
        color: var(--brand-pink);
    }

    .spotlight-content-body h4 {
        font-size: 1.5rem;
    }

    .spotlight-content-body p {
        margin-bottom: 1.5em;
    }

    .spotlight-content-body a {
        color: var(--brand-red);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border-bottom: 2px dashed rgba(255, 0, 0, 0.2);
    }

    .spotlight-content-body a:hover {
        color: #fff;
        background: var(--brand-red);
        border-bottom-color: var(--brand-red);
    }

    .spotlight-content-body ul,
    .spotlight-content-body ol {
        margin-bottom: 1.5em;
        padding-left: 25px;
    }

    .spotlight-content-body li {
        margin-bottom: 0.5em;
    }

    .spotlight-content-body ul li::marker {
        color: var(--brand-pink);
        font-weight: bold;
    }

    .spotlight-content-body blockquote {
        font-family: 'florania', sans-serif;
        font-size: 1.5rem;
        color: var(--brand-teal);
        border-left: 5px solid var(--brand-pink);
        padding: 10px 20px;
        margin: 1.5em 0;
        background: var(--bg-off-white);
        border-radius: 0 8px 8px 0;
    }

    /* --- 3.5 NEW EXTERNAL LINK BUTTON --- */
    .tp-external-link-container {
        text-align: center;
        margin: 20px auto 60px auto;
        padding-top: 40px;
        border-top: 1px dashed #ddd;
        max-width: 800px;
    }

    .btn-playful {
        background: var(--brand-red);
        color: white;
        border: none;
        padding: 15px 30px;
        border-radius: 50px;
        cursor: pointer;
        font-weight: 700;
        font-size: 1.1rem;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(255, 0, 0, 0.3);
    }

    .btn-playful:hover {
        background: var(--brand-red-dark);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(255, 0, 0, 0.4);
    }

    .btn-playful i {
        margin-left: 10px;
        transition: transform 0.2s ease-out;
    }

    .btn-playful:hover i {
        transform: scale(1.1) translateX(2px);
    }


    /* --- 4. PLAYFUL SECTION TITLE --- */
    .playful-section-title {
        text-align: center;
        font-family: 'florania', sans-serif;
        font-size: 2.8rem;
        color: var(--brand-teal);
        margin-bottom: 60px;
        position: relative;
        display: block;
    }

    .playful-section-title::after {
        content: "";
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        width: 150px;
        bottom: -15px;
        height: 16px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 20'%3E%3Cpath d='M5 15 Q 25 5 50 15 T 95 5' stroke='%23E6007E' stroke-width='5' fill='none'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-size: 100% 100%;
    }

    /* --- 5. GALLERY & DOWNLOADS --- */
    .gallery-container,
    .related-posts-container {
        max-width: 1200px;
        margin: 60px auto;
        padding: 60px 0;
        border-top: 1px dashed #ddd;
    }

    .gallery-container {
        /* Don't add a dash if the external link button was already there */
        border-top: <?php echo empty($post['external_link']) ? '1px dashed #ddd': 'none';
        ?>;
        padding-top: <?php echo empty($post['external_link']) ? '60px': '0';
        ?>;
    }

    .related-posts-container {
        background: var(--bg-off-white);
        max-width: none;
        padding: 60px 20px;
    }

    .related-posts-container>div {
        max-width: 1200px;
        margin: 0 auto;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
    }

    .gallery-card-base {
        display: block;
        text-decoration: none;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.07);
        overflow: hidden;
        background: #fff;
        transition: all 0.3s ease;
        border: 1px solid var(--border-light);
    }

    .gallery-card-base:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    /* Style for IMAGE cards */
    .gallery-card {
        position: relative;
        cursor: zoom-in;
    }

    .gallery-card-inner {
        width: 100%;
        height: 250px;
    }

    .gallery-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .gallery-card:hover img {
        transform: scale(1.05);
    }

    .gallery-card::after {
        content: '\f00e';
        /* fa-search-plus */
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.5);
        color: #fff;
        font-size: 2.5rem;
        background: rgba(255, 0, 0, 0.7);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .gallery-card:hover::after {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }
    
    /* --- NEW DOCUMENT CTA BUTTON --- */
    .document-cta-container {
        max-width: 800px;
        margin: 0 auto 40px auto;
        text-align: center;
    }

    .btn-download {
        background: var(--brand-red);
        color: #fff;
        padding: 12px 30px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 5px 15px rgba(255, 0, 0, 0.2);
    }
    .btn-download:hover { background: #cc0000; transform: translateY(-3px); color: #fff; box-shadow: 0 8px 20px rgba(255, 0, 0, 0.3); }
    .btn-download i { margin-right: 10px; }

    /* Style for DOCUMENT cards */
    .document-card {
        display: flex;
        flex-direction: column;
        height: 250px;
        border-top-width: 5px;
        border-top-style: solid;
    }

    .document-card-inner {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px;
        text-align: center;
        background: var(--bg-off-white);
    }

    .document-card-inner i {
        font-size: 4rem;
        margin-bottom: 20px;
    }

    .document-card .file-name {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.9rem;
        line-height: 1.4;
        /* Handle long names */
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .document-download-bar {
        background: var(--brand-red);
        color: #fff;
        padding: 12px 15px;
        text-align: center;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .document-download-bar i {
        margin-left: 8px;
        transition: transform 0.3s ease;
    }

    .document-card:hover .document-download-bar i {
        transform: translateY(2px);
    }

    .document-card:hover .document-download-bar {
        background: var(--brand-red-dark);
    }

    /* Document Icon Colors */
    .doc-color-pdf {
        border-color: #E74C3C;
    }

    .doc-color-pdf i {
        color: #E74C3C;
    }

    _ .doc-color-word {
        border-color: #3498DB;
    }

    .doc-color-word i {
        color: #3498DB;
    }

    .doc-color-excel {
        border-color: #27AE60;
    }

    .doc-color-excel i {
        color: #27AE60;
    }

    .doc-color-ppt {
        border-color: #E67E22;
    }

    .doc-color-ppt i {
        color: #E67E22;
    }

    .doc-color-video {
        border-color: #9B59B6;
    }

    .doc-color-video i {
        color: #9B59B6;
    }

    .doc-color-archive {
        border-color: #F1C40F;
    }

    .doc-color-archive i {
        color: #F1C40F;
    }

    .doc-color-default {
        border-color: #95A5A6;
    }

    .doc-color-default i {
        color: #95A5A6;
    }


    /* --- 6. LIGHTBOX STYLES --- */
    .lightbox-modal {
        display: none;
        position: fixed;
        z-index: 1010;
        /* Above nav */
        padding-top: 60px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.9);
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.4s;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .lightbox-content {
        margin: auto;
        display: block;
        max-width: 90%;
        max-height: 90vh;
        border-radius: 8px;
        animation: zoomIn 0.4s;
    }

    @keyframes zoomIn {
        from {
            transform: scale(0.5);
        }

        to {
            transform: scale(1);
        }
    }

    .lightbox-close {
        position: absolute;
        top: 20px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
        cursor: pointer;
    }

    .lightbox-close:hover,
    .lightbox-close:focus {
        color: #bbb;
        text-decoration: none;
    }

    /* --- 7. RELATED POSTS --- */
    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
    }

    .spotlight-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.07);
        border: 1px solid var(--border-light);
        text-decoration: none;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .spotlight-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .thumbnail-wrapper {
        width: 100%;
        height: 200px;
        overflow: hidden;
    }

    .spotlight-card .thumbnail {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .spotlight-card:hover .thumbnail {
        transform: scale(1.05);
    }

    .card-content {
        padding: 25px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex-grow: 1;
    }

    .card-content h3 {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 1.2rem;
        color: var(--text-dark);
        margin: 0 0 10px 0;
        line-height: 1.4;
    }

    .card-content .description {
        font-size: 0.95rem;
        color: var(--text-light-gray);
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .card-content .read-more {
        font-weight: 600;
        color: var(--brand-red);
        font-size: 0.9rem;
    }

    .card-content .read-more i {
        transition: transform 0.3s ease;
        margin-left: 5px;
    }

    .spotlight-card:hover .read-more i {
        transform: translateX(5px);
    }

    /* --- 8. RESPONSIVE --- */
    @media (max-width: 768px) {
        .tp-intro-main {
            font-size: 2.8rem;
        }

        .tp-intro-sub {
            font-size: 3rem;
        }

        main {
            padding: 40px 15px;
        }

        .spotlight-content-body {
            font-size: 1rem;
            margin-bottom: 40px;
        }

        .tp-external-link-container {
            margin: 20px 0 40px 0;
            padding-top: 20px;
        }

        .gallery-container,
        .related-posts-container {
            margin: 40px auto;
            padding: 40px 0;
        }

        .gallery-container {
            padding-top: <?php echo empty($post['external_link']) ? '40px': '0';
            ?>;
        }

        .related-posts-container {
            padding: 40px 15px;
        }

        .playful-section-title {
            font-size: 2.2rem;
            margin-bottom: 50px;
        }

        .gallery-grid,
        .related-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>
</head>

<body>
    <?php include 'includes/preloader.php'; ?>
    <?php include 'includes/new-navbar.php'; ?>

    <section class="tp-fun-intro fade-in-up" style="animation-delay: 0.2s;">
        <h1 class="tp-intro-main"><?= htmlspecialchars($post['post_title']) ?></h1>
        <h2 class="tp-intro-sub">By: <?= htmlspecialchars($post['partner_name']) ?></h2>

        <div class="tp-post-meta">
            <span class="tp-meta-tag type-<?= strtolower(htmlspecialchars($post['post_type'])) ?>">
                <i class="fas fa-tag"></i> <?= htmlspecialchars($post['post_type']) ?>
            </span>
            <?php if ($post_date_formatted): ?>
            <span class="tp-meta-tag">
                <i class="fas fa-calendar-alt"></i> <?= $post_date_formatted ?>
            </span>
            <?php endif; ?>
        </div>

        <nav class="tp-intro-nav">
            <a href="index.php">Home</a> <i class="fas fa-chevron-right"></i>
            <a href="spotlight.php">Spotlight</a> <i class="fas fa-chevron-right"></i>
            <span><?= htmlspecialchars(substr($post['post_title'], 0, 30)) ?>...</span>
        </nav>
    </section>

    <main>
        <div class="spotlight-content-body">
            <?= $post['post_description'] ?>
        </div>

        <?php if (!empty($post['external_link'])): ?>
        <div class="tp-external-link-container">
            <a href="<?= htmlspecialchars($post['external_link']) ?>" class="btn-playful" target="_blank"
                rel="noopener noreferrer">
                Visit Our Partner <i class="fas fa-external-link-alt"></i>
            </a>
        </div>
        <?php endif; ?>

<?php if (!empty($post['document'])): ?>
<div class="document-cta-container">
    <a href="<?= htmlspecialchars($post['document']) ?>" class="btn-download" target="_blank" download>
        <i class="fas fa-file-download"></i> Read The Full Report
    </a>
</div>
<?php endif; ?>

        <?php if (!empty($files) && is_array($files)): ?>
        <div class="gallery-container">
            <h2 class="playful-section-title">Gallery & Downloads</h2>
            <div class="gallery-grid">

                <?php foreach ($files as $index => $file): ?>
                <?php $details = get_file_details($file); ?>

                <?php if ($details['is_image']): ?>
                <a href="#" class="gallery-card-base gallery-card" data-img-src="admin/<?= htmlspecialchars($file) ?>"
                    data-img-index="<?= $index ?>">
                    <div class="gallery-card-inner">
                        <img loading="lazy" src="admin/<?= htmlspecialchars($file) ?>" alt="Spotlight Gallery Image <?= $index + 1 ?>"
                            loading="lazy">
                    </div>
                </a>
                <?php else: ?>
                <a href="<?= htmlspecialchars($file) ?>"
                    class="gallery-card-base document-card <?= $details['color_class'] ?>" download>
                    <div class="document-card-inner">
                        <i class="fas <?= $details['icon'] ?>"></i>
                        <span class="file-name"><?= htmlspecialchars($details['filename']) ?></span>
                    </div>
                    <div class="document-download-bar">
                        Download File <i class="fas fa-download"></i>
                    </div>
                </a>
                <?php endif; ?>

                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($other_posts)): ?>
        <div class="related-posts-container">
            <div>
                <h2 class="playful-section-title">You Might Also Like</h2>
                <div class="related-grid">
                    <?php foreach ($other_posts as $other_post): ?>
                    <a href="spotlight-detail.php?id=<?= $other_post['id'] ?>" class="spotlight-card">
                        <div class="thumbnail-wrapper">
                            <img loading="lazy" src="admin/<?= htmlspecialchars($other_post['thumbnail_image']) ?>"
                                alt="<?= htmlspecialchars($other_post['post_title']) ?>" class="thumbnail"
                                loading="lazy">
                        </div>
                        <div class="card-content">
                            <div>
                                <h3><?= htmlspecialchars($other_post['post_title']) ?></h3>
                                <div class="description">
                                    <?= substr(strip_tags($other_post['post_description']), 0, 150) ?>...
                                </div>
                            </div>
                            <span class="read-more">Read More <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
        <?php endif; ?>
    </main>

    <?php include 'includes/footer.php'; ?>


    <div id="lightboxModal" class="lightbox-modal">
        <span class="lightbox-close">&times;</span>
        <img loading="lazy" class="lightbox-content" id="lightboxImage">
    </div>

    <script>
    // Lightbox Script
    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById("lightboxModal");
        const modalImg = document.getElementById("lightboxImage");
        const closeBtn = document.getElementsByClassName("lightbox-close")[0];

        const galleryCards = document.querySelectorAll(".gallery-card");

        galleryCards.forEach(card => {
            card.addEventListener("click", function(event) {
                event.preventDefault();
                modal.style.display = "flex";
                modalImg.src = this.getAttribute("data-img-src");
            });
        });

        closeBtn.addEventListener("click", function() {
            modal.style.display = "none";
        });
        modal.addEventListener("click", function(event) {
            S
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
        document.addEventListener('keydown', function(event) {
            s
            if (event.key === 'Escape' && modal.style.display === 'flex') {
                modal.style.display = 'none';
            }
        });
    });
    </sCRIPT>
</body>

</html>

