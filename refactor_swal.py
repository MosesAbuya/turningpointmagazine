import os

directory = r'e:\xampp\htdocs\turningpoint\admin'

replacements = {
    "if (confirm('Are you sure you want to delete this attendee?')) {": "Swal.fire({title: 'Are you sure?', text: 'Are you sure you want to delete this attendee?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e8003d', cancelButtonColor: '#6c757d', confirmButtonText: 'Yes, delete it!'}).then((result) => { if (result.isConfirmed) {",
    "if (selectedIds.length > 0 && confirm('Are you sure you want to delete the selected attendees?')) {": "if (selectedIds.length === 0) { Swal.fire('Error', 'Please select attendees to delete.', 'error'); return; } Swal.fire({title: 'Are you sure?', text: 'Delete selected attendees?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e8003d', cancelButtonColor: '#6c757d'}).then((result) => { if (result.isConfirmed) {",
    
    "if (confirm('Are you sure you want to delete this booking?')) {": "Swal.fire({title: 'Are you sure?', text: 'Are you sure you want to delete this booking?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e8003d', cancelButtonColor: '#6c757d', confirmButtonText: 'Yes, delete it!'}).then((result) => { if (result.isConfirmed) {",
    "if (selectedIds.length > 0 && confirm('Are you sure you want to delete the selected bookings?')) {": "if (selectedIds.length === 0) { Swal.fire('Error', 'Please select bookings to delete.', 'error'); return; } Swal.fire({title: 'Are you sure?', text: 'Delete selected bookings?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e8003d', cancelButtonColor: '#6c757d'}).then((result) => { if (result.isConfirmed) {",
    
    "if (confirm(\"Are you sure you want to delete this organization?\")) {": "Swal.fire({title: 'Are you sure?', text: 'Delete this organization?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e8003d', cancelButtonColor: '#6c757d'}).then((result) => { if (result.isConfirmed) {",
    
    "if (confirm('Are you sure you want to delete this feedback?')) {": "Swal.fire({title: 'Are you sure?', text: 'Delete this feedback?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e8003d', cancelButtonColor: '#6c757d'}).then((result) => { if (result.isConfirmed) {",
    "if (confirm('Are you sure you want to delete the selected feedback?')) {": "Swal.fire({title: 'Are you sure?', text: 'Delete selected feedback?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e8003d', cancelButtonColor: '#6c757d'}).then((result) => { if (result.isConfirmed) {",
    
    "if (confirm(\"Are you sure you want to delete this post?\")) {": "Swal.fire({title: 'Are you sure?', text: 'Delete this post?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e8003d', cancelButtonColor: '#6c757d'}).then((result) => { if (result.isConfirmed) {",
    
    "if (confirm('Are you sure you want to delete this record?')) {": "Swal.fire({title: 'Are you sure?', text: 'Delete this record?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e8003d', cancelButtonColor: '#6c757d'}).then((result) => { if (result.isConfirmed) {",
    
    "if (confirm('Are you sure you want to delete the selected orders?')) {": "Swal.fire({title: 'Are you sure?', text: 'Delete selected orders?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e8003d', cancelButtonColor: '#6c757d'}).then((result) => { if (result.isConfirmed) {",
    "if (confirm('Are you sure you want to delete this order?')) {": "Swal.fire({title: 'Are you sure?', text: 'Delete this order?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e8003d', cancelButtonColor: '#6c757d'}).then((result) => { if (result.isConfirmed) {",
    
    "if (confirm('Are you sure you want to delete the selected stories?')) {": "Swal.fire({title: 'Are you sure?', text: 'Delete selected stories?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e8003d', cancelButtonColor: '#6c757d'}).then((result) => { if (result.isConfirmed) {",
    "if (confirm('Are you sure you want to delete this story?')) {": "Swal.fire({title: 'Are you sure?', text: 'Delete this story?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e8003d', cancelButtonColor: '#6c757d'}).then((result) => { if (result.isConfirmed) {",
    
    "if (confirm('Are you sure you want to delete this subscriber?')) {": "Swal.fire({title: 'Are you sure?', text: 'Delete this subscriber?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e8003d', cancelButtonColor: '#6c757d'}).then((result) => { if (result.isConfirmed) {",
    "if (selectedIds.length > 0 && confirm('Are you sure you want to delete the selected subscribers?')) {": "if (selectedIds.length === 0) { Swal.fire('Error', 'Please select subscribers to delete.', 'error'); return; } Swal.fire({title: 'Are you sure?', text: 'Delete selected subscribers?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e8003d', cancelButtonColor: '#6c757d'}).then((result) => { if (result.isConfirmed) {",

    "alert(response);": "Swal.fire({title: 'Success', text: response, icon: 'success', confirmButtonColor: '#e8003d'});",
    "alert('Attendee deleted successfully!');": "Swal.fire({title: 'Success', text: 'Attendee deleted successfully!', icon: 'success', confirmButtonColor: '#e8003d'});",
    "alert('Selected attendees deleted successfully!');": "Swal.fire({title: 'Success', text: 'Selected attendees deleted successfully!', icon: 'success', confirmButtonColor: '#e8003d'});",
    "alert('Booking deleted successfully!');": "Swal.fire({title: 'Success', text: 'Booking deleted successfully!', icon: 'success', confirmButtonColor: '#e8003d'});",
    "alert('Selected bookings deleted successfully!');": "Swal.fire({title: 'Success', text: 'Selected bookings deleted successfully!', icon: 'success', confirmButtonColor: '#e8003d'});",
    "alert('Record deleted successfully!');": "Swal.fire({title: 'Success', text: 'Record deleted successfully!', icon: 'success', confirmButtonColor: '#e8003d'});",
    "alert('Subscriber deleted successfully!');": "Swal.fire({title: 'Success', text: 'Subscriber deleted successfully!', icon: 'success', confirmButtonColor: '#e8003d'});",
    "alert('Selected subscribers deleted successfully!');": "Swal.fire({title: 'Success', text: 'Selected subscribers deleted successfully!', icon: 'success', confirmButtonColor: '#e8003d'});",
    "alert('Please select attendees to delete.');": "",
    "alert('Please select bookings to delete.');": "",
    "alert('Please select subscribers to delete.');": "",
}

