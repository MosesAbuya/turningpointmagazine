<?php
function validateCheckoutSession($invoice_id) {
    session_start(); // ADD THIS - CRITICAL MISSING LINE
    
    // 1. Session exists check
    if (!isset($_SESSION['checkout_data'])) {
        die("Invalid session - please restart checkout");
    }
    
    // 2. Invoice ID match check
    if ($_SESSION['checkout_data']['invoice_id'] !== $invoice_id) {
        die("Security violation: Invoice ID mismatch");
    }
    
    // 3. Session expiration (15 minutes)
    $checkoutTime = $_SESSION['checkout_data']['checkout_time'] ?? $_SESSION['checkout_time'] ?? 0;
    if (time() - $checkoutTime > 900) {
        unset($_SESSION['checkout_data']);
        die("Session expired - please restart checkout");
    }
}
