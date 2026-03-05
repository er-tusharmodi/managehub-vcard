{{-- restaurant-cafe-template/gallery.blade.php — [{caption, image}] --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-image-multiple me-1"></i>Gallery Photos
    </h6>
</div>
@if(is_array($form))
@foreach($form as $i => $photo)
<div class="col-sm-6 mb-2" wire:key="rgal-{{ $i }}">
    <div class="border rounded-3 p-2 bg-light">
        <label class="form-label small mb-1 fw-semibold">Caption</label>
        <input type="text" class="form-control form-control-sm mb-1"
               wire:model="form.{{ $i }}.caption" placeholder="Chef special — grilled sea bass">
        <label class="form-label small mb-1 fw-semibold">Image URL</label>
        <input type="text" class="form-control form-control-sm"
               wire:model="form.{{ $i }}.image" placeholder="https://…">
    </div>
</div>
@endforeach
@endif
