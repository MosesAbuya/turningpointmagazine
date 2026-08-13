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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_organization'])) {
    // Sanitize text inputs
    $name = htmlspecialchars($_POST['name']);
    $type = htmlspecialchars($_POST['type']);
    $sector = htmlspecialchars($_POST['sector']);
    $description = htmlspecialchars($_POST['description']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $website = htmlspecialchars($_POST['website']);
    $facebook_url = htmlspecialchars($_POST['facebook_url']);
    $linkedin_url = htmlspecialchars($_POST['linkedin_url']);
    $twitter_url = htmlspecialchars($_POST['twitter_url']);
    $instagram_url = htmlspecialchars($_POST['instagram_url']);
    $address = htmlspecialchars($_POST['address']);
    $city = htmlspecialchars($_POST['city']);
    $country = htmlspecialchars($_POST['country']);
    $founded_year = htmlspecialchars($_POST['founded_year']);
    $registration_number = htmlspecialchars($_POST['registration_number']);
    $contact_person_name = htmlspecialchars($_POST['contact_person_name']);
    $contact_person_role = htmlspecialchars($_POST['contact_person_role']);
    $mission = htmlspecialchars($_POST['mission']);
    $vision = htmlspecialchars($_POST['vision']);
    $services = htmlspecialchars($_POST['services']);
    $beneficiaries = htmlspecialchars($_POST['beneficiaries']);
    $partnership_interests = htmlspecialchars($_POST['partnership_interests']);
    $status = htmlspecialchars($_POST['status']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    // --- SECURE FILE UPLOAD LOGIC ---
    function handle_upload($file_key, $upload_dir = "uploads/directories/") {
        if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
            $file_tmp_path = $_FILES[$file_key]['tmp_name'];
            $original_file_name = $_FILES[$file_key]['name'];
            $sanitized_file_name = preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($original_file_name));
            $new_file_name = uniqid('', true) . '_' . $sanitized_file_name;
            $dest_path = '../' . $upload_dir . $new_file_name;
            $stored_path = $upload_dir . $new_file_name;

            if (move_uploaded_file($file_tmp_path, $dest_path)) {
                return $stored_path;
            }
        }
        return null;
    }

    // Handle logo update
    $logo_url = $organization['logo_url'];
    if (!empty($_FILES['logo']['name'])) {
        $new_logo = handle_upload('logo');
        if ($new_logo) {
            if ($logo_url && file_exists('../' . $logo_url)) {
                unlink('../' . $logo_url);
            }
            $logo_url = $new_logo;
        }
    }

    // Update database
    $updateQuery = "UPDATE organizations SET
        name = :name, type = :type, sector = :sector, description = :description, email = :email, phone = :phone, website = :website, facebook_url = :facebook_url, linkedin_url = :linkedin_url, twitter_url = :twitter_url, instagram_url = :instagram_url, address = :address, city = :city, country = :country, founded_year = :founded_year, registration_number = :registration_number, contact_person_name = :contact_person_name, contact_person_role = :contact_person_role, mission = :mission, vision = :vision, services = :services, beneficiaries = :beneficiaries, partnership_interests = :partnership_interests, status = :status, is_featured = :is_featured, logo_url = :logo_url
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Organization</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="form.css">
    <style>
    #body { background-color: #f8f9fa; width: calc(100% - 250px); margin-left: 250px; margin-top: 100px; }
    </style>
</head>

<?php include 'nav.php'; ?>

