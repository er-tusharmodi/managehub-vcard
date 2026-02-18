<div class="card">
    <div class="card-body">
    <form wire:submit="save">
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="title" class="form-label">CTA Title</label>
                    <input wire:model="title" type="text" id="title" class="form-control" placeholder="Ready to Elevate Your Digital Presence?">
                    @error('title') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="subtitle" class="form-label">CTA Subtitle</label>
            <textarea wire:model="subtitle" id="subtitle" class="form-control" rows="3" placeholder="Subtitle text"></textarea>
            @error('subtitle') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="primary_label" class="form-label">Primary Button Label</label>
                    <input wire:model="primary_label" type="text" id="primary_label" class="form-control" placeholder="Start Free Trial">
                    @error('primary_label') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="primary_url" class="form-label">Primary Button URL</label>
                    <input wire:model="primary_url" type="url" id="primary_url" class="form-control" placeholder="https://example.com">
                    @error('primary_url') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="secondary_label" class="form-label">Secondary Button Label</label>
                    <input wire:model="secondary_label" type="text" id="secondary_label" class="form-control" placeholder="Schedule a Demo">
                    @error('secondary_label') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="secondary_url" class="form-label">Secondary Button URL</label>
                    <input wire:model="secondary_url" type="url" id="secondary_url" class="form-control" placeholder="https://example.com">
                    @error('secondary_url') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-group mt-4">
            <button wire:loading.attr="disabled" type="submit" class="btn btn-primary">
                <span wire:loading class="spinner-border spinner-border-sm me-2"></span>
                <span wire:loading.remove>Save CTA Section</span>
                <span wire:loading>Saving...</span>
            </button>
            <a href="{{ route('admin.website-cms', $page->slug) }}" class="btn btn-secondary ms-2">Back</a>
        </div>
    </form>
    </div>
</div>
