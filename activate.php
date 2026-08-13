 <link rel="apple-touch-icon" sizes="180x180" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>favicon-16x16.png">
    <link rel="manifest" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>site.webmanifest">
<style>
/* Style for success and error messages */
.success-message, .error-message {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    border-radius: 10px;
    font-size: 2rem;
    text-align: center;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

@media screen and (max-width : 920px){
.success-message, .error-message{
 font-size: 2rem;
 height: fit-content;
}
}
.success-message {
    background-color: #e0f7fa;
    color: #00796b;
}

.error-message {
    background-color: #ffebee;
    color: #c62828;
}

.success-message h2, .error-message h2 {
    margin-bottom: 20px;
}

.success-message p, .error-message p {
    margin-bottom: 30px;
}

.btn {
font-size: 2rem;
    display: inline-block;
    padding: 10px 20px;
    background-color: #d44b4b;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: red;
}

</style>

<?php
// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Database connection
$host = 'localhost';
$db = 'turning2_turningpoint1';
$user = 'turning2_turningpoint1';
$pass = 'Amo20.03'; // Replace with a secure password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Database connection error
    echo "Database connection failed: " . $e->getMessage();
    exit;
}

if (isset($_GET['code'])) {
    $activation_code = $_GET['code'];

    // Query to check if the activation code exists in the database
    try {
        $sql = "SELECT * FROM subscribers WHERE activation_code = :activation_code";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':activation_code', $activation_code, PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $subscriber = $stmt->fetch(PDO::FETCH_ASSOC);

            // Update the subscriber to activated
            $update_sql = "UPDATE subscribers SET is_activated = 1 WHERE activation_code = :activation_code";
            $update_stmt = $pdo->prepare($update_sql);
            $update_stmt->bindParam(':activation_code', $activation_code, PDO::PARAM_STR);
            
            if ($update_stmt->execute()) {
                // Send a confirmation email to the user
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
                    $mail->addAddress($subscriber['email']);

                    $mail->isHTML(true);
                    $mail->Subject = 'Subscription Activated';
                    $mail->Body = "Dear " . $subscriber['firstname'] . " " . $subscriber['lastname'] . ",<br><br>
                                   Thank you for confirming your subscription to Turningpoint Magazine.<br><br>
                                   We're excited to have you on board!";

                    $mail->send();
                } catch (Exception $e) {
                    echo "There was an issue sending the confirmation email. Error: " . $mail->ErrorInfo . "<br>";
                }

                // Display success message
                echo "<div class='success-message'>";
                echo "<h2>Subscription Activated Successfully!</h2>";
                echo "<p>Thank you for confirming your subscription to Turningpoint Magazine.</p>";
                echo "<a href='index.php' class='btn'>Return to Homepage</a>";
                echo "</div>";
            } else {
                // Error while updating the subscription status
                echo "<div class='error-message'>";
                echo "<h2>Activation Failed</h2>";
                echo "<p>Could not update the subscription. Please try again later.</p>";
                echo "<a href='index.php' class='btn'>Return to Homepage</a>";
                echo "</div>";
            }
        } else {
            // Invalid activation code
            echo "<div class='error-message'>";
            echo "<h2>Invalid Activation Code</h2>";
            echo "<p>Please check the link and try again.</p>";
            echo "<a href='index.php' class='btn'>Return to Homepage</a>";
            echo "</div>";
        }
    } catch (PDOException $e) {
        // Error during the query execution
        echo "<div class='error-message'>";
        echo "<h2>Error Occurred</h2>";
        echo "<p>There was an error while checking the activation code: " . $e->getMessage() . "</p>";
        echo "<a href='index.php' class='btn'>Return to Homepage</a>";
        echo "</div>";
    }
} else {
    // No activation code provided
    echo "<div class='error-message'>";
    echo "<h2>No Activation Code Provided</h2>";
    echo "<p>Please provide an activation code in the URL.</p>";
    echo "<a href='index.php' class='btn'>Return to Homepage</a>";
    echo "</div>";
}
?>

