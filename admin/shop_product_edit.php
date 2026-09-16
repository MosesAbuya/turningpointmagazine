<?php
include 'connection2.php';
session_start();
include 'consent.php';

$pdo = connect();
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id === 0) {
    header("Location: shop_manage.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found.");
}
closeConnection($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
    
    .form-container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .current-img { max-width: 150px; border-radius: 5px; margin-bottom: 10px; display: block; border: 1px solid #ccc; }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
</head>


<body id="body">
    <?php include "nav.php"; ?>
    <?php include "sidebar.php"; ?>
    <div id="page-content-wrapper">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-container">
                    <h2 class="mb-4">Edit <?= $product['edition_id'] ? 'Magazine Edition' : 'Merchandise' ?></h2>
                    <form id="editProductForm">
                        <input type="hidden" name="action" value="edit_product">
                        <input type="hidden" name="id" value="<?= $product_id ?>">
                        <input type="hidden" name="edition_id" value="<?= $product['edition_id'] ?>">
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($product['name']) ?>" <?= $product['edition_id'] ? 'readonly title="Magazine names are locked to the edition name. Edit the edition directly if needed."' : '' ?>>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($product['description']) ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Current Price (Ksh)</label>
                                <input type="number" step="0.01" name="current_price" class="form-control" required value="<?= htmlspecialchars($product['current_price']) ?>">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Slash Price (Ksh)</label>
                                <input type="number" step="0.01" name="prev_price" class="form-control" value="<?= htmlspecialchars($product['prev_price'] ?? 0) ?>">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Cost Price (Ksh)</label>
                                <input type="number" step="0.01" name="cost_price" class="form-control" required value="<?= htmlspecialchars($product['cost_price'] ?? 0) ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Current Image</label>
                            <img src="<?= htmlspecialchars($product['img_path']) ?>" class="current-img" onerror="this.src='../images/placeholder.jpg';">
                            <label>Upload New Image (Optional)</label>
                            <input type="file" name="product_image" class="form-control-file" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-warning" id="saveBtn">Save Changes</button>
                        <a href="shop_manage.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

        </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $(document).ready(function() {
        $('#editProductForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $('#saveBtn').prop('disabled', true).text('Saving...');

            $.ajax({
                url: 'shop_product_action',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = 'shop_manage';
                        });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                        $('#saveBtn').prop('disabled', false).text('Save Changes');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'An unexpected error occurred.', 'error');
                    $('#saveBtn').prop('disabled', false).text('Save Changes');
                }
            });
        });
    });
    </script>
</body>

</html>
