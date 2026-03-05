{{--
 | _shared/social.blade.php
 | Inline editor for social link lists across all templates.
 | Row shape: {type, name, value, action[, url]}
--}}
@php
    $socialPlatforms = [
        'whatsapp'  => 'WhatsApp',
        'facebook'  => 'Facebook',
        'instagram' => 'Instagram',
        'youtube'   => 'YouTube',
        'twitter'   => 'Twitter / X',
        'linkedin'  => 'LinkedIn',
        'website'   => 'Website',
        'email'     => 'Email',
        'practo'    => 'Practo',
        'telegram'  => 'Telegram',
        'phone'     => 'Phone',
        'google'    => 'Google',
        'pinterest' => 'Pinterest',
        'snapchat'  => 'Snapchat',
        'tiktok'    => 'TikTok',
        'threads'   => 'Threads',
    ];
    $socialCols = ['type','name','value','action'];
@endphp

<div class="col-12 mb-2">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-2">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            <i class="mdi mdi-share-variant-outline me-1"></i>Social Links
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ is_array($form) ? count($form) : 0 }}</span>
        </span>
        <button type="button" class="btn btn-sm btn-primary px-3"
                wire:click="addRowAndSave('', {{ json_encode($socialCols) }})">
            <i class="mdi mdi-plus me-1"></i>Add Link
        </button>
    </div>
</div>

@if(is_array($form) && !empty($form))
    @foreach($form as $si => $row)
    @if(!is_array($row)) @continue @endif
    @php $hasUrl = array_key_exists('url', $row); @endphp
    <div class="col-12 mb-2" wire:key="social-row-{{ $si }}">
        <div class="border rounded-3 p-3" style="background:#f8fafc;">
            <div class="row g-2 align-items-end">

                {{-- Platform select --}}
                <div class="col-sm-3 col-lg-2">
                    <label class="form-label small fw-semibold mb-1">Platform</label>
                    <select class="form-select form-select-sm"
                            wire:model="form.{{ $si }}.type"
                            x-on:change="
                                const map = {{ json_encode($socialPlatforms) }};
                                const name = map[$event.target.value];
                                if (name) $wire.set('form.{{ $si }}.name', name);
                            ">
                        <option value="">— Select —</option>
                        @foreach($socialPlatforms as $pKey => $pLabel)
                            <option value="{{ $pKey }}" {{ ($row['type'] ?? '') === $pKey ? 'selected' : '' }}>
                                {{ $pLabel }}
                            </option>
                        @endforeach
                        <option value="other" {{ !isset($socialPlatforms[$row['type'] ?? '']) && !empty($row['type'] ?? '') ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                {{-- Display name --}}
                <div class="col-sm-3 col-lg-2">
                    <label class="form-label small fw-semibold mb-1">Display Name</label>
                    <input type="text"
                           class="form-control form-control-sm"
                           wire:model="form.{{ $si }}.name"
                           placeholder="WhatsApp">
                </div>

                {{-- Value / username --}}
                <div class="col-sm-6 col-lg-3">
                    <label class="form-label small fw-semibold mb-1">Username / Phone / Handle</label>
                    <input type="text"
                           class="form-control form-control-sm"
                           wire:model="form.{{ $si }}.value"
                           placeholder="@handle or +91…">
                </div>

                {{-- Action URL --}}
                <div class="col-sm-{{ $hasUrl ? '5' : '11' }} col-lg-{{ $hasUrl ? '3' : '4' }}">
                    <label class="form-label small fw-semibold mb-1">Action URL</label>
                    <input type="url"
                           class="form-control form-control-sm"
                           wire:model="form.{{ $si }}.action"
                           placeholder="https://wa.me/91…">
                </div>

                {{-- url (optional second URL) --}}
                @if($hasUrl)
                <div class="col-sm-5 col-lg-2">
                    <label class="form-label small fw-semibold mb-1">Alt URL</label>
                    <input type="url"
                           class="form-control form-control-sm"
                           wire:model="form.{{ $si }}.url"
                           placeholder="https://…">
                </div>
                @endif

                {{-- Delete --}}
                <div class="col-sm-1 d-flex align-items-end pb-1">
                    <button type="button"
                            class="btn btn-sm btn-outline-danger p-0 rounded-circle"
                            style="width:28px;height:28px;"
                            wire:click="removeRow('',{{ $si }})"
                            wire:confirm="Delete this social link?">
                        <i class="mdi mdi-delete" style="font-size:12px;"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>
    @endforeach
@else
    <div class="col-12">
        <div class="text-center py-4 rounded-3"
             style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);border:2px dashed #cbd5e1;">
            <i class="mdi mdi-share-variant-outline fs-1 text-muted mb-2 d-block"></i>
            <p class="fw-semibold text-muted mb-2 small">No social links yet</p>
            <button type="button" class="btn btn-sm btn-primary"
                    wire:click="addRowAndSave('', {{ json_encode($socialCols) }})">
                <i class="mdi mdi-plus me-1"></i>Add First Link
            </button>
        </div>
    </div>
@endif
