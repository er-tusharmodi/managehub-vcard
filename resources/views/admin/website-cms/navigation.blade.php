@extends('admin.layouts.app')

@section('title', 'Navigation Links')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Navigation Links</h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.website-cms', $page->slug) }}">Website CMS</a></li>
                <li class="breadcrumb-item active">Navigation</li>
            </ol>
        </div>
    </div>

    <livewire:website.cms-navigation :page="$page" />
@endsection
