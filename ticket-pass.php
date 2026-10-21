<?php
/**
 * Official Branded Ticket & Table Pass
 * Turning Point Magazine - The Leadership Dinner 2026
 */

require_once __DIR__ . '/connection2.php';
require_once __DIR__ . '/includes/event_db_helper.php';

$pdo = connect();
ensure_event_tables_exist($pdo);

$ref = trim($_GET['ref'] ?? '');
$token = trim($_GET['token'] ?? '');

if (empty($ref)) {
    die("Error: Missing booking reference.");
}

$stmt = $pdo->prepare("
    SELECT b.*, t.tier_name, t.badge_label, t.seats_per_unit, t.perks_json,
           e.title AS event_title, e.subtitle, e.event_date, e.event_time, e.venue,
           e.principal_guest_name, e.principal_guest_title, e.special_highlight
    FROM tp_event_bookings b
    JOIN tp_ticket_tiers t ON b.tier_id = t.id
    JOIN tp_events e ON b.event_id = e.id
    WHERE b.booking_reference = ? " . (!empty($token) ? "AND b.qr_token = ?" : "") . "
    LIMIT 1
");

$params = !empty($token) ? [$ref, $token] : [$ref];
$stmt->execute($params);
$b = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$b) {
    die("Invalid booking reference or verification token.");
}

// Fetch any registered guests
$stmtGuests = $pdo->prepare("SELECT * FROM tp_event_guests WHERE booking_id = ? ORDER BY seat_number ASC");
$stmtGuests->execute([$b['id']]);
$guests = $stmtGuests->fetchAll(PDO::FETCH_ASSOC);

$isTable = ($b['booking_type'] === 'corporate_table');
$magCopies = $isTable ? ($b['quantity'] * 5) : $b['quantity'];
$perks = !empty($b['perks_json']) ? json_decode($b['perks_json'], true) : [];

// QR Code URL for check-in verification
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'www.turningpointmagazine.africa';
$base = defined('BASE_URL') ? BASE_URL : '/';
$verifyUrl = rtrim($protocol . $host . $base, '/') . "/admin/events_ticketing.php?tab=checkin&ref=" . urlencode($b['booking_reference']) . "&token=" . urlencode($b['qr_token']);
$qrCodeSrc = "https://api.qrserver.com/v1/create-qr-code/?size=240x240&margin=8&data=" . urlencode($verifyUrl);

