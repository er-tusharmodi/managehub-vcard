{{--
 | jewelry-shop-template/followLinks.blade.php
 | Professional follow-links editor: platform select, auto-fill name, add/delete.
 | Row shape: {type, name, value, action}  — no "url" (Alt URL) field.
--}}
@php
    $platforms = [
        'whatsapp'  => ['label'=>'WhatsApp',   'icon'=>'mdi-whatsapp',           'color'=>'#25D366', 'placeholder'=>'+91 98765 43210'],
        'instagram' => ['label'=>'Instagram',  'icon'=>'mdi-instagram',           'color'=>'#E1306C', 'placeholder'=>'@yourbrand'],
        'facebook'  => ['label'=>'Facebook',   'icon'=>'mdi-facebook',            'color'=>'#1877F2', 'placeholder'=>'Your Page Name'],
        'youtube'   => ['label'=>'YouTube',    'icon'=>'mdi-youtube',             'color'=>'#FF0000', 'placeholder'=>'Channel Name'],
        'pinterest' => ['label'=>'Pinterest',  'icon'=>'mdi-pinterest',           'color'=>'#E60023', 'placeholder'=>'Board / Handle'],
        'twitter'   => ['label'=>'Twitter / X','icon'=>'mdi-twitter',             'color'=>'#1DA1F2', 'placeholder'=>'@handle'],
        'linkedin'  => ['label'=>'LinkedIn',   'icon'=>'mdi-linkedin',            'color'=>'#0A66C2', 'placeholder'=>'Profile / Page'],
        'telegram'  => ['label'=>'Telegram',   'icon'=>'mdi-telegram',            'color'=>'#2AABEE', 'placeholder'=>'@username'],
        'website'   => ['label'=>'Website',    'icon'=>'mdi-web',                 'color'=>'#6366F1', 'placeholder'=>'https://yoursite.com'],
        'email'     => ['label'=>'Email',      'icon'=>'mdi-email-outline',       'color'=>'#F59E0B', 'placeholder'=>'hello@brand.com'],
        'phone'     => ['label'=>'Phone',      'icon'=>'mdi-phone-outline',       'color'=>'#10B981', 'placeholder'=>'+91 98765 43210'],
        'google'    => ['label'=>'Google',     'icon'=>'mdi-google',              'color'=>'#4285F4', 'placeholder'=>'Google Business URL'],
        'snapchat'  => ['label'=>'Snapchat',   'icon'=>'mdi-snapchat',            'color'=>'#FFFC00', 'placeholder'=>'@snapname'],
        'tiktok'    => ['label'=>'TikTok',     'icon'=>'mdi-music-note',          'color'=>'#000000', 'placeholder'=>'@tiktok'],
        'threads'   => ['label'=>'Threads',    'icon'=>'mdi-at',                  'color'=>'#000000', 'placeholder'=>'@threads'],
    ];
    $socialCols = ['type','name','value','action'];
    $links = is_array($form) ? $form : [];
@endphp

{{-- Header --}}
<div class="col-12 mb-3">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            <i class="mdi mdi-link-variant me-1"></i>Follow Links
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ count($links) }}</span>
        </span>
        <button type="button" class="btn btn-sm btn-primary px-3"
                wire:click="addRowAndSave('', {{ json_encode($socialCols) }})">
            <i class="mdi mdi-plus me-1"></i>Add Link
        </button>
    </div>
</div>

@if(!empty($links))
    @foreach($links as $si => $row)
    @if(!is_array($row)) @continue @endif
    @php
        $pType   = $row['type'] ?? '';
        $pMeta   = $platforms[$pType] ?? ['label'=>$pType,'icon'=>'mdi-link','color'=>'#64748B','placeholder'=>'Value'];
        $pColor  = $pMeta['color'];
        $pIcon   = $pMeta['icon'];
    @endphp
    <div class="col-12 mb-2" wire:key="fl-row-{{ $si }}">
        <div class="border rounded-3 overflow-hidden" style="background:#fff;">

            {{-- Colored platform header bar --}}
            <div class="d-flex align-items-center px-3 py-2 gap-2"
                 style="background: {{ $pColor }}18; border-bottom:1px solid {{ $pColor }}33;">
                <span class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                      style="width:30px;height:30px;background:{{ $pColor }}22;">
                    <i class="mdi {{ $pIcon }}" style="color:{{ $pColor }};font-size:15px;"></i>
                </span>
                <span class="fw-semibold small" style="color:{{ $pColor }};">
                    {{ $pMeta['label'] }}
                    @if(!empty($row['name']) && $row['name'] !== $pMeta['label'])
                        <span class="text-muted fw-normal">— {{ $row['name'] }}</span>
                    @endif
                </span>
                <button type="button"
                        class="btn btn-sm btn-outline-danger p-0 rounded-circle ms-auto flex-shrink-0"
                        style="width:26px;height:26px;border-color:{{ $pColor }}44;"
                        wire:click="removeRow('', {{ $si }})"
                        wire:confirm="Remove this link?">
                    <i class="mdi mdi-delete" style="font-size:11px;"></i>
                </button>
            </div>

            {{-- Fields --}}
            <div class="row g-3 p-3">

                {{-- Platform --}}
                <div class="col-sm-3">
                    <label class="form-label small fw-semibold mb-1 text-muted">Platform</label>
                    <select class="form-select form-select-sm"
                            wire:model="form.{{ $si }}.type"
                            x-on:change="
                                const map = {{ json_encode(array_map(fn($v)=>$v['label'],$platforms)) }};
                                const name = map[$event.target.value];
                                if (name) $wire.set('form.{{ $si }}.name', name);
                            ">
                        <option value="">— Select —</option>
                        @foreach($platforms as $pKey => $pData)
                            <option value="{{ $pKey }}" {{ ($row['type'] ?? '') === $pKey ? 'selected' : '' }}>
                                {{ $pData['label'] }}
                            </option>
                        @endforeach
                        <option value="other" {{ !isset($platforms[$row['type'] ?? '']) && !empty($row['type'] ?? '') ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                {{-- Display Name --}}
                <div class="col-sm-3">
                    <label class="form-label small fw-semibold mb-1 text-muted">Display Name</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.{{ $si }}.name"
                           placeholder="{{ $pMeta['label'] }}">
                </div>

                {{-- Value --}}
                <div class="col-sm-3">
                    <label class="form-label small fw-semibold mb-1 text-muted">Handle / Phone / Username</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.{{ $si }}.value"
                           placeholder="{{ $pMeta['placeholder'] }}">
                </div>

                {{-- Action URL --}}
                <div class="col-sm-3">
                    <label class="form-label small fw-semibold mb-1 text-muted">Action URL</label>
                    <input type="url" class="form-control form-control-sm"
                           wire:model="form.{{ $si }}.action"
                           placeholder="https://wa.me/91…">
                </div>

            </div>
        </div>
    </div>
    @endforeach

@else

    <div class="col-12">
        <div class="text-center py-5 rounded-3"
             style="background:linear-gradient(135deg,#fdf6f0,#fef9f3);border:2px dashed #d4af80;">
            <i class="mdi mdi-link-variant-plus fs-1 mb-2 d-block" style="color:#d4af80;"></i>
            <p class="fw-semibold mb-2 small" style="color:#9a7a52;">No follow links added yet</p>
            <button type="button" class="btn btn-sm px-4"
                    style="background:#d4af80;color:#fff;border:none;"
                    wire:click="addRowAndSave('', {{ json_encode($socialCols) }})">
                <i class="mdi mdi-plus me-1"></i>Add First Link
            </button>
        </div>
    </div>

@endif
