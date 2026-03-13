{{-- restaurant-cafe-template/social.blade.php --}}
{{-- Row shape: {type, name, value, action, url?} --}}
{{-- action = "url"|"wa"|"call"|"email" (action TYPE, not the URL itself) --}}
@php
    $socialPlatforms = [
        'whatsapp'  => ['label' => 'WhatsApp',    'color' => '#25D366', 'icon' => 'mdi-whatsapp'],
        'instagram' => ['label' => 'Instagram',   'color' => '#E1306C', 'icon' => 'mdi-instagram'],
        'facebook'  => ['label' => 'Facebook',    'color' => '#1877F2', 'icon' => 'mdi-facebook'],
        'youtube'   => ['label' => 'YouTube',     'color' => '#FF0000', 'icon' => 'mdi-youtube'],
        'twitter'   => ['label' => 'Twitter / X', 'color' => '#1DA1F2', 'icon' => 'mdi-twitter'],
        'linkedin'  => ['label' => 'LinkedIn',    'color' => '#0A66C2', 'icon' => 'mdi-linkedin'],
        'telegram'  => ['label' => 'Telegram',    'color' => '#26A5E4', 'icon' => 'mdi-telegram'],
        'tiktok'    => ['label' => 'TikTok',      'color' => '#010101', 'icon' => 'mdi-music-note'],
        'pinterest' => ['label' => 'Pinterest',   'color' => '#BD081C', 'icon' => 'mdi-pinterest'],
        'snapchat'  => ['label' => 'Snapchat',    'color' => '#FFFC00', 'icon' => 'mdi-snapchat'],
        'threads'   => ['label' => 'Threads',     'color' => '#010101', 'icon' => 'mdi-at'],
        'website'   => ['label' => 'Website',     'color' => '#6366f1', 'icon' => 'mdi-web'],
        'email'     => ['label' => 'Email',       'color' => '#ea4335', 'icon' => 'mdi-email'],
        'phone'     => ['label' => 'Phone',       'color' => '#16a34a', 'icon' => 'mdi-phone'],
        'google'    => ['label' => 'Google',      'color' => '#4285F4', 'icon' => 'mdi-google'],
    ];
    $socialRows = is_array($form) ? $form : [];
    $addCols    = ['type','name','value','action','url'];
    // Build a JS-safe map for Alpine reactive header
    $jsPlatforms = [];
    foreach ($socialPlatforms as $k => $v) {
        $jsPlatforms[$k] = ['label' => $v['label'], 'color' => $v['color'], 'icon' => $v['icon']];
    }
@endphp

{{-- ── Section header ── --}}
<div class="col-12 mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
                <i class="mdi mdi-share-variant-outline me-1"></i>Social Links
                <span class="badge bg-secondary-subtle text-secondary ms-1">{{ count($socialRows) }}</span>
            </h6>
            <small class="text-muted" style="font-size:.72rem;">Add platforms where customers can follow or contact you.</small>
        </div>
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
    $rowType   = $row['type'] ?? '';
    $rowAction = $row['action'] ?? 'url';
