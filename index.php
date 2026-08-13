<?php
session_start();

require_once ('shop/inc/Database.php');
require_once ('shop/inc/dynamic_elements.php');
include('connection2.php');

// create instance of Database class
$database = new Database();

if (isset($_POST['add'])){
  // ... (Your cart logic remains untouched) ...
}

// --- PRE-LOAD ALL DATA ---

$pdo = connect();

// 1. Fetch Editions
try {
  $stmt = $pdo->query("SELECT id, front_page_image, edition_name, price FROM editions ORDER BY id DESC");
  $editions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Error fetching editions: " . $e->getMessage());
}

// 2. Fetch Latest 3 Blogs/Events
try {
  $stmt = $pdo->query("SELECT * FROM blog ORDER BY id DESC LIMIT 3");
  $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Error fetching blogs: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Turning Point Magazine - Africa's News & Culture</title>

    <meta charset="UTF-8">

    <meta name="description"
        content="Turning Point Magazine is a digital platform dedicated to amplifying grassroots voices and celebrating stories of positive change across Africa." />

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">

    <link rel="canonical" href="https://www.turningpointmagazine.africa/">


    <link rel="apple-touch-icon" sizes="180x180" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>apple-touch-icon.png">

    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">

    <link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>favicon-16x16.png">

    <link rel="manifest" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>site.webmanifest">


    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">


    <link rel="stylesheet" href="includes/new-navbar.css">

    <link rel="stylesheet" href="global.css">


    <link rel="stylesheet" href="dflip/css/dflip.min.css" type="text/css">

    <link rel="stylesheet" href="dflip/css/themify-icons.min.css" type="text/css">

    <style>
    /* ---
  START: Master Style Block for index.php
  --- */

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

    body {
        padding-top: 0 !important;
        font-family: 'Poppins', sans-serif;
        background-color: #fff;
    }

    /* --- 2. ANIMATIONS & HELPERS --- */
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
        animation: fadeInUp 0.8s ease-out forwards;
    }

    /* Global Card Hover */
    .tp-book-card,
    .counter-card,
    .i-blog-card,
    .partner-logo-card {
        transition: all 0.3s ease;
    }

    .tp-book-card:hover,
    .counter-card:hover,
    .i-blog-card:hover,
    .partner-logo-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    /* Global Button Hover */
    .btn {
        background: var(--brand-red);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .btn:hover {
        background: #cc0000;
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .btn-text {
        font-family: 'Poppins', sans-serif;
    }

    .btn-shine {
        display: none;
    }

    /* Hide old shine */


    /* Page Section Wrapper */
    .page-section {
        padding: 80px 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-section.bg-light {
        background: var(--bg-off-white);
        max-width: none;
        padding: 80px 20px;
    }

    .page-section.bg-light>div {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* No-padding sections for full-width CTAs */
    .page-section-full {
        width: 100%;
        padding: 0;
        margin: 80px 0;
    }


    /* Standardized Section Title */
    .section-title {
        text-align: center;
        font-family: 'florania', sans-serif;
        font-size: 2.8rem;
        color: var(--brand-teal);
        margin-bottom: 60px;
        position: relative;
        display: block;
    }

    .section-title::after {
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

    /* --- 3. HERO & INTRO SECTION (REVISED) --- */
    .hero-background-layer {
        /*background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/breadcrumbs/bread.jpeg');*/
        background-size: cover;
        background-position: center;
        position: absolute;
        z-index: 1;
        top: 0;
        width: 100%;
        height: 100vh;
        background-attachment: scroll;
        /* Fix for mobile */
    }

    .tp-fun-intro {
        position: relative;
        z-index: 2;
        padding: 60px 20px;
        text-align: center;
        height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: #fff;
        margin-top: -50px;
    }

    .tp-intro-main {
        font-family: 'florania', sans-serif;
        font-size: 5rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
        line-height: 1.1;
        text-shadow: 0 3px 10px rgba(0, 0, 0, 0.5);
        opacity: 0;
        animation: fadeInUp 0.8s 0.2s ease-out forwards;
    }

    .tp-intro-sub {
        font-family: 'Caveat', cursive;
        font-size: 4rem;
        color: var(--brand-gold);
        margin: 10px 0 25px 0;
        line-height: 1;
        text-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        opacity: 0;
        animation: fadeInUp 0.8s 0.4s ease-out forwards;
    }

    /* === START: MODIFIED SCROLL ICON === */
    .scroll-down {
        /* position: absolute; */
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
        text-align: center;
        cursor: pointer;
    }

    .scroll-down a {
        display: inline-block;
        text-decoration: none;
        color: #fff;
        font-size: 1.5rem;
        width: 60px;
        height: 60px;
        line-height: 60px;
        border: 2px solid rgba(255, 255, 255, 0.7);
        border-radius: 50%;
        opacity: 0.8;
        transition: all 0.3s ease;
        animation: playful-bounce 2.5s 1s infinite;
    }

    .scroll-down a:hover {
        opacity: 1;
        background: var(--brand-red);
        border-color: var(--brand-red);
        transform: scale(1.1);
    }

    .scroll-down i {
        animation: icon-move 2.5s 1s infinite;
        display: inline-block;
        /* Allows transform */
    }

    /* New 'playful-bounce' for the circle */
    @keyframes playful-bounce {

        0%,
        20%,
        50%,
        80%,
        100% {
            transform: translateY(0);
            animation-timing-function: ease-out;
        }

        40% {
            transform: translateY(-25px);
            animation-timing-function: ease-in;
        }

        60% {
            transform: translateY(-10px);
            animation-timing-function: ease-out;
        }
    }

    /* New 'icon-move' for the arrow inside */
    @keyframes icon-move {

        0%,
        20%,
        80%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(5px);
        }
    }

    /* === END: MODIFIED SCROLL ICON === */

    /* --- 4. LATEST PUBLICATION (PDF) --- */
    /* This section container ensures the title is above */
    #latest {
        display: flex;
        flex-direction: column;
        background-image: url(assets/h1.webp);
        background-size: cover;
        background-position: 50%;
        max-width: none;
        padding-top: 80px;
        padding-bottom: 80px;
        margin-top: -150px;

    }

    #latest .section-title {
        color: #ff0000ff;
        /* Make title white on this bg */
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
    }

    #latest .section-title::after {
        stroke: var(--brand-gold);
        /* Make squiggle gold */
        /* Note: SVG stroke color can't be changed this way. Re-encoding SVG: */
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 20'%3E%3Cpath d='M5 15 Q 25 5 50 15 T 95 5' stroke='%23FFD700' stroke-width='5' fill='none'/%3E%3C/svg%3E");
    }

    #latest ._df_book {
        max-width: 900px;
        margin: 0 auto;
        border: 1px solid #fff;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        border-radius: 10px;
    }

    /* --- 5. MAGAZINE LIBRARY (#tp-collection) --- */
    .tp-collection-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
    }

    .tp-book-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-light);
        display: flex;
        flex-direction: column;
    }

    .tp-book-link {
        text-decoration: none;
        color: var(--text-dark);
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .tp-book-cover {
        width: 100%;
        height: 380px;
        padding: 10px;
        background: #f9f9f9;
        border-bottom: 1px solid var(--border-light);
    }

    .tp-book-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .tp-book-info {
        padding: 20px;
        text-align: center;
        flex-grow: 1;
    }

    .tp-edition-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 1.1rem;
        margin: 0;
    }

    .tp-book-actions {
        padding: 0 20px 20px 20px;
    }

    .tp-cart-button {
        display: block;
        text-decoration: none;
        background: var(--brand-red);
        color: #fff;
        padding: 12px;
        border-radius: 5px;
        text-align: center;
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* --- 5.5 NEW "ABOUT INTRO" SECTION --- */
    .about-intro-grid {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 40px;
        align-items: center;
        margin-bottom: 60px;
        /* Space between intro and counters */
    }

    .about-intro-image img {
        width: 100%;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .about-intro-text h3 {
        font-family: 'florania', sans-serif;
        font-size: 2.5rem;
        color: var(--brand-pink);
        margin-top: 0;
        margin-bottom: 15px;
    }

    .about-intro-text p {
        color: var(--text-light-gray);
        font-size: 1.1rem;
        line-height: 1.7;
    }

    .about-intro-text .btn {
        margin-top: 20px;
    }

    /* --- 6. COUNTER SECTION (REVISED FUN & SMALLER) --- */
    .counter-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        /* Was 30px */
    }

    .counter-card {
        background: #fff;
        border: 1px solid var(--border-light);
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        padding: 20px 15px;
        /* Was 30px 20px */
        text-align: center;
    }

    .counter-card i {
        font-size: 2rem;
        /* Was 2.5rem */
        color: var(--brand-red);
        margin-bottom: 15px;
    }

    .counter-card h1.count {
        font-family: 'florania', sans-serif;
        font-size: 2.5rem;
        /* Was 3rem */
        font-weight: 700;
        color: var(--brand-teal);
        margin: 0;
    }

    .counter-card p {
        font-family: 'Caveat', cursive;
        font-size: 1.2rem;
        /* Was 1.5rem */
        color: var(--brand-pink);
        margin: -5px 0 0 0;
    }

    /* --- 7. FORMS (Subscribe & Feedback) --- */
    .form-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        align-items: center;
    }

    .form-image img {
        width: 100%;
        border-radius: 8px;
    }

    .form-container form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .form-container .input,
    .form-container select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
    }

    #feedbackForm textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        height: 150px;
    }

    .feedback-form {
        max-width: 600px;
        margin: 0 auto;
        text-align: center;
    }

    p.sub-text {
        font-size: 0.8rem;
        color: #777;
        margin-top: 15px;
        line-height: 1.5;
    }

    p.sub-text a {
        color: var(--brand-red);
    }

    /* --- 8. FULL-WIDTH CTA (NEW) --- */
    .cta-section {
        position: relative;
        padding: 80px 20px;
        background-size: cover;
        background-position: center;
        border-radius: 0;
        /* Full width */
        color: #fff;
        text-align: center;
        overflow: hidden;
        /* For zoom effect */
    }

    .cta-section .cta-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 64, 64, 0.7);
        /* Dark Teal Overlay */
        z-index: 1;
    }

    .cta-section .cta-bg-image {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-size: cover;
        background-position: center;
        z-index: 0;
        transition: transform 0.6s ease;
    }

    .cta-section:hover .cta-bg-image {
        transform: scale(1.05);
    }

    .cta-section .cta-content {
        position: relative;
        z-index: 2;
        max-width: 700px;
        margin: 0 auto;
    }

    .cta-section h2 {
        font-family: 'florania', sans-serif;
        font-size: 3rem;
        color: #fff;
        text-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        margin: 0 0 10px 0;
    }

    .cta-section p {
        font-family: 'Caveat', cursive;
        font-size: 2.2rem;
        color: var(--brand-gold);
        margin: 0 0 25px 0;
        line-height: 1.4;
    }

    /* --- 9. LATEST EVENTS (BLOGS) --- */
    .i-blog-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }

    .i-blog-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-light);
        text-decoration: none;
        color: var(--text-dark);
        overflow: hidden;
    }

    .i-blog-image {
        width: 100%;
        height: 200px;
        object-fit: cover;

    }

    .i-blog-content {
        padding: 20px;
    }

    .i-blog-date {
        font-size: 0.8rem;
        color: var(--brand-red);
        font-weight: 600;
        text-transform: uppercase;
    }

    .i-blog-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 1.2rem;
        line-height: 1.4;
        margin: 5px 0 0 0;
        color: var(--text-dark);
    }

    /* --- 10. PARTNERS LOGO GRID --- */
    #partners-section .partner-main {
        max-width: 400px;
        margin: 0 auto 60px auto;
        /* Center it and give space below */
        text-align: center;
    }

    #partners-section .partner-main .partner-logo-card {
        background: #fff;
        border: 2px solid var(--brand-gold);
        /* Special gold border */
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    #partners-section .partner-main .partner-logo-container {
        height: 120px;
        /* Taller logo area */
    }

    .partner-category {
        margin-bottom: 40px;
    }

    .partner-type-title {
        text-align: center;
        font-family: 'Caveat', cursive;
        font-size: 2.5rem;
        color: var(--brand-pink);
        margin-bottom: 25px;
    }

    .partners-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 20px;
    }

    .partner-logo-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-light);
        padding: 20px;
    }

    .partner-logo-card a {
        display: block;
        text-decoration: none;
    }

    .partner-logo-container {
        height: 80px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .partner-logo {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    /* --- 11. RESPONSIVE --- */
    @media (max-width: 992px) {
        .i-blog-container {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        #tp-menu-toggle {
            color: #fff;
        }

        .tp-fun-intro {
            margin-top: -80px;
        }

        .page-section {
            padding: 60px 15px;
        }

        .page-section.bg-light {
            padding: 60px 15px;
        }

        /* Hero mobile */
        .tp-intro-main {
            font-size: 3.5rem;
        }

        .tp-intro-sub {
            font-size: 3rem;
            margin: 10px 0 20px 0;
        }

        /* --- MODIFIED: Hides new scroll down icon --- */
        .scroll-down {
            display: none;
        }

        /* --- NEW: Responsive About Intro --- */
        .about-intro-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .about-intro-image {
            order: 2;
        }

        .about-intro-text {
            order: 1;
            text-align: center;
        }

        .counter-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        /* Mobile counters */
        .form-section {
            grid-template-columns: 1fr;
        }

        .form-image {
            order: 2;
        }

        .form-container {
            order: 1;
        }

        .partners-grid {
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        }

        .cta-section h2 {
            font-size: 2.5rem;
        }

        .cta-section p {
            font-size: 1.8rem;
        }
    }

    /* ---
  END: Master Style Block
  --- */
    /* --- 3.1 NEW: HERO ANIMATIONS --- */

    /* 1. Ken Burns Effect on Background */
    .hero-background-layer {
        background-image: linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.3)), url('assets/breadcrumbs/bread.jpeg');
        background-size: cover;
        background-position: center;
        position: absolute;
        z-index: 1;
        top: 0;
        width: 100%;
        height: 100vh;
        background-attachment: scroll;
        /* ADDED ANIMATION */
        animation: ken-burns 25s ease-in-out infinite alternate;
    }

    @keyframes ken-burns {
        from {
            transform: scale(1) translate(0, 0);
            background-position: center center;
        }

        to {
            transform: scale(1.1) translate(-2%, 0);
            background-position: top left;
        }
    }

    /* 2. CSS-Only Particles */
    .tp-hero-particles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        z-index: 2;
        /* Below navbar (1009) but above BG (1) */
        overflow: hidden;
        pointer-events: none;
        /* Let clicks pass through */
    }

    .particle {
        position: absolute;
        bottom: -10px;
        /* Start from below the screen */
        left: var(--left);
        width: var(--size);
        height: var(--size);
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        box-shadow: 0 0 8px var(--brand-gold);
        opacity: 0;
        animation: float-particles linear infinite;
        animation-duration: var(--duration);
        animation-delay: var(--delay);
    }

    @keyframes float-particles {
        0% {
            transform: translateY(0);
            opacity: 0;
        }

        10%,
        90% {
            opacity: 1;
        }

        100% {
            transform: translateY(-100vh);
            /* Float to the top */
            opacity: 0;
        }
    }

    /* 3. Typing Animation Cursor */
    .tp-typing-cursor::after {
        content: '|';
        display: inline-block;
        margin-left: 5px;
        color: var(--brand-gold);
        font-weight: 300;
        animation: blink-cursor 0.7s infinite;
    }

    @keyframes blink-cursor {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0;
        }
    }

    /* --- Make sure hero content is above particles --- */
    .tp-fun-intro {
        position: relative;
        z-index: 3;
        /* Above particles */
        /* ... rest of your .tp-fun-intro styles ... */
    }
    </style>

    <!-- Open Graph & Twitter Cards -->
    <meta property="og:title" content="Turning Point Magazine - Africa's News & Culture" />
    <meta property="og:description" content="Turning Point Magazine is a digital platform dedicated to amplifying grassroots voices and celebrating stories of positive change across Africa." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.turningpointmagazine.africa/" />
    <meta property="og:image" content="https://www.turningpointmagazine.africa/assets/logo.png" />
    <meta name="twitter:card" content="summary_large_image" />

    <!-- JSON-LD Organization Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Turning Point Magazine",
      "url": "https://www.turningpointmagazine.africa/",
      "logo": "https://www.turningpointmagazine.africa/assets/logo.png",
      "sameAs": [
        "https://twitter.com/turningpointmag",
        "https://www.facebook.com/turningpointmag"
      ]
    }
    </script>
