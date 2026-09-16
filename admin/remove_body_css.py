import os
import re

admin_dir = r'e:\xampp\htdocs\turningpoint\admin'
php_files = [f for f in os.listdir(admin_dir) if f.endswith('.php')]

for file_name in php_files:
    file_path = os.path.join(admin_dir, file_name)
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    original_content = content
    
    # Remove #body block from <style>
    content = re.sub(r'#body\s*\{[^}]*\}', '', content, flags=re.MULTILINE)
    
    if content != original_content:
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Removed #body from {file_name}")

print("Done")
