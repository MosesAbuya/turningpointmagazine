<?php

function prodElement($product_details){
    $type = $product_details['edition_id'] ? 'Magazine' : 'Merch';
    $element = "
    <div class=\"col-lg-4 col-md-6 col-sm-12 mb-5\">
        <div class=\"modern-card\">
            <a href=\"product?id={$product_details['id']}\" style=\"text-decoration: none; color: inherit;\">
                <div class=\"modern-card-bg-top\"></div>
                
                <div class=\"img-wrapper\">
                    <img src=\"../admin/{$product_details['img_path']}\" alt=\"{$product_details['name']}\" class=\"product-img\" onerror=\"this.src='../images/placeholder.jpg';\">
                </div>
            </a>
            
            <div class=\"modern-card-body text-center\">
                <a href=\"product?id={$product_details['id']}\" style=\"text-decoration: none; color: inherit;\">
                    <h5 class=\"modern-card-title\">{$product_details['name']}</h5>
                </a>
                <div class=\"modern-card-subtitle\">{$type} Collection</div>
                
                <div class=\"stars\">
                    <i class=\"fas fa-star\"></i>
                    <i class=\"fas fa-star\"></i>
                    <i class=\"fas fa-star\"></i>
                    <i class=\"fas fa-star\"></i>
                    <i class=\"far fa-star\"></i>
                </div>
                
                <div class=\"modern-card-price\">
                    ".($product_details['prev_price'] > 0 ? "<small><s class=\"text-muted mr-2\">Kes ".(number_format($product_details['prev_price'], 2))."</s></small>" : "")."
                    Kes ".(number_format($product_details['current_price'], 2))."
                </div>

                <div class=\"d-flex mt-auto pt-3\" style=\"gap: 10px;\">
                    <a href=\"product?id={$product_details['id']}\" class=\"btn-more w-50 text-center\" style=\"text-decoration: none; display: flex; align-items: center; justify-content: center;\">
                        MORE
                    </a>
                    <form action=\"index.php\" method=\"post\" class=\"add-to-cart-form w-50 m-0\">
                        <input type='hidden' name='product_id' value='{$product_details['id']}'>
                        <button type=\"submit\" class=\"btn-brand-red w-100\" name=\"add\" style=\"padding: 8px 10px; font-size: 0.9rem;\">
                            <i class=\"fas fa-cart-plus\"></i> CART
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    ";
    echo $element;
}

function cartItems($product_details){
    $element = "
    <div class=\"modern-cart-item\" id=\"cart-item-{$product_details['id']}\">
        <img src=\"../admin/{$product_details['img_path']}\" alt=\"{$product_details['name']}\" class=\"modern-cart-img\" onerror=\"this.src='../images/placeholder.jpg';\">
        
        <div class=\"flex-grow-1 px-4\">
            <h5 class=\"font-weight-bold mb-1\">{$product_details['name']}</h5>
            <p class=\"text-muted small mb-2 text-truncate\" style=\"max-width:300px;\">{$product_details['description']}</p>
            <h6 class=\"text-brand-red font-weight-bold mb-0\">Kes ".(number_format($product_details['current_price'], 2))."</h6>
        </div>
        
        <div class=\"d-flex align-items-center\">
            <div class=\"qty-control mr-4\">
                <button type=\"button\" class=\"update-qty-btn\" data-pid=\"{$product_details['id']}\" data-operation=\"minus\"><i class=\"fas fa-minus\"></i></button>
                <input type=\"text\" value=\"{$_SESSION['cart'][$product_details['id']]}\" class=\"qty-input-{$product_details['id']}\" readonly>
                <button type=\"button\" class=\"update-qty-btn\" data-pid=\"{$product_details['id']}\" data-operation=\"add\"><i class=\"fas fa-plus\"></i></button>
            </div>
            
            <button type=\"button\" class=\"btn btn-sm btn-outline-danger rounded-circle remove-item-btn\" data-id=\"{$product_details['id']}\" style=\"width:35px; height:35px;\" title=\"Remove Item\">
                <i class=\"fas fa-times\"></i>
            </button>
        </div>
    </div>
    ";
    echo  $element;
}
?>
