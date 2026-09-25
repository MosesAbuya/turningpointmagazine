<?php
$c = file_get_contents('shop/inc/dynamic_elements.php');

$pattern = '/function cartItems.*?echo \$element;\s*\}/s';

$replace = <<<EOT
function cartItems(\$product_details, \$cart_key = null, \$qty = 1){
    global \$database;
    \$size_label = '';
    \$safe_key = htmlspecialchars(\$cart_key ?? \$product_details['id'], ENT_QUOTES);
    \$current_size = '';
    
    if (\$cart_key && strpos(\$cart_key, '_') !== false) {
        \$parts = explode('_', \$cart_key);
        \$current_size = \$parts[1];
    }
    
    if (\$product_details['has_sizes'] == 1 && isset(\$database->conn)) {
        \$res = \$database->conn->query("SELECT size_name FROM product_sizes ORDER BY order_index ASC");
        \$options = "";
        while(\$s = \$res->fetch_assoc()){
            \$sname = htmlspecialchars(\$s['size_name']);
            \$selected = (\$sname === \$current_size) ? "selected" : "";
            \$options .= "<option value='{\$sname}' {\$selected}>{\$sname}</option>";
        }
        \$size_label = "
            <select class='form-control form-control-sm d-inline-block w-auto ml-2 change-size-select' data-oldkey='{\$safe_key}' data-pid='{\$product_details['id']}'>
                {\$options}
            </select>
        ";
    } else if (\$current_size) {
        \$size_label = '<span class="badge badge-secondary ml-2">' . htmlspecialchars(\$current_size) . '</span>';
    }
    
    \$element = "
    <div class=\"modern-cart-item\" id=\"cart-item-{\$safe_key}\">
        <img src=\"../admin/{\$product_details['img_path']}\" alt=\"{\$product_details['name']}\" class=\"modern-cart-img\" onerror=\"this.src='../images/placeholder.jpg';\">
        
        <div class=\"flex-grow-1 px-4\">
            <h5 class=\"font-weight-bold mb-1 d-flex align-items-center\">{\$product_details['name']} {\$size_label}</h5>
            <p class=\"text-muted small mb-2 text-truncate\" style=\"max-width:300px;\">{\$product_details['description']}</p>
            <h6 class=\"text-brand-red font-weight-bold mb-0\">Kes ".(number_format(\$product_details['current_price'], 2))."</h6>
        </div>
        
        <div class=\"d-flex align-items-center\">
            <div class=\"qty-control mr-4\">
                <button type=\"button\" class=\"update-qty-btn\" data-pid=\"{\$safe_key}\" data-operation=\"minus\"><i class=\"fas fa-minus\"></i></button>
                <input type=\"text\" value=\"{\$qty}\" class=\"qty-input-{\$safe_key}\" readonly>
                <button type=\"button\" class=\"update-qty-btn\" data-pid=\"{\$safe_key}\" data-operation=\"add\"><i class=\"fas fa-plus\"></i></button>
            </div>
            
            <button type=\"button\" class=\"btn btn-sm btn-outline-danger rounded-circle remove-item-btn\" data-id=\"{\$safe_key}\" style=\"width:35px; height:35px;\" title=\"Remove Item\">
                <i class=\"fas fa-times\"></i>
            </button>
        </div>
    </div>
    ";
    echo \$element;
}
EOT;

$c = preg_replace($pattern, $replace, $c);
file_put_contents('shop/inc/dynamic_elements.php', $c);
echo "dynamic_elements.php cartItems updated.";
?>
