<div class="container mt-5">
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
            <h5 class="mb-3">Categories</h5>
            @foreach ($categories as $index => $category)
                <div class="card mb-3 p-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label class="form-label">Icon Class</label>
                                <input type="text" wire:model="categories.{{ $index }}.icon" class="form-control" placeholder="fas fa-star">
                                @error("categories.{$index}.icon") <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label class="form-label">Title</label>
                                <input type="text" wire:model="categories.{{ $index }}.title" class="form-control" placeholder="Category Name">
                                @error("categories.{$index}.title") <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label class="form-label">Icon Background Color</label>
                                <div class="input-group">
                                    <input type="color" wire:model="categories.{{ $index }}.icon_bg" class="form-control form-control-color" style="max-width: 100px;">
                                    <input type="text" class="form-control" wire:model="categories.{{ $index }}.icon_bg">
                                </div>
                                @error("categories.{$index}.icon_bg") <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label class="form-label">Icon Color</label>
                                <div class="input-group">
                                    <input type="color" wire:model="categories.{{ $index }}.icon_color" class="form-control form-control-color" style="max-width: 100px;">
                                    <input type="text" class="form-control" wire:model="categories.{{ $index }}.icon_color">
                                </div>
                                @error("categories.{$index}.icon_color") <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label">Description</label>
                        <textarea wire:model="categories.{{ $index }}.description" class="form-control" rows="2" placeholder="Category description"></textarea>
                        @error("categories.{$index}.description") <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <button type="button" wire:click="removeCategory({{ $index }})" class="btn btn-danger btn-sm">Remove Category</button>
                </div>
            @endforeach
        </div>

        <div class="form-group mb-3">
            <button type="button" wire:click="addCategory" class="btn btn-outline-primary">+ Add Category</button>
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
