<?php
session_start();
require_once ('inc/Database.php');
require_once ('inc/dynamic_elements.php');

$database = new Database();

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($product_id <= 0) {
    header('Location: index.php');
    exit;
}

$result = $database->getData([$product_id]);
if (!$result) {
    header('Location: index.php');
    exit;
}
$product = $result->fetch_assoc();
$type = $product['edition_id'] ? 'Magazine' : 'Merch';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Global Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
    <link rel="manifest" href="../site.webmanifest">
    <title><?= htmlspecialchars($product['name']) ?> - Turning Point Shop</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.css" />
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <link rel="stylesheet" href="../global.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .product-page-hero {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }
        .product-page-hero::before {
            content: '';
            position: absolute;
            top: -50%; right: -10%;
            width: 60%; height: 200%;
            background: radial-gradient(circle, rgba(230,0,0,0.03) 0%, transparent 70%);
            transform: rotate(-30deg);
        }
        .product-large-img {
            max-height: 500px;
            object-fit: contain;
            filter: drop-shadow(0 20px 30px rgba(0,0,0,0.15));
            transition: transform 0.3s ease;
        }
        .product-large-img:hover {
            transform: scale(1.05);
        }
        .product-details-box {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
            position: relative;
            z-index: 10;
        }
    </style>
</head>
<body>

<?php require_once ("inc/header.php"); ?>

<div class="product-page-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-center mb-5 mb-md-0 position-relative" style="z-index: 2;">
                <img src="../admin/<?= htmlspecialchars($product['img_path']) ?>" class="product-large-img img-fluid" alt="<?= htmlspecialchars($product['name']) ?>" onerror="this.src='../images/placeholder.jpg';">
            </div>
            
            <div class="col-md-6">
                <div class="product-details-box">
                    <div class="modern-card-subtitle mb-2"><?= $type ?> Collection</div>
                    <h1 class="font-weight-bold mb-3"><?= htmlspecialchars($product['name']) ?></h1>
                    
                    <div class="stars mb-4" style="color: #ffc107; font-size: 1.1rem;">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <span class="text-muted ml-2 text-sm" style="font-size: 0.9rem;">(4.5/5 Reviews)</span>
                    </div>
                    
                    <h2 class="font-weight-bold mb-4">
                        <?php if($product['prev_price'] > 0): ?>
                            <small><s class="text-secondary mr-2">Kes <?= number_format($product['prev_price'], 2) ?></s></small>
                        <?php endif; ?>
                        <span class="text-brand-red">Kes <?= number_format($product['current_price'], 2) ?></span>
                    </h2>
                    
                    <p class="text-muted mb-5" style="line-height: 1.8; font-size: 1.05rem;">
                        <?= nl2br(htmlspecialchars($product['description'])) ?>
                    </p>
                    
                    <form action="index.php" method="post" class="add-to-cart-form">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit" class="btn-brand-red py-3 px-5 text-uppercase w-100" style="font-size: 1.1rem; border-radius: 50px;">
                            Add to Cart <i class="fas fa-cart-plus ml-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('.add-to-cart-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var productId = form.find('input[name="product_id"]').val();
        var btn = form.find('button[type="submit"]');
        var originalText = btn.html();
        
        btn.html('<i class="fa fa-spinner fa-spin"></i> Adding...');
        btn.prop('disabled', true);

        $.ajax({
            url: 'ajax_cart',
            type: 'POST',
            data: { action: 'add', pid: productId },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#cart_count').text(response.cart_count);
                    btn.html('<i class="fa fa-check"></i> Added');
                    
                    Swal.fire({
                        title: 'Added to Cart!',
                        text: 'Item has been added to your shopping cart.',
                        icon: 'success',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });

                    setTimeout(function() {
                        btn.html(originalText);
                        btn.prop('disabled', false);
                    }, 2000);
                }
            },
            error: function() {
                Swal.fire('Error', 'Failed to add item to cart.', 'error');
                btn.html(originalText);
                btn.prop('disabled', false);
            }
        });
    });
});
</script>
<?php include '../includes/footer.php'; ?>
</body>
</html>
