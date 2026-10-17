<?php
/**
 * Corporate Proforma Invoice
 * Turning Point Magazine - The Leadership Dinner 2026
 */

require_once __DIR__ . '/connection2.php';
require_once __DIR__ . '/includes/event_db_helper.php';

$pdo = connect();
ensure_event_tables_exist($pdo);

$ref = trim($_GET['ref'] ?? '');
$token = trim($_GET['token'] ?? '');

if (empty($ref)) {
    die("Error: Missing invoice reference.");
}

$stmt = $pdo->prepare("
    SELECT b.*, t.tier_name, t.badge_label, t.seats_per_unit, t.perks_json,
           e.title AS event_title, e.subtitle, e.event_date, e.event_time, e.venue,
           e.paybill_number, e.bank_details_text
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
    die("Invalid invoice reference or verification token.");
}

$isTable = ($b['booking_type'] === 'corporate_table');
$magCopies = $isTable ? ($b['quantity'] * 5) : $b['quantity'];
$invoiceDate = date('d F Y', strtotime($b['created_at']));
$dueDate = date('d F Y', strtotime($b['event_date']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proforma Invoice - <?= htmlspecialchars($b['booking_reference']) ?> | Turning Point Magazine</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @font-face {
            font-family: florania;
            src: url("assets/fonts/florania.otf") format("opentype");
        }

        :root {
            --primary: #141414;
            --gold: #b38728;
            --gold-dark: #8c6819;
            --crimson: #c8102e;
            --dark: #1e1e1e;
            --text-dark: #222222;
            --text-muted: #666666;
            --border: #e2e8f0;
            --bg-light: #f8fafc;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f1f5f9;
            color: var(--text-dark);
            font-family: 'Poppins', sans-serif;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .actions-bar {
            width: 100%;
            max-width: 850px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .btn-act {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.25s ease;
            border: none;
        }

        .btn-print {
            background: #1e293b;
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .btn-print:hover {
            background: #0f172a;
            transform: translateY(-2px);
        }

        .btn-back {
            background: #ffffff;
            color: #334155;
            border: 1px solid #cbd5e1;
        }
        .btn-back:hover {
            background: #f8fafc;
        }

        /* Invoice Paper Sheet */
        .invoice-sheet {
            width: 100%;
            max-width: 850px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 20px 45px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
            padding: 50px 60px;
            position: relative;
        }

        .inv-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid var(--border);
            padding-bottom: 30px;
            margin-bottom: 35px;
        }

        .inv-brand h1 {
            font-family: 'florania', serif;
            font-size: 28px;
            color: #0f172a;
            letter-spacing: 2px;
            font-weight: 800;
        }

        .inv-brand .sub {
            font-size: 12px;
            color: var(--gold-dark);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-top: 4px;
        }

        .inv-publisher {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 10px;
            line-height: 1.5;
        }

        .inv-meta-right {
            text-align: right;
        }

        .inv-badge {
            display: inline-block;
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .inv-number {
            font-family: monospace;
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 1px;
        }

        .inv-date {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Bill To / Event Details Grid */
        .inv-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            margin-bottom: 35px;
        }

        .inv-grid-col h3 {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-muted);
            margin-bottom: 12px;
        }

        .bill-org {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .bill-detail {
            font-size: 13px;
            color: #334155;
            line-height: 1.6;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            background: #f8fafc;
            color: #475569;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 14px 18px;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            text-align: left;
        }

        .items-table th.text-right, .items-table td.text-right {
            text-align: right;
        }

        .items-table td {
            padding: 18px;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
            color: #1e293b;
        }

        .item-title {
            font-weight: 700;
            color: #0f172a;
            font-size: 15px;
        }

        .item-desc {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
            line-height: 1.4;
        }

        /* Totals */
        .totals-box {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 40px;
        }

        .totals-table {
            width: 320px;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 8px 0;
            font-size: 14px;
        }

        .totals-table td.total-label {
            color: #64748b;
        }

        .totals-table td.total-val {
            text-align: right;
            font-weight: 700;
            color: #0f172a;
        }

        .totals-table tr.grand-total td {
            border-top: 2px solid #0f172a;
            padding-top: 12px;
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
        }

        /* Payment Instructions Box */
        .pay-instructions {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 22px 25px;
            margin-bottom: 30px;
        }

        .pay-instructions h4 {
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0f172a;
            margin-bottom: 10px;
        }

        .pay-instructions pre {
            font-family: inherit;
            font-size: 13px;
            color: #334155;
            white-space: pre-wrap;
            line-height: 1.6;
        }

        /* Footer */
        .inv-footer {
            border-top: 1px solid var(--border);
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: #94a3b8;
        }

        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .actions-bar {
                display: none !important;
            }
            .invoice-sheet {
                border: none !important;
                box-shadow: none !important;
                padding: 20px 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body>

    <div class="actions-bar">
        <a href="leadership-dinner-2026.php" class="btn-act btn-back">
            <i class="fas fa-arrow-left"></i> Return to Event Page
        </a>
        <button onclick="window.print()" class="btn-act btn-print">
            <i class="fas fa-print"></i> Download / Print Proforma Invoice (PDF)
        </button>
    </div>

    <div class="invoice-sheet" id="invoiceArea">
        
        <!-- Header -->
        <div class="inv-header">
            <div class="inv-brand">
                <h1>TURNING POINT</h1>
                <div class="sub">Malshe Media Limited • Editorial Publications</div>
                <div class="inv-publisher">
                    P.O. Box 00100 Nairobi, Kenya<br>
                    Licensed Data Controller: Serial No. 03323 (ID: 454-5788-A9FE)<br>
                    Email: accounts@turningpointmagazine.africa | info@turningpointmagazine.africa
                </div>
            </div>
            <div class="inv-meta-right">
                <div class="inv-badge">PROFORMA INVOICE</div>
                <div class="inv-number"><?= htmlspecialchars($b['booking_reference']) ?></div>
                <div class="inv-date">Issued: <?= $invoiceDate ?></div>
                <div class="inv-date" style="color:var(--crimson);">Due: <?= $dueDate ?></div>
            </div>
        </div>

        <!-- Billed To & Event Details -->
        <div class="inv-grid">
            <div class="inv-grid-col">
                <h3>Billed To (Organization / Client)</h3>
                <div class="bill-org"><?= htmlspecialchars($b['organization_name'] ?: $b['contact_name']) ?></div>
                <div class="bill-detail">
                    Attn: <strong><?= htmlspecialchars($b['contact_name']) ?></strong><br>
                    Email: <?= htmlspecialchars($b['contact_email']) ?><br>
                    Phone: <?= htmlspecialchars($b['contact_phone']) ?><br>
                    <?= !empty($b['dietary_notes']) ? 'Special Notes: ' . htmlspecialchars($b['dietary_notes']) : '' ?>
                </div>
            </div>

            <div class="inv-grid-col">
                <h3>Event Engagement Reference</h3>
                <div style="font-weight:700; color:#0f172a; font-size:15px;"><?= htmlspecialchars($b['event_title']) ?></div>
                <div class="bill-detail" style="margin-top:4px;">
                    <strong>Date:</strong> <?= date('l, 6 November 2026', strtotime($b['event_date'])) ?><br>
                    <strong>Time:</strong> <?= htmlspecialchars($b['event_time']) ?><br>
                    <strong>Venue:</strong> <?= htmlspecialchars($b['venue']) ?><br>
                    <strong>Launch:</strong> Third Edition of Turning Point Magazine
                </div>
            </div>
        </div>

        <!-- Line Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width:50%;">Description / Entitlement</th>
                    <th class="text-right">Unit Price (KES)</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Total Seats</th>
                    <th class="text-right">Amount (KES)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="item-title"><?= htmlspecialchars($b['tier_name']) ?></div>
                        <div class="item-desc">
                            Includes reserved admission to The Leadership Dinner 2026 at Tamarind Tree Hotel, Nairobi, full 3-course gourmet service, executive networking, and <strong><?= $magCopies ?> complimentary physical copies</strong> of the 3rd Edition of Turning Point Magazine.
                            <?php if (!empty($b['organization_name'])): ?>
                                <br><span style="color:#b38728; font-weight:600;"><i class="fas fa-tag"></i> Includes custom Organization Table Signage for "<?= htmlspecialchars($b['organization_name']) ?>"</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="text-right"><?= number_format($b['unit_price_kes'], 2) ?></td>
                    <td class="text-right"><?= intval($b['quantity']) ?></td>
                    <td class="text-right"><?= intval($b['total_seats']) ?></td>
                    <td class="text-right" style="font-weight:700;"><?= number_format($b['total_amount_kes'], 2) ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Totals Summary -->
        <div class="totals-box">
            <table class="totals-table">
                <tr>
                    <td class="total-label">Subtotal</td>
                    <td class="total-val">KES <?= number_format($b['total_amount_kes'], 2) ?></td>
                </tr>
                <tr>
                    <td class="total-label">VAT / Taxes</td>
                    <td class="total-val">Inclusive</td>
                </tr>
                <tr class="grand-total">
                    <td>Total Payable</td>
                    <td style="text-align:right;">KES <?= number_format($b['total_amount_kes'], 2) ?></td>
                </tr>
            </table>
        </div>

        <!-- Remittance / Payment Instructions -->
        <div class="pay-instructions">
            <h4><i class="fas fa-university me-1"></i> PAYMENT DETAILS</h4>
            <p style="font-size: 13px; color: #475569; margin-bottom: 14px;">Please remit your entry or additional guest payments using the details below:</p>
            
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 13px; background: #ffffff;">
                <tr style="background: #f1f5f9;">
                    <th style="padding: 10px 14px; border: 1px solid #e2e8f0; text-align: left; width: 45%; color: #334155;">Bank Transfer Details</th>
                    <th style="padding: 10px 14px; border: 1px solid #e2e8f0; text-align: left; color: #334155;">Information</th>
                </tr>
                <tr>
                    <td style="padding: 8px 14px; border: 1px solid #e2e8f0; font-weight: 600;">Bank Name</td>
                    <td style="padding: 8px 14px; border: 1px solid #e2e8f0;">NCBA Bank Kenya Plc</td>
                </tr>
                <tr>
                    <td style="padding: 8px 14px; border: 1px solid #e2e8f0; font-weight: 600;">Account Name</td>
                    <td style="padding: 8px 14px; border: 1px solid #e2e8f0;">Malshe Media</td>
                </tr>
                <tr>
                    <td style="padding: 8px 14px; border: 1px solid #e2e8f0; font-weight: 600;">Account No.</td>
                    <td style="padding: 8px 14px; border: 1px solid #e2e8f0; font-family: monospace; font-weight: 700;">1000145594</td>
                </tr>
                <tr>
                    <td style="padding: 8px 14px; border: 1px solid #e2e8f0; font-weight: 600;">Branch Code</td>
                    <td style="padding: 8px 14px; border: 1px solid #e2e8f0;">125</td>
                </tr>
                <tr>
                    <td style="padding: 8px 14px; border: 1px solid #e2e8f0; font-weight: 600;">Bank Code</td>
                    <td style="padding: 8px 14px; border: 1px solid #e2e8f0;">07000</td>
                </tr>
                <tr>
                    <td style="padding: 8px 14px; border: 1px solid #e2e8f0; font-weight: 600;">SWIFT Code</td>
                    <td style="padding: 8px 14px; border: 1px solid #e2e8f0; font-family: monospace; font-weight: 700;">CBAFKENX</td>
                </tr>
            </table>

            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px 18px; margin-bottom: 14px;">
                <strong style="color: #0f172a; font-size: 13.5px;"><i class="fas fa-mobile-alt me-1 text-success"></i> Mobile Money (M-Pesa) & Contact:</strong>
                <div style="font-size: 13px; margin-top: 6px; color: #334155; line-height: 1.6;">
                    • <strong>Buy Goods Till Number:</strong> <span style="font-family: monospace; font-size: 15px; font-weight: 800; color: #0f172a;">575555</span><br>
                    • <strong>Payment Reference:</strong> <span style="font-family: monospace; font-weight: 700; color: #b38728;"><?= htmlspecialchars($b['booking_reference']) ?></span>
                </div>
            </div>

            <div style="font-size: 12px; color: #64748b;">
                * Once payment is remitted via M-Pesa or Bank Wire, please email remittance confirmation to <strong>accounts@turningpointmagazine.africa</strong> citing Reference <strong><?= htmlspecialchars($b['booking_reference']) ?></strong> for immediate digital pass issuance.
            </div>
        </div>

        <!-- Footer -->
        <div class="inv-footer">
            <div>This is a computer-generated proforma invoice issued by Malshe Media Limited.</div>
            <div>Reference: <?= htmlspecialchars($b['booking_reference']) ?></div>
        </div>

    </div>

</body>
</html>
