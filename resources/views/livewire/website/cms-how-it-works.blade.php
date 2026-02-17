<div class="container mt-5">
    <form wire:submit="save">
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="title" class="form-label">Section Title</label>
                    <input wire:model="title" type="text" id="title" class="form-control" placeholder="How ManageHub Works">
                    @error('title') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="highlight" class="form-label">Highlighted Word</label>
                    <input wire:model="highlight" type="text" id="highlight" class="form-control" placeholder="Hub">
                    @error('highlight') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="subtitle" class="form-label">Section Subtitle</label>
            <input wire:model="subtitle" type="text" id="subtitle" class="form-control" placeholder="Three simple steps — no tech skills needed.">
            @error('subtitle') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <h5 class="mb-3">Steps</h5>
            @foreach ($steps as $index => $step)
                <div class="card mb-3 p-3">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-2">
                                <label class="form-label">Step Number</label>
                                <input type="number" wire:model="steps.{{ $index }}.number" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group mb-2">
                                <label class="form-label">Title</label>
                                <input type="text" wire:model="steps.{{ $index }}.title" class="form-control" placeholder="Step Title">
                                @error("steps.{$index}.title") <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label">Description</label>
                        <textarea wire:model="steps.{{ $index }}.description" class="form-control" rows="2" placeholder="Step description"></textarea>
                        @error("steps.{$index}.description") <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label class="form-label">Badge Background</label>
                                <input type="text" wire:model="steps.{{ $index }}.badge_bg" class="form-control" placeholder="bg-blue-100">
                                @error("steps.{$index}.badge_bg") <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label class="form-label">Badge Text Color</label>
                                <input type="text" wire:model="steps.{{ $index }}.badge_text" class="form-control" placeholder="text-blue-700">
                                @error("steps.{$index}.badge_text") <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <button type="button" wire:click="removeStep({{ $index }})" class="btn btn-danger btn-sm">Remove Step</button>
                </div>
            @endforeach
        </div>

        <div class="form-group mb-3">
            <button type="button" wire:click="addStep" class="btn btn-outline-primary">+ Add Step</button>
        </div>

        <div class="form-group mt-4">
            <button wire:loading.attr="disabled" type="submit" class="btn btn-primary">
                <span wire:loading class="spinner-border spinner-border-sm me-2"></span>
                <span wire:loading.remove>Save How It Works</span>
                <span wire:loading>Saving...</span>
            </button>
            <a href="{{ route('admin.website-cms', $page->slug) }}" class="btn btn-secondary ms-2">Back</a>
        </div>
    </form>
</div>
