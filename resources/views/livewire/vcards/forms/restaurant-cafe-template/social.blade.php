{{-- restaurant-cafe-template/social.blade.php --}}
{{-- Row shape: {type, name, value, action, url?} --}}
{{-- action = "url"|"wa"|"call"|"email" (action TYPE, not the URL itself) --}}
@php
    $socialPlatforms = [
        'whatsapp'  => ['label' => 'WhatsApp',       'color' => '#25D366'],
        'instagram' => ['label' => 'Instagram',      'color' => '#E1306C'],
        'facebook'  => ['label' => 'Facebook',       'color' => '#1877F2'],
        'youtube'   => ['label' => 'YouTube',        'color' => '#FF0000'],
        'twitter'   => ['label' => 'Twitter / X',    'color' => '#1DA1F2'],
        'linkedin'  => ['label' => 'LinkedIn',       'color' => '#0A66C2'],
        'telegram'  => ['label' => 'Telegram',       'color' => '#26A5E4'],
        'tiktok'    => ['label' => 'TikTok',         'color' => '#000000'],
        'pinterest' => ['label' => 'Pinterest',      'color' => '#BD081C'],
        'snapchat'  => ['label' => 'Snapchat',       'color' => '#FFFC00'],
        'threads'   => ['label' => 'Threads',        'color' => '#000000'],
        'website'   => ['label' => 'Website',        'color' => '#6366f1'],
        'email'     => ['label' => 'Email',          'color' => '#ea4335'],
        'phone'     => ['label' => 'Phone',          'color' => '#16a34a'],
        'google'    => ['label' => 'Google',         'color' => '#4285F4'],
    ];
    $platformLabels = array_map(fn($v) => $v['label'], $socialPlatforms);
    $socialRows     = is_array($form) ? $form : [];
    $addCols        = ['type','name','value','action','url'];
@endphp

<div class="col-12 mb-2">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-2">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            <i class="mdi mdi-share-variant-outline me-1"></i>Social Links
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ count($socialRows) }}</span>
        </span>
        <button type="button" class="btn btn-sm btn-primary px-3"
                wire:click="addRowAndSave('', {{ json_encode($addCols) }})">
            <i class="mdi mdi-plus me-1"></i>Add Link
        </button>
    </div>
</div>

@if(count($socialRows) > 0)
<div class="col-12" data-sort-path="">
@foreach($socialRows as $si => $row)
@if(!is_array($row)) @continue @endif
@php
    $platform    = $socialPlatforms[$row['type'] ?? ''] ?? null;
    $headerColor = $platform ? $platform['color'] : '#6c757d';
    $actionType  = $row['action'] ?? 'url';
    $mdiIconMap  = [
        'whatsapp'  => 'mdi-whatsapp',
        'instagram' => 'mdi-instagram',
        'facebook'  => 'mdi-facebook',
        'youtube'   => 'mdi-youtube',
        'twitter'   => 'mdi-twitter',
        'linkedin'  => 'mdi-linkedin',
        'telegram'  => 'mdi-telegram',
        'tiktok'    => 'mdi-music-note',
        'pinterest' => 'mdi-pinterest',
        'snapchat'  => 'mdi-snapchat',
        'threads'   => 'mdi-at',
        'website'   => 'mdi-web',
        'email'     => 'mdi-email',
        'phone'     => 'mdi-phone',
        'google'    => 'mdi-google',
    ];
    $socialIconClass = 'mdi ' . ($mdiIconMap[$row['type'] ?? ''] ?? 'mdi-link-variant');
@endphp

