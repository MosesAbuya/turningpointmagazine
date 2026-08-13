<?php
// Include database connection
require 'connection2.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Connect to the database
$pdo = connect();

// Fetch all registered users
$sql = "SELECT firstname, lastname, email FROM booking";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Close the database connection
closeConnection($pdo);

// Step 1: Display the emails before sending
if (!isset($_POST['confirm'])) {
    echo "<h2>Emails to be sent:</h2>";
    echo "<form method='post'>";
    foreach ($users as $user) {
        echo "<p>{$user['firstname']} {$user['lastname']} - {$user['email']}</p>";
    }
    echo "<input type='hidden' name='confirm' value='1'>";
    echo "<button type='submit'>Send Emails</button>";
    echo "</form>";
    exit;
}

// Step 2: Send the emails if confirmed
foreach ($users as $user) {
    $firstname = $user['firstname'];
    $lastname = $user['lastname'];
    $email = $user['email'];

    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'da8.host-ww.net';
        $mail->SMTPAuth = true;
        $mail->Username = 'info@turningpointmagazine.africa';
        $mail->Password = 'Amo20.03';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender and recipient
        $mail->setFrom('info@turningpointmagazine.africa', 'Turning Point Magazine Africa');
        $mail->addAddress($email, "$firstname $lastname");

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Reminder: International Women\'s Day and 2025 Africa Exhibition & Trade Expo';
        $mail->Body = "
            <p>Dear <strong>$firstname $lastname</strong>,</p>
            <p>We are excited to remind you about the upcoming <strong>International Women's Day and 2025 Africa Exhibition & Trade Expo</strong> which kicks off tomorrow!</p>
            <p>📍 <strong>Venue:</strong> Kenyatta International Conference Center, Nairobi<br>
            📅 <strong>Date:</strong> 7th - 8th March 2025<br>
            ⏰ <strong>Time:</strong> 8:00 AM - 4:00 PM</p>
            <p>We look forward to welcoming you to this amazing event!</p>
            <p>Regards,<br><br>
            <strong>Turning Point Magazine Team</strong>
            </p>
        ";

        // Attach event flyer
        $mail->addAttachment('assets/nps1.jpg','assets/nps2.jpg' );

        // Send email
        $mail->send();
        echo "Email sent to: $email<br>";
    } catch (Exception $e) {
        echo "Error sending to $email: {$mail->ErrorInfo}<br>";
    }
}
?>
