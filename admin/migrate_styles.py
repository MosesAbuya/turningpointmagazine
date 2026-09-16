import os
import re

admin_dir = r'e:\xampp\htdocs\turningpoint\admin'
php_files = [f for f in os.listdir(admin_dir) if f.endswith('.php')]

for file_name in php_files:
    # Skip the new/overhauled ones
    if file_name in ['home.php', 'shop_orders.php', 'shop_order_detail.php', 'shop_sales.php', 'nav.php', 'sidebar.php', 'admin.css', 'shop_product_action.php', 'shop_order_action.php', 'smtp_settings.php']:
        continue

    file_path = os.path.join(admin_dir, file_name)
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    original_content = content
    
    # 1. Check if it has a <body> tag, if not skip (might be action script)
    if '<body' not in content:
        continue

    # 2. Add admin.css and FontAwesome if not present
    if 'admin.css' not in content:
        content = re.sub(r'(</head>)', r'    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">\n    <link rel="stylesheet" href="admin.css">\n\1', content, flags=re.IGNORECASE)

    # 3. Fix nav.php and sidebar.php placement
    # Remove existing includes
    content = re.sub(r'<\?php\s+include\s+[\'"]nav\.php[\'"]\s*;?\s*\?>', '', content, flags=re.IGNORECASE)
    content = re.sub(r'<\?php\s+include\s+[\'"]sidebar\.php[\'"]\s*;?\s*\?>', '', content, flags=re.IGNORECASE)
    
    # 4. Inject them right after <body>
    content = re.sub(r'(<body[^>]*>)', r'\1\n    <?php include "nav.php"; ?>\n    <?php include "sidebar.php"; ?>\n    <div id="page-content-wrapper">', content, flags=re.IGNORECASE)
    
    # 5. Close page-content-wrapper before script tags or </body>
    if '<script' in content:
        # insert before first script tag that comes after body
        body_split = content.split('<?php include "sidebar.php"; ?>\n    <div id="page-content-wrapper">')
        if len(body_split) > 1:
            rest = body_split[1]
            if '<script' in rest:
                rest = rest.replace('<script', '    </div>\n<script', 1)
                content = body_split[0] + '<?php include "sidebar.php"; ?>\n    <div id="page-content-wrapper">' + rest
            else:
                content = content.replace('</body>', '    </div>\n</body>')
    else:
        content = content.replace('</body>', '    </div>\n</body>')

    # 6. Change table classes to modern
    content = re.sub(r'class="table\s+([^"]*)"', r'class="table table-modern \1"', content)
    content = content.replace('table-modern table-bordered', 'table-modern')
    content = content.replace('table-modern table-striped', 'table-modern')
    content = content.replace('table-modern table-hover', 'table-modern')
    
    # 7. Convert <div class="container mt-5"> to something without mt-5
    content = content.replace('<div class="container mt-5">', '<div class="container-fluid">')
    
    # 8. Convert <h2 ... underline ...> to modern header
    content = re.sub(r'<h2 class="text-center underline[^"]*">(.*?)</h2>', r'<h2 class="fw-bold mb-4">\1</h2>', content)
    
    if content != original_content:
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Updated {file_name}")

print("Done")
