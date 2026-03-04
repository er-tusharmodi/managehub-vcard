{{--
 | coaching-template/location.blade.php
 | Location section for coaching-template.
 | mapEmbed = raw iframe HTML from Google Maps → must be textarea.
--}}

<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-map-marker-outline me-1"></i>Location Details
    </h6>
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="loc-title">Location Section Title</label>
    <input type="text"
           id="loc-title"
           class="form-control @error('form.title') is-invalid @enderror"
           wire:model="form.title"
           placeholder="Find Us Here">
    @error('form.title') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="loc-mapEmbed">Google Maps Embed Code</label>
    <textarea id="loc-mapEmbed"
              class="form-control font-monospace @error('form.mapEmbed') is-invalid @enderror"
              wire:model="form.mapEmbed"
              rows="4"
              placeholder='&lt;iframe src="https://www.google.com/maps/embed?pb=..." ...&gt;&lt;/iframe&gt;'></textarea>
    @error('form.mapEmbed') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <small class="text-muted">
        <i class="mdi mdi-information-outline me-1"></i>
        Go to <a href="https://maps.google.com" target="_blank" class="text-primary">Google Maps</a>
        → Share → Embed a map → Copy HTML
    </small>
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="loc-address">Full Address</label>
    <textarea id="loc-address"
              class="form-control @error('form.address') is-invalid @enderror"
              wire:model="form.address"
              rows="2"
              placeholder="Building, Street, City, State, PIN"></textarea>
    @error('form.address') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="loc-directionsText">Directions / Get Here Text</label>
    <textarea id="loc-directionsText"
              class="form-control @error('form.directionsText') is-invalid @enderror"
              wire:model="form.directionsText"
              rows="2"
              placeholder="Near landmark, metro station, etc."></textarea>
    @error('form.directionsText') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
