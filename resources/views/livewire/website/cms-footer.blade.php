<div class="card">
    <div class="card-body">
    <form wire:submit="save">
        <div class="form-group mb-4">
            <label for="footer_about" class="form-label">Footer About Text</label>
            <textarea wire:model="footer_about" id="footer_about" class="form-control" rows="3" placeholder="Footer description"></textarea>
            @error('footer_about') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <h5 class="mb-3">Product Links</h5>
            @foreach ($product_links as $index => $link)
                <div class="row mb-2">
                    <div class="col-md-5">
                        <input type="text" wire:model="product_links.{{ $index }}.label" class="form-control" placeholder="Link Label">
                        @error("product_links.{$index}.label") <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-5">
                        <input type="url" wire:model="product_links.{{ $index }}.url" class="form-control" placeholder="URL">
                        @error("product_links.{$index}.url") <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-2">
                        <button type="button" wire:click="removeProductLink({{ $index }})" class="btn btn-danger btn-sm w-100">Remove</button>
                    </div>
                </div>
            @endforeach
            <button type="button" wire:click="addProductLink" class="btn btn-outline-primary btn-sm mt-2">+ Add Link</button>
        </div>

        <div class="mb-4">
            <h5 class="mb-3">Resources Links</h5>
            @foreach ($resources_links as $index => $link)
                <div class="row mb-2">
                    <div class="col-md-5">
                        <input type="text" wire:model="resources_links.{{ $index }}.label" class="form-control" placeholder="Link Label">
                        @error("resources_links.{$index}.label") <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-5">
                        <input type="url" wire:model="resources_links.{{ $index }}.url" class="form-control" placeholder="URL">
                        @error("resources_links.{$index}.url") <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-2">
                        <button type="button" wire:click="removeResourceLink({{ $index }})" class="btn btn-danger btn-sm w-100">Remove</button>
                    </div>
                </div>
            @endforeach
            <button type="button" wire:click="addResourceLink" class="btn btn-outline-primary btn-sm mt-2">+ Add Link</button>
        </div>

        <div class="form-group mt-4">
            <button wire:loading.attr="disabled" type="submit" class="btn btn-primary">
                <span wire:loading class="spinner-border spinner-border-sm me-2"></span>
                <span wire:loading.remove>Save Footer</span>
                <span wire:loading>Saving...</span>
            </button>
            <a href="{{ route('admin.website-cms', $page->slug) }}" class="btn btn-secondary ms-2">Back</a>
        </div>
    </form>
    </div>
</div>
