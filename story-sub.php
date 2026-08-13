<?php
header('Content-Type: application/json');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

require_once 'includes/conn.php';

// Database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize inputs
    $firstname = filter_input(INPUT_POST, 'f-name', FILTER_SANITIZE_STRING);
    $lastname = filter_input(INPUT_POST, 'l-name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
    $story = filter_input(INPUT_POST, 'story', FILTER_SANITIZE_STRING);
    $p_description = filter_input(INPUT_POST, 'p_description', FILTER_SANITIZE_STRING);
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

    // Handle file uploads
    $photos = [];
    $maxFileSize = 100 * 1024 * 1024; // 100MB in bytes
    if (isset($_FILES['photo'])) {
        $uploadDir = 'assets/uploads/';
        foreach ($_FILES['photo']['name'] as $key => $name) {
            $fileTmpName = $_FILES['photo']['tmp_name'][$key];
            $fileSize = $_FILES['photo']['size'][$key];

            // Check file size
            if ($fileSize > $maxFileSize) {
                echo json_encode(['status' => 'error', 'message' => 'File size exceeds 100MB.']);
                exit;
            }

            // Check for errors
            if ($_FILES['photo']['error'][$key] === UPLOAD_ERR_OK) {
                $uploadFile = $uploadDir . uniqid() . '-' . basename($name);
                $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'ppt', 'docx', 'doc', 'webp'];
                if (!in_array($fileType, $allowedTypes)) {
                    echo json_encode(['status' => 'error', 'message' => 'Invalid file type.']);
                    exit;
                }
                if (move_uploaded_file($fileTmpName, $uploadFile)) {
                    $photos[] = $uploadFile;
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'File upload failed.']);
                    exit;
                }
            }
        }
    }

    // Prepare photo data for insertion into the database
    $photosStr = implode(',', $photos);

    // Insert story data into the database
    $sql = "INSERT INTO stories (firstname, lastname, email, category, story, p_description, photo, ipAddress)
            VALUES (:firstname, :lastname, :email, :category, :story, :p_description, :photo, :ipAddress)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':story', $story);
    $stmt->bindParam(':p_description', $p_description);
    $stmt->bindParam(':photo', $photosStr);
    $stmt->bindParam(':ipAddress', $ipAddress);

    // Execute the insert and send email if successful
    if ($stmt->execute()) {
        // Send email to info@turningpointmagazine.africa with the story details
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'da8.host-ww.net';
            $mail->SMTPAuth = true;
            $mail->Username = 'info@turningpointmagazine.africa';
            $mail->Password = 'Amo20.03';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipient details
            $mail->setFrom('info@turningpointmagazine.africa', 'Turningpoint');
            $mail->addAddress('info@turningpointmagazine.africa');

            $mail->isHTML(true);
            $mail->Subject = 'New Story Submission';
            $mail->Body = "
                <p>You have received a new story submission from:</p>
                <p><strong>First Name:</strong> $firstname</p>
                <p><strong>Last Name:</strong> $lastname</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Category:</strong> $category</p>
                <p><strong>Story:</strong> $story</p>
                <p><strong>Description:</strong> $p_description</p>
                <p><strong>IP Address:</strong> $ipAddress</p>
                <p><strong>Photos:</strong> " . (!empty($photosStr) ? 'Yes' : 'No') . "</p>
            ";

            // Send the email
            $mail->send();
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => "Failed to send email. Error: " . $e->getMessage()]);
            exit;
        }

        echo json_encode(['status' => 'success', 'message' => 'Thank you for sharing your story!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save your story.']);
    }
}
?>
