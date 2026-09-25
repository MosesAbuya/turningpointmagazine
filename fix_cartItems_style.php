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
        // Placed in the same level as qty and styled cleanly
        \$size_label = "
            <select class='custom-select change-size-select mr-4' data-oldkey='{\$safe_key}' data-pid='{\$product_details['id']}' style='width:auto; border-radius:30px; font-weight:600; background-color:#f4f5f7; border:none; padding-left:20px; padding-right:40px; height:45px;'>
                {\$options}
            </select>
        ";
    } else if (\$current_size) {
        \$size_label = '<span class="badge badge-secondary ml-2">' . htmlspecialchars(\$current_size) . '</span>';
    }
    
    \$element = "
    <div class=\"modern-cart-item py-4\" id=\"cart-item-{\$safe_key}\" style=\"border-bottom: 1px solid #eee;\">
        <div class=\"d-flex flex-wrap flex-md-nowrap align-items-center\">
            <img src=\"../admin/{\$product_details['img_path']}\" alt=\"{\$product_details['name']}\" class=\"modern-cart-img\" style=\"width: 100px; height: 100px; object-fit: cover; border-radius: 12px;\" onerror=\"this.src='../images/placeholder.jpg';\">
            
            <div class=\"flex-grow-1 px-4 mb-3 mb-md-0\">
                <h5 class=\"font-weight-bold mb-1\">{\$product_details['name']}</h5>
                <h6 class=\"text-brand-red font-weight-bold mb-0\">Kes ".(number_format(\$product_details['current_price'], 2))."</h6>
            </div>
            
            <div class=\"d-flex align-items-center\">
                {\$size_label}
                
                <div class=\"d-flex align-items-center justify-content-between mr-4\" style=\"background-color:#f4f5f7; border-radius:30px; padding: 5px 15px; width:120px; height: 45px;\">
                    <button type=\"button\" class=\"update-qty-btn border-0 bg-transparent text-dark p-0\" data-pid=\"{\$safe_key}\" data-operation=\"minus\" style=\"outline:none; font-size:1.2rem;\"><i class=\"fas fa-minus\"></i></button>
                    <input type=\"text\" value=\"{\$qty}\" class=\"qty-input-{\$safe_key} border-0 bg-transparent text-center font-weight-bold\" readonly style=\"width: 40px; font-size: 1.1rem; outline:none;\">
                    <button type=\"button\" class=\"update-qty-btn border-0 bg-transparent text-dark p-0\" data-pid=\"{\$safe_key}\" data-operation=\"add\" style=\"outline:none; font-size:1.2rem;\"><i class=\"fas fa-plus\"></i></button>
                </div>
                
                <button type=\"button\" class=\"btn btn-sm rounded-circle remove-item-btn d-flex align-items-center justify-content-center\" data-id=\"{\$safe_key}\" style=\"width:45px; height:45px; border: 1px solid #e74c3c; color: #e74c3c; background: #fff; font-size: 1.2rem; transition: all 0.2s ease;\" title=\"Remove Item\">
                    <i class=\"fas fa-times\"></i>
                </button>
            </div>
        </div>
    </div>
    ";
    echo \$element;
}
EOT;

$c = preg_replace($pattern, $replace, $c);
file_put_contents('shop/inc/dynamic_elements.php', $c);
echo "cartItems restyled.\n";
?>
