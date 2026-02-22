<div class="card">
    <div class="card-body">
        <form wire:submit.prevent="save">
            <h5 class="mb-4">General Settings</h5>
            
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label class="form-label">Site URL</label>
                    <input wire:model="site_url" type="url" class="form-control">
                    @error('site_url') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label">Contact Email</label>
                    <input wire:model="contact_email" type="email" class="form-control">
                    @error('contact_email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label class="form-label">Contact Phone</label>
                    <input wire:model="contact_phone" type="text" class="form-control">
                    @error('contact_phone') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-lg-6 mb-3">
                    <label class="form-label">Contact Address</label>
                    <input wire:model="contact_address" type="text" class="form-control">
                    @error('contact_address') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <span wire:loading.remove>Save Changes</span>
                    <span wire:loading><i class="mdi mdi-loading mdi-spin"></i> Saving...</span>
                </button>
                <button type="button" class="btn btn-secondary" onclick="window.history.back()">Cancel</button>
            </div>
        </form>
    </div>
</div>
    </div>
</div>