</head>

<body>

    <?php include 'includes/preloader.php'; ?>

    <div class="hero-background-layer"></div>

    <?php include 'includes/new-navbar.php'; ?>

    <section class="tp-fun-intro" id="top-hero">

        <div class="tp-hero-particles">
            <?php 
      // Generate 30 randomized particles
      for ($i = 0; $i < 30; $i++): 
      	// Random duration, x-offset, size, and delay
      	$duration = rand(10, 30); // 10s to 30s
      	$left = rand(0, 100); // 0% to 100%
     	$size = rand(1, 4); // 1px to 4px
      	$delay = rand(0, 15); // 0s to 15s delay
      ?>
            <span class="particle" style="
      		--duration: <?= $duration ?>s;
      		--left: <?= $left ?>vw;
      		--size: <?= $size ?>px;
      		--delay: <?= $delay ?>s;
      	"></span>
            <?php endfor; ?>
        </div>
        <h1 class="tp-intro-main fade-in-up">Turning Point</h1>

        <h2 class="tp-intro-sub fade-in-up" style="animation-delay: 0.2s;">
            <span id="tp-typing-text" class="tp-typing-cursor"></span>
        </h2>
        <div class="scroll-down">
            <a href="#latest"> <i class="bi bi-chevron-down"></i></a>
        </div>
    </section>
    <main class="et-main">

        <section class="page-section fade-in-up" id="latest">
            <h2 class="section-title">Latest Publication</h2>
            <div class="_df_book" height="500" webgl="true" backgroundcolor="transparent" source="assets/Mag2.pdf"
                id="df_manual_book">
            </div>
        </section>
   

