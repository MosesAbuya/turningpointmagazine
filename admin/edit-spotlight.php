<?php
include 'connection2.php';
session_start();

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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_spotlight'])) {
    $partner_name = htmlspecialchars($_POST['partner_name']);
    $post_title = htmlspecialchars($_POST['post_title']);
    $post_type = htmlspecialchars($_POST['post_type']);
    $post_description = $_POST['post_description'];
    $external_link = htmlspecialchars($_POST['external_link']);

    // --- Secure File Upload Logic ---
    // UPDATED: Added $keep_original_name parameter
    function handle_upload($file_key, $upload_dir = "assets/uploads/", $keep_original_name = false) {
        if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
            $file_tmp_path = $_FILES[$file_key]['tmp_name'];
            $original_file_name = $_FILES[$file_key]['name'];
            $sanitized_file_name = preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($original_file_name));
            
            // Logic to optionally keep original name
            if ($keep_original_name) {
                $new_file_name = $sanitized_file_name;
            } else {
                $new_file_name = uniqid('', true) . '_' . $sanitized_file_name;
            }
            
            $dest_path = '../' . $upload_dir . $new_file_name; 
            $stored_path = $upload_dir . $new_file_name; 

            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
            $file_extension = strtolower(pathinfo($dest_path, PATHINFO_EXTENSION));
            if (in_array($file_extension, $allowed_extensions)) {
                if (move_uploaded_file($file_tmp_path, $dest_path)) {
                    return $stored_path;
                }
            }
        }
        return null;
    }

    // UPDATED: Added $keep_original_name parameter here too
    function handle_multiple_uploads($file_key, $upload_dir = "assets/uploads/", $keep_original_name = false) {
        $uploaded_paths = [];
        if (isset($_FILES[$file_key])) {
            $files = $_FILES[$file_key];
            foreach ($files['name'] as $index => $name) {
                if ($files['error'][$index] === UPLOAD_ERR_OK) {
                    $file_tmp_path = $files['tmp_name'][$index];
                    $sanitized_file_name = preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($name));
                    
                    // Logic to optionally keep original name
                    if ($keep_original_name) {
                        $new_file_name = $sanitized_file_name;
                    } else {
                        $new_file_name = uniqid('', true) . '_' . $sanitized_file_name;
                    }
                    
                    $dest_path = '../' . $upload_dir . $new_file_name; 
                    $stored_path = $upload_dir . $new_file_name; 
                    
                    if (move_uploaded_file($file_tmp_path, $dest_path)) {
                        $uploaded_paths[] = $stored_path;
                    }
                }
            }
        }
        return $uploaded_paths;
    }

    // Handle thumbnail update (Keep random ID for uniqueness)
    $thumbnail_image_target = $post['thumbnail_image'];
    if (!empty($_FILES['thumbnail_image']['name'])) {
        $new_thumbnail = handle_upload('thumbnail_image');
        if ($new_thumbnail) {
            if ($thumbnail_image_target && file_exists('../' . $thumbnail_image_target)) { 
                unlink('../' . $thumbnail_image_target);
            }
            $thumbnail_image_target = $new_thumbnail;
        }
    }

    // Handle Document Update (Original Name = TRUE)
    $document_target = $post['document']; 
    if (!empty($_FILES['document']['name'])) {
        // Pass true to keep name
        $new_doc = handle_upload('document', "assets/uploads/", true); 
        if ($new_doc) {
            if ($document_target && file_exists('../' . $document_target)) { 
                unlink('../' . $document_target); 
            }
            $document_target = $new_doc;
        }
    }

    // Handle multiple file uploads
    $existing_files = json_decode($post['file_upload'], true) ?: [];
    if (isset($_POST['remove_files'])) {
        foreach ($_POST['remove_files'] as $file_to_remove) {
            if (($key = array_search($file_to_remove, $existing_files)) !== false) {
                if (file_exists('../' . $existing_files[$key])) { 
                    unlink('../' . $existing_files[$key]);
                }
                unset($existing_files[$key]);
            }
        }
    }
    
    // UPDATED: Pass true to keep original names for gallery files
    $new_files = handle_multiple_uploads('file_upload', "assets/uploads/", true);
    
    $updated_files = array_merge(array_values($existing_files), $new_files);
    $file_upload_json = json_encode($updated_files);

    // Update database
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
    <style>
    #body { background-color: #f8f9fa; width: calc(100% - 250px); margin-left: 250px; margin-top: 100px; }
    .current-files .file-item { display: flex; align-items: center; margin-bottom: 10px; }
    .current-files .file-item img { max-width: 100px; margin-right: 10px; }
    </style>
    <link rel="stylesheet" href="form.css">
