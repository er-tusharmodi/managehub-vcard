<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>vCard Editor</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container py-4">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">vCard Editor</h4>
                    <div class="text-muted">{{ $vcard->subdomain }}.{{ config('vcard.base_domain') }}</div>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="/">vCard</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('vcard.editor.update', ['subdomain' => $vcard->subdomain]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @php
                            $useGrid = true;
                            $keys = array_keys($sections);
                        @endphp

                        <ul class="nav nav-tabs" role="tablist">
                            @foreach ($keys as $index => $key)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link @if($index === 0) active @endif" id="tab-{{ $key }}" data-bs-toggle="tab" data-bs-target="#pane-{{ $key }}" type="button" role="tab">
                                        {{ \Illuminate\Support\Str::headline($key) }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <style>
                            .tab-pane.fade:not(.show) {
                                display: none !important;
                            }
                        </style>

                        <div class="tab-content pt-3">
                            @foreach ($keys as $index => $key)
                                <div class="tab-pane fade @if($index === 0) show active @endif" id="pane-{{ $key }}" role="tabpanel">
                                    <div class="row">
                                        @include('vcards.partials.field', [
                                            'key' => $key,
                                            'value' => $sections[$key],
                                            'name' => 'sections[' . $key . ']',
                                            'path' => $key,
                                            'useGrid' => $useGrid,
                                        ])
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-3">
                            <button class="btn btn-primary" type="submit">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('click', function (event) {
                const addBtn = event.target.closest('.add-row');
                const removeBtn = event.target.closest('.remove-row');
                const moveUpBtn = event.target.closest('.move-up');
                const moveDownBtn = event.target.closest('.move-down');

                if (addBtn) {
                    const container = addBtn.closest('[data-repeat]');
                    const template = container.querySelector('template.repeat-template');
                    const items = container.querySelector('.repeat-items');
                    let nextIndex = parseInt(container.getAttribute('data-next-index'), 10) || 0;
                    const html = template.innerHTML.replace(/__INDEX__/g, nextIndex);
                    container.setAttribute('data-next-index', nextIndex + 1);
                    const wrapper = document.createElement('div');
                    wrapper.innerHTML = html;
                    items.appendChild(wrapper.firstElementChild);
                    return;
                }

                if (removeBtn) {
                    const row = removeBtn.closest('.repeat-item');
                    if (row) {
                        row.remove();
                    }
                    return;
                }

                if (moveUpBtn) {
                    const row = moveUpBtn.closest('.repeat-item');
                    const prev = row?.previousElementSibling;
                    if (row && prev) {
                        row.parentNode.insertBefore(row, prev);
                    }
                    return;
                }

                if (moveDownBtn) {
                    const row = moveDownBtn.closest('.repeat-item');
                    const next = row?.nextElementSibling;
                    if (row && next) {
                        row.parentNode.insertBefore(next, row);
                    }
                }
            });
        </script>
    </body>
</html>