<body id="body">
<div class="container mt-5">
    <h2 class="underline">Edit Organization</h2>
    <div class="sep"></div>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Organization Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($organization['name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="type">Type</label>
            <select class="form-control" id="type" name="type" required>
                <option value="Company" <?= $organization['type'] == 'Company' ? 'selected' : '' ?>>Company</option>
                <option value="NGO" <?= $organization['type'] == 'NGO' ? 'selected' : '' ?>>NGO</option>
                <option value="Government" <?= $organization['type'] == 'Government' ? 'selected' : '' ?>>Government</option>
                <option value="Other" <?= $organization['type'] == 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>
        <div class="form-group">
            <label for="sector">Sector</label>
            <input type="text" class="form-control" id="sector" name="sector" value="<?= htmlspecialchars($organization['sector']) ?>">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control"><?= htmlspecialchars($organization['description']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($organization['email']) ?>">
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($organization['phone']) ?>">
        </div>
        <div class="form-group">
            <label for="website">Website</label>
            <input type="url" class="form-control" id="website" name="website" value="<?= htmlspecialchars($organization['website']) ?>">
        </div>
        <div class="form-group">
            <label for="facebook_url">Facebook URL</label>
            <input type="url" class="form-control" id="facebook_url" name="facebook_url" value="<?= htmlspecialchars($organization['facebook_url']) ?>">
        </div>
        <div class="form-group">
            <label for="linkedin_url">LinkedIn URL</label>
            <input type="url" class="form-control" id="linkedin_url" name="linkedin_url" value="<?= htmlspecialchars($organization['linkedin_url']) ?>">
        </div>
        <div class="form-group">
            <label for="twitter_url">Twitter URL</label>
            <input type="url" class="form-control" id="twitter_url" name="twitter_url" value="<?= htmlspecialchars($organization['twitter_url']) ?>">
        </div>
        <div class="form-group">
            <label for="instagram_url">Instagram URL</label>
            <input type="url" class="form-control" id="instagram_url" name="instagram_url" value="<?= htmlspecialchars($organization['instagram_url']) ?>">
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" class="form-control"><?= htmlspecialchars($organization['address']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="city">City</label>
            <input type="text" class="form-control" id="city" name="city" value="<?= htmlspecialchars($organization['city']) ?>">
        </div>
        <div class="form-group">
            <label for="country">Country</label>
            <input type="text" class="form-control" id="country" name="country" value="<?= htmlspecialchars($organization['country']) ?>">
        </div>
        <div class="form-group">
            <label for="founded_year">Founded Year</label>
            <input type="number" class="form-control" id="founded_year" name="founded_year" min="1000" max="2099" step="1" value="<?= htmlspecialchars($organization['founded_year']) ?>">
        </div>
        <div class="form-group">
            <label for="registration_number">Registration Number</label>
            <input type="text" class="form-control" id="registration_number" name="registration_number" value="<?= htmlspecialchars($organization['registration_number']) ?>">
        </div>
        <div class="form-group">
            <label for="contact_person_name">Contact Person Name</label>
            <input type="text" class="form-control" id="contact_person_name" name="contact_person_name" value="<?= htmlspecialchars($organization['contact_person_name']) ?>">
        </div>
        <div class="form-group">
            <label for="contact_person_role">Contact Person Role</label>
            <input type="text" class="form-control" id="contact_person_role" name="contact_person_role" value="<?= htmlspecialchars($organization['contact_person_role']) ?>">
        </div>
        <div class="form-group">
            <label for="mission">Mission</label>
            <textarea id="mission" name="mission" class="form-control"><?= htmlspecialchars($organization['mission']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="vision">Vision</label>
            <textarea id="vision" name="vision" class="form-control"><?= htmlspecialchars($organization['vision']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="services">Services</label>
            <textarea id="services" name="services" class="form-control"><?= htmlspecialchars($organization['services']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="beneficiaries">Beneficiaries</label>
            <textarea id="beneficiaries" name="beneficiaries" class="form-control"><?= htmlspecialchars($organization['beneficiaries']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="partnership_interests">Partnership Interests</label>
            <textarea id="partnership_interests" name="partnership_interests" class="form-control"><?= htmlspecialchars($organization['partnership_interests']) ?></textarea>
        </div>
        <div class="form-group">
            <label>Current Logo</label><br>
            <?php if (!empty($organization['logo_url'])): ?>
                <img loading="lazy" src="../<?= htmlspecialchars($organization['logo_url']) ?>" alt="Logo" style="max-width: 200px;">
            <?php else: ?>
                <p>No logo uploaded.</p>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="logo">Update Logo (optional)</label>
            <input type="file" class="form-control-file" id="logo" name="logo">
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="Pending" <?= $organization['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Approved" <?= $organization['status'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
                <option value="Rejected" <?= $organization['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
            </select>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" <?= $organization['is_featured'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="is_featured">Featured</label>
        </div>
        <button type="submit" class="btn btn-primary mt-3" name="update_organization">Update Organization</button>
    </form>
</div>
</body>
<?php include 'sidebar.php'; ?>
</html>
