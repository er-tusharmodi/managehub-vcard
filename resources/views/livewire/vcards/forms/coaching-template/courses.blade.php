{{--
 | coaching-template/courses.blade.php
 | Course cards: items[{name, tag, bannerGradient, buttonGradient, imageUrl, iconClass, enquiryText, pills[]}]
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-book-open-variant me-1"></i>Courses
    </h6>
</div>

@php $items = $form['items'] ?? []; @endphp
@foreach($items as $i => $course)
<div class="col-12 mb-3" wire:key="course-{{ $i }}">
    <div class="border rounded-3 p-3 bg-light">
        <div class="row g-2">

            {{-- Course image --}}
            <div class="col-12 col-sm-auto d-flex align-items-start">
                @php
                    $cImg = $course['imageUrl'] ?? null;
                    $cSrc = $cImg ? (isset($assetBaseUrl) ? rtrim($assetBaseUrl,'/').'/'.$cImg : $cImg) : null;
                @endphp
                <div style="width:80px;">
                    @if($cSrc)
                        <img src="{{ $cSrc }}" class="rounded border mb-1" style="width:80px;height:60px;object-fit:cover;" alt="">
                    @endif
                    <input type="file" class="form-control form-control-sm p-0 border-0" style="font-size:.65rem;"
                           wire:model="form.items.{{ $i }}.imageUrl" accept="image/*">
                </div>
            </div>

            <div class="col">
                <div class="row g-2">
                    <div class="col-sm-6">
                        <label class="form-label small mb-1 fw-semibold">Course Name</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.items.{{ $i }}.name" placeholder="UPSC Foundation">
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small mb-1 fw-semibold">Tag</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.items.{{ $i }}.tag" placeholder="IAS/IPS">
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small mb-1 fw-semibold">Icon Class</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.items.{{ $i }}.iconClass" placeholder="bi-book">
                    </div>
                    <div class="col-12">
                        <label class="form-label small mb-1 fw-semibold">Enquiry Text (WA pre-fill)</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.items.{{ $i }}.enquiryText"
                               placeholder="I want to enquire about UPSC Foundation course">
                    </div>
                    {{-- Pills (tags/chips on the card) --}}
                    @php $pills = $course['pills'] ?? []; @endphp
                    @if(!empty($pills))
                    <div class="col-12">
                        <label class="form-label small mb-1 fw-semibold text-muted">Chips / Pills</label>
                        <div class="row g-1">
                            @foreach($pills as $pi => $pill)
                            <div class="col-auto">
                                <input type="text" class="form-control form-control-sm"
                                       style="width:120px;"
                                       wire:model="form.items.{{ $i }}.pills.{{ $pi }}"
                                       placeholder="GS Paper I">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
