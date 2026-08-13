<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include 'connection2.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = connect();
    $award_id = (int)$_POST['award_id'];
    $applicant_name = htmlspecialchars($_POST['applicant_name']);
    $applicant_email = filter_var($_POST['applicant_email'], FILTER_VALIDATE_EMAIL);
    $applicant_phone = htmlspecialchars($_POST['applicant_phone']);
    $organization_name = htmlspecialchars($_POST['organization_name']);
    $application_text = htmlspecialchars($_POST['application_text']);
    $attachment_url = null;

    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'assets/uploads/awards/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $filename = uniqid() . '-' . basename($_FILES['attachment']['name']);
        $destination = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $destination)) {
            $attachment_url = $destination;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO award_applicants (award_id, applicant_name, applicant_email, applicant_phone, organization_name, application_text, attachment_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$award_id, $applicant_name, $applicant_email, $applicant_phone, $organization_name, $application_text, $attachment_url])) {
        // Send emails
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'da8.host-ww.net';
            $mail->SMTPAuth = true;
            $mail->Username = 'info@turningpointmagazine.africa';
            $mail->Password = 'Amo20.03';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Email to applicant
            $mail->setFrom('info@turningpointmagazine.africa', 'Turning Point Magazine');
            $mail->addAddress($applicant_email);
            $mail->isHTML(true);
            $mail->Subject = 'Application Received';
            $mail->Body = 'Thank you for your application. We will review it and get back to you shortly.';
            $mail->send();

            // Email to admin
            $mail->clearAddresses();
            $mail->addAddress('info@turningpointmagazine.africa');
            $mail->Subject = 'New Award Application';
            $mail->Body = "A new application has been submitted for an award.<br><b>Award ID:</b> $award_id<br><b>Applicant:</b> $applicant_name<br><b>Email:</b> $applicant_email";
            $mail->send();

            echo json_encode(['status' => 'success', 'message' => 'Application submitted successfully.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Application saved, but email could not be sent.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save application.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>