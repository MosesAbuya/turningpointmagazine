<?php
session_start();
require_once ('inc/Database.php');
require_once ('inc/dynamic_elements.php');

$database = new Database();
$result = $database->getData();
$products = [];
if($result){
    while ($row = $result->fetch_assoc()){
        $products[] = $row;
    }
}
// Get latest 3 products for the carousel
$heroProducts = array_slice($products, 0, 3);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turning Point Shop</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.css" />
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <link rel="stylesheet" href="../global.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once ("inc/header.php"); ?>

<!-- Hero Carousel Section -->
<?php if(count($heroProducts) > 0): ?>
<div id="shopHeroCarousel" class="carousel slide" data-ride="carousel" data-interval="5000">
    <div class="carousel-inner">
        <?php foreach($heroProducts as $index => $hero): ?>
        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
            <div class="hero-carousel">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-6 hero-content">
                            <h1 class="hero-title"><?= htmlspecialchars($hero['name']) ?></h1>
                            <p class="hero-desc"><?= htmlspecialchars(substr($hero['description'], 0, 150)) ?>...</p>
                            
                            <h3 class="mb-4 font-weight-bold">
                                <?php if($hero['prev_price'] > 0): ?>
                                    <small><s class="text-secondary">Kes <?= number_format($hero['prev_price']) ?></s></small>
                                <?php endif; ?>
                                <span class="text-brand-red">Kes <?= number_format($hero['current_price']) ?></span>
                            </h3>

                            <form action="index.php" method="post" class="add-to-cart-form d-inline-block">
                                <input type="hidden" name="product_id" value="<?= $hero['id'] ?>">
                                <button type="submit" class="btn-brand-dark px-4 py-2" name="add">
                                    SHOP NOW <i class="fas fa-chevron-right ml-2"></i>
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6 hero-img-container mt-5 mt-md-0">
                            <a href="product?id=<?= $hero['id'] ?>">
                                <img src="../admin/<?= htmlspecialchars($hero['img_path']) ?>" class="hero-img img-fluid" alt="<?= htmlspecialchars($hero['name']) ?>" onerror="this.src='../images/placeholder.jpg';">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php if(count($heroProducts) > 1): ?>
    <a class="carousel-control-prev" href="#shopHeroCarousel" role="button" data-slide="prev" style="width: 5%;">
        <span class="carousel-control-prev-icon" aria-hidden="true" style="filter: invert(1);"></span>
    </a>
    <a class="carousel-control-next" href="#shopHeroCarousel" role="button" data-slide="next" style="width: 5%;">
        <span class="carousel-control-next-icon" aria-hidden="true" style="filter: invert(1);"></span>
    </a>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Filter Bar -->
<div class="container">
    <div class="filter-bar">
        <div class="filter-item">
            <span class="filter-label">Category</span>
            <span class="filter-value">All Items</span>
        </div>
        <div class="filter-item">
            <span class="filter-label">Brand</span>
            <span class="filter-value">Turning Point</span>
        </div>
        <div class="filter-item">
            <span class="filter-label">Sort By</span>
            <span class="filter-value">Latest Arrivals</span>
        </div>
        <div class="filter-item">
            <a href="#products-grid" class="btn btn-outline-red">BROWSE SHOP <i class="fas fa-chevron-right ml-1"></i></a>
        </div>
    </div>
</div>

<!-- Products Grid -->
<div class="container" id="products-grid">
    <div class="row pt-5 pb-5">
        <div class="col-12 mb-4">
            <h3 class="font-weight-bold">New Collection</h3>
        </div>
        <?php
            if(count($products) > 0){
                foreach ($products as $row){
                    prodElement($row);
                }
            } else {
                echo "<div class='col-12'><h4 class='text-center text-muted py-5'>No Products Listed Yet</h4></div>";
            }
        ?>
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
