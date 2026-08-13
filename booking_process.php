<?php
// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json'); // Ensure JSON response
$response = ["status" => "error", "message" => "Something went wrong!"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'vendor/autoload.php'; // Load Composer's autoloader

    // Database connection
    $host = 'localhost';
    $db = 'turning2_turningpoint1';
    $user = 'turning2_turningpoint1';
    $pass = 'Amo20.03'; // Replace with a secure password

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Database connection failed."]);
        exit;
    }

    // Sanitize input
    $firstname = htmlspecialchars(trim($_POST['f-name'] ?? ''));
    $lastname = htmlspecialchars(trim($_POST['l-name'] ?? ''));
    $organization = htmlspecialchars(trim($_POST['organization'] ?? ''));
    $contact = htmlspecialchars(trim($_POST['contact'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);

    if (!$firstname || !$lastname || !$organization || !$contact || !$email) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    // Validate email
    if (!$email) {
        echo json_encode(["status" => "error", "message" => "Invalid email address."]);
        exit;
    }

      // Check if the email already exists in the database
    $sql = "SELECT COUNT(*) FROM bookings WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $emailExists = $stmt->fetchColumn();
     // If email already exists, show error message
    if ($emailExists) {
         $response = ["status" => "exists", "message" => "This user is already subscribed."];
        exit;
    }

    // Generate ticket number
    $sql = "SELECT invoice FROM bookings ORDER BY id DESC LIMIT 1";
    $stmt = $pdo->query($sql);
    $lastInvoice = $stmt->fetchColumn();

    $newNumber = $lastInvoice ? (int)substr($lastInvoice, 5) + 1 : 1;
    $invoice = "TPM02" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

    // Get IP address of the user
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

    // Insert booking details into the database
    $sql = "INSERT INTO bookings (firstname, lastname, organization, contact, email, invoice, ipAddress) 
            VALUES (:firstname, :lastname, :organization, :contact, :email, :invoice, :ipAddress)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':organization', $organization);
    $stmt->bindParam(':contact', $contact);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':invoice', $invoice);
    $stmt->bindParam(':ipAddress', $ipAddress);

    if ($stmt->execute()) {
        $mail = new PHPMailer(true);

        try {
            // Email configuration
            $mail->isSMTP();
            $mail->Host = 'da8.host-ww.net';
            $mail->SMTPAuth = true;
            $mail->Username = 'info@turningpointmagazine.africa';
            $mail->Password = 'Amo20.03';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('info@turningpointmagazine.africa', 'Turning Point Magazine Africa');

            // **Email 1: Confirmation to User**
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'RE: Booking Successful';
            $mail->Body = "
                <p>Dear <strong>$organization</strong>,</p>
                <p>We are pleased to inform you that your booth reservation at the <strong>2025 Africa Exhibition & Trade Expo and Turning Point Magazine Issue 2 launch</strong> was successful. Below is your reservation number:</p>
                <p><strong>Reservation Number: $invoice</strong></p>
                <p>Thank you for choosing Turning Point Magazine!</p>
                <p>Best Regards,<br><br>
                <strong>Michael Ager</strong><br>
                Operations Manager<br>
                Turning Point Magazine<br>
                Tel: <strong>0718055457</strong><br>
                Website: <a href=http://www.turningpointmagazine.africa target=_blank >www.turningpointmagazine.africa</a><br>
                Address: Twiga Towers | 6th Floor | Suite 616
                </p>
                
                ";
            $mail->addAttachment('assets/m8.jpg', 'march-8th.jpg');
            $mail->send();

            // **Email 2: Notification to Admin**
            $mail->clearAddresses();
            $mail->addAddress('info@turningpointmagazine.africa');
            $mail->Subject = 'RE: New Booth Booking';
            $mail->Body = "
                <p>A new booth booking has been made. Below are the details:</p>
                <p><strong>Name:</strong> $firstname $lastname</p>
                <p><strong>Organization:</strong> $organization</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Contact:</strong> $contact</p>
                <p><strong>Ticket Number:</strong> $invoice</p>
            ";
            $mail->send();

            $response = ["status" => "success", "message" => "Booking successful! Confirmation email has been sent."];

        } catch (Exception $e) {
            $response = ["status" => "warning", "message" => "Booking successful, but emails could not be sent. Error: " . $e->getMessage()];
        }
    } else {
        $response = ["status" => "error", "message" => "Error occurred while saving your booking."];
    }
}

echo json_encode($response);
exit;
?>
