<?php
// Include the connection file
include('connection2.php');

// Check if 'edition_id' and 'offset' are set in the URL
if (isset($_GET['edition_id']) && isset($_GET['offset'])) {
    $edition_id = $_GET['edition_id'];
    $offset = $_GET['offset'];  // The number of ads already loaded

    // Establish database connection
    $pdo = connect();

    // Prepare SQL to get more ads based on edition_id and offset
    $adsQuery = "
        SELECT ad_banner_image, ad_company_name, catch_phrase
        FROM ads
        WHERE edition_id = :edition_id
        LIMIT 3 OFFSET :offset
    ";
    $stmtAds = $pdo->prepare($adsQuery);
    $stmtAds->bindParam(':edition_id', $edition_id, PDO::PARAM_INT);
    $stmtAds->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmtAds->execute();

    $adsList = $stmtAds->fetchAll(PDO::FETCH_ASSOC);

    // If ads are found, return them as JSON
    if ($adsList) {
        echo json_encode($adsList);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
?>