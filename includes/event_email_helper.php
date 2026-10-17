<?php
/**
 * Event Email Helper
 * Turning Point Magazine - The Leadership Dinner 2026
 * Luxury styled HTML emails matching obsidian/gold/crimson branding.
 */

require_once __DIR__ . '/../connection2.php';
require_once __DIR__ . '/mailer.php';

function get_app_base_url() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'www.turningpointmagazine.africa';
    $base = defined('BASE_URL') ? BASE_URL : '/';
    return rtrim($protocol . $host . $base, '/') . '/';
}

function send_event_booking_confirmation_email($pdo, $bookingId) {
    try {
        $stmt = $pdo->prepare("
            SELECT b.*, t.tier_name, t.badge_label, t.seats_per_unit, t.perks_json, e.title AS event_title, e.event_date, e.event_time, e.venue
            FROM tp_event_bookings b
            JOIN tp_ticket_tiers t ON b.tier_id = t.id
            JOIN tp_events e ON b.event_id = e.id
            WHERE b.id = ?
            LIMIT 1
        ");
        $stmt->execute([$bookingId]);
        $b = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$b) return false;

        $baseUrl = get_app_base_url();
        $passUrl = $baseUrl . "ticket-pass.php?ref=" . urlencode($b['booking_reference']) . "&token=" . urlencode($b['qr_token']);
        
        $isTable = ($b['booking_type'] === 'corporate_table');
        $magCopies = $isTable ? ($b['quantity'] * 5) : $b['quantity'];
        $formattedDate = date('l, j F Y', strtotime($b['event_date']));

        $subject = "Official Reservation Confirmed: " . $b['event_title'] . " [" . $b['booking_reference'] . "]";

        $body = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <style>
                body { margin: 0; padding: 0; background-color: #0d0d0d; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #f5f5f5; }
                .email-container { max-width: 650px; margin: 20px auto; background-color: #141414; border: 1px solid #d4af37; border-radius: 12px; overflow: hidden; }
                .header-banner { background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%); padding: 35px 30px; text-align: center; border-bottom: 2px solid #d4af37; }
                .logo-text { color: #d4af37; font-size: 26px; font-weight: 800; letter-spacing: 3px; text-transform: uppercase; margin: 0; }
                .gold-sub { color: #e6c875; font-size: 13px; letter-spacing: 2px; text-transform: uppercase; margin-top: 6px; }
                .content-box { padding: 35px 30px; line-height: 1.6; }
                .greeting { font-size: 18px; color: #ffffff; margin-bottom: 20px; }
                .status-badge { display: inline-block; background-color: #1b4332; color: #52b788; border: 1px solid #2d6a4f; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; }
                .highlight-card { background: #1c1c1c; border: 1px solid rgba(212, 175, 55, 0.4); padding: 20px; border-radius: 8px; margin: 25px 0; }
                .details-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                .details-table td { padding: 10px 0; border-bottom: 1px solid #262626; font-size: 14px; }
                .details-table td.label { color: #a3a3a3; width: 40%; }
                .details-table td.value { color: #ffffff; font-weight: 600; text-align: right; }
                .cta-btn-wrap { text-align: center; margin: 35px 0 20px; }
                .cta-btn { display: inline-block; background: linear-gradient(135deg, #d4af37 0%, #aa8421 100%); color: #0d0d0d !important; text-decoration: none; padding: 15px 35px; border-radius: 30px; font-weight: 800; font-size: 14px; letter-spacing: 1.5px; text-transform: uppercase; box-shadow: 0 4px 15px rgba(212,175,55,0.3); }
                .footer { background: #0a0a0a; padding: 25px 30px; text-align: center; font-size: 12px; color: #737373; border-top: 1px solid #222; }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='header-banner'>
                    <div class='logo-text'>TURNING POINT MAGAZINE</div>
                    <div class='gold-sub'>The Leadership Dinner 2026 • Official Pass</div>
                </div>
                <div class='content-box'>
                    <span class='status-badge'>✓ Payment Confirmed</span>
                    <div class='greeting'>Dear <strong>" . htmlspecialchars($b['contact_name']) . "</strong>,</div>
                    <p>It is our distinguished pleasure to confirm your reservation for <strong>" . htmlspecialchars($b['event_title']) . "</strong>. Your seat(s) have been secured, and your digital entry pass is now active.</p>
                    
                    <div class='highlight-card'>
                        <div style='color: #d4af37; font-size: 15px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;'>Pass Details</div>
                        <div style='font-size: 20px; color: #ffffff; font-weight: 700; margin-top: 5px;'>" . htmlspecialchars($b['tier_name']) . "</div>" .
                        (!empty($b['organization_name']) ? "<div style='color: #e6c875; font-size: 14px; margin-top: 3px;'><i class='fas fa-building'></i> Organization: <strong>" . htmlspecialchars($b['organization_name']) . "</strong></div>" : "") . "
                    </div>

                    <table class='details-table'>
                        <tr>
                            <td class='label'>Booking Reference</td>
                            <td class='value' style='color:#d4af37; font-family:monospace; font-size:16px;'>" . htmlspecialchars($b['booking_reference']) . "</td>
                        </tr>
                        <tr>
                            <td class='label'>Date & Time</td>
                            <td class='value'>" . htmlspecialchars($formattedDate) . "<br><span style='font-size:12px; color:#aaa;'>" . htmlspecialchars($b['event_time']) . "</span></td>
                        </tr>
                        <tr>
                            <td class='label'>Venue</td>
                            <td class='value'>" . htmlspecialchars($b['venue']) . "</td>
                        </tr>
                        <tr>
                            <td class='label'>Total Reserved Seats</td>
                            <td class='value'>" . intval($b['total_seats']) . " " . ($isTable ? "Seats (VIP Table of 5)" : "Seat") . "</td>
                        </tr>
                        <tr>
                            <td class='label'>Amount Paid</td>
                            <td class='value' style='color:#52b788;'>KES " . number_format($b['total_amount_kes'], 2) . "</td>
                        </tr>" .
                        (!empty($b['mpesa_receipt_number']) ? "
                        <tr>
                            <td class='label'>M-Pesa Receipt</td>
                            <td class='value' style='font-family:monospace;'>" . htmlspecialchars($b['mpesa_receipt_number']) . "</td>
                        </tr>" : "") . "
                        <tr>
                            <td class='label'>3rd Edition Magazine Perk</td>
                            <td class='value' style='color:#d4af37;'>" . $magCopies . " Physical Copies Entitled</td>
                        </tr>
                    </table>

                    <div class='cta-btn-wrap'>
                        <a href='" . $passUrl . "' class='cta-btn' target='_blank'>View / Print Official QR Pass</a>
                    </div>
                    
                    <p style='font-size: 13px; color: #a3a3a3; text-align: center;'>
                        Please present your digital pass QR code on arrival at Tamarind Tree Hotel for swift VIP check-in.
                    </p>
                </div>
                <div class='footer'>
                    Turning Point Magazine Africa • Malshe Media (Data Controller Reg: 03323)<br>
                    Inquiries: info@turningpointmagazine.africa | +254 700 000 000
                </div>
            </div>
        </body>
        </html>
        ";

        return sendGlobalMail($pdo, $b['contact_email'], $b['contact_name'], $subject, $body);
    } catch (Exception $e) {
        error_log("Event Booking Confirmation Email Error: " . $e->getMessage());
        return false;
    }
}

function send_event_invoice_email($pdo, $bookingId) {
    try {
        $stmt = $pdo->prepare("
            SELECT b.*, t.tier_name, t.badge_label, t.seats_per_unit, e.title AS event_title, e.event_date, e.event_time, e.venue, e.paybill_number, e.bank_details_text
            FROM tp_event_bookings b
            JOIN tp_ticket_tiers t ON b.tier_id = t.id
            JOIN tp_events e ON b.event_id = e.id
            WHERE b.id = ?
            LIMIT 1
        ");
        $stmt->execute([$bookingId]);
        $b = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$b) return false;

        $baseUrl = get_app_base_url();
        $invoiceUrl = $baseUrl . "event-invoice.php?ref=" . urlencode($b['booking_reference']) . "&token=" . urlencode($b['qr_token']);
        $formattedDate = date('l, j F Y', strtotime($b['event_date']));

        $subject = "Proforma Invoice " . $b['booking_reference'] . " - " . $b['event_title'];

        $body = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <style>
                body { margin: 0; padding: 0; background-color: #0d0d0d; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #f5f5f5; }
                .email-container { max-width: 650px; margin: 20px auto; background-color: #141414; border: 1px solid #d4af37; border-radius: 12px; overflow: hidden; }
                .header-banner { background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%); padding: 35px 30px; text-align: center; border-bottom: 2px solid #d4af37; }
                .logo-text { color: #d4af37; font-size: 26px; font-weight: 800; letter-spacing: 3px; text-transform: uppercase; margin: 0; }
                .gold-sub { color: #e6c875; font-size: 13px; letter-spacing: 2px; text-transform: uppercase; margin-top: 6px; }
                .content-box { padding: 35px 30px; line-height: 1.6; }
                .status-badge { display: inline-block; background-color: #3d2f0a; color: #e6c875; border: 1px solid #8c6d1f; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; }
                .highlight-card { background: #1c1c1c; border: 1px solid rgba(200, 16, 46, 0.4); padding: 20px; border-radius: 8px; margin: 25px 0; }
                .details-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                .details-table td { padding: 10px 0; border-bottom: 1px solid #262626; font-size: 14px; }
                .details-table td.label { color: #a3a3a3; width: 40%; }
                .details-table td.value { color: #ffffff; font-weight: 600; text-align: right; }
                .bank-box { background: #181818; border: 1px dashed #d4af37; border-radius: 8px; padding: 18px; margin: 25px 0; font-size: 13px; color: #e5e5e5; }
                .cta-btn-wrap { text-align: center; margin: 30px 0 20px; }
                .cta-btn { display: inline-block; background: linear-gradient(135deg, #d4af37 0%, #aa8421 100%); color: #0d0d0d !important; text-decoration: none; padding: 15px 35px; border-radius: 30px; font-weight: 800; font-size: 14px; letter-spacing: 1.5px; text-transform: uppercase; }
                .footer { background: #0a0a0a; padding: 25px 30px; text-align: center; font-size: 12px; color: #737373; border-top: 1px solid #222; }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='header-banner'>
                    <div class='logo-text'>TURNING POINT MAGAZINE</div>
                    <div class='gold-sub'>The Leadership Dinner 2026 • Proforma Invoice</div>
                </div>
                <div class='content-box'>
                    <span class='status-badge'>Invoice Generated</span>
                    <div class='greeting'>Attn: <strong>" . htmlspecialchars($b['contact_name']) . "</strong>" .
                        (!empty($b['organization_name']) ? " (" . htmlspecialchars($b['organization_name']) . ")" : "") . ",
                    </div>
                    <p>Thank you for initiating your corporate reservation for <strong>" . htmlspecialchars($b['event_title']) . "</strong>. Below is your official proforma invoice summary:</p>

                    <table class='details-table'>
                        <tr>
                            <td class='label'>Invoice Reference</td>
                            <td class='value' style='color:#d4af37; font-family:monospace; font-size:16px;'>" . htmlspecialchars($b['booking_reference']) . "</td>
                        </tr>
                        <tr>
                            <td class='label'>Package / Tier</td>
                            <td class='value'>" . htmlspecialchars($b['tier_name']) . "</td>
                        </tr>
                        <tr>
                            <td class='label'>Quantity & Seats</td>
                            <td class='value'>" . intval($b['quantity']) . " unit(s) (" . intval($b['total_seats']) . " Total Seats)</td>
                        </tr>
                        <tr>
                            <td class='label'>Total Due</td>
                            <td class='value' style='color:#d4af37; font-size:18px;'>KES " . number_format($b['total_amount_kes'], 2) . "</td>
                        </tr>
                        <tr>
                            <td class='label'>Event Date & Venue</td>
                            <td class='value'>" . htmlspecialchars($formattedDate) . "<br>" . htmlspecialchars($b['venue']) . "</td>
                        </tr>
                    </table>

                    <div class='bank-box'>
                        <strong style='color:#d4af37; font-size:14px; display:block; margin-bottom:8px;'>Payment Instructions:</strong>
                        " . nl2br(htmlspecialchars($b['bank_details_text'] ?? "Buy Goods Till / Paybill: 575555\nReference: " . $b['booking_reference'])) . "
                    </div>

                    <div class='cta-btn-wrap'>
                        <a href='" . $invoiceUrl . "' class='cta-btn' target='_blank'>Download / Print Proforma Invoice (PDF)</a>
                    </div>
                </div>
                <div class='footer'>
                    Turning Point Magazine Africa • Malshe Media (Data Controller Reg: 03323)<br>
                    Accounts: accounts@turningpointmagazine.africa | +254 700 000 000
                </div>
            </div>
        </body>
        </html>
        ";

        return sendGlobalMail($pdo, $b['contact_email'], $b['contact_name'], $subject, $body);
    } catch (Exception $e) {
        error_log("Event Invoice Email Error: " . $e->getMessage());
        return false;
    }
}
