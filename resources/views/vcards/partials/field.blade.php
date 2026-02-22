@php
    $label = $label ?? \Illuminate\Support\Str::headline($key);
    $isArray = is_array($value);
    $isAssoc = $isArray && array_values($value) !== $value;
    $isList = $isArray && !$isAssoc;
    $isScalarList = $isList && (empty($value) || !is_array($value[0] ?? null));
    $isImageKey = is_string($key) && preg_match('/(image|logo|banner|profile|photo|avatar|icon)/i', $key);
    $isTextArea = is_string($key) && preg_match('/(description|desc|about|bio|note|tagline|address|subtitle|message)/i', $key);
    $isUrl = is_string($key) && preg_match('/(url|link|website|maps|facebook|instagram|youtube|twitter|whatsapp)/i', $key);
    $isPhone = is_string($key) && preg_match('/(phone|mobile|whatsapp|tel)/i', $key);
    $uploadName = str_starts_with($name, 'sections') ? 'uploads' . substr($name, strlen('sections')) : 'uploads[' . $name . ']';
    $useGrid = $useGrid ?? false;
    $wrapperClass = null;
    if ($useGrid) {
        $wrapperClass = ($isAssoc || $isList || $isScalarList) ? 'col-12' : 'col-lg-6';
    }
@endphp

@if ($useGrid)
    <div class="{{ $wrapperClass }}">
@endif

@if ($isAssoc)
    <div class="border rounded p-3 mb-3">
        <div class="fw-semibold mb-2">{{ $label }}</div>
        @foreach ($value as $childKey => $childValue)
            @include('vcards.partials.field', [
                'key' => $childKey,
                'value' => $childValue,
                'name' => $name . '[' . $childKey . ']',
                'path' => $path . '-' . $childKey,
            ])
        @endforeach
    </div>
@elseif ($isScalarList)
    <div class="mb-3" data-repeat="list" data-next-index="{{ count($value) }}">
        <label class="form-label">{{ $label }}</label>
        <div class="repeat-items">
            @foreach ($value as $i => $item)
                <div class="d-flex gap-2 mb-2 repeat-item">
                    <input type="text" name="{{ $name }}[{{ $i }}]" class="form-control" value="{{ $item }}">
                    <button type="button" class="btn btn-outline-secondary btn-sm move-up">Up</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm move-down">Down</button>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-row">Remove</button>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary add-row">Add</button>
        <template class="repeat-template">
            <div class="d-flex gap-2 mb-2 repeat-item">
                <input type="text" name="{{ $name }}[__INDEX__]" class="form-control" value="">
                <button type="button" class="btn btn-outline-secondary btn-sm move-up">Up</button>
                <button type="button" class="btn btn-outline-secondary btn-sm move-down">Down</button>
                <button type="button" class="btn btn-outline-danger btn-sm remove-row">Remove</button>
            </div>
        </template>
    </div>
@elseif ($isList)
    @php
        $columns = array_keys($value[0] ?? []);
    @endphp
    <div class="mb-3" data-repeat="table" data-next-index="{{ count($value) }}" data-columns="{{ implode(',', $columns) }}">
        <label class="form-label">{{ $label }}</label>
        <div class="repeat-items">
            @foreach ($value as $i => $row)
                <div class="border rounded p-3 mb-2 repeat-item">
                    <div class="row g-2">
                        @foreach ($columns as $col)
                            <div class="col-md-6">
                                <label class="form-label small">{{ \Illuminate\Support\Str::headline($col) }}</label>
                                <input type="text" name="{{ $name }}[{{ $i }}][{{ $col }}]" class="form-control" value="{{ $row[$col] ?? '' }}">
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex gap-2 mt-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm move-up">Up</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm move-down">Down</button>
                        <button type="button" class="btn btn-outline-danger btn-sm remove-row">Remove</button>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary add-row">Add</button>
        <template class="repeat-template">
            <div class="border rounded p-3 mb-2 repeat-item">
                <div class="row g-2">
                    @foreach ($columns as $col)
                        <div class="col-md-6">
                            <label class="form-label small">{{ \Illuminate\Support\Str::headline($col) }}</label>
                            <input type="text" name="{{ $name }}[__INDEX__][{{ $col }}]" class="form-control" value="">
                        </div>
                    @endforeach
                </div>
                <div class="d-flex gap-2 mt-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm move-up">Up</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm move-down">Down</button>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-row">Remove</button>
                </div>
            </div>
        </template>
    </div>
@else
    <div class="mb-3">
        <label class="form-label">{{ $label }}</label>
        @if ($isImageKey)
            @if (!empty($value))
                <div class="mb-2">
                    <img src="{{ $value }}" alt="" style="max-width: 160px; height: auto;" class="img-thumbnail">
                </div>
            @endif
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
            <input type="file" name="{{ $uploadName }}" class="form-control">
        @elseif ($isTextArea)
            <textarea name="{{ $name }}" class="form-control" rows="3">{{ $value }}</textarea>
        @else
            <input type="{{ $isUrl ? 'url' : ($isPhone ? 'tel' : 'text') }}" name="{{ $name }}" class="form-control" value="{{ $value }}">
        @endif
    </div>
@endif

@if ($useGrid)
    </div>
@endif
