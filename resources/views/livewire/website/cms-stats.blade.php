<div class="card">
    <div class="card-body">
    <form wire:submit="save">
        <p class="text-muted mb-4">Manage the statistics shown in the stats bar below the hero section (e.g. "9+ Templates", "100% Customizable").</p>

        @foreach ($items as $index => $item)
        <div class="row mb-3 align-items-center">
            <div class="col-md-3">
                <label class="form-label">Number</label>
                <input type="text" wire:model="items.{{ $index }}.number" class="form-control" placeholder="e.g. 100">
                @error("items.{$index}.number") <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Suffix</label>
                <input type="text" wire:model="items.{{ $index }}.suffix" class="form-control" placeholder="e.g. + or %">
                @error("items.{$index}.suffix") <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Label</label>
                <input type="text" wire:model="items.{{ $index }}.label" class="form-control" placeholder="e.g. Templates">
                @error("items.{$index}.label") <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" wire:click="removeItem({{ $index }})" class="btn btn-danger btn-sm w-100 mt-4">Remove</button>
            </div>
        </div>
        @endforeach

        <div class="form-group mb-4">
            <button type="button" wire:click="addItem" class="btn btn-outline-primary btn-sm">+ Add Stat</button>
        </div>

        <div class="form-group mt-4">
            <button wire:loading.attr="disabled" type="submit" class="btn btn-primary">
                <span wire:loading class="spinner-border spinner-border-sm me-2"></span>
                <span wire:loading.remove>Save Stats Bar</span>
                <span wire:loading>Saving...</span>
            </button>
            <a href="{{ route('admin.website-cms', $pageSlug) }}" class="btn btn-secondary ms-2">Back</a>
        </div>
    </form>
    </div>
</div>
