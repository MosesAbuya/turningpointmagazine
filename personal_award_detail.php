<?php
include('connection2.php');
$pdo = connect();

$award_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$award_id) {
    header('Location: our_awards_won.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM personal_awards_won WHERE id = :id");
$stmt->execute(['id' => $award_id]);
$award = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$award) {
    header('Location: our_awards_won.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= htmlspecialchars($award['title']) ?> - Our Awards - Turning Point Magazine</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="apple-touch-icon" sizes="180x180" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>favicon-16x16.png">
    <link rel="manifest" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>site.webmanifest">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="includes/new-navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="global.css">


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
            width: 110%;
        }

        /* Overdraw slightly */
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

        /* Sits behind sticky nav */
        position: absolute;
        z-index: 1;
        top: 0;
        width: 100%;
        height: 150px;
        /* Fixed height for the background area */
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
        /* MODIFIED: Added box-shadow */

        /* All content-related styles removed */
    }

    /* --- 2. NEW "Fun Intro" Section --- */
    .tp-fun-intro {
        position: relative;
        z-index: 2;
        /* Sits on top of the background image */
        margin-top: 0;
        background: var(--brand-white);
        /* MODIFIED: Reverted to white background */
        padding: 100px 20px 60px 20px;
        /* MODIFIED: Adjusted padding to position content */
        text-align: center;
        border-bottom: 1px solid #eee;
    }

    .tp-intro-main {
        font-family: 'florania', sans-serif;
        font-size: 3.5rem;
        /* Big and bold */
        font-weight: 700;
        color: var(--brand-teal, #008080);
        margin: 0;
        line-height: 1.2;
    }

    .tp-intro-sub {
        font-family: 'Caveat', cursive;
        /* Playful font */
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


    /* --- MAIN CONTAINER --- */
    main {
        position: relative;
        z-index: 2;
        background: #fff;
    }

    .award-detail-container {
        padding: 0;
        max-width: 1000px;
        margin: 0 auto 40px auto;
        background: #fff;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.05);
        border-radius: 10px;
        overflow: hidden;
    }

    /* --- HEADER & BANNER --- */
    .award-detail-header img {
        width: 100%;
        height: 400px;
        object-fit: cover;
    }

    .award-detail-title {
        padding: 30px;
        text-align: center;
    }

    .award-detail-title h1 {
        font-family: 'florania', sans-serif;
        font-size: 3.5rem;
        margin-top: 0;
        margin-bottom: 20px;
        position: relative;
        display: inline-block;
        color: #333;
    }

    /* Tilted yellow underline */
    .award-detail-title h1::after {
        content: "";
        position: absolute;
        left: -5%;
        bottom: 0px;
        height: 10px;
        width: 0;
        background-color: #FFD700;
        opacity: 0.8;
        transform: rotate(-2deg);
        z-index: -1;
        animation: drawUnderline 0.8s ease-out 0.5s forwards;
    }

    .directory-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
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

    /* --- BODY CONTENT --- */
    .award-detail-body {
        padding: 0 30px 40px 30px;
    }

    .section-title {
        font-family: 'florania', sans-serif;
        font-size: 2.5rem;
        color: #008080;
        margin-bottom: 30px;
        margin-top: 40px;
        position: relative;
        display: inline-block;
    }

    /* Tilted pink underline */
    .section-title::after {
        content: "";
        position: absolute;
        left: -5%;
        bottom: 0px;
        height: 8px;
        width: 0;
        background-color: #E6007E;
        transform: rotate(-2deg);
        z-index: -1;
        animation: drawUnderline 0.8s ease-out 0.7s forwards;
    }

    .award-detail-body p {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #444;
    }

    /* --- GALLERY --- */
    .gallery-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .gallery-card {
        display: block;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        position: relative;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .gallery-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    }

    .gallery-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;
    }

    .gallery-card:hover img {
        transform: scale(1.05);
    }

    /* --- LIGHTBOX --- */
    .lightbox-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.9);
        animation: fadeIn 0.3s;
        align-items: center;
        justify-content: center;
    }

    .lightbox-content {
        margin: auto;
        display: block;
        max-width: 90%;
        max-height: 90%;
        border-radius: 8px;
    }

    .lightbox-close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
        cursor: pointer;
    }

    .lightbox-close:hover {
        color: #bbb;
    }

    /* --- RESPONSIVE --- */
    @media (max-width: 768px) {
        .breadcrumb-container {
            height: 75px;
        }

        .tp-fun-intro {
            margin-top: 0;
            padding: 80px 20px 40px 20px;
            /* MODIFIED: Adjusted padding */
        }

        .tp-intro-main {
            font-size: 2.8rem;
        }

        .tp-intro-sub {
            font-size: 3rem;
        }

        .award-detail-header img {
            height: 300px;
        }

        .award-detail-title h1 {
            font-size: 2.8rem;
        }

        .section-title {
            font-size: 2.2rem;
        }

        .gallery-container {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }
    }

    @media (max-width: 480px) {
        .breadcrumb-container {
            height: 75px;
        }

        .tp-fun-intro {
            margin-top: 0;
            padding: 60px 20px 30px 20px;
            /* MODIFIED: Adjusted padding */
        }

        .tp-intro-main {
            font-size: 2.2rem;
        }

        .tp-intro-sub {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .award-detail-header img {
            height: 200px;
        }

        .award-detail-title h1 {
            font-size: 2.2rem;
        }

        .section-title {
            font-size: 2rem;
        }

        .award-detail-body {
            padding: 0 20px 30px 20px;
        }

        .gallery-container {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        }
    }
    </style>
</head>

<body>
    <?php include 'includes/preloader.php'; ?>
    <?php include 'includes/new-navbar.php';?>

    <div class="breadcrumb-container fade-in-up">
    </div>

    <section class="tp-fun-intro fade-in-up" style="animation-delay: 0.2s;">
        <h1 class="tp-intro-main">Award Detail</h1>
        <h2 class="tp-intro-sub"><?= htmlspecialchars($award['title']) ?></h2>
        <nav class="tp-intro-nav">
            <a href="index.php">Home</a> <span><i class="fas fa-chevron-right"></i> <a
                    href="our_awards_won.php">Our Awards</a> <i class="fas fa-chevron-right"></i> Details</span>
        </nav>
    </section>

    <main class="award-detail-container fade-in-up" style="animation-delay: 0.2s;">
        <div class="award-detail-header">
            <img loading="lazy" src="admin/<?= htmlspecialchars($award['thumbnail_url']) ?>"
                alt="<?= htmlspecialchars($award['title']) ?>">

            <div class="award-detail-title">
                <h1><?= htmlspecialchars($award['title']) ?></h1>
                <div class="directory-meta">
                    <div class="meta-tag"><i class="fas fa-calendar-alt"></i>
                        <?= date('F j, Y', strtotime($award['date_awarded'])) ?></div>
                    <?php if (!empty($award['category'])): ?>
                    <div class="meta-tag"><i class="fas fa-tag"></i> <?= htmlspecialchars($award['category']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="award-detail-body">
            <div class="content-section fade-in-up" style="animation-delay: 0.3s;">
                <h2 class="section-title">Details</h2>
                <p><?= nl2br(htmlspecialchars($award['description'])) ?></p>
            </div>

            <?php $gallery = json_decode($award['gallery_images'] ?? '[]', true); ?>
            <?php if (!empty($gallery)): ?>
            <div class="content-section fade-in-up" style="animation-delay: 0.4s;">
                <h2 class="section-title">Gallery</h2>
                <div class="gallery-container">
                    <?php foreach ($gallery as $image): ?>
                    <a href="#" class="gallery-card" data-img-src="admin/<?= htmlspecialchars($image) ?>">
                        <img loading="lazy" src="admin/<?= htmlspecialchars($image) ?>" alt="Gallery image">
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <div id="lightboxModal" class="lightbox-modal">
        <span class="lightbox-close">&times;</span>
        <img loading="lazy" class="lightbox-content" id="lightboxImage">
    </div>

    <?php include 'includes/footer.php';?>

    <script>
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
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && modal.style.display === 'flex') {
                modal.style.display = 'none';
            }
        });
    });
    </script>
</body>

</html>
