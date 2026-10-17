<?php
/**
 * Dedicated Event Checkout Page: The Leadership Dinner 2026
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

// Default selected tier from query parameter if present
$selectedTierCode = $_GET['tier'] ?? 'corporate_table';
$defaultTierId = 2;
foreach ($tiers as $t) {
    if ($t['tier_code'] === $selectedTierCode) {
        $defaultTierId = $t['id'];
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Reservation Checkout | <?= htmlspecialchars($event['title']) ?> - Turning Point Magazine</title>
    
    <meta name="description" content="Secure your corporate table or individual delegate pass for The Leadership Dinner 2026 at Tamarind Tree Hotel, Nairobi.">
    <link rel="canonical" href="https://www.turningpointmagazine.africa/event-checkout.php">

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
            --tp-bg-page: #f8f9fa;
            --tp-card-bg: #ffffff;
            --tp-dark: #0f172a;
            --tp-text-dark: #1e293b;
            --tp-text-muted: #64748b;
            --tp-border-light: #e2e8f0;
            --tp-gold: #b45309;
            --tp-gold-bright: #d4af37;
            --tp-gold-light: #fef3c7;
            --tp-gold-border: rgba(212, 175, 55, 0.45);
            --tp-crimson: #c8102e;
            --tp-crimson-light: #fee2e2;
            --tp-green: #00a859;
            --tp-green-light: #f0fdf4;
            --tp-green-border: #bbf7d0;
        }

        html, body {
            background-color: var(--tp-bg-page) !important;
            color: var(--tp-text-dark);
            font-family: 'Poppins', sans-serif;
            margin: 0 !important;
            padding: 0 !important;
            padding-top: 0 !important;
            overflow-x: clip;
        }

        /* --- CLEAN HEADER (NO HERO SECTION) --- */
        .checkout-page-header {
            padding: 25px 20px 20px;
            text-align: center;
            max-width: 900px;
            margin: 10px auto 25px;
            background: transparent !important;
        }

        .checkout-event-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 18px;
            background: #ffffff;
            border: 1px solid #d4af37;
            border-radius: 30px;
            color: #b45309;
            font-size: 11.5px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            box-shadow: 0 2px 10px rgba(212, 175, 55, 0.15);
            margin-bottom: 12px;
        }

        .checkout-title {
            font-family: 'florania', serif;
            font-size: clamp(28px, 4vw, 42px);
            font-weight: 700;
            color: var(--tp-dark);
            letter-spacing: 1px;
            margin-bottom: 10px;
            line-height: 1.2;
        }

        .checkout-subtitle {
            font-size: 14.5px;
            color: var(--tp-text-muted);
            margin: 0 auto;
            max-width: 650px;
            line-height: 1.6;
        }

        .checkout-meta-pills {
            display: inline-flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
            margin-top: 15px;
        }

        .meta-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ffffff;
            border: 1px solid var(--tp-border-light);
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 12.5px;
            color: #475569;
            font-weight: 500;
        }

        .meta-pill i {
            color: var(--tp-crimson);
        }

        /* --- CONTAINER HOLDING THE CARDS (MATCHES PAGE BG & DISAPPEARS INTO BACKGROUND) --- */
        main,
        main.checkout-main-wrap,
        .checkout-main-wrap,
        #eventBookingForm {
            max-width: 1180px;
            margin: 0 auto 80px;
            padding: 0 20px;
            background: var(--tp-bg-page) !important;
            background-color: var(--tp-bg-page) !important;
            box-shadow: none !important;
            border: none !important;
        }

        /* --- CLEAN ON-STYLE CARDS --- */
        .checkout-card {
            background: var(--tp-card-bg);
            border: 1px solid var(--tp-border-light);
            border-radius: 16px;
            padding: 32px 30px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.04);
            margin-bottom: 25px;
            transition: box-shadow 0.25s ease;
        }

        .checkout-card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.07);
        }

        .card-heading-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 16px;
            margin-bottom: 22px;
            border-bottom: 1px solid var(--tp-border-light);
        }

        .card-heading-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #fef3c7;
            color: #b45309;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
            border: 1px solid #fde68a;
        }

        .card-heading-title {
            font-family: 'florania', serif;
            font-size: 19px;
            font-weight: 700;
            color: var(--tp-dark);
            margin: 0;
            letter-spacing: 0.5px;
        }

        .card-heading-sub {
            font-size: 12px;
            color: var(--tp-text-muted);
            margin: 0;
        }

        /* --- TIER SELECTION TILES --- */
        .tier-choice-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .tier-choice-tile {
            background: #ffffff;
            border: 2px solid var(--tp-border-light);
            border-radius: 14px;
            padding: 22px 20px;
            cursor: pointer;
            transition: all 0.25s ease;
            position: relative;
        }

        .tier-choice-tile:hover {
            border-color: #cbd5e1;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
        }

        .tier-choice-tile.active-corporate {
            border-color: #d4af37;
            background: #fffdf7;
            box-shadow: 0 6px 22px rgba(212, 175, 55, 0.18);
        }

        .tier-choice-tile.active-individual {
            border-color: #c8102e;
            background: #fffafa;
            box-shadow: 0 6px 22px rgba(200, 16, 46, 0.15);
        }

        .tier-radio-circle {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
        }

        .tier-choice-tile.active-corporate .tier-radio-circle {
            border-color: #d4af37;
        }

        .tier-choice-tile.active-corporate .tier-radio-circle::after {
            content: '';
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #d4af37;
        }

        .tier-choice-tile.active-individual .tier-radio-circle {
            border-color: #c8102e;
        }

        .tier-choice-tile.active-individual .tier-radio-circle::after {
            content: '';
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #c8102e;
        }

        .tier-badge-label {
            display: inline-block;
            font-size: 10.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--tp-crimson);
            margin-bottom: 6px;
        }

        .tier-tile-name {
            font-family: 'florania', serif;
            font-size: 19px;
            font-weight: 700;
            color: var(--tp-dark);
            margin-bottom: 6px;
        }

        .tier-tile-price {
            font-family: 'florania', serif;
            font-size: 26px;
            font-weight: 700;
            color: #b45309;
            margin-bottom: 8px;
        }

        .tier-tile-perk {
            font-size: 12px;
            color: #64748b;
            line-height: 1.4;
        }

        /* --- QUANTITY STEPPER --- */
        .stepper-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8fafc;
            border: 1px solid var(--tp-border-light);
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 10px;
        }

        .qty-control-group {
            display: inline-flex;
            align-items: center;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            overflow: hidden;
            background: #ffffff;
        }

        .qty-btn {
            background: #ffffff;
            border: none;
            color: #1e293b;
            padding: 8px 16px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .qty-btn:hover {
            background: #f1f5f9;
            color: var(--tp-crimson);
        }

        .qty-display {
            width: 50px;
            background: transparent;
            border: none;
            color: #0f172a;
            text-align: center;
            font-size: 16px;
            font-weight: 700;
        }

        /* --- FORM CONTROLS --- */
        .form-label-clean {
            font-size: 12.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #334155;
            margin-bottom: 7px;
            display: block;
        }

        .form-control-clean {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #0f172a !important;
            border-radius: 8px !important;
            padding: 12px 16px !important;
            font-size: 14px !important;
            font-family: 'Poppins', sans-serif !important;
            transition: all 0.2s ease !important;
        }

        .form-control-clean:focus {
            border-color: #d4af37 !important;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.25) !important;
            outline: none !important;
        }

        .form-control-clean::placeholder {
            color: #94a3b8;
        }

        /* --- PAYMENT METHOD SELECTOR --- */
        .payment-method-tabs {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 22px;
        }

        .pay-tab-btn {
            background: #ffffff;
            border: 2px solid var(--tp-border-light);
            border-radius: 12px;
            padding: 18px 20px;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .pay-tab-btn:hover {
            border-color: #cbd5e1;
        }

        .pay-tab-btn.active-mpesa {
            border-color: #00a859;
            background: #f0fdf4;
            box-shadow: 0 4px 18px rgba(0, 168, 89, 0.15);
        }

        .pay-tab-btn.active-invoice {
            border-color: #d4af37;
            background: #fffdf5;
            box-shadow: 0 4px 18px rgba(212, 175, 55, 0.15);
        }

        .pay-tab-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 4px;
        }

        .pay-tab-desc {
            font-size: 12px;
            color: #64748b;
            margin: 0;
        }

        /* --- MPESA SPECIFICATION BOX --- */
        .mpesa-spec-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 22px;
            margin-bottom: 22px;
        }

        .mpesa-phone-option {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
        }

        .mpesa-phone-option:hover {
            border-color: #00a859;
        }

        .mpesa-phone-option.selected {
            border-color: #00a859;
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 168, 89, 0.15);
        }

        .mpesa-phone-option input[type="radio"] {
            accent-color: #00a859;
            width: 18px;
            height: 18px;
        }

        /* --- OFFICIAL INVOICE PAYMENT DETAILS BOX --- */
        .invoice-spec-box {
            background: #fffdf5;
            border: 1px solid #fde68a;
            border-radius: 12px;
            padding: 22px;
            margin-bottom: 22px;
        }

        .remittance-table-clean {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
            margin-bottom: 18px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            font-size: 13.5px;
        }

        .remittance-table-clean th {
            background: #f1f5f9;
            padding: 10px 14px;
            text-align: left;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #334155;
            border-bottom: 1px solid #e2e8f0;
        }

        .remittance-table-clean td {
            padding: 10px 14px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }

        .remittance-table-clean tr:last-child td {
            border-bottom: none;
        }

        .remittance-table-clean td.label-col {
            width: 45%;
            font-weight: 600;
            color: #0f172a;
        }

        .remittance-table-clean td.value-col {
            font-family: monospace;
            font-size: 14px;
            color: #b45309;
            font-weight: 700;
        }

        .till-badge-box {
            background: #ffffff;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        /* --- GUEST ACCORDION --- */
        .guest-accordion-wrap {
            background: #f8fafc;
            border: 1px solid var(--tp-border-light);
            border-radius: 12px;
            overflow: hidden;
            margin-top: 15px;
            margin-bottom: 20px;
        }

        .guest-accordion-header {
            padding: 16px 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f1f5f9;
        }

        .guest-accordion-header:hover {
            background: #e2e8f0;
        }

        .guest-accordion-body {
            padding: 20px;
            display: none;
            background: #ffffff;
        }

        .guest-row {
            display: grid;
            grid-template-columns: 35px 1.5fr 1.5fr 1.5fr;
            gap: 10px;
            align-items: center;
            margin-bottom: 12px;
        }

        .guest-num-badge {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #f1f5f9;
            color: #b45309;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 12px;
            border: 1px solid #cbd5e1;
        }

        /* --- ORDER SUMMARY SIDEBAR (STICKY ON PC UNTIL CONTAINER EXITS) --- */
        .order-summary-sidebar {
            background: #ffffff;
            border: 1px solid var(--tp-border-light);
            border-radius: 16px;
            padding: 30px 24px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.04);
        }

        @media (min-width: 992px) {
            .checkout-main-wrap .row {
                display: flex;
                align-items: stretch;
            }
            .checkout-main-wrap .col-lg-4 {
                position: relative;
                height: auto;
            }
            .order-summary-sidebar {
                position: -webkit-sticky !important;
                position: sticky !important;
                top: 110px !important;
                z-index: 90 !important;
            }
        }

        .summary-header-title {
            font-family: 'florania', serif;
            font-size: 21px;
            font-weight: 700;
            color: var(--tp-dark);
            padding-bottom: 14px;
            border-bottom: 1px solid var(--tp-border-light);
            margin-bottom: 18px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 13.5px;
            color: #475569;
            margin-bottom: 11px;
        }

        .summary-row.bold-row {
            color: #0f172a;
            font-weight: 600;
        }

        .summary-total-divider {
            border-top: 1px solid var(--tp-border-light);
            border-bottom: 1px solid var(--tp-border-light);
            padding: 16px 0;
            margin: 18px 0;
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }

        .total-label-text {
            font-size: 13.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0f172a;
        }

        .total-amount-display {
            font-family: 'florania', serif;
            font-size: 32px;
            font-weight: 700;
            color: #b45309;
        }

        .btn-submit-action {
            width: 100%;
            padding: 16px 20px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 14px;
            letter-spacing: 1px;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit-action.mpesa {
            background: linear-gradient(135deg, #00a859 0%, #00753d 100%);
            color: #ffffff;
            box-shadow: 0 4px 18px rgba(0, 168, 89, 0.35);
        }

        .btn-submit-action.invoice {
            background: linear-gradient(135deg, #d4af37 0%, #aa8421 100%);
            color: #0d0d0d;
            box-shadow: 0 4px 18px rgba(212, 175, 55, 0.35);
        }

        .btn-submit-action:hover {
            transform: translateY(-2px);
            opacity: 0.95;
        }

        /* --- WAITING MODAL --- */
        .tp-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(8px);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .tp-modal-box {
            background: #ffffff;
            border: 2px solid #d4af37;
            border-radius: 18px;
            max-width: 480px;
            width: 100%;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.25);
            color: #1e293b;
        }

        .spinner-gold {
            width: 55px;
            height: 55px;
            border: 4px solid #fef3c7;
            border-top-color: #d4af37;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 991px) {
            body {
                padding-top: 0 !important;
            }
            .tier-choice-grid {
                grid-template-columns: 1fr;
            }
            .payment-method-tabs {
                grid-template-columns: 1fr;
            }
            .order-summary-sidebar {
                position: static;
                margin-top: 25px;
            }
            .guest-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- Main Navigation Bar -->
    <?php include 'includes/new-navbar.php'; ?>

    <!-- CLEAN PAGE HEADER (NO HERO SECTION) -->
    <header class="checkout-page-header">
        <div class="checkout-event-badge">
            <i class="fas fa-award"></i>
            <span>Official Event Accreditation</span>
        </div>

        <h1 class="checkout-title">
            <?= htmlspecialchars($event['title']) ?>
        </h1>

        <p class="checkout-subtitle">
            Secure your delegation table or individual pass for an evening of transformative leadership, executive networking, and the 3rd Edition magazine launch.
        </p>

        <div class="checkout-meta-pills">
            <div class="meta-pill">
                <i class="fas fa-calendar-alt"></i>
                <span>Friday, <?= date('j F Y', strtotime($event['event_date'])) ?></span>
            </div>
            <div class="meta-pill">
                <i class="fas fa-clock"></i>
                <span><?= htmlspecialchars($event['event_time']) ?></span>
            </div>
            <div class="meta-pill">
                <i class="fas fa-map-marker-alt"></i>
                <span><?= htmlspecialchars($event['venue']) ?></span>
            </div>
        </div>
    </header>

    <!-- MAIN CHECKOUT CONTENT -->
    <main class="checkout-main-wrap">
        <form id="eventBookingForm">
            <input type="hidden" name="tier_id" id="selectedTierIdInput" value="<?= $defaultTierId ?>">
            <input type="hidden" name="payment_method" id="selectedPaymentMethodInput" value="mpesa_stk">

            <div class="row">
                <!-- Left Column: Form Fields & Selections -->
                <div class="col-lg-8">
                    
                    <!-- 1. TIER SELECTION TILE -->
                    <div class="checkout-card">
                        <div class="card-heading-wrap">
                            <div class="card-heading-icon"><i class="fas fa-ticket-alt"></i></div>
                            <div>
                                <h3 class="card-heading-title">1. Select Reservation Package</h3>
                                <p class="card-heading-sub">Choose a corporate delegation table of 5 or an individual delegate pass</p>
                            </div>
                        </div>

                        <div class="tier-choice-grid">
                            <?php foreach ($tiers as $tier): 
                                $isCorp = ($tier['tier_code'] === 'corporate_table');
                                $isSelected = ($tier['id'] == $defaultTierId);
                                $activeClass = $isSelected ? ($isCorp ? 'active-corporate' : 'active-individual') : '';
                            ?>
                            <div class="tier-choice-tile <?= $activeClass ?>" 
                                 id="tierTile_<?= $tier['id'] ?>"
                                 data-is-corp="<?= $isCorp ? '1' : '0' ?>"
                                 onclick="selectTierById(<?= $tier['id'] ?>, <?= $isCorp ? 'true' : 'false' ?>)">
                                <div class="tier-radio-circle"></div>
                                <span class="tier-badge-label"><?= htmlspecialchars($tier['badge_label']) ?></span>
                                <h4 class="tier-tile-name"><?= htmlspecialchars($tier['tier_name']) ?></h4>
                                <div class="tier-tile-price">
                                    KES <?= number_format($tier['price_kes']) ?>
                                    <small style="font-size:12px; color:#64748b; font-family:'Poppins',sans-serif;">/ <?= $isCorp ? 'Table of 5' : 'Seat' ?></small>
                                </div>
                                <div class="tier-tile-perk">
                                    <i class="fas fa-check-circle text-success me-1"></i> <?= $tier['seats_per_unit'] ?> Reserved Seat<?= $tier['seats_per_unit'] > 1 ? 's' : '' ?> + <?= $tier['seats_per_unit'] ?> Copies of 3rd Edition
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Quantity Stepper -->
                        <div class="stepper-box">
                            <div>
                                <div style="font-size: 13.5px; font-weight: 700; color: #0f172a;">Quantity to Reserve:</div>
                                <div style="font-size: 12px; color: var(--tp-text-muted);" id="qtyLabelSub">Number of Corporate Tables</div>
                            </div>
                            <div class="qty-control-group">
                                <button type="button" class="qty-btn" onclick="adjustQty(-1)"><i class="fas fa-minus"></i></button>
                                <input type="number" name="quantity" id="qtyInput" class="qty-display" value="1" min="1" max="10" readonly>
                                <button type="button" class="qty-btn" onclick="adjustQty(1)"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- 2. CONTACT & DELEGATION INFORMATION -->
                    <div class="checkout-card">
                        <div class="card-heading-wrap">
                            <div class="card-heading-icon"><i class="fas fa-id-card"></i></div>
                            <div>
                                <h3 class="card-heading-title">2. Organization & Contact Details</h3>
                                <p class="card-heading-sub">Primary delegate or coordinator receiving passes and accreditation</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-12" id="orgNameCol">
                                <label class="form-label-clean">
                                    Organization / Company Name <span class="text-danger" id="orgReqStar">*</span>
                                    <small style="text-transform:none; color:var(--tp-text-muted); font-weight:normal;">(Printed on custom VIP Table signage)</small>
                                </label>
                                <input type="text" name="organization_name" id="orgNameInput" class="form-control form-control-clean" placeholder="e.g. Kenya Commercial Bank / UN Women / Safaricom">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-clean">Primary Contact Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="contact_name" id="contactNameInput" class="form-control form-control-clean" placeholder="e.g. Dr. Amina Mohamed" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-clean">Official Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="contact_email" id="contactEmailInput" class="form-control form-control-clean" placeholder="amina@organization.org" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label-clean">Primary Contact Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" name="contact_phone" id="contactPhoneInput" class="form-control form-control-clean" placeholder="e.g. 0712345678 or 254712345678" required oninput="handleContactPhoneChange(this.value)">
                                <small style="font-size:12px; color:#64748b;">Used for reservation confirmation notices and event coordination.</small>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label-clean">Dietary or Special Seating Notes <small style="text-transform:none; color:#64748b; font-weight:normal;">(Optional)</small></label>
                                <textarea name="dietary_notes" rows="2" class="form-control form-control-clean" placeholder="e.g. 2 Vegetarian meals, 1 Halal, wheelchair access required."></textarea>
                            </div>
                        </div>

                        <!-- Optional Corporate Table Guest Assignment Accordion -->
                        <div class="guest-accordion-wrap" id="guestAccordionWrap">
                            <div class="guest-accordion-header" onclick="toggleGuestAccordion()">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <i class="fas fa-users-cog text-warning"></i>
                                    <div>
                                        <strong style="font-size: 13.5px; color:#0f172a;">Assign Delegation Guest Names</strong>
                                        <small style="display:block; font-size:11.5px; color:#64748b;">Optional — guest names and dietary needs can also be submitted later</small>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-down text-muted" id="guestChevron"></i>
                            </div>
                            <div class="guest-accordion-body" id="guestAccordionBody">
                                <p style="font-size:12px; color:#64748b; margin-bottom:12px;">Enter up to 5 guest details for your delegation's custom table seating cards:</p>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <div class="guest-row">
                                    <div class="guest-num-badge"><?= $i ?></div>
                                    <div>
                                        <input type="text" name="guest_names[]" class="form-control form-control-clean form-control-sm" placeholder="Guest <?= $i ?> Full Name">
                                    </div>
                                    <div>
                                        <input type="email" name="guest_emails[]" class="form-control form-control-clean form-control-sm" placeholder="Email Address">
                                    </div>
                                    <div>
                                        <input type="text" name="guest_dietary[]" class="form-control form-control-clean form-control-sm" placeholder="Dietary Needs">
                                    </div>
                                </div>
                                <?php endfor; ?>
                            </div>
                        </div>

                    </div>

                    <!-- 3. PAYMENT METHOD & SPECIFIC MPESA LINE -->
                    <div class="checkout-card">
                        <div class="card-heading-wrap">
                            <div class="card-heading-icon"><i class="fas fa-credit-card"></i></div>
                            <div>
                                <h3 class="card-heading-title">3. Payment & Settlement Options</h3>
                                <p class="card-heading-sub">Instant M-Pesa STK push or Corporate Proforma Invoice</p>
                            </div>
                        </div>

                        <!-- Payment Tabs -->
                        <div class="payment-method-tabs">
                            <div class="pay-tab-btn active-mpesa" id="tabBtnMpesa" onclick="switchPaymentMethod('mpesa_stk')">
                                <div class="pay-tab-title">
                                    <i class="fas fa-mobile-alt text-success"></i>
                                    <span>M-Pesa Express</span>
                                </div>
                                <p class="pay-tab-desc">Instant STK Push prompt to your phone</p>
                            </div>

                            <div class="pay-tab-btn" id="tabBtnInvoice" onclick="switchPaymentMethod('corporate_invoice')">
                                <div class="pay-tab-title">
                                    <i class="fas fa-file-invoice-dollar text-warning"></i>
                                    <span>Corporate Invoice</span>
                                </div>
                                <p class="pay-tab-desc">Official proforma invoice for bank wire</p>
                            </div>
                        </div>

                        <!-- TAB CONTENT 1: M-PESA STK WITH EXPLICIT PHONE NUMBER PROMPT -->
                        <div id="mpesaContentBox" class="mpesa-spec-box">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                                <i class="fas fa-shield-alt text-success" style="font-size: 18px;"></i>
                                <strong style="color: #0f172a; font-size: 14.5px;">Specify M-Pesa Payment Number</strong>
                            </div>
                            <p style="font-size: 12.5px; color: #475569; margin-bottom: 14px; line-height: 1.5;">
                                Which Safaricom line should receive the M-Pesa STK Push prompt to enter the PIN? You can use your contact phone or specify a different number (e.g. an accountant or company director).
                            </p>

                            <!-- Option A: Use Contact Phone -->
                            <div class="mpesa-phone-option selected" id="optUseContact" onclick="selectMpesaOption('contact')">
                                <input type="radio" name="mpesa_choice" id="radioUseContact" value="contact" checked>
                                <div>
                                    <strong style="color: #0f172a; font-size: 13.5px;">Use My Primary Contact Phone</strong>
                                    <div style="font-size: 12px; color: #b45309;" id="displayContactPhonePreview">(Enter contact phone above)</div>
                                </div>
                            </div>

                            <!-- Option B: Specify Different Phone -->
                            <div class="mpesa-phone-option" id="optUseCustom" onclick="selectMpesaOption('custom')">
                                <input type="radio" name="mpesa_choice" id="radioUseCustom" value="custom">
                                <div>
                                    <strong style="color: #0f172a; font-size: 13.5px;">Pay Using a Different M-Pesa Line</strong>
                                    <div style="font-size: 12px; color: #64748b;">Send the PIN prompt to a different Safaricom number</div>
                                </div>
                            </div>

                            <!-- Explicit M-Pesa Input Field -->
                            <div class="mt-3" id="customMpesaInputWrap" style="display: none;">
                                <label class="form-label-clean">
                                    <i class="fas fa-phone-alt me-1 text-success"></i> M-Pesa Authorization Phone Number <span class="text-danger">*</span>
                                </label>
                                <input type="tel" name="mpesa_payment_phone" id="mpesaPaymentPhoneInput" class="form-control form-control-clean" placeholder="e.g. 0712 345 678 or 254712345678">
                                <small style="font-size: 12px; color: #64748b; margin-top: 4px; display: block;">
                                    This designated number will receive the prompt: <em>"Do you want to pay KES [Amount] to Malshe Media?"</em>
                                </small>
                            </div>
                        </div>

                        <!-- TAB CONTENT 2: CORPORATE PROFORMA INVOICE DETAILS -->
                        <div id="invoiceContentBox" class="invoice-spec-box" style="display: none;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                <i class="fas fa-university text-warning" style="font-size: 18px;"></i>
                                <strong style="color: #0f172a; font-size: 14.5px;">PAYMENT DETAILS</strong>
                            </div>
                            <p style="font-size: 12.5px; color: #475569; margin-bottom: 12px;">
                                Please remit your entry or additional guest payments using the details below:
                            </p>

                            <!-- NCBA Bank Transfer Table -->
                            <table class="remittance-table-clean">
                                <thead>
                                    <tr>
                                        <th>Bank Transfer Details</th>
                                        <th>Information</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="label-col">Bank Name</td>
                                        <td class="value-col">NCBA Bank Kenya Plc</td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">Account Name</td>
                                        <td class="value-col">Malshe Media</td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">Account No.</td>
                                        <td class="value-col">1000145594</td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">Branch Code</td>
                                        <td class="value-col">125</td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">Bank Code</td>
                                        <td class="value-col">07000</td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">SWIFT Code</td>
                                        <td class="value-col">CBAFKENX</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Buy Goods Till Highlight -->
                            <div class="till-badge-box">
                                <div>
                                    <strong style="color: #0f172a; font-size: 13px;"><i class="fas fa-mobile-alt text-success me-1"></i> Mobile Money (M-Pesa) & Contact:</strong>
                                    <div style="font-size: 12px; color: #64748b; margin-top: 2px;">Use M-Pesa Buy Goods & Services Till</div>
                                </div>
                                <div style="text-align: right;">
                                    <span style="font-size: 11px; text-transform: uppercase; color: #64748b; display: block;">Till Number</span>
                                    <span style="font-family: monospace; font-size: 18px; font-weight: 800; color: #00a859;">575555</span>
                                </div>
                            </div>

                            <p style="font-size: 12px; color: #64748b; margin-top: 12px; margin-bottom: 0;">
                                <i class="fas fa-info-circle me-1"></i> An official proforma invoice with your reference number will be generated immediately and emailed to your organization.
                            </p>
                        </div>

                        <!-- Data Protection Notice Checkbox -->
                        <div class="form-check mt-3 mb-1">
                            <input class="form-check-input" type="checkbox" name="data_consent" id="dataConsentCheck" value="1" required checked style="accent-color: #00a859;">
                            <label class="form-check-label" for="dataConsentCheck" style="font-size: 12px; color: #475569;">
                                I consent to data processing for event accreditation by Malshe Media (Data Controller Serial No. 03323, ID: 454-5788-A9FE under the Kenya Data Protection Act).
                            </label>
                        </div>

                    </div>

                </div>

                <!-- Right Column: Live Order Summary & Submit -->
                <div class="col-lg-4">
                    <div class="order-summary-sidebar">
                        <h4 class="summary-header-title">Reservation Summary</h4>

                        <div class="summary-row bold-row">
                            <span id="sumTierName">Corporate VIP Table</span>
                            <span id="sumUnitPrice" style="color:#b45309;">KES 50,000</span>
                        </div>

                        <div class="summary-row">
                            <span>Quantity:</span>
                            <span id="sumQtyText">1 Table</span>
                        </div>

                        <div class="summary-row">
                            <span>Total Reserved Seats:</span>
                            <span id="sumSeatsText" style="color: #b45309; font-weight: 600;">5 Seats</span>
                        </div>

                        <div class="summary-row">
                            <span>3rd Edition Copies:</span>
                            <span id="sumCopiesText">5 Collector Copies</span>
                        </div>

                        <div class="summary-row">
                            <span>3-Course Gourmet Dinner:</span>
                            <span style="color: #00a859; font-weight: 600;"><i class="fas fa-check"></i> Included</span>
                        </div>

                        <div class="summary-total-divider">
                            <span class="total-label-text">Total Payable</span>
                            <span class="total-amount-display" id="sumTotalAmount">KES 50,000</span>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-submit-action mpesa" id="submitOrderBtn">
                            <i class="fas fa-lock"></i>
                            <span id="submitBtnText">Pay KES 50,000 via M-Pesa</span>
                        </button>

                        <div style="text-align: center; margin-top: 15px; font-size: 11.5px; color: #64748b;">
                            <i class="fas fa-shield-alt text-success me-1"></i> Official Turning Point Magazine Africa Portal
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </main>

    <!-- STATUS WAITING MODAL -->
    <div class="tp-modal-overlay" id="statusModal">
        <div class="tp-modal-box">
            <div class="spinner-gold" id="modalSpinner"></div>
            <h3 style="font-family:'florania',serif; font-size: 22px; color: #0f172a; margin-bottom: 8px;" id="modalTitle">
                Awaiting M-Pesa Authorization
            </h3>
            <p style="font-size: 13.5px; color: #475569; line-height: 1.5; margin-bottom: 18px;" id="modalBody">
                We have sent an M-Pesa prompt to your phone. Please enter your PIN to authorize the payment. Do not refresh this page.
            </p>
            <div style="font-family: monospace; font-size: 15px; color: #b45309; font-weight: 700; margin-bottom: 18px;" id="modalRefDisplay"></div>
            
            <button type="button" class="btn btn-sm btn-outline-secondary" id="manualCheckBtn" onclick="pollPaymentStatus()" style="border-radius:20px; padding: 6px 18px; font-size:12px;">
                <i class="fas fa-sync-alt me-1"></i> Check Payment Status
            </button>
        </div>
    </div>

    <!-- Global Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Client-side Logic Script -->
    <script>
        const tiersData = <?= json_encode($tiers) ?>;
        let currentTierId = <?= $defaultTierId ?>;
        let currentPaymentMethod = 'mpesa_stk';
        let currentBookingRef = null;
        let currentToken = null;
        let pollInterval = null;
        let mpesaOption = 'contact';

        function handleContactPhoneChange(val) {
            const preview = document.getElementById('displayContactPhonePreview');
            if (val && val.trim().length > 0) {
                preview.innerText = `(${val.trim()})`;
            } else {
                preview.innerText = '(Enter contact phone above)';
            }
        }

        function selectMpesaOption(opt) {
            mpesaOption = opt;
            const optContact = document.getElementById('optUseContact');
            const optCustom = document.getElementById('optUseCustom');
            const radioContact = document.getElementById('radioUseContact');
            const radioCustom = document.getElementById('radioUseCustom');
            const customWrap = document.getElementById('customMpesaInputWrap');
            const customInput = document.getElementById('mpesaPaymentPhoneInput');

            if (opt === 'contact') {
                optContact.classList.add('selected');
                optCustom.classList.remove('selected');
                radioContact.checked = true;
                customWrap.style.display = 'none';
                customInput.required = false;
            } else {
                optCustom.classList.add('selected');
                optContact.classList.remove('selected');
                radioCustom.checked = true;
                customWrap.style.display = 'block';
                customInput.required = true;
                customInput.focus();
            }
        }

        function switchPaymentMethod(method) {
            currentPaymentMethod = method;
            document.getElementById('selectedPaymentMethodInput').value = method;

            const tabMpesa = document.getElementById('tabBtnMpesa');
            const tabInvoice = document.getElementById('tabBtnInvoice');
            const boxMpesa = document.getElementById('mpesaContentBox');
            const boxInvoice = document.getElementById('invoiceContentBox');
            const submitBtn = document.getElementById('submitOrderBtn');
            const submitText = document.getElementById('submitBtnText');

            if (method === 'mpesa_stk') {
                tabMpesa.className = 'pay-tab-btn active-mpesa';
                tabInvoice.className = 'pay-tab-btn';
                boxMpesa.style.display = 'block';
                boxInvoice.style.display = 'none';
                submitBtn.className = 'btn-submit-action mpesa';
                updateCalculations();
            } else {
                tabInvoice.className = 'pay-tab-btn active-invoice';
                tabMpesa.className = 'pay-tab-btn';
                boxInvoice.style.display = 'block';
                boxMpesa.style.display = 'none';
                submitBtn.className = 'btn-submit-action invoice';
                submitText.innerText = 'Generate Official Corporate Proforma Invoice';
            }
        }

        function selectTierById(tierId, isCorp) {
            currentTierId = tierId;
            document.getElementById('selectedTierIdInput').value = tierId;

            document.querySelectorAll('.tier-choice-tile').forEach(t => {
                t.classList.remove('active-corporate', 'active-individual');
            });
            const activeTile = document.getElementById('tierTile_' + tierId);
            if (activeTile) {
                activeTile.classList.add(isCorp ? 'active-corporate' : 'active-individual');
            }

            updateCalculations();
        }

        function adjustQty(delta) {
            const input = document.getElementById('qtyInput');
            let current = parseInt(input.value) || 1;
            let next = Math.max(1, Math.min(10, current + delta));
            input.value = next;
            updateCalculations();
        }

        function updateCalculations() {
            const tier = tiersData.find(t => t.id == currentTierId) || tiersData[0];
            const qty = parseInt(document.getElementById('qtyInput').value) || 1;
            const unitPrice = parseFloat(tier.price_kes);
            const total = qty * unitPrice;
            const isCorp = (tier.tier_code === 'corporate_table');
            const seatsPerUnit = parseInt(tier.seats_per_unit);
            const totalSeats = qty * seatsPerUnit;

            document.getElementById('qtyLabelSub').innerText = isCorp ? 'Number of Corporate Tables' : 'Number of Individual Passes';
            document.getElementById('sumTierName').innerText = tier.tier_name;
            document.getElementById('sumUnitPrice').innerText = `KES ${unitPrice.toLocaleString()}`;
            document.getElementById('sumQtyText').innerText = `${qty} ${isCorp ? 'Table' + (qty>1?'s':'') : 'Seat' + (qty>1?'s':'')}`;
            document.getElementById('sumSeatsText').innerText = `${totalSeats} Confirmed Seat${totalSeats>1?'s':''}`;
            document.getElementById('sumCopiesText').innerText = `${totalSeats} Physical Collector Copies`;
            document.getElementById('sumTotalAmount').innerText = `KES ${total.toLocaleString()}`;

            const orgStar = document.getElementById('orgReqStar');
            const orgInput = document.getElementById('orgNameInput');
            const guestWrap = document.getElementById('guestAccordionWrap');

            if (isCorp) {
                orgStar.style.display = 'inline';
                orgInput.required = true;
                guestWrap.style.display = 'block';
            } else {
                orgStar.style.display = 'none';
                orgInput.required = false;
                guestWrap.style.display = 'none';
            }

            if (currentPaymentMethod === 'mpesa_stk') {
                document.getElementById('submitBtnText').innerText = `Pay KES ${total.toLocaleString()} via M-Pesa`;
            } else {
                document.getElementById('submitBtnText').innerText = 'Generate Official Corporate Proforma Invoice';
            }
        }

        function toggleGuestAccordion() {
            const body = document.getElementById('guestAccordionBody');
            const chevron = document.getElementById('guestChevron');
            if (body.style.display === 'block') {
                body.style.display = 'none';
                chevron.className = 'fas fa-chevron-down text-muted';
            } else {
                body.style.display = 'block';
                chevron.className = 'fas fa-chevron-up text-muted';
            }
        }

        updateCalculations();

        document.getElementById('eventBookingForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const submitBtn = document.getElementById('submitOrderBtn');
            const originalBtnHtml = submitBtn.innerHTML;

            if (currentPaymentMethod === 'mpesa_stk' && mpesaOption === 'custom') {
                const customPhone = document.getElementById('mpesaPaymentPhoneInput').value.trim();
                if (!customPhone) {
                    alert('Please enter the M-Pesa phone number that will authorize this payment.');
                    document.getElementById('mpesaPaymentPhoneInput').focus();
                    return;
                }
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing Reservation...';

            const formData = new FormData(form);

            if (mpesaOption === 'contact') {
                formData.set('mpesa_payment_phone', formData.get('contact_phone'));
            }

            try {
                const response = await fetch('api/event_initiate_booking.php', {
                    method: 'POST',
                    body: formData
                });

                const res = await response.json();

                if (res.status === 'success') {
                    currentBookingRef = res.booking_reference;
                    currentToken = res.qr_token;

                    if (res.payment_method === 'corporate_invoice') {
                        window.location.href = res.redirect_url;
                    } else {
                        document.getElementById('modalRefDisplay').innerText = `Booking Reference: ${currentBookingRef}`;
                        document.getElementById('statusModal').style.display = 'flex';
                        startPolling();
                    }
                } else {
                    alert(res.message || 'Error initiating booking. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHtml;
                }
            } catch (err) {
                console.error(err);
                alert('Connection error occurred while processing booking. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }
        });

        function startPolling() {
            if (pollInterval) clearInterval(pollInterval);
            pollInterval = setInterval(pollPaymentStatus, 3000);
        }

        async function pollPaymentStatus() {
            if (!currentBookingRef) return;

            try {
                const res = await fetch(`api/event_payment_status.php?ref=${encodeURIComponent(currentBookingRef)}&token=${encodeURIComponent(currentToken)}`);
                const data = await res.json();

                if (data.status === 'paid') {
                    clearInterval(pollInterval);
                    document.getElementById('modalSpinner').style.display = 'none';
                    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-check-circle text-success"></i> Payment Confirmed!';
                    document.getElementById('modalBody').innerText = `Thank you! M-Pesa Receipt: ${data.mpesa_receipt || 'Confirmed'}. Redirecting to your official event pass...`;
                    setTimeout(() => {
                        window.location.href = data.redirect_url;
                    }, 1500);
                } else if (data.status === 'failed') {
                    clearInterval(pollInterval);
                    document.getElementById('modalSpinner').style.display = 'none';
                    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-times-circle text-danger"></i> Payment Unsuccessful';
                    document.getElementById('modalBody').innerText = data.message || 'Payment prompt was cancelled or timed out. Please try again.';
                    document.getElementById('manualCheckBtn').innerText = 'Close & Try Again';
                    document.getElementById('manualCheckBtn').onclick = function() {
                        document.getElementById('statusModal').style.display = 'none';
                        location.reload();
                    };
                }
            } catch (err) {
                console.error("Polling error:", err);
            }
        }
    </script>
</body>
</html>
