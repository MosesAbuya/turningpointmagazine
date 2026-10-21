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
 * @param array $embeddedImages Optional list of images to embed: [['path' => '...', 'cid' => '...', 'name' => '...']]
 * @param array $attachments Optional list of file paths to attach
 * @return bool True if successful, false otherwise
 */
function sendGlobalMail($pdo, $to, $toName, $subject, $body, $embeddedImages = [], $attachments = []) {
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
        $mail->Timeout    = 15;

        // SSL options to handle local/proxy certificate verification
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ]
        ];

        // Recipients
        $mail->setFrom($settings['from_email'], $settings['from_name']);
        $mail->addReplyTo($settings['from_email'], $settings['from_name']);
        $mail->addAddress($to, $toName);

        // Automatic embedding of Turning Point logo if referenced via CID
        $tpLogoPath = dirname(__DIR__) . '/assets/logos/tp.png';
        if (strpos($body, 'cid:tp_logo') !== false && file_exists($tpLogoPath)) {
            $mail->addEmbeddedImage($tpLogoPath, 'tp_logo', 'tp.png', 'base64', 'image/png');
        }

        // Custom embedded images
        if (!empty($embeddedImages) && is_array($embeddedImages)) {
            foreach ($embeddedImages as $img) {
                if (!empty($img['path']) && file_exists($img['path'])) {
                    $cid = $img['cid'] ?? basename($img['path']);
                    $name = $img['name'] ?? basename($img['path']);
                    $mime = $img['mime'] ?? 'image/png';
                    $mail->addEmbeddedImage($img['path'], $cid, $name, 'base64', $mime);
                }
            }
        }

        // Custom file attachments
        if (!empty($attachments) && is_array($attachments)) {
            foreach ($attachments as $att) {
                if (is_string($att) && file_exists($att)) {
                    $mail->addAttachment($att);
                } elseif (is_array($att) && !empty($att['path']) && file_exists($att['path'])) {
                    $name = $att['name'] ?? basename($att['path']);
                    $mail->addAttachment($att['path'], $name);
                }
            }
        }

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
