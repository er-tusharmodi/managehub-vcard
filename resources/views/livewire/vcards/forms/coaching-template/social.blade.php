{{-- coaching-template/social.blade.php --}}
{{-- Row shape: {iconClass, label, value, action, url} --}}
@php
    $iconOptions = [
        'ic-wa'  => ['label' => 'WhatsApp',  'bi' => 'bi-whatsapp'],
        'ic-yt'  => ['label' => 'YouTube',   'bi' => 'bi-youtube'],
        'ic-tg'  => ['label' => 'Telegram',  'bi' => 'bi-telegram'],
        'ic-ig'  => ['label' => 'Instagram', 'bi' => 'bi-instagram'],
        'ic-fb'  => ['label' => 'Facebook',  'bi' => 'bi-facebook'],
        'ic-web' => ['label' => 'Website',   'bi' => 'bi-globe'],
        'ic-tw'  => ['label' => 'Twitter/X', 'bi' => 'bi-twitter-x'],
        'ic-li'  => ['label' => 'LinkedIn',  'bi' => 'bi-linkedin'],
    ];
    $socialRows = is_array($form['items'] ?? null) ? $form['items'] : [];
    $addCols    = ['iconClass', 'label', 'value', 'action', 'url'];

    $iconColors = [
        'ic-wa'  => '#25D366',
        'ic-yt'  => '#FF0000',
        'ic-tg'  => '#26A5E4',
        'ic-ig'  => '#E1306C',
        'ic-fb'  => '#1877F2',
        'ic-web' => '#6366f1',
        'ic-tw'  => '#1DA1F2',
        'ic-li'  => '#0A66C2',
    ];
@endphp

<div class="col-12 mb-2">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-2">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            <i class="mdi mdi-share-variant-outline me-1"></i>Social Links
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ count($socialRows) }}</span>
        </span>
        <button type="button" class="btn btn-sm btn-primary px-3"
                wire:click="addRowAndSave('items', {{ json_encode($addCols) }})">
            <i class="mdi mdi-plus me-1"></i>Add Link
        </button>
    </div>
</div>

@forelse($socialRows as $si => $row)
@if(!is_array($row)) @continue @endif
@php
    $iconKey   = $row['iconClass'] ?? '';
    $iconInfo  = $iconOptions[$iconKey] ?? null;
    $color     = $iconColors[$iconKey] ?? '#6c757d';
    $actionVal = $row['action'] ?? '';
@endphp

<div class="col-12 mb-2" wire:key="coacsoc-{{ $si }}">
    <div class="border rounded-3 overflow-hidden">

        {{-- Colored header bar --}}
        <div class="d-flex align-items-center justify-content-between px-3 py-2"
             style="background:{{ $color }}1a; border-bottom:2px solid {{ $color }};">
            <div class="d-flex align-items-center gap-2">
                @if($iconInfo)
                    <i class="bi {{ $iconInfo['bi'] }}" style="color:{{ $color }};font-size:.95rem;"></i>
                @endif
                <span class="fw-semibold" style="color:{{ $color }};font-size:.8rem;">
                    {{ $iconInfo ? $iconInfo['label'] : ($iconKey ?: 'Link') }}
                </span>
                @if(!empty($row['label']))
                    <span class="text-muted" style="font-size:.75rem;">· {{ $row['label'] }}</span>
                @endif
            </div>
            <button type="button"
                    class="btn btn-sm p-0 rounded-circle d-flex align-items-center justify-content-center"
                    style="width:26px;height:26px;background:{{ $color }}33;border:none;"
                    x-on:click="showConfirmToast('Delete this social link?', () => $wire.removeRow('items', {{ $si }}))">
                <i class="mdi mdi-close" style="font-size:13px;color:{{ $color }};"></i>
            </button>
        </div>

        {{-- Fields --}}
        <div class="p-3" style="background:#fafafa;">
            <div class="row g-2 align-items-end">

                {{-- Platform Icon --}}
                <div class="col-sm-3 col-md-2">
                    <label class="form-label small fw-semibold mb-1">Platform</label>
                    <select class="form-select form-select-sm"
                            wire:model="form.items.{{ $si }}.iconClass">
                        <option value="">— select —</option>
                        @foreach($iconOptions as $iKey => $iInfo)
                            <option value="{{ $iKey }}" {{ $iconKey === $iKey ? 'selected' : '' }}>
                                {{ $iInfo['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Display Label --}}
                <div class="col-sm-3 col-md-2">
                    <label class="form-label small fw-semibold mb-1">Display Name</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.items.{{ $si }}.label"
                           placeholder="WhatsApp Channel">
                </div>

                {{-- Subtitle --}}
                <div class="col-sm-6 col-md-4">
                    <label class="form-label small fw-semibold mb-1">Subtitle Text</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.items.{{ $si }}.value"
                           placeholder="Daily updates · 1.2 Lakh members">
                </div>

                {{-- Action Type --}}
                <div class="col-sm-3 col-md-2">
                    <label class="form-label small fw-semibold mb-1">Action</label>
                    <select class="form-select form-select-sm"
                            wire:model="form.items.{{ $si }}.action">
                        <option value="" {{ $actionVal === '' ? 'selected' : '' }}>Open URL</option>
                        <option value="openWA" {{ $actionVal === 'openWA' ? 'selected' : '' }}>WhatsApp</option>
                        <option value="callInstitute" {{ $actionVal === 'callInstitute' ? 'selected' : '' }}>Phone Call</option>
                        <option value="mailInstitute" {{ $actionVal === 'mailInstitute' ? 'selected' : '' }}>Email</option>
                    </select>
                </div>

                {{-- URL --}}
                <div class="col-sm-9 col-md-2">
                    <label class="form-label small fw-semibold mb-1">URL</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.items.{{ $si }}.url"
                           placeholder="https://…">
                </div>

            </div>
        </div>
    </div>
</div>
@empty
<div class="col-12">
    <div class="text-center py-5 rounded-3"
         style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);border:2px dashed #cbd5e1;">
        <i class="mdi mdi-share-variant-outline fs-1 text-muted mb-2 d-block"></i>
        <p class="fw-semibold text-muted mb-2 small">No social links yet</p>
        <button type="button" class="btn btn-sm btn-primary"
                wire:click="addRowAndSave('items', {{ json_encode($addCols) }})">
            <i class="mdi mdi-plus me-1"></i>Add First Link
        </button>
    </div>
</div>
@endforelse
