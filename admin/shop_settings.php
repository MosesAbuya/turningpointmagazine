<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'consent.php';
include '../connection2.php';

$pdo = connect();
$error = '';
$success = '';

// Handle Gateway Trigger / Switch & Settings Form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Quick Trigger Switch Button
    if (isset($_POST['trigger_switch_gateway'])) {
        $target_gateway = ($_POST['trigger_switch_gateway'] === 'daraja') ? 'daraja' : 'paystack';
        
        $stmt = $pdo->prepare("UPDATE shop_settings SET payment_gateway = ?");
        $stmt->execute([$target_gateway]);
        
        $gateway_label = ($target_gateway === 'paystack') ? 'Paystack' : 'Daraja M-Pesa';
        $success = "Active payment gateway successfully switched to <strong>{$gateway_label}</strong>.";
    } 
    // 2. Full Settings Form Save
    elseif (isset($_POST['save_settings'])) {
        $delivery_fee = floatval($_POST['delivery_fee'] ?? 150);
        $payment_gateway = (isset($_POST['payment_gateway']) && $_POST['payment_gateway'] === 'daraja') ? 'daraja' : 'paystack';

        // Check if row exists
        $stmt = $pdo->query("SELECT id FROM shop_settings LIMIT 1");
        if ($stmt->rowCount() > 0) {
            $stmt = $pdo->prepare("UPDATE shop_settings SET delivery_fee = ?, payment_gateway = ?");
            $stmt->execute([$delivery_fee, $payment_gateway]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO shop_settings (delivery_fee, payment_gateway) VALUES (?, ?)");
            $stmt->execute([$delivery_fee, $payment_gateway]);
        }
        $success = "Shop settings and payment gateway updated successfully.";
    }
}

// Fetch current settings
$delivery_fee = 150.00;
$payment_gateway = 'paystack';

