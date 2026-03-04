{{--
 | _shared/social.blade.php
 | Inline editor for social link lists across all templates.
 | Used for sections: social, socialLinks, followLinks
 | Row shape: {type, name, value, action[, url]}
 |   type   → platform key  (text)
 |   name   → display label (text)
 |   value  → username / phone (text — could be phone for WhatsApp)
 |   action → click URL        (url)
 |   url    → optional second URL, restaurant only (url)
--}}

<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-share-variant-outline me-1"></i>Social Links
    </h6>
    <small class="text-muted">Each row = one social platform. <code>action</code> = the URL opened on tap.</small>
</div>

@if(is_array($form) && !empty($form))
    @foreach($form as $si => $row)
    @php
        $hasUrl = array_key_exists('url', $row);
    @endphp
    <div class="col-12 mb-2" wire:key="social-row-{{ $si }}">
        <div class="border rounded-3 p-3 bg-light">
            <div class="row g-2 align-items-end">

                {{-- type --}}
                <div class="col-6 col-sm-3 col-lg-2">
                    <label class="form-label small fw-semibold mb-1">Platform Key</label>
                    <input type="text"
                           class="form-control form-control-sm"
                           wire:model="form.{{ $si }}.type"
                           placeholder="instagram">
                    <small class="text-muted" style="font-size:.65rem;">whatsapp / instagram…</small>
                </div>

                {{-- name --}}
                <div class="col-6 col-sm-3 col-lg-2">
                    <label class="form-label small fw-semibold mb-1">Display Label</label>
                    <input type="text"
                           class="form-control form-control-sm"
                           wire:model="form.{{ $si }}.name"
                           placeholder="Instagram">
                </div>

                {{-- value --}}
                <div class="col-sm-6 col-lg-3">
                    <label class="form-label small fw-semibold mb-1">Value / Username / Phone</label>
                    <input type="text"
                           class="form-control form-control-sm"
                           wire:model="form.{{ $si }}.value"
                           placeholder="@handle or +91…">
                </div>

                {{-- action URL --}}
                <div class="col-sm-{{ $hasUrl ? '6' : '12' }} col-lg-{{ $hasUrl ? '3' : '5' }}">
                    <label class="form-label small fw-semibold mb-1">Action URL</label>
                    <input type="url"
                           class="form-control form-control-sm"
                           wire:model="form.{{ $si }}.action"
                           placeholder="https://instagram.com/…">
                </div>

                {{-- url (restaurant only) --}}
                @if($hasUrl)
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label small fw-semibold mb-1">URL (alt)</label>
                    <input type="url"
                           class="form-control form-control-sm"
                           wire:model="form.{{ $si }}.url"
                           placeholder="https://…">
                </div>
                @endif

            </div>
        </div>
    </div>
    @endforeach
@else
    <div class="col-12">
        <p class="text-muted small"><i class="mdi mdi-information-outline me-1"></i>No social links found.</p>
    </div>
@endif