// Cache base64 data for instant, CORS-free client-side PDF export
$qrImageSrc = $qrCodeSrc;
try {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $qrCodeSrc);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $qrData = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($httpCode === 200 && !empty($qrData)) {
        $qrImageSrc = 'data:image/png;base64,' . base64_encode($qrData);
    }
} catch (Exception $e) {
    // fallback to url
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Pass - <?= htmlspecialchars($b['booking_reference']) ?> | The Leadership Dinner 2026</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Direct Client-Side PDF Generation Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    
    <style>
        @font-face {
            font-family: florania;
            src: url("assets/fonts/florania.otf") format("opentype");
        }

        :root {
            --obsidian: #0d0d0d;
            --charcoal: #141414;
            --surface: #1a1a1a;
            --gold: #d4af37;
            --gold-light: #f3e5ab;
            --gold-gradient: linear-gradient(135deg, #d4af37 0%, #aa8421 50%, #f3e5ab 100%);
            --crimson: #c8102e;
            --ivory: #faf7f2;
            --muted: #a3a3a3;
            --border-gold: rgba(212, 175, 55, 0.35);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--obsidian);
            color: var(--ivory);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(200, 16, 46, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(212, 175, 55, 0.08) 0%, transparent 40%);
        }

        .pass-actions-top {
            width: 100%;
            max-width: 900px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-print {
            background: var(--gold-gradient);
            color: #0d0d0d;
            box-shadow: 0 4px 20px rgba(212, 175, 55, 0.3);
        }
        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(212, 175, 55, 0.5);
        }

        .btn-back {
            background: transparent;
            color: var(--gold);
            border: 1px solid var(--border-gold);
        }
        .btn-back:hover {
            background: rgba(212, 175, 55, 0.1);
        }

        /* Pass Master Container */
        .pass-card {
            width: 100%;
            max-width: 900px;
            background: var(--charcoal);
            border: 2px solid var(--gold);
            border-radius: 18px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.8), 0 0 40px rgba(212, 175, 55, 0.15);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Decorative Gold Corner Borders */
        .pass-card::before, .pass-card::after {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            pointer-events: none;
            z-index: 2;
        }
        .pass-card::before {
            top: 10px;
            left: 10px;
            border-top: 2px solid var(--gold);
            border-left: 2px solid var(--gold);
        }
        .pass-card::after {
            bottom: 10px;
            right: 10px;
            border-bottom: 2px solid var(--gold);
            border-right: 2px solid var(--gold);
        }

        /* Header */
        .pass-header {
            background: linear-gradient(180deg, #1f1f1f 0%, #141414 100%);
            padding: 35px 40px 25px;
            border-bottom: 1px solid var(--border-gold);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .pass-brand {
            display: flex;
            flex-direction: column;
        }

        .pass-brand-name {
            font-family: 'florania', serif;
            font-size: 24px;
            font-weight: 800;
            color: var(--gold);
            letter-spacing: 4px;
            text-transform: uppercase;
        }

        .pass-brand-sub {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--muted);
            margin-top: 4px;
        }

        .pass-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            background: <?= ($isTable ? 'var(--gold-gradient)' : 'linear-gradient(135deg, #c8102e 0%, #900c20 100%)') ?>;
            color: <?= ($isTable ? '#0d0d0d' : '#ffffff') ?>;
            box-shadow: 0 4px 15px rgba(0,0,0,0.4);
        }

        /* Body Split: Details Left, QR Right */
        .pass-body {
            display: grid;
            grid-template-columns: 1fr 280px;
            padding: 40px;
            gap: 35px;
            position: relative;
        }

        .event-meta-tag {
            display: inline-block;
            color: var(--crimson);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .pass-title {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 800;
            color: var(--ivory);
            line-height: 1.15;
            margin-bottom: 10px;
        }

        .pass-subtitle {
            font-size: 14px;
            color: var(--gold-light);
            font-style: italic;
            margin-bottom: 25px;
        }

        /* Organization Table Signage Highlight */
        <?php if (!empty($b['organization_name'])): ?>
        .org-card-box {
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.12) 0%, rgba(20, 20, 20, 0.8) 100%);
            border: 1px solid var(--gold);
            border-radius: 12px;
            padding: 18px 24px;
            margin-bottom: 25px;
        }
        .org-card-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--gold);
        }
        .org-card-name {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
            margin-top: 4px;
            letter-spacing: 1px;
        }
        <?php endif; ?>

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--muted);
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 15px;
            font-weight: 700;
            color: var(--ivory);
        }

        .info-value.gold {
            color: var(--gold);
        }

        /* Perks List */
        .perks-box {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 10px;
            padding: 15px 20px;
            margin-top: 15px;
        }

        .perks-box-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--gold);
            margin-bottom: 8px;
        }

        .perks-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .perks-list li {
            font-size: 12px;
            color: #ccc;
            margin-bottom: 5px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .perks-list li i {
            color: var(--gold);
            font-size: 11px;
            margin-top: 3px;
        }

        /* Right Column: QR Pass Module */
        .qr-column {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #111111;
            border: 1px dashed var(--border-gold);
            border-radius: 14px;
            padding: 25px 20px;
            text-align: center;
            position: relative;
        }

        .qr-image-wrap {
            background: #ffffff;
            padding: 12px;
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.6);
            margin-bottom: 15px;
            display: inline-block;
        }

        .qr-image-wrap img {
            display: block;
            width: 170px;
            height: 170px;
        }

        .qr-ref-code {
            font-family: monospace;
            font-size: 16px;
            font-weight: 800;
            color: var(--gold);
            letter-spacing: 2px;
            margin-bottom: 4px;
        }

        .qr-status-pill {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 4px 12px;
            border-radius: 20px;
            background: <?= ($b['payment_status'] === 'paid' ? 'rgba(82, 183, 136, 0.15)' : 'rgba(212, 175, 55, 0.15)') ?>;
            color: <?= ($b['payment_status'] === 'paid' ? '#52b788' : '#e6c875') ?>;
            border: 1px solid <?= ($b['payment_status'] === 'paid' ? '#2d6a4f' : '#aa8421') ?>;
            margin-top: 6px;
        }

        .qr-instruction {
            font-size: 11px;
            color: var(--muted);
            margin-top: 12px;
            line-height: 1.4;
        }

        /* Footer Watermark */
        .pass-footer {
            background: #0a0a0a;
            border-top: 1px solid rgba(255,255,255,0.06);
            padding: 22px 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            gap: 8px;
            font-size: 11.5px;
            color: #888;
        }

        .compliance-note {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-align: center;
            color: #b0b0b0;
        }

        .footer-secretariat {
            text-align: center;
            color: #888;
        }
        .footer-secretariat a {
            color: var(--gold);
            text-decoration: none;
            font-weight: 500;
        }
        .footer-secretariat a:hover {
            text-decoration: underline;
        }

        /* Print Optimization */
        @media print {
            body {
                background: #ffffff !important;
                color: #000000 !important;
                padding: 0 !important;
            }
            .pass-actions-top {
                display: none !important;
            }
            .pass-card {
                border: 2px solid #000000 !important;
                box-shadow: none !important;
                background: #ffffff !important;
                color: #000000 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            .pass-header {
                background: #f4f4f4 !important;
                border-bottom: 2px solid #000000 !important;
            }
            .pass-brand-name {
                color: #000000 !important;
            }
            .pass-title {
                color: #000000 !important;
            }
            .org-card-box {
                background: #f9f9f9 !important;
                border: 1px solid #000000 !important;
            }
            .org-card-name {
                color: #000000 !important;
            }
            .info-value {
                color: #000000 !important;
            }
            .perks-box {
                background: #f9f9f9 !important;
                border: 1px solid #000000 !important;
            }
            .perks-list li {
                color: #333333 !important;
            }
            .qr-column {
                background: #ffffff !important;
                border: 1px solid #000000 !important;
            }
            .pass-footer {
                background: #f4f4f4 !important;
                color: #444444 !important;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 25px 15px;
            }
            .pass-actions-top {
                flex-direction: column-reverse;
                align-items: stretch;
                width: 100%;
                gap: 12px;
            }
            .pass-actions-top .btn-action {
                width: 100%;
                justify-content: center;
                text-align: center;
                padding: 14px 18px;
                font-size: 13px;
            }
            .pass-body {
                grid-template-columns: 1fr;
            }
            .pass-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            .info-grid {
                grid-template-columns: 1fr;
            }
            .pass-title {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>

    <div class="pass-actions-top">
        <a href="leadership-dinner-2026.php" class="btn-action btn-back">
            <i class="fas fa-arrow-left"></i> Return to Event Page
        </a>
        <button type="button" id="downloadPdfBtn" onclick="downloadPassPdf()" class="btn-action btn-print">
            <i class="fas fa-download"></i> Download Official Pass (PDF)
        </button>
    </div>

    <div class="pass-card" id="printablePass">
        
        <!-- Header -->
        <div class="pass-header">
            <div class="pass-brand">
                <span class="pass-brand-name">Turning Point</span>
                <span class="pass-brand-sub">Africa's Leadership & Policy Journal</span>
            </div>
            <div class="pass-badge-pill">
                <i class="fas fa-ticket-alt"></i>
                <span><?= htmlspecialchars($b['tier_name']) ?></span>
            </div>
        </div>

        <!-- Body -->
        <div class="pass-body">
            
            <div class="pass-details-col">
                <span class="event-meta-tag"><i class="fas fa-star me-1"></i> Flagship Gala • 3rd Edition Launch</span>
                <h1 class="pass-title"><?= htmlspecialchars($b['event_title']) ?></h1>
                <div class="pass-subtitle"><?= htmlspecialchars($b['subtitle']) ?></div>

                <?php if (!empty($b['organization_name'])): ?>
                <div class="org-card-box">
                    <div class="org-card-label"><i class="fas fa-building me-1"></i> Reserved VIP Table Signage For:</div>
                    <div class="org-card-name"><?= htmlspecialchars($b['organization_name']) ?></div>
                </div>
                <?php endif; ?>

                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Date & Time</span>
                        <span class="info-value"><?= date('l, 6 November 2026', strtotime($b['event_date'])) ?></span>
                        <span style="font-size: 12px; color: var(--gold-light);"><?= htmlspecialchars($b['event_time']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Venue</span>
                        <span class="info-value"><?= htmlspecialchars($b['venue']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Primary Delegate</span>
                        <span class="info-value"><?= htmlspecialchars($b['contact_name']) ?></span>
                        <span style="font-size: 12px; color: var(--muted);"><?= htmlspecialchars($b['contact_email']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Capacity & Perks</span>
                        <span class="info-value gold"><?= intval($b['total_seats']) ?> Total Seats (<?= htmlspecialchars($b['badge_label'] ?? $b['tier_name']) ?>)</span>
                        <span style="font-size: 12px; color: var(--gold-light);"><i class="fas fa-book-open me-1"></i> <?= $magCopies ?> Physical Copies of 3rd Edition</span>
                    </div>
                </div>

                <div class="perks-box">
                    <div class="perks-box-title"><i class="fas fa-check-circle me-1"></i> Entitled Privileges & Amenities</div>
                    <ul class="perks-list">
                        <?php if (!empty($perks)): ?>
                            <?php foreach ($perks as $perk): ?>
                                <li><i class="fas fa-check-circle"></i> <span><?= htmlspecialchars($perk) ?></span></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li><i class="fas fa-check-circle"></i> <span>Full 3-course gourmet dinner & executive networking at Tamarind Tree Hotel</span></li>
                            <li><i class="fas fa-check-circle"></i> <span>Complimentary physical copy of the 3rd Edition of Turning Point Magazine</span></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <?php if (!empty($guests)): ?>
                <div style="margin-top: 20px;">
                    <div style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: var(--gold); letter-spacing: 1px; margin-bottom: 8px;">
                        <i class="fas fa-users me-1"></i> Assigned Delegation Members:
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                        <?php foreach ($guests as $g): ?>
                            <span style="background: #222; border: 1px solid #333; padding: 4px 12px; border-radius: 15px; font-size: 12px;">
                                Seat <?= $g['seat_number'] ?>: <strong><?= htmlspecialchars($g['guest_name'] ?: 'Guest') ?></strong>
                                <?= !empty($g['dietary_requirements']) ? ' (' . htmlspecialchars($g['dietary_requirements']) . ')' : '' ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>

            <!-- QR Verification Column -->
            <div class="qr-column">
                <div class="qr-image-wrap">
                    <img src="<?= $qrImageSrc ?>" alt="QR Pass Verification">
                </div>
                <div class="qr-ref-code"><?= htmlspecialchars($b['booking_reference']) ?></div>
                <div class="qr-status-pill">
                    <?= ($b['payment_status'] === 'paid' ? '✓ VERIFIED PASS' : 'STATUS: ' . strtoupper($b['payment_status'])) ?>
                </div>

                <?php if (!empty($b['mpesa_receipt_number'])): ?>
                <div style="font-size: 11px; color: var(--muted); margin-top: 8px; font-family: monospace;">
                    Receipt: <strong style="color:#fff;"><?= htmlspecialchars($b['mpesa_receipt_number']) ?></strong>
                </div>
                <?php endif; ?>

                <div class="qr-instruction">
                    Scan at entrance desk for swift badge issuance and VIP escort to your reserved table.
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="pass-footer">
            <div class="compliance-note">
                <i class="fas fa-shield-alt" style="color: var(--gold);"></i>
                <span>Malshe Media Data Controller Reg: <strong>Serial No. 03323</strong> (ID: 454-5788-A9FE)</span>
            </div>
            <div class="footer-secretariat">
                <span>Official Secretariat: <a href="mailto:info@turningpointmagazine.africa">info@turningpointmagazine.africa</a></span>
            </div>
        </div>

    </div>

    <script>
        function downloadPassPdf() {
            const btn = document.getElementById('downloadPdfBtn');
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Generating PDF...';

            const element = document.getElementById('printablePass');

            const opt = {
                margin:       [6, 6, 6, 6],
                filename:     'TurningPoint-Pass-<?= htmlspecialchars($b['booking_reference']) ?>.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { 
                    scale: 2, 
                    useCORS: true,
                    logging: false,
                    windowWidth: 1024
                },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
            };

            if (typeof html2pdf !== 'undefined') {
                html2pdf().set(opt).from(element).save().then(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check me-2"></i> Downloaded!';
                    setTimeout(() => {
                        btn.innerHTML = originalHtml;
                    }, 3500);
                }).catch(err => {
                    console.error('PDF generation error:', err);
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                    window.print();
                });
            } else {
                window.print();
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        }
    </script>
</body>
</html>
