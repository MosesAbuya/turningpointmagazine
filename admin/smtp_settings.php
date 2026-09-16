<?php
session_start();
include '../connection2.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$pdo = connect();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = $_POST['host'] ?? '';
    $port = $_POST['port'] ?? 587;
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $encryption = $_POST['encryption'] ?? 'tls';
    $from_email = $_POST['from_email'] ?? '';
    $from_name = $_POST['from_name'] ?? '';

    try {
        $stmt = $pdo->prepare("UPDATE smtp_settings SET host = ?, port = ?, username = ?, password = ?, encryption = ?, from_email = ?, from_name = ? WHERE id = 1");
        if ($stmt->execute([$host, $port, $username, $password, $encryption, $from_email, $from_name])) {
            $message = "SMTP settings updated successfully.";
        } else {
            $error = "Failed to update settings.";
        }
    } catch (PDOException $e) {
        $error = "Database Error: " . $e->getMessage();
    }
}

// Fetch current settings
try {
    $stmt = $pdo->query("SELECT * FROM smtp_settings WHERE id = 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // If table doesn't exist on live server, automatically create it
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS smtp_settings (
            id INT PRIMARY KEY,
            host VARCHAR(255) NOT NULL,
            port INT NOT NULL,
            username VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            encryption VARCHAR(50) NOT NULL,
            from_email VARCHAR(255) NOT NULL,
            from_name VARCHAR(255) NOT NULL
        )
    ");
    $settings = false;
}

if (!$settings) {
    // Insert defaults if somehow deleted or newly created
    try {
        $pdo->query("INSERT INTO smtp_settings (id, host, port, username, password, encryption, from_email, from_name) VALUES (1, '', 587, '', '', 'tls', '', '')");
    } catch (PDOException $e) {}
    
    $settings = [
        'host' => '', 'port' => 587, 'username' => '', 'password' => '',
        'encryption' => 'tls', 'from_email' => '', 'from_name' => ''
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Global SMTP Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'nav.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div id="page-content-wrapper">
        <div class="container-fluid">
            <h2 class="fw-bold mb-4">Global SMTP Settings</h2>

            <?php if ($message): ?>
                <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="content-card p-4">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SMTP Host</label>
                            <input type="text" name="host" class="form-control" value="<?= htmlspecialchars($settings['host']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SMTP Port</label>
                            <input type="number" name="port" class="form-control" value="<?= htmlspecialchars($settings['port']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username (Email)</label>
                            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($settings['username']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" value="<?= htmlspecialchars($settings['password']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Encryption</label>
                            <select name="encryption" class="form-control">
                                <option value="tls" <?= $settings['encryption'] == 'tls' ? 'selected' : '' ?>>TLS (Recommended)</option>
                                <option value="ssl" <?= $settings['encryption'] == 'ssl' ? 'selected' : '' ?>>SSL</option>
                                <option value="none" <?= $settings['encryption'] == 'none' ? 'selected' : '' ?>>None</option>
                            </select>
                        </div>
                    </div>
                    <h4 class="mt-4 mb-3">Sender Details</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">From Email</label>
                            <input type="email" name="from_email" class="form-control" value="<?= htmlspecialchars($settings['from_email']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">From Name</label>
                            <input type="text" name="from_name" class="form-control" value="<?= htmlspecialchars($settings['from_name']) ?>" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-dark fw-bold px-4 rounded-pill">Save Settings</button>
                </form>
            </div>

            <!-- Test SMTP Form -->
            <div class="content-card p-4 mt-4">
                <h4 class="fw-bold mb-3">Test Email Server</h4>
                <p class="text-muted small">Send a test email to verify your SMTP settings. Save your settings first!</p>
                <form id="testEmailForm">
                    <div class="row align-items-end">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Send Test Email To:</label>
                            <input type="email" id="test_email" class="form-control" placeholder="your-email@example.com" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button type="submit" class="btn btn-outline-danger fw-bold px-4 rounded-pill">
                                <i class="fas fa-paper-plane me-2"></i> Send Test
                            </button>
                        </div>
                    </div>
                </form>
                
                <div id="testResult" class="mt-3 p-3 rounded" style="display: none; background: #f8f9fa; border: 1px solid #ddd; max-height: 300px; overflow-y: auto; font-family: monospace; font-size: 0.85rem;">
                </div>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#testEmailForm').on('submit', function(e) {
                e.preventDefault();
                var email = $('#test_email').val();
                var resultBox = $('#testResult');
                
                resultBox.show().html('<i class="fas fa-spinner fa-spin"></i> Sending test email... Please wait.');
                
                $.post('test_smtp.php', { email: email }, function(response) {
                    resultBox.html('<strong>Status Report:</strong><br>' + response);
                }).fail(function(xhr) {
                    resultBox.html('<span class="text-danger">Request failed. Check network tab.</span><br>' + xhr.responseText);
                });
            });
        });
    </script>
</body>
</html>
