<?php
include 'connection2.php';
session_start(); // Start the session

// If the session does not exist, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the edition ID from the URL
$edition_id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$edition_id) {
    header('Location: editions.php');
    exit;
}

// Create a database connection
$pdo = connect();

// Fetch the edition details
$query = "SELECT * FROM editions WHERE id = :edition_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['edition_id' => $edition_id]);
$edition = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch all ads associated with this edition
$adsQuery = "SELECT * FROM ads WHERE edition_id = :edition_id";
$adsStmt = $pdo->prepare($adsQuery);
$adsStmt->execute(['edition_id' => $edition_id]);
$ads = $adsStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle the form submission to update edition and ads
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_edition'])) {
    // Update edition details
    $edition_name = $_POST['edition_name'];
    $edition_date = $_POST['edition_date'];
    $front_page_image = $_FILES['front_page_image']['name'];
    $back_page_image = $_FILES['back_page_image']['name'];

    // File upload logic for images (simplified)
    $target_dir = "assets/uploads/";
    if (!empty($front_page_image)) {
        $front_page_image_target = $target_dir . basename($front_page_image);
        move_uploaded_file($_FILES['front_page_image']['tmp_name'], $front_page_image_target);
    } else {
        $front_page_image_target = $edition['front_page_image']; // Keep the old image if no new one is uploaded
    }

    if (!empty($back_page_image)) {
        $back_page_image_target = $target_dir . basename($back_page_image);
        move_uploaded_file($_FILES['back_page_image']['tmp_name'], $back_page_image_target);
    } else {
        $back_page_image_target = $edition['back_page_image']; // Keep the old image if no new one is uploaded
    }

    // Update the edition details in the database
    $updateEditionQuery = "UPDATE editions SET edition_name = :edition_name, date = :edition_date, 
                           front_page_image = :front_page_image, back_page_image = :back_page_image 
                           WHERE id = :edition_id";
    $stmtUpdateEdition = $pdo->prepare($updateEditionQuery);
    $stmtUpdateEdition->execute([
        'edition_name' => $edition_name,
        'edition_date' => $edition_date,
        'front_page_image' => $front_page_image_target,
        'back_page_image' => $back_page_image_target,
        'edition_id' => $edition_id
    ]);

    // Handle updating ads
    if (isset($_POST['ad_company_name']) && is_array($_POST['ad_company_name'])) {
        $ad_company_names = $_POST['ad_company_name'];
        $catch_phrases = $_POST['catch_phrase_ad'];
        $ad_images = $_FILES['ad_banner_image']['name'];

        foreach ($ad_company_names as $index => $company_name) {
            if (!empty($company_name) && !empty($catch_phrases[$index])) {
                // Handle file upload for ad images
                $ad_image_target = !empty($ad_images[$index]) ? $target_dir . basename($ad_images[$index]) : $ads[$index]['ad_banner_image'];
                if (!empty($ad_images[$index])) {
                    move_uploaded_file($_FILES['ad_banner_image']['tmp_name'][$index], $ad_image_target);
                }

                // Update the ad in the ads table
                $updateAdQuery = "UPDATE ads SET ad_company_name = :ad_company_name, ad_banner_image = :ad_banner_image, 
                                  catch_phrase = :catch_phrase WHERE id = :ad_id";
                $stmtAdUpdate = $pdo->prepare($updateAdQuery);
                $stmtAdUpdate->execute([
                    'ad_company_name' => $company_name,
                    'ad_banner_image' => $ad_image_target,
                    'catch_phrase' => $catch_phrases[$index],
                    'ad_id' => $ads[$index]['id']
                ]);
            }
        }
    }

    // Handle adding new ads
    if (isset($_POST['new_ad_company_name']) && is_array($_POST['new_ad_company_name'])) {
        $new_ad_company_names = $_POST['new_ad_company_name'];
        $new_catch_phrases = $_POST['new_catch_phrase_ad'];
        $new_ad_images = $_FILES['new_ad_banner_image']['name'];

        foreach ($new_ad_company_names as $index => $company_name) {
            if (!empty($company_name) && !empty($new_catch_phrases[$index])) {
                // Handle file upload for new ad images
                $new_ad_image_target = $target_dir . basename($new_ad_images[$index]);
                move_uploaded_file($_FILES['new_ad_banner_image']['tmp_name'][$index], $new_ad_image_target);

                // Insert the new ad into the ads table
                $insertNewAdQuery = "INSERT INTO ads (edition_id, ad_company_name, ad_banner_image, catch_phrase) 
                                     VALUES (:edition_id, :ad_company_name, :ad_banner_image, :catch_phrase)";
                $stmtInsertNewAd = $pdo->prepare($insertNewAdQuery);
                $stmtInsertNewAd->execute([
                    'edition_id' => $edition_id,
                    'ad_company_name' => $company_name,
                    'ad_banner_image' => $new_ad_image_target,
                    'catch_phrase' => $new_catch_phrases[$index]
                ]);
            }
        }
    }

    // Redirect back to editions list after updating
    header('Location: editions.php');
    exit;
}