<div class="col-12 mb-2" wire:key="rcsoc-{{ $si }}">
    <div class="border rounded-3 overflow-hidden">

        {{-- Colored header bar --}}
        <div class="d-flex align-items-center justify-content-between px-3 py-2"
             style="background:{{ $headerColor }}1a; border-bottom:2px solid {{ $headerColor }};">
            <div class="d-flex align-items-center gap-2">
                <i class="mdi mdi-drag-vertical drag-handle text-muted me-1" style="font-size:1.1rem;cursor:grab;"></i>
                <i class="{{ $socialIconClass }}" style="font-size:1.1rem;color:{{ $headerColor }};"></i>
                <span class="fw-semibold" style="color:{{ $headerColor }};font-size:.8rem;">
                    {{ $platform ? $platform['label'] : ($row['type'] ?? 'Link') }}
                </span>
                @if(!empty($row['name']))
                <span class="text-muted" style="font-size:.75rem;">· {{ $row['name'] }}</span>
                @endif
            </div>
            <button type="button"
                    class="btn btn-sm p-0 rounded-circle d-flex align-items-center justify-content-center"
                    style="width:26px;height:26px;background:{{ $headerColor }}33;border:none;"
                    x-on:click="showConfirmToast('Delete this social link?', () => $wire.removeRowWithConfirm({{ $si }}, ''))">
                <i class="mdi mdi-close" style="font-size:13px;color:{{ $headerColor }};"></i>
            </button>
        </div>

        {{-- Fields --}}
        <div class="p-3" style="background:#fafafa;">
            <div class="row g-2 align-items-end">

                {{-- Platform --}}
                <div class="col-sm-3 col-md-2">
                    <label class="form-label small fw-semibold mb-1">Platform</label>
                    <select class="form-select form-select-sm"
                            wire:model="form.{{ $si }}.type"
                            x-on:change="
                                const map = {{ json_encode($platformLabels) }};
                                const name = map[$event.target.value];
                                if (name) $wire.set('form.{{ $si }}.name', name);
                            ">
                        <option value="">— select —</option>
                        @foreach($socialPlatforms as $pKey => $pInfo)
                            <option value="{{ $pKey }}" {{ ($row['type'] ?? '') === $pKey ? 'selected' : '' }}>
                                {{ $pInfo['label'] }}
                            </option>
                        @endforeach
                        <option value="other" {{ !isset($socialPlatforms[$row['type'] ?? '']) && !empty($row['type'] ?? '') ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                {{-- Display Name --}}
                <div class="col-sm-3 col-md-2">
                    <label class="form-label small fw-semibold mb-1">Display Name</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.{{ $si }}.name"
                           placeholder="WhatsApp Orders">
                </div>

                {{-- Subtitle / value --}}
                <div class="col-sm-6 col-md-4">
                    <label class="form-label small fw-semibold mb-1">Subtitle Text</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.{{ $si }}.value"
                           placeholder="@handle or +91… or follower count">
                </div>

                {{-- Action Type --}}
                <div class="col-sm-3 col-md-2">
                    <label class="form-label small fw-semibold mb-1">Action Type</label>
                    <select class="form-select form-select-sm"
                            wire:model="form.{{ $si }}.action">
                        <option value="url" {{ $actionType === 'url'   ? 'selected' : '' }}>Open URL</option>
                        <option value="wa"  {{ $actionType === 'wa'    ? 'selected' : '' }}>WhatsApp</option>
                        <option value="call"{{ $actionType === 'call'  ? 'selected' : '' }}>Phone Call</option>
                        <option value="email"{{ $actionType==='email' ? 'selected' : '' }}>Email</option>
                    </select>
                </div>

                {{-- URL --}}
                <div class="col-sm-9 col-md-2">
                    <label class="form-label small fw-semibold mb-1">URL / Number</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.{{ $si }}.url"
                           placeholder="https://… or +91XXXXXXXXXX">
                </div>

            </div>
        </div>
    </div>
</div>
@endforeach
</div>{{-- /data-sort-path --}}
@else
<div class="col-12">
    <div class="text-center py-5 rounded-3"
         style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);border:2px dashed #cbd5e1;">
        <i class="mdi mdi-share-variant-outline fs-1 text-muted mb-2 d-block"></i>
        <p class="fw-semibold text-muted mb-2 small">No social links yet</p>
        <button type="button" class="btn btn-sm btn-primary"
                wire:click="addRowAndSave('', {{ json_encode($addCols) }})">
            <i class="mdi mdi-plus me-1"></i>Add First Link
        </button>
    </div>
</div>
@endif
