<?php


session_start();

require_once ('shop/inc/Database.php');
require_once ('shop/inc/dynamic_elements.php');


// create instance of Database class
$database = new Database();

if (isset($_POST['add'])){
    /// print_r($_POST['product_id']);
    if(isset($_SESSION['cart'])){

        if(in_array($_POST['product_id'], array_keys($_SESSION['cart']))){
            $_SESSION['cart'][$_POST['product_id']] += 1;
            header("location: shop.php");
        }else{
            // Create new session variable
            $_SESSION['cart'][$_POST['product_id']] = 1;
            // print_r($_SESSION['cart']);
            header("location: shop.php");
        }

    }else{
        // Create new session variable
        $_SESSION['cart'][$_POST['product_id']] = 1;
        // print_r($_SESSION['cart']);
        header("location: shop.php");
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Turning Point Magazine - Africa's Premier Source for News, Culture, and Innovation</title>
    <meta charset="UTF-8">
    <meta name="description"
        content="Turning Point Magazine is a digital platform dedicated to amplifying grassroots voices and celebrating stories of positive change across Africa. Join us in shaping a brighter future through inclusive, transformative content." />

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">

    <!-- Preconnect to Fonts and External Resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Favicon and App Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>favicon-16x16.png">
    <link rel="manifest" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>site.webmanifest">
    <script src="https://unpkg.com/smooth-scroll@16.1.3/dist/smooth-scroll.min.js"></script>

    <link rel="stylesheet" href="nav-2.css">
    <link rel="stylesheet" href="includes/navbar.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="latest.css">
    <link rel="stylesheet" href="global.css">
    <link rel="stylesheet" href="tp-design-system.css">
    <link rel="stylesheet" href="collection.css">
    <link rel="stylesheet" href="patners.css">
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="subscribe.css">
    <link rel="stylesheet" href="counter.css">
    <link rel="stylesheet" href="promo.css">
    <link rel="stylesheet" href="feedback.css">
    <link rel="stylesheet" href="patners.css">
    <link rel="stylesheet" href="button.css">
    <link rel="stylesheet" href="i-blog.css">
    <link rel="stylesheet" href="patner.css">
    <link rel="stylesheet" href="subscribes.css">
    <link rel="stylesheet" href="next-release.css">
    <link rel="stylesheet" href="next-issue.css">
    <style>
    .p-s,
    .p-1 {
        font-weight: bold;
    }

    .scroll-down-icon {
        top: 75dvh;
        transform: translateX(-50%);
        animation: bounce 1.5s infinite;
        /* Infinite bounce animation */
        font-size: 3.5rem;
        /* Adjust icon size */

        cursor: pointer;

        /* Indicate it's interactive */
        a {
            text-decoration: none;
            color: #ff0000;
            /* Icon color */
        }
    }

    .counts {

        animation: bounc .1s infinite;
        /* Infinite bounce animation */
    }

    @keyframes bounc {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0;
        }
    }

    /* Bounce Animation */
    @keyframes bounce {

        0%,
        100% {
            transform: translateY(0) translateX(-50%);
        }

        50% {
            transform: translateY(10px) translateX(-50%);
        }
    }

    @media screen and (max-width: 768px) {
        .scroll-down-icon {
            display: none;
        }

        #b-h1 {
            display: none;
        }
    }

    select {
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-family: "Century Gothic", 'Lato', sans-serif;
        width: 300px;
    }

    option {
        padding: 0;
    }

    form {
        width: 100%;
    }

    .form-item {
        width: 90%;
    }

    #in-1,
    #in-2,
    #in-3 {

        width: 100%;
    }

    #none {
        color: transparent;
        margin-top: -100px;
        margin-bottom: 0;
    }

    .sub-text {
        width: 700px;

    }

    .blog-banner {
        background-color: rgba(0, 0, 0, 0.489);
    }

    h1 {
        font-family: 'florania';
        font-size: 3rem;
    }

    #nn-navbar-s {
        opacity: 0;
        z-index: 10000;
    }

    @media (max-width: 768px) {
        .sub-text {
            width: 100%;
            margin: 10px;
            padding: 10px;
        }

        .card-container-inner {
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
        }
    }
    </style>

    <link rel="stylesheet" href="dflip/css/dflip.min.css" type="text/css"> <!-- Minified version -->
    <link rel="stylesheet" href="dflip/css/themify-icons.min.css" type="text/css"> <!-- Minified version -->

    <!-- External Libraries (use CDN for better performance) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <!-- Preload your custom PHP includes -->
    <?php include 'includes/preloader.php'; ?>

