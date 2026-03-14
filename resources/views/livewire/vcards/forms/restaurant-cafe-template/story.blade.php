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
        // Only prepend $assetBaseUrl for relative paths; absolute (/...) and full URLs are used as-is.
        $storyPreview = $storyImg
            ? (isset($assetBaseUrl) && is_string($storyImg) && !preg_match('~^(https?:)?//|/~', $storyImg)
                ? rtrim($assetBaseUrl, '/') . '/' . ltrim($storyImg, '/')
                : $storyImg)
            : null;
    @endphp
    <div class="d-flex align-items-start gap-3 flex-wrap">
        @if($storyPreview)
            <img src="{{ $storyPreview }}" class="rounded border" style="height:80px;width:80px;object-fit:cover;" alt="Story image">
        @endif
        <div class="flex-grow-1">
            <div wire:loading wire:target="uploads.image" class="mb-1">
                <span class="spinner-border spinner-border-sm text-primary"></span>
                <small class="text-primary ms-1">Uploading…</small>
            </div>
            <input type="file"
                   class="form-control @error('uploads.image') is-invalid @enderror"
                   wire:model.live="uploads.image"
                   accept="image/*">
            @error('uploads.image') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

{{-- Highlights list --}}
@php
$highlightIcons = [
    'fa-utensils'       => 'Utensils',
    'fa-burger'         => 'Burger',
    'fa-pizza-slice'    => 'Pizza',
    'fa-drumstick-bite' => 'Chicken',
    'fa-fish'           => 'Seafood',
    'fa-carrot'         => 'Veggie',
    'fa-seedling'       => 'Organic',
    'fa-pepper-hot'     => 'Spicy',
    'fa-bread-slice'    => 'Bakery',
    'fa-cake-candles'   => 'Dessert',
    'fa-ice-cream'      => 'Ice Cream',
    'fa-egg'            => 'Eggs',
    'fa-lemon'          => 'Citrus',
    'fa-apple-whole'    => 'Fruit',
    'fa-mug-hot'        => 'Coffee',
    'fa-wine-glass'     => 'Wine',
    'fa-beer-mug-empty' => 'Beer',
    'fa-martini-glass'  => 'Cocktail',
    'fa-bottle-water'   => 'Water',
    'fa-fire-burner'    => 'Grill',
    'fa-hat-chef'       => 'Chef',
    'fa-star'           => 'Quality',
    'fa-award'          => 'Award',
    'fa-trophy'         => 'Best',
    'fa-clock'          => 'Quick',
    'fa-motorcycle'     => 'Delivery',
    'fa-table'          => 'Dine-in',
    'fa-wifi'           => 'WiFi',
    'fa-snowflake'      => 'AC',
    'fa-car'            => 'Parking',
    'fa-people-group'   => 'Family',
    'fa-music'          => 'Music',
    'fa-tree'           => 'Outdoor',
];
@endphp
@if(isset($form['highlights']) && is_array($form['highlights']))
<div class="col-12 mb-1">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <label class="form-label fw-semibold text-muted mb-0">Highlights</label>
            <small class="d-block text-muted">Choose an icon and enter a label for each USP badge.</small>
        </div>
        <button type="button" class="btn btn-sm btn-primary px-3"
                wire:click="addRowAndSave('highlights', ['icon', 'label'])">
            <i class="mdi mdi-plus me-1"></i>Add
        </button>
    </div>
</div>
@foreach($form['highlights'] as $hi => $highlight)
    @php $activeIcon = $highlight['icon'] ?? 'fa-utensils'; @endphp
    <div class="col-12">
        <div class="rounded-3 p-3 mb-2" wire:key="rc-hl-{{ $hi }}"
             style="background:#f8fafc;border:1px solid #e2e8f0;border-left:4px solid #d97706 !important;">

            {{-- Header: label + delete --}}
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label small fw-semibold mb-0">Icon</label>
                <button type="button"
                        class="btn btn-sm btn-outline-danger p-0 rounded-circle"
                        style="width:28px;height:28px;flex-shrink:0;"
                        x-on:click="showConfirmToast('Remove this highlight?', () => $wire.removeRowWithConfirm({{ $hi }}, 'highlights'))">
                    <i class="mdi mdi-delete-outline" style="font-size:13px;"></i>
                </button>
            </div>

            {{-- Icon tile grid --}}
            <div class="d-flex flex-wrap gap-2 mb-3">
                @foreach($highlightIcons as $iKey => $iLabel)
                    @php $isActive = $activeIcon === $iKey; @endphp
                    <button type="button"
                            wire:click="$set('form.highlights.{{ $hi }}.icon', '{{ $iKey }}')"
                            title="{{ $iLabel }}"
                            style="width:54px;height:54px;border-radius:10px;flex-direction:column;display:flex;align-items:center;justify-content:center;gap:3px;padding:4px;cursor:pointer;border:2px solid {{ $isActive ? '#d97706' : '#e2e8f0' }};background:{{ $isActive ? '#d97706' : '#fff' }};color:{{ $isActive ? '#fff' : '#d97706' }};">
                        <i class="fa-solid {{ $iKey }}" style="font-size:18px;color:inherit;"></i>
                        <span style="font-size:8px;line-height:1.1;font-weight:600;white-space:nowrap;overflow:hidden;max-width:48px;text-overflow:ellipsis;color:inherit;">{{ $iLabel }}</span>
                    </button>
                @endforeach
            </div>

            {{-- Label input --}}
            <div>
                <label class="form-label small fw-semibold mb-1">Label</label>
                <input type="text"
                       class="form-control form-control-sm"
                       wire:model="form.highlights.{{ $hi }}.label"
                       placeholder="e.g. Wood-Fired Oven">
            </div>
        </div>
    </div>
@endforeach
@endif
