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
        <section class="py-24 relative overflow-hidden" style="background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $secondaryColor }} 100%);">
            <!-- Animated Background Elements -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl animate-blob"></div>
                <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl animate-blob animation-delay-2000"></div>
                <div class="absolute bottom-1/4 left-1/2 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl animate-blob animation-delay-4000"></div>
            </div>

            <div class="container mx-auto px-4 max-w-7xl relative z-10">
                <!-- Header -->
                <div class="text-center mb-16">
                    <div class="inline-block mb-4">
                        <span class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white text-sm font-semibold rounded-full border border-white/30">
                            ✨ Premium Templates
                        </span>
                    </div>
                    <h2 class="vcard-preview-title text-4xl md:text-6xl font-bold mb-6 text-white">
                        {{ $vcardPreviewsSection['title'] ?? 'Stunning vCard Templates' }}
                    </h2>
                    <p class="text-white/90 text-lg max-w-3xl mx-auto leading-relaxed">
                        {{ $vcardPreviewsSection['subtitle'] ?? 'Choose from our collection of professionally designed templates. Each one is crafted with care and fully customizable to match your brand.' }}
                    </p>
                </div>

                <!-- Templates Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach ($templates as $index => $template)
                        <div class="group relative" style="animation: fadeInUp 0.6s ease-out {{ $index * 0.1 }}s backwards;">
                            <!-- Card Container -->
                            <div class="relative bg-white/95 backdrop-blur-sm rounded-2xl overflow-hidden shadow-2xl transform transition-all duration-500 hover:scale-105 hover:shadow-3xl hover:-translate-y-2">
                                <!-- Category Badge -->
                                <div class="absolute top-4 right-4 z-10">
                                    <span class="px-3 py-1.5 text-white text-xs font-bold rounded-full shadow-lg" style="background: linear-gradient(to right, {{ $primaryColor }}, {{ $secondaryColor }});">
                                        {{ $template['category'] }}
                                    </span>
                                </div>

                                <!-- Phone Mockup -->
                                <div class="relative p-6 pb-4">
                                    <div class="relative mx-auto" style="max-width: 280px;">
                                        <!-- Phone Frame -->
                                        <div class="relative bg-gray-900 rounded-[2.5rem] p-3 shadow-2xl">
                                            <!-- Notch -->
                                            <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-1/3 h-6 bg-gray-900 rounded-b-3xl z-10"></div>
                                            
                                            <!-- Screen -->
                                            <div class="relative bg-white rounded-[2rem] overflow-hidden" style="height: 400px;">
                                                <!-- Status Bar -->
                                                <div class="absolute top-0 inset-x-0 h-8 bg-gradient-to-b from-black/5 to-transparent z-10 flex items-center justify-between px-6 text-[10px] text-gray-600">
                                                    <span>9:41</span>
                                                    <div class="flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg>
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M17.778 8.222c-4.296-4.296-11.26-4.296-15.556 0A1 1 0 01.808 6.808c5.076-5.077 13.308-5.077 18.384 0a1 1 0 01-1.414 1.414zM14.95 11.05a7 7 0 00-9.9 0 1 1 0 01-1.414-1.414 9 9 0 0112.728 0 1 1 0 01-1.414 1.414zM12.12 13.88a3 3 0 00-4.242 0 1 1 0 01-1.415-1.415 5 5 0 017.072 0 1 1 0 01-1.415 1.415zM9 16a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                                                        <svg class="w-4 h-3" fill="currentColor" viewBox="0 0 24 20"><rect x="1" y="3" width="18" height="14" rx="2" fill="currentColor"></rect><path d="M20 8v4a2 2 0 002-2V8z"></path></svg>
                                                    </div>
                                                </div>
                                                
                                                <!-- Template Preview -->
                                                <iframe src="{{ $template['preview_url'] }}" class="w-full h-full border-none" loading="lazy" style="pointer-events: none;"></iframe>
                                                
                                                <!-- Overlay on hover -->
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-8">
                                                    <a href="{{ $template['preview_url'] }}" target="_blank" class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 px-6 py-3 bg-white text-gray-900 font-semibold rounded-full hover:bg-gray-100 inline-flex items-center gap-2 shadow-xl">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        <span>Preview</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Template Info -->
                                <div class="px-6 pb-6">
                                    <h3 class="template-card-title text-xl font-bold text-gray-900 mb-2 transition-colors">
                                        {{ $template['title'] }}
                                    </h3>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-500 flex items-center gap-1">
                                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Live Preview
                                        </span>
                                        <a href="{{ $template['preview_url'] }}" target="_blank" class="template-view-link text-sm font-semibold transition-colors inline-flex items-center gap-1" style="color: {{ $primaryColor }};">
                                            View Full
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- CSS Animations -->
            <style>
                @keyframes fadeInUp {
                    from {
                        opacity: 0;
                        transform: translateY(30px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                
                @keyframes blob {
                    0%, 100% {
                        transform: translate(0, 0) scale(1);
                    }
                    33% {
                        transform: translate(30px, -50px) scale(1.1);
                    }
                    66% {
                        transform: translate(-20px, 20px) scale(0.9);
                    }
                }
                
                .animate-blob {
                    animation: blob 7s infinite;
                }
                
                .animation-delay-2000 {
                    animation-delay: 2s;
                }
                
                .animation-delay-4000 {
                    animation-delay: 4s;
                }

                /* Dynamic hover colors for template cards */
                .template-card-title:hover {
                    color: {{ $primaryColor }} !important;
                }
                
                .template-view-link:hover {
                    color: {{ $secondaryColor }} !important;
                }
            </style>
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
