<div class="card">
    <div class="card-body">
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
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="mb-0">Steps</h5>
                <button type="button" wire:click="addStep" class="btn btn-outline-primary btn-sm">+ Add Step</button>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 56px;">#</th>
                            <th style="width: 220px;">Title</th>
                            <th>Description</th>
                            <th style="width: 200px;">Badge Background</th>
                            <th style="width: 200px;">Badge Text Color</th>
                            <th style="width: 120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($steps as $index => $step)
                            <tr>
                                <td>
                                    <input type="number" wire:model="steps.{{ $index }}.number" class="form-control" disabled>
                                </td>
                                <td>
                                    <input type="text" wire:model="steps.{{ $index }}.title" class="form-control" placeholder="Step Title">
                                    @error("steps.{$index}.title") <span class="text-danger small">{{ $message }}</span> @enderror
                                </td>
                                <td>
                                    <textarea wire:model="steps.{{ $index }}.description" class="form-control" rows="2" placeholder="Step description"></textarea>
                                    @error("steps.{$index}.description") <span class="text-danger small">{{ $message }}</span> @enderror
                                </td>
                                <td>
                                    <input type="text" wire:model="steps.{{ $index }}.badge_bg" class="form-control" placeholder="bg-blue-100">
                                    @error("steps.{$index}.badge_bg") <span class="text-danger small">{{ $message }}</span> @enderror
                                </td>
                                <td>
                                    <input type="text" wire:model="steps.{{ $index }}.badge_text" class="form-control" placeholder="text-blue-700">
                                    @error("steps.{$index}.badge_text") <span class="text-danger small">{{ $message }}</span> @enderror
                                </td>
                                <td>
                                    <button type="button" wire:click="removeStep({{ $index }})" class="btn btn-outline-danger btn-sm w-100">Remove</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No steps yet. Click "Add Step" to create one.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
</div>
