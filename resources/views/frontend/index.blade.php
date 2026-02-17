<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $page->meta_title ?: 'ManageHub — Smart vCards, Seamlessly Shared' }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        />
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: "{{ $settings['primary_color'] ?? '#4F46E5' }}",
                            "primary-dark": "{{ $settings['secondary_color'] ?? '#4338CA' }}",
                            "bg-light": "#F9FAFB",
                            "card-bg": "#FFFFFF",
                            "border-light": "#E5E7EB",
                        },
                    },
                },
            };
        </script>
        <style>
            @import url("https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap");
            body {
                font-family:
                    "Inter",
                    -apple-system,
                    BlinkMacSystemFont,
                    "Segoe UI",
                    sans-serif;
            }
            .gradient-text {
                background: linear-gradient(90deg, #4f46e5, #10b981);
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
            }
        </style>
    </head>
    <body class="bg-gray-50 text-gray-800">
        @php
            $headerCta = $page->header_cta ?? [];
            $heroButtons = $page->hero_buttons ?? [];
            $heroPrimary = $heroButtons[0] ?? [];
            $heroSecondary = $heroButtons[1] ?? [];
            $categories = $page->categories ?? [];
            $categoryItems = $categories['items'] ?? [];
            $vcard = $page->vcard_preview ?? [];
            $how = $page->how_it_works ?? [];
            $howSteps = $how['steps'] ?? [];
            $cta = $page->cta_section ?? [];
            $footerLinks = $page->footer_links ?? [];
            $productLinks = $footerLinks['product'] ?? [];
            $resourceLinks = $footerLinks['resources'] ?? [];

            if (!count($categoryItems)) {
                $categoryItems = [
                    [
                        'title' => 'Business',
                        'description' => 'For companies, teams & departments — embed logos, team members, locations & more.',
                        'icon' => 'fas fa-building',
                        'icon_bg' => 'bg-blue-100',
                        'icon_color' => 'text-blue-600',
                    ],
                    [
                        'title' => 'Professional',
                        'description' => 'Lawyers, doctors, consultants — highlight credentials, certifications & contact workflows.',
                        'icon' => 'fas fa-user-tie',
                        'icon_bg' => 'bg-emerald-100',
                        'icon_color' => 'text-emerald-600',
                    ],
                    [
                        'title' => 'Creative',
                        'description' => 'Designers, photographers, musicians — showcase portfolios, social links & booking buttons.',
                        'icon' => 'fas fa-paint-brush',
                        'icon_bg' => 'bg-purple-100',
                        'icon_color' => 'text-purple-600',
                    ],
                    [
                        'title' => 'Personal',
                        'description' => 'Friends, family, networking — simple, warm, privacy-aware sharing for life moments.',
                        'icon' => 'fas fa-users',
                        'icon_bg' => 'bg-amber-100',
                        'icon_color' => 'text-amber-600',
                    ],
                    [
                        'title' => 'Event',
                        'description' => 'Conferences, weddings, meetups — include RSVP, maps, schedules & guest lists.',
                        'icon' => 'fas fa-calendar-day',
                        'icon_bg' => 'bg-cyan-100',
                        'icon_color' => 'text-cyan-600',
                    ],
                ];
            }

            if (!count($howSteps)) {
                $howSteps = [
                    [
                        'number' => '1',
                        'title' => 'Create',
                        'description' => 'Pick a category, customize design, add contact info, links & CTAs.',
                        'badge_bg' => 'bg-blue-100',
                        'badge_text' => 'text-blue-700',
                    ],
                    [
                        'number' => '2',
                        'title' => 'Share',
                        'description' => 'Get a unique short link (e.g. managehub.io/v/yourname) — share anywhere.',
                        'badge_bg' => 'bg-emerald-100',
                        'badge_text' => 'text-emerald-700',
                    ],
                    [
                        'number' => '3',
                        'title' => 'Track & Update',
                        'description' => 'See who viewed your card, when & where — update info anytime. No re-sharing!',
                        'badge_bg' => 'bg-purple-100',
                        'badge_text' => 'text-purple-700',
                    ],
                ];
            }

            if (!count($productLinks)) {
                $productLinks = [
                    ['label' => 'Features', 'url' => '#'],
                    ['label' => 'Templates', 'url' => '#'],
                    ['label' => 'Analytics', 'url' => '#'],
                    ['label' => 'Integrations', 'url' => '#'],
                ];
            }

            if (!count($resourceLinks)) {
                $resourceLinks = [
                    ['label' => 'Blog', 'url' => '#'],
                    ['label' => 'Help Center', 'url' => '#'],
                    ['label' => 'API Docs', 'url' => '#'],
                    ['label' => 'Status', 'url' => '#'],
                ];
            }
        @endphp
        <!-- Navigation -->
        <header
            class="sticky top-0 z-50 bg-white/80 backdrop-blur-sm border-b border-border-light"
        >
            <div
                class="container mx-auto px-4 py-4 flex justify-between items-center"
            >
                <div class="flex items-center space-x-2">
                    <div
                        class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center"
                    >
                        <span class="text-white font-bold text-xl">MH</span>
                    </div>
                    <span class="text-xl font-bold text-gray-900"
                        >Manage<span class="gradient-text">Hub</span></span
                    >
                </div>
                <nav class="hidden md:flex space-x-8">
                    <a
                        href="#features"
                        class="font-medium hover:text-primary transition"
                        >Features</a
                    >
                    <a
                        href="#categories"
                        class="font-medium hover:text-primary transition"
                        >Categories</a
                    >
                    <a
                        href="#how-it-works"
                        class="font-medium hover:text-primary transition"
                        >How It Works</a
                    >
                    <a
                        href="#contact"
                        class="font-medium hover:text-primary transition"
                        >Contact</a
                    >
                </nav>
                <a
                    href="{{ $headerCta['url'] ?? '#' }}"
                    class="px-5 py-2 bg-primary text-white font-medium rounded-lg hover:bg-primary-dark transition shadow-sm"
                >
                    {{ $headerCta['label'] ?? 'Get Started' }}
                </a>
            </div>
        </header>

        <!-- Hero Section -->
        <section
            class="py-16 md:py-24 bg-gradient-to-br from-blue-50 to-emerald-50"
        >
            <div class="container mx-auto px-4 text-center max-w-3xl">
                <h1
                    class="text-4xl md:text-6xl font-extrabold leading-tight mb-6"
                >
                    {{ $page->hero_title ?: 'vCards,' }}
                    <span class="gradient-text">{{ $page->hero_title_highlight ?: 'Reimagined' }}</span>.
                </h1>
                <p class="text-xl text-gray-600 mb-10 max-w-2xl mx-auto">
                    {{ $page->hero_subtitle ?: 'ManageHub helps businesses, creators, and professionals share rich, interactive digital business cards — instantly, beautifully, and with purpose.' }}
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a
                        href="{{ $heroPrimary['url'] ?? '#' }}"
                        class="px-8 py-3 bg-primary text-white font-semibold rounded-lg shadow-lg hover:bg-primary-dark transition transform hover:-translate-y-0.5"
                    >
                        {{ $heroPrimary['label'] ?? 'Create Your vCard →' }}
                    </a>
                    <a
                        href="{{ $heroSecondary['url'] ?? '#' }}"
                        class="px-8 py-3 bg-white text-primary font-semibold rounded-lg border border-primary hover:bg-gray-50 transition"
                    >
                        {{ $heroSecondary['label'] ?? 'View Demo' }}
                    </a>
                </div>
            </div>
        </section>

        <!-- Categories Section -->
        <section id="categories" class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">
                        {{ $categories['title'] ?? 'vCards Built for Every' }}
                        <span class="gradient-text">{{ $categories['highlight'] ?? 'Category' }}</span>{{ $categories['suffix'] ?? '' }}
                    </h2>
                    <p class="text-gray-600">
                        {{ $categories['subtitle'] ?? 'Choose the perfect template — optimized for your role, industry, or use case.' }}
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6"
                >
                    @foreach ($categoryItems as $item)
                        @php
                            $bgClass = $item['icon_bg'] ?? 'bg-blue-100';
                            $textClass = $item['icon_color'] ?? 'text-blue-600';
                            $isBgHex = strpos($bgClass, '#') === 0;
                            $isTextHex = strpos($textClass, '#') === 0;
                        @endphp
                        <div
                            class="bg-card-bg rounded-2xl p-6 border border-border-light hover:shadow-md transition shadow-sm"
                        >
                            <div
                                @if ($isBgHex)style="background-color: {{ $bgClass }}; width: 56px; height: 56px;" class="rounded-xl flex items-center justify-center mb-4"@else class="w-14 h-14 rounded-xl {{ $bgClass }} flex items-center justify-center mb-4"@endif
                            >
                                <i
                                    @if ($isTextHex)style="color: {{ $textClass }};" @endif
                                    class="{{ $item['icon'] ?? 'fas fa-building' }} {{ !$isTextHex ? $textClass : '' }} text-xl"
                                ></i>
                            </div>
                            <h3 class="text-xl font-bold mb-2">{{ $item['title'] ?? '' }}</h3>
                            <p class="text-gray-600 text-sm">
                                {{ $item['description'] ?? '' }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- vCard Preview Mockup -->
        <section class="py-16 bg-bg-light">
            <div class="container mx-auto px-4 max-w-5xl">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">
                        {{ $vcard['heading'] ?? 'A vCard That' }}
                        <span class="gradient-text">{{ $vcard['highlight'] ?? 'Does More' }}</span>{{ $vcard['suffix'] ?? '' }}
                    </h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        {{ $vcard['subheading'] ?? 'Interactive. Trackable. Brand-aligned. One link. Infinite updates.' }}
                    </p>
                </div>

                <div
                    class="bg-white rounded-2xl shadow-xl overflow-hidden border border-border-light max-w-3xl mx-auto"
                >
                    <div class="bg-gray-900 px-6 py-4 flex items-center">
                        <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                        <div
                            class="w-3 h-3 rounded-full bg-yellow-500 mr-2"
                        ></div>
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        <span class="ml-auto text-gray-400 text-sm font-mono"
                            >managehub.io/v/alex</span
                        >
                    </div>
                    <div class="p-6">
                        <div class="flex items-start space-x-4">
                            <div
                                class="w-20 h-20 rounded-full bg-gradient-to-br from-primary to-emerald-500 flex items-center justify-center text-white font-bold text-xl"
                            >
                                {{ $vcard['initials'] ?? 'A' }}
                            </div>
                            <div>
                                <h3 class="text-xl font-bold">{{ $vcard['name'] ?? 'Alex Morgan' }}</h3>
                                <p class="text-primary font-medium">
                                    {{ $vcard['role'] ?? 'Senior Product Designer' }}
                                </p>
                                <p class="text-gray-600 text-sm mt-1">
                                    {{ $vcard['company'] ?? 'TechNova Labs' }} • {{ $vcard['location'] ?? 'San Francisco' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 space-y-3">
                            <div class="flex items-center">
                                <i
                                    class="fas fa-envelope text-gray-500 mr-3 text-lg"
                                ></i>
                                <a
                                    href="mailto:{{ $vcard['email'] ?? 'alex@technova.dev' }}"
                                    class="text-gray-700 hover:text-primary transition"
                                    >{{ $vcard['email'] ?? 'alex@technova.dev' }}</a
                                >
                            </div>
                            <div class="flex items-center">
                                <i
                                    class="fas fa-phone text-gray-500 mr-3 text-lg"
                                ></i>
                                <a
                                    href="tel:{{ $vcard['phone'] ?? '+14155550199' }}"
                                    class="text-gray-700 hover:text-primary transition"
                                    >{{ $vcard['phone'] ?? '+1 (415) 555-0199' }}</a
                                >
                            </div>
                            <div class="flex items-center">
                                <i
                                    class="fab fa-linkedin-in text-gray-500 mr-3 text-lg"
                                ></i>
                                <a
                                    href="{{ $vcard['linkedin_url'] ?? '#' }}"
                                    class="text-gray-700 hover:text-primary transition"
                                    >{{ $vcard['linkedin_label'] ?? 'linkedin.com/in/alexmorgan' }}</a
                                >
                            </div>
                            <div class="flex items-center">
                                <i
                                    class="fab fa-dribbble text-gray-500 mr-3 text-lg"
                                ></i>
                                <a
                                    href="{{ $vcard['dribbble_url'] ?? '#' }}"
                                    class="text-gray-700 hover:text-primary transition"
                                    >{{ $vcard['dribbble_label'] ?? 'dribbble.com/alexmorgan' }}</a
                                >
                            </div>
                        </div>

                        <div class="mt-8 flex flex-wrap gap-2">
                            <button
                                class="px-4 py-2 bg-primary text-white text-sm rounded-lg flex items-center"
                            >
                                <i class="fas fa-download mr-2"></i> Save
                                Contact
                            </button>
                            <button
                                class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg flex items-center"
                            >
                                <i class="fas fa-share-alt mr-2"></i> Share Link
                            </button>
                            <button
                                class="px-4 py-2 bg-emerald-100 text-emerald-700 text-sm rounded-lg flex items-center"
                            >
                                <i class="fas fa-calendar-check mr-2"></i> Book
                                Time
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section id="how-it-works" class="py-16 bg-white">
            <div class="container mx-auto px-4 max-w-4xl">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">
                        {{ $how['title'] ?? 'How Manage' }}<span class="gradient-text">{{ $how['highlight'] ?? 'Hub' }}</span>{{ $how['suffix'] ?? ' Works' }}
                    </h2>
                    <p class="text-gray-600">
                        {{ $how['subtitle'] ?? 'Three simple steps — no tech skills needed.' }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($howSteps as $step)
                        <div class="text-center">
                            <div
                                class="w-16 h-16 rounded-full {{ $step['badge_bg'] ?? 'bg-blue-100' }} flex items-center justify-center mx-auto mb-4"
                            >
                                <span class="{{ $step['badge_text'] ?? 'text-blue-700' }} text-xl font-bold"
                                    >{{ $step['number'] ?? '' }}</span
                                >
                            </div>
                            <h3 class="text-xl font-bold mb-2">{{ $step['title'] ?? '' }}</h3>
                            <p class="text-gray-600">
                                {{ $step['description'] ?? '' }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section
            class="py-16 bg-gradient-to-r from-primary to-emerald-500 text-white"
        >
            <div class="container mx-auto px-4 text-center max-w-3xl">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">
                    {{ $cta['title'] ?? 'Ready to Elevate Your Digital Presence?' }}
                </h2>
                <p class="text-blue-100 text-xl mb-10 max-w-2xl mx-auto">
                    {{ $cta['subtitle'] ?? 'Join thousands of professionals already using ManageHub to make every connection count.' }}
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a
                        href="{{ $cta['primary_url'] ?? '#' }}"
                        class="px-8 py-3 bg-white text-primary font-bold rounded-lg shadow-lg hover:bg-gray-100 transition"
                    >
                        {{ $cta['primary_label'] ?? 'Start Free Trial' }}
                    </a>
                    <a
                        href="{{ $cta['secondary_url'] ?? '#' }}"
                        class="px-8 py-3 bg-transparent border-2 border-white text-white font-bold rounded-lg hover:bg-black/10 transition"
                    >
                        {{ $cta['secondary_label'] ?? 'Schedule a Demo' }}
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer id="contact" class="py-12 bg-gray-900 text-gray-400">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <div class="flex items-center space-x-2 mb-4">
                            <div
                                class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center"
                            >
                                <span class="text-white font-bold">MH</span>
                            </div>
                            <span class="text-white font-bold text-lg"
                                >Manage<span class="gradient-text"
                                    >Hub</span
                                ></span
                            >
                        </div>
                        <p class="mb-4">
                            {{ $page->footer_about ?: 'The smartest way to share, track, and grow your professional network — one vCard at a time.' }}
                        </p>
                        <div class="flex space-x-4">
                            <a href="{{ $settings['social_twitter'] ?? '#' }}" class="text-gray-400 hover:text-white"
                                ><i class="fab fa-twitter"></i
                            ></a>
                            <a href="{{ $settings['social_linkedin'] ?? '#' }}" class="text-gray-400 hover:text-white"
                                ><i class="fab fa-linkedin-in"></i
                            ></a>
                            <a href="{{ $settings['social_instagram'] ?? '#' }}" class="text-gray-400 hover:text-white"
                                ><i class="fab fa-instagram"></i
                            ></a>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-white font-semibold mb-4">Product</h3>
                        <ul class="space-y-2">
                            @foreach ($productLinks as $link)
                                <li>
                                    <a href="{{ $link['url'] ?? '#' }}" class="hover:text-white transition"
                                        >{{ $link['label'] ?? '' }}</a
                                    >
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-white font-semibold mb-4">Resources</h3>
                        <ul class="space-y-2">
                            @foreach ($resourceLinks as $link)
                                <li>
                                    <a href="{{ $link['url'] ?? '#' }}" class="hover:text-white transition"
                                        >{{ $link['label'] ?? '' }}</a
                                    >
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div></div>
                </div>
            </div>
        </footer>
    </body>
</html>
