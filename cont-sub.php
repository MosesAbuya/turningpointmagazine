<?php
 
 if (empty($activation_code)) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$activation_code = substr(str_shuffle($characters), 0, 32);  // 32-character code 
    }
// Ensure JSON response
header('Content-Type: application/json');

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'vendor/autoload.php'; // Load Composer's autoloader

// Database connection details
require_once 'includes/conn.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input values
    $firstname = htmlspecialchars(trim($_POST['f-name']), ENT_QUOTES, 'UTF-8');
    $lastname = htmlspecialchars(trim($_POST['l-name']), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $category = htmlspecialchars(trim($_POST['category']), ENT_QUOTES, 'UTF-8');
    $comments = htmlspecialchars(trim($_POST['comments']), ENT_QUOTES, 'UTF-8');
    
    // Check if the email is valid
    if (!$email) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
        exit;
    }

    // Check if the email already exists in the database
    // $emailCheckSql = "SELECT COUNT(*) FROM subscribers WHERE email = :email";
    // $emailCheckStmt = $pdo->prepare($emailCheckSql);
    // $emailCheckStmt->bindParam(':email', $email, PDO::PARAM_STR);
    // $emailCheckStmt->execute();
    // $emailExists = $emailCheckStmt->fetchColumn();

    // if ($emailExists) {
    //     echo json_encode(['status' => 'error', 'message' => 'Email already exists.']);
    //     exit;
    // }

    

    // Get IP address of the user
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

    // Insert the subscriber data into the database
    $sql = "INSERT INTO subscribers (firstname, lastname, email, activation_code, is_activated, category, comments, ipAddress) 
            VALUES (:firstname, :lastname, :email, :activation_code, 0, :category, :comments, :ipAddress)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
    $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':activation_code', $activation_code, PDO::PARAM_STR);
    $stmt->bindParam(':category', $category, PDO::PARAM_STR);
    $stmt->bindParam(':comments', $comments, PDO::PARAM_STR);
    $stmt->bindParam(':ipAddress', $ipAddress, PDO::PARAM_STR);

    if ($stmt->execute()) {
        // Send activation email using PHPMailer
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
            echo json_encode(['status' => 'success', 'message' => 'Your Message Has Been Sent Succesfully.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Could not send activation email.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add subscriber.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
