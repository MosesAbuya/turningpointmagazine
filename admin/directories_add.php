<?php
include 'connection2.php';
session_start();
include 'consent.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_organization'])) {
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

            // Sanitize filename
            $sanitized_file_name = preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($original_file_name));
            $new_file_name = uniqid('', true) . '_' . $sanitized_file_name;

            $target_directory = '../' . $upload_dir;
            $dest_path = $target_directory . $new_file_name;
            $stored_path = $upload_dir . $new_file_name;

            if (!is_dir($target_directory)) {
                if (!mkdir($target_directory, 0775, true)) {
                    error_log("Failed to create upload directory: " . $target_directory);
                    return null;
                }
            }

            if (!is_writable($target_directory)) {
                error_log("Upload directory is not writable: " . $target_directory);
                return null;
            }

            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            $file_extension = strtolower(pathinfo($dest_path, PATHINFO_EXTENSION));

            if (in_array($file_extension, $allowed_extensions)) {
                if (move_uploaded_file($file_tmp_path, $dest_path)) {
                    return $stored_path;
                } else {
                    error_log("Failed to move uploaded file to: " . $dest_path);
                    return null;
                }
            }
        } else if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] !== UPLOAD_ERR_NO_FILE) {
            error_log("File upload error for key " . $file_key . ": " . $_FILES[$file_key]['error']);
        }
        return null;
    }

    $logo_url = handle_upload('logo');

    // Insert new organization into the database
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
    <style>
    #body {
        background-color: #f8f9fa;
        width: calc(100% - 250px);
        margin-left: 250px;
        margin-top: 100px;
    }
    </style>
</head>

<?php include 'nav.php'; ?>

<body id="body">
    <div class="container mt-5">
        <h2 class="underline">Add New Organization</h2>
        <div class="sep"></div>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Organization Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="type">Type</label>
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
                <textarea id="description" name="description" class="form-control"></textarea>
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
                <input type="url" class="form-control" id="website" name="website">
            </div>
            <div class="form-group">
                <label for="facebook_url">Facebook URL</label>
                <input type="url" class="form-control" id="facebook_url" name="facebook_url">
            </div>
            <div class="form-group">
                <label for="linkedin_url">LinkedIn URL</label>
                <input type="url" class="form-control" id="linkedin_url" name="linkedin_url">
            </div>
            <div class="form-group">
                <label for="twitter_url">Twitter URL</label>
                <input type="url" class="form-control" id="twitter_url" name="twitter_url">
            </div>
            <div class="form-group">
                <label for="instagram_url">Instagram URL</label>
                <input type="url" class="form-control" id="instagram_url" name="instagram_url">
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
                <input type="number" class="form-control" id="founded_year" name="founded_year" min="1000" max="2099" step="1">
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
                <label for="logo">Logo (JPG, PNG, GIF)</label>
                <input type="file" class="form-control-file" id="logo" name="logo" accept=".jpg,.jpeg,.png,.gif">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1">
                <label class="form-check-label" for="is_featured">Featured</label>
            </div>
            <button type="submit" class="btn btn-primary mt-3" name="add_organization">Add Organization</button>
        </form>
    </div>
</body>

<?php include 'sidebar.php'; ?>

</html>