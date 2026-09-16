<?php
include 'connection.php';
session_start();


include 'consent.php';
// Create a database connection
$pdo = connect();

// Fetch all stories
$query = "SELECT * FROM stories";
$stmt = $pdo->query($query);
$stories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Close the database connection
closeConnection($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stories Management Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
</head>


<body id="body">
    <?php include "nav.php"; ?>
    <?php include "sidebar.php"; ?>
    <div id="page-content-wrapper">

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold m-0">Stories Management Dashboard</h2>
            <button class="btn btn-danger d-none" id="bulkDeleteBtn"><i class="fas fa-trash"></i> Delete Selected</button>
        </div>
        
        <div class="table-responsive content-card p-3">
            <table class="table table-modern">
                <thead class="thead-dark">
                    <tr>
                        <th style="width: 40px;"><input type="checkbox" id="selectAll"></th>
                        <th>ID</th>
                        <th>Email</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Story</th>
                        <th>Category</th>
                        <th>Photo</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stories as $story) { 
                        // Determine if the story has images
                        $hasImages = !empty(trim($story['photo']));
                    ?>
                        <tr>
                            <td><input type="checkbox" class="row-checkbox" value="<?= $story['id'] ?>"></td>
                            <td><?= $story['id']; ?></td>
                            <td><?= htmlspecialchars($story['email']); ?></td>
                            <td><?= htmlspecialchars($story['firstname']); ?></td>
                            <td><?= htmlspecialchars($story['lastname']); ?></td>
                            <td>
                                <?php 
                                    $story_text = strip_tags($story['story']);
                                    echo htmlspecialchars(strlen($story_text) > 50 ? substr($story_text, 0, 50) . '...' : $story_text);
                                ?>
                            </td>
                            <td><?= htmlspecialchars($story['category']); ?></td>
                            <td>
                                <?= $hasImages ? '<span class="text-success">Has Images</span>' : '<span class="text-danger">No Images</span>'; ?>
                            </td>
                            <td>
                                <a href="get_story_details.php?id=<?= $story['id']; ?>" class="btn btn-primary btn-sm me-1">
                                    View
                                </a>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $story['id'] ?>"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.row-checkbox');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

        function toggleBulkDelete() {
            const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
            if (checkedCount > 0) {
                bulkDeleteBtn.classList.remove('d-none');
            } else {
                bulkDeleteBtn.classList.add('d-none');
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                toggleBulkDelete();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', toggleBulkDelete);
        });

        // Bulk Delete
        if (bulkDeleteBtn) {
            bulkDeleteBtn.addEventListener('click', function() {
                Swal.fire({title: 'Are you sure?', text: 'Delete selected stories?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e8003d', cancelButtonColor: '#6c757d'}).then((result) => {
                    if (result.isConfirmed) {
                        const ids = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
                        $.post('delete_story.php', { ids: ids }, function(res) {
                            try {
                                res = typeof res === 'string' ? JSON.parse(res) : res;
                                Swal.fire({title: 'Deleted!', text: 'Stories deleted successfully.', icon: 'success', confirmButtonColor: '#e8003d'}).then(() => {
                                    location.reload();
                                });
                            } catch (e) {
                                Swal.fire('Error', 'Server returned an invalid response.', 'error');
                            }
                        }).fail(function() {
                            Swal.fire('Error', 'Failed to delete stories.', 'error');
                        });
                    }
                });
            });
        }

        // Single Delete
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = btn.getAttribute('data-id');
                Swal.fire({title: 'Are you sure?', text: 'Delete this story?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e8003d', cancelButtonColor: '#6c757d'}).then((result) => {
                    if (result.isConfirmed) {
                        $.post('delete_story.php', { id: id }, function(res) {
                            try {
                                res = typeof res === 'string' ? JSON.parse(res) : res;
                                Swal.fire({title: 'Deleted!', text: 'Story deleted successfully.', icon: 'success', confirmButtonColor: '#e8003d'}).then(() => {
                                    location.reload();
                                });
                            } catch (e) {
                                Swal.fire('Error', 'Server returned an invalid response.', 'error');
                            }
                        }).fail(function() {
                            Swal.fire('Error', 'Failed to delete story.', 'error');
                        });
                    }
                });
            });
        });
    });
    </script>
</body>

</html>
