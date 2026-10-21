<?php
/**
 * Event DB Helper & Auto-initializer
 * Turning Point Magazine - The Leadership Dinner 2026
 */

require_once __DIR__ . '/../connection2.php';
require_once __DIR__ . '/paystack_config.php';

function ensure_event_tables_exist($pdo = null) {
    if (!$pdo) {
        $pdo = connect();
    }
    
    $tablesExist = false;
    try {
        $check = $pdo->query("SHOW TABLES LIKE 'tp_events'");
        if ($check && $check->rowCount() > 0) {
            $tablesExist = true;
        }
    } catch (Exception $e) {
        $tablesExist = false;
    }

    if (!$tablesExist) {
        $sqlFile = __DIR__ . '/../sql/leadership_dinner_2026_schema.sql';
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            $queries = array_filter(array_map('trim', explode(';', $sql)));
            foreach ($queries as $query) {
                if (!empty($query)) {
                    try {
                        $pdo->exec($query);
                    } catch (Exception $ex) {
                        error_log("Event Schema Exec Error: " . $ex->getMessage());
                    }
                }
            }
        }
    }

    // Ensure Paystack columns exist in tp_event_bookings
    try {
        $colCheck = $pdo->query("SHOW COLUMNS FROM `tp_event_bookings` LIKE 'paystack_reference'");
        if ($colCheck && $colCheck->rowCount() === 0) {
            $pdo->exec("ALTER TABLE `tp_event_bookings` 
                ADD COLUMN `paystack_reference` VARCHAR(100) DEFAULT NULL AFTER `mpesa_receipt_number`,
                ADD COLUMN `paystack_transaction_id` VARCHAR(100) DEFAULT NULL AFTER `paystack_reference`,
                ADD COLUMN `paystack_channel` VARCHAR(50) DEFAULT NULL AFTER `paystack_transaction_id`,
                ADD INDEX `idx_paystack_ref` (`paystack_reference`)");
        }
    } catch (Exception $e) {
        // Table may not exist yet or already altered
    }

    // Self-healing: Reactivate any tiers that were set to is_active = 0 due to previous form bug
    try {
        $pdo->exec("UPDATE `tp_ticket_tiers` SET `is_active` = 1 WHERE `is_active` = 0");
    } catch (Exception $e) {
        // Table may not exist yet
    }

    return true;
}

function get_flagship_event($pdo = null, $slug = 'leadership-dinner-2026') {
    if (!$pdo) {
        $pdo = connect();
    }
    ensure_event_tables_exist($pdo);

    $stmt = $pdo->prepare("SELECT * FROM tp_events WHERE slug = ? AND is_active = 1 LIMIT 1");
    $stmt->execute([$slug]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        $stmt = $pdo->prepare("SELECT * FROM tp_events ORDER BY id ASC LIMIT 1");
        $stmt->execute();
        $event = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return $event;
}

function get_event_tiers($pdo = null, $eventId = 1, $onlyActive = true) {
    if (!$pdo) {
        $pdo = connect();
    }
    ensure_event_tables_exist($pdo);

    $sql = "SELECT * FROM tp_ticket_tiers WHERE event_id = ?";
    if ($onlyActive) {
        $sql .= " AND is_active = 1";
    }
    $sql .= " ORDER BY sort_order ASC, price_kes ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$eventId]);
    $tiers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Resiliency: If onlyActive was requested but no tiers returned, auto-heal
    if ($onlyActive && empty($tiers)) {
        try {
            $pdo->prepare("UPDATE tp_ticket_tiers SET is_active = 1 WHERE event_id = ?")->execute([$eventId]);
            $stmt->execute([$eventId]);
            $tiers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {}
    }

    return $tiers;
}

function generate_booking_reference($pdo = null) {
    if (!$pdo) {
        $pdo = connect();
    }
    $prefix = 'TP-LD26-';
    for ($i = 0; $i < 10; $i++) {
        $candidate = $prefix . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
        $stmt = $pdo->prepare("SELECT id FROM tp_event_bookings WHERE booking_reference = ? LIMIT 1");
        $stmt->execute([$candidate]);
        if (!$stmt->fetch()) {
            return $candidate;
        }
    }
    return $prefix . rand(1000, 9999);
}

function generate_qr_token() {
    return bin2hex(random_bytes(16));
}

function normalize_kenya_phone($phone) {
    $clean = preg_replace('/[^0-9]/', '', $phone);
    if (strpos($clean, '07') === 0 && strlen($clean) === 10) {
        return '254' . substr($clean, 1);
    }
    if (strpos($clean, '01') === 0 && strlen($clean) === 10) {
        return '254' . substr($clean, 1);
    }
    if (strpos($clean, '7') === 0 && strlen($clean) === 9) {
        return '254' . $clean;
    }
    if (strpos($clean, '1') === 0 && strlen($clean) === 9) {
        return '254' . $clean;
    }
    if (strpos($clean, '254') === 0 && (strlen($clean) === 12)) {
        return $clean;
    }
    return $clean;
}
