<?php
include('connection2.php');
$pdo = connect();

$awards_per_page = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $awards_per_page;

$total_awards_stmt = $pdo->query("SELECT COUNT(*) FROM awards_to_apply WHERE status = 'Active'");
$total_awards = $total_awards_stmt->fetchColumn();
$total_pages = ceil($total_awards / $awards_per_page);

$stmt = $pdo->prepare("SELECT * FROM awards_to_apply WHERE status = 'Active' ORDER BY application_deadline DESC LIMIT :limit OFFSET :offset");
$stmt->bindParam(':limit', $awards_per_page, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$awards = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Awards - Turning Point Magazine</title>
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

    /* --- 3. Main Content Area --- */
    main {
        position: relative;
        z-index: 2;
        /* Also stacks on top of the absolute background */
        background: #fff;
    }

    .awards-container {
        padding: 40px 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Section Title with Underline */
    .section-title {
        font-family: 'florania', sans-serif;
        font-size: 2.5rem;
        color: #008080;
        margin-bottom: 30px;
        position: relative;
        display: inline-block;
    }

    .section-title::after {
        content: "";
        position: absolute;
        left: -5%;
        right: -5%;
        bottom: -8px;
        height: 16px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 20'%3E%3Cpath d='M5 15 Q 25 5 50 15 T 95 5' stroke='%23E6007E' stroke-width='5' fill='none'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-size: 100% 100%;
        opacity: 0;
        animation: fadeIn 0.8s ease-out 0.5s forwards;
        z-index: -1;
    }

    /* Award Card Styling */
    .award-card {
        display: flex;
        margin-bottom: 30px;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .award-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px -4px rgba(0, 0, 0, 0.15);
    }

    .award-image-wrapper {
        width: 40%;
        flex-shrink: 0;
        overflow: hidden;
    }

    .award-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .award-card:hover img {
        transform: scale(1.05);
    }

    .award-content {
        padding: 25px 30px;
        width: 60%;
        display: flex;
        flex-direction: column;
    }

    .award-content h3 {
        font-family: 'florania', sans-serif;
        font-size: 2.2rem;
        margin-top: 0;
        margin-bottom: 10px;
        color: #333;
    }

    .award-content .description {
        flex-grow: 1;
        color: #555;
        line-height: 1.6;
        font-size: 1.05rem;
    }

    .award-content .deadline {
        font-weight: 700;
        color: #ff0000;
        font-size: 1.1rem;
        margin-top: 15px;
    }

    .apply-now-btn {
        display: inline-block;
        margin-top: 20px;
        padding: 12px 25px;
        background-color: #ff0000;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        font-weight: 700;
        transition: background-color 0.3s ease;
        align-self: flex-start;
    }

    .apply-now-btn:hover {
        background-color: #cc0000;
    }

    /* --- OUR AWARDS CTA --- */
    .our-awards-cta {
        padding: 50px 20px;
        text-align: center;
        background-color: #f8f9fa;
        margin-top: 40px;
        border-radius: 8px;
    }

    .cta-title-main {
        font-family: 'florania', sans-serif;
        font-size: 2.8rem;
        font-weight: 700;
        color: #008080;
        margin-bottom: 10px;
        position: relative;
        display: inline-block;
    }

    .cta-title-main::after {
        content: "";
        position: absolute;
        left: -5%;
        right: -5%;
        bottom: -8px;
        height: 16px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 20'%3E%3Cpath d='M5 15 Q 25 5 50 15 T 95 5' stroke='%23FFD700' stroke-width='5' fill='none'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-size: 100% 100%;
        opacity: 0;
        animation: fadeIn 0.8s ease-out 0.7s forwards;
        z-index: -1;
    }

    .cta-title-playful {
        font-family: 'Caveat', cursive;
        color: #E6007E;
        font-size: 2.5rem;
        margin-top: -10px;
        margin-bottom: 20px;
        display: block;
    }

    .cta-button {
        display: inline-block;
        background-color: #ff0000;
        color: #fff;
        padding: 15px 30px;
        font-size: 1.1rem;
        font-weight: 700;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .cta-button:hover {
        background-color: #cc0000;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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

        /* Other styles */
        .award-card {
            flex-direction: column;
        }

        .award-image-wrapper {
            width: 100%;
            height: 250px;
        }

        .award-content {
            width: 100%;
        }

        .section-title {
            font-size: 2.2rem;
        }

        .cta-title-main {
            font-size: 2.2rem;
        }

        .cta-title-main::after,
        .section-title::after {
            height: 14px;
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

        /* Other styles */
        .award-content h3 {
            font-size: 1.8rem;
        }

        .section-title {
            font-size: 2rem;
        }

        .cta-title-main {
            font-size: 1.8rem;
        }

        .cta-title-playful {
            font-size: 2rem;
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
        <h1 class="tp-intro-main">Turning Point Magazine</h1>
        <h2 class="tp-intro-sub">Awards</h2>
        <nav class="tp-intro-nav">
            <a href="index.php">Home</a> <span><i class="fas fa-chevron-right"></i> Awards</span>
        </nav>
    </section>

    <main>
        <section class="awards-container fade-in-up" style="animation-delay: 0.4s;">
            <h2 class="section-title">Awards to Apply For</h2>

            <?php if (empty($awards)): ?>
            <p>No awards available at the moment.</p>
            <?php else: ?>
            <?php foreach ($awards as $index => $award): ?>
            <div class="award-card fade-in-up" style="animation-delay: <?= 0.5 + ($index * 0.1) ?>s;">
                <div class="award-image-wrapper">
                    <img loading="lazy" src="admin/<?= htmlspecialchars($award['image_url']) ?>"
                        alt="<?= htmlspecialchars($award['title']) ?>">
                </div>
                <div class="award-content">
                    <h3><?= htmlspecialchars($award['title']) ?></h3>
                    <p class="description"><?= htmlspecialchars($award['short_description']) ?></p>
                    <p class="deadline"><i class="fas fa-calendar-alt"></i> Deadline:
                        <?= date('F j, Y', strtotime($award['application_deadline'])) ?></p>
                    <a href="apply_award.php?id=<?= $award['id'] ?>" class="apply-now-btn">Apply Now <i
                            class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>

            <div class="our-awards-cta fade-in-up" style="animation-delay: 0.5s;">
                <h2 class="cta-title-main">Our Achievements</h2>
                <span class="cta-title-playful">See what we've won!</span>
                <a href="our_awards_won.php" class="cta-button">View Our Awards <i class="fas fa-trophy"></i></a>
            </div>

        </section>
    </main>

    <?php include 'includes/footer.php';?>
</body>

</html>