</head>

<?php include 'nav.php'; ?>

<body id="body">
<div class="container mt-5">
    <h2 class="underline">Edit Spotlight Post</h2>
    <div class="sep"></div>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="partner_name">Partner Name</label>
            <input type="text" class="form-control" id="partner_name" name="partner_name" value="<?= htmlspecialchars($post['partner_name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="post_title">Post Title</label>
            <input type="text" class="form-control" id="post_title" name="post_title" value="<?= htmlspecialchars($post['post_title']) ?>" required>
        </div>
        <div class="form-group">
            <label for="post_type">Post Type</label>
            <select class="form-control" id="post_type" name="post_type" required>
                <option value="Story" <?= $post['post_type'] == 'Story' ? 'selected' : '' ?>>Story</option>
                <option value="PDF" <?= $post['post_type'] == 'PDF' ? 'selected' : '' ?>>PDF</option>
                <option value="Image" <?= $post['post_type'] == 'Image' ? 'selected' : '' ?>>Image</option>
                <option value="Advertisement" <?= $post['post_type'] == 'Advertisement' ? 'selected' : '' ?>>Advertisement</option>
            </select>
        </div>
        <div class="form-group">
            <label for="post_description">Post Description</label>
            <textarea id="post_description" name="post_description" class="form-control"><?= htmlspecialchars($post['post_description']) ?></textarea>
        </div>
        <div class="form-group">
            <label>Current Thumbnail</label><br>
            <img loading="lazy" src="../<?= htmlspecialchars($post['thumbnail_image']) ?>" alt="Thumbnail" style="max-width: 200px;">
        </div>
        <div class="form-group">
            <label for="thumbnail_image">Update Thumbnail (optional)</label>
            <input type="file" class="form-control-file" id="thumbnail_image" name="thumbnail_image">
        </div>
        
        <div class="form-group">
            <label>Current Document</label><br>
            <?php if (!empty($post['document'])): ?>
                <a href="../<?= htmlspecialchars($post['document']) ?>" target="_blank" class="btn btn-sm btn-info mb-2">View Existing Document</a>
            <?php else: ?>
                <p class="text-muted">No document attached.</p>
            <?php endif; ?>
            <label for="document">Update Document (Optional - PDF, DOC)</label>
            <input type="file" class="form-control-file" id="document" name="document" accept=".pdf,.doc,.docx">
        </div>

        <div class="form-group">
            <label>Current Gallery Files</label>
            <div class="current-files">
                <?php
                $files = json_decode($post['file_upload'], true) ?: [];
                foreach ($files as $file): ?>
                <div class="file-item">
                    <input type="checkbox" name="remove_files[]" value="<?= htmlspecialchars($file) ?>">
                    <a href="../<?= htmlspecialchars($file) ?>" target="_blank"><?= basename($file) ?></a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="form-group">
            <label for="file_upload">Upload New Gallery Files (optional)</label>
            <input type="file" class="form-control-file" id="file_upload" name="file_upload[]" multiple>
        </div>
        <div class="form-group">
            <label for="external_link">External Link</label>
            <input type="text" class="form-control" id="external_link" name="external_link" value="<?= htmlspecialchars($post['external_link']) ?>">
        </div>
        <button type="submit" class="btn btn-primary" name="update_spotlight">Update Post</button>
    </form>
</div>
</body>
<?php include 'sidebar.php'; ?>
</html>
