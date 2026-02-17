<div class="container mt-5">
    <form wire:submit="save">
        <div class="mb-3">
            <h5 class="mb-3">Social Media Links</h5>
            @foreach ($links as $index => $link)
                <div class="row mb-3">
                    <div class="col-md-5">
                        <input type="text" wire:model="links.{{ $index }}.platform" class="form-control" placeholder="Platform (e.g., Facebook, Twitter)">
                        @error("links.{$index}.platform") <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-5">
                        <input type="url" wire:model="links.{{ $index }}.url" class="form-control" placeholder="URL">
                        @error("links.{$index}.url") <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-2">
                        <button type="button" wire:click="removeLink({{ $index }})" class="btn btn-danger btn-sm w-100">Remove</button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="form-group mb-3">
            <button type="button" wire:click="addLink" class="btn btn-outline-primary">+ Add Social Link</button>
        </div>

        <div class="form-group mt-4">
            <button wire:loading.attr="disabled" type="submit" class="btn btn-primary">
                <span wire:loading class="spinner-border spinner-border-sm me-2"></span>
                <span wire:loading.remove>Save Social Links</span>
                <span wire:loading>Saving...</span>
            </button>
            <a href="{{ route('admin.website-cms', $page->slug) }}" class="btn btn-secondary ms-2">Back</a>
        </div>
    </form>
</div>
