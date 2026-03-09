@extends('admin.layouts.app')

@section('title', 'Scripts & SEO Injection')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Scripts & SEO Injection</h4>
        </div>

        <div class="text-end">
            <a href="{{ route('admin.website-cms', $page) }}" class="btn btn-secondary btn-sm">
                <i class="mdi mdi-arrow-left"></i> Back to CMS
            </a>
        </div>
    </div>

    <livewire:website.cms-scripts :page="$page" />
@endsection
