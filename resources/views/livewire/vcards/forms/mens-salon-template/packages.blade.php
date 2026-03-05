{{-- mens-salon-template/packages.blade.php — [{name, badge, badgeClass, items[], price, old, save}] --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-tag-multiple me-1"></i>Packages / Combos
    </h6>
</div>
@if(is_array($form))
@foreach($form as $i => $pkg)
<div class="col-12 mb-3" wire:key="mpkg-{{ $i }}">
    <div class="border rounded-3 p-2 bg-light">
        <div class="row g-2">
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Package Name</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.name" placeholder="The Classic">
            </div>
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Badge Text</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.badge" placeholder="Hot Deal">
            </div>
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Badge Class</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.badgeClass" placeholder="hot / popular / new">
            </div>
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Price</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.price" placeholder="₹399">
            </div>
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Old Price</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.old" placeholder="₹570">
            </div>
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Save Text</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.save" placeholder="Save ₹171">
            </div>
            @if(is_array($form[$i]['items'] ?? null))
            <div class="col-12">
                <label class="form-label small mb-1 fw-semibold">Included Items (one per line)</label>
                <textarea class="form-control form-control-sm" rows="{{ max(2, count($form[$i]['items'])) }}"
                          wire:model="form.{{ $i }}.items_text"
                          placeholder="Regular Haircut&#10;Beard Trim &amp; Shape"
                          style="display:none"></textarea>
                @foreach(($form[$i]['items'] ?? []) as $ii => $item)
                <input type="text" class="form-control form-control-sm mb-1"
                       wire:model="form.{{ $i }}.items.{{ $ii }}" wire:key="mpkgitem-{{ $i }}-{{ $ii }}"
                       placeholder="Service / item name">
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endforeach
@endif