<style>
    /* --- 1. MASTER LAYOUT (Strict 100vh) --- */
    .iwd-mega-section {
        position: relative;
        width: 100%;
        height: 100vh; /* Fixed height */
        background: #1a1a1a;
        color: #fff;
        display: flex;
        flex-direction: column;
        overflow: hidden; /* No external scrollbars */
    }

    /* --- 2. UPPER CONTENT AREA --- */
    .iwd-split-container {
        display: flex;
        width: 100%;
        flex: 1; /* Takes remaining height */
        min-height: 0; /* Prevents flex overflow */
        position: relative;
    }

    /* --- LEFT: IMAGE STAGE (Stable Box) --- */
    .iwd-stage {
        flex: 0 0 55%; /* Fixed width 55% */
        position: relative; /* Anchor for absolute image */
        background: #000;
        overflow: hidden;
    }

    /* The Image - Absolute Positioning to prevent Layout Shift */
    .iwd-main-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover; /* Crops to fill box */
        opacity: 1;
        transform: scale(1);
        transition: opacity 0.6s ease-in-out;
        will-change: transform, opacity;
        z-index: 1;
    }

    /* Ken Burns Animation */
    .ken-burns-active {
        animation: kenBurns 6s linear forwards;
    }

    @keyframes kenBurns {
        from { transform: scale(1); }
        to { transform: scale(1.10); }
    }
    
    .fade-out {
        opacity: 0;
    }

    /* Shadow Overlay */
    .iwd-stage::after {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(90deg, rgba(0,0,0,0) 0%, rgba(26,26,26,1) 100%);
        pointer-events: none;
        z-index: 2;
    }

    /* --- RIGHT: NARRATIVE --- */
    .iwd-narrative {
        flex: 1;
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: #1a1a1a;
        z-index: 3;
        /* Hide Scrollbar but allow scroll if needed */
        overflow-y: auto;
        scrollbar-width: none; /* Firefox */
    }
    .iwd-narrative::-webkit-scrollbar { display: none; } /* Chrome */

    .iwd-narrative .tag {
        color: var(--brand-gold);
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 0.8rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        margin-bottom: -30px;
    }

    .iwd-narrative .tag::before {
        content: '';
        width: 30px;
        height: 2px;
        background: var(--brand-gold);
        margin-right: 10px;
    }

    .iwd-narrative h2 {
        font-family: 'florania', sans-serif;
        font-size: clamp(2.5rem, 3.5vw, 4rem);
        line-height: 1.1;
        margin-bottom: 10px;
        background: linear-gradient(to right, #fff, #ccc);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .iwd-narrative p {
        font-family: 'Poppins', sans-serif;
        font-size: clamp(0.9rem, 1vw, 1.1rem);
        line-height: 1.6;
        color: #bbb;
        margin-bottom: 25px;
        max-width: 550px;
    }

    .iwd-stat-row {
        display: flex;
        gap: 25px;
        margin-bottom: 30px;
        border-top: 1px solid #333;
        border-bottom: 1px solid #333;
        padding: 15px 0;
    }

    .iwd-stat h4 {
        font-family: 'florania', sans-serif;
        font-size: 2rem;
        color: var(--brand-pink);
        margin: 0;
    }
    
    .iwd-stat span {
        font-family: 'Poppins', sans-serif;
        font-size: 0.75rem;
        color: #888;
        text-transform: uppercase;
    }

    /* --- 3. BOTTOM STRIP (Fixed Height) --- */
    .iwd-strip-container {
        height: 120px; /* Fixed */
        background: #111;
        border-top: 1px solid #333;
        display: flex;
        flex-direction: column;
        justify-content: center;
        flex-shrink: 0;
        z-index: 10;
        position: relative;
    }

    .iwd-strip-title {
        text-align: center;
        color: #555;
        font-size: 0.75rem;
        text-transform: uppercase;
        margin: 5px 0;
        letter-spacing: 1px;
    }

    /* Horizontal Track - NO SNAP, NO SCROLLBAR */
    .iwd-gallery-track {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding: 0 40px;
        height: 80px;
        align-items: center;
        
        /* Disable Scroll Snap */
        scroll-snap-type: none;
        
        /* Hide Scrollbars */
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE */
    }
    
    .iwd-gallery-track::-webkit-scrollbar { 
        display: none; /* Chrome/Safari */
    }

    .thumb-card {
        flex: 0 0 120px; 
        height: 70px;
        border-radius: 4px;
        overflow: hidden;
        cursor: pointer;
        opacity: 0.4;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        position: relative;
    }

    /* Progress Bar */
    .thumb-card::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0;
        height: 3px;
        width: 0%;
        background: var(--brand-gold);
        transition: width 0s;
    }

    .thumb-card.active::after {
        width: 100%;
        transition: width 5s linear; /* Animation Timer */
    }

    .thumb-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .thumb-card:hover { opacity: 0.8; transform: translateY(-3px); }
    
    .thumb-card.active {
        opacity: 1;
        border-color: var(--brand-gold);
        transform: scale(1.05);
        box-shadow: 0 0 15px rgba(255, 215, 0, 0.2);
    }

    /* --- RESPONSIVE MOBILE --- */
    @media (max-width: 992px) {
        .iwd-mega-section {
            height: auto;
            min-height: 100vh;
        }
        .iwd-split-container {
            flex-direction: column;
        }
        .iwd-stage {
            width: 100%;
            height: 40vh; /* Fixed height on mobile */
            flex: none;
        }
        .iwd-narrative {
            padding: 30px 20px;
            flex: none;
        }
        .iwd-strip-container {
            position: sticky;
            bottom: 0;
            background: rgba(17,17,17,0.98);
        }
    }
