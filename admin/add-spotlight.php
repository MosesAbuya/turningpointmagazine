<?php
include 'connection2.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_spotlight'])) {
    // Sanitize text inputs
    $partner_name = htmlspecialchars($_POST['partner_name']);
    $post_title = htmlspecialchars($_POST['post_title']);
    $post_type = htmlspecialchars($_POST['post_type']);
    $post_description = $_POST['post_description']; 
    $external_link = htmlspecialchars($_POST['external_link']);

    // --- SECURE FILE UPLOAD LOGIC ---
    function handle_upload($file_key, $upload_dir = "assets/uploads/", $keep_original_name = false) {
        if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
            $file_tmp_path = $_FILES[$file_key]['tmp_name'];
            $original_file_name = $_FILES[$file_key]['name'];

            $sanitized_file_name = preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($original_file_name));
            
            // Check if we should keep the original name
            if ($keep_original_name) {
                $new_file_name = $sanitized_file_name;
            } else {
                $new_file_name = uniqid('', true) . '_' . $sanitized_file_name;
            }

            $target_directory = './' . $upload_dir; 
            $dest_path = $target_directory . $new_file_name;
            $stored_path = $upload_dir . $new_file_name; 

            if (!is_dir($target_directory)) {
                mkdir($target_directory, 0775, true);
            }

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

    // --- Multiple Upload Logic (UPDATED) ---
    // Added $keep_original_name parameter
    function handle_multiple_uploads($file_key, $upload_dir = "assets/uploads/", $keep_original_name = false) {
        $uploaded_files = [];
        if (isset($_FILES[$file_key])) {
            $files = $_FILES[$file_key];
            $file_count = count($files['name']);
            $target_directory = './' . $upload_dir; 

            if (!is_dir($target_directory)) mkdir($target_directory, 0775, true);

            for ($i = 0; $i < $file_count; $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $file_tmp_path = $files['tmp_name'][$i];
                    $original_file_name = $files['name'][$i];
                    $sanitized_file_name = preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($original_file_name));
                    
                    // UPDATED LOGIC: Check if we should keep the original name
                    if ($keep_original_name) {
                        $new_file_name = $sanitized_file_name;
                    } else {
                        $new_file_name = uniqid('', true) . '_' . $sanitized_file_name;
                    }

                    $dest_path = $target_directory . $new_file_name;
                    $stored_path = $upload_dir . $new_file_name; 

                    if (move_uploaded_file($file_tmp_path, $dest_path)) {
                        $uploaded_files[] = $stored_path; 
                    }
                }
            }
        }
        return $uploaded_files;
    }

    // Process Uploads
    $thumbnail_image_target = handle_upload('thumbnail_image'); // Keep random ID for thumbnail
    
    // Document: Pass 'true' to keep original name
    $document_target = handle_upload('document', "assets/uploads/", true); 
    
    // Gallery Files: Pass 'true' to keep original name (UPDATED call)
    $uploaded_files = handle_multiple_uploads('file_upload', "assets/uploads/", true);
    
    $file_upload_json = json_encode($uploaded_files);


    // Insert into database
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
    <style>
    #body {
        background-color: #f8f9fa;
        width: calc(100% - 250px);
        margin-left: 250px;
        margin-top: 100px;
    }
    </style>
    <link rel="stylesheet" href="form.css">
</head>

<?php include 'nav.php'; ?>

<body id="body">
    <div class="container mt-5">
        <h2 class="underline">Add New Spotlight Post</h2>
        <div class="sep"></div>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="partner_name">Partner Name</label>
                <input type="text" class="form-control" id="partner_name" name="partner_name" required>
            </div>
            <div class="form-group">
                <label for="post_title">Post Title</label>
                <input type="text" class="form-control" id="post_title" name="post_title" required>
            </div>
            <div class="form-group">
                <label for="post_type">Post Type</label>
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
                <label for="thumbnail_image">Thumbnail Image (JPG, PNG, GIF)</label>
                <input type="file" class="form-control-file" id="thumbnail_image" name="thumbnail_image"
                    accept=".jpg,.jpeg,.png,.gif" required>
            </div>
            
            <div class="form-group">
                <label for="document">Attach Document (Optional - PDF, DOC)</label>
                <input type="file" class="form-control-file" id="document" name="document"
                    accept=".pdf,.doc,.docx">
            </div>

            <div class="form-group">
                <label for="file_upload">Additional Gallery Files (PDF, JPG, PNG, MP4, etc.)</label>
                <input type="file" class="form-control-file" id="file_upload" name="file_upload[]"
                    accept=".pdf,.jpg,.jpeg,.png,.mp4,.webm" multiple>
            </div>
            <div class="form-group">
                <label for="external_link">External Link (Optional)</label>
                <input type="url" class="form-control" id="external_link" name="external_link">
            </div>
            <button type="submit" class="btn btn-primary" name="add_spotlight">Add Spotlight Post</button>
        </form>
    </div>
</body>

<?php include 'sidebar.php'; ?>

</html>