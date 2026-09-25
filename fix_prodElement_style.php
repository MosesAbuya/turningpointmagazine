<?php
$c = file_get_contents('shop/inc/dynamic_elements.php');

$pattern = '/function prodElement\(\$product_details\).*?function cartItems/s';
$replace = <<<EOT
function prodElement(\$product_details) {
    global \$database;
    \$id = \$product_details['id'];
    \$name = htmlspecialchars(\$product_details['name']);
    \$desc = htmlspecialchars(\$product_details['description']);
    \$prev_price = \$product_details['prev_price'];
    \$curr_price = \$product_details['current_price'];
    \$img = htmlspecialchars(\$product_details['img_path']);
    \$has_sizes = \$product_details['has_sizes'] ?? 0;
    
    \$size_selector = "";
    if (\$has_sizes == 1 && isset(\$database->conn)) {
        \$res = \$database->conn->query("SELECT size_name FROM product_sizes ORDER BY order_index ASC");
        \$options = "<option value='' disabled selected>Size</option>";
        while(\$s = \$res->fetch_assoc()){
            \$sname = htmlspecialchars(\$s['size_name']);
            \$options .= "<option value='{\$sname}'>{\$sname}</option>";
        }
        
        \$size_selector = "
        <div class='d-flex mb-2' style='gap:10px;'>
            <select name='size' class='custom-select custom-select-sm w-50' style='border-radius:20px;' required>{\$options}</select>
            <div class='input-group input-group-sm w-50'>
                <div class='input-group-prepend'><span class='input-group-text' style='border-radius: 20px 0 0 20px;'>Qty</span></div>
                <input type='number' name='qty' class='form-control' value='1' min='1' required style='border-radius: 0 20px 20px 0; text-align:center;'>
            </div>
        </div>
        ";
    }

    // Revert structure back to closely match original design while keeping form wrapper
    \$form_html = "
    <form action='index.php' method='post' class='add-to-cart-form w-100 m-0'>
        <input type='hidden' name='product_id' value='{\$id}'>
        {\$size_selector}
        <div class='d-flex' style='gap: 10px;'>
            <a href='product?id={\$id}' class='btn-more w-50 text-center' style='text-decoration: none; display: flex; align-items: center; justify-content: center; border-radius:30px; font-weight:600;'>
                MORE
            </a>
            <button type='submit' class='btn-brand-red w-50' name='add' style='padding: 8px 10px; font-size: 0.9rem; border-radius:30px; font-weight:600;'>
                <i class='fas fa-cart-plus'></i> CART
            </button>
        </div>
    </form>
    ";

    \$element = "
    <div class='col-md-3 col-sm-6 my-3'>
        <div class='card shadow-sm h-100 product-card'>
            <div class='position-relative'>
                <a href='product?id={\$id}'>
                    <img src='../admin/{\$img}' alt='{\$name}' class='card-img-top product-img' onerror=\"this.src='../images/placeholder.jpg';\">
                </a>
            </div>
            <div class='card-body d-flex flex-column'>
                <h5 class='card-title font-weight-bold'>
                    <a href='product?id={\$id}' class='text-dark' style='text-decoration:none;'>{\$name}</a>
                </h5>
                <p class='card-text text-muted small text-truncate'>{\$desc}</p>
                <div class='mt-2 mb-3 font-weight-bold text-brand-red'>
                    " . (\$prev_price > 0 ? "<small><s class='text-muted mr-2'>Kes " . number_format(\$prev_price, 2) . "</s></small>" : "") . "
                    Kes " . number_format(\$curr_price, 2) . "
                </div>
                <div class='mt-auto pt-3'>
                    {\$form_html}
                </div>
            </div>
        </div>
    </div>
    ";
    echo \$element;
}

function cartItems
EOT;

$c = preg_replace($pattern, $replace, $c);
file_put_contents('shop/inc/dynamic_elements.php', $c);
echo "prodElement restyled.\n";
?>
