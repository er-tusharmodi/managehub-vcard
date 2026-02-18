<div class="card">
    <div class="card-body">
    <form wire:submit="save">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="logo_file" class="form-label">Logo File</label>
                    <input wire:model="logo_file" type="file" id="logo_file" class="form-control" accept="image/png,image/jpeg,image/svg+xml">
                    @error('logo_file') <span class="text-danger small">{{ $message }}</span> @enderror
                    @if ($logo_url)
                        <div class="mt-2">
                            <img src="{{ $logo_url }}" alt="Logo preview" style="max-height: 48px;">
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="favicon_file" class="form-label">Favicon File</label>
                    <input wire:model="favicon_file" type="file" id="favicon_file" class="form-control" accept="image/png,image/jpeg,image/svg+xml,image/x-icon">
                    @error('favicon_file') <span class="text-danger small">{{ $message }}</span> @enderror
                    @if ($favicon_url)
                        <div class="mt-2">
                            <img src="{{ $favicon_url }}" alt="Favicon preview" style="max-height: 32px;">
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="footer_logo_file" class="form-label">Footer Logo File</label>
                    <input wire:model="footer_logo_file" type="file" id="footer_logo_file" class="form-control" accept="image/png,image/jpeg,image/svg+xml">
                    @error('footer_logo_file') <span class="text-danger small">{{ $message }}</span> @enderror
                    @if ($footer_logo_url)
                        <div class="mt-2">
                            <img src="{{ $footer_logo_url }}" alt="Footer logo preview" style="max-height: 48px;">
                        </div>
                    @endif
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
</div>
