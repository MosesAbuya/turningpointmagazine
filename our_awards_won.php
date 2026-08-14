<?php
include('connection2.php');
$pdo = connect();
$stmt = $pdo->query("SELECT * FROM personal_awards_won ORDER BY date_awarded DESC");
$awards_won = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Our Awards - Turning Point Magazine</title>
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

    <link rel="stylesheet" href="includes/tp-navbar.css">
    <link rel="stylesheet" href="tp-design-system.css">
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

    /* --- MAIN CONTENT --- */
    main {
        position: relative;
        z-index: 2;
        /* Also stacks on top of the absolute background */
        background: #fff;
    }

    .awards-grid-wrapper {
        padding: 40px 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Section Title with Underline */
    .section-title {
        font-family: 'florania', sans-serif;
        font-size: 2.5rem;
        color: #008080;
        /* Teal */
        margin-bottom: 40px;
        position: relative;
        display: inline-block;
    }

    .section-title::after {
        content: "";
        position: absolute;
        left: -5%;
        bottom: 0px;
        height: 8px;
        /* Thicker line */
        width: 0;
        background-color: #E6007E;
        /* Pink */
        transform: rotate(-2deg);
        z-index: -1;
        animation: drawUnderline 0.8s ease-out 0.5s forwards;
    }

    /* Grid */
    .awards-grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
    }

    /* Card Styling */
    .award-won-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        /* To make footer sticky */
        transition: all 0.3s ease;
    }

    .award-won-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px -4px rgba(0, 0, 0, 0.15);
    }

    .award-image-wrapper {
        width: 100%;
        height: 220px;
        overflow: hidden;
    }

    .award-won-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .award-won-card:hover img {
        transform: scale(1.05);
    }

    .award-won-content {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        /* Pushes button to bottom */
    }

    .award-won-content h3 {
        font-family: 'florania', sans-serif;
        font-size: 1.8rem;
        margin-top: 0;
        margin-bottom: 10px;
        color: #333;
    }

    .award-won-content .description {
        color: #555;
        line-height: 1.6;
        flex-grow: 1;
        /* Fills space */
    }

    .award-won-content .award-date {
        font-weight: 700;
        color: #ff0000;
        font-size: 0.95rem;
        margin-top: 15px;
        margin-bottom: 15px;
    }

    .award-date i {
        margin-right: 5px;
    }

    .learn-more-btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #ff0000;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        font-weight: 700;
        transition: background-color 0.3s ease;
        align-self: flex-start;
    }

    .learn-more-btn:hover {
        background-color: #cc0000;
    }

    .learn-more-btn i {
        margin-left: 5px;
        transition: transform 0.3s ease;
    }

    .learn-more-btn:hover i {
        transform: translateX(4px);
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

        .section-title {
            font-size: 2.2rem;
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

        .awards-grid-container {
            grid-template-columns: 1fr;
            /* Stack on mobile */
        }

        .section-title {
            font-size: 2rem;
        }

        .award-won-content h3 {
            font-size: 1.6rem;
        }
    }
    </style>
</head>

<body>
    <?php include 'includes/preloader.php'; ?>
    <?php include 'includes/new-navbar.php';?>

    <section class="tp-fun-intro fade-in-up" style="animation-delay: 0.2s;">
        <h1 class="tp-intro-main">Our Achievements</h1>
        <h2 class="tp-intro-sub">Awards Won</h2>
        <nav class="tp-intro-nav">
            <a href="index.php">Home</a> <span><i class="fas fa-chevron-right"></i> <a href="awards.php">Awards</a>
                <i class="fas fa-chevron-right"></i> Our Achievements</span>
        </nav>
    </section>

    <main>
        <section class="awards-grid-wrapper fade-in-up" style="animation-delay: 0.2s;">
            <h2 class="section-title">Our Trophy Case</h2>

            <div class="awards-grid-container">
                <?php if (empty($awards_won)): ?>
                <p style="grid-column: 1 / -1; text-align: center; font-size: 1.2rem; color: #555;">We're working on
                    winning our first one! Check back soon.</p>
                <?php else: ?>
                <?php foreach ($awards_won as $index => $award): ?>
                <div class="award-won-card fade-in-up" style="animation-delay: <?= 0.2 + ($index * 0.1) ?>s;">
                    <div class="award-image-wrapper">
                        <img loading="lazy" src="admin/<?= htmlspecialchars($award['thumbnail_url']) ?>"
                            alt="<?= htmlspecialchars($award['title']) ?>">
                    </div>
                    <div class="award-won-content">
                        <h3><?= htmlspecialchars($award['title']) ?></h3>
                        <p class="description"><?= htmlspecialchars(substr($award['description'], 0, 100)) ?>...</p>
                        <p class="award-date"><i class="fas fa-calendar-alt"></i> <strong>Date:</strong>
                            <?= date('F Y', strtotime($award['date_awarded'])) ?></p>
                        <a href="personal_award_detail.php?id=<?= $award['id'] ?>" class="learn-more-btn">Learn More <i
                                class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php';?>
</body>

</html>

