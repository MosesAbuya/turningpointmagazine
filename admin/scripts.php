<script>
$(document).on('click', '.editBtn', function() {
    const id = $(this).data('id');

    // Fetch data for the selected record and populate the form
    $.ajax({
        url: 'get_record.php', // Create a script to fetch a single record by ID
        type: 'POST',
        data: {
            id: id
        },
        success: function(data) {
            const record = JSON.parse(data);
            $('#editModal input[name="category"]').val(record.category);
            $('#editModal input[name="name"]').val(record.name);
            $('#editModal textarea[name="description"]').val(record.description);
            $('#editModal input[name="target"]').val(record.target);
            $('#editModal input[name="raised"]').val(record.raised);
            $('#editModal').modal('show');
        }
    });
});

$(document).on('click', '.deleteBtn', function() {
    const id = $(this).data('id');

    if (confirm('Are you sure you want to delete this record?')) {
        $.ajax({
            url: 'delete_record.php',
            type: 'POST',
            data: {
                id: id
            },
            success: function() {
                alert('Record deleted successfully!');
                location.reload(); // Reload the page to reflect changes
            }
        });
    }
});
</script>