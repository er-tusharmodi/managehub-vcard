<div class="card">
    <div class="card-body">
    <form wire:submit="save">
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="hero_title" class="form-label">Hero Title</label>
                    <input wire:model="hero_title" type="text" id="hero_title" class="form-control" placeholder="Main title">
                    @error('hero_title') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="hero_title_highlight" class="form-label">Highlighted Text</label>
                    <input wire:model="hero_title_highlight" type="text" id="hero_title_highlight" class="form-control" placeholder="Highlighted part">
                    @error('hero_title_highlight') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="hero_subtitle" class="form-label">Hero Subtitle</label>
            <textarea wire:model="hero_subtitle" id="hero_subtitle" class="form-control" rows="3" placeholder="Subtitle text"></textarea>
            @error('hero_subtitle') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <div class="form-group mb-3">
            <label for="hero_image_file" class="form-label">Hero Image (Optional)</label>
            <input wire:model="hero_image_file" type="file" id="hero_image_file" class="form-control" accept="image/png,image/jpeg,image/webp">
            <small class="text-muted d-block mt-1">Upload a JPG, PNG, or WebP file.</small>
            @error('hero_image_file') <span class="text-danger small">{{ $message }}</span> @enderror
            @if (!empty($hero_image_path))
                <div class="mt-2">
                    <img src="{{ $hero_image_path }}" alt="Hero image" class="img-fluid rounded" style="max-height: 160px;">
                </div>
            @endif
        </div>

        <div class="mb-3">
            <h5 class="mb-3">CTA Buttons</h5>
            @foreach ($cta_buttons as $index => $button)
                <div class="row mb-3">
                    <div class="col-md-5">
                        <input type="text" wire:model="cta_buttons.{{ $index }}.label" class="form-control" placeholder="Button Text">
                        @error("cta_buttons.{$index}.label") <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-5">
                        <input type="url" wire:model="cta_buttons.{{ $index }}.url" class="form-control" placeholder="Button URL">
                        @error("cta_buttons.{$index}.url") <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-2">
                        <button type="button" wire:click="removeButton({{ $index }})" class="btn btn-danger btn-sm w-100">Remove</button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="form-group mb-3">
            <button type="button" wire:click="addButton" class="btn btn-outline-primary">+ Add Button</button>
        </div>

        <div class="form-group mt-4">
            <button wire:loading.attr="disabled" type="submit" class="btn btn-primary">
                <span wire:loading class="spinner-border spinner-border-sm me-2"></span>
                <span wire:loading.remove>Save Hero Section</span>
                <span wire:loading>Saving...</span>
            </button>
            <a href="{{ route('admin.website-cms', $page->slug) }}" class="btn btn-secondary ms-2">Back</a>
        </div>
    </form>
    </div>
</div>
