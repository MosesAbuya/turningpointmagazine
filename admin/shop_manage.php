<?php 
include 'connection2.php';
session_start();

include 'consent.php';

$pdo = connect();

// Fetch all shop products
$query = "SELECT * FROM products ORDER BY id DESC";
$stmt = $pdo->query($query);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

closeConnection($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Management Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
    #body {
        background-color: #f8f9fa;
        width: calc(100% - 250px);
        margin-left: 250px;
        margin-top: 100px;
    }
    .card {
        margin-bottom: 20px;
        padding: 10px;
        box-shadow: 0 2px 4px black;
    }
    .no-print { border-bottom: 3px solid black; padding-bottom: 15px; margin-bottom: 20px; }
    .card-title { font-size: 1.5rem; color: red; font-weight: 600; }
    .card-text { font-size: 1rem; color: black; font-weight: 400; }
    .product-card img { object-fit: contain; border-radius: 4px; height: 200px; width: 100%; background: #eee; }
    .underline { text-decoration: underline; font-size: 3rem; font-weight: 700; color: red; }
    .type-badge { position: absolute; top: 10px; right: 10px; padding: 5px 10px; font-weight: bold; border-radius: 5px; color: white; }
    .type-magazine { background-color: #007bff; }
    .type-merch { background-color: #28a745; }
    </style>
</head>
<?php include 'nav.php' ?>

<body id="body">
    <div class="container mt-5">
        <h2 class="text-center underline">Shop Management</h2>

        <div class="text-right mb-4 no-print">
            <button class="btn btn-warning" id="syncMagazinesBtn"><i class="fas fa-sync"></i> Auto-Sync Magazines</button>
            <a href="shop_product_add.php" class="btn btn-success"><i class="fas fa-plus"></i> Add Merchandise</a>
        </div>

        <div class="row">
            <?php foreach ($products as $product): ?>
            <div class="col-md-4">
                <div class="card product-card">
                    <?php if ($product['edition_id']): ?>
                        <span class="type-badge type-magazine">Magazine</span>
                    <?php else: ?>
                        <span class="type-badge type-merch">Merchandise</span>
                    <?php endif; ?>

                    <img loading="lazy" src="<?= htmlspecialchars($product['img_path']) ?>" class="card-img-top" alt="Product Image" onerror="this.src='../images/placeholder.jpg';">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                        <h6 class="text-success font-weight-bold">Ksh <?= number_format($product['current_price'], 2) ?></h6>
                        <p class="card-text text-truncate" title="<?= htmlspecialchars($product['description']) ?>">
                            <?= htmlspecialchars($product['description']) ?>
                        </p>

                        <div class="custom-control custom-switch mt-2 mb-2">
                            <input type="checkbox" class="custom-control-input feature-toggle" id="featureSwitch<?= $product['id'] ?>" data-id="<?= $product['id'] ?>" <?= isset($product['is_featured']) && $product['is_featured'] ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="featureSwitch<?= $product['id'] ?>">Feature in Flash Sale</label>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="shop_product_edit.php?id=<?= $product['id'] ?>" class="btn btn-warning btn-sm w-100 mr-1">Edit Item</a>
                            <button class="btn btn-danger btn-sm w-100 ml-1 delete-product" data-id="<?= $product['id'] ?>">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <?php if (empty($products)): ?>
                <div class="col-12 text-center">
                    <h4>No products in the shop yet.</h4>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Toggle Feature
            $(".feature-toggle").change(function() {
                var id = $(this).data("id");
                var is_featured = $(this).is(":checked") ? 1 : 0;
                
                $.ajax({
                    url: "shop_product_action",
                    type: "POST",
                    data: { action: "toggle_feature", id: id, is_featured: is_featured },
                    dataType: "json",
                    success: function(response) {
                        if(response.status === "success") {
                            Swal.fire({
                                title: "Success!",
                                text: response.message,
                                icon: "success",
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 3000
                            });
                        } else {
                            Swal.fire("Error", response.message, "error");
                        }
                    }
                });
            });
            // Sync Magazines
            $('#syncMagazinesBtn').click(function() {
                Swal.fire({
                    title: 'Auto-Sync Magazines',
                    text: 'This will automatically add any missing magazine editions to the shop. Proceed?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, sync them!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'shop_product_action',
                            type: 'POST',
                            data: { action: 'sync_magazines' },
                            dataType: 'json',
                            success: function(res) {
                                if(res.status === 'success') {
                                    Swal.fire('Synced!', res.message, 'success').then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire('Error', res.message, 'error');
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error', 'An error occurred while syncing magazines.', 'error');
                            }
                        });
                    }
                });
            });

            // Delete Product
            $('.delete-product').click(function() {
                let productId = $(this).data('id');
                Swal.fire({
                    title: 'Delete Product?',
                    text: 'Are you sure you want to delete this product? It will be removed from the shop.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'shop_product_action',
                            type: 'POST',
                            data: { action: 'delete_product', id: productId },
                            dataType: 'json',
                            success: function(res) {
                                if(res.status === 'success') {
                                    Swal.fire('Deleted!', res.message, 'success').then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire('Error', res.message, 'error');
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error', 'An error occurred while deleting the product.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
<?php include 'sidebar.php'; ?>
</html>


