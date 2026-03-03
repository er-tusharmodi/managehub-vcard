import re

files = [
    'resources/views/vcards/templates/coaching-template.blade.php',
    'resources/views/vcards/templates/bookshop-template.blade.php',
]

for path in files:
    with open(path, 'r') as f:
        content = f.read()

    original = content

    # 1. @php foreach (X) → @foreach(X)
    content = re.sub(r'@php foreach \(', '@foreach(', content)

    # 2. Raw <?php foreach (X) without colon → @foreach(X)
    content = re.sub(r'<\?php foreach \((.+?)\)\s*\n', lambda m: f'@foreach({m.group(1)})\n', content)

    # 3. <?php if (!is_array($X)) { continue; } @endphp → @continueIf(!is_array($X))
    content = re.sub(
        r'<\?php if \((!is_array\([^)]+\))\) \{ continue; \} @endphp',
        r'@continueIf(\1)',
        content
    )

    count_changes = sum(1 for a, b in zip(original.splitlines(), content.splitlines()) if a != b)
    with open(path, 'w') as f:
        f.write(content)
    print(f'Fixed {count_changes} lines in {path}')
