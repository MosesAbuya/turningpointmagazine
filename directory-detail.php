<?php
include('connection2.php');
$pdo = connect();

$directory_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$directory_id) {
    header('Location: directories.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM directories WHERE id = :id");
$stmt->execute(['id' => $directory_id]);
$directory = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$directory) {
    header('Location: directories.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= htmlspecialchars($directory['name']) ?> - Turning Point Magazine</title>
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

    body {
        padding-top: 0 !important;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
        }
    }

    .fade-in-up {
        opacity: 0;
        animation: fadeInUp 0.6s ease-out forwards;
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

    .directory-detail-container {
        max-width: 900px;
        margin: 40px auto;
        padding: 20px;
    }
    </style>
</head>

<body>
    <?php include 'includes/preloader.php'; ?>
    <?php include 'includes/new-navbar.php';?>

    <div class="breadcrumb-container fade-in-up"></div>

    <section class="tp-fun-intro fade-in-up" style="animation-delay: 0.2s;">
        <h1 class="tp-intro-main"><?= htmlspecialchars($directory['name']) ?></h1>
        <h2 class="tp-intro-sub">Directory Details</h2>
        <nav class="tp-intro-nav">
            <a href="index.php">Home</a> <i class="fas fa-chevron-right"></i>
            <a href="directories.php">Directories</a> <i class="fas fa-chevron-right"></i>
            <span><?= htmlspecialchars($directory['name']) ?></span>
        </nav>
    </section>

    <main>
        <div class="directory-detail-container">
            <p><?= htmlspecialchars($directory['description']) ?></p>
            <!-- Add more details as needed -->
        </div>
    </main>

    <?php include 'includes/footer.php';?>
</body>

</html>