@endphp
{{-- Each card is an Alpine component for reactive header color/icon --}}
<div class="col-12 mb-3" wire:key="rcsoc-{{ $si }}"
     x-data="{
         platforms: {{ Js::from($jsPlatforms) }},
         type: '{{ $rowType }}',
         get info() { return this.platforms[this.type] || { label: this.type || 'Link', color: '#6c757d', icon: 'mdi-link-variant' }; }
     }">
    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius:.85rem;">

        {{-- ── Coloured header strip ── --}}
        <div class="px-3 py-2 d-flex align-items-center justify-content-between"
             :style="'background:' + info.color + '18; border-bottom: 2px solid ' + info.color">
            <div class="d-flex align-items-center gap-2">
                <i class="mdi mdi-drag-vertical drag-handle text-muted" style="font-size:1.1rem;cursor:grab;"></i>
                <span class="rounded-circle d-inline-flex align-items-center justify-content-center flex-shrink-0"
                      :style="'background:' + info.color + '; width:30px;height:30px;'">
                    <i class="mdi" :class="info.icon" style="font-size:.95rem;color:#fff;"></i>
                </span>
                <div>
                    <span class="fw-semibold" :style="'color:' + info.color + ';font-size:.82rem;'" x-text="info.label"></span>
                    @if(!empty($row['name']))
                        <span class="text-muted" style="font-size:.75rem;"> · {{ $row['name'] }}</span>
                    @endif
                </div>
            </div>
            <button type="button"
                    class="btn btn-sm p-0 rounded-circle d-flex align-items-center justify-content-center"
                    style="width:28px;height:28px;"
                    :style="'background:' + info.color + '22;border:none;'"
                    x-on:click="showConfirmToast('Delete this social link?', () => $wire.removeRowWithConfirm({{ $si }}, ''))">
                <i class="mdi mdi-close" :style="'font-size:13px;color:' + info.color"></i>
            </button>
        </div>

        {{-- ── Fields ── --}}
        <div class="card-body p-3 bg-white">
            <div class="row g-2 align-items-end">

                {{-- Platform --}}
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small fw-semibold mb-1">
                        <i class="mdi mdi-apps me-1 text-muted"></i>Platform
                    </label>
                    <select class="form-select form-select-sm"
                            wire:model.live="form.{{ $si }}.type"
                            x-model="type"
                            @change="
                                const p = platforms[$event.target.value];
                                if (p) { $wire.set('form.{{ $si }}.name', p.label, false); }
                            ">
                        <option value="">— Select platform —</option>
                        @foreach($socialPlatforms as $pKey => $pInfo)
                            <option value="{{ $pKey }}" {{ $rowType === $pKey ? 'selected' : '' }}>
                                {{ $pInfo['label'] }}
                            </option>
                        @endforeach
                        <option value="other" {{ !isset($socialPlatforms[$rowType]) && $rowType !== '' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                {{-- Display Name --}}
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small fw-semibold mb-1">
                        <i class="mdi mdi-tag-outline me-1 text-muted"></i>Display Name
                    </label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.{{ $si }}.name"
                           placeholder="e.g. WhatsApp Orders">
                </div>

                {{-- Subtitle / handle --}}
                <div class="col-12 col-sm-6 col-md-2">
                    <label class="form-label small fw-semibold mb-1">
                        <i class="mdi mdi-subtitles-outline me-1 text-muted"></i>Subtitle
                    </label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.{{ $si }}.value"
                           placeholder="@handle or follower count">
                </div>

                {{-- Action Type --}}
                <div class="col-6 col-sm-3 col-md-2">
                    <label class="form-label small fw-semibold mb-1">
                        <i class="mdi mdi-cursor-default-click-outline me-1 text-muted"></i>On Click
                    </label>
                    <select class="form-select form-select-sm"
                            wire:model="form.{{ $si }}.action">
                        <option value="url"  {{ $rowAction === 'url'   ? 'selected' : '' }}>Open URL</option>
                        <option value="wa"   {{ $rowAction === 'wa'    ? 'selected' : '' }}>WhatsApp</option>
                        <option value="call" {{ $rowAction === 'call'  ? 'selected' : '' }}>Phone Call</option>
                        <option value="email"{{ $rowAction === 'email' ? 'selected' : '' }}>Email</option>
                    </select>
                </div>

                {{-- URL / Number --}}
                <div class="col-6 col-sm-9 col-md-2">
                    <label class="form-label small fw-semibold mb-1">
                        <i class="mdi mdi-link-variant me-1 text-muted"></i>URL / Number
                    </label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.{{ $si }}.url"
                           placeholder="https://… or +91…">
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
        <i class="mdi mdi-share-variant-outline d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.4;"></i>
        <p class="fw-semibold text-muted mb-1 small">No social links added yet</p>
        <p class="text-muted mb-3" style="font-size:.78rem;">Add WhatsApp, Instagram, Facebook &amp; more to let customers reach you.</p>
        <button type="button" class="btn btn-sm btn-primary px-4"
                wire:click="addRowAndSave('', {{ json_encode($addCols) }})">
            <i class="mdi mdi-plus me-1"></i>Add First Link
        </button>
    </div>
</div>
@endif
