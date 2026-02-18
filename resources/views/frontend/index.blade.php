<!doctype html>
<html lang="en">
    @php
        // Get branding colors early for CSS
        $primaryColor = \App\Helpers\BrandingHelper::getPrimaryColor();
        $secondaryColor = \App\Helpers\BrandingHelper::getSecondaryColor();
    @endphp
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $page->meta_title ?: 'ManageHub — Smart vCards, Seamlessly Shared' }}</title>
        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ \App\Helpers\BrandingHelper::getFaviconUrl() }}">
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
                            primary: "{{ $primaryColor }}",
                            "primary-dark": "{{ $secondaryColor }}",
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
                background: linear-gradient(90deg, {{ $primaryColor }}, {{ $secondaryColor }});
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
            }
        </style>
    </head>
    <body class="bg-gray-50 text-gray-800">
        @php
            // Extract CMS data from page->data
            $data = $page->data ?? [];
            
            $headerCta = $data['header_cta'] ?? [];
            $heroButtons = $data['hero_buttons'] ?? [];
            $heroPrimary = $heroButtons[0] ?? [];
            $heroSecondary = $heroButtons[1] ?? [];
            $heroImagePath = $data['hero_image_path'] ?? '';
            $categories = $data['categories'] ?? [];
            $categoryItems = $categories['items'] ?? [];
            $vcard = $data['vcard_preview'] ?? [];
            $how = $data['how_it_works'] ?? [];
            $howSteps = $how['steps'] ?? [];
            $cta = $data['cta_section'] ?? [];
            $footerLinks = $data['footer_links'] ?? [];
            $productLinks = $footerLinks['product'] ?? [];
            $resourceLinks = $footerLinks['resources'] ?? [];
            $socialLinks = $data['social_links'] ?? [];
        @endphp
        <!-- Navigation -->
        <header
            class="sticky top-0 z-50 bg-white/80 backdrop-blur-sm border-b border-border-light"
        >
            <div
                class="container mx-auto px-4 py-4 flex justify-between items-center"
            >
                <div class="flex items-center">
                    <div class="h-10 rounded-lg flex items-center justify-center">
                        <img src="{{ \App\Helpers\BrandingHelper::getLogoUrl() }}" alt="Logo" class="h-full w-full object-contain rounded-lg" />
                    </div>
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
            class="py-16 md:py-24 bg-gradient-to-br from-blue-50 to-emerald-50 bg-cover bg-center bg-no-repeat relative" 
            @if($heroImagePath) style="background-image: url('{{ $heroImagePath }}');" @endif
        >
            @if($heroImagePath)
            <div class="absolute inset-0 bg-black/30"></div>
            @endif
            <div class="container mx-auto px-4 text-center max-w-3xl relative z-10">
                <h1
                    class="text-4xl md:text-6xl font-extrabold leading-tight mb-6 {{ $heroImagePath ? 'text-white' : '' }}"
                >
                    {{ $data['hero_title'] ?? 'vCards,' }}
                    <span class="gradient-text">{{ $data['hero_title_highlight'] ?? 'Reimagined' }}</span>.
                </h1>
                <p class="text-xl {{ $heroImagePath ? 'text-gray-100' : 'text-gray-600' }} mb-10 max-w-2xl mx-auto">
                    {{ $data['hero_subtitle'] ?? 'ManageHub helps businesses, creators, and professionals share rich, interactive digital business cards — instantly, beautifully, and with purpose.' }}
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    @if(isset($heroPrimary['url']) && isset($heroPrimary['label']))
                    <a
                        href="{{ $heroPrimary['url'] }}"
                        style="background-color: {{ $primaryColor }};"
                        class="px-8 py-3 text-white font-semibold rounded-lg shadow-lg hover:opacity-90 transition transform hover:-translate-y-0.5"
                    >
                        {{ $heroPrimary['label'] }}
                    </a>
                    @endif
                    @if(isset($heroSecondary['url']) && isset($heroSecondary['label']))
                    <a
                        href="{{ $heroSecondary['url'] }}"
                        style="border-color: {{ $primaryColor }};color: {{ $primaryColor }};"
                        class="px-8 py-3 bg-white font-semibold rounded-lg hover:bg-gray-50 transition"
                    >
                        {{ $heroSecondary['label'] }}
                    </a>
                    @endif
                </div>
            </div>
        </section>

        <!-- Categories Section -->
        @if(isset($categoryItems) && count($categoryItems) > 0)
        <section id="categories" class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">
                        {{ $categories['title'] ?? '' }}
                        <span class="gradient-text">{{ $categories['highlight'] ?? '' }}</span>{{ $categories['suffix'] ?? '' }}
                    </h2>
                    <p class="text-gray-600">
                        {{ $categories['subtitle'] ?? '' }}
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
        @endif

        <!-- vCard Preview Mockup -->
        @if(isset($vcard) && !empty($vcard))
        <section class="py-16 bg-bg-light">
            <div class="container mx-auto px-4 max-w-5xl">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">
                        {{ $vcard['heading'] ?? '' }}
                        <span class="gradient-text">{{ $vcard['highlight'] ?? '' }}</span>{{ $vcard['suffix'] ?? '' }}
                    </h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        {{ $vcard['subheading'] ?? '' }}
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
        @endif

        <!-- How It Works -->
        @if(isset($howSteps) && count($howSteps) > 0)
        <section id="how-it-works" class="py-16 bg-white">
            <div class="container mx-auto px-4 max-w-4xl">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">
                        {{ $how['title'] ?? '' }}<span class="gradient-text">{{ $how['highlight'] ?? '' }}</span>{{ $how['suffix'] ?? '' }}
                    </h2>
                    <p class="text-gray-600">
                        {{ $how['subtitle'] ?? '' }}\n
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
        @endif

        <!-- CTA Section -->
        @if(isset($cta) && !empty($cta))
        <section
            class="py-16 bg-gradient-to-r from-primary to-emerald-500 text-white"
        >
            <div class="container mx-auto px-4 text-center max-w-3xl">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">
                    {{ $cta['title'] ?? '' }}
                </h2>
                <p class="text-blue-100 text-xl mb-10 max-w-2xl mx-auto">
                    {{ $cta['subtitle'] ?? '' }}
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    @if(isset($cta['primary_url']) && isset($cta['primary_label']))
                    <a
                        href="{{ $cta['primary_url'] }}"
                        class="px-8 py-3 bg-white text-primary font-bold rounded-lg shadow-lg hover:bg-gray-100 transition"
                    >
                        {{ $cta['primary_label'] }}
                    </a>
                    @endif
                    @if(isset($cta['secondary_url']) && isset($cta['secondary_label']))
                    <a
                        href="{{ $cta['secondary_url'] }}"
                        class="px-8 py-3 bg-transparent border-2 border-white text-white font-bold rounded-lg hover:bg-black/10 transition"
                    >
                        {{ $cta['secondary_label'] }}
                    </a>
                    @endif
                </div>
            </div>
        </section>
        @endif

        <!-- Footer -->
        <footer id="contact" class="py-12 bg-gray-900 text-gray-400">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <div class="flex items-center mb-4">
                            <img src="{{ \App\Helpers\BrandingHelper::getFooterLogoUrl() }}" alt="Footer Logo" class="h-8 object-contain rounded-lg" />
                        </div>
                        <p class="mb-4">
                            {{ $data['footer_about'] ?? '' }}
                        </p>
                        <div class="flex space-x-4">
                            @forelse ($socialLinks as $social)
                                @php
                                    $platformLower = strtolower($social['platform'] ?? '');
                                    $icons = [
                                        'facebook' => 'fab fa-facebook-f',
                                        'twitter' => 'fab fa-twitter',
                                        'instagram' => 'fab fa-instagram',
                                        'linkedin' => 'fab fa-linkedin-in',
                                        'youtube' => 'fab fa-youtube',
                                        'tiktok' => 'fab fa-tiktok',
                                        'pinterest' => 'fab fa-pinterest-p',
                                        'snapchat' => 'fab fa-snapchat-ghost',
                                        'whatsapp' => 'fab fa-whatsapp',
                                        'telegram' => 'fab fa-telegram',
                                        'discord' => 'fab fa-discord',
                                        'github' => 'fab fa-github',
                                        'gitlab' => 'fab fa-gitlab',
                                        'medium' => 'fab fa-medium',
                                        'behance' => 'fab fa-behance',
                                        'dribbble' => 'fab fa-dribbble',
                                        'figma' => 'fab fa-figma',
                                        'twitch' => 'fab fa-twitch',
                                        'wechat' => 'fab fa-weixin',
                                    ];
                                    $icon = $icons[$platformLower] ?? 'fab fa-globe';
                                @endphp
                                <a href="{{ $social['url'] ?? '#' }}" class="text-gray-400 hover:text-white" title="{{ $social['platform'] }}"
                                    ><i class="{{ $icon }}"></i
                                ></a>
                            @empty
                                {{-- No social links configured --}}
                            @endforelse
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
