{{--
 | _shared/meta.blade.php
 | SEO / Meta section — used by ALL templates.
 | Available: $form (array), $uploads (array)
--}}

<div class="col-12 mb-3">
    <h6 class="fw-semibold text-muted text-uppercase mb-3" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-tag-multiple-outline me-1"></i>SEO & Share Settings
    </h6>
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="meta-title">Page Title</label>
    <input type="text"
           id="meta-title"
           class="form-control @error('form.title') is-invalid @enderror"
           wire:model="form.title"
           placeholder="Your Business Name — City | Category">
    @error('form.title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="text-muted">Shown in browser tab and search results</small>
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="meta-description">Meta Description</label>
    <textarea id="meta-description"
              class="form-control @error('form.description') is-invalid @enderror"
              wire:model="form.description"
              rows="3"
              maxlength="160"
              placeholder="Brief description of your business (up to 160 characters)"></textarea>
    @error('form.description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="text-muted">Used by search engines. Keep under 160 characters.</small>
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="meta-keywords">Keywords</label>
    <input type="text"
           id="meta-keywords"
           class="form-control @error('form.keywords') is-invalid @enderror"
           wire:model="form.keywords"
           placeholder="keyword1, keyword2, keyword3">
    @error('form.keywords')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="text-muted">Comma-separated keywords for SEO</small>
</div>

{{-- OG Image --}}
<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold">OG Share Image</label>
    @if(!empty($form['og_image'] ?? ''))
        <div class="mb-2">
            <img src="{{ $form['og_image'] }}" alt="OG Image"
                 class="img-thumbnail"
                 style="max-width:200px;max-height:100px;object-fit:cover;">
        </div>
    @endif
    <div wire:loading wire:target="uploads.og_image" class="mb-1">
        <span class="spinner-border spinner-border-sm text-primary"></span>
        <small class="text-primary ms-1">Uploading…</small>
    </div>
    <input type="file"
           class="form-control @error('uploads.og_image') is-invalid @enderror"
           wire:model.live="uploads.og_image"
           accept="image/*">
    @error('uploads.og_image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="text-muted">Image shown when link is shared on WhatsApp/Facebook. Recommended: 1200×630 px</small>
</div>
