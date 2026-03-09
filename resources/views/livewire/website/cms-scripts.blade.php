<div class="card">
    <div class="card-body">
        <div class="alert alert-warning d-flex align-items-start gap-2">
            <i class="fas fa-exclamation-triangle mt-1"></i>
            <div>
                <strong>Security Notice:</strong> Scripts entered here are injected as raw HTML into every page load.
                Only add scripts from trusted, verified sources (e.g. Google Analytics, Meta Pixel, Google Tag Manager).
                Never paste scripts from unknown sources.
            </div>
        </div>

        <form wire:submit="save">
            <div class="form-group mb-4">
                <label for="head_script" class="form-label fw-semibold">
                    Head Scripts
                    <small class="text-muted fw-normal ms-1">— injected just before <code>&lt;/head&gt;</code></small>
                </label>
                <textarea wire:model="head_script" id="head_script" class="form-control font-monospace" rows="10"
                    placeholder="<!-- Paste your head scripts here, e.g. Google Tag Manager, meta pixel base code -->"></textarea>
                @error('head_script') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="form-group mb-4">
                <label for="footer_script" class="form-label fw-semibold">
                    Footer Scripts
                    <small class="text-muted fw-normal ms-1">— injected just before <code>&lt;/body&gt;</code></small>
                </label>
                <textarea wire:model="footer_script" id="footer_script" class="form-control font-monospace" rows="10"
                    placeholder="<!-- Paste your footer scripts here, e.g. analytics event listeners, chat widgets -->"></textarea>
                @error('footer_script') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="form-group mt-4">
                <button wire:loading.attr="disabled" type="submit" class="btn btn-primary">
                    <span wire:loading class="spinner-border spinner-border-sm me-2"></span>
                    <span wire:loading.remove>Save Scripts</span>
                    <span wire:loading>Saving...</span>
                </button>
                <a href="{{ route('admin.website-cms', $pageSlug) }}" class="btn btn-secondary ms-2">Back</a>
            </div>
        </form>
    </div>
</div>
