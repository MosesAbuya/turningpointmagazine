<?php
include 'connection.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid story ID.");
}

$storyId = $_GET['id'];
$pdo = connect();

// Fetch associated images
$query = "SELECT image_path FROM story_images WHERE story_id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$storyId]);
$images = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Close connection
closeConnection($pdo);

if (!$images) {
    die("No images to download.");
}

// Create a ZIP file
$zip = new ZipArchive();
$zipName = "story_images_$storyId.zip";

if ($zip->open($zipName, ZipArchive::CREATE) === TRUE) {
    foreach ($images as $image) {
        $filePath = __DIR__ . "/uploads/" . $image;
        if (file_exists($filePath)) {
            $zip->addFile($filePath, basename($filePath));
        }
    }
    $zip->close();

    // Download the ZIP file
    header('Content-Type: application/zip');
    header("Content-Disposition: attachment; filename=$zipName");
    header('Content-Length: ' . filesize($zipName));
    readfile($zipName);

    // Delete the ZIP file after download
    unlink($zipName);
} else {
    die("Failed to create ZIP file.");
}
?>
