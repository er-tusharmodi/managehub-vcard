import re

templates = [
    'resources/views/vcards/templates/bookshop-template.blade.php',
    'resources/views/vcards/templates/coaching-template.blade.php',
]

for path in templates:
    with open(path, 'r') as f:
        content = f.read()

    # Replace: @if(!is_array($X)) { continue; } ?> → @continueIf(!is_array($X))
    content = re.sub(
        r'@if\((!is_array\([^)]+\))\)\s*\{\s*continue;\s*\}\s*\?>',
        r'@continueIf(\1)',
        content
    )
    # Replace: @if(!is_array($X)) { continue; } @endphp → @continueIf(!is_array($X))
    content = re.sub(
        r'@if\((!is_array\([^)]+\))\)\s*\{\s*continue;\s*\}\s*@endphp',
        r'@continueIf(\1)',
        content
    )

    with open(path, 'w') as f:
        f.write(content)
    print(f'Fixed: {path}')
