<?php
// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Database connection
$host = 'localhost';
$db = 'turningpoint';
$user = 'root'; // your database username
$pass = ''; // your database password

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $conn->real_escape_string($_POST['f-name']);
    $lastname = $conn->real_escape_string($_POST['l-name']);
    $address = $conn->real_escape_string($_POST['address']);
    $email = $conn->real_escape_string($_POST['email']);

    // Generate a random activation code
    $activation_code = bin2hex(random_bytes(16));

    // Insert into database
    $sql = "INSERT INTO subscribers (firstname, lastname, address, email, activation_code, is_activated) 
            VALUES ('$firstname', '$lastname', '$address', '$email', '$activation_code', 0)";

    if ($conn->query($sql) === TRUE) {
        // Send activation email
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'mosesabuya812@gmail.com'; // SMTP username
            $mail->Password = 'cvgtruvemluqnvdx'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 465;

            $mail->SMTPSecure = 'ssl'; 
            // Recipients
            $mail->setFrom('mosesabuya812@gmail.com', 'Turningpoint');
            $mail->addAddress($email); // Add the user's email

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Confirm your email';
            $mail->Body = "Thank you for subscribing! Please click the link below to activate your subscription:<br>
                           <a href='http://localhost/malshe/activate.php?code=$activation_code'>Activate Subscription</a>";

            $mail->send();
            echo 'Activation email has been sent!';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
