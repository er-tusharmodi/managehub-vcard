@extends('frontend.layout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        @if (!empty($settings['site_tagline']))
                            <span class="text-muted">{{ $settings['site_tagline'] }}</span>
                        @endif
                    </div>
                    <h4 class="page-title">{{ $page->title }}</h4>
                </div>
            </div>
        </div>

        <div class="row align-items-center g-4 mb-4">
            <div class="col-lg-6">
                <h1 class="display-5 fw-semibold">{{ $page->hero_title ?: $page->title }}</h1>
                @if (!empty($page->hero_subtitle))
                    <p class="text-muted fs-16">{{ $page->hero_subtitle }}</p>
                @endif
                <div class="d-flex gap-3 mt-4">
                    <a href="{{ route('admin.login') }}" class="btn btn-primary">Admin Login</a>
                    @if (!empty($settings['contact_email']))
                        <a href="mailto:{{ $settings['contact_email'] }}" class="btn btn-outline-secondary">Contact Us</a>
                    @endif
                </div>
            </div>
            <div class="col-lg-6">
                @if (!empty($page->hero_image_path))
                    <img src="{{ Storage::url($page->hero_image_path) }}" class="img-fluid rounded-3 shadow-sm" alt="Hero image">
                @endif
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h3 class="fw-semibold">{{ $page->about_title ?: 'About Us' }}</h3>
                        <p class="text-muted">{{ $page->about_body ?: 'Add your about content from Website CMS.' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                @if (!empty($page->about_image_path))
                    <img src="{{ Storage::url($page->about_image_path) }}" class="img-fluid rounded-3 shadow-sm" alt="About image">
                @endif
            </div>
        </div>

    @php
        $services = $page->services ?? [];
        $testimonials = $page->testimonials ?? [];
        $faqs = $page->faqs ?? [];
    @endphp

    @if (count($services))
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="fw-semibold">Services</h3>
                </div>
            </div>
            <div class="row g-3">
                @foreach ($services as $service)
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="fw-semibold">{{ $service['title'] ?? 'Service' }}</h5>
                                <p class="text-muted">{{ $service['description'] ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if (count($testimonials))
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="fw-semibold mb-3">Testimonials</h3>
            </div>
            <div class="row g-3">
                @foreach ($testimonials as $testimonial)
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <p class="text-muted">"{{ $testimonial['quote'] ?? '' }}"</p>
                                <h6 class="fw-semibold mb-0">{{ $testimonial['name'] ?? 'Client' }}</h6>
                                <small class="text-muted">{{ $testimonial['role'] ?? '' }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if (count($faqs))
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="fw-semibold mb-3">FAQs</h3>
            </div>
            <div class="col-12">
                <div class="accordion" id="faqAccordion">
                    @foreach ($faqs as $index => $faq)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqHeading{{ $index }}">
                                <button class="accordion-button {{ $index ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $index }}" aria-expanded="{{ $index ? 'false' : 'true' }}">
                                    {{ $faq['question'] ?? 'Question' }}
                                </button>
                            </h2>
                            <div id="faqCollapse{{ $index }}" class="accordion-collapse collapse {{ $index ? '' : 'show' }}" aria-labelledby="faqHeading{{ $index }}" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    {{ $faq['answer'] ?? '' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    </div>
@endsection
