<?php
include('connection2.php');
$pdo = connect();

// --- 1. GET ALL PARAMS ---
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$type_filter = isset($_GET['type']) ? $_GET['type'] : '';
$sector_filter = isset($_GET['sector']) ? $_GET['sector'] : '';
$country_filter = isset($_GET['country']) ? $_GET['country'] : '';
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// --- 2. BUILD SQL AND PARAMS ---
$sql = "SELECT * FROM organizations WHERE status = 'Approved'";
$params = [];

if (!empty($search_query)) {
    $sql .= " AND (name LIKE :search_name OR description LIKE :search_desc)";
    $params[':search_name'] = '%' . $search_query . '%';
    $params[':search_desc'] = '%' . $search_query . '%';
}
if (!empty($type_filter)) {
    $sql .= " AND type = :type";
    $params[':type'] = $type_filter;
}
if (!empty($sector_filter)) {
    $sql .= " AND sector = :sector";
    $params[':sector'] = $sector_filter;
}
if (!empty($country_filter)) {
    $sql .= " AND country = :country";
    $params[':country'] = $country_filter;
}

// --- 3. PAGINATION LOGIC ---
$posts_per_page = 12;
$offset = ($page - 1) * $posts_per_page;

$count_sql = str_replace("SELECT *", "SELECT COUNT(*)", $sql);
$total_stmt = $pdo->prepare($count_sql);
foreach ($params as $key => $val) {
    $total_stmt->bindValue($key, $val);
}
$total_stmt->execute();
$total_organizations = $total_stmt->fetchColumn();
$total_pages = ceil($total_organizations / $posts_per_page);

// --- 4. GET ORGANIZATIONS ---
$sql .= " ORDER BY is_featured DESC, name ASC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':limit', (int)$posts_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
$stmt->execute();
$organizations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- 5. BUILD HTML RESPONSE ---
$html = '';
if (empty($organizations)) {
    $html = '<div style="grid-column: 1 / -1; text-align: center; padding: 50px; color: #666;">
                <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 20px; opacity: 0.5;"></i>
                <h3>No organizations found.</h3>
                <p>Try adjusting your filters or search criteria.</p>
            </div>';
} else {
    foreach ($organizations as $org) {
        $html .= '<a href="directory_detail.php?id=' . $org['id'] . '" class="directory-card">'; // Note: animation class added by JS
        $html .= '<div class="logo-wrapper">';
        if (!empty($org['logo_url'])) {
            $html .= '<img src="' . htmlspecialchars($org['logo_url']) . '" alt="' . htmlspecialchars($org['name']) . ' Logo" class="logo" loading="lazy">';
        } else {
            $html .= '<i class="fas fa-building" style="font-size: 4rem; color: #eee;"></i>';
        }
        $html .= '</div>';
        $html .= '<div class="card-content">';
        $html .= '<div>';
        $html .= '<h3>' . htmlspecialchars($org['name']) . '</h3>';
        if (!empty($org['type'])) {
            $html .= '<p><i class="fas fa-tag" style="width: 20px; color:#ff0000;"></i> ' . htmlspecialchars($org['type']) . '</p>';
        }
        if (!empty($org['sector'])) {
            $html .= '<p><i class="fas fa-briefcase" style="width: 20px; color:#ff0000;"></i> ' . htmlspecialchars($org['sector']) . '</p>';
        }
        if (!empty($org['country'])) {
            $html .= '<p><i class="fas fa-map-marker-alt" style="width: 20px; color:#ff0000;"></i> ' . htmlspecialchars($org['country']) . '</p>';
        }
        $html .= '<div class="description">' . substr(strip_tags($org['description']), 0, 150) . '...</div>';
        $html .= '</div>';
        $html .= '<span class="details-link">View Details <i class="fas fa-arrow-right"></i></span>';
        $html .= '</div>';
        $html .= '</a>';
    }
}

// --- 6. BUILD PAGINATION HTML ---
$pagination_html = '';
if ($total_pages > 1) {
    $queryParams = $_GET;
    unset($queryParams['page']);
    $queryString = http_build_query($queryParams);

    if ($page > 1) {
        $pagination_html .= '<a href="?page=' . ($page - 1) . '&' . $queryString . '">Previous</a>';
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        $active_class = ($i == $page) ? 'active' : '';
        $pagination_html .= '<a href="?page=' . $i . '&' . $queryString . '" class="' . $active_class . '">' . $i . '</a>';
    }
    if ($page < $total_pages) {
        $pagination_html .= '<a href="?page=' . ($page + 1) . '&' . $queryString . '">Next</a>';
    }
}

// --- 7. RETURN JSON RESPONSE ---
header('Content-Type: application/json');
echo json_encode([
    'html' => $html,
    'pagination' => $pagination_html
]);
?>