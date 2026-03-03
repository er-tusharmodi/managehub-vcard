import subprocess

templates = [
    'minimart-template',
    'mens-salon-template',
    'restaurant-cafe-template',
    'jewelry-shop-template',
    'electronics-shop-template',
    'bookshop-template',
    'coaching-template',
    'sweetshop-template',
]

for key in templates:
    src = subprocess.run(
        ['git', 'show', f'2cf679b:vcard-template/{key}/index.php'],
        capture_output=True, text=True
    ).stdout

    # Split at ?> to get preamble
    doctype_pos = src.lower().find('<!doctype')
    preamble = src[:doctype_pos]

    # Extract only top-level variable assignments (after all the function defs)
    # Strategy: find the last closing brace "^}" at depth 0, then take everything after
    lines = preamble.split('\n')
    depth = 0
    last_close_idx = 0
    for i, line in enumerate(lines):
        stripped = line.strip()
        depth += stripped.count('{') - stripped.count('}')
        if depth == 0 and stripped == '}':
            last_close_idx = i

    var_lines = lines[last_close_idx + 1:]
    # Strip PHP close tag
    result = []
    for l in var_lines:
        s = l.strip()
        if s in ['?>', '']:
            continue
        result.append(l)

    print(f'=== {key} ===')
    print('\n'.join(result))
    print()
