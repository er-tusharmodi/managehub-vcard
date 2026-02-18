<div class="card">
    <div class="card-body">
    <form wire:submit="save">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="meta_title" class="form-label">Meta Title (SEO)</label>
                    <input wire:model="meta_title" type="text" id="meta_title" class="form-control" placeholder="Page title for search engines" maxlength="160">
                    <small class="text-muted">{{ strlen($meta_title) }}/160 characters</small>
                    @error('meta_title') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label for="meta_description" class="form-label">Meta Description (SEO)</label>
                    <textarea wire:model="meta_description" id="meta_description" class="form-control" rows="3" placeholder="Description for search engines" maxlength="160"></textarea>
                    <small class="text-muted">{{ strlen($meta_description) }}/160 characters</small>
                    @error('meta_description') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label for="meta_keywords" class="form-label">Meta Keywords (Optional)</label>
                    <input wire:model="meta_keywords" type="text" id="meta_keywords" class="form-control" placeholder="keyword1, keyword2, keyword3">
                    @error('meta_keywords') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label for="canonical_url" class="form-label">Canonical URL (Optional)</label>
                    <input wire:model="canonical_url" type="url" id="canonical_url" class="form-control" placeholder="https://example.com">
                    @error('canonical_url') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-group mt-4">
            <button wire:loading.attr="disabled" type="submit" class="btn btn-primary">
                <span wire:loading class="spinner-border spinner-border-sm me-2"></span>
                <span wire:loading.remove>Save SEO Settings</span>
                <span wire:loading>Saving...</span>
            </button>
            <a href="{{ route('admin.website-cms', $page->slug) }}" class="btn btn-secondary ms-2">Back</a>
        </div>
    </form>
    </div>
</div>
