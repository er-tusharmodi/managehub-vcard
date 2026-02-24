@extends('client.layouts.app')

@section('title', 'Edit vCard - ' . $vcard->client_name)

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">
                <i class="mdi mdi-pencil me-2 text-primary"></i>Edit vCard
            </h4>
            <div class="text-muted small">
                <i class="mdi mdi-link"></i> {{ $vcard->subdomain }}.{{ config('vcard.base_domain') }}
                <a href="{{ url('/' . $vcard->subdomain) }}" target="_blank" class="ms-2">
                    <i class="mdi mdi-open-in-new"></i> Preview
                </a>
            </div>
        </div>
        <div class="text-end">
            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">
                <i class="mdi mdi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @livewire('Vcards.ClientSectionEditor', [
        'subdomain' => $vcard->subdomain,
        'section' => $section ?? null,
    ])
@endsection
