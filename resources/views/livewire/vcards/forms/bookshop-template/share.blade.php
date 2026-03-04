{{--
 | bookshop-template/share.blade.php
 | Share sheet text & options for bookshop-template.
 | Available: $form (array), $categoryOptions (array)
--}}

{{-- ── Share Sheet Labels ──────────────────────────────────────── --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-share-variant-outline me-1"></i>Share Sheet
    </h6>
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="share-title">Share Sheet Title</label>
    <input type="text"
           id="share-title"
           class="form-control @error('form.title') is-invalid @enderror"
           wire:model="form.title"
           placeholder="Share PageTurner Books">
    @error('form.title') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="share-cancel">Cancel Button Label</label>
    <input type="text"
           id="share-cancel"
           class="form-control @error('form.cancel') is-invalid @enderror"
           wire:model="form.cancel"
           placeholder="Cancel">
    @error('form.cancel') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- ── Share Options List ──────────────────────────────────────── --}}
@if(isset($form['options']) && is_array($form['options']))
    <div class="col-12 mb-2 mt-1">
        <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
            <i class="mdi mdi-format-list-bulleted me-1"></i>Share Options
        </h6>
    </div>
    <div class="col-12">
        @foreach($form['options'] as $i => $option)
            <div class="row g-2 mb-2 align-items-center" wire:key="share-option-{{ $i }}">
                <div class="col-lg-4">
                    <label class="form-label fw-semibold mb-1" style="font-size:.8rem;">
                        Option {{ $i + 1 }} — Label
                    </label>
                    <input type="text"
                           class="form-control form-control-sm @error('form.options.' . $i . '.label') is-invalid @enderror"
                           wire:model="form.options.{{ $i }}.label"
                           placeholder="WhatsApp">
                    @error('form.options.' . $i . '.label') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-lg-4">
                    <label class="form-label fw-semibold mb-1" style="font-size:.8rem;">Key</label>
                    <input type="text"
                           class="form-control form-control-sm @error('form.options.' . $i . '.key') is-invalid @enderror"
                           wire:model="form.options.{{ $i }}.key"
                           placeholder="wa">
                    @error('form.options.' . $i . '.key') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-lg-4">
                    <label class="form-label fw-semibold mb-1" style="font-size:.8rem;">Action</label>
                    <input type="text"
                           class="form-control form-control-sm @error('form.options.' . $i . '.action') is-invalid @enderror"
                           wire:model="form.options.{{ $i }}.action"
                           placeholder="shareWA">
                    @error('form.options.' . $i . '.action') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        @endforeach
    </div>
@endif
