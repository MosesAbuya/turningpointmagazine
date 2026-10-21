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

/**
 * Send official confirmation email with QR pass to customer,
 * and automatically notify info@turningpointmagazine.africa of the payment.
 */
function send_event_booking_confirmation_email($pdo, $bookingId, $notifyAdmin = true) {
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
                body { margin: 0; padding: 0; background-color: #0d0d0d; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #ffffff; }
                .email-container { max-width: 650px; margin: 20px auto; background-color: #141414; border: 1px solid #d4af37; border-radius: 12px; overflow: hidden; }
                .header-banner { background: linear-gradient(135deg, #181818 0%, #0d0d0d 100%); padding: 32px 25px; text-align: center; border-bottom: 2px solid #d4af37; }
                .logo-card-wrap { background-color: #ffffff; display: inline-block; padding: 10px 24px; border-radius: 10px; margin: 0 auto 14px auto; box-shadow: 0 4px 15px rgba(0,0,0,0.5); }
                .logo-img { max-height: 48px; max-width: 220px; width: auto; height: auto; display: block; margin: 0 auto; border: 0; }
                .gold-sub { color: #e6c875; font-size: 12px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; margin-top: 4px; }
                .content-box { padding: 35px 30px; line-height: 1.6; }
                .greeting { font-size: 18px; color: #ffffff !important; margin-bottom: 16px; }
                .greeting strong { color: #ffffff !important; }
                .lead-text { color: #ffffff !important; font-size: 15px; line-height: 1.7; margin: 0 0 24px 0; }
                .lead-text strong { color: #ffffff !important; font-weight: 700; }
                .status-badge { display: inline-block; background-color: #1b4332; color: #52b788; border: 1px solid #2d6a4f; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; }
                .highlight-card { background: #1c1c1c; border: 1px solid rgba(212, 175, 55, 0.4); padding: 20px; border-radius: 8px; margin: 25px 0; }
                .details-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                .details-table td { padding: 10px 0; border-bottom: 1px solid #262626; font-size: 14px; }
                .details-table td.label { color: #a3a3a3; width: 40%; }
                .details-table td.value { color: #ffffff !important; font-weight: 600; text-align: right; }
                .cta-btn-wrap { text-align: center; margin: 35px 0 20px; }
                .cta-btn { display: inline-block; background: linear-gradient(135deg, #d4af37 0%, #aa8421 100%); color: #0d0d0d !important; text-decoration: none; padding: 15px 35px; border-radius: 30px; font-weight: 800; font-size: 14px; letter-spacing: 1.5px; text-transform: uppercase; box-shadow: 0 4px 15px rgba(212,175,55,0.3); }
                .footer { background: #0a0a0a; padding: 25px 30px; text-align: center; font-size: 12px; color: #737373; border-top: 1px solid #222; }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='header-banner'>
                    <div class='logo-card-wrap' style='background-color: #ffffff; display: inline-block; padding: 10px 24px; border-radius: 10px; margin: 0 auto 12px auto; box-shadow: 0 4px 15px rgba(0,0,0,0.5);'>
                        <img src='cid:tp_logo' alt='Turning Point Magazine' class='logo-img' style='max-height: 48px; max-width: 220px; width: auto; height: auto; display: block; margin: 0 auto; border: 0;' />
                    </div>
                    <div class='gold-sub'>The Leadership Dinner 2026 &bull; Official Pass</div>
                </div>
                <div class='content-box'>
                    <span class='status-badge'>✓ Payment Confirmed</span>
                    <div class='greeting' style='color: #ffffff !important;'>Dear <strong style='color: #ffffff !important;'>" . htmlspecialchars($b['contact_name']) . "</strong>,</div>
                    <p class='lead-text' style='color: #ffffff !important; font-size: 15px; line-height: 1.7; margin: 0 0 24px 0;'>It is our distinguished pleasure to confirm your reservation for <strong style='color: #ffffff !important; font-weight: 700;'>" . htmlspecialchars($b['event_title']) . "</strong>. Your seat(s) have been secured, and your digital entry pass is now active.</p>
                    
                    <div class='highlight-card'>
                        <div style='color: #d4af37; font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;'>Pass Details</div>
                        <div style='font-size: 20px; color: #ffffff !important; font-weight: 700; margin-top: 5px;'>" . htmlspecialchars($b['tier_name']) . "</div>" .
                        (!empty($b['organization_name']) ? "<div style='color: #e6c875; font-size: 14px; margin-top: 4px;'>Organization: <strong>" . htmlspecialchars($b['organization_name']) . "</strong></div>" : "") . "
                    </div>

                    <table class='details-table'>
                        <tr>
                            <td class='label'>Booking Reference</td>
                            <td class='value' style='color:#d4af37 !important; font-family:monospace; font-size:16px;'>" . htmlspecialchars($b['booking_reference']) . "</td>
                        </tr>
                        <tr>
                            <td class='label'>Date & Time</td>
                            <td class='value' style='color:#ffffff !important;'>" . htmlspecialchars($formattedDate) . "<br><span style='font-size:12px; color:#aaa;'>" . htmlspecialchars($b['event_time']) . "</span></td>
                        </tr>
                        <tr>
                            <td class='label'>Venue</td>
                            <td class='value' style='color:#ffffff !important;'>" . htmlspecialchars($b['venue']) . "</td>
                        </tr>
                        <tr>
                            <td class='label'>Total Reserved Seats</td>
                            <td class='value' style='color:#ffffff !important;'>" . intval($b['total_seats']) . " " . ($isTable ? "Seats (VIP Table of 5)" : "Seat") . "</td>
                        </tr>
                        <tr>
                            <td class='label'>Amount Paid</td>
                            <td class='value' style='color:#52b788 !important; font-size:16px;'>KES " . number_format($b['total_amount_kes'], 2) . "</td>
                        </tr>" .
                        (!empty($b['paystack_reference']) ? "
                        <tr>
                            <td class='label'>Payment Reference</td>
                            <td class='value' style='font-family:monospace; color:#d4af37 !important;'>" . htmlspecialchars($b['paystack_reference']) . (!empty($b['paystack_channel']) ? " (" . ucfirst($b['paystack_channel']) . ")" : "") . "</td>
                        </tr>" : (!empty($b['mpesa_receipt_number']) ? "
                        <tr>
                            <td class='label'>M-Pesa Receipt</td>
                            <td class='value' style='font-family:monospace; color:#ffffff !important;'>" . htmlspecialchars($b['mpesa_receipt_number']) . "</td>
                        </tr>" : "")) . "
                        <tr>
                            <td class='label'>3rd Edition Magazine Perk</td>
                            <td class='value' style='color:#d4af37 !important; font-weight:600;'>" . $magCopies . " Physical Copies Entitled</td>
                        </tr>
                    </table>

                    <div class='cta-btn-wrap'>
                        <a href='" . $passUrl . "' class='cta-btn' target='_blank'>View / Print Official QR Pass</a>
                    </div>
                    
                    <p style='font-size: 13px; color: #a3a3a3; text-align: center; margin-top: 20px; line-height: 1.5;'>
                        Please present your digital pass QR code on arrival at Tamarind Tree Hotel for swift VIP check-in.
                    </p>
                </div>
                <div class='footer'>
                    Turning Point Magazine Africa &bull; Malshe Media (Data Controller Reg: 03323)<br>
                    Inquiries: <a href='mailto:info@turningpointmagazine.africa' style='color: #d4af37; text-decoration: none;'>info@turningpointmagazine.africa</a>
                </div>
            </div>
        </body>
        </html>
        ";

        $customerSent = sendGlobalMail($pdo, $b['contact_email'], $b['contact_name'], $subject, $body);

        // Also notify info@turningpointmagazine.africa of the payment
        if ($notifyAdmin) {
            send_event_admin_payment_notification($pdo, $bookingId);
        }

        return $customerSent;
    } catch (Exception $e) {
        error_log("Event Booking Confirmation Email Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Send an administrative payment notification email to info@turningpointmagazine.africa
 */
function send_event_admin_payment_notification($pdo, $bookingId) {
    try {
        $stmt = $pdo->prepare("
            SELECT b.*, t.tier_name, t.badge_label, t.seats_per_unit, e.title AS event_title, e.event_date, e.event_time, e.venue
            FROM tp_event_bookings b
            JOIN tp_ticket_tiers t ON b.tier_id = t.id
            JOIN tp_events e ON b.event_id = e.id
            WHERE b.id = ?
            LIMIT 1
        ");
        $stmt->execute([$bookingId]);
        $b = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$b) return false;

        // Fetch guests
        $stmtGuests = $pdo->prepare("SELECT * FROM tp_event_guests WHERE booking_id = ? ORDER BY seat_number ASC");
        $stmtGuests->execute([$b['id']]);
        $guests = $stmtGuests->fetchAll(PDO::FETCH_ASSOC);

        $baseUrl = get_app_base_url();
        $adminPortalUrl = $baseUrl . "admin/events_ticketing.php?tab=bookings&search=" . urlencode($b['booking_reference']);
        $passUrl = $baseUrl . "ticket-pass.php?ref=" . urlencode($b['booking_reference']) . "&token=" . urlencode($b['qr_token']);
        
        $isTable = ($b['booking_type'] === 'corporate_table');
        $magCopies = $isTable ? ($b['quantity'] * 5) : $b['quantity'];
        $formattedDate = date('l, j F Y', strtotime($b['event_date']));

        $subject = "[PAYMENT RECEIVED] KES " . number_format($b['total_amount_kes'], 2) . " - " . $b['contact_name'] . " [" . $b['booking_reference'] . "]";

        $guestRowsHtml = "";
        if (!empty($guests)) {
            $guestRowsHtml = "<div style='margin-top: 25px; border-top: 1px solid #262626; padding-top: 15px;'>
                <div style='color: #d4af37; font-size: 14px; font-weight: 700; text-transform: uppercase; margin-bottom: 10px;'>Registered Delegation Guests:</div>
                <table style='width: 100%; border-collapse: collapse; font-size: 13px;'>";
            foreach ($guests as $g) {
                $guestRowsHtml .= "<tr>
                    <td style='padding: 6px 0; color: #a3a3a3; width: 25%;'>Seat #" . intval($g['seat_number']) . ":</td>
                    <td style='padding: 6px 0; color: #ffffff; font-weight: 600;'>" . htmlspecialchars($g['guest_name'] ?? 'Pending') . 
                    (!empty($g['guest_email']) ? " (" . htmlspecialchars($g['guest_email']) . ")" : "") . 
                    (!empty($g['dietary_requirements']) ? "<br><span style='color: #e6c875; font-size: 11px;'>Dietary: " . htmlspecialchars($g['dietary_requirements']) . "</span>" : "") . 
                    "</td>
                </tr>";
            }
            $guestRowsHtml .= "</table></div>";
        }

        $body = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <style>
                body { margin: 0; padding: 0; background-color: #0d0d0d; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #ffffff; }
                .email-container { max-width: 650px; margin: 20px auto; background-color: #141414; border: 1px solid #d4af37; border-radius: 12px; overflow: hidden; }
                .header-banner { background: linear-gradient(135deg, #181818 0%, #0d0d0d 100%); padding: 32px 25px; text-align: center; border-bottom: 2px solid #d4af37; }
                .logo-card-wrap { background-color: #ffffff; display: inline-block; padding: 10px 24px; border-radius: 10px; margin: 0 auto 12px auto; box-shadow: 0 4px 15px rgba(0,0,0,0.5); }
                .logo-img { max-height: 48px; max-width: 220px; width: auto; height: auto; display: block; margin: 0 auto; border: 0; }
                .admin-tag { color: #e6c875; font-size: 12px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; margin-top: 4px; }
                .content-box { padding: 35px 30px; line-height: 1.6; }
                .status-badge { display: inline-block; background-color: #1b4332; color: #52b788; border: 1px solid #2d6a4f; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; }
                .amount-card { background: #1c1c1c; border: 1px solid rgba(82, 183, 136, 0.5); padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center; }
                .amount-val { font-size: 28px; font-weight: 800; color: #52b788; }
                .amount-sub { color: #a3a3a3; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; margin-top: 4px; }
                .details-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                .details-table td { padding: 9px 0; border-bottom: 1px solid #262626; font-size: 14px; }
                .details-table td.label { color: #a3a3a3; width: 38%; }
                .details-table td.value { color: #ffffff !important; font-weight: 600; text-align: right; }
                .cta-btn-wrap { text-align: center; margin: 30px 0 15px; }
                .cta-btn-primary { display: inline-block; background: linear-gradient(135deg, #d4af37 0%, #aa8421 100%); color: #0d0d0d !important; text-decoration: none; padding: 12px 28px; border-radius: 30px; font-weight: 800; font-size: 13px; letter-spacing: 1.2px; text-transform: uppercase; margin: 5px; }
                .cta-btn-secondary { display: inline-block; background: #262626; color: #ffffff !important; border: 1px solid #404040; text-decoration: none; padding: 12px 24px; border-radius: 30px; font-weight: 600; font-size: 13px; margin: 5px; }
                .footer { background: #0a0a0a; padding: 20px 30px; text-align: center; font-size: 12px; color: #737373; border-top: 1px solid #222; }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='header-banner'>
                    <div class='logo-card-wrap' style='background-color: #ffffff; display: inline-block; padding: 10px 24px; border-radius: 10px; margin: 0 auto 12px auto; box-shadow: 0 4px 15px rgba(0,0,0,0.5);'>
                        <img src='cid:tp_logo' alt='Turning Point Magazine' class='logo-img' style='max-height: 48px; max-width: 220px; width: auto; height: auto; display: block; margin: 0 auto; border: 0;' />
                    </div>
                    <div class='admin-tag'>Admin Notification &bull; The Leadership Dinner 2026</div>
                </div>
                <div class='content-box'>
                    <span class='status-badge'>✓ Payment Successfully Received</span>
                    <h2 style='color: #ffffff; font-size: 20px; margin: 15px 0 8px 0;'>New Ticket Payment Confirmed</h2>
                    <p style='color: #ffffff !important; font-size: 14px; line-height: 1.6; margin: 0 0 15px 0;'>
                        A payment has just been received and confirmed for <strong>" . htmlspecialchars($b['event_title']) . "</strong>. The customer's official VIP digital pass has been activated and dispatched.
                    </p>

                    <div class='amount-card'>
                        <div class='amount-val'>KES " . number_format($b['total_amount_kes'], 2) . "</div>
                        <div class='amount-sub'>Status: PAID &bull; Method: " . strtoupper(htmlspecialchars($b['payment_method'])) . (!empty($b['paystack_channel']) ? " (" . ucfirst($b['paystack_channel']) . ")" : "") . "</div>
                    </div>

                    <table class='details-table'>
                        <tr>
                            <td class='label'>Booking Reference</td>
                            <td class='value' style='color:#d4af37 !important; font-family:monospace; font-size:15px;'>" . htmlspecialchars($b['booking_reference']) . "</td>
                        </tr>
                        <tr>
                            <td class='label'>Customer Name</td>
                            <td class='value' style='color:#ffffff !important;'>" . htmlspecialchars($b['contact_name']) . "</td>
                        </tr>
                        <tr>
                            <td class='label'>Organization</td>
                            <td class='value' style='color:#ffffff !important;'>" . (!empty($b['organization_name']) ? htmlspecialchars($b['organization_name']) : "<span style='color:#888;'>Individual</span>") . "</td>
                        </tr>
                        <tr>
                            <td class='label'>Contact Email</td>
                            <td class='value'><a href='mailto:" . htmlspecialchars($b['contact_email']) . "' style='color:#d4af37; text-decoration:none;'>" . htmlspecialchars($b['contact_email']) . "</a></td>
                        </tr>
                        <tr>
                            <td class='label'>Contact Phone</td>
                            <td class='value'><a href='tel:" . htmlspecialchars($b['contact_phone']) . "' style='color:#ffffff; text-decoration:none;'>" . htmlspecialchars($b['contact_phone']) . "</a></td>
                        </tr>
                        <tr>
                            <td class='label'>Ticket Tier</td>
                            <td class='value' style='color:#ffffff !important;'>" . htmlspecialchars($b['tier_name']) . " (" . htmlspecialchars($b['badge_label']) . ")</td>
                        </tr>
                        <tr>
                            <td class='label'>Seats Reserved</td>
                            <td class='value' style='color:#ffffff !important;'>" . intval($b['total_seats']) . " " . ($isTable ? "Seats (VIP Table of 5)" : "Seat") . "</td>
                        </tr>
                        <tr>
                            <td class='label'>Magazines Entitled</td>
                            <td class='value' style='color:#d4af37 !important;'>" . $magCopies . " Physical Copies</td>
                        </tr>" .
                        (!empty($b['paystack_reference']) ? "
                        <tr>
                            <td class='label'>Paystack Reference</td>
                            <td class='value' style='font-family:monospace; color:#d4af37 !important;'>" . htmlspecialchars($b['paystack_reference']) . "</td>
                        </tr>" : (!empty($b['mpesa_receipt_number']) ? "
                        <tr>
                            <td class='label'>M-Pesa Receipt</td>
                            <td class='value' style='font-family:monospace; color:#ffffff !important;'>" . htmlspecialchars($b['mpesa_receipt_number']) . "</td>
                        </tr>" : "")) . "
                        <tr>
                            <td class='label'>Date & Time Paid</td>
                            <td class='value' style='color:#ffffff !important;'>" . (!empty($b['paid_at']) ? date('M j, Y - g:i A', strtotime($b['paid_at'])) : date('M j, Y - g:i A')) . "</td>
                        </tr>" .
                        (!empty($b['dietary_notes']) ? "
                        <tr>
                            <td class='label'>Dietary / Notes</td>
                            <td class='value' style='color:#e6c875 !important;'>" . htmlspecialchars($b['dietary_notes']) . "</td>
                        </tr>" : "") . "
                    </table>

                    " . $guestRowsHtml . "

                    <div class='cta-btn-wrap'>
                        <a href='" . $adminPortalUrl . "' class='cta-btn-primary' target='_blank'>Manage in Admin Portal</a>
                        <a href='" . $passUrl . "' class='cta-btn-secondary' target='_blank'>View Customer QR Pass</a>
                    </div>
                </div>
                <div class='footer'>
                    Turning Point Magazine Africa &bull; Automated Internal Payment System
                </div>
            </div>
        </body>
        </html>
        ";

        $adminEmail = 'info@turningpointmagazine.africa';
        return sendGlobalMail($pdo, $adminEmail, 'Turning Point Admin', $subject, $body);
    } catch (Exception $e) {
        error_log("Event Admin Payment Notification Error: " . $e->getMessage());
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
                body { margin: 0; padding: 0; background-color: #0d0d0d; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #ffffff; }
                .email-container { max-width: 650px; margin: 20px auto; background-color: #141414; border: 1px solid #d4af37; border-radius: 12px; overflow: hidden; }
                .header-banner { background: linear-gradient(135deg, #181818 0%, #0d0d0d 100%); padding: 32px 25px; text-align: center; border-bottom: 2px solid #d4af37; }
                .logo-card-wrap { background-color: #ffffff; display: inline-block; padding: 10px 24px; border-radius: 10px; margin: 0 auto 14px auto; box-shadow: 0 4px 15px rgba(0,0,0,0.5); }
                .logo-img { max-height: 48px; max-width: 220px; width: auto; height: auto; display: block; margin: 0 auto; border: 0; }
                .gold-sub { color: #e6c875; font-size: 12px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; margin-top: 4px; }
                .content-box { padding: 35px 30px; line-height: 1.6; }
                .status-badge { display: inline-block; background-color: #3d2f0a; color: #e6c875; border: 1px solid #8c6d1f; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; }
                .details-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                .details-table td { padding: 10px 0; border-bottom: 1px solid #262626; font-size: 14px; }
                .details-table td.label { color: #a3a3a3; width: 40%; }
                .details-table td.value { color: #ffffff !important; font-weight: 600; text-align: right; }
                .bank-box { background: #181818; border: 1px dashed #d4af37; border-radius: 8px; padding: 18px; margin: 25px 0; font-size: 13px; color: #e5e5e5; }
                .cta-btn-wrap { text-align: center; margin: 30px 0 20px; }
                .cta-btn { display: inline-block; background: linear-gradient(135deg, #d4af37 0%, #aa8421 100%); color: #0d0d0d !important; text-decoration: none; padding: 15px 35px; border-radius: 30px; font-weight: 800; font-size: 14px; letter-spacing: 1.5px; text-transform: uppercase; }
                .footer { background: #0a0a0a; padding: 25px 30px; text-align: center; font-size: 12px; color: #737373; border-top: 1px solid #222; }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='header-banner'>
                    <div class='logo-card-wrap' style='background-color: #ffffff; display: inline-block; padding: 10px 24px; border-radius: 10px; margin: 0 auto 12px auto; box-shadow: 0 4px 15px rgba(0,0,0,0.5);'>
                        <img src='cid:tp_logo' alt='Turning Point Magazine' class='logo-img' style='max-height: 48px; max-width: 220px; width: auto; height: auto; display: block; margin: 0 auto; border: 0;' />
                    </div>
                    <div class='gold-sub'>The Leadership Dinner 2026 &bull; Proforma Invoice</div>
                </div>
                <div class='content-box'>
                    <span class='status-badge'>Invoice Generated</span>
                    <div class='greeting' style='color: #ffffff !important; font-size: 18px; margin-bottom: 15px;'>Attn: <strong style='color: #ffffff !important;'>" . htmlspecialchars($b['contact_name']) . "</strong>" .
                        (!empty($b['organization_name']) ? " (" . htmlspecialchars($b['organization_name']) . ")" : "") . ",
                    </div>
                    <p style='color: #ffffff !important; font-size: 15px; line-height: 1.7; margin: 0 0 20px 0;'>Thank you for initiating your corporate reservation for <strong style='color: #ffffff !important;'>" . htmlspecialchars($b['event_title']) . "</strong>. Below is your official proforma invoice summary:</p>

                    <table class='details-table'>
                        <tr>
                            <td class='label'>Invoice Reference</td>
                            <td class='value' style='color:#d4af37 !important; font-family:monospace; font-size:16px;'>" . htmlspecialchars($b['booking_reference']) . "</td>
                        </tr>
                        <tr>
                            <td class='label'>Package / Tier</td>
                            <td class='value' style='color:#ffffff !important;'>" . htmlspecialchars($b['tier_name']) . "</td>
                        </tr>
                        <tr>
                            <td class='label'>Quantity & Seats</td>
                            <td class='value' style='color:#ffffff !important;'>" . intval($b['quantity']) . " unit(s) (" . intval($b['total_seats']) . " Total Seats)</td>
                        </tr>
                        <tr>
                            <td class='label'>Total Due</td>
                            <td class='value' style='color:#d4af37 !important; font-size:18px;'>KES " . number_format($b['total_amount_kes'], 2) . "</td>
                        </tr>
                        <tr>
                            <td class='label'>Event Date & Venue</td>
                            <td class='value' style='color:#ffffff !important;'>" . htmlspecialchars($formattedDate) . "<br>" . htmlspecialchars($b['venue']) . "</td>
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
                    Turning Point Magazine Africa &bull; Malshe Media (Data Controller Reg: 03323)<br>
                    Accounts: <a href='mailto:accounts@turningpointmagazine.africa' style='color: #d4af37; text-decoration: none;'>accounts@turningpointmagazine.africa</a>
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

function send_event_ticket_email($pdo, $bookingId) {
    return send_event_booking_confirmation_email($pdo, $bookingId, true);
}
