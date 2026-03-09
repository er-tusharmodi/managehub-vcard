<div class="card">
    <div class="card-body">
    <form wire:submit="save">
        <p class="text-muted mb-4">Manage the navigation links shown in the top nav bar and mobile menu. Use anchor links (e.g. <code>#features</code>) for same-page sections or full URLs for other pages.</p>

        @foreach ($nav_links as $index => $link)
        <div class="row mb-3 align-items-center">
            <div class="col-md-4">
                <label class="form-label">Label</label>
                <input type="text" wire:model="nav_links.{{ $index }}.label" class="form-control" placeholder="e.g. Features">
                @error("nav_links.{$index}.label") <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">URL / Anchor</label>
                <input type="text" wire:model="nav_links.{{ $index }}.url" class="form-control" placeholder="e.g. #features or https://...">
                @error("nav_links.{$index}.url") <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" wire:click="removeLink({{ $index }})" class="btn btn-danger btn-sm w-100 mt-4">Remove</button>
            </div>
        </div>
        @endforeach

        <div class="form-group mb-4">
            <button type="button" wire:click="addLink" class="btn btn-outline-primary btn-sm">+ Add Link</button>
        </div>

        <div class="form-group mt-4">
            <button wire:loading.attr="disabled" type="submit" class="btn btn-primary">
                <span wire:loading class="spinner-border spinner-border-sm me-2"></span>
                <span wire:loading.remove>Save Navigation</span>
                <span wire:loading>Saving...</span>
            </button>
            <a href="{{ route('admin.website-cms', $pageSlug) }}" class="btn btn-secondary ms-2">Back</a>
        </div>
    </form>
    </div>
</div>
