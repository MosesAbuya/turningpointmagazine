<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include 'connection2.php';
session_start();
include 'consent.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = connect();
$applicant_id = (int)$_GET['id'];

// Fetch applicant details before any updates
$stmt = $pdo->prepare("
    SELECT aa.*, ata.title AS award_title
    FROM award_applicants aa
    JOIN awards_to_apply ata ON aa.award_id = ata.id
    WHERE aa.id = ?
");
$stmt->execute([$applicant_id]);
$applicant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$applicant) {
    die("Applicant not found.");
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $new_status = htmlspecialchars($_POST['status']);

    // Check if the status is changing to "Accepted"
    if ($new_status === 'Accepted' && $applicant['status'] !== 'Accepted') {
        // Send email notification
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'da8.host-ww.net';
            $mail->SMTPAuth = true;
            $mail->Username = 'info@turningpointmagazine.africa';
            $mail->Password = 'Amo20.03';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('info@turningpointmagazine.africa', 'Turning Point Magazine');
            $mail->addAddress($applicant['applicant_email'], $applicant['applicant_name']);
            $mail->isHTML(true);
            $mail->Subject = 'Congratulations! Your Application has been Accepted';
            $mail->Body = "
                Dear " . htmlspecialchars($applicant['applicant_name']) . ",<br><br>
                We are delighted to inform you that your application for the '<strong>" . htmlspecialchars($applicant['award_title']) . "</strong>' award has been accepted.<br><br>
                Congratulations on this achievement! We will be in touch with further details soon.<br><br>
                Best regards,<br>
                The Turning Point Magazine Team
            ";
            $mail->send();
        } catch (Exception $e) {
            // Optional: Handle mail sending error (e.g., log it)
            error_log("PHPMailer Error: " . $mail->ErrorInfo);
        }
    }

    $update_stmt = $pdo->prepare("UPDATE award_applicants SET status = :status WHERE id = :id");
    $update_stmt->execute(['status' => $new_status, 'id' => $applicant_id]);

    header("Location: view_applicant.php?id=$applicant_id&status_updated=true");
    exit;
}

closeConnection($pdo);
?>
<style>
#body {
    background-color: #f8f9fa;
    width: calc(100% - 250px);
    margin-left: 250px;
    margin-top: 100px;
}
</style>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applicant Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="form.css">
    <style>
    .applicant-details-card {
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 8px;
        background-color: #f9f9f9;
    }
    </style>
</head>
<?php include 'nav.php'; ?>

<body id="body">
    <div class="container mt-5">
        <h2>Applicant Details</h2>

        <?php if (isset($_GET['status_updated'])): ?>
        <div class="alert alert-success">Status updated successfully!</div>
        <?php endif; ?>

        <div class="applicant-details-card">
            <h4><?= htmlspecialchars($applicant['applicant_name']) ?></h4>
            <hr>
            <p><strong>Award:</strong> <?= htmlspecialchars($applicant['award_title']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($applicant['applicant_email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($applicant['applicant_phone'] ?: 'N/A') ?></p>
            <p><strong>Organization:</strong> <?= htmlspecialchars($applicant['organization_name'] ?: 'N/A') ?></p>
            <p><strong>Applied At:</strong> <?= htmlspecialchars($applicant['applied_at']) ?></p>

            <h5>Application Text</h5>
            <div style="background-color: #fff; padding: 15px; border-radius: 5px; border: 1px solid #eee;">
                <?= nl2br(htmlspecialchars($applicant['application_text'])) ?>
            </div>

            <?php if ($applicant['attachment_url']): ?>
            <p class="mt-3">
                <strong>Attachment:</strong>
                <a href="../<?= htmlspecialchars($applicant['attachment_url']) ?>" target="_blank"
                    class="btn btn-secondary btn-sm">Download Attachment</a>
            </p>
            <?php endif; ?>

            <hr>

            <form method="POST">
                <div class="form-group">
                    <label for="status"><strong>Update Status</strong></label>
                    <div class="d-flex">
                        <select name="status" id="status" class="form-control"
                            style="width: 200px; margin-right: 10px;">
                            <option value="Pending" <?= $applicant['status'] == 'Pending' ? 'selected' : '' ?>>Pending
                            </option>
                            <option value="Reviewed" <?= $applicant['status'] == 'Reviewed' ? 'selected' : '' ?>>
                                Reviewed</option>
                            <option value="Accepted" <?= $applicant['status'] == 'Accepted' ? 'selected' : '' ?>>
                                Accepted</option>
                            <option value="Rejected" <?= $applicant['status'] == 'Rejected' ? 'selected' : '' ?>>
                                Rejected</option>
                        </select>
                        <button type="submit" name="update_status" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
        <a href="award_applicants.php" class="btn btn-light mt-3">Back to Applicants List</a>
    </div>
</body>
<?php include 'sidebar.php'; ?>

</html>