</style>

<section class="iwd-mega-section fade-in-up" id="iwd-2025">
    
    <div class="iwd-split-container">
        <div class="iwd-stage">
            <img loading="lazy" src="assets/IWD/IW1.jpg" alt="IWD 2025 Main" class="iwd-main-img ken-burns-active" id="mainDisplay">
        </div>

        <div class="iwd-narrative">
            <div class="tag">IWD Celebration 2025</div>
            <h2>Accelerating Action: <br> A Historic Event</h2>
            
            <p>
                From March 7-8, 2025, the KICC hosted a powerful convergence of leaders, including <strong>Hon. Harriette Chiggai</strong>. 
                We launched our <strong>2nd Issue</strong> and honored 25 trailblazing women.
            </p>

            <div class="iwd-stat-row">
                <div class="iwd-stat">
                    <h4>2 Days</h4>
                    <span>Of Impact</span>
                </div>
                <div class="iwd-stat">
                    <h4>25+</h4>
                    <span>Women Honored</span>
                </div>
                <div class="iwd-stat">
                    <h4>100+</h4>
                    <span>Stories Told</span>
                </div>
            </div>

            <a href="https://www.turningpointmagazine.africa/blog/international-womens-day-2025-celebration" class="btn" style="align-self: flex-start;">
                <span class="btn-text">Read Full Report</span>
                <i class="fas fa-arrow-right" style="margin-left: 10px;"></i>
            </a>
        </div>
    </div>

    <div class="iwd-strip-container">
        <div class="iwd-strip-title"><i class="fas fa-play-circle"></i> Gallery</div>
        <div class="iwd-gallery-track" id="thumbTrack">
            <?php for($i=1; $i<=20; $i++): ?>
            <div class="thumb-card <?php echo ($i==1) ? 'active' : ''; ?>" onclick="manualSelect(<?php echo $i-1; ?>)">
                <img loading="lazy" src="assets/IWD/IW<?php echo $i; ?>.jpg" alt="IWD Moment <?php echo $i; ?>">
            </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<script>
    // --- CONFIG ---
    const slideInterval = 5000;
    // Auto-generate array for 20 images
    const images = [];
    for(let i=1; i<=20; i++) {
        images.push('assets/IWD/IW' + i + '.jpg');
    }

    let currentIndex = 0;
    let autoPlayTimer;
    const mainImg = document.getElementById('mainDisplay');
    const thumbs = document.querySelectorAll('.thumb-card');
    const track = document.getElementById('thumbTrack');

    function updateSlide(index) {
        // Fade Out
        mainImg.classList.add('fade-out');

        setTimeout(() => {
            // Swap
            mainImg.src = images[index];
            
            // Reset Animation
            mainImg.classList.remove('ken-burns-active');
            void mainImg.offsetWidth; 
            mainImg.classList.add('ken-burns-active');

            // Fade In
            mainImg.onload = () => {
                mainImg.classList.remove('fade-out');
            };
        }, 500);

        // Update Thumbnails
        thumbs.forEach(t => t.classList.remove('active'));
        if(thumbs[index]) {
            thumbs[index].classList.add('active');
            
            // Soft Scroll Logic (Keeps active item visible)
            const thumbLeft = thumbs[index].offsetLeft;
            const thumbWidth = thumbs[index].offsetWidth;
            const trackWidth = track.offsetWidth;
            
            track.scrollTo({
                left: thumbLeft - (trackWidth / 2) + (thumbWidth / 2),
                behavior: 'smooth'
            });
        }
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % images.length;
        updateSlide(currentIndex);
    }

    function startTimer() {
        if(autoPlayTimer) clearInterval(autoPlayTimer);
        autoPlayTimer = setInterval(nextSlide, slideInterval);
    }

    function manualSelect(index) {
        clearInterval(autoPlayTimer);
        currentIndex = index;
        updateSlide(currentIndex);
        startTimer();
    }

    document.addEventListener('DOMContentLoaded', () => {
        startTimer();
    });
