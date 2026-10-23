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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_organization'])) {
    $name = trim($_POST['name'] ?? '');
    $type = trim($_POST['type'] ?? 'Other');
    $sector = trim($_POST['sector'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $website = trim($_POST['website'] ?? '');
    $facebook_url = trim($_POST['facebook_url'] ?? '');
    $linkedin_url = trim($_POST['linkedin_url'] ?? '');
    $twitter_url = trim($_POST['twitter_url'] ?? '');
    $instagram_url = trim($_POST['instagram_url'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $country = trim($_POST['country'] ?? '');
    
    // Founded year must be integer or NULL for MySQL year(4) column
    $founded_year = (!empty($_POST['founded_year']) && is_numeric($_POST['founded_year'])) ? (int)$_POST['founded_year'] : null;

    $registration_number = trim($_POST['registration_number'] ?? '');
    $contact_person_name = trim($_POST['contact_person_name'] ?? '');
    $contact_person_role = trim($_POST['contact_person_role'] ?? '');
    $mission = trim($_POST['mission'] ?? '');
    $vision = trim($_POST['vision'] ?? '');
    $services = trim($_POST['services'] ?? '');
    $beneficiaries = trim($_POST['beneficiaries'] ?? '');
    $partnership_interests = trim($_POST['partnership_interests'] ?? '');
    
    $allowed_statuses = ['Pending', 'Approved', 'Rejected'];
    $status = in_array($_POST['status'] ?? '', $allowed_statuses) ? $_POST['status'] : 'Pending';

    $allowed_types = ['Company', 'NGO', 'Government', 'Other'];
    if (!in_array($type, $allowed_types)) {
        $type = 'Other';
    }

    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    // --- SECURE FILE UPLOAD LOGIC ---
    function handle_upload($file_key, $upload_dir = "uploads/directories/") {
        if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
            $file_tmp_path = $_FILES[$file_key]['tmp_name'];
            $original_file_name = $_FILES[$file_key]['name'];

            $sanitized_file_name = preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($original_file_name));
            $new_file_name = uniqid('', true) . '_' . $sanitized_file_name;

            $target_directory = __DIR__ . '/../' . $upload_dir;
            if (!is_dir($target_directory)) {
                if (!mkdir($target_directory, 0775, true)) {
                    error_log("Failed to create upload directory: " . $target_directory);
                    return null;
                }
            }

            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
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

    $logo_url = handle_upload('logo');

    // Insert new organization into the database
    try {
        $insertQuery = "INSERT INTO organizations
            (name, type, sector, description, email, phone, website, facebook_url, linkedin_url, twitter_url, instagram_url, address, city, country, founded_year, registration_number, contact_person_name, contact_person_role, mission, vision, services, beneficiaries, partnership_interests, status, is_featured, logo_url)
            VALUES (:name, :type, :sector, :description, :email, :phone, :website, :facebook_url, :linkedin_url, :twitter_url, :instagram_url, :address, :city, :country, :founded_year, :registration_number, :contact_person_name, :contact_person_role, :mission, :vision, :services, :beneficiaries, :partnership_interests, :status, :is_featured, :logo_url)";

        $stmt = $pdo->prepare($insertQuery);
        $stmt->execute([
            'name' => $name,
            'type' => $type,
            'sector' => $sector,
            'description' => $description,
            'email' => $email,
            'phone' => $phone,
            'website' => $website,
            'facebook_url' => $facebook_url,
            'linkedin_url' => $linkedin_url,
            'twitter_url' => $twitter_url,
            'instagram_url' => $instagram_url,
            'address' => $address,
            'city' => $city,
            'country' => $country,
            'founded_year' => $founded_year,
            'registration_number' => $registration_number,
            'contact_person_name' => $contact_person_name,
            'contact_person_role' => $contact_person_role,
            'mission' => $mission,
            'vision' => $vision,
            'services' => $services,
            'beneficiaries' => $beneficiaries,
            'partnership_interests' => $partnership_interests,
            'status' => $status,
            'is_featured' => $is_featured,
            'logo_url' => $logo_url,
        ]);

        header('Location: directories_list.php');
        exit;
    } catch (Exception $e) {
        $error_message = 'Failed to add organization: ' . $e->getMessage();
    }
}

closeConnection($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Organization</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        <h2 class="underline">Add New Organization</h2>
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
                <label for="name">Organization Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="type">Type <span class="text-danger">*</span></label>
                <select class="form-control" id="type" name="type" required>
                    <option value="Company">Company</option>
                    <option value="NGO">NGO</option>
                    <option value="Government">Government</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="sector">Sector</label>
                <input type="text" class="form-control" id="sector" name="sector">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone">
            </div>
            <div class="form-group">
                <label for="website">Website</label>
                <input type="url" class="form-control" id="website" name="website" placeholder="https://example.com">
            </div>
            <div class="form-group">
                <label for="facebook_url">Facebook URL</label>
                <input type="url" class="form-control" id="facebook_url" name="facebook_url" placeholder="https://facebook.com/...">
            </div>
            <div class="form-group">
                <label for="linkedin_url">LinkedIn URL</label>
                <input type="url" class="form-control" id="linkedin_url" name="linkedin_url" placeholder="https://linkedin.com/in/...">
            </div>
            <div class="form-group">
                <label for="twitter_url">Twitter URL</label>
                <input type="url" class="form-control" id="twitter_url" name="twitter_url" placeholder="https://twitter.com/...">
            </div>
            <div class="form-group">
                <label for="instagram_url">Instagram URL</label>
                <input type="url" class="form-control" id="instagram_url" name="instagram_url" placeholder="https://instagram.com/...">
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" class="form-control" id="city" name="city">
            </div>
            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" class="form-control" id="country" name="country">
            </div>
            <div class="form-group">
                <label for="founded_year">Founded Year</label>
                <input type="number" class="form-control" id="founded_year" name="founded_year" min="1000" max="2099" step="1" placeholder="e.g. 2020">
            </div>
            <div class="form-group">
                <label for="registration_number">Registration Number</label>
                <input type="text" class="form-control" id="registration_number" name="registration_number">
            </div>
            <div class="form-group">
                <label for="contact_person_name">Contact Person Name</label>
                <input type="text" class="form-control" id="contact_person_name" name="contact_person_name">
            </div>
            <div class="form-group">
                <label for="contact_person_role">Contact Person Role</label>
                <input type="text" class="form-control" id="contact_person_role" name="contact_person_role">
            </div>
            <div class="form-group">
                <label for="mission">Mission</label>
                <textarea id="mission" name="mission" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="vision">Vision</label>
                <textarea id="vision" name="vision" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="services">Services</label>
                <textarea id="services" name="services" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="beneficiaries">Beneficiaries</label>
                <textarea id="beneficiaries" name="beneficiaries" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="partnership_interests">Partnership Interests</label>
                <textarea id="partnership_interests" name="partnership_interests" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="logo">Logo (JPG, PNG, GIF, WebP, SVG)</label>
                <input type="file" class="form-control-file" id="logo" name="logo" accept=".jpg,.jpeg,.png,.gif,.webp,.svg">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Pending" selected>Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1">
                <label class="form-check-label" for="is_featured">Featured</label>
            </div>
            <button type="submit" class="btn btn-primary mt-4 mb-5" name="add_organization">Add Organization</button>
            <a href="directories_list.php" class="btn btn-secondary mt-4 mb-5 ml-2">Cancel</a>
        </form>
    </div>
    </div>
</body>
</html>