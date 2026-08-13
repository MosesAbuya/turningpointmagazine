<?php
include 'connection2.php';
session_start(); // Start the session

// If the session does not exist, redirect to login page

include 'consent.php';
// Get the edition ID from the URL, if available
$edition_id = isset($_GET['id']) ? $_GET['id'] : null;

// Create a database connection
$pdo = connect();

// Handle adding a new edition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_edition'])) {
    // Get the form data
    $edition_name = $_POST['edition_name'];
    $edition_date = $_POST['edition_date'];
    $front_page_image = $_FILES['front_page_image']['name'];
    $back_page_image = $_FILES['back_page_image']['name'];
    $prev_price = $_POST['prev_price']; // Get previous price
    $current_price = $_POST['current_price']; // Get current price


    // File upload logic (simplified for now)
    $target_dir = "assets/uploads/";
    $front_page_image_target = $target_dir . basename($front_page_image);
    $back_page_image_target = $target_dir . basename($back_page_image);
    move_uploaded_file($_FILES['front_page_image']['tmp_name'], $front_page_image_target);
    move_uploaded_file($_FILES['back_page_image']['tmp_name'], $back_page_image_target);

    // Insert the new edition into the editions table (without catch phrase)
    $insertQuery = "INSERT INTO editions (edition_name, date, front_page_image, back_page_image) 
                    VALUES (:edition_name, :edition_date, :front_page_image, :back_page_image)";
    $stmtInsert = $pdo->prepare($insertQuery);
    $stmtInsert->execute([
        'edition_name' => $edition_name,
        'edition_date' => $edition_date,
        'front_page_image' => $front_page_image_target,
        'back_page_image' => $back_page_image_target,
    ]);

    // Get the last inserted edition ID
    $edition_id = $pdo->lastInsertId();

    // Handle multiple ads
    if (isset($_POST['ad_company_name']) && is_array($_POST['ad_company_name'])) {
        $ad_company_names = $_POST['ad_company_name'];
        $catch_phrases = $_POST['catch_phrase_ad'];
        $ad_images = $_FILES['ad_banner_image']['name'];

        foreach ($ad_company_names as $index => $company_name) {
            if (!empty($company_name) && !empty($catch_phrases[$index])) {
                // Handle file upload for ad images
                $ad_image_target = $target_dir . basename($ad_images[$index]);
                move_uploaded_file($_FILES['ad_banner_image']['tmp_name'][$index], $ad_image_target);

                // Insert ad into the ads table
                $insertAdQuery = "INSERT INTO ads (edition_id, ad_company_name, ad_banner_image, catch_phrase) 
                                  VALUES (:edition_id, :ad_company_name, :ad_banner_image, :catch_phrase)";
                $stmtAdInsert = $pdo->prepare($insertAdQuery);
                $stmtAdInsert->execute([
                    'edition_id' => $edition_id,
                    'ad_company_name' => $company_name,
                    'ad_banner_image' => $ad_image_target,
                    'catch_phrase' => $catch_phrases[$index],
                ]);
            }
        }
    }

    // Create product code (example: ED-YYYYMMDD-ID)
    $edition_date_formatted = date("Ymd", strtotime($edition_date));
    $product_code = "ED-" . $edition_date_formatted . "-" . $edition_id;

    // Insert the new edition as a product into the products table
    $insertProductQuery = "INSERT INTO products (edition_id, code, name, description, prev_price, current_price, img_path) 
                           VALUES (:edition_id, :code, :name, :description, :prev_price, :current_price, :img_path)";

    $stmtProductInsert = $pdo->prepare($insertProductQuery);
    $stmtProductInsert->execute([
        'edition_id' => $edition_id,
        'code' => $product_code,
        'name' => $edition_name,
        'description' => 'Edition ' . $edition_name . ' released on ' . $edition_date,
        'prev_price' => $prev_price,
        'current_price' => $current_price,
        'img_path' => $front_page_image_target, // Use front page as product image
    ]);


    // Redirect back to editions list after insertion
    header('Location: editions.php');
    exit;
}

