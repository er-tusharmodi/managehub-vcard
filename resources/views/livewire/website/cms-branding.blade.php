<div class="container mt-5">
    <form wire:submit="save">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="logo_url" class="form-label">Logo URL</label>
                    <input wire:model="logo_url" type="url" id="logo_url" class="form-control" placeholder="https://example.com/logo.png">
                    @error('logo_url') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="favicon_url" class="form-label">Favicon URL</label>
                    <input wire:model="favicon_url" type="url" id="favicon_url" class="form-control" placeholder="https://example.com/favicon.ico">
                    @error('favicon_url') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="primary_color" class="form-label">Primary Color</label>
                    <div class="input-group">
                        <input wire:model="primary_color" type="color" id="primary_color" class="form-control form-control-color" style="max-width: 100px;">
                        <input type="text" class="form-control" wire:model="primary_color" placeholder="#000000">
                    </div>
                    @error('primary_color') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="secondary_color" class="form-label">Secondary Color</label>
                    <div class="input-group">
                        <input wire:model="secondary_color" type="color" id="secondary_color" class="form-control form-control-color" style="max-width: 100px;">
                        <input type="text" class="form-control" wire:model="secondary_color" placeholder="#666666">
                    </div>
                    @error('secondary_color') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-group mt-4">
            <button wire:loading.attr="disabled" type="submit" class="btn btn-primary">
                <span wire:loading class="spinner-border spinner-border-sm me-2"></span>
                <span wire:loading.remove>Save Branding</span>
                <span wire:loading>Saving...</span>
            </button>
            <a href="{{ route('admin.website-cms', $page->slug) }}" class="btn btn-secondary ms-2">Back</a>
        </div>
    </form>
</div>
