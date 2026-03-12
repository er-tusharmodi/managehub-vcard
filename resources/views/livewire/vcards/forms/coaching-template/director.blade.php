{{--
 | coaching-template/director.blade.php
 | Director / founder spotlight: name, role, message, initials, badges[].
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-account-tie me-1"></i>Director / Founder
    </h6>
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="dir-name">Name</label>
    <input type="text" id="dir-name"
           class="form-control @error('form.name') is-invalid @enderror"
           wire:model="form.name" placeholder="Dr. Rajesh Sharma">
    @error('form.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-4 mb-3">
    <label class="form-label fw-semibold" for="dir-role">Role / Designation</label>
    <input type="text" id="dir-role"
           class="form-control @error('form.role') is-invalid @enderror"
           wire:model="form.role" placeholder="Founder & Director">
    @error('form.role') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="dir-msg">Message / Quote</label>
    <textarea id="dir-msg" rows="3"
              class="form-control @error('form.message') is-invalid @enderror"
              wire:model="form.message"
              placeholder="Our mission is to transform students into confident civil servants..."></textarea>
    @error('form.message') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

@php $badges = $form['badges'] ?? []; @endphp
@if(!empty($badges))
<div class="col-12 mb-1">
    <label class="form-label fw-semibold text-muted small text-uppercase">Credential Badges</label>
</div>
@foreach($badges as $bi => $badge)
<div class="col-lg-6 mb-2" wire:key="dirbadge-{{ $bi }}">
    <div class="row g-2">
        <div class="col-7">
            <input type="text" class="form-control form-control-sm"
                   wire:model="form.badges.{{ $bi }}.label"
                   placeholder="IRS Retd.">
        </div>
        <div class="col-5">
            <input type="text" class="form-control form-control-sm"
                   wire:model="form.badges.{{ $bi }}.iconClass"
                   placeholder="bi-trophy-fill">
        </div>
    </div>
</div>
@endforeach
@endif
