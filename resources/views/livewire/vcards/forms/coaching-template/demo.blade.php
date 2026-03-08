{{--
 | coaching-template/demo.blade.php
 | Demo section editor: title, promo, slotTitle, slot table (add/edit/delete), show/hide toggle
--}}

{{-- ── Section Header ── --}}
<div class="col-12 mb-3">
    <div class="d-flex align-items-center justify-content-between mb-1 pb-2 border-bottom">
        <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
            <i class="mdi mdi-video-outline me-1"></i>Free Demo Class Section
        </h6>
        @if(isset($sectionsConfig['demo']))
            @php $demoEnabled = $sectionsConfig['demo']['enabled'] ?? true; @endphp
            <div wire:click="toggleSection('demo')" style="cursor:pointer;" title="{{ $demoEnabled ? 'Click to hide this section' : 'Click to show this section' }}">
                <span class="badge rounded-pill {{ $demoEnabled ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                    <i class="mdi {{ $demoEnabled ? 'mdi-eye' : 'mdi-eye-off' }} me-1"></i>
                    {{ $demoEnabled ? 'Section Visible' : 'Section Hidden' }}
                </span>
            </div>
        @endif
    </div>
</div>

{{-- ── Section Title ── --}}
<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="demo-title">Section Heading</label>
    <input type="text" id="demo-title"
           class="form-control @error('form.title') is-invalid @enderror"
           wire:model="form.title"
           placeholder="Book a Free Demo Class">
    @error('form.title') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="demo-slotTitle">Slot Picker Heading</label>
    <input type="text" id="demo-slotTitle"
           class="form-control @error('form.slotTitle') is-invalid @enderror"
           wire:model="form.slotTitle"
           placeholder="Choose Your Demo Slot">
    @error('form.slotTitle') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- ── Promo Bar ── --}}
<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="demo-promoTitle">Promo Title</label>
    <input type="text" id="demo-promoTitle"
           class="form-control @error('form.promoTitle') is-invalid @enderror"
           wire:model="form.promoTitle"
           placeholder="Experience Before You Enroll">
    @error('form.promoTitle') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="demo-promoText">Promo Text</label>
    <textarea id="demo-promoText" rows="2"
              class="form-control @error('form.promoText') is-invalid @enderror"
              wire:model="form.promoText"
              placeholder="Book your free demo class today..."></textarea>
    @error('form.promoText') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- ── Demo Slots Table ── --}}
@php $slots = $form['slots'] ?? []; @endphp

@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $slots,
    'addPath'     => 'slots',
    'modelBase'   => 'form.slots',
    'sectionKey'  => 'demoslots',
    'itemLabel'   => 'Demo Slot',
    'tableFields' => ['day', 'time', 'topic', 'mode'],
    'fields'      => [
        ['key' => 'slot',  'label' => 'Slot Name',    'type' => 'text',   'placeholder' => 'Saturday – Classroom Demo', 'span' => 'col-md-12'],
        ['key' => 'day',   'label' => 'Day',          'type' => 'text',   'placeholder' => 'Saturday'],
        ['key' => 'time',  'label' => 'Time',         'type' => 'text',   'placeholder' => '10:00 – 12:00 PM'],
        ['key' => 'topic', 'label' => 'Topic',        'type' => 'text',   'placeholder' => 'UPSC GS Strategy'],
        ['key' => 'mode',  'label' => 'Mode',         'type' => 'text',   'placeholder' => 'Offline · Classroom'],
        ['key' => 'selected', 'label' => 'Pre-selected', 'type' => 'toggle', 'placeholder' => 'Select this slot by default'],
    ],
])

