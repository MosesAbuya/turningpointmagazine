<?php
/**
 * Events & Ticketing Module - Admin Dashboard
 * Turning Point Magazine - The Leadership Dinner 2026
 */

session_start();
require_once __DIR__ . '/../connection2.php';
require_once __DIR__ . '/../includes/event_db_helper.php';
require_once __DIR__ . '/../includes/event_email_helper.php';

// Session Timeout Authentication Check
$inactiveTimeout = 600;
if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['last_activity'])) {
        $timeDiff = time() - $_SESSION['last_activity'];
        if ($timeDiff > $inactiveTimeout) {
            session_unset();
            session_destroy();
            header("Location: login.php?timeout=true");
            exit();
        }
    }
    $_SESSION['last_activity'] = time();
} else {
    header("Location: login.php");
    exit();
}

$pdo = connect();
ensure_event_tables_exist($pdo);

$feedbackMsg = '';
$feedbackType = 'success';

// --- CSV EXPORT HANDLER ---
if (isset($_GET['action']) && $_GET['action'] === 'export_csv') {
    $stmt = $pdo->query("
        SELECT b.booking_reference, b.booking_type, t.tier_name, b.organization_name,
               b.contact_name, b.contact_email, b.contact_phone, b.quantity, b.total_seats,
               b.total_amount_kes, b.payment_method, b.payment_status, b.paystack_reference, b.mpesa_receipt_number,
               b.paid_at, b.dietary_notes, b.checked_in_count, b.created_at
        FROM tp_event_bookings b
        JOIN tp_ticket_tiers t ON b.tier_id = t.id
        ORDER BY b.created_at DESC
    ");
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=leadership_dinner_2026_delegates_' . date('Y-m-d_His') . '.csv');

    $output = fopen('php://output', 'w');
    // UTF-8 BOM for Excel
    fputs($output, "\xEF\xBB\xBF");

    fputcsv($output, [
        'Booking Ref',
        'Tier / Package',
        'Organization Name',
        'Primary Contact',
        'Email Address',
        'Phone Number',
        'Units Booked',
        'Total Seats',
        'Magazine Copies Entitled',
        'Total Amount (KES)',
        'Payment Method',
        'Payment Status',
        'M-Pesa / Bank Receipt',
        'Payment Date',
        'Dietary / Special Notes',
        'Guests Registered',
        'Checked In Seats',
        'Booking Date'
    ]);

    foreach ($bookings as $row) {
        // Fetch registered guest names
        $gStmt = $pdo->prepare("SELECT seat_number, guest_name, dietary_requirements FROM tp_event_guests WHERE booking_id = (SELECT id FROM tp_event_bookings WHERE booking_reference = ? LIMIT 1)");
        $gStmt->execute([$row['booking_reference']]);
        $guestRows = $gStmt->fetchAll(PDO::FETCH_ASSOC);
        $guestsFormatted = [];
        foreach ($guestRows as $gr) {
            $guestsFormatted[] = "Seat " . $gr['seat_number'] . ": " . ($gr['guest_name'] ?: 'N/A') . (!empty($gr['dietary_requirements']) ? " (" . $gr['dietary_requirements'] . ")" : "");
        }
        $guestString = implode(' | ', $guestsFormatted);

        $magCopies = ($row['booking_type'] === 'corporate_table') ? ($row['quantity'] * 5) : $row['quantity'];

        fputcsv($output, [
            $row['booking_reference'],
            $row['tier_name'],
            $row['organization_name'] ?: 'N/A (Individual)',
            $row['contact_name'],
            $row['contact_email'],
            $row['contact_phone'],
            $row['quantity'],
            $row['total_seats'],
            $magCopies,
            $row['total_amount_kes'],
            $row['payment_method'],
            strtoupper($row['payment_status']),
            $row['paystack_reference'] ?: ($row['mpesa_receipt_number'] ?: 'N/A'),
            $row['paid_at'] ?: 'N/A',
            $row['dietary_notes'] ?: 'None',
            $guestString ?: 'None specified',
            $row['checked_in_count'],
            $row['created_at']
        ]);
    }
    fclose($output);
    exit;
}

// --- POST ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['admin_action'] ?? '';

    // 1. Save Event Settings (Tab 1)
    if ($action === 'save_event_settings') {
        $eventId = intval($_POST['event_id'] ?? 1);
        $title = trim($_POST['title'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $eventDate = trim($_POST['event_date'] ?? '2026-11-06');
        $eventTime = trim($_POST['event_time'] ?? '4:00 PM – 9:00 PM EAT');
        $venue = trim($_POST['venue'] ?? '');
        $principalName = trim($_POST['principal_guest_name'] ?? '');
        $principalTitle = trim($_POST['principal_guest_title'] ?? '');
        $patronName = trim($_POST['patron_name'] ?? '');
        $patronTitle = trim($_POST['patron_title'] ?? '');
        $highlight = trim($_POST['special_highlight'] ?? '');
        $badgeText = trim($_POST['hero_badge_text'] ?? '');
        $paybill = trim($_POST['paybill_number'] ?? '575555');
        $bankDetails = trim($_POST['bank_details_text'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $stmt = $pdo->prepare("
            UPDATE tp_events SET
                title = ?, subtitle = ?, event_date = ?, event_time = ?, venue = ?,
                principal_guest_name = ?, principal_guest_title = ?, patron_name = ?, patron_title = ?,
                special_highlight = ?, hero_badge_text = ?, paybill_number = ?,
                bank_details_text = ?, is_active = ?, updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([
            $title, $subtitle, $eventDate, $eventTime, $venue,
            $principalName, $principalTitle, $patronName, $patronTitle,
            $highlight, $badgeText, $paybill, $bankDetails, $isActive, $eventId
        ]);

        $feedbackMsg = "Event settings successfully updated.";
    }

    // 2. Save Tier Settings & Perks (Tab 1)
    if ($action === 'save_tier_settings') {
        $tierId = intval($_POST['tier_id'] ?? 0);
        $tierName = trim($_POST['tier_name'] ?? '');
        $badgeLabel = trim($_POST['badge_label'] ?? '');
        $price = floatval($_POST['price_kes'] ?? 0);
        $seats = intval($_POST['seats_per_unit'] ?? 1);
        $capacity = intval($_POST['max_capacity'] ?? 100);
        $featured = !empty($_POST['is_featured']) ? 1 : 0;
        $active = !empty($_POST['is_active']) ? 1 : 0;

        // Process Perks array from textarea (one perk per line)
        $perksRaw = trim($_POST['perks_raw'] ?? '');
        $perksArray = array_values(array_filter(array_map('trim', explode("\n", $perksRaw))));
        $perksJson = json_encode($perksArray);

        $stmt = $pdo->prepare("
            UPDATE tp_ticket_tiers SET
                tier_name = ?, badge_label = ?, price_kes = ?, seats_per_unit = ?,
                perks_json = ?, max_capacity = ?, is_featured = ?, is_active = ?, updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$tierName, $badgeLabel, $price, $seats, $perksJson, $capacity, $featured, $active, $tierId]);

        $feedbackMsg = "Ticket tier '{$tierName}' and perks successfully updated.";
    }

    // 2b. Restore Default Tiers (Tab 1 Fallback)
    if ($action === 'restore_default_tiers') {
        $sqlFile = __DIR__ . '/../sql/leadership_dinner_2026_schema.sql';
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            $queries = array_filter(array_map('trim', explode(';', $sql)));
            foreach ($queries as $query) {
                if (!empty($query)) {
                    try {
                        $pdo->exec($query);
                    } catch (Exception $ex) {}
                }
            }
        }
        $pdo->exec("UPDATE `tp_ticket_tiers` SET `is_active` = 1");
        $feedbackMsg = "Default ticket tiers and perks successfully restored and activated.";
    }

    // 3. Mark Invoice as Paid (Tab 2)
    if ($action === 'mark_invoice_paid') {
        $bookingId = intval($_POST['booking_id'] ?? 0);
        $receipt = trim($_POST['mpesa_receipt_number'] ?? '');
        if (empty($receipt)) {
            $receipt = 'INV-PAY-' . strtoupper(substr(md5(time()), 0, 8));
        }

        $stmt = $pdo->prepare("
            UPDATE tp_event_bookings SET
                payment_status = 'paid', mpesa_receipt_number = ?, paid_at = NOW(), updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$receipt, $bookingId]);

        // Send Official Confirmation Pass Email
        send_event_booking_confirmation_email($pdo, $bookingId);

        $feedbackMsg = "Invoice marked as paid. Official QR pass and confirmation email dispatched to client.";
    }

    // 4. Resend Email (Tab 2)
    if ($action === 'resend_email') {
        $bookingId = intval($_POST['booking_id'] ?? 0);
        $stmt = $pdo->prepare("SELECT payment_status FROM tp_event_bookings WHERE id = ?");
        $stmt->execute([$bookingId]);
        $status = $stmt->fetchColumn();

        if ($status === 'paid') {
            send_event_booking_confirmation_email($pdo, $bookingId);
            $feedbackMsg = "Confirmation pass email resent to client.";
        } else {
            send_event_invoice_email($pdo, $bookingId);
            $feedbackMsg = "Proforma invoice email resent to client.";
        }
    }

    // 5. Update Guests List (Tab 2)
    if ($action === 'update_guests') {
        $bookingId = intval($_POST['booking_id'] ?? 0);
        if ($bookingId > 0 && !empty($_POST['guests']) && is_array($_POST['guests'])) {
            $pdo->prepare("DELETE FROM tp_event_guests WHERE booking_id = ?")->execute([$bookingId]);
            $stmtIns = $pdo->prepare("
                INSERT INTO tp_event_guests (booking_id, seat_number, guest_name, guest_email, dietary_requirements)
                VALUES (?, ?, ?, ?, ?)
            ");
            foreach ($_POST['guests'] as $seatNum => $gData) {
                $gName = trim($gData['name'] ?? '');
                $gEmail = trim($gData['email'] ?? '');
                $gDiet = trim($gData['dietary'] ?? '');
                if (!empty($gName) || !empty($gDiet)) {
                    $stmtIns->execute([$bookingId, $seatNum, $gName, $gEmail, $gDiet]);
                }
            }
            $feedbackMsg = "Guest roster successfully updated.";
        }
    }

    // 6. Check-in Guest (Tab 3)
    if ($action === 'perform_checkin') {
        $bookingId = intval($_POST['booking_id'] ?? 0);
        $checkinCount = intval($_POST['checkin_count'] ?? 1);

        $stmt = $pdo->prepare("UPDATE tp_event_bookings SET checked_in_count = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$checkinCount, $bookingId]);

        $feedbackMsg = "Check-in updated successfully ({$checkinCount} delegates checked in).";
    }
}

// Fetch Flagship Event and Tiers for CMS
$event = get_flagship_event($pdo, 'leadership-dinner-2026');
$tiers = get_event_tiers($pdo, $event['id'], false); // Retrieve all tiers (active & inactive) for admin management

// KPI Metrics
$kpi_revenue = $pdo->query("SELECT COALESCE(SUM(total_amount_kes), 0) FROM tp_event_bookings WHERE payment_status = 'paid'")->fetchColumn();
$kpi_tables = $pdo->query("SELECT COALESCE(SUM(quantity), 0) FROM tp_event_bookings WHERE booking_type = 'corporate_table' AND payment_status = 'paid'")->fetchColumn();
$kpi_individual = $pdo->query("SELECT COALESCE(SUM(quantity), 0) FROM tp_event_bookings WHERE booking_type = 'individual' AND payment_status = 'paid'")->fetchColumn();
$kpi_guests = $pdo->query("SELECT COALESCE(SUM(total_seats), 0) FROM tp_event_bookings WHERE payment_status = 'paid'")->fetchColumn();
$kpi_invoices = $pdo->query("SELECT COUNT(*) FROM tp_event_bookings WHERE payment_status = 'invoice_requested'")->fetchColumn();

// Bookings Query with Filters
$filterStatus = $_GET['filter_status'] ?? 'all';
$filterType = $_GET['filter_type'] ?? 'all';
$searchQuery = trim($_GET['search'] ?? '');

$sqlBookings = "
    SELECT b.*, t.tier_name, t.badge_label 
    FROM tp_event_bookings b
    JOIN tp_ticket_tiers t ON b.tier_id = t.id
    WHERE 1=1
";
$bParams = [];

if ($filterStatus !== 'all') {
    $sqlBookings .= " AND b.payment_status = ?";
    $bParams[] = $filterStatus;
}
if ($filterType !== 'all') {
    $sqlBookings .= " AND b.booking_type = ?";
    $bParams[] = $filterType;
}
if (!empty($searchQuery)) {
    $sqlBookings .= " AND (b.booking_reference LIKE ? OR b.organization_name LIKE ? OR b.contact_name LIKE ? OR b.contact_email LIKE ? OR b.contact_phone LIKE ? OR b.paystack_reference LIKE ? OR b.mpesa_receipt_number LIKE ?)";
    $likeQ = '%' . $searchQuery . '%';
    $bParams = array_merge($bParams, [$likeQ, $likeQ, $likeQ, $likeQ, $likeQ, $likeQ, $likeQ]);
}
$sqlBookings .= " ORDER BY b.created_at DESC";

$stmtB = $pdo->prepare($sqlBookings);
$stmtB->execute($bParams);
$allBookings = $stmtB->fetchAll(PDO::FETCH_ASSOC);

// Check-in Lookup Handler (Tab 3)
$lookupBooking = null;
$lookupGuests = [];
$lookupRef = trim($_GET['lookup_ref'] ?? ($_GET['ref'] ?? ''));
if (!empty($lookupRef)) {
    $stmtL = $pdo->prepare("
        SELECT b.*, t.tier_name, t.badge_label, e.title AS event_title, e.venue, e.event_date
        FROM tp_event_bookings b
        JOIN tp_ticket_tiers t ON b.tier_id = t.id
        JOIN tp_events e ON b.event_id = e.id
        WHERE b.booking_reference = ? OR b.qr_token = ?
        LIMIT 1
    ");
    $stmtL->execute([$lookupRef, $lookupRef]);
    $lookupBooking = $stmtL->fetch(PDO::FETCH_ASSOC);

    if ($lookupBooking) {
        $stmtLG = $pdo->prepare("SELECT * FROM tp_event_guests WHERE booking_id = ? ORDER BY seat_number ASC");
        $stmtLG->execute([$lookupBooking['id']]);
        $lookupGuests = $stmtLG->fetchAll(PDO::FETCH_ASSOC);
    }
}

$activeTab = $_REQUEST['tab'] ?? 'bookings';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events & Ticketing - Admin Dashboard</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">

    <style>
        .kpi-card {
            border-radius: 12px;
            padding: 22px;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            transition: transform 0.2s ease;
        }
        .kpi-card:hover {
            transform: translateY(-2px);
        }
        .kpi-card .kpi-num {
            font-size: 26px;
            font-weight: 800;
            margin: 6px 0 2px;
        }
        .kpi-card .kpi-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
        }
        .kpi-card .kpi-icon {
            position: absolute;
            right: 18px;
            bottom: 18px;
            font-size: 40px;
            opacity: 0.2;
        }
        .bg-kpi-gold { background: linear-gradient(135deg, #d4af37 0%, #aa8421 100%); color: #0d0d0d !important; }
        .bg-kpi-crimson { background: linear-gradient(135deg, #c8102e 0%, #900c20 100%); }
        .bg-kpi-blue { background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); }
        .bg-kpi-green { background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); }
        .bg-kpi-purple { background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); }

        .nav-tabs-luxury .nav-link {
            font-weight: 700;
            font-size: 14px;
            color: #4b5563;
            border: none;
            border-bottom: 3px solid transparent;
            padding: 12px 20px;
            border-radius: 0;
        }
        .nav-tabs-luxury .nav-link.active {
            color: #d4af37;
            border-bottom: 3px solid #d4af37;
            background: transparent;
        }

        .badge-status-paid { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; font-weight: 700; }
        .badge-status-invoice { background: #fef9c3; color: #854d0e; border: 1px solid #fef08a; font-weight: 700; }
        .badge-status-pending { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; font-weight: 700; }
        .badge-status-failed { background: #f3f4f6; color: #6b7280; border: 1px solid #e5e7eb; font-weight: 700; }

        .table-bookings td {
            vertical-align: middle;
            font-size: 13px;
        }
    </style>
</head>
<body>

    <?php include 'nav.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div id="page-content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1"><i class="fas fa-ticket-alt text-warning me-2"></i>Events & Ticketing Module</h2>
                <div class="text-muted small">The Leadership Dinner 2026 • Real-Time Accreditation & Dynamic CMS</div>
            </div>
            <div>
                <a href="events_ticketing.php?action=export_csv" class="btn btn-success btn-sm px-3 shadow-sm" style="border-radius: 20px;">
                    <i class="fas fa-file-excel me-1"></i> Export Guest List (CSV)
                </a>
                <a href="../leadership-dinner-2026.php" target="_blank" class="btn btn-outline-dark btn-sm px-3 ms-2" style="border-radius: 20px;">
                    <i class="fas fa-external-link-alt me-1"></i> View Live Landing Page
                </a>
            </div>
        </div>

        <?php if (!empty($feedbackMsg)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?= htmlspecialchars($feedbackMsg) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- KPI SUMMARY CARDS -->
        <div class="row g-3 mb-4">
            <div class="col-xl col-md-4 col-sm-6">
                <div class="kpi-card bg-kpi-gold">
                    <div class="kpi-title">Total Revenue (KES)</div>
                    <div class="kpi-num">KES <?= number_format($kpi_revenue) ?></div>
                    <div style="font-size: 11px;">M-Pesa & Corporate Bank Paid</div>
                    <i class="fas fa-wallet kpi-icon"></i>
                </div>
            </div>
            <div class="col-xl col-md-4 col-sm-6">
                <div class="kpi-card bg-kpi-crimson">
                    <div class="kpi-title">VIP Tables Booked</div>
                    <div class="kpi-num"><?= intval($kpi_tables) ?> <small style="font-size:13px; font-weight:normal;">Tables</small></div>
                    <div style="font-size: 11px;"><?= $kpi_tables * 5 ?> Total Corporate Guests</div>
                    <i class="fas fa-building kpi-icon"></i>
                </div>
            </div>
            <div class="col-xl col-md-4 col-sm-6">
                <div class="kpi-card bg-kpi-blue">
                    <div class="kpi-title">Individual Passes</div>
                    <div class="kpi-num"><?= intval($kpi_individual) ?> <small style="font-size:13px; font-weight:normal;">Seats</small></div>
                    <div style="font-size: 11px;">Single Delegate Seats</div>
                    <i class="fas fa-user-tie kpi-icon"></i>
                </div>
            </div>
            <div class="col-xl col-md-4 col-sm-6">
                <div class="kpi-card bg-kpi-green">
                    <div class="kpi-title">Confirmed Attendees</div>
                    <div class="kpi-num"><?= intval($kpi_guests) ?> <small style="font-size:13px; font-weight:normal;">Guests</small></div>
                    <div style="font-size: 11px;">Total accredited dinner seats</div>
                    <i class="fas fa-users kpi-icon"></i>
                </div>
            </div>
            <div class="col-xl col-md-4 col-sm-6">
                <div class="kpi-card bg-kpi-purple">
                    <div class="kpi-title">Invoices Awaiting Wire</div>
                    <div class="kpi-num"><?= intval($kpi_invoices) ?></div>
                    <div style="font-size: 11px;">Corporate Proformas Issued</div>
                    <i class="fas fa-file-invoice-dollar kpi-icon"></i>
                </div>
            </div>
        </div>

        <!-- NAVIGATION TABS -->
        <ul class="nav nav-tabs nav-tabs-luxury mb-4" id="eventTabNav">
            <li class="nav-item">
                <a class="nav-link <?= ($activeTab === 'bookings' ? 'active' : '') ?>" href="?tab=bookings">
                    <i class="fas fa-list me-1"></i> Bookings & Corporate Invoices (<?= count($allBookings) ?>)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($activeTab === 'cms' ? 'active' : '') ?>" href="?tab=cms">
                    <i class="fas fa-sliders-h me-1"></i> Event & Tier Settings (100% Dynamic CMS)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($activeTab === 'checkin' ? 'active' : '') ?>" href="?tab=checkin">
                    <i class="fas fa-qrcode me-1"></i> QR Check-In & Verification Lookup
                </a>
            </li>
        </ul>

        <!-- ==================== SUB-TAB 1: BOOKINGS & INVOICES ==================== -->
        <?php if ($activeTab === 'bookings'): ?>
        <div class="content-card shadow-sm p-4 bg-white rounded-3">
            
            <!-- Filter Bar -->
            <form method="GET" class="row g-2 mb-3">
                <input type="hidden" name="tab" value="bookings">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search ref, org, name, phone, receipt..." value="<?= htmlspecialchars($searchQuery) ?>">
                </div>
                <div class="col-md-2">
                    <select name="filter_status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="all" <?= ($filterStatus === 'all' ? 'selected' : '') ?>>All Statuses</option>
                        <option value="paid" <?= ($filterStatus === 'paid' ? 'selected' : '') ?>>Paid (Confirmed)</option>
                        <option value="invoice_requested" <?= ($filterStatus === 'invoice_requested' ? 'selected' : '') ?>>Invoice Requested</option>
                        <option value="pending" <?= ($filterStatus === 'pending' ? 'selected' : '') ?>>Pending</option>
                        <option value="failed" <?= ($filterStatus === 'failed' ? 'selected' : '') ?>>Failed / Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="filter_type" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="all" <?= ($filterType === 'all' ? 'selected' : '') ?>>All Types</option>
                        <option value="corporate_table" <?= ($filterType === 'corporate_table' ? 'selected' : '') ?>>Corporate Tables</option>
                        <option value="individual" <?= ($filterType === 'individual' ? 'selected' : '') ?>>Individual Seats</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-dark w-100">Filter</button>
                </div>
                <div class="col-md-3 text-end">
                    <span class="text-muted small">Showing <?= count($allBookings) ?> record(s)</span>
                </div>
            </form>

            <!-- Bookings Table -->
            <div class="table-responsive">
                <table class="table table-hover table-bookings align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Ref Code</th>
                            <th>Type / Package</th>
                            <th>Organization / Contact</th>
                            <th>Units / Seats</th>
                            <th>Amount (KES)</th>
                            <th>Payment Status</th>
                            <th>Receipt / Ref</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($allBookings)): ?>
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">No event bookings found matching criteria.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($allBookings as $b): 
                                $isTable = ($b['booking_type'] === 'corporate_table');
                            ?>
                            <tr>
                                <td>
                                    <strong class="text-dark font-monospace"><?= htmlspecialchars($b['booking_reference']) ?></strong>
                                </td>
                                <td>
                                    <?php if ($isTable): ?>
                                        <span class="badge bg-warning text-dark"><i class="fas fa-building me-1"></i> Corporate Table</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><i class="fas fa-user me-1"></i> Individual</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($b['organization_name'])): ?>
                                        <div class="fw-bold text-primary"><?= htmlspecialchars($b['organization_name']) ?></div>
                                    <?php endif; ?>
                                    <div><?= htmlspecialchars($b['contact_name']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($b['contact_phone']) ?> | <?= htmlspecialchars($b['contact_email']) ?></small>
                                </td>
                                <td>
                                    <strong><?= intval($b['quantity']) ?> <?= ($isTable ? 'Table(s)' : 'Seat(s)') ?></strong><br>
                                    <small class="text-muted">(<?= intval($b['total_seats']) ?> Total Seats)</small>
                                </td>
                                <td>
                                    <strong>KES <?= number_format($b['total_amount_kes'], 2) ?></strong>
                                </td>
                                <td>
                                    <?php if ($b['payment_status'] === 'paid'): ?>
                                        <span class="badge badge-status-paid px-2 py-1"><i class="fas fa-check-circle me-1"></i> Paid</span>
                                    <?php elseif ($b['payment_status'] === 'invoice_requested'): ?>
                                        <span class="badge badge-status-invoice px-2 py-1"><i class="fas fa-clock me-1"></i> Invoice Requested</span>
                                    <?php elseif ($b['payment_status'] === 'pending'): ?>
                                        <span class="badge badge-status-pending px-2 py-1">Pending</span>
                                    <?php else: ?>
                                        <span class="badge badge-status-failed px-2 py-1"><?= ucfirst($b['payment_status']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="font-monospace text-muted"><?= htmlspecialchars($b['paystack_reference'] ?: ($b['mpesa_receipt_number'] ?: '—')) ?></small>
                                    <?php if (!empty($b['paystack_channel'])): ?>
                                        <br><span class="badge bg-light text-secondary border" style="font-size:10px;"><?= strtoupper(htmlspecialchars($b['paystack_channel'])) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted"><?= date('M j, Y H:i', strtotime($b['created_at'])) ?></small>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                            
                                            <!-- View Pass or Invoice -->
                                            <?php if ($b['payment_status'] === 'paid'): ?>
                                                <li>
                                                    <a class="dropdown-item" href="../ticket-pass.php?ref=<?= urlencode($b['booking_reference']) ?>&token=<?= urlencode($b['qr_token']) ?>" target="_blank">
                                                        <i class="fas fa-qrcode text-success me-2"></i> View Branded Pass
                                                    </a>
                                                </li>
                                            <?php else: ?>
                                                <li>
                                                    <a class="dropdown-item" href="../event-invoice.php?ref=<?= urlencode($b['booking_reference']) ?>&token=<?= urlencode($b['qr_token']) ?>" target="_blank">
                                                        <i class="fas fa-file-invoice text-warning me-2"></i> View Proforma Invoice
                                                    </a>
                                                </li>
                                            <?php endif; ?>

                                            <!-- Mark Invoice as Paid -->
                                            <?php if ($b['payment_status'] !== 'paid'): ?>
                                                <li>
                                                    <button class="dropdown-item text-success" type="button" onclick="openMarkPaidModal(<?= $b['id'] ?>, '<?= htmlspecialchars($b['booking_reference']) ?>', <?= $b['total_amount_kes'] ?>)">
                                                        <i class="fas fa-check-double me-2"></i> Mark Invoice as Paid
                                                    </button>
                                                </li>
                                            <?php endif; ?>

                                            <!-- View / Edit Guests -->
                                            <li>
                                                <button class="dropdown-item" type="button" onclick="openGuestsModal(<?= $b['id'] ?>, '<?= htmlspecialchars($b['booking_reference']) ?>', <?= $b['total_seats'] ?>)">
                                                    <i class="fas fa-users text-primary me-2"></i> View / Edit Guests
                                                </button>
                                            </li>

                                            <!-- Resend Email -->
                                            <li>
                                                <form method="POST" onsubmit="return confirm('Resend email to <?= htmlspecialchars($b['contact_email']) ?>?');">
                                                    <input type="hidden" name="admin_action" value="resend_email">
                                                    <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-envelope text-info me-2"></i> Resend Email
                                                    </button>
                                                </form>
                                            </li>

                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-muted" href="?tab=checkin&lookup_ref=<?= urlencode($b['booking_reference']) ?>">
                                                    <i class="fas fa-user-check me-2"></i> Check-In Terminal
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
        <?php endif; ?>

        <!-- ==================== SUB-TAB 2: EVENT & TIER SETTINGS (CMS) ==================== -->
        <?php if ($activeTab === 'cms'): ?>
        <div class="row g-4">
            
            <!-- Left: Flagship Event Metadata -->
            <div class="col-lg-7">
                <div class="content-card shadow-sm p-4 bg-white rounded-3 h-100">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>Flagship Gala Details (tp_events)
                    </h5>

                    <form method="POST">
                        <input type="hidden" name="tab" value="cms">
                        <input type="hidden" name="admin_action" value="save_event_settings">
                        <input type="hidden" name="event_id" value="<?= $event['id'] ?>">

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small">Event Title</label>
                                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($event['title']) ?>" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold small">Subtitle / Tagline</label>
                                <input type="text" name="subtitle" class="form-control" value="<?= htmlspecialchars($event['subtitle']) ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Event Date</label>
                                <input type="date" name="event_date" class="form-control" value="<?= htmlspecialchars($event['event_date']) ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Event Time</label>
                                <input type="text" name="event_time" class="form-control" value="<?= htmlspecialchars($event['event_time']) ?>" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold small">Venue Location</label>
                                <input type="text" name="venue" class="form-control" value="<?= htmlspecialchars($event['venue']) ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Principal Guest Name</label>
                                <input type="text" name="principal_guest_name" class="form-control" value="<?= htmlspecialchars($event['principal_guest_name']) ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Principal Guest Title</label>
                                <input type="text" name="principal_guest_title" class="form-control" value="<?= htmlspecialchars($event['principal_guest_title']) ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Patron Name</label>
                                <input type="text" name="patron_name" class="form-control" value="<?= htmlspecialchars($event['patron_name']) ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Patron Title</label>
                                <input type="text" name="patron_title" class="form-control" value="<?= htmlspecialchars($event['patron_title']) ?>">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold small">Special Highlight (Magazine Launch)</label>
                                <input type="text" name="special_highlight" class="form-control" value="<?= htmlspecialchars($event['special_highlight']) ?>">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold small">Hero Ribbon Badge Text</label>
                                <input type="text" name="hero_badge_text" class="form-control" value="<?= htmlspecialchars($event['hero_badge_text']) ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Paybill / Till Number</label>
                                <input type="text" name="paybill_number" class="form-control" value="<?= htmlspecialchars($event['paybill_number']) ?>">
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-semibold small">Bank & Remittance Details for Invoices</label>
                                <textarea name="bank_details_text" class="form-control" rows="3"><?= htmlspecialchars($event['bank_details_text']) ?></textarea>
                            </div>

                            <div class="col-md-12">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="eventActiveCheck" <?= ($event['is_active'] ? 'checked' : '') ?>>
                                    <label class="form-check-label fw-semibold small" for="eventActiveCheck">Event Active & Open for Bookings</label>
                                </div>
                            </div>

                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-1"></i> Save Event Metadata
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right: Ticket Tiers & Perks Dynamic Editor -->
            <div class="col-lg-5">
                <div class="content-card shadow-sm p-4 bg-white rounded-3 h-100">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">
                        <i class="fas fa-tags text-warning me-2"></i>Ticket Tiers & Perks Editor (tp_ticket_tiers)
                    </h5>

                    <?php if (empty($tiers)): ?>
                        <div class="alert alert-warning py-3 text-center">
                            <i class="fas fa-exclamation-triangle me-2"></i> No ticket tiers found in database.
                            <form method="POST" class="mt-2">
                                <input type="hidden" name="tab" value="cms">
                                <input type="hidden" name="admin_action" value="restore_default_tiers">
                                <button type="submit" class="btn btn-sm btn-warning fw-bold">
                                    <i class="fas fa-sync-alt me-1"></i> Restore Default Flagship Tiers
                                </button>
                            </form>
                        </div>
                    <?php else: ?>
                        <?php foreach ($tiers as $tr): 
                            $perksList = !empty($tr['perks_json']) ? json_decode($tr['perks_json'], true) : [];
                            $perksText = implode("\n", (array)$perksList);
                        ?>
                        <div class="card mb-4 border <?= ($tr['is_featured'] ? 'border-warning shadow-sm' : '') ?> <?= (!$tr['is_active'] ? 'border-secondary opacity-75' : '') ?>">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                <div>
                                    <strong class="text-dark">
                                        <?= htmlspecialchars($tr['tier_name']) ?>
                                    </strong>
                                    <?php if ($tr['is_featured']): ?>
                                        <span class="badge bg-warning text-dark ms-1">Featured</span>
                                    <?php endif; ?>
                                    <?php if ($tr['is_active']): ?>
                                        <span class="badge bg-success ms-1"><i class="fas fa-check-circle me-1"></i> Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary ms-1"><i class="fas fa-eye-slash me-1"></i> Inactive</span>
                                    <?php endif; ?>
                                </div>
                                <span class="badge bg-dark"><?= strtoupper($tr['tier_code']) ?></span>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <input type="hidden" name="tab" value="cms">
                                    <input type="hidden" name="admin_action" value="save_tier_settings">
                                    <input type="hidden" name="tier_id" value="<?= $tr['id'] ?>">

                                    <div class="row g-2 mb-2">
                                        <div class="col-8">
                                            <label class="form-label small fw-semibold">Tier Name</label>
                                            <input type="text" name="tier_name" class="form-control form-control-sm" value="<?= htmlspecialchars($tr['tier_name']) ?>" required>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label small fw-semibold">Price (KES)</label>
                                            <input type="number" step="0.01" name="price_kes" class="form-control form-control-sm" value="<?= htmlspecialchars($tr['price_kes']) ?>" required>
                                        </div>
                                    </div>

                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <label class="form-label small fw-semibold">Seats Per Unit</label>
                                            <input type="number" name="seats_per_unit" class="form-control form-control-sm" value="<?= intval($tr['seats_per_unit']) ?>" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-semibold">Max Capacity</label>
                                            <input type="number" name="max_capacity" class="form-control form-control-sm" value="<?= intval($tr['max_capacity']) ?>" required>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small fw-semibold">Badge Label</label>
                                        <input type="text" name="badge_label" class="form-control form-control-sm" value="<?= htmlspecialchars($tr['badge_label']) ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">
                                            Included Perks & Amenities (One perk per line)
                                        </label>
                                        <textarea name="perks_raw" class="form-control form-control-sm font-monospace" rows="4"><?= htmlspecialchars($perksText) ?></textarea>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-2 border-top">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" name="is_active" id="active_<?= $tr['id'] ?>" value="1" <?= ($tr['is_active'] ? 'checked' : '') ?>>
                                                <label class="form-check-label small fw-semibold text-success" for="active_<?= $tr['id'] ?>">Active on Site</label>
                                            </div>
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" name="is_featured" id="feat_<?= $tr['id'] ?>" value="1" <?= ($tr['is_featured'] ? 'checked' : '') ?>>
                                                <label class="form-check-label small" for="feat_<?= $tr['id'] ?>">Gold Ribbon</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary px-3">
                                            <i class="fas fa-save me-1"></i> Update Tier & Perks
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
            </div>

        </div>
        <?php endif; ?>

        <!-- ==================== SUB-TAB 3: QR CHECK-IN & LOOKUP ==================== -->
        <?php if ($activeTab === 'checkin'): ?>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="content-card shadow-sm p-4 bg-white rounded-3 mb-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-qrcode text-primary me-2"></i>Delegate QR Scan & Ticket Verification Terminal</h5>
                    <p class="text-muted small">
                        Scan delegate pass QR code or type their Booking Reference (e.g. <code>TP-LD26-XXXX</code>) to verify authentication and check them in at the Tamarind Tree Hotel entrance.
                    </p>

                    <form method="GET" class="d-flex gap-2 mb-4">
                        <input type="hidden" name="tab" value="checkin">
                        <input type="text" name="lookup_ref" class="form-control form-control-lg font-monospace" placeholder="Enter Booking Reference or QR Token..." value="<?= htmlspecialchars($lookupRef) ?>" autofocus required>
                        <button type="submit" class="btn btn-dark px-4">
                            <i class="fas fa-search me-1"></i> Verify
                        </button>
                    </form>

                    <?php if (!empty($lookupRef)): ?>
                        <?php if ($lookupBooking): 
                            $isPaid = ($lookupBooking['payment_status'] === 'paid');
                            $isTable = ($lookupBooking['booking_type'] === 'corporate_table');
                            $magCopies = $isTable ? ($lookupBooking['quantity'] * 5) : $lookupBooking['quantity'];
                        ?>
                        <div class="card border-2 <?= ($isPaid ? 'border-success' : 'border-danger') ?> shadow-sm">
                            <div class="card-header <?= ($isPaid ? 'bg-success text-white' : 'bg-danger text-white') ?> d-flex justify-content-between align-items-center py-2">
                                <span class="fw-bold"><i class="fas <?= ($isPaid ? 'fa-check-circle' : 'fa-exclamation-triangle') ?> me-1"></i> <?= ($isPaid ? 'VALID & VERIFIED ENTRY PASS' : 'ATTENTION: UNPAID PASS') ?></span>
                                <span class="font-monospace fw-bold"><?= htmlspecialchars($lookupBooking['booking_reference']) ?></span>
                            </div>
                            <div class="card-body">
                                
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <small class="text-muted text-uppercase fw-bold d-block">Tier / Package</small>
                                        <div class="fw-bold fs-5 text-dark"><?= htmlspecialchars($lookupBooking['tier_name']) ?></div>
                                        <?php if (!empty($lookupBooking['organization_name'])): ?>
                                            <div class="text-primary fw-semibold"><i class="fas fa-building me-1"></i> <?= htmlspecialchars($lookupBooking['organization_name']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6 text-md-end">
                                        <small class="text-muted text-uppercase fw-bold d-block">Reserved Seats</small>
                                        <div class="fs-4 fw-bold text-success"><?= intval($lookupBooking['total_seats']) ?> Total Seats</div>
                                        <div class="text-muted small"><i class="fas fa-book-open me-1"></i> <?= $magCopies ?> Physical Magazines Included</div>
                                    </div>
                                </div>

                                <div class="row g-2 mb-3 bg-light p-3 rounded">
                                    <div class="col-md-6">
                                        <strong>Primary Contact:</strong> <?= htmlspecialchars($lookupBooking['contact_name']) ?><br>
                                        <strong>Email:</strong> <?= htmlspecialchars($lookupBooking['contact_email']) ?><br>
                                        <strong>Phone:</strong> <?= htmlspecialchars($lookupBooking['contact_phone']) ?>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Amount:</strong> KES <?= number_format($lookupBooking['total_amount_kes'], 2) ?><br>
                                        <strong>Payment Status:</strong> <span class="badge <?= ($isPaid ? 'bg-success' : 'bg-warning text-dark') ?>"><?= strtoupper($lookupBooking['payment_status']) ?></span><br>
                                        <strong>Receipt / Ref:</strong> <code><?= htmlspecialchars($lookupBooking['paystack_reference'] ?: ($lookupBooking['mpesa_receipt_number'] ?: 'N/A')) ?></code>
                                    </div>
                                </div>

                                <?php if (!empty($lookupGuests)): ?>
                                    <div class="mb-3">
                                        <h6 class="fw-bold text-dark mb-2"><i class="fas fa-users me-1 text-primary"></i> Registered Delegation:</h6>
                                        <div class="list-group">
                                            <?php foreach ($lookupGuests as $g): ?>
                                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2">
                                                    <div>
                                                        Seat <?= $g['seat_number'] ?>: <strong><?= htmlspecialchars($g['guest_name'] ?: 'Guest') ?></strong>
                                                        <?= !empty($g['dietary_requirements']) ? ' <span class="badge bg-light text-dark border">Diet: ' . htmlspecialchars($g['dietary_requirements']) . '</span>' : '' ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Check-in Action Bar -->
                                <div class="border-top pt-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted small">Checked In:</span>
                                        <strong class="fs-6"><?= intval($lookupBooking['checked_in_count']) ?> of <?= intval($lookupBooking['total_seats']) ?> seats</strong>
                                    </div>
                                    <form method="POST" class="d-flex align-items-center gap-2">
                                        <input type="hidden" name="admin_action" value="perform_checkin">
                                        <input type="hidden" name="booking_id" value="<?= $lookupBooking['id'] ?>">
                                        <label class="small fw-semibold text-muted">Update Count:</label>
                                        <input type="number" name="checkin_count" class="form-control form-control-sm text-center" style="width: 70px;" value="<?= min(intval($lookupBooking['total_seats']), intval($lookupBooking['checked_in_count']) + 1) ?>" min="0" max="<?= intval($lookupBooking['total_seats']) ?>">
                                        <button type="submit" class="btn btn-sm btn-success px-3">
                                            <i class="fas fa-check me-1"></i> Check In
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                        <?php else: ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle me-2"></i> No booking pass found matching <strong>"<?= htmlspecialchars($lookupRef) ?>"</strong>.
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <!-- MODAL: MARK INVOICE AS PAID -->
    <div class="modal fade" id="markPaidModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <input type="hidden" name="admin_action" value="mark_invoice_paid">
                    <input type="hidden" name="booking_id" id="paidBookingId">
                    <div class="modal-header bg-success text-white py-2">
                        <h5 class="modal-title fs-6"><i class="fas fa-check-double me-1"></i> Confirm Payment for <span id="paidBookingRef"></span></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small text-muted mb-3">
                            Confirming this corporate invoice payment will immediately flip status to <strong>PAID</strong>, activate their QR Entry Pass, and send the official luxury confirmation email to the client.
                        </p>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Amount to Confirm (KES)</label>
                            <input type="text" id="paidAmountText" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Paystack / Bank Wire / M-Pesa Reference <span class="text-danger">*</span></label>
                            <input type="text" name="mpesa_receipt_number" class="form-control" placeholder="e.g. QK89H76TR, SCBL-WIRE-12345 or Paystack Ref" required>
                        </div>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-success px-3"><i class="fas fa-check me-1"></i> Confirm & Dispatch Pass</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: VIEW / EDIT GUESTS -->
    <div class="modal fade" id="guestsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST">
                    <input type="hidden" name="admin_action" value="update_guests">
                    <input type="hidden" name="booking_id" id="guestModalBookingId">
                    <div class="modal-header py-2">
                        <h5 class="modal-title fs-6"><i class="fas fa-users me-1 text-primary"></i> Delegation Guest Roster: <span id="guestModalRef"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="guestFieldsContainer">
                        <!-- Populated dynamically via JS -->
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-primary px-3"><i class="fas fa-save me-1"></i> Save Guest Names</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openMarkPaidModal(id, ref, amount) {
            document.getElementById('paidBookingId').value = id;
            document.getElementById('paidBookingRef').innerText = ref;
            document.getElementById('paidAmountText').value = 'KES ' + Number(amount).toLocaleString();
            new bootstrap.Modal(document.getElementById('markPaidModal')).show();
        }

        async function openGuestsModal(bookingId, ref, totalSeats) {
            document.getElementById('guestModalBookingId').value = bookingId;
            document.getElementById('guestModalRef').innerText = ref;

            const container = document.getElementById('guestFieldsContainer');
            container.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin me-2"></i> Loading guest details...</div>';

            const modal = new bootstrap.Modal(document.getElementById('guestsModal'));
            modal.show();

            try {
                // Fetch existing guests via quick inline endpoint or pass them
                const res = await fetch(`api/event_guest_details.php?booking_id=${bookingId}`);
                const data = await res.json();
                
                let html = '';
                const maxSeats = Math.min(totalSeats, 10);
                for (let i = 1; i <= maxSeats; i++) {
                    const existing = (data.guests && data.guests.find(g => g.seat_number == i)) || {};
                    html += `
                        <div class="row g-2 mb-2 p-2 border-bottom align-items-center">
                            <div class="col-1 text-center fw-bold text-muted">#${i}</div>
                            <div class="col-6">
                                <input type="text" name="guests[${i}][name]" class="form-control form-control-sm" placeholder="Full Name" value="${existing.guest_name || ''}">
                            </div>
                            <div class="col-5">
                                <input type="text" name="guests[${i}][dietary]" class="form-control form-control-sm" placeholder="Dietary / Preference" value="${existing.dietary_requirements || ''}">
                            </div>
                        </div>
                    `;
                }
                container.innerHTML = html;
            } catch (e) {
                // Fallback default 5 rows
                let html = '';
                for (let i = 1; i <= 5; i++) {
                    html += `
                        <div class="row g-2 mb-2 p-2 border-bottom align-items-center">
                            <div class="col-1 text-center fw-bold text-muted">#${i}</div>
                            <div class="col-6">
                                <input type="text" name="guests[${i}][name]" class="form-control form-control-sm" placeholder="Full Name">
                            </div>
                            <div class="col-5">
                                <input type="text" name="guests[${i}][dietary]" class="form-control form-control-sm" placeholder="Dietary / Preference">
                            </div>
                        </div>
                    `;
                }
                container.innerHTML = html;
            }
        }
    </script>
</body>
</html>
