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

    <!-- Paystack Inline JS -->
    <script src="https://js.paystack.co/v1/inline.js"></script>

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

        /* --- 3. PAYMENT METHOD (PAYSTACK BOX) --- */
        .paystack-option-box {
            background: #f8fafc;
            border: 1px solid var(--tp-border-light);
            border-radius: 14px;
            padding: 24px 22px;
            margin-bottom: 18px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        }

        .paystack-option-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 14px;
            padding-bottom: 14px;
            border-bottom: 1px solid #e2e8f0;
        }

        .paystack-badge {
            background: rgba(200, 16, 46, 0.08);
            border: 1px solid rgba(200, 16, 46, 0.25);
            color: var(--tp-crimson);
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
        }

        .paystack-brand-title {
            font-size: 14.5px;
            color: #0f172a;
            font-weight: 700;
        }

        .paystack-trust-badge {
            font-size: 12px;
            color: #00a859;
            font-weight: 600;
            display: flex;
            align-items: center;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .paystack-desc {
            font-size: 13.5px;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .paystack-channels-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 16px;
        }

        .paystack-channel-pill {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 9px 15px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
            transition: all 0.2s ease;
        }

        .paystack-channel-pill:hover {
            border-color: #94a3b8;
            background: #ffffff;
            transform: translateY(-1px);
        }

        .paystack-channel-pill i {
            font-size: 16px;
        }

        .paystack-footnote {
            font-size: 12px;
            color: #475569;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 14px;
            display: flex;
            align-items: flex-start;
            line-height: 1.5;
        }

        .data-consent-box {
            background: #f8fafc;
            border: 1px solid var(--tp-border-light);
            border-radius: 10px;
            padding: 12px 16px;
            margin-top: 15px;
        }

        .data-consent-box .form-check-input {
            accent-color: #00a859;
            margin-top: 3px;
        }

        .data-consent-box .form-check-label {
            font-size: 12px;
            color: #475569;
            line-height: 1.5;
        }

        /* --- ASSIGN DELEGATION GUEST NAMES ACCORDION --- */
        .guest-accordion-wrap {
            background: #ffffff;
            border: 1px solid var(--tp-border-light);
            border-radius: 14px;
            overflow: hidden;
            margin-top: 22px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            transition: all 0.25s ease;
        }

        .guest-accordion-header {
            padding: 16px 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8fafc;
            border-bottom: 1px solid transparent;
            transition: background 0.2s ease;
        }

        .guest-accordion-header:hover {
            background: #f1f5f9;
        }

        .guest-icon-badge {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .guest-header-title {
            font-size: 14px;
            color: #0f172a;
            display: block;
            font-weight: 700;
        }

        .guest-header-subtitle {
            display: block;
            font-size: 12px;
            color: #64748b;
        }

        .guest-chevron-pill {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 11px;
            transition: transform 0.25s ease;
        }

        .guest-accordion-body {
            padding: 22px 20px;
            display: none;
            background: #ffffff;
            border-top: 1px solid var(--tp-border-light);
        }

        .guest-hint-bar {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 12.5px;
            color: #475569;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
        }

        .guest-row {
            display: grid;
            grid-template-columns: 36px 1.5fr 1.5fr 1.5fr;
            gap: 12px;
            align-items: flex-end;
            margin-bottom: 14px;
            padding-bottom: 14px;
            border-bottom: 1px solid #f1f5f9;
        }

        .guest-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .guest-num-badge {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f8fafc;
            color: #b45309;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            border: 1px solid #cbd5e1;
            margin-bottom: 4px;
        }

        .guest-input-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 4px;
            display: block;
        }

        /* --- RESERVATION SUMMARY SIDEBAR --- */
        .order-summary-sidebar {
            background: var(--tp-card-bg);
            border: 1px solid var(--tp-border-light);
            border-radius: 16px;
            padding: 30px 26px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.04);
            transition: box-shadow 0.25s ease;
        }

        .order-summary-sidebar:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.07);
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

        .summary-details-box {
            background: #f8fafc;
            border: 1px solid var(--tp-border-light);
            border-radius: 12px;
            padding: 16px 18px;
            margin-bottom: 18px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: #475569;
            padding: 8px 0;
            border-bottom: 1px dashed #e2e8f0;
        }

        .summary-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .summary-row.bold-row {
            color: #0f172a;
            font-weight: 700;
            font-size: 14.5px;
            padding-top: 0;
        }

        .summary-price-badge {
            color: #b45309;
            font-weight: 700;
            font-size: 15px;
        }

        .summary-label {
            display: flex;
            align-items: center;
        }

        .summary-val {
            font-weight: 600;
            color: #1e293b;
        }

        .text-gold-highlight {
            color: #b45309 !important;
        }

        .text-success-highlight {
            color: #00a859 !important;
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

        .btn-submit-action.paystack {
            width: 100%;
            padding: 16px 20px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 13.5px;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: linear-gradient(135deg, #09a5db 0%, #0077b6 100%);
            color: #ffffff;
            box-shadow: 0 4px 20px rgba(9, 165, 219, 0.35);
        }

        .btn-submit-action.paystack:hover {
            background: linear-gradient(135deg, #0077b6 0%, #023e8a 100%);
            box-shadow: 0 6px 25px rgba(0, 119, 182, 0.45);
            transform: translateY(-2px);
        }

        .summary-trust-footer {
            text-align: center;
            margin-top: 15px;
            font-size: 11.5px;
            color: #64748b;
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

        /* --- RESPONSIVE & MOBILE LAYOUT (90% WIDTH CARDS) --- */
        @media (max-width: 991px) {
            body {
                padding-top: 0 !important;
            }
            .order-summary-sidebar {
                position: static !important;
                margin-top: 25px;
            }
        }

        @media (max-width: 768px) {
            /* Outer container resets on mobile */
            main.checkout-main-wrap,
            .checkout-main-wrap,
            #eventBookingForm {
                width: 100% !important;
                max-width: 100% !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            .checkout-main-wrap .row {
                margin-left: 0 !important;
                margin-right: 0 !important;
                --bs-gutter-x: 0 !important;
            }

            .checkout-main-wrap .col-lg-8,
            .checkout-main-wrap .col-lg-4 {
                width: 100% !important;
                max-width: 100% !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            /* ALL CARDS 90% WIDTH ON MOBILE */
            .checkout-card,
            .order-summary-sidebar {
                width: 90% !important;
                max-width: 90% !important;
                margin-left: auto !important;
                margin-right: auto !important;
                padding: 24px 18px !important;
                border-radius: 14px !important;
            }

            /* Page header 90% width centered */
            .checkout-page-header {
                width: 90% !important;
                max-width: 90% !important;
                margin-left: auto !important;
                margin-right: auto !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .checkout-title {
                font-size: 26px !important;
            }

            .checkout-subtitle {
                font-size: 13.5px !important;
            }

            .meta-pill {
                font-size: 11.5px !important;
                padding: 4px 10px !important;
            }

            .tier-choice-grid {
                grid-template-columns: 1fr !important;
                gap: 12px !important;
            }

            .stepper-box {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }

            .stepper-box .qty-control-group {
                align-self: flex-start;
            }

            .guest-row {
                grid-template-columns: 1fr !important;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                padding: 14px;
                margin-bottom: 12px;
                align-items: stretch;
            }

            .guest-row:last-child {
                margin-bottom: 0;
            }

            .guest-num-badge {
                margin-bottom: 4px;
            }

            .paystack-channels-row {
                gap: 8px;
            }

            .paystack-channel-pill {
                width: 100%;
                justify-content: flex-start;
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
            <input type="hidden" name="payment_method" id="selectedPaymentMethodInput" value="paystack">

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
                                <input type="tel" name="contact_phone" id="contactPhoneInput" class="form-control form-control-clean" placeholder="e.g. 0712345678 or 254712345678" required>
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
                                <div class="d-flex align-items-center gap-3">
                                    <div class="guest-icon-badge">
                                        <i class="fas fa-users-cog"></i>
                                    </div>
                                    <div>
                                        <strong class="guest-header-title">Assign Delegation Guest Names</strong>
                                        <small class="guest-header-subtitle">Optional — submit names now or update attendee details later</small>
                                    </div>
                                </div>
                                <div class="guest-chevron-pill">
                                    <i class="fas fa-chevron-down" id="guestChevron"></i>
                                </div>
                            </div>
                            <div class="guest-accordion-body" id="guestAccordionBody">
                                <div class="guest-hint-bar">
                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                    <span>Enter up to 5 attendee names for custom printed table seating cards (or leave blank to submit later):</span>
                                </div>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <div class="guest-row">
                                    <div class="guest-num-badge"><?= $i ?></div>
                                    <div>
                                        <label class="guest-input-label">Guest <?= $i ?> Full Name</label>
                                        <input type="text" name="guest_names[]" class="form-control form-control-clean form-control-sm" placeholder="e.g. Dr. Jane Doe">
                                    </div>
                                    <div>
                                        <label class="guest-input-label">Email Address</label>
                                        <input type="email" name="guest_emails[]" class="form-control form-control-clean form-control-sm" placeholder="jane@company.com">
                                    </div>
                                    <div>
                                        <label class="guest-input-label">Dietary / Seating Needs</label>
                                        <input type="text" name="guest_dietary[]" class="form-control form-control-clean form-control-sm" placeholder="e.g. Vegetarian, Halal">
                                    </div>
                                </div>
                                <?php endfor; ?>
                            </div>
                        </div>

                    </div>

                    <!-- 3. PAYMENT METHOD (PAYSTACK ONLY) -->
                    <div class="checkout-card">
                        <div class="card-heading-wrap">
                            <div class="card-heading-icon"><i class="fas fa-credit-card"></i></div>
                            <div>
                                <h3 class="card-heading-title">3. Payment Method</h3>
                                <p class="card-heading-sub">Secure online payment via Paystack Payment Gateway</p>
                            </div>
                        </div>

                        <!-- Single Paystack Payment Option Card -->
                        <div class="paystack-option-box">
                            <div class="paystack-option-header">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="paystack-badge"><i class="fas fa-bolt me-1"></i> Instant Gateway</span>
                                    <strong class="paystack-brand-title">Multiple Secure Payment Options</strong>
                                </div>
                                <div class="paystack-trust-badge">
                                    <i class="fas fa-lock text-success me-1"></i> 256-bit SSL Encrypted
                                </div>
                            </div>

                            <p class="paystack-desc">
                                Clicking <strong>"Proceed to Pay"</strong> will launch the secure Paystack checkout modal, where you can complete your payment with any of the supported payment methods:
                            </p>

                            <!-- Supported Channels Showcase -->
                            <div class="paystack-channels-row">
                                <div class="paystack-channel-pill">
                                    <i class="fas fa-mobile-alt text-success"></i>
                                    <span>M-Pesa Express</span>
                                </div>
                                <div class="paystack-channel-pill">
                                    <i class="fab fa-cc-visa text-primary"></i>
                                    <span>Visa Card</span>
                                </div>
                                <div class="paystack-channel-pill">
                                    <i class="fab fa-cc-mastercard text-danger"></i>
                                    <span>Mastercard</span>
                                </div>
                                <div class="paystack-channel-pill">
                                    <i class="fab fa-cc-amex text-info"></i>
                                    <span>American Express</span>
                                </div>
                                <div class="paystack-channel-pill">
                                    <i class="fas fa-university text-warning"></i>
                                    <span>Bank Transfer</span>
                                </div>
                                <div class="paystack-channel-pill">
                                    <i class="fab fa-apple text-dark"></i>
                                    <span>Apple Pay</span>
                                </div>
                            </div>

                            <div class="paystack-footnote">
                                <i class="fas fa-shield-alt text-success mt-1 me-2 flex-shrink-0"></i>
                                <span>Official Malshe Media Paystack merchant. Your transaction is settled in Kenyan Shillings (KES) and your authenticated digital VIP ticket pass is issued immediately.</span>
                            </div>
                        </div>

                        <!-- Data Protection Notice Checkbox -->
                        <div class="data-consent-box">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="data_consent" id="dataConsentCheck" value="1" required checked>
                                <label class="form-check-label" for="dataConsentCheck">
                                    I consent to data processing for event accreditation by Malshe Media (Licensed Data Controller Serial No. 03323, Registration ID: 454-5788-A9FE under Kenya's Data Protection Act).
                                </label>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Right Column: Live Order Summary & Submit -->
                <div class="col-lg-4">
                    <div class="order-summary-sidebar">
                        <div class="card-heading-wrap">
                            <div class="card-heading-icon"><i class="fas fa-receipt"></i></div>
                            <div>
                                <h3 class="card-heading-title">Reservation Summary</h3>
                                <p class="card-heading-sub">Live attendance & pricing breakdown</p>
                            </div>
                        </div>

                        <div class="summary-details-box">
                            <div class="summary-row bold-row">
                                <span id="sumTierName">Corporate VIP Table</span>
                                <span id="sumUnitPrice" class="summary-price-badge">KES 50,000</span>
                            </div>

                            <div class="summary-row">
                                <span class="summary-label"><i class="fas fa-layer-group text-muted me-2"></i>Quantity:</span>
                                <span id="sumQtyText" class="summary-val">1 Table</span>
                            </div>

                            <div class="summary-row">
                                <span class="summary-label"><i class="fas fa-chair text-muted me-2"></i>Total Reserved Seats:</span>
                                <span id="sumSeatsText" class="summary-val text-gold-highlight">5 Seats</span>
                            </div>

                            <div class="summary-row">
                                <span class="summary-label"><i class="fas fa-book-open text-muted me-2"></i>3rd Edition Copies:</span>
                                <span id="sumCopiesText" class="summary-val">5 Collector Copies</span>
                            </div>

                            <div class="summary-row">
                                <span class="summary-label"><i class="fas fa-utensils text-muted me-2"></i>3-Course Gourmet Dinner:</span>
                                <span class="summary-val text-success-highlight"><i class="fas fa-check-circle me-1"></i> Included</span>
                            </div>
                        </div>

                        <div class="summary-total-divider">
                            <span class="total-label-text">Total Payable</span>
                            <span class="total-amount-display" id="sumTotalAmount">KES 50,000</span>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-submit-action paystack" id="submitOrderBtn">
                            <i class="fas fa-lock"></i>
                            <span id="submitBtnText">Proceed to Pay KES 50,000 via Paystack</span>
                        </button>

                        <div class="summary-trust-footer">
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
                Confirming Payment...
            </h3>
            <p style="font-size: 13.5px; color: #475569; line-height: 1.5; margin-bottom: 18px;" id="modalBody">
                Paystack has authorized your transaction. Finalizing ticket confirmation and dispatching your VIP pass...
            </p>
            <div style="font-family: monospace; font-size: 15px; color: #b45309; font-weight: 700; margin-bottom: 18px;" id="modalRefDisplay"></div>
            
            <button type="button" class="btn btn-sm btn-outline-secondary" id="modalActionBtn" style="display:none; border-radius:20px; padding: 6px 18px; font-size:12px;">
                <i class="fas fa-ticket-alt me-1"></i> View Ticket Pass
            </button>
        </div>
    </div>

    <!-- Global Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Client-side Logic Script -->
    <script>
        const tiersData = <?= json_encode($tiers) ?>;
        let currentTierId = <?= $defaultTierId ?>;

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

            const btnText = document.getElementById('submitBtnText');
            if (btnText) {
                btnText.innerText = `Proceed to Pay KES ${total.toLocaleString()} via Paystack`;
            }
        }

        function toggleGuestAccordion() {
            const body = document.getElementById('guestAccordionBody');
            const chevron = document.getElementById('guestChevron');
            if (body.style.display === 'block') {
                body.style.display = 'none';
                chevron.className = 'fas fa-chevron-down';
            } else {
                body.style.display = 'block';
                chevron.className = 'fas fa-chevron-up';
            }
        }

        updateCalculations();

        document.getElementById('eventBookingForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const submitBtn = document.getElementById('submitOrderBtn');
            const originalBtnHtml = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Preparing Payment Portal...';

            const formData = new FormData(form);

            try {
                const response = await fetch('api/event_initiate_booking.php', {
                    method: 'POST',
                    body: formData
                });

                const res = await response.json();

                if (res.status === 'success') {
                    // Initialize Paystack Inline Modal
                    const handler = PaystackPop.setup({
                        key: res.public_key,
                        email: res.email,
                        amount: res.amount_subunits,
                        currency: res.currency || 'KES',
                        ref: res.booking_reference,
                        metadata: {
                            custom_fields: [
                                {
                                    display_name: "Booking Reference",
                                    variable_name: "booking_reference",
                                    value: res.booking_reference
                                },
                                {
                                    display_name: "Attendee Name",
                                    variable_name: "attendee_name",
                                    value: res.contact_name
                                },
                                {
                                    display_name: "Tier",
                                    variable_name: "tier_name",
                                    value: res.tier_name
                                },
                                {
                                    display_name: "Phone Number",
                                    variable_name: "phone_number",
                                    value: res.contact_phone
                                }
                            ]
                        },
                        callback: function(paymentResponse) {
                            // Paystack payment authorized!
                            document.getElementById('modalSpinner').style.display = 'block';
                            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-spinner fa-spin text-warning"></i> Confirming Payment...';
                            document.getElementById('modalBody').innerText = 'Paystack has confirmed your transaction. Generating your official VIP pass and dispatching your confirmation email...';
                            document.getElementById('modalRefDisplay').innerText = `Ref: ${paymentResponse.reference || res.booking_reference}`;
                            document.getElementById('statusModal').style.display = 'flex';

                            verifyPaystackTransaction(paymentResponse.reference || res.booking_reference, res.booking_reference, res.qr_token);
                        },
                        onClose: function() {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnHtml;
                        }
                    });

                    handler.openIframe();
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

        async function verifyPaystackTransaction(reference, bookingRef, token) {
            try {
                const response = await fetch('api/verify_paystack_payment.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        reference: reference,
                        booking_reference: bookingRef,
                        token: token
                    })
                });

                const data = await response.json();

                if (data.status === 'success') {
                    document.getElementById('modalSpinner').style.display = 'none';
                    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-check-circle text-success"></i> Payment Confirmed!';
                    document.getElementById('modalBody').innerText = 'Thank you! Your payment was successful. Redirecting to your official event pass...';
                    setTimeout(() => {
                        window.location.href = data.redirect_url;
                    }, 1200);
                } else {
                    document.getElementById('modalSpinner').style.display = 'none';
                    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-exclamation-triangle text-warning"></i> Verification Processing';
                    document.getElementById('modalBody').innerText = data.message || 'Payment received! Finalizing ticket confirmation...';
                    const actionBtn = document.getElementById('modalActionBtn');
                    actionBtn.style.display = 'inline-block';
                    actionBtn.innerText = 'View Ticket Pass';
                    actionBtn.onclick = function() {
                        window.location.href = `ticket-pass.php?ref=${encodeURIComponent(bookingRef)}&token=${encodeURIComponent(token)}`;
                    };
                }
            } catch (err) {
                console.error(err);
                document.getElementById('modalSpinner').style.display = 'none';
                document.getElementById('modalTitle').innerHTML = '<i class="fas fa-info-circle text-primary"></i> Processing Complete';
                document.getElementById('modalBody').innerText = 'Your payment was processed by Paystack. Click below to view your digital ticket pass.';
                const actionBtn = document.getElementById('modalActionBtn');
                actionBtn.style.display = 'inline-block';
                actionBtn.innerText = 'Open My Ticket Pass';
                actionBtn.onclick = function() {
                    window.location.href = `ticket-pass.php?ref=${encodeURIComponent(bookingRef)}&token=${encodeURIComponent(token)}`;
                };
            }
        }
    </script>
</body>
</html>
