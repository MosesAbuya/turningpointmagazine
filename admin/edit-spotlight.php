<?php
include 'connection2.php';
session_start();
include 'consent.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = connect();
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$post_id) {
    header('Location: manage-spotlight.php');
    exit;
}

// Fetch existing post
$stmt = $pdo->prepare("SELECT * FROM spotlight_posts WHERE id = :id");
$stmt->execute(['id' => $post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    header('Location: manage-spotlight.php');
    exit;
}

$error_message = '';

function removeFileSafely($filePath) {
    if (empty($filePath)) return;
    $p1 = __DIR__ . '/' . $filePath;
    $p2 = __DIR__ . '/../' . $filePath;
    if (file_exists($p1) && !is_dir($p1)) @unlink($p1);
    if (file_exists($p2) && !is_dir($p2)) @unlink($p2);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_spotlight'])) {
    $partner_name = trim($_POST['partner_name'] ?? '');
    $post_title = trim($_POST['post_title'] ?? '');
    $post_type = trim($_POST['post_type'] ?? 'Story');
    $post_description = $_POST['post_description'] ?? '';
    $external_link = trim($_POST['external_link'] ?? '');

    // Secure File Upload Logic
    function handle_upload($file_key, $upload_dir = "assets/uploads/", $keep_original_name = false) {
        if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
            $file_tmp_path = $_FILES[$file_key]['tmp_name'];
            $original_file_name = $_FILES[$file_key]['name'];
            $sanitized_file_name = preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($original_file_name));
            
            if ($keep_original_name) {
                $new_file_name = time() . '_' . $sanitized_file_name;
            } else {
                $new_file_name = uniqid('', true) . '_' . $sanitized_file_name;
            }
            
            $target_directory = __DIR__ . '/' . $upload_dir; 
            if (!is_dir($target_directory)) {
                mkdir($target_directory, 0775, true);
            }

            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
            $file_extension = strtolower(pathinfo($new_file_name, PATHINFO_EXTENSION));

            if (in_array($file_extension, $allowed_extensions)) {
                $dest_path = $target_directory . $new_file_name; 
                $stored_path = $upload_dir . $new_file_name; 
                if (move_uploaded_file($file_tmp_path, $dest_path)) {
                    return $stored_path;
                }
            }
        }
        return null;
    }

    function handle_multiple_uploads($file_key, $upload_dir = "assets/uploads/", $keep_original_name = false) {
        $uploaded_paths = [];
        if (isset($_FILES[$file_key]) && is_array($_FILES[$file_key]['name'])) {
            $files = $_FILES[$file_key];
            $target_directory = __DIR__ . '/' . $upload_dir; 
            if (!is_dir($target_directory)) {
                mkdir($target_directory, 0775, true);
            }

            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'mp4', 'webm', 'mov'];

            foreach ($files['name'] as $index => $name) {
                if ($files['error'][$index] === UPLOAD_ERR_OK) {
                    $file_tmp_path = $files['tmp_name'][$index];
                    $sanitized_file_name = preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($name));
                    
                    if ($keep_original_name) {
                        $new_file_name = time() . '_' . $sanitized_file_name;
                    } else {
                        $new_file_name = uniqid('', true) . '_' . $sanitized_file_name;
                    }
                    
                    $file_extension = strtolower(pathinfo($new_file_name, PATHINFO_EXTENSION));
                    if (in_array($file_extension, $allowed_extensions)) {
                        $dest_path = $target_directory . $new_file_name; 
                        $stored_path = $upload_dir . $new_file_name; 
                        
                        if (move_uploaded_file($file_tmp_path, $dest_path)) {
                            $uploaded_paths[] = $stored_path;
                        }
                    }
                }
            }
        }
        return $uploaded_paths;
    }

    // Handle thumbnail update
    $thumbnail_image_target = $post['thumbnail_image'];
    if (!empty($_FILES['thumbnail_image']['name'])) {
        $new_thumbnail = handle_upload('thumbnail_image');
        if ($new_thumbnail) {
            removeFileSafely($thumbnail_image_target);
            $thumbnail_image_target = $new_thumbnail;
        }
    }

    // Handle Document Update
    $document_target = $post['document'] ?? null; 
    if (!empty($_FILES['document']['name'])) {
        $new_doc = handle_upload('document', "assets/uploads/", true); 
        if ($new_doc) {
            removeFileSafely($document_target);
            $document_target = $new_doc;
        }
    }

    // Handle multiple file uploads and removals
    $existing_files = json_decode($post['file_upload'] ?? '', true) ?: [];
    if (isset($_POST['remove_files']) && is_array($_POST['remove_files'])) {
        foreach ($_POST['remove_files'] as $file_to_remove) {
            if (($key = array_search($file_to_remove, $existing_files)) !== false) {
                removeFileSafely($existing_files[$key]);
                unset($existing_files[$key]);
            }
        }
    }
    
    $new_files = handle_multiple_uploads('file_upload', "assets/uploads/", true);
    $updated_files = array_merge(array_values($existing_files), $new_files);
    $file_upload_json = json_encode($updated_files);

    // Update database
    try {
        $updateQuery = "UPDATE spotlight_posts SET
            partner_name = :partner_name,
            post_title = :post_title,
            post_type = :post_type,
            post_description = :post_description,
            thumbnail_image = :thumbnail_image,
            file_upload = :file_upload,
            external_link = :external_link,
            document = :document
            WHERE id = :id";

        $stmt = $pdo->prepare($updateQuery);
        $stmt->execute([
            'partner_name' => $partner_name,
            'post_title' => $post_title,
            'post_type' => $post_type,
            'post_description' => $post_description,
            'thumbnail_image' => $thumbnail_image_target,
            'file_upload' => $file_upload_json,
            'external_link' => $external_link,
            'document' => $document_target,
            'id' => $post_id
        ]);

        header('Location: manage-spotlight.php');
        exit;
    } catch (Exception $e) {
        $error_message = 'Failed to update spotlight post: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Spotlight Post</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
    <script>
    tinymce.init({
        selector: 'textarea#post_description',
        height: 400,
        menubar: false,
        plugins: 'lists link image',
        toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright | bullist numlist | link image'
    });
    </script>
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
    <style>
        .current-files .file-item { display: flex; align-items: center; margin-bottom: 10px; padding: 6px 10px; background: #f8f9fa; border-radius: 4px; }
        .current-files .file-item input { margin-right: 10px; }
    </style>
</head>

<body id="body">
    <?php include "nav.php"; ?>
    <?php include "sidebar.php"; ?>
    <div id="page-content-wrapper">
<div class="container-fluid">
    <h2 class="underline">Edit Spotlight Post</h2>
    <div class="sep"></div>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="partner_name">Partner Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="partner_name" name="partner_name" value="<?= htmlspecialchars($post['partner_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="form-group">
            <label for="post_title">Post Title <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="post_title" name="post_title" value="<?= htmlspecialchars($post['post_title'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="form-group">
            <label for="post_type">Post Type <span class="text-danger">*</span></label>
            <select class="form-control" id="post_type" name="post_type" required>
                <option value="Story" <?= ($post['post_type'] ?? '') == 'Story' ? 'selected' : '' ?>>Story</option>
                <option value="PDF" <?= ($post['post_type'] ?? '') == 'PDF' ? 'selected' : '' ?>>PDF</option>
                <option value="Image" <?= ($post['post_type'] ?? '') == 'Image' ? 'selected' : '' ?>>Image</option>
                <option value="Advertisement" <?= ($post['post_type'] ?? '') == 'Advertisement' ? 'selected' : '' ?>>Advertisement</option>
            </select>
        </div>
        <div class="form-group">
            <label for="post_description">Post Description</label>
            <textarea id="post_description" name="post_description" class="form-control"><?= htmlspecialchars($post['post_description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
        <div class="form-group">
            <label>Current Thumbnail</label><br>
            <?php
            $thumb = $post['thumbnail_image'] ?? '';
            $thumbUrl = '';
            if (!empty($thumb)) {
                $thumbUrl = file_exists(__DIR__ . '/' . $thumb) ? $thumb : (file_exists(__DIR__ . '/../' . $thumb) ? '../' . $thumb : $thumb);
            }
            ?>
            <?php if (!empty($thumbUrl)): ?>
                <img loading="lazy" src="<?= htmlspecialchars($thumbUrl, ENT_QUOTES, 'UTF-8') ?>" alt="Thumbnail" style="max-width: 200px; max-height: 140px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; padding: 4px;">
            <?php else: ?>
                <p class="text-muted">No thumbnail uploaded.</p>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="thumbnail_image">Update Thumbnail (optional - JPG, PNG, GIF, WebP)</label>
            <input type="file" class="form-control-file" id="thumbnail_image" name="thumbnail_image" accept=".jpg,.jpeg,.png,.gif,.webp">
        </div>
        
        <div class="form-group">
            <label>Current Document</label><br>
            <?php
            $doc = $post['document'] ?? '';
            $docUrl = '';
            if (!empty($doc)) {
                $docUrl = file_exists(__DIR__ . '/' . $doc) ? $doc : (file_exists(__DIR__ . '/../' . $doc) ? '../' . $doc : $doc);
            }
            ?>
            <?php if (!empty($docUrl)): ?>
                <a href="<?= htmlspecialchars($docUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="btn btn-sm btn-info mb-2"><i class="fas fa-file-alt mr-1"></i> View Existing Document (<?= basename($doc) ?>)</a>
            <?php else: ?>
                <p class="text-muted">No document attached.</p>
            <?php endif; ?>
            <label for="document">Update Document (Optional - PDF, DOC, DOCX, XLS, PPT)</label>
            <input type="file" class="form-control-file" id="document" name="document" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
        </div>

        <div class="form-group">
            <label>Current Gallery Files</label>
            <div class="current-files">
                <?php
                $files = json_decode($post['file_upload'] ?? '', true) ?: [];
                if (!empty($files)):
                    foreach ($files as $file):
                        $fileUrl = file_exists(__DIR__ . '/' . $file) ? $file : (file_exists(__DIR__ . '/../' . $file) ? '../' . $file : $file);
                ?>
                <div class="file-item">
                    <input type="checkbox" name="remove_files[]" value="<?= htmlspecialchars($file, ENT_QUOTES, 'UTF-8') ?>" id="del_<?= md5($file) ?>">
                    <label for="del_<?= md5($file) ?>" class="mb-0 text-danger mr-2" style="cursor: pointer;"><i class="fas fa-trash-alt"></i> Remove</label>
                    <a href="<?= htmlspecialchars($fileUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank"><?= htmlspecialchars(basename($file), ENT_QUOTES, 'UTF-8') ?></a>
                </div>
                <?php 
                    endforeach;
                else: 
                ?>
                    <p class="text-muted">No gallery files uploaded.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="form-group">
            <label for="file_upload">Upload New Gallery Files (optional)</label>
            <input type="file" class="form-control-file" id="file_upload" name="file_upload[]" accept=".pdf,.jpg,.jpeg,.png,.webp,.mp4,.webm" multiple>
        </div>
        <div class="form-group">
            <label for="external_link">External Link</label>
            <input type="url" class="form-control" id="external_link" name="external_link" value="<?= htmlspecialchars($post['external_link'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="https://example.com">
        </div>
        <button type="submit" class="btn btn-primary mt-3 mb-5" name="update_spotlight">Update Post</button>
        <a href="manage-spotlight.php" class="btn btn-secondary mt-3 mb-5 ml-2">Cancel</a>
    </form>
</div>
    </div>
</body>
</html>