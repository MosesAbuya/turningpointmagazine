<?php
include 'connection2.php';
session_start();
include 'consent.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = connect();
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_spotlight'])) {
    $partner_name = trim($_POST['partner_name'] ?? '');
    $post_title = trim($_POST['post_title'] ?? '');
    $post_type = trim($_POST['post_type'] ?? 'Story');
    $post_description = $_POST['post_description'] ?? ''; 
    $external_link = trim($_POST['external_link'] ?? '');

    // --- SECURE FILE UPLOAD LOGIC ---
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

    // --- Multiple Upload Logic ---
    function handle_multiple_uploads($file_key, $upload_dir = "assets/uploads/", $keep_original_name = false) {
        $uploaded_files = [];
        if (isset($_FILES[$file_key]) && is_array($_FILES[$file_key]['name'])) {
            $files = $_FILES[$file_key];
            $file_count = count($files['name']);
            $target_directory = __DIR__ . '/' . $upload_dir; 

            if (!is_dir($target_directory)) {
                mkdir($target_directory, 0775, true);
            }

            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'mp4', 'webm', 'mov'];

            for ($i = 0; $i < $file_count; $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $file_tmp_path = $files['tmp_name'][$i];
                    $original_file_name = $files['name'][$i];
                    $sanitized_file_name = preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($original_file_name));
                    
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
                            $uploaded_files[] = $stored_path; 
                        }
                    }
                }
            }
        }
        return $uploaded_files;
    }

    // Process Uploads
    $thumbnail_image_target = handle_upload('thumbnail_image');
    if (!$thumbnail_image_target) {
        $error_message = 'Please upload a valid thumbnail image (JPG, PNG, GIF, WebP).';
    } else {
        $document_target = handle_upload('document', "assets/uploads/", true); 
        $uploaded_files = handle_multiple_uploads('file_upload', "assets/uploads/", true);
        $file_upload_json = json_encode($uploaded_files);

        // Insert into database
        try {
            $insertQuery = "INSERT INTO spotlight_posts
                (partner_name, post_title, post_type, post_description, thumbnail_image, file_upload, external_link, document)
                VALUES (:partner_name, :post_title, :post_type, :post_description, :thumbnail_image, :file_upload, :external_link, :document)";

            $stmt = $pdo->prepare($insertQuery);
            $stmt->execute([
                'partner_name' => $partner_name,
                'post_title' => $post_title,
                'post_type' => $post_type,
                'post_description' => $post_description,
                'thumbnail_image' => $thumbnail_image_target,
                'file_upload' => $file_upload_json,
                'external_link' => $external_link,
                'document' => $document_target,
            ]);

            header('Location: manage-spotlight.php');
            exit;
        } catch (Exception $e) {
            $error_message = 'Failed to create spotlight post: ' . $e->getMessage();
        }
    }
}

closeConnection($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Spotlight Post</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
    <script>
    tinymce.init({
        selector: 'textarea#post_description',
        plugins: 'advlist autolink lists link image charmap print preview anchor pagebreak',
        toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        menubar: false,
        height: 400,
        branding: false
    });
    </script>
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body id="body">
    <?php include "nav.php"; ?>
    <?php include "sidebar.php"; ?>
    <div id="page-content-wrapper">
    <div class="container-fluid">
        <h2 class="underline">Add New Spotlight Post</h2>
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
                <input type="text" class="form-control" id="partner_name" name="partner_name" required>
            </div>
            <div class="form-group">
                <label for="post_title">Post Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="post_title" name="post_title" required>
            </div>
            <div class="form-group">
                <label for="post_type">Post Type <span class="text-danger">*</span></label>
                <select class="form-control" id="post_type" name="post_type" required>
                    <option value="Story">Story</option>
                    <option value="PDF">PDF</option>
                    <option value="Image">Image</option>
                    <option value="Advertisement">Advertisement</option>
                </select>
            </div>
            <div class="form-group">
                <label for="post_description">Post Description</label>
                <textarea id="post_description" name="post_description" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="thumbnail_image">Thumbnail Image (JPG, PNG, GIF, WebP) <span class="text-danger">*</span></label>
                <input type="file" class="form-control-file" id="thumbnail_image" name="thumbnail_image"
                    accept=".jpg,.jpeg,.png,.gif,.webp" required>
            </div>
            
            <div class="form-group">
                <label for="document">Attach Document (Optional - PDF, DOC, DOCX, XLS, PPT)</label>
                <input type="file" class="form-control-file" id="document" name="document"
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
            </div>

            <div class="form-group">
                <label for="file_upload">Additional Gallery Files (PDF, JPG, PNG, MP4, etc.)</label>
                <input type="file" class="form-control-file" id="file_upload" name="file_upload[]"
                    accept=".pdf,.jpg,.jpeg,.png,.webp,.mp4,.webm" multiple>
            </div>
            <div class="form-group">
                <label for="external_link">External Link (Optional)</label>
                <input type="url" class="form-control" id="external_link" name="external_link" placeholder="https://example.com">
            </div>
            <button type="submit" class="btn btn-primary mt-3 mb-5" name="add_spotlight">Add Spotlight Post</button>
            <a href="manage-spotlight.php" class="btn btn-secondary mt-3 mb-5 ml-2">Cancel</a>
        </form>
    </div>
    </div>
</body>
</html>