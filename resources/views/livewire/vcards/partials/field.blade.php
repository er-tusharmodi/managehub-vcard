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
                    <button type="button" class="btn btn-outline-danger btn-sm rounded-circle p-0" style="width: 32px; height: 32px;" wire:click="removeRow('{{ $wirePath }}', {{ $i }})" wire:confirm="Are you sure you want to delete this item?" title="Remove">
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
        
        // Detect image columns
        $imageColumns = array_filter($columns, function($col) {
            return preg_match('/(image|logo|banner|profile|photo|avatar|icon|bg|thumb|picture)/i', $col);
        });
        
        // Filter out tag and color columns
        $excludeColumns = array_filter($columns, function($col) {
            return preg_match('/(tag|color|id)/i', $col);
        });
        
        // Get non-image and non-excluded columns for table
        $displayColumns = array_diff($columns, $imageColumns, $excludeColumns);
    @endphp
    
    <div class="col-12 mb-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
            <div>
                <h6 class="mb-1 fw-semibold">
                    <i class="mdi mdi-table me-2"></i>{{ $label }}
                </h6>
                <p class="text-muted small mb-0">{{ count($value) }} {{ count($value) === 1 ? $itemLabel : strtolower($label) }} added</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal{{ $wirePath }}">
                <i class="mdi mdi-plus me-1"></i> Add {{ $itemLabel }}
            </button>
        </div>

        @if (empty($value))
            <!-- Empty State -->
            <div class="text-center py-5" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 12px; border: 2px dashed #cbd5e1;">
                <div class="mb-3">
                    <i class="mdi mdi-table-outline" style="font-size: 48px; color: #94a3b8;"></i>
                </div>
                <p class="fw-semibold text-muted mb-2">No {{ strtolower($label) }} Added</p>
                <p class="text-muted small mb-3">Start by adding your first {{ strtolower($itemLabel) }}</p>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal{{ $wirePath }}">
                    <i class="mdi mdi-plus"></i> Add First {{ $itemLabel }}
                </button>
            </div>
        @else
            <!-- Table -->
            <div class="table-responsive" style="border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <tr>
                            @foreach ($displayColumns as $col)
                                @php
                                    $colLabel = \Illuminate\Support\Str::headline($col);
                                    $isColTextarea = preg_match('/(description|desc|about|bio|note|details|content)/i', $col);
                                @endphp
                                <th style="font-weight: 600; color: #475569; font-size: 0.85rem; padding: 12px 16px; min-width: {{ $isColTextarea ? '150px' : '120px' }};">
                                    {{ $colLabel }}
                                </th>
                            @endforeach
                            <th style="width: 120px; font-weight: 600; color: #475569; font-size: 0.85rem; padding: 12px 16px; text-align: center;">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($value as $i => $row)
                            @php
                                $displayTitle = "Item #" . ($i + 1);
                                $namePriority = ['name', 'title', 'product_name', 'service_name', 'category_name', 'item_name', 'label', 'brand'];
                                
                                foreach ($namePriority as $nameField) {
                                    if (isset($row[$nameField]) && is_string($row[$nameField]) && !empty(trim($row[$nameField]))) {
                                        $displayTitle = trim($row[$nameField]);
                                        break;
                                    }
                                }
                            @endphp
                            <tr wire:key="{{ $wirePath }}-{{ $i }}" style="border-bottom: 1px solid #e2e8f0;">
                                @foreach ($displayColumns as $col)
                                    @php
                                        $colValue = $row[$col] ?? '';
                                        $isColTextarea = preg_match('/(description|desc|about|bio|note|details|content)/i', $col);
                                    @endphp
                                    <td style="padding: 12px 16px; max-width: 300px;">
                                        <span class="form-control-plaintext" style="font-size: 0.85rem;">
                                            {{ !empty($colValue) ? (strlen($colValue) > 50 ? substr($colValue, 0, 50) . '...' : $colValue) : '—' }}
                                        </span>
                                    </td>
                                @endforeach
                                <td style="padding: 12px 16px; text-align: center;">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <!-- View/Edit Modal Button -->
                                        <button type="button" class="btn btn-sm btn-outline-info rounded-circle p-0" style="width: 32px; height: 32px;" data-bs-toggle="modal" data-bs-target="#editModal{{ $wirePath }}{{ $i }}" title="Edit All Fields">
                                            <i class="mdi mdi-pencil" style="font-size: 14px;"></i>
                                        </button>
                                        
                                        <!-- Move Up -->
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle p-0" style="width: 32px; height: 32px;" wire:click.stop="moveRow('{{ $wirePath }}', {{ $i }}, -1)" title="Move Up" {{ $i === 0 ? 'disabled' : '' }}>
                                            <i class="mdi mdi-arrow-up" style="font-size: 14px;"></i>
                                        </button>
                                        
                                        <!-- Move Down -->
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle p-0" style="width: 32px; height: 32px;" wire:click.stop="moveRow('{{ $wirePath }}', {{ $i }}, 1)" title="Move Down" {{ $i === count($value) - 1 ? 'disabled' : '' }}>
                                            <i class="mdi mdi-arrow-down" style="font-size: 14px;"></i>
                                        </button>
                                        
                                        <!-- Delete -->
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-circle p-0" style="width: 32px; height: 32px;" wire:click="removeRowWithConfirm('{{ $wirePath }}', {{ $i }})" wire:confirm="Are you sure you want to delete this item? This action cannot be undone." title="Delete">
                                            <i class="mdi mdi-delete" style="font-size: 14px;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Edit Modal for each row -->
            @foreach ($value as $i => $row)
                @php
                    $displayTitle = "Item #" . ($i + 1);
                    $namePriority = ['name', 'title', 'product_name', 'service_name', 'category_name', 'item_name', 'label', 'brand'];
                    
                    foreach ($namePriority as $nameField) {
                        if (isset($row[$nameField]) && is_string($row[$nameField]) && !empty(trim($row[$nameField]))) {
                            $displayTitle = trim($row[$nameField]);
                            break;
                        }
                    }
                @endphp
                <div class="modal fade" id="editModal{{ $wirePath }}{{ $i }}" tabindex="-1" aria-labelledby="editLabel{{ $wirePath }}{{ $i }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header" style="border-bottom: 1px solid #e2e8f0; background-color: #f8fafc;">
                                <h5 class="modal-title fw-semibold" id="editLabel{{ $wirePath }}{{ $i }}">
                                    <i class="mdi mdi-pencil me-2"></i>Edit {{ \Illuminate\Support\Str::limit($displayTitle, 35) }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="row g-3">
                                    @foreach ($columns as $col)
                                        @php
                                            // Skip id field
                                            if (preg_match('/^id$/i', $col)) continue;
                                            
                                            $colValue = $row[$col] ?? '';
                                            $isColImage = preg_match('/(image|logo|banner|profile|photo|avatar|icon|bg|thumb|picture)/i', $col) || 
                                                          (is_string($colValue) && (preg_match('/(\.jpg|\.jpeg|\.png|\.gif|\.webp|\.svg)/i', $colValue) || 
                                                           preg_match('/url\([\'"]?https?:\/\//i', $colValue)));
                                            $isColTextarea = preg_match('/(description|desc|about|bio|note|details|content)/i', $col);
                                            $isColColor = preg_match('/(tag_color|color|bg_color|background_color)/i', $col);
                                            $modalModelPath = empty($wirePath) ? "form.{$i}.{$col}" : "form.{$wirePath}.{$i}.{$col}";
                                            $modalUploadPath = empty($wirePath) ? "uploads.{$i}.{$col}" : "uploads.{$wirePath}.{$i}.{$col}";
                                        @endphp
                                        
                                        <div class="col-12">
                                            <label class="form-label fw-semibold" style="font-size: 0.9rem;">
                                                {{ \Illuminate\Support\Str::headline($col) }}
                                            </label>
                                            @if ($isColImage)
                                                @if (!empty($colValue))
                                                    @php
                                                        $imageUrl = $colValue;
                                                        if (preg_match('/url\([\'"]?(.*?)[\'"]?\)/i', $colValue, $matches)) {
                                                            $imageUrl = $matches[1];
                                                        }
                                                    @endphp
                                                    <div class="mb-2">
                                                        <img src="{{ $imageUrl }}" alt="" style="max-width: 100%; max-height: 150px; border-radius: 8px; border: 1px solid #e2e8f0;">
                                                    </div>
                                                @endif
                                                <input type="file" class="form-control" wire:model.live="{{ $modalUploadPath }}" accept="image/*">
                                                <small class="text-muted d-block mt-1">Upload image (JPG, PNG, GIF)</small>
                                            @elseif ($isColColor)
                                                <div class="d-flex gap-2 align-items-center">
                                                    <input type="color" class="form-control form-control-color" wire:model="{{ $modalModelPath }}" style="width: 60px; height: 38px;">
                                                    <input type="text" class="form-control" wire:model="{{ $modalModelPath }}" placeholder="#000000">
                                                </div>
                                            @elseif ($isColTextarea)
                                                <textarea class="form-control" rows="4" wire:model="{{ $modalModelPath }}"></textarea>
                                            @else
                                                <input type="text" class="form-control" wire:model="{{ $modalModelPath }}">
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="modal-footer" style="border-top: 1px solid #e2e8f0;">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                                    <i class="mdi mdi-check me-1"></i>Done
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Add New Item Modal -->
        <div class="modal fade" id="addModal{{ $wirePath }}" tabindex="-1" aria-labelledby="addLabel{{ $wirePath }}" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header" style="border-bottom: 1px solid #e2e8f0; background-color: #f8fafc;">
                        <h5 class="modal-title fw-semibold" id="addLabel{{ $wirePath }}">
                            <i class="mdi mdi-plus-circle me-2"></i>Add New {{ $itemLabel }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            @foreach ($columns as $col)
                                @php
                                    // Skip id field
                                    if (preg_match('/^id$/i', $col)) continue;
                                    
                                    $isColImage = preg_match('/(image|logo|banner|profile|photo|avatar|icon|bg|thumb|picture)/i', $col);
                                    $isColTextarea = preg_match('/(description|desc|about|bio|note|details|content)/i', $col);
                                    $isColColor = preg_match('/(tag_color|color|bg_color|background_color)/i', $col);
                                @endphp
                                
                                <div class="col-12">
                                    <label class="form-label fw-semibold" style="font-size: 0.9rem;">
                                        {{ \Illuminate\Support\Str::headline($col) }}
                                    </label>
                                    @if ($isColImage)
                                        <input type="file" class="form-control" accept="image/*">
                                        <small class="text-muted d-block mt-1">Upload image (JPG, PNG, GIF)</small>
                                    @elseif ($isColColor)
                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="color" class="form-control form-control-color" value="#000000" style="width: 60px; height: 38px;">
                                            <input type="text" class="form-control" placeholder="#000000">
                                        </div>
                                    @elseif ($isColTextarea)
                                        <textarea class="form-control" rows="4"></textarea>
                                    @else
                                        <input type="text" class="form-control">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #e2e8f0;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" wire:click="addRow('{{ $wirePath }}', @js($columns))" data-bs-dismiss="modal">
                            <i class="mdi mdi-check me-1"></i>Add {{ $itemLabel }}
                        </button>
                    </div>
                </div>
            </div>
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
