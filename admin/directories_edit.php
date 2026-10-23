<?php
include 'connection2.php';
session_start();
include 'consent.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = connect();
$org_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$org_id) {
    header('Location: directories_list.php');
    exit;
}

// Fetch existing organization
$stmt = $pdo->prepare("SELECT * FROM organizations WHERE id = :id");
$stmt->execute(['id' => $org_id]);
$organization = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$organization) {
    header('Location: directories_list.php');
    exit;
}

$error_message = '';
$success_message = '';

// --- SECURE FILE UPLOAD LOGIC ---
function handle_directory_upload($file_key, $upload_dir = "uploads/directories/") {
    if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES[$file_key]['tmp_name'];
        $original_file_name = $_FILES[$file_key]['name'];
        $sanitized_file_name = preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($original_file_name));
        $new_file_name = uniqid('', true) . '_' . $sanitized_file_name;
        
        $target_directory = __DIR__ . '/../' . $upload_dir;
        if (!is_dir($target_directory)) {
            mkdir($target_directory, 0775, true);
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_organization'])) {
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

    // Handle logo update
    $logo_url = $organization['logo_url'] ?? null;
    if (!empty($_FILES['logo']['name'])) {
        $new_logo = handle_directory_upload('logo');
        if ($new_logo) {
            if ($logo_url && file_exists(__DIR__ . '/../' . $logo_url) && !is_dir(__DIR__ . '/../' . $logo_url)) {
                @unlink(__DIR__ . '/../' . $logo_url);
            }
            $logo_url = $new_logo;
        }
    }

    // Update database
    try {
        $updateQuery = "UPDATE organizations SET
            name = :name,
            type = :type,
            sector = :sector,
            description = :description,
            email = :email,
            phone = :phone,
            website = :website,
            facebook_url = :facebook_url,
            linkedin_url = :linkedin_url,
            twitter_url = :twitter_url,
            instagram_url = :instagram_url,
            address = :address,
            city = :city,
            country = :country,
            founded_year = :founded_year,
            registration_number = :registration_number,
            contact_person_name = :contact_person_name,
            contact_person_role = :contact_person_role,
            mission = :mission,
            vision = :vision,
            services = :services,
            beneficiaries = :beneficiaries,
            partnership_interests = :partnership_interests,
            status = :status,
            is_featured = :is_featured,
            logo_url = :logo_url
            WHERE id = :id";

        $stmt = $pdo->prepare($updateQuery);
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
            'id' => $org_id
        ]);

        header('Location: directories_list.php');
        exit;
    } catch (Exception $e) {
        $error_message = 'Failed to update organization: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Organization</title>
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
    <h2 class="underline">Edit Organization</h2>
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
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($organization['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="form-group">
            <label for="type">Type <span class="text-danger">*</span></label>
            <select class="form-control" id="type" name="type" required>
                <option value="Company" <?= ($organization['type'] ?? '') == 'Company' ? 'selected' : '' ?>>Company</option>
                <option value="NGO" <?= ($organization['type'] ?? '') == 'NGO' ? 'selected' : '' ?>>NGO</option>
                <option value="Government" <?= ($organization['type'] ?? '') == 'Government' ? 'selected' : '' ?>>Government</option>
                <option value="Other" <?= ($organization['type'] ?? '') == 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>
        <div class="form-group">
            <label for="sector">Sector</label>
            <input type="text" class="form-control" id="sector" name="sector" value="<?= htmlspecialchars($organization['sector'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="4"><?= htmlspecialchars($organization['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($organization['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($organization['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="form-group">
            <label for="website">Website</label>
            <input type="url" class="form-control" id="website" name="website" value="<?= htmlspecialchars($organization['website'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="form-group">
            <label for="facebook_url">Facebook URL</label>
            <input type="url" class="form-control" id="facebook_url" name="facebook_url" value="<?= htmlspecialchars($organization['facebook_url'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="form-group">
            <label for="linkedin_url">LinkedIn URL</label>
            <input type="url" class="form-control" id="linkedin_url" name="linkedin_url" value="<?= htmlspecialchars($organization['linkedin_url'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="form-group">
            <label for="twitter_url">Twitter URL</label>
            <input type="url" class="form-control" id="twitter_url" name="twitter_url" value="<?= htmlspecialchars($organization['twitter_url'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="form-group">
            <label for="instagram_url">Instagram URL</label>
            <input type="url" class="form-control" id="instagram_url" name="instagram_url" value="<?= htmlspecialchars($organization['instagram_url'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" class="form-control"><?= htmlspecialchars($organization['address'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
        <div class="form-group">
            <label for="city">City</label>
            <input type="text" class="form-control" id="city" name="city" value="<?= htmlspecialchars($organization['city'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="form-group">
            <label for="country">Country</label>
            <input type="text" class="form-control" id="country" name="country" value="<?= htmlspecialchars($organization['country'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="form-group">
            <label for="founded_year">Founded Year</label>
            <input type="number" class="form-control" id="founded_year" name="founded_year" min="1000" max="2099" step="1" value="<?= !empty($organization['founded_year']) ? htmlspecialchars((string)$organization['founded_year'], ENT_QUOTES, 'UTF-8') : '' ?>">
        </div>
        <div class="form-group">
            <label for="registration_number">Registration Number</label>
            <input type="text" class="form-control" id="registration_number" name="registration_number" value="<?= htmlspecialchars($organization['registration_number'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="form-group">
            <label for="contact_person_name">Contact Person Name</label>
            <input type="text" class="form-control" id="contact_person_name" name="contact_person_name" value="<?= htmlspecialchars($organization['contact_person_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="form-group">
            <label for="contact_person_role">Contact Person Role</label>
            <input type="text" class="form-control" id="contact_person_role" name="contact_person_role" value="<?= htmlspecialchars($organization['contact_person_role'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="form-group">
            <label for="mission">Mission</label>
            <textarea id="mission" name="mission" class="form-control"><?= htmlspecialchars($organization['mission'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
        <div class="form-group">
            <label for="vision">Vision</label>
            <textarea id="vision" name="vision" class="form-control"><?= htmlspecialchars($organization['vision'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
        <div class="form-group">
            <label for="services">Services</label>
            <textarea id="services" name="services" class="form-control"><?= htmlspecialchars($organization['services'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
        <div class="form-group">
            <label for="beneficiaries">Beneficiaries</label>
            <textarea id="beneficiaries" name="beneficiaries" class="form-control"><?= htmlspecialchars($organization['beneficiaries'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
        <div class="form-group">
            <label for="partnership_interests">Partnership Interests</label>
            <textarea id="partnership_interests" name="partnership_interests" class="form-control"><?= htmlspecialchars($organization['partnership_interests'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
        <div class="form-group">
            <label>Current Logo</label><br>
            <?php if (!empty($organization['logo_url'])): ?>
                <img loading="lazy" src="../<?= htmlspecialchars($organization['logo_url'], ENT_QUOTES, 'UTF-8') ?>" alt="Logo" style="max-width: 200px; max-height: 120px; object-fit: contain; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">
            <?php else: ?>
                <p class="text-muted">No logo uploaded.</p>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="logo">Update Logo (optional - JPG, PNG, GIF, WebP, SVG)</label>
            <input type="file" class="form-control-file" id="logo" name="logo" accept=".jpg,.jpeg,.png,.gif,.webp,.svg">
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="Pending" <?= ($organization['status'] ?? '') == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Approved" <?= ($organization['status'] ?? '') == 'Approved' ? 'selected' : '' ?>>Approved</option>
                <option value="Rejected" <?= ($organization['status'] ?? '') == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
            </select>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" <?= !empty($organization['is_featured']) ? 'checked' : '' ?>>
            <label class="form-check-label" for="is_featured">Featured</label>
        </div>
        <button type="submit" class="btn btn-primary mt-4 mb-5" name="update_organization">Update Organization</button>
        <a href="directories_list.php" class="btn btn-secondary mt-4 mb-5 ml-2">Cancel</a>
    </form>
</div>
    </div>
</body>
</html>