</script>

        <section class="page-section bg-light fade-in-up" id="tp-collection">
            <div>
                <h2 class="section-title">Our Magazine Library</h2>
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

                                    <?= htmlspecialchars($edition['edition_name']) ?> <i class="fas fa-arrow-right"
                                        style="font-size: 0.8em;"></i>
                                </h3>
                            </div>
                        </a>
                        <div class="tp-book-actions">
                            <a href="shop.php?edition_id=<?= htmlspecialchars($edition['id']) ?>"
                                class="tp-cart-button">
                                <i class="fas fa-shopping-cart" style="margin-right: 8px;"></i>
                                Order A Physical Copy
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="page-section fade-in-up" id="about-intro">
            <h2 class="section-title">Who We Are</h2>
            <div class="about-intro-grid">
                <div class="about-intro-image">
                    <img loading="lazy" src="assets/about.jpg" alt="About Turning Point Magazine">
                </div>
                <div class="about-intro-text">
                    <h3>Amplifying Grassroots Voices</h3>
                    <p>
                        Turning Point Magazine is a digital platform dedicated to celebrating
                        stories of positive
                        change across Africa. We believe in the power of inclusive,
                        transformative content to shape a
                        brighter future for everyone.
                    </p>
                    <a href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>about" class="btn">Discover Our Story</a>
                </div>
            </div>

            <!-- Counter Grid Commented Out for Cleanup
            <div class="counter-grid">
                <div class="counter-card">
                    <i class="fas fa-lightbulb"></i>
                    <div class="count" data-target="100">0</div>
                    <p>Advertisements</p>
                </div>
                <div class="counter-card">
                    <i class="fas fa-users"></i>
                    <div class="count" data-target="300">0</div>
                    <p>Visitors</p>
                </div>
                <div class="counter-card">
                    <i class="fas fa-envelope-open-text"></i>
                    <div class="count" data-target="500">0</div>
                    <p>Subscriptions</p>
                </div>
            </div>
            -->
        </section>
        <section class="page-section bg-light fade-in-up" id="subscribe">
            <div>
                <h2 class="section-title">Don't Miss Out</h2>
                <div class="form-section">
                    <div class="form-image">
                        <img loading="lazy" src="assets/about.png" alt="Subscribe">
                    </div>
                    <div class="form-container">
                        <p
                            style="text-align: center; color: var(--text-light-gray); font-size: 1.1rem; margin-bottom: 20px;">
                            Subscribe now to get regular
                            updates whenever we have a new publication.</p>
                        <form id="subscribeForm" name="sub" method="POST">
                            <input type="text" placeholder="First Name" class="input" name="f-name" required>
                            <input type="text" placeholder="Last Name" class="input" name="l-name" required>
                            <input type="email" placeholder="Email Address" class="input" name="email" required>
                            <select class="input" name="address" required>
                                <option value="" disabled selected>Select a country
                                </option>
                            </select>
                            <button type="submit" class="btn">
                                <span class="btn-text">Subscribe</span>
                            </button>
                            <div id="subscribe-response"></div>
                        </form>
                        <p class="p-s sub-text">
                            By subscribing, you agree to our <a href="#">Terms</a> and <a href="#">Privacy Policy</a>.
                            Of You acknowledge we are a <a href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>about#licencing">licensed
                                Data Controller</a>.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="page-section-full fade-in-up">
            <div class="cta-section">
                <div class="cta-bg-image" style="background-image: url('assets/c1.webp');"></div>
                <div class="cta-overlay"></div>
                <div class="cta-content">
                    <h2>Advertise With Us</h2>
                    <p>Showcase your brand and connect with our engaged audience.</p>
                    <a href="contact.php" class="btn">Reach Out Now!</a>
                </div>
            </div>
        </section>

      <section class="page-section bg-light fade-in-up">
    <div>
        <h2 class="section-title">Latest Events</h2>
        
        <?php 
        // Sort the blogs array by the created_at timestamp (latest to oldest)
        usort($blogs, function($a, $b) {
            return strtotime($b['created_at']) <=> strtotime($a['created_at']);
        });
        ?>

        <div class="i-blog-container">
            <?php foreach($blogs as $blog): 
                // Generate the slug exactly like in blog.php
                $slug = strtolower(preg_replace('/\s+/', '-', $blog['title']));
                $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
                $slug = preg_replace('/-+/', '-', $slug);
                $slug = trim($slug, '-');
            ?>
            <a href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>blog/<?= $slug ?>" class="i-blog-card">
                <img loading="lazy" src="admin/<?= htmlspecialchars($blog['top_photo']) ?>"
                    alt="<?= htmlspecialchars($blog['title']) ?>" class="i-blog-image">
                <div class="i-blog-content">
                    <div class="i-blog-date">
                        <?= date('F j, Y', strtotime($blog['date'])) ?>
                    </div>
                    <h3 class="i-blog-title"><?= htmlspecialchars($blog['title']) ?></h3>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="blog.php" class="btn btn-primary" style="padding: 10px 25px; text-decoration: none; border-radius: 5px;">
                View All Events
            </a>
        </div>
    </div>
