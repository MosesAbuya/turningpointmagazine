<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ensure this path is correct relative to where mailer.php is included
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

/**
 * Send an email using global SMTP settings from the database.
 * 
 * @param PDO $pdo Active PDO connection
 * @param string $to Recipient email
 * @param string $toName Recipient name
 * @param string $subject Email subject
 * @param string $body Email HTML body
 * @return bool True if successful, false otherwise
 */
function sendGlobalMail($pdo, $to, $toName, $subject, $body) {
    try {
        // Fetch SMTP settings
        $stmt = $pdo->query("SELECT * FROM smtp_settings WHERE id = 1");
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$settings) {
            error_log("SMTP Settings not configured in the database.");
            return false;
        }

        $mail = new PHPMailer(true);

        // Server settings
        // $mail->SMTPDebug = 0; // Disable verbose debug output
        $mail->isSMTP();
        $mail->Host       = $settings['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $settings['username'];
        $mail->Password   = $settings['password'];
        
        if (strtolower($settings['encryption']) === 'tls') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } elseif (strtolower($settings['encryption']) === 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } else {
            $mail->SMTPSecure = false; // no encryption
        }
        
        $mail->Port       = $settings['port'];

        // Recipients
        $mail->setFrom($settings['from_email'], $settings['from_name']);
        $mail->addAddress($to, $toName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags(str_replace('<br>', "\n", $body));

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mail Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>
