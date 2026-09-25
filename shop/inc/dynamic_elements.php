<?php

function prodElement($product_details) {
    global $database;
    $id = $product_details['id'];
    $name = htmlspecialchars($product_details['name']);
    $desc = htmlspecialchars($product_details['description']);
    $prev_price = $product_details['prev_price'];
    $curr_price = $product_details['current_price'];
    $img = htmlspecialchars($product_details['img_path']);
    $has_sizes = $product_details['has_sizes'] ?? 0;
    
    $size_selector = "";
    if ($has_sizes == 1 && isset($database->conn)) {
        $res = $database->conn->query("SELECT size_name FROM product_sizes ORDER BY order_index ASC");
        $options = "<option value='' disabled selected>Size</option>";
        while($s = $res->fetch_assoc()){
            $sname = htmlspecialchars($s['size_name']);
            $options .= "<option value='{$sname}'>{$sname}</option>";
        }
        
        $size_selector = "
        <div class='d-flex mb-2' style='gap:10px;'>
            <select name='size' class='custom-select custom-select-sm w-50' style='border-radius:20px;' required>{$options}</select>
            <div class='input-group input-group-sm w-50'>
                <div class='input-group-prepend'><span class='input-group-text' style='border-radius: 20px 0 0 20px;'>Qty</span></div>
                <input type='number' name='qty' class='form-control' value='1' min='1' required style='border-radius: 0 20px 20px 0; text-align:center;'>
            </div>
        </div>
        ";
    }

    // Revert structure back to closely match original design while keeping form wrapper
    $form_html = "
    <form action='index.php' method='post' class='add-to-cart-form w-100 m-0'>
        <input type='hidden' name='product_id' value='{$id}'>
        {$size_selector}
        <div class='d-flex' style='gap: 10px;'>
            <a href='product?id={$id}' class='btn-more w-50 text-center' style='text-decoration: none; display: flex; align-items: center; justify-content: center; border-radius:30px; font-weight:600;'>
                MORE
            </a>
            <button type='submit' class='btn-brand-red w-50' name='add' style='padding: 8px 10px; font-size: 0.9rem; border-radius:30px; font-weight:600;'>
                <i class='fas fa-cart-plus'></i> CART
            </button>
        </div>
    </form>
    ";

    $element = "
    <div class='col-md-3 col-sm-6 my-3'>
        <div class='card shadow-sm h-100 product-card'>
            <div class='position-relative'>
                <a href='product?id={$id}'>
                    <img src='../admin/{$img}' alt='{$name}' class='card-img-top product-img' onerror=\"this.src='../images/placeholder.jpg';\">
                </a>
            </div>
            <div class='card-body d-flex flex-column'>
                <h5 class='card-title font-weight-bold'>
                    <a href='product?id={$id}' class='text-dark' style='text-decoration:none;'>{$name}</a>
                </h5>
                <p class='card-text text-muted small text-truncate'>{$desc}</p>
                <div class='mt-2 mb-3 font-weight-bold text-brand-red'>
                    " . ($prev_price > 0 ? "<small><s class='text-muted mr-2'>Kes " . number_format($prev_price, 2) . "</s></small>" : "") . "
                    Kes " . number_format($curr_price, 2) . "
                </div>
                <div class='mt-auto pt-3'>
                    {$form_html}
                </div>
            </div>
        </div>
    </div>
    ";
    echo $element;
}

function cartItems($product_details, $cart_key = null, $qty = 1){
    global $database;
    $size_label = '';
    $safe_key = htmlspecialchars($cart_key ?? $product_details['id'], ENT_QUOTES);
    $current_size = '';
    
    if ($cart_key && strpos($cart_key, '_') !== false) {
        $parts = explode('_', $cart_key);
        $current_size = $parts[1];
    }
    
    if ($product_details['has_sizes'] == 1 && isset($database->conn)) {
        $res = $database->conn->query("SELECT size_name FROM product_sizes ORDER BY order_index ASC");
        $options = "";
        while($s = $res->fetch_assoc()){
            $sname = htmlspecialchars($s['size_name']);
            $selected = ($sname === $current_size) ? "selected" : "";
            $options .= "<option value='{$sname}' {$selected}>{$sname}</option>";
        }
        // Placed in the same level as qty and styled cleanly
        $size_label = "
            <select class='custom-select change-size-select mr-4' data-oldkey='{$safe_key}' data-pid='{$product_details['id']}' style='width:auto; border-radius:30px; font-weight:600; background-color:#f4f5f7; border:none; padding-left:20px; padding-right:40px; height:45px;'>
                {$options}
            </select>
        ";
    } else if ($current_size) {
        $size_label = '<span class="badge badge-secondary ml-2">' . htmlspecialchars($current_size) . '</span>';
    }
    
    $element = "
    <div class=\"modern-cart-item py-4\" id=\"cart-item-{$safe_key}\" style=\"border-bottom: 1px solid #eee;\">
        <div class=\"d-flex flex-wrap flex-md-nowrap align-items-center\">
            <img src=\"../admin/{$product_details['img_path']}\" alt=\"{$product_details['name']}\" class=\"modern-cart-img\" style=\"width: 100px; height: 100px; object-fit: cover; border-radius: 12px;\" onerror=\"this.src='../images/placeholder.jpg';\">
            
            <div class=\"flex-grow-1 px-4 mb-3 mb-md-0\">
                <h5 class=\"font-weight-bold mb-1\">{$product_details['name']}</h5>
                <h6 class=\"text-brand-red font-weight-bold mb-0\">Kes ".(number_format($product_details['current_price'], 2))."</h6>
            </div>
            
            <div class=\"d-flex align-items-center\">
                {$size_label}
                
                <div class=\"d-flex align-items-center justify-content-between mr-4\" style=\"background-color:#f4f5f7; border-radius:30px; padding: 5px 15px; width:120px; height: 45px;\">
                    <button type=\"button\" class=\"update-qty-btn border-0 bg-transparent text-dark p-0\" data-pid=\"{$safe_key}\" data-operation=\"minus\" style=\"outline:none; font-size:1.2rem;\"><i class=\"fas fa-minus\"></i></button>
                    <input type=\"text\" value=\"{$qty}\" class=\"qty-input-{$safe_key} border-0 bg-transparent text-center font-weight-bold\" readonly style=\"width: 40px; font-size: 1.1rem; outline:none;\">
                    <button type=\"button\" class=\"update-qty-btn border-0 bg-transparent text-dark p-0\" data-pid=\"{$safe_key}\" data-operation=\"add\" style=\"outline:none; font-size:1.2rem;\"><i class=\"fas fa-plus\"></i></button>
                </div>
                
                <button type=\"button\" class=\"btn btn-sm rounded-circle remove-item-btn d-flex align-items-center justify-content-center\" data-id=\"{$safe_key}\" style=\"width:45px; height:45px; border: 1px solid #e74c3c; color: #e74c3c; background: #fff; font-size: 1.2rem; transition: all 0.2s ease;\" title=\"Remove Item\">
                    <i class=\"fas fa-times\"></i>
                </button>
            </div>
        </div>
    </div>
    ";
    echo $element;
}
?>
