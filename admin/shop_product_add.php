<?php
include 'connection2.php';
session_start();
include 'consent.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Merchandise</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
    #body { background-color: #f8f9fa; width: calc(100% - 250px); margin-left: 250px; margin-top: 100px; }
    .form-container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    </style>
</head>
<?php include 'nav.php' ?>

<body id="body">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-container">
                    <h2 class="mb-4">Add Custom Merchandise</h2>
                    <form id="addProductForm">
                        <input type="hidden" name="action" value="add_product">
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. Turning Point T-Shirt">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="4" required placeholder="Enter product details..."></textarea>
                        </div>
                        <div class="form-group">
                            <label>Price (Ksh)</label>
                            <input type="number" step="0.01" name="current_price" class="form-control" required placeholder="e.g. 1500">
                        </div>
                        <div class="form-group">
                            <label>Product Image</label>
                            <input type="file" name="product_image" class="form-control-file" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-success" id="saveBtn">Add Merchandise</button>
                        <a href="shop_manage.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $(document).ready(function() {
        $('#addProductForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $('#saveBtn').prop('disabled', true).text('Adding...');

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
                        $('#saveBtn').prop('disabled', false).text('Add Merchandise');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'An unexpected error occurred.', 'error');
                    $('#saveBtn').prop('disabled', false).text('Add Merchandise');
                }
            });
        });
    });
    </script>
</body>
<?php include 'sidebar.php'; ?>
</html>
