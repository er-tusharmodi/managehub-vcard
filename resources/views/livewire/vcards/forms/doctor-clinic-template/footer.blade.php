{{-- doctor-clinic-template/footer.blade.php — {line1, line2, line3, line4} --}}
{{--
    line1 = tagline / note (e.g. "This is a verified digital clinic card")
    line2 = doctor / clinic name (bold, accent color) — falls back to _common.name
    line3 = qualifications / details (e.g. "MBBS, MD · Reg. MH-12345")
    line4 = emergency note (e.g. "For medical emergencies call 112")
--}}

@php
    $autoName = isset($vcard)
        ? (data_get($vcard->data_content ?? [], '_common.name', ''))
        : '';
@endphp

<div class="col-12 mb-3">
    <div class="d-flex align-items-center gap-2 pb-2 border-bottom">
        <span class="avatar-xs flex-shrink-0">
            <span class="avatar-title rounded-circle bg-soft-teal text-teal font-size-18">
                <i class="mdi mdi-card-text-outline"></i>
            </span>
        </span>
        <div>
            <h6 class="mb-0 fw-semibold">Footer Text</h6>
            <small class="text-muted">Four lines shown at the bottom of the vCard</small>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="card border-0 shadow-sm" style="border-radius:.85rem;">
        <div class="card-body p-3">

            {{-- Live preview --}}
            <div class="mb-3 p-2 rounded-2 text-center" style="background:#f0fdfa;border:1px dashed #99f6e4;font-size:.78rem;color:#555;line-height:1.7;">
                <div>{{ $form['line1'] ?? '' }}</div>
                <div><strong style="color:#0d9488;">{{ $form['line2'] ?: ($autoName ?: '—') }}</strong></div>
                <div>{{ $form['line3'] ?? '' }}</div>
                <div style="font-size:.7rem;color:#aaa;">{{ $form['line4'] ?? '' }}</div>
            </div>

            <div class="row g-3">

                {{-- Line 1 --}}
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Line 1 <span class="text-muted fw-normal">(tagline / verification note)</span></label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.line1"
                           placeholder="This is a verified digital clinic card">
                </div>

                {{-- Line 2 — Doctor/Clinic Name --}}
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">
                        Line 2 — Name
                        <span class="badge bg-success-subtle text-success-emphasis fw-normal ms-1" style="font-size:.65rem;">Auto</span>
                    </label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.line2"
                           placeholder="{{ $autoName ?: 'Auto from General Info' }}">
                    @if($autoName)
                    <small class="text-muted">
                        <i class="mdi mdi-information-outline me-1"></i>Leave blank to auto-use <strong>{{ $autoName }}</strong> from General Info
                    </small>
                    @else
                    <small class="text-muted">
                        <i class="mdi mdi-information-outline me-1"></i>Leave blank to auto-use the name from General Info
                    </small>
                    @endif
                </div>

                {{-- Line 3 --}}
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Line 3 <span class="text-muted fw-normal">(qualifications / registration)</span></label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.line3"
                           placeholder="MBBS, MD · Reg. MH-12345">
                </div>

                {{-- Line 4 --}}
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Line 4 <span class="text-muted fw-normal">(emergency / disclaimer note)</span></label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.line4"
                           placeholder="For medical emergencies call 112">
                </div>

            </div>
        </div>
    </div>
</div>
