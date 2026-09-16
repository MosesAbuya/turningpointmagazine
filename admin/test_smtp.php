<?php
session_start();
include '../connection2.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Error: Unauthorized. Please log in.";
    exit();
}

$test_email = $_POST['email'] ?? '';
if (!filter_var($test_email, FILTER_VALIDATE_EMAIL)) {
    echo "Error: Invalid email address.";
    exit();
}

// Load Composer autoloader
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
} else {
    echo "Error: Composer autoload.php not found at $autoloadPath";
    exit();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$pdo = connect();

try {
    $stmt = $pdo->query("SELECT * FROM smtp_settings WHERE id = 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$settings || empty($settings['host'])) {
        echo "Error: SMTP settings not configured in the database.";
        exit();
    }

    $mail = new PHPMailer(true);
    
    // Enable verbose debug output and capture it
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    
    // We need to capture the debug output instead of echoing directly
    $debugOutput = "";
    $mail->Debugoutput = function($str, $level) use (&$debugOutput) {
        $debugOutput .= htmlspecialchars($str) . "<br>";
    };

    // Server settings
    $mail->isSMTP();
    $mail->Host       = $settings['host'];
    $mail->SMTPAuth   = !empty($settings['username']);
    
    if ($mail->SMTPAuth) {
        $mail->Username = $settings['username'];
        $mail->Password = $settings['password'];
    }

    if ($settings['encryption'] === 'tls') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    } elseif ($settings['encryption'] === 'ssl') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    } else {
        $mail->SMTPSecure = false;
        $mail->SMTPAutoTLS = false;
    }
    
    $mail->Port = $settings['port'];

    // Recipients
    $mail->setFrom($settings['from_email'], $settings['from_name']);
    $mail->addAddress($test_email);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Turning Point Magazine - SMTP Test';
    $mail->Body    = "<h2>SMTP Test Successful!</h2><p>Your SMTP settings are working perfectly. You are ready to send emails from your dashboard.</p>";
    $mail->AltBody = "SMTP Test Successful! Your settings are working perfectly.";

    $mail->send();
    echo "<div class='alert alert-success mb-2'><strong>SUCCESS!</strong> The test email was sent successfully to $test_email.</div>";
    echo "<strong>SMTP Debug Log:</strong><br>" . $debugOutput;

} catch (Exception $e) {
    echo "<div class='alert alert-danger mb-2'><strong>FAILED!</strong> Message could not be sent. Mailer Error: {$mail->ErrorInfo}</div>";
    echo "<strong>SMTP Debug Log:</strong><br>" . $debugOutput;
} catch (PDOException $e) {
    echo "<div class='alert alert-danger mb-2'><strong>Database Error:</strong> " . $e->getMessage() . "</div>";
}
?>
