-- Leadership Dinner 2026 Database Schema & Idempotent Seed Migration
-- Dedicated tables for Turning Point Magazine Events & Ticketing Module

CREATE TABLE IF NOT EXISTS `tp_events` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `title` VARCHAR(255) NOT NULL,
  `subtitle` VARCHAR(255) DEFAULT NULL,
  `event_date` DATE NOT NULL DEFAULT '2026-11-06',
  `event_time` VARCHAR(100) NOT NULL DEFAULT '4:00 PM – 9:00 PM EAT',
  `venue` VARCHAR(255) NOT NULL DEFAULT 'Tamarind Tree Hotel, Nairobi, Kenya',
  `principal_guest_name` VARCHAR(255) DEFAULT 'Hon. Harriet Chiggai',
  `principal_guest_title` VARCHAR(255) DEFAULT 'President’s Special Advisor on Women’s Rights in Kenya',
  `patron_name` VARCHAR(255) DEFAULT 'Prof. Julia Ojiambo',
  `patron_title` VARCHAR(255) DEFAULT 'Patron of Turning Point Magazine',
  `special_highlight` TEXT DEFAULT NULL,
  `hero_badge_text` VARCHAR(255) DEFAULT 'UPCOMING FLAGSHIP GALA • FRIDAY, 6 NOVEMBER 2026',
  `description` TEXT DEFAULT NULL,
  `banner_image` VARCHAR(255) DEFAULT 'assets/IWD/IW1.jpg',
  `paybill_number` VARCHAR(50) DEFAULT '575555',
  `account_reference_prefix` VARCHAR(50) DEFAULT 'TP-LD26',
  `bank_details_text` TEXT DEFAULT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `tp_ticket_tiers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `event_id` INT NOT NULL,
  `tier_code` VARCHAR(50) NOT NULL,
  `tier_name` VARCHAR(255) NOT NULL,
  `badge_label` VARCHAR(100) DEFAULT NULL,
  `price_kes` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `seats_per_unit` INT NOT NULL DEFAULT 1,
  `perks_json` LONGTEXT DEFAULT NULL,
  `max_capacity` INT DEFAULT 100,
  `is_featured` TINYINT(1) DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `sort_order` INT DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_tier_event` (`event_id`),
  CONSTRAINT `fk_tp_tier_event` FOREIGN KEY (`event_id`) REFERENCES `tp_events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `tp_event_bookings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_reference` VARCHAR(50) NOT NULL UNIQUE,
  `event_id` INT NOT NULL,
  `tier_id` INT NOT NULL,
  `booking_type` VARCHAR(50) NOT NULL DEFAULT 'individual',
  `quantity` INT NOT NULL DEFAULT 1,
  `total_seats` INT NOT NULL DEFAULT 1,
  `unit_price_kes` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `total_amount_kes` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `organization_name` VARCHAR(255) DEFAULT NULL,
  `contact_name` VARCHAR(255) NOT NULL,
  `contact_email` VARCHAR(255) NOT NULL,
  `contact_phone` VARCHAR(50) NOT NULL,
  `dietary_notes` TEXT DEFAULT NULL,
  `payment_method` VARCHAR(50) NOT NULL DEFAULT 'mpesa_stk',
  `payment_status` VARCHAR(50) NOT NULL DEFAULT 'pending',
  `mpesa_checkout_request_id` VARCHAR(100) DEFAULT NULL,
  `mpesa_receipt_number` VARCHAR(100) DEFAULT NULL,
  `paid_at` DATETIME DEFAULT NULL,
  `qr_token` VARCHAR(64) NOT NULL UNIQUE,
  `checked_in_count` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_booking_event` (`event_id`),
  KEY `idx_booking_tier` (`tier_id`),
  KEY `idx_booking_status` (`payment_status`),
  KEY `idx_checkout_request` (`mpesa_checkout_request_id`),
  CONSTRAINT `fk_tp_booking_event` FOREIGN KEY (`event_id`) REFERENCES `tp_events` (`id`),
  CONSTRAINT `fk_tp_booking_tier` FOREIGN KEY (`tier_id`) REFERENCES `tp_ticket_tiers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `tp_event_guests` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_id` INT NOT NULL,
  `seat_number` INT NOT NULL DEFAULT 1,
  `guest_name` VARCHAR(255) DEFAULT NULL,
  `guest_email` VARCHAR(255) DEFAULT NULL,
  `dietary_requirements` VARCHAR(255) DEFAULT NULL,
  `checked_in` TINYINT(1) DEFAULT 0,
  `checked_in_at` DATETIME DEFAULT NULL,
  KEY `idx_guest_booking` (`booking_id`),
  CONSTRAINT `fk_tp_guest_booking` FOREIGN KEY (`booking_id`) REFERENCES `tp_event_bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Default Flagship Event (idempotent)
INSERT INTO `tp_events` (
  `id`, `slug`, `title`, `subtitle`, `event_date`, `event_time`, `venue`,
  `principal_guest_name`, `principal_guest_title`, `patron_name`, `patron_title`,
  `special_highlight`, `hero_badge_text`, `description`, `banner_image`,
  `paybill_number`, `account_reference_prefix`, `bank_details_text`, `is_active`
) VALUES (
  1,
  'leadership-dinner-2026',
  'THE LEADERSHIP DINNER 2026',
  'An Evening of Transformative Leadership & High-Level Networking',
  '2026-11-06',
  '4:00 PM – 9:00 PM EAT',
  'Tamarind Tree Hotel, Nairobi, Kenya',
  'Hon. Harriet Chiggai',
  'President’s Special Advisor on Women’s Rights in Kenya',
  'Prof. Julia Ojiambo',
  'Patron of Turning Point Magazine',
  'Official Launch of the Third Edition of Turning Point Magazine',
  'UPCOMING FLAGSHIP GALA • FRIDAY, 6 NOVEMBER 2026',
  'Join Africa\'s foremost thought leaders, policymakers, captains of industry, and development champions for the prestigious Turning Point Leadership Dinner 2026 at the Tamarind Tree Hotel, Nairobi. An evening commemorating transformative leadership, catalytic dialogues, and the official unveiling of the Third Edition of Turning Point Magazine.',
  'assets/IWD/IW1.jpg',
  '575555',
  'TP-LD26',
  'Bank Transfer Details:\n• Bank Name: NCBA Bank Kenya Plc\n• Account Name: Malshe Media\n• Account No.: 1000145594\n• Branch Code: 125\n• Bank Code: 07000\n• SWIFT Code: CBAFKENX\n\nMobile Money (M-Pesa):\n• Buy Goods Till Number: 575555',
  1
) ON DUPLICATE KEY UPDATE 
  `title` = VALUES(`title`),
  `subtitle` = VALUES(`subtitle`),
  `event_date` = VALUES(`event_date`),
  `event_time` = VALUES(`event_time`),
  `venue` = VALUES(`venue`),
  `principal_guest_name` = VALUES(`principal_guest_name`),
  `principal_guest_title` = VALUES(`principal_guest_title`),
  `patron_name` = VALUES(`patron_name`),
  `patron_title` = VALUES(`patron_title`),
  `special_highlight` = VALUES(`special_highlight`),
  `hero_badge_text` = VALUES(`hero_badge_text`),
  `paybill_number` = VALUES(`paybill_number`),
  `account_reference_prefix` = VALUES(`account_reference_prefix`),
  `bank_details_text` = VALUES(`bank_details_text`);

-- Seed Default Ticket Tiers (idempotent)
INSERT INTO `tp_ticket_tiers` (
  `id`, `event_id`, `tier_code`, `tier_name`, `badge_label`, `price_kes`,
  `seats_per_unit`, `perks_json`, `max_capacity`, `is_featured`, `is_active`, `sort_order`
) VALUES 
(
  1,
  1,
  'individual',
  'Individual Delegate Pass',
  'Single Seat',
  10000.00,
  1,
  '["Reserved single seat at The Leadership Dinner 2026", "1 complimentary physical copy of the 3rd Edition of Turning Point Magazine", "Full 3-course gourmet dinner & refreshments at Tamarind Tree Hotel", "Access to high-level executive networking & launch ceremony"]',
  150,
  0,
  1,
  1
),
(
  2,
  1,
  'corporate_table',
  'Corporate VIP Table (Table of 5)',
  'Most Popular for Organizations',
  50000.00,
  5,
  '["Reserved VIP Table for 5 guests with custom Organization Name Card signage", "5 complimentary physical copies of the 3rd Edition of Turning Point Magazine (1 copy per head)", "Special corporate recognition during The Leadership Dinner programme", "Priority VIP table placement near the Principal Guest & keynote stage", "Full 3-course gourmet dinner & executive networking for your delegation", "Flexible guest assignment (register your table now, submit or update guest names later)"]',
  30,
  1,
  1,
  2
) ON DUPLICATE KEY UPDATE 
  `tier_name` = VALUES(`tier_name`),
  `badge_label` = VALUES(`badge_label`),
  `price_kes` = VALUES(`price_kes`),
  `seats_per_unit` = VALUES(`seats_per_unit`),
  `perks_json` = VALUES(`perks_json`),
  `is_featured` = VALUES(`is_featured`),
  `sort_order` = VALUES(`sort_order`);
