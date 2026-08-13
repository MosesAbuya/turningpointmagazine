<?php

function prodElement($product_details){
    $element = "
    
    <div class=\"col-lg-3 col-md-6 col-sm-12 col-sm-6 my-3 rounded-0\">
                <form action=\"index.php\" method=\"post\">
                    <div class=\"card shadow rounded-0\">
                        <div class='position-relative rounded-0'>
                            <img loading="lazy" src=\"{$product_details['img_path']}\" alt=\"Image1\" class=\"img-fluid product-img card-img-top  rounded-0\">
                        </div>
                        <div class=\"card-body rounded-0\">
                            <h5 class=\"card-title\">{$product_details['name']}</h5>
                            <h6>
                                <i class=\"fas fa-star\"></i>
                                <i class=\"fas fa-star\"></i>
                                <i class=\"fas fa-star\"></i>
                                <i class=\"far fa-star\"></i>
                                <i class=\"far fa-star\"></i>
                            </h6>
                            <p class=\"card-text\">
                                {$product_details['description']}
                            </p>
                            <h5>
                                ".($product_details['prev_price'] > 0 ? "<small><s class=\"text-secondary\">$ ".(number_format($product_details['prev_price'],2))."</s></small>" : "")."
                                <span class=\"price\">$ ".(number_format($product_details['current_price'],2))."</span>
                            </h5>

                            <button type=\"submit\" class=\"btn btn-primary my-3 rounded-0\" name=\"add\"><i class=\"fa fa-cart-plus\"> Add to Cart</i></button>
                             <input type='hidden' name='product_id' value='{$product_details['id']}'>
                        </div>
                    </div>
                </form>
            </div>
    ";
    echo $element;
}

function cartItems($product_details){
    $element = "
    
    <form action=\"\" method=\"post\" class=\"cart-items\">
                    <div class=\"border rounded\">
                        <div class=\"row bg-white\">
                            <div class=\"col-md-3 pl-0\">
                                <div class=\"position-relative\">
                                    <img loading="lazy" src=\"{$product_details['img_path']}\" alt=\"Image1\" class=\"img-fluid prod-img-cart\">
                                </div>
                            </div>
                            <div class=\"col-md-6 py-3\">
                                <h5 class=\"pt-2\">{$product_details['name']}</h5>
                                <small class=\"text-secondary\">Description: {$product_details['description']}</small>
                                <h5 class=\"pt-2\">$ {$product_details['current_price']}</h5>
                                <button onclick=\"if(confirm('Are you sure to remove this item from list?') === true)location.replace('cart.php?action=removeItem&id={$product_details['id']}');\" type=\"button\" class=\"btn btn-outline-danger btn-sm rounded-0 mx-2\" name=\"remove\"><i class=\"fas fa-trash\"></i> Remove Item</button>
                            </div>
                            <div class=\"col-md-3 py-5\">
                                <div class=\"input-group\">
                                    <button onclick=\"location.replace('cart.php?action=update_qty&pid={$product_details['id']}&operation=minus')\" type=\"button\" class=\"btn bg-light border rounded-0\"><i class=\"fas fa-minus\"></i></button>
                                    <input type=\"text\" value=\"{$_SESSION['cart'][$product_details['id']]}\" class=\"form-control w-25 d-inline text-center\" readonly>
                                    <button onclick=\"location.replace('cart.php?action=update_qty&pid={$product_details['id']}&operation=add')\" type=\"button\" class=\"btn bg-light border rounded-0\"><i class=\"fas fa-plus\"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
    
    ";
    echo  $element;
}


