$stmt = $pdo->query("SELECT delivery_fee, payment_gateway FROM shop_settings LIMIT 1");
if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if (!empty($row['delivery_fee'])) $delivery_fee = floatval($row['delivery_fee']);
    if (!empty($row['payment_gateway'])) $payment_gateway = strtolower($row['payment_gateway']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Settings & Payment Gateway - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .gateway-card {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.25s ease;
            position: relative;
            background: #fff;
        }
        .gateway-card:hover {
            border-color: #cbd5e1;
            transform: translateY(-2px);
        }
        .gateway-card.active {
            border-color: #0ba4db;
            background: #f0f9ff;
            box-shadow: 0 4px 15px rgba(11, 164, 219, 0.12);
        }
        .gateway-card.active-daraja {
            border-color: #10b981;
            background: #f0fdf4;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.12);
        }
        .active-badge-pill {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
        }
        .trigger-box {
            background: linear-gradient(135deg, #0c1a30 0%, #1e293b 100%);
            color: #fff;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body id="body">

    <?php include 'nav.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div id="page-content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold m-0">Shop Settings & Payment Gateway</h2>
                <p class="text-muted mb-0">Manage customer checkout payment methods and store delivery fee</p>
            </div>
            <a href="shop_orders.php" class="btn btn-outline-secondary"><i class="fas fa-shopping-bag me-1"></i> View Orders</a>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?= $success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> <?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- ============================================== -->
        <!-- PROMINENT ONE-CLICK TRIGGER SWITCHER SECTION -->
        <!-- ============================================== -->
        <div class="trigger-box shadow-sm">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <span class="badge bg-light text-dark text-uppercase mb-2" style="letter-spacing: 1px;">Live Checkout Gateway</span>
                    <h3 class="fw-bold mb-2">
                        <?php if ($payment_gateway === 'paystack'): ?>
                            <span class="text-info"><i class="fas fa-shield-alt"></i> Paystack</span> is Currently Active
                        <?php else: ?>
                            <span class="text-success"><i class="fas fa-mobile-alt"></i> Daraja (M-Pesa)</span> is Currently Active
                        <?php endif; ?>
                    </h3>
                    <p class="text-light opacity-75 mb-md-0">
                        <?php if ($payment_gateway === 'paystack'): ?>
                            Customers in the shop currently checkout via Paystack with instant M-Pesa, Card, Apple Pay, & Bank Transfer. Daraja is deactivated and hidden.
                        <?php else: ?>
                            Customers in the shop currently checkout via Safaricom Daraja STK Push prompt. Paystack is currently deactivated.
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                    <form method="POST" id="quickTriggerForm">
                        <?php if ($payment_gateway === 'paystack'): ?>
                            <input type="hidden" name="trigger_switch_gateway" value="daraja">
                            <button type="button" class="btn btn-warning btn-lg px-4 fw-bold shadow" onclick="confirmGatewaySwitch('daraja', 'Daraja M-Pesa')">
                                <i class="fas fa-exchange-alt me-2"></i> Switch to Daraja M-Pesa
                            </button>
                        <?php else: ?>
                            <input type="hidden" name="trigger_switch_gateway" value="paystack">
                            <button type="button" class="btn btn-info btn-lg px-4 text-white fw-bold shadow" onclick="confirmGatewaySwitch('paystack', 'Paystack')">
                                <i class="fas fa-exchange-alt me-2"></i> Switch to Paystack
                            </button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- DETAILED SETTINGS FORM (GATEWAYS & DELIVERY)   -->
        <!-- ============================================== -->
        <form method="POST">
            <input type="hidden" name="save_settings" value="1">
            <div class="row">
                <!-- Gateway Details -->
                <div class="col-lg-8 mb-4">
                    <div class="content-card">
                        <div class="card-header fw-bold">Select Active Payment Gateway</div>
                        <div class="card-body">
                            <p class="text-muted small mb-4">Choose which payment system your shop customers will interact with during checkout.</p>

                            <div class="row g-3">
                                <!-- Option 1: Paystack -->
                                <div class="col-md-6">
                                    <label class="w-100 mb-0">
                                        <div class="gateway-card <?= $payment_gateway === 'paystack' ? 'active' : '' ?>" id="card-paystack">
                                            <?php if ($payment_gateway === 'paystack'): ?>
                                                <span class="active-badge-pill bg-primary text-white"><i class="fas fa-check"></i> LIVE NOW</span>
                                            <?php else: ?>
                                                <span class="active-badge-pill bg-light text-muted border">STANDBY</span>
                                            <?php endif; ?>

                                            <div class="form-check p-0 d-flex align-items-center mb-2">
                                                <input class="form-check-input me-2" type="radio" name="payment_gateway" id="gateway_paystack" value="paystack" <?= $payment_gateway === 'paystack' ? 'checked' : '' ?> onchange="updateCardHighlight()">
                                                <h5 class="fw-bold mb-0 text-dark">Paystack Gateway</h5>
                                            </div>
                                            <span class="badge bg-info text-white mb-2">Recommended</span>
                                            <p class="text-muted small mb-2">Same secure system used for event ticketing. Supports multi-channel payments:</p>
                                            <ul class="text-muted small ps-3 mb-0">
                                                <li><strong>M-PESA</strong> (Direct prompt)</li>
                                                <li><strong>Cards</strong> (Visa, Mastercard, Amex)</li>
                                                <li><strong>Apple Pay</strong></li>
                                                <li><strong>Bank Wire / Transfer</strong></li>
                                                <li>Real-time automated verification & receipts</li>
                                            </ul>
                                        </div>
                                    </label>
                                </div>

                                <!-- Option 2: Daraja -->
                                <div class="col-md-6">
                                    <label class="w-100 mb-0">
                                        <div class="gateway-card <?= $payment_gateway === 'daraja' ? 'active-daraja' : '' ?>" id="card-daraja">
                                            <?php if ($payment_gateway === 'daraja'): ?>
                                                <span class="active-badge-pill bg-success text-white"><i class="fas fa-check"></i> LIVE NOW</span>
                                            <?php else: ?>
                                                <span class="active-badge-pill bg-light text-muted border">DEACTIVATED</span>
                                            <?php endif; ?>

                                            <div class="form-check p-0 d-flex align-items-center mb-2">
                                                <input class="form-check-input me-2" type="radio" name="payment_gateway" id="gateway_daraja" value="daraja" <?= $payment_gateway === 'daraja' ? 'checked' : '' ?> onchange="updateCardHighlight()">
                                                <h5 class="fw-bold mb-0 text-dark">Daraja M-Pesa</h5>
                                            </div>
                                            <span class="badge bg-secondary mb-2">Safaricom Direct</span>
                                            <p class="text-muted small mb-2">Direct Safaricom Daraja STK Push integration. Preserved and available as backup:</p>
                                            <ul class="text-muted small ps-3 mb-0">
                                                <li><strong>M-PESA STK Push</strong> only</li>
                                                <li>Sends PIN prompt to Kenyan phone</li>
                                                <li>Requires Till Number & Passkey</li>
                                                <li>Does not accept credit cards or Apple Pay</li>
                                            </ul>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Fee & Save -->
                <div class="col-lg-4 mb-4">
                    <div class="content-card">
                        <div class="card-header fw-bold">General Delivery Fee</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Delivery Fee (Ksh)</label>
                                <input type="number" step="0.01" name="delivery_fee" class="form-control" value="<?= htmlspecialchars($delivery_fee) ?>" required>
                                <small class="text-muted">This flat rate will be automatically added to the customer's total at checkout if they choose Delivery.</small>
                            </div>
                            <button type="submit" class="btn btn-danger w-100 py-2 fw-bold">
                                <i class="fas fa-save me-1"></i> Save All Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function updateCardHighlight() {
        const isPaystack = document.getElementById('gateway_paystack').checked;
        const cardPaystack = document.getElementById('card-paystack');
        const cardDaraja = document.getElementById('card-daraja');
        
        if (isPaystack) {
            cardPaystack.classList.add('active');
            cardDaraja.classList.remove('active-daraja');
        } else {
            cardPaystack.classList.remove('active');
            cardDaraja.classList.add('active-daraja');
        }
    }

    function confirmGatewaySwitch(targetGateway, gatewayLabel) {
        Swal.fire({
            title: 'Switch Payment Gateway?',
            html: `Are you sure you want to trigger the payment gateway to <strong>${gatewayLabel}</strong>?<br><br><small class="text-muted">All shop customers checking out will immediately be routed to ${gatewayLabel}.</small>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: targetGateway === 'paystack' ? '#0ba4db' : '#10b981',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, Switch to ${gatewayLabel}`
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('quickTriggerForm').submit();
            }
        });
    }
    </script>
</body>
</html>