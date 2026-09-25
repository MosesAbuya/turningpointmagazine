<?php
$c = file_get_contents('admin/shop_product_action.php');

$pattern1 = '/\$cost_price = \$_POST\[\'cost_price\'\] \?\? 0;/s';
$replacement1 = "\$cost_price = \$_POST['cost_price'] ?? 0;\n    \$has_sizes = isset(\$_POST['has_sizes']) ? 1 : 0;";
$c = preg_replace($pattern1, $replacement1, $c);

// For update (edit_product): UPDATE products SET name = ?, description = ?, current_price = ?, prev_price = ?, cost_price = ?, img_path = ? WHERE id = ?
$pattern2 = '/UPDATE products SET name = \?, description = \?, current_price = \?, prev_price = \?, cost_price = \?, img_path = \? WHERE id = \?/s';
$replacement2 = "UPDATE products SET name = ?, description = ?, current_price = ?, prev_price = ?, cost_price = ?, img_path = ?, has_sizes = ? WHERE id = ?";
$c = preg_replace($pattern2, $replacement2, $c);

// Fix execute for edit_product
$pattern3 = '/\$updateStmt->execute\(\[\$name, \$desc, \$price, \$prev_price, \$cost_price, \$imgPath, \$id\]\);/s';
$replacement3 = "\$updateStmt->execute([\$name, \$desc, \$price, \$prev_price, \$cost_price, \$imgPath, \$has_sizes, \$id]);";
$c = preg_replace($pattern3, $replacement3, $c);


// For insert (add_product): INSERT INTO products (code, name, description, current_price, prev_price, cost_price, img_path, edition_id) VALUES (?, ?, ?, ?, ?, ?, ?, NULL)
$pattern4 = '/INSERT INTO products \(code, name, description, current_price, prev_price, cost_price, img_path, edition_id\) VALUES \(\?, \?, \?, \?, \?, \?, \?, NULL\)/s';
$replacement4 = "INSERT INTO products (code, name, description, current_price, prev_price, cost_price, img_path, has_sizes, edition_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NULL)";
$c = preg_replace($pattern4, $replacement4, $c);

// Fix execute for add_product
$pattern5 = '/\$stmt->execute\(\[\$code, \$name, \$desc, \$price, \$prev_price, \$cost_price, \$imgPath\]\);/s';
$replacement5 = "\$stmt->execute([\$code, \$name, \$desc, \$price, \$prev_price, \$cost_price, \$imgPath, \$has_sizes]);";
$c = preg_replace($pattern5, $replacement5, $c);

file_put_contents('admin/shop_product_action.php', $c);
echo "Replaced in admin/shop_product_action.php";
?>
