<div class="container mt-5">
    <form wire:submit="save">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input wire:model="name" type="text" id="name" class="form-control" placeholder="Alex Morgan">
                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="role" class="form-label">Role/Title</label>
                    <input wire:model="role" type="text" id="role" class="form-control" placeholder="Senior Product Designer">
                    @error('role') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="company" class="form-label">Company</label>
                    <input wire:model="company" type="text" id="company" class="form-control" placeholder="TechNova Labs">
                    @error('company') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="location" class="form-label">Location</label>
                    <input wire:model="location" type="text" id="location" class="form-control" placeholder="San Francisco">
                    @error('location') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input wire:model="email" type="email" id="email" class="form-control" placeholder="alex@example.com">
                    @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input wire:model="phone" type="text" id="phone" class="form-control" placeholder="+1 (415) 555-0199">
                    @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="bio" class="form-label">Bio (Optional)</label>
            <textarea wire:model="bio" id="bio" class="form-control" rows="3" placeholder="Short bio or professional summary"></textarea>
            @error('bio') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <div class="form-group mt-4">
            <button wire:loading.attr="disabled" type="submit" class="btn btn-primary">
                <span wire:loading class="spinner-border spinner-border-sm me-2"></span>
                <span wire:loading.remove>Save vCard Preview</span>
                <span wire:loading>Saving...</span>
            </button>
            <a href="{{ route('admin.website-cms', $page->slug) }}" class="btn btn-secondary ms-2">Back</a>
        </div>
    </form>
</div>
