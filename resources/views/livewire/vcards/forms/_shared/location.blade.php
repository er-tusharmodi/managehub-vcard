{{--
 | _shared/location.blade.php
 | Location / address block — used by templates where location is a simple dict.
 | Handles: electronics {titleLine,addressLine,mapLabel}, mens-salon {title,address,mapLabel},
 |          restaurant {name,address,mapLabel,transport}, sweetshop {line1,line2,mapButtonLabel},
 |          doctor {clinicName,line1,line2,mapLabel}.
 |
 | Rules: address/line/paragraph fields → textarea | maps/map URL fields → url input
 | Everything else → text input.
--}}

@php
    $textareaKeys = ['address', 'addressLine', 'line1', 'line2', 'line3', 'line4',
                     'transport', 'paragraph', 'paragraph1', 'paragraph2', 'note', 'directionsText'];
    $urlKeys      = ['mapUrl', 'mapEmbed', 'maps', 'gmaps', 'mapsUrl'];
@endphp

<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-map-marker-outline me-1"></i>Location Details
    </h6>
</div>

@foreach($form as $locKey => $locVal)
    @if(is_array($locVal)) @continue @endif
    @php
        $isTA  = in_array($locKey, $textareaKeys) || preg_match('/(address|line\d|paragraph|transport|note)/i', $locKey);
        $isUrl = in_array($locKey, $urlKeys) || preg_match('/(mapUrl|mapsUrl|gmaps)/i', $locKey);
        $colClass = $isTA ? 'col-12' : 'col-lg-6';
        $locLabel = \Illuminate\Support\Str::headline($locKey);
    @endphp
    <div class="{{ $colClass }} mb-3">
        <label class="form-label fw-semibold" for="loc-{{ $locKey }}">{{ $locLabel }}</label>
        @if($isTA)
            <textarea id="loc-{{ $locKey }}"
                      class="form-control @error('form.' . $locKey) is-invalid @enderror"
                      wire:model="form.{{ $locKey }}"
                      rows="2"></textarea>
        @elseif($isUrl)
            <input type="url"
                   id="loc-{{ $locKey }}"
                   class="form-control @error('form.' . $locKey) is-invalid @enderror"
                   wire:model="form.{{ $locKey }}"
                   placeholder="https://maps.google.com/?q=…">
        @else
            <input type="text"
                   id="loc-{{ $locKey }}"
                   class="form-control @error('form.' . $locKey) is-invalid @enderror"
                   wire:model="form.{{ $locKey }}">
        @endif
        @error('form.' . $locKey)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
@endforeach
