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
        @php
            $seoData = $page->data['seo'] ?? [];
            $metaTitle = $page->meta_title ?: ($settings['seo_title'] ?? 'ManageHub — Smart vCards, Seamlessly Shared');
            $metaDescription = $page->meta_description ?: ($settings['seo_description'] ?? 'ManageHub — Smart vCards, Seamlessly Shared');
            $metaKeywords = $seoData['meta_keywords'] ?? ($settings['seo_keywords'] ?? '');
            $canonicalUrl = $seoData['canonical_url'] ?? '';
        @endphp
        <title>{{ $metaTitle }}</title>
        <meta name="description" content="{{ $metaDescription }}" />
        @if (!empty($metaKeywords))
            <meta name="keywords" content="{{ $metaKeywords }}" />
        @endif
        @if (!empty($canonicalUrl))
            <link rel="canonical" href="{{ $canonicalUrl }}" />
        @endif
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
            @import url("https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&display=swap");
            body {
                font-family:
                    "Inter",
                    -apple-system,
                    BlinkMacSystemFont,
                    "Segoe UI",
                    sans-serif;
            }
            .vcard-preview-title {
                font-family: "Space Grotesk", "Inter", sans-serif;
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
            $vcardPreviews = $data['vcard_previews'] ?? [];
            $vcardPreviewsSection = $data['vcard_previews_section'] ?? [];
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

        <!-- vCard Previews -->
        @if(isset($templates) && count($templates) > 0)
        <section class="py-20 bg-gray-50">
            <div class="container mx-auto px-4 max-w-7xl">
                <!-- Header -->
                <div class="text-center mb-16">
                    <h2 class="vcard-preview-title text-4xl md:text-5xl font-bold mb-4 text-gray-900">
                        {{ $vcardPreviewsSection['title'] ?? 'Stunning vCard Templates' }}
                    </h2>
                    <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                        {{ $vcardPreviewsSection['subtitle'] ?? 'Choose from our collection of professionally designed templates. Each one is fully customizable to match your brand.' }}
                    </p>
                </div>

                <!-- Templates Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($templates as $index => $template)
                        <div class="group">
                            <!-- Card Container -->
                            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 border border-gray-200">
                                <!-- Category Badge -->
                                <div class="px-4 py-3 border-b border-gray-200 bg-gradient-to-r" style="background: linear-gradient(to right, {{ $primaryColor }}15, {{ $secondaryColor }}15);">
                                    <span class="text-xs font-semibold text-gray-700 uppercase tracking-wide">
                                        {{ $template['category'] }}
                                    </span>
                                </div>

                                <!-- Template Preview -->
                                <div class="relative bg-gray-100 overflow-hidden" style="height: 450px;">
                                    <iframe src="{{ $template['preview_url'] }}" class="w-full h-full border-none" loading="lazy" style="pointer-events: none;"></iframe>
                                    
                                    <!-- Overlay on hover -->
                                    <div class="absolute inset-0 bg-black/0 hover:bg-black/40 transition-colors duration-300 flex items-center justify-center">
                                        <a href="{{ $template['preview_url'] }}" target="_blank" class="px-4 py-2 bg-white text-gray-900 font-semibold rounded transition-opacity duration-300 opacity-0 hover:opacity-100 group-hover:opacity-100 text-sm shadow-lg">
                                            Full Preview
                                        </a>
                                    </div>
                                </div>

                                <!-- Template Info -->
                                <div class="px-4 py-4">
                                    <h3 class="template-card-title text-lg font-semibold text-gray-900 mb-3">
                                        {{ $template['title'] }}
                                    </h3>
                                    <a href="{{ $template['preview_url'] }}" target="_blank" class="template-view-link text-sm font-medium inline-flex items-center gap-2 transition-transform hover:translate-x-1" style="color: {{ $primaryColor }};">
                                        View →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
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

                    <div>
                        <h3 class="text-white font-semibold mb-4">Contact</h3>
                        <ul class="space-y-3 text-sm">
                            @if (!empty($settings['contact_email']))
                                <li class="flex items-start gap-3">
                                    <i class="fas fa-envelope mt-1 text-gray-500"></i>
                                    <a href="mailto:{{ $settings['contact_email'] }}" class="hover:text-white transition">
                                        {{ $settings['contact_email'] }}
                                    </a>
                                </li>
                            @endif
                            @if (!empty($settings['contact_phone']))
                                <li class="flex items-start gap-3">
                                    <i class="fas fa-phone mt-1 text-gray-500"></i>
                                    <a href="tel:{{ $settings['contact_phone'] }}" class="hover:text-white transition">
                                        {{ $settings['contact_phone'] }}
                                    </a>
                                </li>
                            @endif
                            @if (!empty($settings['contact_address']))
                                <li class="flex items-start gap-3">
                                    <i class="fas fa-location-dot mt-1 text-gray-500"></i>
                                    <span>{{ $settings['contact_address'] }}</span>
                                </li>
                            @endif
                            @if (!empty($settings['site_url']))
                                <li class="flex items-start gap-3">
                                    <i class="fas fa-globe mt-1 text-gray-500"></i>
                                    <a href="{{ $settings['site_url'] }}" class="hover:text-white transition" target="_blank" rel="noopener">
                                        {{ $settings['site_url'] }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