</head>
<!--Start of Tawk.to Script-->

<!--End of Tawk.to Script-->

<body>
    <main class="et-main">
        <section class="et-hero-tabs">
            <div class="tab-1">
                <h1 id="b-h1">Turning Point</h1>
                <h3 class="b-h3">Transforming Everyday</h3>
            </div>
            <div class="scroll-down-icon">
                <a href="#latest"> <i class="bi bi-chevron-down"></i></a>
            </div>
            <?php include 'includes/nav2.php' ?>
        </section>
        <?php include 'includes/nav-4.php' ?>

        <section class="et-slide" id="latest">


            <div class="container " id="lates">

                <div class="row">
                    <div class="col-xs-12">

                        <h1>Latest publication</h1>
                        <h1 id="none">...Latest in our list of publications</h1>

                    </div>
                    <div class="col-xs-12" style="padding-bottom:30px">
                        <!--Normal FLipbook-->
                        <div class="_df_book" height="500" webgl="true" backgroundcolor="transparent"
                            source="assets/Mag2.pdf" id="df_manual_book">
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <?php

$pdo = connect();

// Fetch all editions
try {
    $stmt = $pdo->query("SELECT id, front_page_image, edition_name, price  FROM editions");
    $editions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching editions: " . $e->getMessage());
}
?>

        <section class="tp-collection" id="tp-collection">
            <h1 style="text-align: center;">Our Magazine Library</h1>
            <div class="tp-collection-grid">
                <?php foreach ($editions as $edition): ?>
                <div class="tp-book-card">
                    <a href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>edition/<?= generate_slug($edition['edition_name']) ?>" target="_blank"
                        class="tp-book-link">
                        <div class="tp-book-cover">
                            <img loading="lazy" src="admin/<?= htmlspecialchars($edition['front_page_image']) ?>"
                                alt="<?= htmlspecialchars($edition['edition_name']) ?>" class="tp-book-image">
                        </div>
                        <div class="tp-book-info">
                            <h3 class="tp-edition-title">
                                <?= htmlspecialchars($edition['edition_name']) ?> <svg
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    viewBox="0 0 16 16" style="vertical-align: middle; margin-left: 4px;">
                                    <path fill-rule="evenodd"
                                        d="M1 8a.5.5 0 0 1 .5-.5h11.793l-4.147-4.146a.5.5 0 1 1 .708-.708l5 5a.5.5 0 0 1 0 .708l-5 5a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                                </svg>
                            </h3>
                        </div>
                    </a>
                    <div class="tp-book-actions">
                        <a href="shop.php?edition_id=<?= htmlspecialchars($edition['id']) ?>" class="tp-cart-button">
                            <div class="tp-cart-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <span class="tp-cart-text">Order A Physical Copy</span>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <!-- <section class="next-release-container"
            style="display: block; align-items: center; width:100%; margin-top: 50px; left: 0;">
            <div class="next-release">
                <div class="next-left">
                    <h1>
                        <span>Next Release</span> International Women’s Day Edition
                    </h1>
                </div>
                <div class="next-right">
                    <img loading="lazy" src="includes/nextt.jpg" class="next-release-image">
                </div>
            </div>
            <button>
                <a href="next-issue.php">Read More</a>
            </button>
            <a href="next-issue.php"
                style="left: 0; display: block; align-items: center; width:100%; padding: 10px; justify-content: flex-start;">
                <img loading="lazy" style="width: 95%; height: auto;" src="assets/nps.png" alt="next-issue">
            </a>
        </section>

        <div class="buy">
            <a href="registration.php">
                <img loading="lazy" src="assets/reg.png"/>
            </a>
        </div> -->

        <div class="et-slidess">

            <div class="counter-wrapper">
                <div class="counter">
                    <div class="c-out">
                        <h1 class="count" data-target="100">0</h1>
                        <p class="p-1">Advertisements</p>
                    </div>
                </div>

                <div class="counter c-a">
                    <h1 class="count" data-target="300">0</h1>
                    <p class="p-1">Visitors</p>
                </div>

                <div class="counter ">
                    <h1 class="count" data-target="500">0</h1>
                    <p class="p-1">Subscriptions</p>
                </div>
            </div>



        </div>

        <section class=" et-slide" id="subscribe">
            <h1>Don't miss out</h1>
            <p class="f-head">Subscribe now to get regular updates whenever we have a new publication</p>
            <div class="form form-sm">
                <img loading="lazy" class="img-f" src="assets/s1s1.webp" alt="">
                <form id="subscribeForm" name="sub" method="POST">
                    <div class="form-item">
                        <input type="text" placeholder="First Name" class="input" id="in-1" name="f-name" required>
                    </div>
                    <div class="form-item">
                        <input type="text" placeholder="Last Name" class="input" id="in-3" name="l-name" required>
                    </div>
                    <div class="form-item">
                        <input type="text" placeholder="Email" class="input" id="in-2" name="email" required>
                    </div>
                    <div class="form-item select-wrapper">
                        <select class="input" name="gender" required>
                            <option value="" disabled selected>Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="female">Others</option>
                            <option value="other">I prefer not to say</option>
                        </select>
                    </div>
                    <div class="form-item select-wrapper">
                        <select class="input" name="age" required>
                            <option value="" disabled selected>Age</option>
                            <option value="15-24">15-24</option>
                            <option value="25-30">25-34</option>
                            <option value="31-40">35-44</option>
                            <option value="45-above">45 and above</option>
                            <option value="other">Other</option>

                        </select>
                    </div>
                    <div class="form-item select-wrapper">
                        <select class="input" name="address" required>
                            <option value="" disabled selected>Select a country</option>
                        </select>
                    </div>

                    <button type="submit" class="btn">
                        <span class="btn-text">Subscribe</span>
                        <span class="btn-shine"></span>
                    </button>
                    <div id="subscribe-response" style="width: 300px"></div>
                </form>
            </div>
            <p class="p-s sub-text">
                <span class="">By subscribing to our website, you agree to our <a href="#">Terms and Conditions</a>.
                    Your personal information will be used in accordance with our <a href="#">Privacy Policy</a>. You
                    also acknowledge that we are a <a href="about.php#licencing">licensed Data Controller</a>,
                    authorized by the Office of the Data Protection Commissioner, and that we may collect and analyze
                    <span>your usage data to improve our services.</span></span>
            </p>
        </section>

        <section class="subscribe-section">
            <div class="subscribe-section-item" id="item-1">
                <div class="subscribe-title">
                    <h2>Advertise</h2>
                </div>
                <div class="subscribe-row">
                    <div class="subscribe-img"><img loading="lazy" class="img-f" src="assets/c1.webp" class="image-full" alt=""></div>
                    <div class="subscribe-text">
                        <p>Reach your target audience with our magazine. Our publication offers a unique opportunity to
                            showcase your brand and connect with potential customers</p>
                    </div>
                </div>
                <a href="contact.php">Reach Out Now!</a>

            </div>

            <div class="subscribe-section-item" id="item-2">
                <div class="subscribe-title">
                    <h2>Contribute</h2>
                </div>
                <div class="subscribe-row">
                    <div class="subscribe-img"><img loading="lazy" class="img-f" src="assets/c2.webp" class="image-full" alt=""></div>
                    <div class="subscribe-text">
                        <p>Join our community and share your unique experiences! Contribute your photos, stories, and
                            insights to be featured in our magazine.</p>
                    </div>
                </div>

                <a href="story.php">Contribute Now!</a>

            </div>


        </section>

        <section class="et-slide" id="patners">
            <h1>Latest Events</h1>
            <?php
