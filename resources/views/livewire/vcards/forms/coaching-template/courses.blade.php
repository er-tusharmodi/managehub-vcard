{{-- coaching-template/courses.blade.php --}}
@php
$items = $form['items'] ?? [];
$fields = [
    ['key'=>'imageUrl',    'label'=>'Image',              'type'=>'image',    'span'=>'col-md-3'],
    ['key'=>'name',        'label'=>'Course Name',        'type'=>'text',     'span'=>'col-md-5', 'placeholder'=>'UPSC Foundation'],
    ['key'=>'tag',         'label'=>'Tag',                'type'=>'text',     'span'=>'col-md-4', 'placeholder'=>'IAS/IPS'],
    ['key'=>'successBadge','label'=>'Success Badge',      'type'=>'text',     'span'=>'col-md-4', 'placeholder'=>'800+ selections'],
    ['key'=>'iconClass',   'label'=>'Icon Class',         'type'=>'text',     'span'=>'col-md-4', 'placeholder'=>'bi-book'],
    ['key'=>'enquiryText', 'label'=>'WhatsApp Enquiry',   'type'=>'textarea', 'span'=>'col-12',   'placeholder'=>'I want to enquire about this course', 'rows'=>2],
];
@endphp

{{-- Section-level subtitle --}}
<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="courses-subtitle">Section Subtitle
        <small class="fw-normal text-muted">(shown below section heading, e.g. "Swipe →")</small>
    </label>
    <input type="text" id="courses-subtitle"
           class="form-control @error('form.subtitle') is-invalid @enderror"
           wire:model="form.subtitle"
           placeholder="Swipe →">
    @error('form.subtitle') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => 'items',
    'modelBase'   => 'form.items',
    'sectionKey'  => 'coaching-courses',
    'itemLabel'   => 'Course',
    'tableFields' => ['imageUrl', 'name', 'tag'],
    'fields'      => $fields,
])
