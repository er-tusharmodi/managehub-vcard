{{--
 | doctor-clinic-template/doctor.blade.php
 | Doctor profile data: name, role, qualification, regNumber, clinicName, vcardTitle, vcardOrg, vcardNote
 | Contact fields (phone, email, address, maps, website) are managed in Basic Info (_common).
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-doctor me-1"></i>Doctor Details
    </h6>
    <small class="text-muted d-block mb-2">Contact details (phone, email, address) are managed in <strong>Basic Info</strong>.</small>
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="doc-name">Doctor Name</label>
    <input type="text" id="doc-name"
           class="form-control @error('form.name') is-invalid @enderror"
           wire:model="form.name" placeholder="Dr. Priya Sharma">
    @error('form.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="doc-role">Specialization / Role</label>
    <input type="text" id="doc-role"
           class="form-control @error('form.role') is-invalid @enderror"
           wire:model="form.role" placeholder="Senior Cardiologist">
    @error('form.role') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="doc-qual">Qualification</label>
    <input type="text" id="doc-qual"
           class="form-control @error('form.qualification') is-invalid @enderror"
           wire:model="form.qualification" placeholder="MBBS, MD (Cardiology), AIIMS Delhi">
    @error('form.qualification') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-3 mb-3">
    <label class="form-label fw-semibold" for="doc-reg">Registration No.</label>
    <input type="text" id="doc-reg"
           class="form-control @error('form.regNumber') is-invalid @enderror"
           wire:model="form.regNumber" placeholder="MCI-12345">
    @error('form.regNumber') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-9 mb-3">
    <label class="form-label fw-semibold" for="doc-clinic">Clinic / Hospital Name</label>
    <input type="text" id="doc-clinic"
           class="form-control @error('form.clinicName') is-invalid @enderror"
           wire:model="form.clinicName" placeholder="Sharma Heart Care Centre">
    @error('form.clinicName') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-12">
    <h6 class="fw-semibold text-muted text-uppercase mb-2 mt-1" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-card-account-details-outline me-1"></i>VCard Fields
    </h6>
</div>

<div class="col-lg-4 mb-3">
    <label class="form-label fw-semibold" for="doc-vtitle">vCard Title</label>
    <input type="text" id="doc-vtitle"
           class="form-control @error('form.vcardTitle') is-invalid @enderror"
           wire:model="form.vcardTitle" placeholder="Dr. Priya Sharma">
    @error('form.vcardTitle') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-4 mb-3">
    <label class="form-label fw-semibold" for="doc-vorg">vCard Organisation</label>
    <input type="text" id="doc-vorg"
           class="form-control @error('form.vcardOrg') is-invalid @enderror"
           wire:model="form.vcardOrg" placeholder="Sharma Heart Care Centre">
    @error('form.vcardOrg') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-4 mb-3">
    <label class="form-label fw-semibold" for="doc-vnote">vCard Note</label>
    <input type="text" id="doc-vnote"
           class="form-control @error('form.vcardNote') is-invalid @enderror"
           wire:model="form.vcardNote" placeholder="Senior Cardiologist">
    @error('form.vcardNote') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
