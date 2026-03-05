{{-- minimart-template/sections.blade.php — nested config: editable fields from location, hours, qr, contact --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-view-module me-1"></i>Section Data
    </h6>
    <p class="text-muted small mb-0">Location, hours, QR and contact sub-section content</p>
</div>

{{-- Location --}}
<div class="col-12 mt-1">
    <div class="card border-0 bg-light mb-2">
        <div class="card-body p-2">
            <h6 class="fw-semibold mb-2" style="font-size:.8rem;"><i class="mdi mdi-map-marker me-1"></i>Location</h6>
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label small mb-1">Address Line 1</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.location.addressLine1" placeholder="42 Green Park Market, Sector 5">
                </div>
                <div class="col-md-6">
                    <label class="form-label small mb-1">Address Line 2</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.location.addressLine2" placeholder="New Delhi, Delhi – 110016, India">
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Business Hours --}}
<div class="col-12">
    <div class="card border-0 bg-light mb-2">
        <div class="card-body p-2">
            <h6 class="fw-semibold mb-2" style="font-size:.8rem;"><i class="mdi mdi-clock-outline me-1"></i>Business Hours</h6>
            @if(is_array($form['hours']['rows'] ?? null))
            @foreach(($form['hours']['rows'] ?? []) as $ri => $row)
            <div class="row g-1 mb-1 align-items-center" wire:key="mmhr-{{ $ri }}">
                <div class="col-sm-3">
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.hours.rows.{{ $ri }}.day" placeholder="Monday">
                </div>
                <div class="col-sm-2">
                    <select class="form-select form-select-sm"
                            wire:model="form.hours.rows.{{ $ri }}.status">
                        <option value="open">Open</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
                <div class="col-sm-7">
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.hours.rows.{{ $ri }}.time" placeholder="7:00 am – 10:00 pm">
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>

{{-- QR --}}
<div class="col-12">
    <div class="card border-0 bg-light mb-2">
        <div class="card-body p-2">
            <h6 class="fw-semibold mb-2" style="font-size:.8rem;"><i class="mdi mdi-qrcode me-1"></i>QR Section</h6>
            <label class="form-label small mb-1">Help Text</label>
            <input type="text" class="form-control form-control-sm"
                   wire:model="form.qr.helpText" placeholder="Scan QR code to visit this page & save contact">
        </div>
    </div>
</div>

{{-- Contact Form Placeholders --}}
<div class="col-12">
    <div class="card border-0 bg-light">
        <div class="card-body p-2">
            <h6 class="fw-semibold mb-2" style="font-size:.8rem;"><i class="mdi mdi-email me-1"></i>Contact Form Placeholders</h6>
            @foreach(['name' => 'Name placeholder', 'phone' => 'Phone placeholder', 'email' => 'Email placeholder', 'message' => 'Message placeholder'] as $fld => $lbl)
            <div class="row g-1 mb-1 align-items-center">
                <div class="col-sm-4"><small class="text-muted">{{ $lbl }}</small></div>
                <div class="col-sm-8">
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.contact.form.{{ $fld }}Placeholder" placeholder="{{ $lbl }}">
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