</section>

        <section class="page-section-full fade-in-up">
            <div class="cta-section">
                <div class="cta-bg-image" style="background-image: url('assets/c2.webp');"></div>
                <div class="cta-overlay" style="background-color: rgba(100, 0, 80, 0.7);"></div>
                <div class="cta-content">
                    <h2>Contribute Your Story</h2>
                    <p>Share your photos, stories, and insights with our community.</p>
                    <a href="story.php" class="btn">Contribute Now!</a>
                </div>
            </div>
        </section>

        <section class="page-section fade-in-up" id="partners-section">
            <h2 class="section-title">Our Partners</h2>

            <div class="partner-category">
                <h3 class="partner-type-title">Main Partner</h3>
                <div class="partners-grid">
                    <div class="partner-logo-card" style="border-color: var(--brand-gold);">
                        <a href="https://malshemedia.com" target="_blank">
                            <div class="partner-logo-container"><img loading="lazy" src="assets/logos/malshe-main.png"
                                    alt="Malshe Media" class="partner-logo"></div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="partner-category">
                <h3 class="partner-type-title">Strategic Partners</h3>
                <div class="partners-grid">
                    <div class="partner-logo-card">
                        <a href="#">
                            <div class="partner-logo-container"><img loading="lazy" src="assets/logos/acik-strategic.png" alt="Acik"
                                    class="partner-logo"></div>
                        </a>
                    </div>
                    <div class="partner-logo-card">
                        <a href="#">
                            <div class="partner-logo-container"><img loading="lazy" src="assets/logos/nairobi-startegic.png"
                                    alt="Nairobi Strategic" class="partner-logo"></div>
                        </a>
                    </div>
                    <div class="partner-logo-card">
                        <a href="#">
                            <div class="partner-logo-container"><img loading="lazy" src="assets/logos/prestine-strategic.png"
                                    alt="Prestine Strategic" class="partner-logo"></div>
                        </a>
                    </div>
                    <div class="partner-logo-card">
                        <a href="#">
                            <div class="partner-logo-container"><img loading="lazy" src="assets/logos/redcross-strategic.png"
                                    alt="Redcross" class="partner-logo"></div>
                        </a>
                    </div>
                    <div class="partner-logo-card">
                        <a href="#">
                            <div class="partner-logo-container"><img loading="lazy" src="assets/logos/topwellness-strategic.png"
                                    alt="Topwellness" class="partner-logo"></div>
                        </a>
                    </div>
                    <div class="partner-logo-card">
                        <a href="#">
                            <div class="partner-logo-container"><img loading="lazy" src="assets/logos/childline-startegic.png"
                                    alt="Childline" class="partner-logo"></div>
                        </a>
                    </div>
                    <div class="partner-logo-card">
                        <a href="#">
                            <div class="partner-logo-container"><img loading="lazy" src="assets/logos/dayo-strategic.png" alt="Dayo"
                                    class="partner-logo"></div>
                        </a>
                    </div>
                    <div class="partner-logo-card">
                        <a href="#">
                            <div class="partner-logo-container"><img loading="lazy" src="assets/logos/enkare-strategic.png"
                                    alt="Enkare" class="partner-logo"></div>
                        </a>
                    </div>
                    <div class="partner-logo-card">
                        <a href="#">
                            <div class="partner-logo-container"><img loading="lazy" src="assets/logos/kreate-strategic.png"
                                    alt="Kreate" class="partner-logo"></div>
                        </a>
                    </div>
                    <div class="partner-logo-card">
                        <a href="https://jitoleegoodfriendsfoundation.org/" target="_blank">
                            <div class="partner-logo-container"><img loading="lazy" src="assets/logos/jitolee-strategic.png"
                                    alt="Jitolee" class="partner-logo"></div>
                        </a>
                    </div>
                    <div class="partner-logo-card">
                        <a href="#">
                            <div class="partner-logo-container"><img loading="lazy" src="assets/logos/manyo-strategic.png" alt="Manyo"
                                    class="partner-logo"></div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="partner-category">
                <h3 class="partner-type-title">Regional Partners</h3>
                <div class="partners-grid">
                    <div class="partner-logo-card">
                        <a href="#">
                            <div class="partner-logo-container"><img loading="lazy" src="assets/logos/affrican-regional.png"
                                    alt="Affrican" class="partner-logo"></div>
                        </a>
                    </div>
                    <div class="partner-logo-card">
                        <a href="#">
                            <div class="partner-logo-container"><img loading="lazy" src="assets/logos/cowlha-regional.png" alt="Cowlha"
                                    class="partner-logo"></div>
                        </a>
                    </div>
                    <div class="partner-logo-card">
                        <a href="#">
                            <div class="partner-logo-container"><img loading="lazy" src="assets/logos/ufel-regional.png" alt="Ufel"
                                    class="partner-logo"></div>
                        </a>
                    </div>
                    <div class="partner-logo-card">
                        <a href="#">
                            <div class="partner-logo-container"><img loading="lazy" src="assets/logos/wgnrr-regional.png" alt="WGNRR"
                                    class="partner-logo"></div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="partner-category">
                <h3 class="partner-type-title">Media Partners</h3>
                <div class="partners-grid">
                    <div class="partner-logo-card">
                        <a href="#">
                            <div class="partner-logo-container"><img loading="lazy" src="assets/logos/baboon-media.png"
                                    alt="Baboon Media" class="partner-logo"></div>
                        </a>
                    </div>
                    <div class="partner-logo-card">
                        <a href="#">
                            <div class="partner-logo-container"><img loading="lazy" src="assets/logos/bestbrand-media.png"
                                    alt="Bestbrand Media" class="partner-logo"></div>
                        </a>
                    </div>
                    <div class="partner-logo-card">
                        <a href="#">
                            <div class="partner-logo-container"><img loading="lazy" src="assets/logos/digitalscroll-media.png"
                                    alt="Digitalscroll Media" class="partner-logo"></div>
                        </a>
                    </div>
                    <div class="partner-logo-card">
                        <a href="#">
                            <div class="partner-logo-container"><img loading="lazy" src="assets/logos/getricmedia.png"
                                    alt="Getricmedia" class="partner-logo"></div>

                        </a>
                    </div>
                    <div class="partner-logo-card">
                        <a href="#">
                            <div class="partner-logo-container"><img loading="lazy" src="assets/logos/jawaka-media.png"
                                    alt="Jawaka Media" class="partner-logo"></div>
                        </a>
                    </div>
                    <div class="partner-logo-card">
                        <a href="#">
                            <div class="partner-logo-container"><img loading="lazy" src="assets/logos/thro-media.png" alt="Thro Media"
                                    class="partner-logo"></div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="partner-category">
                <h3 class="partner-type-title">National Partners</h3>
                <div class="partners-grid">
                    <div class="partner-logo-card">
                        <a href="#">
                            <div class="partner-logo-container"><img loading="lazy" src="assets/logos/GOK-National.png" alt="GOK"
                                    class="partner-logo"></div>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="page-section bg-light fade-in-up" id="feedback">
            <div>
                <h2 class="section-title">Feedback</h2>
                <div class="feedback-form">
                    <p style="color: var(--text-light-gray); margin-bottom: 20px; font-size: 1.1rem;">
                        Have an opinion?
                        Let us know! Your
                        feedback will help us make our website even better.</p>
                    <form id="feedbackForm" name="feedback" method="POST">
                        <textarea placeholder="Share your ideas here" name="comments" id="comments"></textarea>
                        <button type="submit" class="btn btn-feed">
                            <span class="btn-text">Submit Feedback</span>
                        </button>
                        <div id="feedback-response" style="margin-top: 15px;"></div>
                    </form>
                </div>
            </div>
        </section>

        <div id="back-top">
            <a title="Go to Top" href="#top-hero"> <i class="fas fa-level-up-alt"></i></a>
        </div>

    </main>

    <?php include 'includes/footer.php' ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/smooth-scroll@16.1.3/dist/smooth-scroll.min.js"></script>

    <script src="dflip/js/dflip.min.js" type="text/javascript"></script>

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
        Note
    })();
    </script>

    <script>
    $(document).ready(function() {

        // --- Smooth Scroll ---
        var scroll = new SmoothScroll('a[href*="#"]', {
            speed: 800,
            speedAsDuration: true,
            header: '.nn-navbar[data-sticky="true"]'
        });

        // --- Feedback Form AJAX ---
        $('#feedbackForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: "POST",
                url: "feedback.php",
                data: formData,
                success: function(response) {
                    $('#feedback-response').html(
                        '<p style="color: green; font-weight: 500;">' + response +
                        '</p>');
                    $('#feedbackForm')[0].reset();
                },
                error: function() {
                    $('#feedback-response').html(
                        '<p style="color: red;">An error occurred. Please try again.</p>'
                    );
                }
            });
        });

        // --- Subscribe Form AJAX ---
        $('#subscribeForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: "POST",
                url: "subscribe.php",
                data: formData,
                success: function(response) {
                    $('#subscribe-response').html(
                        '<p style="color: green; font-weight: 500;">' + response +
                        '</p>');
                    $('#subscribeForm')[0].reset();
                },
                error: function() {
                    $('#subscribe-response').html(
                        '<p style="color: red;">An error occurred. Please try again.</p>'
                    );
                }
            });
        });

        // --- Counter Animation ---
        function animateCounter() {
            $('.count').each(function() {
                const $this = $(this);
                const target = +$this.data('target');
                if ($this.text() != "0") return; // Already animated

                $({
                    current: 0
                }).animate({
                    current: target
                }, {
                    duration: 2000,
                    easing: 'swing',
                    step: function() {
                        $this.text(Math.ceil(this.current));
                    },
                    complete: function() {
                        $this.text(this.current + "+"); // Add '+' at the end
                    }
                });
            });
        }

        function isInViewport(element) {
            if (!element.length) return false;
            const elementTop = $(element).offset().top;
            const elementBottom = elementTop + $(element).outerHeight();
            const viewportTop = $(window).scrollTop();
            const viewportBottom = viewportTop + $(window).height();
            return elementBottom > viewportTop && elementTop < (viewportBottom - 50); // Trigger a bit earlier
        }

        let counterAnimated = false;
        $(window).on('scroll.counter', function() {
            if (!counterAnimated && isInViewport($('.counter-grid'))) {
                animateCounter();
                counterAnimated = true;
                $(window).off('scroll.counter'); // Stop listening once animated
            }
        });
        $(window).trigger('scroll.counter'); // Check on load

        // --- Country List Generator ---
        const countrySelect = document.querySelector('select[name="address"]');
        if (countrySelect) {
            const countries = new Intl.DisplayNames(['en'], {
                type: 'region'
            });
            const countryCodes = ["AF", "AL", "DZ", "AD", "AO", "AG", "AR", "AM", "AU", "AT", "AZ", "BS", "BH",
                "BD", "BB", "BY", "BE", "BZ", "BJ", "BT", "BO", "BA", "BW", "BR", "BN", "BG", "BF", "BI",
                "CV", "KH", "CM", "CA", "CF", "TD", "CL", "CN", "CO", "KM", "CG", "CR", "HR", "CU", "CY",
                "CZ", "CD", "DK", "DJ", "DM", "DO", "EC", "EG", "SV", "GQ", "ER", "EE", "SZ", "ET", "FJ",
                "FI", "FR", "GA", "GM", "GE", "DE", "GH", "GR", "GD", "GT", "GN", "GW", "GY", "HT", "HN",
                "HU", "IS", "IN", "ID", "IR", "IQ", "IE", "IL", "IT", "JM", "JP", "JO", "KZ", "KE", "KI",
                "KP", "KR", "XK", "KW", "KG", "LA", "LV", "LB", "LS", "LR", "LY", "LI", "LT", "LU", "MG",
                "MW", "MY", "MV", "ML", "MT", "MH", "MR", "MU", "MX", "FM", "MD", "MC", "MN", "ME", "MA",
                "MZ", "MM", "NA", "NR", "NP", "NL", "NZ", "NI", "NE", "NG", "MK", "NO", "OM", "PK",
                "PW",
                "PS", "PA", "PG", "PY", "PE", "PH", "PL", "PT", "QA", "RO", "RU", "RW", "KN", "LC", "VC",
                "WS", "SM", "ST", "SA", "SN", "RS", "SC", "SL", "SG", "SK", "SI", "SB", "SO", "ZA", "SS",
                "ES", "LK", "SD", "SR", "SE", "CH", "SY", "TJ", "TZ", "TH", "TL", "TG", "TO", "TT", "TN",
                "TR", "TM", "TV", "UG", "UA", "AE", "GB", "US", "UY", "UZ", "VU", "VA", "VE",
                "VN", "YE",
                "ZM", "ZW"
            ];
            countryCodes.forEach(code => {
                const option = document.createElement('option');
                const countryName = countries.of(code);
                option.value = countryName;
                option.textContent = countryName;
                countrySelect.appendChild(option);
            });
        }
        // === START: NEW TYPING ANIMATION SCRIPT ===
        const typingTextElement = document.getElementById('tp-typing-text');
        const wordsToType = [
            "Transforming Everyday.",
            "Inspiring Africa.",
            "Amplifying Voices.",
            "Celebrating Change."
        ];
        let wordIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        const typeSpeed = 120;
        const deleteSpeed = 60;
        const delayBetweenWords = 1500;

        function type() {
            const currentWord = wordsToType[wordIndex];

            if (isDeleting) {
                // Deleting
                typingTextElement.textContent = currentWord.substring(0, charIndex - 1);
                charIndex--;
            } else {
                // Typing
                typingTextElement.textContent = currentWord.substring(0, charIndex + 1);
                charIndex++;
            }

            let typingDelay = isDeleting ? deleteSpeed : typeSpeed;

            if (!isDeleting && charIndex === currentWord.length) {
                // Word fully typed
                typingDelay = delayBetweenWords;
                isDeleting = true;
            } else if (isDeleting && charIndex === 0) {
                // Word fully deleted
                isDeleting = false;
                wordIndex = (wordIndex + 1) % wordsToType.length;
                typingDelay = typeSpeed;
            }

            setTimeout(type, typingDelay);
        }

        if (typingTextElement) {
            setTimeout(type, 500); // Start typing after a short delay
        }
        // === END: NEW TYPING ANIMATION SCRIPT ===

    });
    </script>
</body>

</html>
