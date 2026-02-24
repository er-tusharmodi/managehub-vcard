<div class="card">
    <div class="card-body">
    <form wire:submit="save">
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="title" class="form-label">Section Title</label>
                    <input wire:model="title" type="text" id="title" class="form-control" placeholder="vCards Built for Every">
                    @error('title') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="highlight" class="form-label">Highlighted Word</label>
                    <input wire:model="highlight" type="text" id="highlight" class="form-control" placeholder="Category">
                    @error('highlight') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="subtitle" class="form-label">Section Subtitle</label>
            <textarea wire:model="subtitle" id="subtitle" class="form-control" rows="2" placeholder="Subtitle text"></textarea>
            @error('subtitle') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="mb-0">Categories</h5>
                <button type="button" wire:click="addCategory" class="btn btn-outline-primary btn-sm">+ Add Category</button>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 56px;">#</th>
                            <th>Icon Class</th>
                            <th>Title</th>
                            <th style="width: 220px;">Icon Background</th>
                            <th style="width: 220px;">Icon Color</th>
                            <th>Description</th>
                            <th style="width: 120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $index => $category)
                            <tr>
                                <td class="text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <input type="text" wire:model="categories.{{ $index }}.icon" class="form-control" placeholder="fas fa-star">
                                    @error("categories.{$index}.icon") <span class="text-danger small">{{ $message }}</span> @enderror
                                </td>
                                <td>
                                    <input type="text" wire:model="categories.{{ $index }}.title" class="form-control" placeholder="Category Name">
                                    @error("categories.{$index}.title") <span class="text-danger small">{{ $message }}</span> @enderror
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="color" wire:model="categories.{{ $index }}.icon_bg" class="form-control form-control-color" style="max-width: 72px;">
                                        <input type="text" class="form-control" wire:model="categories.{{ $index }}.icon_bg" placeholder="#ffffff">
                                    </div>
                                    @error("categories.{$index}.icon_bg") <span class="text-danger small">{{ $message }}</span> @enderror
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="color" wire:model="categories.{{ $index }}.icon_color" class="form-control form-control-color" style="max-width: 72px;">
                                        <input type="text" class="form-control" wire:model="categories.{{ $index }}.icon_color" placeholder="#000000">
                                    </div>
                                    @error("categories.{$index}.icon_color") <span class="text-danger small">{{ $message }}</span> @enderror
                                </td>
                                <td>
                                    <textarea wire:model="categories.{{ $index }}.description" class="form-control" rows="2" placeholder="Category description"></textarea>
                                    @error("categories.{$index}.description") <span class="text-danger small">{{ $message }}</span> @enderror
                                </td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-outline-danger btn-sm w-100"
                                        onclick="showConfirmToast('Delete this category?', function () { if (window.Livewire && Livewire.find) { Livewire.find('{{ $this->getId() }}').call('removeCategoryConfirmed', {{ $index }}); } })"
                                    >
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No categories yet. Click "Add Category" to create one.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="form-group mt-4">
            <button wire:loading.attr="disabled" type="submit" class="btn btn-primary">
                <span wire:loading class="spinner-border spinner-border-sm me-2"></span>
                <span wire:loading.remove>Save Categories</span>
                <span wire:loading>Saving...</span>
            </button>
            <a href="{{ route('admin.website-cms', $page->slug) }}" class="btn btn-secondary ms-2">Back</a>
        </div>
    </form>
    </div>
</div>
