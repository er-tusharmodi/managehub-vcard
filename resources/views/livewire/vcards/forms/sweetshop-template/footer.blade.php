{{-- sweetshop-template/footer.blade.php — {copyright, brand, rights, poweredBy, poweredBrand} --}}

@php
    $autoName = isset($vcard)
        ? (data_get($vcard->data_content ?? [], '_common.name', ''))
        : '';
@endphp

<div class="col-12 mb-3">
    <div class="d-flex align-items-center gap-2 pb-2 border-bottom">
        <span class="avatar-xs flex-shrink-0">
            <span class="avatar-title rounded-circle bg-soft-secondary text-secondary font-size-18">
                <i class="mdi mdi-copyright"></i>
            </span>
        </span>
        <div>
            <h6 class="mb-0 fw-semibold">Footer / Copyright</h6>
            <small class="text-muted">Shown at the bottom of the vCard</small>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="card border-0 shadow-sm" style="border-radius:.85rem;">
        <div class="card-body p-3">

            {{-- Live preview --}}
            <div class="mb-3 p-2 rounded-2 text-center" style="background:#f8fafc;border:1px dashed #cbd5e1;font-size:.8rem;color:#555;">
                <span>{{ $form['copyright'] ?? '© ' . date('Y') }}</span>
                <strong class="mx-1">{{ $form['brand'] ?: ($autoName ?: '—') }}</strong>
                <span>·</span>
                <span>{{ $form['rights'] ?? 'All Rights Reserved' }}</span>
            </div>

            <div class="row g-3">

                {{-- Copyright Year --}}
                <div class="col-sm-4">
                    <label class="form-label small fw-semibold mb-1">Copyright Year</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.copyright"
                           placeholder="© {{ date('Y') }}">
                    <small class="text-muted">e.g. <code>© {{ date('Y') }}</code></small>
                </div>

                {{-- Rights Text --}}
                <div class="col-sm-8">
                    <label class="form-label small fw-semibold mb-1">Rights Text</label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.rights"
                           placeholder="All Rights Reserved">
                </div>

                {{-- Business Name override --}}
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">
                        Business Name
                        <span class="badge bg-success-subtle text-success-emphasis fw-normal ms-1" style="font-size:.65rem;">Auto</span>
                    </label>
                    <input type="text" class="form-control form-control-sm"
                           wire:model="form.brand"
                           placeholder="{{ $autoName ?: 'Auto from General Info' }}">
                    @if($autoName)
                    <small class="text-muted">
                        <i class="mdi mdi-information-outline me-1"></i>Leave blank to auto-use <strong>{{ $autoName }}</strong> from General Info
                    </small>
                    @else
                    <small class="text-muted">
                        <i class="mdi mdi-information-outline me-1"></i>Leave blank to auto-use the Business Name from General Info
                    </small>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
