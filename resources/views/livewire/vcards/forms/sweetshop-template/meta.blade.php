{{--
 | sweetshop-template/meta.blade.php
 | Meta / SEO section for sweetshop-template.
 | Extends _shared/meta with extra bannerAlt + profileAlt alt-text fields.
--}}

<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-tag-text-outline me-1"></i>SEO &amp; Meta
    </h6>
</div>

{{-- Title --}}
<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="meta-title">Page Title
        <small class="fw-normal text-muted">(shown in browser tab &amp; Google)</small>
    </label>
    <input type="text"
           id="meta-title"
           class="form-control @error('form.title') is-invalid @enderror"
           wire:model="form.title"
           placeholder="Sweet Shop Name – Sweets &amp; Desserts">
    @error('form.title') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Description --}}
<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="meta-desc">Meta Description
        <small class="fw-normal text-muted">(max 160 chars)</small>
    </label>
    <textarea id="meta-desc"
              class="form-control @error('form.description') is-invalid @enderror"
              wire:model="form.description"
              rows="2"
              maxlength="160"
              placeholder="Describe your sweet shop in 1-2 sentences for search engines..."></textarea>
    @error('form.description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Keywords --}}
<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="meta-kw">Keywords
        <small class="fw-normal text-muted">(comma separated)</small>
    </label>
    <input type="text"
           id="meta-kw"
           class="form-control @error('form.keywords') is-invalid @enderror"
           wire:model="form.keywords"
           placeholder="sweets, mithai, desserts, gift box, wedding sweets">
    @error('form.keywords') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- OG Image --}}
<div class="col-12 mb-3">
    <label class="form-label fw-semibold">Social Share Image (og:image)
        <small class="fw-normal text-muted">Shown when shared on WhatsApp / Facebook · 1200×630px ideal</small>
    </label>
    @php
        $ogVal = $form['og_image'] ?? null;
        $ogPreview = $ogVal ? (isset($assetBaseUrl) ? rtrim($assetBaseUrl,'/').'/'.$ogVal : $ogVal) : null;
    @endphp
    <div class="d-flex align-items-start gap-3 flex-wrap">
        @if($ogPreview)
            <img src="{{ $ogPreview }}" class="rounded border" style="height:60px;max-width:120px;object-fit:cover;" alt="OG preview">
        @endif
        <div class="flex-grow-1">
            <div wire:loading wire:target="uploads.og_image" class="mb-1">
                <span class="spinner-border spinner-border-sm text-primary"></span>
                <small class="text-primary ms-1">Uploading…</small>
            </div>
            <input type="file"
                   class="form-control @error('uploads.og_image') is-invalid @enderror"
                   wire:model.live="uploads.og_image"
                   accept="image/*">
            @error('uploads.og_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>


