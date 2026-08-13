<?php
// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

  if (empty($activation_code)) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$activation_code = substr(str_shuffle($characters), 0, 32);  // 32-character code 
    }
// Load Composer's autoloader
require 'vendor/autoload.php';

// Database connection
require_once 'includes/conn.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $firstname = htmlspecialchars(trim($_POST['f-name']));
    $lastname = htmlspecialchars(trim($_POST['l-name']));
    $age = htmlspecialchars(trim($_POST['age']));
    $gender = htmlspecialchars(trim($_POST['gender']));
    $address = htmlspecialchars(trim($_POST['address']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $comments = ''; // Default comments value

    // Validate email
    if (!$email) {
        echo "Invalid email address.";
        exit;
    }

    // Only generate the activation code if it's not already set
  

    // Check if the email already exists in the database
    $sql = "SELECT COUNT(*) FROM subscribers WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $emailExists = $stmt->fetchColumn();

    // If email already exists, show error message
    if ($emailExists) {
        echo "This email is already subscribed.";
        exit;
    }

// Get IP address of the user
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    // Insert subscriber details along with the activation code into the database
    $sql = "INSERT INTO subscribers (firstname, lastname, gender, age, address, email, activation_code, is_activated, comments, ipAddress) 
            VALUES (:firstname, :lastname, :gender, :age, :address, :email, :activation_code, 0, :comments, :ipAddress)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':age', $age);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':activation_code', $activation_code);
    $stmt->bindParam(':comments', $comments);
    $stmt->bindParam(':ipAddress', $ipAddress);

    // Execute the insertion query
    if ($stmt->execute()) {
        
        // Send confirmation email with the same activation code
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'da8.host-ww.net';
            $mail->SMTPAuth = true;
            $mail->Username = 'info@turningpointmagazine.africa';
            $mail->Password = 'Amo20.03';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('info@turningpointmagazine.africa', 'Turningpoint');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Confirm your email';
            $mail->Body = "Thank you for subscribing! Please click the link below to activate your subscription:<br>
                           <a href='https://turningpointmagazine.africa/activate.php?code=$activation_code'>Activate Subscription</a>";

            // Send the email
            $mail->send();
            echo "Thank you for subscribing! Please check your email to confirm your subscription.";
        } catch (Exception $e) {
            echo "Subscription successful, but we couldn't send the activation email. Error: " . $e->getMessage();
        }
    } else {
        echo "Error occurred while saving your subscription.";
    }
}
?>