// Close the connection
closeConnection($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Edition</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    #body {
        background-color: #f8f9fa;
        width: calc(100% - 250px);
        margin-left: 250px;
        margin-top: 100px;
    }

    small {
        margin-top: 10px;
        margin-bottom: 10px;
        padding: 5px;

        img {
            margin: 10px;
            border: 2px solid black;
        }
    }

    .form-control-file {
        margin-top: 10px;
    }
    </style>
    <link rel="stylesheet" href="form.css">
</head>

<body id="body">

    <?php include 'nav.php'; ?>

    <div class="container mt-5">
        <h2 class="underline">Edit Edition: <?= htmlspecialchars($edition['edition_name']) ?> (<?= $edition['date'] ?>)
        </h2>
        <div class="sep"></div>
        <!-- Edit Edition Form -->
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="edition_name">Edition Name</label>
                <input type="text" class="form-control" id="edition_name" name="edition_name"
                    value="<?= htmlspecialchars($edition['edition_name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="edition_date">Edition Date</label>
                <input type="date" class="form-control" id="edition_date" name="edition_date"
                    value="<?= $edition['date'] ?>" required>
            </div>
            <div class="form-group">
                <label for="front_page_image">Front Page Image</label>
                <input type="file" class="form-control-file" id="front_page_image" name="front_page_image">
                <small>Current: <img loading="lazy" src="<?= $edition['front_page_image'] ?>" width="100"
                        alt="Front Page Image"></small>
            </div>
            <div class="form-group">
                <label for="back_page_image">Back Page Image</label>
                <input type="file" class="form-control-file" id="back_page_image" name="back_page_image">
                <small>Current: <img loading="lazy" src="<?= $edition['back_page_image'] ?>" width="100" alt="Back Page Image"></small>
            </div>

            <!-- Ads Section -->
            <div id="adsSection">
                <?php foreach ($ads as $index => $ad): ?>
                <div class="ad-container">
                    <h4>Ad <?= $index + 1 ?></h4>
                    <div class="form-group">
                        <label for="ad_company_name[]">Ad Company Name</label>
                        <input type="text" class="form-control" name="ad_company_name[]"
                            value="<?= htmlspecialchars($ad['ad_company_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="catch_phrase_ad[]">Ad Catch Phrase</label>
                        <input type="text" class="form-control" name="catch_phrase_ad[]"
                            value="<?= htmlspecialchars($ad['catch_phrase']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="ad_banner_image[]">Ad Banner Image</label>
                        <input type="file" class="form-control-file" name="ad_banner_image[]">
                        <small>Current: <img loading="lazy" src="<?= $ad['ad_banner_image'] ?>" width="100"
                                alt="Ad Banner Image"></small>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- New Ads Section (for adding new ads) -->
            <div id="newAdsSection"></div>

            <button type="button" id="addAdButton" class="btn btn-secondary">Add Another Ad</button>
            <br><br>
            <button type="submit" class="btn btn-primary" name="update_edition">Update Edition</button>
        </form>
    </div>

    <script>
    document.getElementById("addAdButton").addEventListener("click", function() {
        var adSection = document.getElementById("newAdsSection");
        var adCount = adSection.getElementsByClassName("ad-container").length + 1;

        var newAd = document.createElement("div");
        newAd.classList.add("ad-container");
        newAd.innerHTML = `
            <h4>Ad ${adCount}</h4>
            <div class="form-group">
                <label for="new_ad_company_name[]">Ad Company Name</label>
                <input type="text" class="form-control" name="new_ad_company_name[]" required>
            </div>
            <div class="form-group">
                <label for="new_catch_phrase_ad[]">Ad Catch Phrase</label>
                <input type="text" class="form-control" name="new_catch_phrase_ad[]" required>
            </div>
            <div class="form-group">
                <label for="new_ad_banner_image[]">Ad Banner Image</label>
                <input type="file" class="form-control-file" name="new_ad_banner_image[]" required>
            </div>
        `;
        adSection.appendChild(newAd);
    });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
<?php include 'sidebar.php'; ?>


</html>
