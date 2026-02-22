@php
    $label = $label ?? \Illuminate\Support\Str::headline($key);
    $isArray = is_array($value);
    $isAssoc = $isArray && array_values($value) !== $value;
    $isList = $isArray && !$isAssoc;
    $isScalarList = $isList && (empty($value) || !is_array($value[0] ?? null));
    
    // Enhanced image detection: check key name OR value content
    $isImageKey = false;
    if (is_string($key)) {
        // Check if key name suggests it's an image
        $isImageKey = preg_match('/(image|logo|banner|profile|photo|avatar|icon|bg|thumb|background)/i', $key);
        
        // Also check if value looks like an image URL
        if (!$isImageKey && is_string($value)) {
            $isImageKey = preg_match('/(\.jpg|\.jpeg|\.png|\.gif|\.webp|\.svg)/i', $value) || 
                          preg_match('/url\([\'"]?https?:\/\//i', $value);
        }
    }
    
    $isTextArea = is_string($key) && preg_match('/(description|desc|about|bio|note|tagline|address|subtitle|message)/i', $key);
    $isUrl = is_string($key) && preg_match('/(url|link|website|maps|facebook|instagram|youtube|twitter|whatsapp)/i', $key) && !$isImageKey;
    $isPhone = is_string($key) && preg_match('/(phone|mobile|whatsapp|tel)/i', $key);

    $wirePath = $wirePath ?? $key;
    $uploadPath = 'uploads.' . $wirePath;
    $wrapperClass = ($isAssoc || $isList || $isScalarList) ? 'col-12' : 'col-lg-6';
@endphp

@if ($isAssoc)
    <div class="col-12 mb-4">
        <div class="border rounded-3 p-3" style="background-color: #f8fafc;">
            <div class="fw-semibold mb-3 d-flex align-items-center">
                <i class="mdi mdi-folder-outline me-2 text-primary"></i>
                {{ $label }}
            </div>
            <div class="row">
                @foreach ($value as $childKey => $childValue)
                    @include('livewire.vcards.partials.field', [
                        'key' => $childKey,
                        'value' => $childValue,
                        'wirePath' => $wirePath . '.' . $childKey,
                    ])
                @endforeach
            </div>
        </div>
    </div>
@elseif ($isScalarList)
    <div class="col-12 mb-4">
        <label class="form-label fw-semibold">{{ $label }}</label>
        <div class="d-flex flex-column gap-2">
            @foreach ($value as $i => $item)
                <div class="d-flex gap-2 align-items-center" wire:key="{{ $wirePath }}-{{ $i }}">
                    <input type="text" class="form-control" wire:model="form.{{ $wirePath }}.{{ $i }}">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle p-0" style="width: 32px; height: 32px;" wire:click="moveRow('{{ $wirePath }}', {{ $i }}, -1)" title="Move Up">
                        <i class="mdi mdi-arrow-up"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle p-0" style="width: 32px; height: 32px;" wire:click="moveRow('{{ $wirePath }}', {{ $i }}, 1)" title="Move Down">
                        <i class="mdi mdi-arrow-down"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm rounded-circle p-0" style="width: 32px; height: 32px;" wire:click="removeRow('{{ $wirePath }}', {{ $i }})" title="Remove">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-sm btn-primary mt-2" wire:click="addRow('{{ $wirePath }}')">
            <i class="mdi mdi-plus"></i> Add Item
        </button>
    </div>