require_once 'connection2.php';
$pdo = connect();
$stmt = $pdo->query("SELECT * FROM blog");
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
closeConnection($pdo);
?>

            <div class="i-blog-container">
                <?php $i = 1; foreach($blogs as $blog): 
        // Create slug from title for IDs
        $slug = strtolower(preg_replace('/\s+/', '-', $blog['title']));
        $slug = preg_replace('/[^A-Za-z0-9\-]/', '', $slug);
        $slug = preg_replace('/-+/', '-', $slug);

        // Get the first other photo
        $otherPhotos = json_decode($blog['other_photos'], true);
        $firstOtherPhoto = !empty($otherPhotos) ? $otherPhotos[0] : '';
    ?>
                <div class="i-blog">
                    <a href="blog.php#<?= $slug ?>">
                        <div class="i-blog-item">
                            <?php if($i == 2): ?>
                            <div class="blog-banner" id="ban-<?= $i ?>"
                                style="background-image: url('admin/<?= $firstOtherPhoto ?>')">
                                <?php else: ?>
                                <div class="blog-banner" id="ban-<?= $i ?>"
                                    style="background-image: url('admin/<?= $blog['top_photo'] ?>')">
                                    <?php endif; ?>
                                    <div class="blog-banner-items">
                                        <div class="blog-title">
                                            <p><?= htmlspecialchars($blog['title']) ?></p>
                                        </div>
                                        <div class="read">
                                            <a href="blog.php#<?= $slug ?>">
                                                <button>
                                                    <p>Read More <i class="fa fa-arrow-right"></i>
                                                    </p>
                                                </button>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                                <div class="blog-row">

                                    <div class="blog-date">
                                        <p><?= date('F j, Y', strtotime($blog['date'])) ?></p>
                                    </div>
                                </div>


                            </div>
                    </a>

                </div>
                <?php $i++; endforeach; ?>
            </div>



            <div class="container">
                <h1>Our Partners</h1>

                <!-- Strategic Partners -->
                <div class="partner-category">
                    <h2 class="partner-type-title">Strategic Partners</h2>
                    <div class="partners-grid">
                        <div class="partner-card">
                            <a href="https://impactalentconsulting.com/" target="_blank">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/l4.png" alt="Impact Talent Consulting" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="#">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/l5.png" alt="Partner Logo" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="#">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/l8.png" alt="Partner Logo" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="https://topwellnessafrica.co.ke/" target="_blank">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/l1.png" alt="Top Wellness Africa" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="https://jitoleegoodfriendsfoundation.org/" target="_blank">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/l7.png" alt="Jitolee Good Friends Foundation" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Regional Partners -->
                <div class="partner-category">
                    <h2 class="partner-type-title">Regional Partners</h2>
                    <div class="partners-grid">
                        <div class="partner-card">
                            <a href="#">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/pn_1.png" alt="Partner Logo" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="#">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/pn_2.png" alt="Partner Logo" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="#">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/pn_3.png" alt="Partner Logo" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="#">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/pn_4.png" alt="Partner Logo" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="#">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/pn_5.png" alt="Partner Logo" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="#">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/pn_6.png" alt="Partner Logo" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="#">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/pn_7.png" alt="Partner Logo" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="#">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/pn_8.png" alt="Partner Logo" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="#">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/pn_9.png" alt="Partner Logo" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="#">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/pn_10.png" alt="Partner Logo" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="#">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/pn_11.webp" alt="Partner Logo" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="#">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/pn_12.png" alt="Partner Logo" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="#">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/pn_13.png" alt="Partner Logo" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="#">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/pn_14.png" alt="Partner Logo" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="#">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/pn_15.png" alt="Partner Logo" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="#">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/pn_16.png" alt="Partner Logo" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="#">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/pn_17.png" alt="Partner Logo" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="#">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/pn_18.png" alt="Partner Logo" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="#">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/pn_19.png" alt="Partner Logo" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="#">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/pn_20.png" alt="Partner Logo" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Media Partners -->
                <div class="partner-category">
                    <h2 class="partner-type-title">Media Partners</h2>
                    <div class="partners-grid">
                        <div class="partner-card">
                            <a href="https://malshemedia.com" target="_blank">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/l2.png" alt="Malshe Media" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="partner-card">
                            <a href="#">
                                <div class="partner-logo-container">
                                    <img loading="lazy" src="assets/l3.png" alt="Partner Logo" class="partner-logo">
                                </div>
                                <div class="partner-overlay">
                                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <section class="et-slide" id="feedback">
            <h1>Feedback</h1>
            <p>Have an opinion? Let us know! Your feedback will help us make our website even better.</p>
            <div class="iform" id="iform">
                <form id="feedbackForm" class="feedback-form" name="feedback" method="POST">
                    <textarea placeholder="Share your ideas here" rows="20" name="comments" id="comments" cols="40"
                        class="input-n input-feed" autocomplete="off" role="textbox" aria-autocomplete="list"
                        aria-haspopup="true"></textarea>
                    <button type="submit" class="btn btn-feed"><span class="btn-text">Submit</span>
                        <span class="btn-shine"></span></button>
                    <div id="feedback-response" style="margin-top: 0px;"></div>
                </form>

            </div>
        </section>
        <div id="back-top">
            <a title="Go to Top" href="#latest"> <i class="fas fa-level-up-alt"></i></a>
        </div>
        <?php include 'includes/footer.php' ?>
    </main>


    <script>
    window.addEventListener("scroll", function() {
        var e = document.querySelector(".et-hero-tabs-container"),
            t = document.querySelector(".et-hero-tabs"),
            t = t.offsetTop + t.offsetHeight;
        window.scrollY >= t ? e.classList.add("sticky") : e.classList.remove("sticky")
    }), document.addEventListener("DOMContentLoaded", function() {
        var e = document.getElementById("menu-icon"),
            t = document.getElementById("nav-links");
        e.addEventListener("click", function() {
            t.classList.toggle("show")
        }), window.addEventListener("resize", function() {
            768 <= window.innerWidth ? t.classList.add("show") : t.classList.contains("show") || t
                .classList
                .remove("show")
        })
    });
    const sub_active = document.getElementById("sub_class");

    function onClick() {
        sub_active.classList.add("active")
    }
    sub_active.addEventListener("click", onClick);
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#feedbackForm').submit(function(e) {
            e.preventDefault(); // Prevent form from submitting the normal way

            var formData = $(this).serialize(); // Serialize form data

            // Send form data via AJAX
            $.ajax({
                type: "POST",
                url: "feedback.php",
                data: formData,
                success: function(response) {
                    $('#feedback-response').html('<p style="color: green;">' +
                        response + '</p>');
                    $('#feedbackForm')[0].reset(); // Clear the form
                },
                error: function() {
                    $('#feedback-response').html(
                        '<p style="color: red;">An error occurred. Please try again.</p>'
                    );
                }
            });
        });
    });
    </script>
    <script type="text/javascript">
    var Tawk_API = Tawk_API || {},
        Tawk_LoadStart = new Date();
    (function() {
        var s1 = document.createElement("script"),
            s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/674ee80f2480f5b4f5a713d3/1ie63kv61';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
    </script>
    <script>
    $(document).ready(function() {
        // Function to animate the counter
        function animateCounter() {
            $('.count').each(function() {
                const $this = $(this);
                const target = +$this.data('target'); // Get the target number from data-target
                const increment = Math.ceil(target / 100); // Set the increment value
                let current = 0;

                // Update the number at regular intervals
                const interval = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        $this.text(`${target}+`); // Add the "+" if needed
                        clearInterval(interval); // Stop the interval when the target is reached
                    } else {
                        $this.text(`${current}+`);
                    }
                }, 30); // Update every 30ms
            });
        }

        // Trigger the animation when the counters are in view
        function isInViewport(element) {
            const elementTop = $(element).offset().top;
            const elementBottom = elementTop + $(element).outerHeight();
            const viewportTop = $(window).scrollTop();
            const viewportBottom = viewportTop + $(window).height();

            return elementBottom > viewportTop && elementTop < viewportBottom;
        }

        // Check visibility on scroll
        $(window).on('scroll', function() {
            $('.counter-wrapper').each(function() {
                if (isInViewport(this)) {
                    animateCounter();
                    $(window).off('scroll'); // Ensure it only triggers once
                }
            });
        });

        // Trigger animation if counters are already in view on page load
        $(window).trigger('scroll');
    });
    </script>

    <script>
    $(document).ready(function() {
        $('#subscribeForm').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            var formData = $(this).serialize(); // Serialize form data

            // Send form data via AJAX
            $.ajax({
                type: "POST",
                url: "subscribe.php",
                data: formData,
                success: function(response) {
                    $('#subscribe-response').html('<p style="color: green;">' +
                        response + '</p>');
                    $('#subscribeForm')[0].reset(); // Clear the form
                },
                error: function() {
                    $('#subscribe-response').html(
                        '<p style="color: red;">An error occurred. Please try again.</p>'
                    );
                }
            });
        });
    });
    </script>
    <script>
    const countrySelect = document.querySelector('select[name="address"]');
    const countries = new Intl.DisplayNames(['en'], {
        type: 'region'
    });

    const countryCodes = [
        "AF", "AL", "DZ", "AD", "AO", "AG", "AR", "AM", "AU", "AT", "AZ", "BS", "BH", "BD", "BB", "BY", "BE", "BZ",
        "BJ", "BT",
        "BO", "BA", "BW", "BR", "BN", "BG", "BF", "BI", "CV", "KH", "CM", "CA", "CF", "TD", "CL", "CN", "CO", "KM",
        "CG", "CR",
        "HR", "CU", "CY", "CZ", "CD", "DK", "DJ", "DM", "DO", "EC", "EG", "SV", "GQ", "ER", "EE", "SZ", "ET", "FJ",
        "FI", "FR",
        "GA", "GM", "GE", "DE", "GH", "GR", "GD", "GT", "GN", "GW", "GY", "HT", "HN", "HU", "IS", "IN", "ID", "IR",
        "IQ", "IE",
        "IL", "IT", "JM", "JP", "JO", "KZ", "KE", "KI", "KP", "KR", "XK", "KW", "KG", "LA", "LV", "LB", "LS", "LR",
        "LY", "LI",
        "LT", "LU", "MG", "MW", "MY", "MV", "ML", "MT", "MH", "MR", "MU", "MX", "FM", "MD", "MC", "MN", "ME", "MA",
        "MZ", "MM",
        "NA", "NR", "NP", "NL", "NZ", "NI", "NE", "NG", "MK", "NO", "OM", "PK", "PW", "PS", "PA", "PG", "PY", "PE",
        "PH", "PL",
        "PT", "QA", "RO", "RU", "RW", "KN", "LC", "VC", "WS", "SM", "ST", "SA", "SN", "RS", "SC", "SL", "SG", "SK",
        "SI", "SB",
        "SO", "ZA", "SS", "ES", "LK", "SD", "SR", "SE", "CH", "SY", "TJ", "TZ", "TH", "TL", "TG", "TO", "TT", "TN",
        "TR", "TM",
        "TV", "UG", "UA", "AE", "GB", "US", "UY", "UZ", "VU", "VA", "VE", "VN", "YE", "ZM", "ZW"
    ];

    countryCodes.forEach(code => {
        const option = document.createElement('option');
        const countryName = countries.of(code); // Get full country name
        option.value = countryName; // Set the value to the country name
        option.textContent = countryName; // Set the display text to the country name
        countrySelect.appendChild(option);
    });
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const navbar = document.getElementById("nn-navbar");
        const stickyNavbar = document.getElementById("nn-navbar-s");

        // Function to check screen width and add .mobile class
        function checkScreenWidth() {
            if (window.innerWidth <= 768) {
                stickyNavbar.classList.add("mobile");
            } else {
                stickyNavbar.classList.remove("mobile");
            }
        }

        // Run the check on load
        checkScreenWidth();

        // Create an Intersection Observer for sticky functionality
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) {
                    // When the navbar disappears from the viewport
                    stickyNavbar.classList.add("sticky"); // Add sticky class
                } else {
                    // When the navbar comes back into the viewport
                    stickyNavbar.classList.remove("sticky"); // Remove sticky class
                }
            });
        }, {
            threshold: 0 // Trigger when the navbar is fully out of view
        });

        // Start observing the navbar
        observer.observe(navbar);

        // Recheck screen width on window resize
        window.addEventListener("resize", checkScreenWidth);
    });
    </script>

    <script>
    function toggleMenu() {
        const menu = document.querySelector('.n-navbar-menu');
        menu.classList.toggle('active');
    }
    </script>
    <script>
    function toggleMenu() {
        const menu = document.getElementById("nn-menu");
        menu.classList.toggle("active");
    }
    </script>

    <script>
    // Select all the links
    const links = document.querySelectorAll('.n-dropdown-menu li a');

    // Add a click event listener to each link
    links.forEach(link => {
        link.addEventListener('click', () => {
            // Remove 'active' class from all links
            links.forEach(item => item.classList.remove('active'));

            // Add 'active' class to the clicked link
            link.classList.add('active');
        });
    });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="includes/index.min.js"></script>
    <script src="https://kit.fontawesome.com/f65faecb5f.js" crossorigin="anonymous" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer></script>
    <script src="dflip/js/libs/jquery.min.js" type="text/javascript"></script>
    <script src="dflip/js/dflip.min.js" type="text/javascript"></script>
</body>

</html>