// Fetch the edition data if editing an existing edition
if ($edition_id) {
    // Fetch the edition details
    $query = "SELECT * FROM editions WHERE id = :edition_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['edition_id' => $edition_id]);
    $edition = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Close the connection
closeConnection($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edition Management</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    .ad-container {
        margin-bottom: 20px;
    }

    #body {
        background-color: #f8f9fa;
        width: calc(100% - 250px);
        margin-left: 250px;
        margin-top: 100px;
    }

    .underline {

        font-size: 3rem;
        font-weight: 700;
        color: red;
    }

    .sep {
        width: 100%;
        height: 2px;
        border: 2px solid black;
        margin-bottom: 50px;
    }

    form {
        margin-top: 50px;
        width: 50%;
        padding: 10px;
        border: 1px solid #ccc;

        .form-group {

            margin: 20px;

            label {
                display: block;
                font-size: 15px;
                color: grey;
            }

            input {
                width: 100%;
            }
        }

        #adsSection {
            margin-top: 100px;

            .ad-container {
                margin-bottom: 20px;

                h4 {
                    font-size: 24px;
                    font-weight: 700;
                    color: Red;
                    margin: 20px;
                }

                .form-group {
                    margin: 20px;

                    label {
                        font-size: 15px;
                        color: grey;
                    }

                    input {
                        width: 100%;

                    }
                }
            }
        }

        #i-btn {
            margin-top: 50px;
        }

        button {

            margin-left: 20px;
            padding: 5px;
            align-self: center;
            font-size: small;
        }
    }
    </style>
    <link rel="stylesheet" href="form.css">
</head>

<body id="body">

    <?php include 'nav.php'; ?>

    <div class="container mt-5">
        <?php if ($edition_id): ?>
        <h2 class="underline">Edit Edition: <?= htmlspecialchars($edition['edition_name']) ?> (<?= $edition['date'] ?>)
        </h2>
        <?php else: ?>
        <h2 class="underline">Add New Edition</h2>
        <?php endif; ?>
        <div class="sep"></div>

        <!-- Add New Edition Form (or Edit Edition Form) -->
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="edition_name">Edition Name</label>
                <input type="text" class="form-control" id="edition_name" name="edition_name" required>
            </div>
            <div class="form-group">
                <label for="edition_date">Edition Date</label>
                <input type="date" class="form-control" id="edition_date" name="edition_date" required>
            </div>
            <div class="form-group">
                <label for="front_page_image">Front Page Image</label>
                <input type="file" class="form-control-file" id="front_page_image" name="front_page_image" required>
            </div>
            <div class="form-group">
                <label for="back_page_image">Back Page Image</label>
                <input type="file" class="form-control-file" id="back_page_image" name="back_page_image" required>
            </div>
            <div class="form-group">
                <label for="prev_price">Previous Price</label>
                <input type="number" step="0.01" class="form-control" id="prev_price" name="prev_price" required>
            </div>
            <div class="form-group">
                <label for="current_price">Current Price</label>
                <input type="number" step="0.01" class="form-control" id="current_price" name="current_price" required>
            </div>

            <hr>

            <!-- Ads Section -->
            <div id="adsSection">
                <div class="ad-container">
                    <h4>Advertisement 1</h4>
                    <div class="form-group">
                        <label for="ad_company_name[]">Ad Company Name</label>
                        <input type="text" class="form-control" name="ad_company_name[]" required>
                    </div>
                    <div class="form-group">
                        <label for="catch_phrase_ad[]">Ad Catch Phrase</label>
                        <input type="text" class="form-control" name="catch_phrase_ad[]" required>
                    </div>
                    <div class="form-group">
                        <label for="ad_banner_image[]">Ad Banner Image</label>
                        <input type="file" class="form-control-file" name="ad_banner_image[]" required>
                    </div>
                </div>
            </div>
            <button type="button" id="addAdButton" class="btn btn-secondary">Add Another Ad</button>
            <br><br>
            <hr>
            <button type="submit" class="btn btn-primary" id="i-btn" name="add_edition">Save Edition</button>
        </form>
    </div>

    <script>
    document.getElementById("addAdButton").addEventListener("click", function() {
        var adSection = document.getElementById("adsSection");
        var adCount = adSection.getElementsByClassName("ad-container").length + 1;

        var newAd = document.createElement("div");
        newAd.classList.add("ad-container");
        newAd.innerHTML = `
            <h4>Advertisement ${adCount}</h4>
            <div class="form-group">
                <label for="ad_company_name[]">Ad Company Name</label>
                <input type="text" class="form-control" name="ad_company_name[]" required>
            </div>
            <div class="form-group">
                <label for="catch_phrase_ad[]">Ad Catch Phrase</label>
                <input type="text" class="form-control" name="catch_phrase_ad[]" required>
            </div>
            <div class="form-group">
                <label for="ad_banner_image[]">Ad Banner Image</label>
                <input type="file" class="form-control-file" name="ad_banner_image[]" required>
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