@elseif ($isList)
    @php
        $columns = array_keys($value[0] ?? []);
        $itemLabel = \Illuminate\Support\Str::singular($label);
    @endphp
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <label class="form-label fw-semibold mb-0">
                <i class="mdi mdi-format-list-bulleted me-1"></i>
                {{ $label }} ({{ count($value) }} items)
            </label>
            <button type="button" class="btn btn-sm btn-primary" wire:click="addRow('{{ $wirePath }}', @js($columns))">
                <i class="mdi mdi-plus"></i> Add {{ $itemLabel }}
            </button>
        </div>
        
        <div class="d-flex flex-column gap-3">
            @foreach ($value as $i => $row)
                @php
                    $firstValue = $row[array_key_first($row)] ?? '';
                    $displayTitle = is_string($firstValue) ? \Illuminate\Support\Str::limit($firstValue, 30) : "Item #" . ($i + 1);
                @endphp
                <div class="card border shadow-sm" wire:key="{{ $wirePath }}-{{ $i }}" style="border-radius: 8px;">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-2" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#item-{{ $wirePath }}-{{ $i }}" aria-expanded="false">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary">{{ $i + 1 }}</span>
                            <strong class="text-truncate" style="max-width: 300px;">{{ $displayTitle }}</strong>
                        </div>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle p-0" style="width: 28px; height: 28px;" wire:click.stop="moveRow('{{ $wirePath }}', {{ $i }}, -1)" title="Move Up" {{ $i === 0 ? 'disabled' : '' }}>
                                <i class="mdi mdi-arrow-up"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle p-0" style="width: 28px; height: 28px;" wire:click.stop="moveRow('{{ $wirePath }}', {{ $i }}, 1)" title="Move Down" {{ $i === count($value) - 1 ? 'disabled' : '' }}>
                                <i class="mdi mdi-arrow-down"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle p-0" style="width: 28px; height: 28px;" wire:click.stop="removeRow('{{ $wirePath }}', {{ $i }})" title="Delete" onclick="return confirm('Delete this {{ strtolower($itemLabel) }}?')">
                                <i class="mdi mdi-delete"></i>
                            </button>
                            <i class="mdi mdi-chevron-down ms-2"></i>
                        </div>
                    </div>
                    <div class="collapse" id="item-{{ $wirePath }}-{{ $i }}">
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach ($columns as $col)
                                    @php
                                        $colValue = $row[$col] ?? '';
                                        $isColImage = preg_match('/(image|logo|banner|profile|photo|avatar|icon|bg|thumb)/i', $col) || 
                                                      (is_string($colValue) && (preg_match('/(\.jpg|\.jpeg|\.png|\.gif|\.webp|\.svg)/i', $colValue) || 
                                                       preg_match('/url\([\'"]?https?:\/\//i', $colValue)));
                                        $isColTextarea = preg_match('/(description|desc|about|bio|note|details|content)/i', $col);
                                    @endphp
                                    <div class="col-lg-6">
                                        <label class="form-label small fw-semibold">{{ \Illuminate\Support\Str::headline($col) }}</label>
                                        @if ($isColImage)
                                            @if (!empty($colValue))
                                                @php
                                                    $imageUrl = $colValue;
                                                    if (preg_match('/url\([\'"]?(.*?)[\'"]?\)/i', $colValue, $matches)) {
                                                        $imageUrl = $matches[1];
                                                    }
                                                @endphp
                                                <div class="mb-2">
                                                    <img src="{{ $imageUrl }}" alt="" style="max-width: 120px; height: auto;" class="img-thumbnail">
                                                </div>
                                            @endif
                                            <input type="file" class="form-control form-control-sm" wire:model="uploads.{{ $wirePath }}.{{ $i }}.{{ $col }}" accept="image/*">
                                        @elseif ($isColTextarea)
                                            <textarea class="form-control form-control-sm" rows="2" wire:model="form.{{ $wirePath }}.{{ $i }}.{{ $col }}"></textarea>
                                        @else
                                            <input type="text" class="form-control form-control-sm" wire:model="form.{{ $wirePath }}.{{ $i }}.{{ $col }}">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            
            @if (empty($value))
                <div class="text-center py-4 border rounded" style="background-color: #f8fafc;">
                    <i class="mdi mdi-inbox-outline" style="font-size: 48px; color: #cbd5e1;"></i>
                    <p class="text-muted mb-2">No {{ strtolower($label) }} added yet</p>
                    <button type="button" class="btn btn-sm btn-primary" wire:click="addRow('{{ $wirePath }}', @js($columns))">
                        <i class="mdi mdi-plus"></i> Add First {{ $itemLabel }}
                    </button>
                </div>
            @endif
        </div>
    </div>
@else
    <div class="{{ $wrapperClass }} mb-3">
        <label class="form-label fw-semibold">{{ $label }}</label>
        @if ($isImageKey)
            @if (!empty($value))
                @php
                    // Extract URL from url('...') wrapper if present
                    $imageUrl = $value;
                    if (preg_match('/url\([\'"]?(.*?)[\'"]?\)/i', $value, $matches)) {
                        $imageUrl = $matches[1];
                    }
                @endphp
                <div class="mb-2">
                    <img src="{{ $imageUrl }}" alt="" style="max-width: 160px; height: auto;" class="img-thumbnail">
                </div>
            @endif
            <input type="file" class="form-control" wire:model="{{ $uploadPath }}" accept="image/*">
            <small class="form-text text-muted d-block mt-1">
                <i class="mdi mdi-information-outline"></i> Upload a new image to replace the current one
            </small>
        @elseif ($isTextArea)
            <textarea class="form-control" rows="3" wire:model="form.{{ $wirePath }}"></textarea>
        @else
            <input type="{{ $isUrl ? 'url' : ($isPhone ? 'tel' : 'text') }}" class="form-control" wire:model="form.{{ $wirePath }}">
        @endif
    </div>
@endif
