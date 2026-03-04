{{--
 | restaurant-cafe-template/story.blade.php
 | Our Story section: image upload, paragraph1/2 as textareas,
 | chefName/chefRole as text, highlights list via field.blade.php.
--}}

<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-book-open-variant me-1"></i>Our Story
    </h6>
</div>

{{-- Story Image --}}
<div class="col-12 mb-3">
    <label class="form-label fw-semibold">Story / Chef Photo</label>
    @php
        $storyImg = $form['image'] ?? null;
        $storyPreview = $storyImg ? (isset($assetBaseUrl) ? rtrim($assetBaseUrl,'/').'/'.$storyImg : $storyImg) : null;
    @endphp
    <div class="d-flex align-items-start gap-3 flex-wrap">
        @if($storyPreview)
            <img src="{{ $storyPreview }}" class="rounded border" style="height:80px;width:80px;object-fit:cover;" alt="Story image">
        @endif
        <div class="flex-grow-1">
            <input type="file"
                   class="form-control @error('form.image') is-invalid @enderror"
                   wire:model="form.image"
                   accept="image/*">
            @error('form.image') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

{{-- Paragraph 1 --}}
<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="story-p1">Paragraph 1</label>
    <textarea id="story-p1"
              class="form-control @error('form.paragraph1') is-invalid @enderror"
              wire:model="form.paragraph1"
              rows="3"
              placeholder="Tell the story of how your restaurant began..."></textarea>
    @error('form.paragraph1') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Paragraph 2 --}}
<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="story-p2">Paragraph 2</label>
    <textarea id="story-p2"
              class="form-control @error('form.paragraph2') is-invalid @enderror"
              wire:model="form.paragraph2"
              rows="3"
              placeholder="Continue your story — speciality, tradition, secret..."></textarea>
    @error('form.paragraph2') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Chef Details --}}
<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="story-chef">Chef / Owner Name</label>
    <input type="text"
           id="story-chef"
           class="form-control @error('form.chefName') is-invalid @enderror"
           wire:model="form.chefName"
           placeholder="e.g. Marco Rosetti">
    @error('form.chefName') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="story-role">Chef Title / Role</label>
    <input type="text"
           id="story-role"
           class="form-control @error('form.chefRole') is-invalid @enderror"
           wire:model="form.chefRole"
           placeholder="e.g. Head Chef & Founder">
    @error('form.chefRole') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Highlights list — delegate to generic renderer --}}
@if(isset($form['highlights']) && is_array($form['highlights']))
<div class="col-12 mb-1">
    <label class="form-label fw-semibold text-muted">Highlights</label>
    <small class="d-block text-muted mb-2">Icon identifier + label for each USP badge.</small>
</div>
@foreach($form['highlights'] as $hi => $highlight)
    <div class="col-12">
        <div class="border rounded p-2 mb-2 bg-light">
            <div class="row g-2">
                <div class="col-sm-4">
                    <label class="form-label small mb-1">Icon Key</label>
                    <input type="text"
                           class="form-control form-control-sm"
                           wire:model="form.highlights.{{ $hi }}.icon"
                           placeholder="oven / fresh / wine">
                </div>
                <div class="col-sm-8">
                    <label class="form-label small mb-1">Label</label>
                    <input type="text"
                           class="form-control form-control-sm"
                           wire:model="form.highlights.{{ $hi }}.label"
                           placeholder="Wood-Fired Oven">
                </div>
            </div>
        </div>
    </div>
@endforeach
@endif
