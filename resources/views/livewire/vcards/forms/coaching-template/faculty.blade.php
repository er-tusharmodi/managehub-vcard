{{--
 | coaching-template/faculty.blade.php
 | Faculty cards: items[{imageUrl, initials, name, subject, experience, gradient, enquiryText}]
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-account-group-outline me-1"></i>Faculty
    </h6>
</div>

@php $items = $form['items'] ?? []; @endphp
@foreach($items as $i => $member)
<div class="col-12 mb-3" wire:key="faculty-{{ $i }}">
    <div class="border rounded-3 p-3 bg-light">
        <div class="row g-2">
            {{-- Avatar image --}}
            <div class="col-12 col-sm-auto">
                @php
                    $fImg = $member['imageUrl'] ?? null;
                    $fSrc = $fImg ? (isset($assetBaseUrl) ? rtrim($assetBaseUrl,'/').'/'.$fImg : $fImg) : null;
                @endphp
                <div style="width:72px;">
                    @if($fSrc)
                        <img src="{{ $fSrc }}" class="rounded-circle border mb-1" style="width:72px;height:72px;object-fit:cover;" alt="">
                    @endif
                    <input type="file" class="form-control form-control-sm p-0 border-0" style="font-size:.65rem;"
                           wire:model="form.items.{{ $i }}.imageUrl" accept="image/*">
                </div>
            </div>
            <div class="col">
                <div class="row g-2">
                    <div class="col-sm-4">
                        <label class="form-label small mb-1 fw-semibold">Name</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.items.{{ $i }}.name" placeholder="Dr. A. Kumar">
                    </div>
                    <div class="col-sm-2">
                        <label class="form-label small mb-1 fw-semibold">Initials</label>
                        <input type="text" class="form-control form-control-sm" maxlength="3"
                               wire:model="form.items.{{ $i }}.initials" placeholder="AK">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small mb-1 fw-semibold">Subject</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.items.{{ $i }}.subject" placeholder="General Studies">
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label small mb-1 fw-semibold">Experience</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.items.{{ $i }}.experience" placeholder="12 Years">
                    </div>
                    <div class="col-sm-8">
                        <label class="form-label small mb-1 fw-semibold">Enquiry Text (WA pre-fill)</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.items.{{ $i }}.enquiryText"
                               placeholder="I want to enquire about Dr. A. Kumar's classes">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
