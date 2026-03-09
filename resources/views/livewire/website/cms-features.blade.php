<div class="card">
    <div class="card-body">
    <form wire:submit="save">

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="badge" class="form-label">Badge Text</label>
                    <input wire:model="badge" type="text" id="badge" class="form-control" placeholder="Why Choose Us">
                    @error('badge') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="subtitle" class="form-label">Section Subtitle</label>
                    <input wire:model="subtitle" type="text" id="subtitle" class="form-control" placeholder="Ditch the paper. Make every connection count...">
                    @error('subtitle') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="title" class="form-label">Section Title</label>
                    <input wire:model="title" type="text" id="title" class="form-control" placeholder="Everything You Need in a">
                    @error('title') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="title_highlight" class="form-label">Highlighted Word (gradient)</label>
                    <input wire:model="title_highlight" type="text" id="title_highlight" class="form-control" placeholder="Digital vCard">
                    @error('title_highlight') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="mb-0">Feature Items</h5>
                <button type="button" wire:click="addItem" class="btn btn-outline-primary btn-sm">+ Add Feature</button>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 220px;">Icon Class <small class="text-muted">(FontAwesome)</small></th>
                            <th style="width: 220px;">Title</th>
                            <th>Description</th>
                            <th style="width: 100px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $index => $item)
                            <tr>
                                <td>
                                    <input type="text" wire:model="items.{{ $index }}.icon" class="form-control" placeholder="fas fa-star">
                                    @error("items.{$index}.icon") <span class="text-danger small">{{ $message }}</span> @enderror
                                </td>
                                <td>
                                    <input type="text" wire:model="items.{{ $index }}.title" class="form-control" placeholder="Feature Title">
                                    @error("items.{$index}.title") <span class="text-danger small">{{ $message }}</span> @enderror
                                </td>
                                <td>
                                    <textarea wire:model="items.{{ $index }}.desc" class="form-control" rows="2" placeholder="Feature description"></textarea>
                                    @error("items.{$index}.desc") <span class="text-danger small">{{ $message }}</span> @enderror
                                </td>
                                <td>
                                    <button type="button" wire:click="removeItem({{ $index }})" class="btn btn-outline-danger btn-sm w-100">Remove</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No features yet. Click "Add Feature" to create one.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="form-group mt-4">
            <button wire:loading.attr="disabled" type="submit" class="btn btn-primary">
                <span wire:loading class="spinner-border spinner-border-sm me-2"></span>
                <span wire:loading.remove>Save Features Section</span>
                <span wire:loading>Saving...</span>
            </button>
            <a href="{{ route('admin.website-cms', $pageSlug) }}" class="btn btn-secondary ms-2">Back</a>
        </div>

    </form>
    </div>
</div>