import re

for filename in os.listdir(directory):
    if not filename.endswith('.php'): continue
    filepath = os.path.join(directory, filename)
    with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
    
    modified = False
    for k, v in replacements.items():
        if k in content:
            content = content.replace(k, v)
            modified = True
            
    if modified:
        # Regex to append closing }); to the block.
        # Find .then((result) => { if (result.isConfirmed) {
        # Follow it until the closing brace of the if block.
        # This is hard with regex, so let's do a simple count of braces.
        
        lines = content.split('\n')
        new_lines = []
        open_braces = 0
        in_swal_block = False
        swal_stack = []
        
        for line in lines:
            if ".then((result) => { if (result.isConfirmed) {" in line:
                swal_stack.append(open_braces)
                
            open_braces += line.count('{')
            open_braces -= line.count('}')
            
            # If we are closing a block that was opened by Swal
            if swal_stack and open_braces == swal_stack[-1] - 1:
                # We just closed it! Wait, we actually closed it on this line.
                # Find the } and append });
                # Let's just do a naive replace for this line
                line = line.replace('}', '} });', 1)
                swal_stack.pop()
                open_braces -= 1 # because we added a closing brace? No we added }); which has }. Wait. } }); has TWO closing braces!
                # Actually, line.replace('}', '} });', 1) replaces one } with } });. The string itself now has } } so it closed one MORE brace.
                open_braces -= 1
                
            new_lines.append(line)
            
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write('\n'.join(new_lines))
            
print('Done initial replacements.')
