<?php
include 'connection.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if a story ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid story ID.");
}

// Connect to the database
$pdo = connect();
$storyId = $_GET['id'];

// Fetch story details
$query = "SELECT * FROM stories WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$storyId]);
$story = $stmt->fetch(PDO::FETCH_ASSOC);

// Close database connection
closeConnection($pdo);

// If no story is found, display an error
if (!$story) {
    die("Story not found.");
}

// Parse the images from the `photo` column
$imagePaths = [];
if (!empty($story['photo'])) {
    $imageFiles = explode(',', $story['photo']);
    foreach ($imageFiles as $file) {
        $imagePaths[] = '../' . trim($file);
    }
}

// Check if the download button is clicked and create a ZIP file
if (isset($_POST['download_zip'])) {
    $zip = new ZipArchive();
    $zipFile = 'story_images_' . $storyId . '.zip';

    // Open the zip file
    if ($zip->open($zipFile, ZipArchive::CREATE) !== TRUE) {
        exit("Cannot open <$zipFile>\n");
    }

    // Add files to the zip
    foreach ($imagePaths as $imagePath) {
        if (file_exists($imagePath)) {
            $zip->addFile($imagePath, basename($imagePath)); // Add file with its name
        }
    }

    // Close the zip file
    $zip->close();

    // Download the ZIP file
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . basename($zipFile) . '"');
    header('Content-Length: ' . filesize($zipFile));
    readfile($zipFile);

    // Delete the ZIP file after download
    unlink($zipFile);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Story Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .story-details {
            margin-top: 50px;
        }

        .images-gallery img {
            max-height: 200px;
            margin: 10px;
        }

        .btn-download {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container story-details">
        <h1 class="text-center">Story Details</h1>

        <div class="card mt-4">
            <div class="card-header">
                Story Information
            </div>
            <div class="card-body">
                <p><strong>Email:</strong> <?= htmlspecialchars($story['email']); ?></p>
                <p><strong>First Name:</strong> <?= htmlspecialchars($story['firstname']); ?></p>
                <p><strong>Last Name:</strong> <?= htmlspecialchars($story['lastname']); ?></p>
                <p><strong>Story:</strong> <?= nl2br(htmlspecialchars($story['story'])); ?></p>
                <p><strong>Category:</strong> <?= htmlspecialchars($story['category']); ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($story['p_description']); ?></p>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                Associated Images
            </div>
            <div class="card-body">
                <?php if (!empty($imagePaths)) { ?>
                    <div class="images-gallery">
                        <?php foreach ($imagePaths as $image) { ?>
                            <img loading="lazy" src="<?= htmlspecialchars($image); ?>" alt="Image" class="img-thumbnail">
                        <?php } ?>
                    </div>
                    <!-- Download Button -->
                    <form method="POST">
                        <button type="submit" name="download_zip" class="btn btn-primary btn-download">Download All Images as ZIP</button>
                    </form>
                <?php } else { ?>
                    <p>No images associated with this story.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</body>

</html>

