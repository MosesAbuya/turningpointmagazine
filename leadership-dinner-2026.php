<?php
/**
 * Dedicated Event Landing Page: The Leadership Dinner 2026
 * Turning Point Magazine Africa
 */

session_start();
require_once __DIR__ . '/connection2.php';
require_once __DIR__ . '/includes/event_db_helper.php';

$pdo = connect();
ensure_event_tables_exist($pdo);

$event = get_flagship_event($pdo, 'leadership-dinner-2026');
if (!$event) {
    die("Event information currently unavailable.");
}

$tiers = get_event_tiers($pdo, $event['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title><?= htmlspecialchars($event['title']) ?> | Turning Point Magazine Africa</title>
    
    <meta name="description" content="<?= htmlspecialchars($event['subtitle'] . ' - ' . $event['venue']) ?>">
    <link rel="canonical" href="https://www.turningpointmagazine.africa/leadership-dinner-2026.php">

    <link rel="apple-touch-icon" sizes="180x180" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>assets/favicon/favicon-16x16.png">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Global Styles & Navbar -->
    <link rel="stylesheet" href="includes/new-navbar.css">
    <link rel="stylesheet" href="global.css">

    <style>
        @font-face {
            font-family: florania;
            src: url("assets/fonts/florania.otf") format("opentype");
        }

        :root {
            --tp-obsidian: #0d0d0d;
            --tp-charcoal: #141414;
            --tp-surface: #1a1a1a;
            --tp-surface-card: #202020;
            --tp-gold: #d4af37;
            --tp-gold-light: #f5e7b2;
            --tp-gold-dark: #aa8421;
            --tp-gold-gradient: linear-gradient(135deg, #d4af37 0%, #aa8421 50%, #f7eaae 100%);
            --tp-crimson: #c8102e;
            --tp-crimson-hover: #e63946;
            --tp-teal: #008080;
            --tp-pink: #e6007e;
            --tp-ivory: #faf7f2;
            --tp-muted: #a3a3a3;
            --tp-border-gold: rgba(212, 175, 55, 0.35);
            --tp-border-subtle: rgba(255, 255, 255, 0.1);
        }

        html, body {
            background-color: var(--tp-obsidian) !important;
            color: var(--tp-ivory);
            font-family: 'Poppins', sans-serif;
            margin: 0 !important;
            padding: 0 !important;
            padding-top: 0 !important;
            overflow-x: clip;
        }

        /* --- EDITORIAL HERO SECTION --- */
        .dinner-hero {
            position: relative;
            margin-top: -145px;
            padding: 175px 20px 85px;
            min-height: 80vh;
            background: linear-gradient(180deg, rgba(13,13,13,0.55) 0%, rgba(20,20,20,0.96) 100%),
                        url('assets/IWD/IW1.jpg') center top/cover no-repeat !important;
            border-bottom: 2px solid var(--tp-gold) !important;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            overflow: hidden;
        }

        .dinner-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 50% 30%, rgba(200, 16, 46, 0.22) 0%, transparent 65%),
                        radial-gradient(circle at 80% 80%, rgba(212, 175, 55, 0.15) 0%, transparent 50%);
            pointer-events: none;
            z-index: 1;
        }

        .dinner-hero-inner {
            position: relative;
            z-index: 2;
            max-width: 1050px;
            margin: 0 auto;
        }

        .hero-badge-ribbon {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 24px;
            background: rgba(20, 20, 20, 0.88);
            border: 1px solid var(--tp-gold);
            border-radius: 50px;
            color: var(--tp-gold);
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            box-shadow: 0 4px 20px rgba(212, 175, 55, 0.25);
            margin-bottom: 25px;
            backdrop-filter: blur(8px);
        }

        .hero-badge-ribbon i {
            color: var(--tp-crimson);
        }

        .dinner-hero-title {
            font-family: 'florania', serif;
            font-size: clamp(34px, 5.5vw, 64px);
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 3px;
            line-height: 1.12;
            margin-bottom: 18px;
            text-shadow: 0 4px 25px rgba(0,0,0,0.9);
        }

        .dinner-hero-title span {
            background: var(--tp-gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .dinner-hero-sub {
            font-family: 'Caveat', cursive;
            font-size: clamp(22px, 3.2vw, 34px);
            color: var(--tp-gold-light);
            max-width: 860px;
            margin: 0 auto 30px;
            line-height: 1.35;
            letter-spacing: 0.5px;
        }

        .event-pills-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin-bottom: 35px;
        }

        .event-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(26, 26, 26, 0.9);
            border: 1px solid var(--tp-border-subtle);
            padding: 10px 22px;
            border-radius: 30px;
            font-size: 14px;
            color: #e5e5e5;
            backdrop-filter: blur(5px);
        }

        .event-pill i {
            color: var(--tp-gold);
        }

        /* Countdown Timer Box */
        .countdown-container {
            background: rgba(18, 18, 18, 0.92);
            border: 1px solid var(--tp-border-gold);
            border-radius: 16px;
            padding: 22px 30px;
            display: inline-flex;
            gap: 25px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.6);
            margin-bottom: 40px;
            backdrop-filter: blur(10px);
        }

        .countdown-segment {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 70px;
        }

        .countdown-num {
            font-family: 'florania', serif;
            font-size: 38px;
            font-weight: 700;
            color: var(--tp-gold);
            line-height: 1;
            margin-bottom: 4px;
        }

        .countdown-lbl {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--tp-muted);
        }

        /* Hero Action Buttons */
        .hero-cta-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 18px;
        }

        .btn-hero-primary {
            background: var(--tp-gold-gradient);
            color: #0d0d0d !important;
            font-weight: 800;
            font-size: 14px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 16px 36px;
            border-radius: 50px;
            box-shadow: 0 4px 25px rgba(212, 175, 55, 0.4);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 35px rgba(212, 175, 55, 0.6);
        }

        .btn-hero-secondary {
            background: linear-gradient(135deg, #c8102e 0%, #900c20 100%);
            color: #ffffff !important;
            font-weight: 800;
            font-size: 14px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 16px 36px;
            border-radius: 50px;
            box-shadow: 0 4px 25px rgba(200, 16, 46, 0.4);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-hero-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 35px rgba(200, 16, 46, 0.6);
        }

        .btn-hero-outline {
            background: transparent;
            color: #ffffff !important;
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 16px 32px;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-hero-outline:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: #fff;
            transform: translateY(-3px);
        }

        /* --- SECTION BASE STYLES --- */
        .page-section-editorial {
            padding: 90px 20px;
            position: relative;
        }

        .section-header-luxury {
            text-align: center;
            max-width: 800px;
            margin: 0 auto 60px;
        }

        .tag-eyebrow {
            display: inline-block;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--tp-gold);
            margin-bottom: 12px;
        }

        .section-heading-luxury {
            font-family: 'florania', serif;
            font-size: clamp(30px, 4vw, 48px);
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 12px;
            letter-spacing: 1.5px;
        }

        .section-desc-luxury {
            font-size: 15px;
            color: #cccccc;
            line-height: 1.7;
        }

        /* --- DIGNITARIES & MAGAZINE LAUNCH SHOWCASE --- */
        .dignitaries-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            max-width: 1100px;
            margin: 0 auto;
        }

        /* UNIFORM 4-SIDED BORDER ON CARDS (NO ONE-SIDED BORDER) */
        .dignitary-card {
            background: linear-gradient(145deg, #181818 0%, #121212 100%);
            border: 1px solid var(--tp-border-gold);
            border-radius: 16px;
            padding: 35px 30px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.6);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            transition: all 0.3s ease;
        }

        .dignitary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8), 0 0 25px rgba(212, 175, 55, 0.2);
            border-color: var(--tp-gold);
        }

        .dignitary-icon-wrap {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: rgba(212, 175, 55, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--tp-gold);
            font-size: 22px;
            margin-bottom: 20px;
            border: 1px solid rgba(212, 175, 55, 0.3);
        }

        .dignitary-role-tag {
            font-size: 11.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--tp-crimson);
            margin-bottom: 6px;
        }

        .dignitary-name {
            font-family: 'florania', serif;
            font-size: 25px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 8px;
        }

        .dignitary-title {
            font-size: 13.5px;
            color: var(--tp-gold-light);
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .dignitary-desc {
            font-size: 13px;
            color: var(--tp-muted);
            line-height: 1.6;
        }

        /* SPECIAL LAUNCH BANNER: UNIFORM BORDER (NO ONE-SIDED BORDER) */
        .special-highlight-banner {
            max-width: 1100px;
            margin: 50px auto 0;
            background: linear-gradient(135deg, rgba(200, 16, 46, 0.15) 0%, rgba(20, 20, 20, 0.95) 100%);
            border: 1px solid var(--tp-border-gold);
            border-radius: 14px;
            padding: 30px 40px;
            display: flex;
            align-items: center;
            gap: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .highlight-banner-icon {
            font-size: 45px;
            color: var(--tp-gold);
            flex-shrink: 0;
        }

        .highlight-banner-text h3 {
            font-family: 'florania', serif;
            font-size: 22px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 6px;
        }

        .highlight-banner-text p {
            font-size: 14px;
            color: #ccc;
            margin: 0;
            line-height: 1.6;
        }

        /* --- TIERS SECTION --- */
        .pricing-section {
            padding: 90px 20px 100px;
            background: #111111;
            position: relative;
            border-top: 1px solid var(--tp-border-subtle);
        }

        .tiers-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 35px;
            max-width: 1100px;
            margin: 0 auto;
            align-items: stretch;
        }

        /* UNIFORM BORDER ON TIER CARDS */
        .tier-card {
            background: #181818;
            border: 1px solid var(--tp-border-subtle);
            border-radius: 18px;
            padding: 40px 32px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 20px 45px rgba(0,0,0,0.7);
            transition: all 0.3s ease;
        }

        .tier-card:hover {
            transform: translateY(-6px);
            border: 1px solid var(--tp-border-gold);
            box-shadow: 0 25px 60px rgba(0,0,0,0.9), 0 0 30px rgba(212, 175, 55, 0.2);
        }

        .tier-card.featured-corporate {
            border: 1px solid var(--tp-gold);
            background: linear-gradient(160deg, #202020 0%, #161616 100%);
            box-shadow: 0 25px 60px rgba(0,0,0,0.8), 0 0 30px rgba(212, 175, 55, 0.25);
        }

        .tier-top-ribbon {
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--tp-gold-gradient);
            color: #0d0d0d;
            font-size: 11.5px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 5px 22px;
            border-radius: 30px;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4);
            white-space: nowrap;
        }

        .tier-header-info {
            text-align: center;
            border-bottom: 1px solid var(--tp-border-subtle);
            padding-bottom: 25px;
            margin-bottom: 25px;
        }

        .tier-tag {
            display: inline-block;
            font-size: 11.5px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--tp-crimson);
            margin-bottom: 8px;
        }

        .tier-title {
            font-family: 'florania', serif;
            font-size: 26px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 15px;
        }

        .tier-price-box {
            display: flex;
            align-items: baseline;
            justify-content: center;
            gap: 6px;
        }

        .tier-currency {
            font-size: 18px;
            font-weight: 700;
            color: var(--tp-gold);
        }

        .tier-amount {
            font-family: 'florania', serif;
            font-size: 46px;
            font-weight: 700;
            color: #ffffff;
        }

        .tier-unit {
            font-size: 13px;
            color: var(--tp-muted);
        }

        .tier-capacity-badge {
            display: inline-block;
            margin-top: 10px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            padding: 4px 14px;
            border-radius: 15px;
            font-size: 12px;
            color: var(--tp-gold-light);
        }

        .tier-perks-list {
            list-style: none;
            padding: 0;
            margin: 0 0 30px;
            flex-grow: 1;
        }

        .tier-perks-list li {
            font-size: 13.5px;
            color: #d1d5db;
            padding: 9px 0;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            line-height: 1.5;
        }

        .tier-perks-list li i {
            color: var(--tp-gold);
            font-size: 14px;
            margin-top: 3px;
            flex-shrink: 0;
        }

        .btn-select-tier {
            width: 100%;
            padding: 16px 20px;
            border-radius: 40px;
            font-weight: 800;
            font-size: 13.5px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .btn-select-tier.btn-gold {
            background: var(--tp-gold-gradient);
            color: #0d0d0d !important;
            box-shadow: 0 4px 20px rgba(212, 175, 55, 0.35);
        }

        .btn-select-tier.btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 30px rgba(212, 175, 55, 0.55);
        }

        .btn-select-tier.btn-outline {
            background: transparent;
            color: #ffffff !important;
            border: 1px solid var(--tp-border-gold);
        }

        .btn-select-tier.btn-outline:hover {
            background: rgba(212, 175, 55, 0.15);
            border-color: var(--tp-gold);
            transform: translateY(-2px);
        }

        /* --- RESERVATION GUARANTEE / CHECKOUT STRIP --- */
        .checkout-banner-strip {
            max-width: 1100px;
            margin: 50px auto 0;
            background: linear-gradient(145deg, #1a1a1a 0%, #141414 100%);
            border: 1px solid var(--tp-border-gold);
            border-radius: 16px;
            padding: 30px 35px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.6);
        }

        .checkout-banner-text h4 {
            font-family: 'florania', serif;
            font-size: 22px;
            color: #ffffff;
            margin-bottom: 6px;
        }

        .checkout-banner-text p {
            font-size: 13.5px;
            color: #aaa;
            margin: 0;
        }

        .btn-proceed-checkout {
            background: var(--tp-gold-gradient);
            color: #0d0d0d !important;
            font-weight: 800;
            font-size: 13.5px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 14px 30px;
            border-radius: 40px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 20px rgba(212, 175, 55, 0.35);
            transition: all 0.25s ease;
        }

        .btn-proceed-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 30px rgba(212, 175, 55, 0.55);
        }

        /* --- EVENT PROGRAMME SCHEDULE --- */
        .schedule-grid {
            max-width: 900px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        /* UNIFORM 4-SIDED BORDER */
        .schedule-card {
            background: #181818;
            border: 1px solid var(--tp-border-subtle);
            border-radius: 12px;
            padding: 22px 28px;
            display: flex;
            align-items: center;
            gap: 25px;
            transition: all 0.25s ease;
        }

        .schedule-card:hover {
            border: 1px solid var(--tp-border-gold);
            background: #202020;
            transform: translateX(4px);
        }

        .schedule-time {
            font-family: 'florania', serif;
            font-size: 17px;
            font-weight: 700;
            color: var(--tp-gold);
            min-width: 140px;
            flex-shrink: 0;
        }

        .schedule-title {
            font-size: 15px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 3px;
        }

        .schedule-desc {
            font-size: 13px;
            color: #999;
            margin: 0;
        }

        /* --- FAQ SECTION --- */
        .faq-wrap {
            max-width: 900px;
            margin: 0 auto;
        }

        .faq-item {
            background: #181818;
            border: 1px solid var(--tp-border-subtle);
            border-radius: 12px;
            margin-bottom: 15px;
            overflow: hidden;
            transition: border-color 0.25s ease;
        }

        .faq-item:hover {
            border: 1px solid var(--tp-border-gold);
        }

        .faq-question {
            padding: 20px 25px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            font-size: 15px;
            color: #fff;
        }

        .faq-answer {
            padding: 0 25px 22px;
            font-size: 13.5px;
            color: #ccc;
            line-height: 1.7;
            display: none;
        }

        @media (max-width: 991px) {
            .dignitaries-grid, .tiers-grid {
                grid-template-columns: 1fr;
            }
            .special-highlight-banner {
                flex-direction: column;
                text-align: center;
                padding: 30px 20px;
            }
            .schedule-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .checkout-banner-strip {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <!-- Main Navigation Bar (Floats over Hero) -->
    <?php include 'includes/new-navbar.php'; ?>

    <!-- EDITORIAL HERO SECTION (STARTS AT TOP: 0) -->
    <section class="dinner-hero">
        <div class="dinner-hero-inner">
            
            <div class="hero-badge-ribbon">
                <span><?= htmlspecialchars($event['hero_badge_text']) ?></span>
            </div>

            <h1 class="dinner-hero-title">
                <?= htmlspecialchars($event['title']) ?>
            </h1>

            <p class="dinner-hero-sub">
                <?= htmlspecialchars($event['subtitle']) ?>
            </p>

            <div class="event-pills-row">
                <div class="event-pill">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Friday, <?= date('j F Y', strtotime($event['event_date'])) ?></span>
                </div>
                <div class="event-pill">
                    <i class="fas fa-clock"></i>
                    <span><?= htmlspecialchars($event['event_time']) ?></span>
                </div>
                <div class="event-pill">
                    <i class="fas fa-map-marker-alt"></i>
                    <span><?= htmlspecialchars($event['venue']) ?></span>
                </div>
            </div>

            <!-- Live Countdown Timer -->
            <div class="countdown-container" id="countdownTimer">
                <div class="countdown-segment">
                    <span class="countdown-num" id="daysVal">--</span>
                    <span class="countdown-lbl">Days</span>
                </div>
                <div class="countdown-segment">
                    <span class="countdown-num" id="hoursVal">--</span>
                    <span class="countdown-lbl">Hours</span>
                </div>
                <div class="countdown-segment">
                    <span class="countdown-num" id="minsVal">--</span>
                    <span class="countdown-lbl">Mins</span>
                </div>
                <div class="countdown-segment">
                    <span class="countdown-num" id="secsVal">--</span>
                    <span class="countdown-lbl">Secs</span>
                </div>
            </div>

            <!-- Hero Action Buttons -->
            <div class="hero-cta-actions">
                <a href="event-checkout.php?tier=corporate_table" class="btn-hero-primary">
                    Book Corporate Table (KES 50,000)
                </a>
                <a href="event-checkout.php?tier=individual" class="btn-hero-secondary">
                    <i class="fas fa-user-tie"></i> Reserve Individual Seat (KES 10,000)
                </a>
                <a href="#tiers-section" class="btn-hero-outline">
                    <i class="fas fa-th-list"></i> View All Packages
                </a>
            </div>

        </div>
    </section>

    <!-- DIGNITARIES & MAGAZINE LAUNCH SHOWCASE -->
    <section class="page-section-editorial" id="dignitaries">
        <div class="container">
            
            <div class="section-header-luxury">
                <span class="tag-eyebrow">Distinguished Leaders & Guests of Honour</span>
                <h2 class="section-heading-luxury">Leading The Conversation</h2>
                <p class="section-desc-luxury">
                    Join Cabinet Advisors, top corporate executives, diplomatic leaders, and civic changemakers for a transformative evening commemorating excellence and national impact.
                </p>
            </div>

            <div class="dignitaries-grid">
                
                <!-- Principal Guest -->
                <div class="dignitary-card">
                    <div>
                        <div class="dignitary-icon-wrap">
                            <i class="fas fa-award"></i>
                        </div>
                        <div class="dignitary-role-tag">Principal Guest</div>
                        <h3 class="dignitary-name"><?= htmlspecialchars($event['principal_guest_name']) ?></h3>
                        <div class="dignitary-title"><?= htmlspecialchars($event['principal_guest_title']) ?></div>
                    </div>
                    <p class="dignitary-desc">
                        Championing systemic change, inclusive governance, and transformative social policy at the highest levels of the Executive Office of the President of Kenya.
                    </p>
                </div>

                <!-- Patron -->
                <div class="dignitary-card">
                    <div>
                        <div class="dignitary-icon-wrap">
                            <i class="fas fa-university"></i>
                        </div>
                        <div class="dignitary-role-tag">Gala Patron</div>
                        <h3 class="dignitary-name"><?= htmlspecialchars($event['patron_name']) ?></h3>
                        <div class="dignitary-title"><?= htmlspecialchars($event['patron_title']) ?></div>
                    </div>
                    <p class="dignitary-desc">
                        A titan of public service, academic scholarship, and pan-African advocacy, guiding Turning Point Magazine's mission to chronicle and amplify positive transformation.
                    </p>
                </div>

            </div>

            <!-- Special Launch Highlight -->
            <div class="special-highlight-banner">
                <div class="highlight-banner-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="highlight-banner-text">
                    <h3><?= htmlspecialchars($event['special_highlight']) ?></h3>
                    <p>
                        Every registered attendee and corporate table delegation receives complimentary physical copies of this collector's print edition, featuring deep investigative features, high-level policy profiles, and visionary leadership essays.
                    </p>
                </div>
            </div>

        </div>
    </section>

    <!-- DYNAMIC PRICING & TIER SELECTION CARDS -->
    <section class="pricing-section" id="tiers-section">
        <div class="container">
            
            <div class="section-header-luxury">
                <span class="tag-eyebrow">Official Reservation Packages</span>
                <h2 class="section-heading-luxury">Tier Privileges & Packages</h2>
                <p class="section-desc-luxury">
                    Secure your delegation's table or reserve individual seats for this premier executive gathering at Tamarind Tree Hotel, Nairobi.
                </p>
            </div>

            <!-- Tier Cards Grid -->
            <div class="tiers-grid">
                <?php foreach ($tiers as $tier): 
                    $isCorporate = ($tier['tier_code'] === 'corporate_table');
                    $perksList = !empty($tier['perks_json']) ? json_decode($tier['perks_json'], true) : [];
                ?>
                <div class="tier-card <?= ($isCorporate ? 'featured-corporate' : '') ?>" id="tierCard_<?= $tier['id'] ?>">
                    
                    <?php if ($isCorporate): ?>
                        <div class="tier-top-ribbon">
                            <i class="fas fa-building me-1"></i> Recommended for Corporates
                        </div>
                    <?php endif; ?>

                    <div class="tier-header-info">
                        <span class="tier-tag"><?= htmlspecialchars($tier['badge_label']) ?></span>
                        <h3 class="tier-title"><?= htmlspecialchars($tier['tier_name']) ?></h3>
                        
                        <div class="tier-price-box">
                            <span class="tier-currency">KES</span>
                            <span class="tier-amount"><?= number_format($tier['price_kes']) ?></span>
                            <span class="tier-unit">/ <?= ($isCorporate ? 'Table of 5' : 'Seat') ?></span>
                        </div>

                        <div class="tier-capacity-badge">
                            <i class="fas fa-chair me-1"></i> <?= $tier['seats_per_unit'] ?> Reserved Seat<?= ($tier['seats_per_unit'] > 1 ? 's' : '') ?> Included
                        </div>
                    </div>

                    <ul class="tier-perks-list">
                        <?php if (!empty($perksList)): ?>
                            <?php foreach ($perksList as $perk): ?>
                                <li><i class="fas fa-check-circle"></i> <span><?= htmlspecialchars($perk) ?></span></li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>

                    <a href="event-checkout.php?tier=<?= urlencode($tier['tier_code']) ?>" class="btn-select-tier <?= ($isCorporate ? 'btn-gold' : 'btn-outline') ?>">
                        <i class="<?= $isCorporate ? 'fas fa-users' : 'fas fa-user' ?>"></i> 
                        Book <?= htmlspecialchars($tier['tier_name']) ?> &rarr;
                    </a>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- VIP Reservation Guarantee Strip -->
            <div class="checkout-banner-strip">
                <div class="checkout-banner-text">
                    <h4>Flexible Corporate & Individual Payment Options</h4>
                    <p>
                        Instant M-Pesa STK push authorization (to any phone line) • Corporate Proforma Invoices with NCBA Bank Kenya details • Licensed Data Controller (Serial No. 03323)
                    </p>
                </div>
                <div>
                    <a href="event-checkout.php" class="btn-proceed-checkout">
                        <span>Proceed to Reservation</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- EVENT PROGRAMME SCHEDULE -->
    <section class="page-section-editorial" id="schedule">
        <div class="container">
            
            <div class="section-header-luxury">
                <span class="tag-eyebrow">The Evening Itinerary</span>
                <h2 class="section-heading-luxury">Gala Programme</h2>
                <p class="section-desc-luxury">
                    A curated evening of executive dining, keynote leadership perspectives, and the unveiling of Turning Point Magazine's flagship print edition.
                </p>
            </div>

            <div class="schedule-grid">
                <div class="schedule-card">
                    <div class="schedule-time">4:00 PM – 5:15 PM</div>
                    <div>
                        <div class="schedule-title">Red Carpet Arrival & Executive Networking Cocktail</div>
                        <p class="schedule-desc">Welcome refreshments, official media portraits, and high-level delegate networking in the Tamarind Ballroom foyer.</p>
                    </div>
                </div>

                <div class="schedule-card">
                    <div class="schedule-time">5:15 PM – 6:00 PM</div>
                    <div>
                        <div class="schedule-title">Opening Address by Gala Patron Prof. Julia Ojiambo</div>
                        <p class="schedule-desc">Setting the evening's context: Transformative pan-African leadership, civic legacy, and institutional mentorship.</p>
                    </div>
                </div>

                <div class="schedule-card">
                    <div class="schedule-time">6:00 PM – 7:15 PM</div>
                    <div>
                        <div class="schedule-title">3-Course Gourmet Dinner & Curated Table Discussions</div>
                        <p class="schedule-desc">An exquisite dining experience crafted by Tamarind Tree Hotel executive chefs, paired with intimate corporate dialogue.</p>
                    </div>
                </div>

                <div class="schedule-card">
                    <div class="schedule-time">7:15 PM – 8:00 PM</div>
                    <div>
                        <div class="schedule-title">Keynote Leadership Address by Hon. Harriet Chiggai</div>
                        <p class="schedule-desc">President's Special Advisor on Women's Rights on policy impact, leadership resilience, and social equity in Kenya.</p>
                    </div>
                </div>

                <div class="schedule-card">
                    <div class="schedule-time">8:00 PM – 8:30 PM</div>
                    <div>
                        <div class="schedule-title">Official Launch of the Third Edition of Turning Point Magazine</div>
                        <p class="schedule-desc">Unveiling ceremony, ceremonial presentation to corporate table delegations, and presentation of commemorative plaques.</p>
                    </div>
                </div>

                <div class="schedule-card">
                    <div class="schedule-time">8:30 PM – 9:00 PM</div>
                    <div>
                        <div class="schedule-title">Closing Remarks & Networking Reception</div>
                        <p class="schedule-desc">Vote of thanks by Malshe Media leadership, photo opportunities with dignitaries, and networking.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- FAQ SECTION -->
    <section class="page-section-editorial" style="background:#111111; border-top:1px solid var(--tp-border-subtle);" id="faq">
        <div class="container">
            <div class="section-header-luxury">
                <span class="tag-eyebrow">Frequently Asked Questions</span>
                <h2 class="section-heading-luxury">Reservation & Gala Information</h2>
                <p class="section-desc-luxury">Everything you need to know about attendance, corporate invoicing, and magazine copies.</p>
            </div>

            <div class="faq-wrap">
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <span>How do Corporate Proforma Invoices work for institutional procurement?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        When booking a Corporate VIP Table on the checkout page, select the "Corporate Invoice" payment tab. The portal will immediately generate an official PDF-ready proforma invoice addressed to your organization with full NCBA Bank Kenya transfer details (and Buy Goods Till 575555). A digital copy is sent to your official email instantly. Once remittance is completed, your table passes and verified QR credentials are authenticated.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <span>Can an accountant or colleague pay via M-Pesa on my behalf?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Yes! On the reservation checkout page, you are explicitly asked to specify which M-Pesa number should receive the STK push prompt. You can enter a finance manager's or director's line, and they will receive the prompt to enter their PIN directly on their phone while your registration is confirmed.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <span>Can we update or submit guest names for our Corporate Table later?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Yes. You can secure your Corporate Table immediately with your primary contact details. Guest names, executive titles, and dietary preferences can be submitted during checkout or updated at any point prior to 1 November 2026 to ensure custom table signage and accreditation badges are prepared accurately.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <span>How and when do we receive our copies of the 3rd Edition Magazine?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Each individual ticket includes 1 complimentary collector's print copy, and each Corporate VIP Table includes 5 copies (one copy per attendee). These will be presented to your delegation during the launch ceremony at the gala.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <span>What is the dress code for The Leadership Dinner 2026?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        The dress code is Black Tie / Elegant Pan-African Attire / Formal Evening Wear.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Global Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Client-side Logic Scripts -->
    <script>
        // Sticky navbar on scroll
        window.addEventListener('scroll', function() {
            const nav = document.getElementById('tp-main-nav');
            if (nav) {
                if (window.scrollY > 80) {
                    nav.classList.add('nav-scrolled');
                } else {
                    nav.classList.remove('nav-scrolled');
                }
            }
        });

        // Countdown Timer to Nov 6, 2026 16:00:00 EAT (UTC+3)
        const targetDate = new Date("2026-11-06T16:00:00+03:00").getTime();

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = targetDate - now;

            if (distance < 0) {
                document.getElementById("daysVal").innerText = "00";
                document.getElementById("hoursVal").innerText = "00";
                document.getElementById("minsVal").innerText = "00";
                document.getElementById("secsVal").innerText = "00";
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("daysVal").innerText = String(days).padStart(2, '0');
            document.getElementById("hoursVal").innerText = String(hours).padStart(2, '0');
            document.getElementById("minsVal").innerText = String(minutes).padStart(2, '0');
            document.getElementById("secsVal").innerText = String(seconds).padStart(2, '0');
        }
        setInterval(updateCountdown, 1000);
        updateCountdown();

        // FAQ Toggle
        function toggleFaq(el) {
            const answer = el.nextElementSibling;
            const icon = el.querySelector('i');
            const isVisible = answer.style.display === 'block';

            document.querySelectorAll('.faq-answer').forEach(a => a.style.display = 'none');
            document.querySelectorAll('.faq-question i').forEach(i => i.className = 'fas fa-chevron-down');

            if (!isVisible) {
                answer.style.display = 'block';
                icon.className = 'fas fa-chevron-up';
            }
        }
    </script>

</body>
</html>
