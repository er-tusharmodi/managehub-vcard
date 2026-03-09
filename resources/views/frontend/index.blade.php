<!doctype html>
<html lang="en">
@php
    $primaryColor   = \App\Helpers\BrandingHelper::getPrimaryColor();
    $secondaryColor = \App\Helpers\BrandingHelper::getSecondaryColor();
    $data           = $page->data ?? [];
    $headerCta      = $data['header_cta'] ?? [];
    $heroButtons    = $data['hero_buttons'] ?? [];
    $heroPrimary    = $heroButtons[0] ?? [];
    $heroSecondary  = $heroButtons[1] ?? [];
    $categories     = $data['categories'] ?? [];
    $categoryItems  = $categories['items'] ?? [];
    $vcardPreviewsSection = $data['vcard_previews_section'] ?? [];
    $how            = $data['how_it_works'] ?? [];
    $howSteps       = $how['steps'] ?? [];
    $cta            = $data['cta_section'] ?? [];
    $features       = $data['features'] ?? [];
    $featureItems   = $features['items'] ?? null;
    $footerLinks    = $data['footer_links'] ?? [];
    $productLinks   = $footerLinks['product'] ?? [];
    $resourceLinks  = $footerLinks['resources'] ?? [];
    $socialLinks    = $data['social_links'] ?? [];
    $heroTemplates  = collect($templates ?? [])->take(4)->values()->all();
    $navLinks       = $data['nav_links'] ?? [
        ['label' => 'Features',     'url' => '#features'],
        ['label' => 'Categories',   'url' => '#categories'],
        ['label' => 'How It Works', 'url' => '#how-it-works'],
        ['label' => 'Contact',      'url' => '#contact'],
    ];
    $stats          = $data['stats']['items'] ?? [
        ['number' => '9',   'suffix' => '+',      'label' => 'Templates'],
        ['number' => '100', 'suffix' => '%',       'label' => 'Customizable'],
        ['number' => '1',   'suffix' => '-Click',  'label' => 'Contact Save'],
        ['number' => '24',  'suffix' => '/7',      'label' => 'Online Presence'],
    ];
    $metaTitle      = $page->meta_title ?: ($settings['seo_title'] ?? 'ManageHub — Smart vCards, Seamlessly Shared');
    $metaDescription = $page->meta_description ?: ($settings['seo_description'] ?? 'Smart vCards for Modern Businesses');
@endphp
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}" />
    <link rel="shortcut icon" href="{{ \App\Helpers\BrandingHelper::getFaviconUrl() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        :root {
            --primary:   {{ $primaryColor }};
            --secondary: {{ $secondaryColor }};
            --bg:        #070717;
            --bg2:       #0d0d22;
            --bg3:       #111128;
            --bg-card:   rgba(255,255,255,0.035);
            --border:    rgba(255,255,255,0.07);
            --border-md: rgba(255,255,255,0.12);
            --text:      rgba(255,255,255,0.92);
            --text-muted:rgba(255,255,255,0.52);
            --text-dim:  rgba(255,255,255,0.32);
            --radius:    1rem;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background: var(--bg); color: var(--text); line-height: 1.6; overflow-x: hidden; }
        img { max-width: 100%; display: block; }
        a { text-decoration: none; color: inherit; }
        .gradient-text { background: linear-gradient(120deg, var(--primary), var(--secondary)); -webkit-background-clip: text; background-clip: text; color: transparent; }
        .container { width: 100%; max-width: 1280px; margin: 0 auto; padding: 0 1.5rem; }
        .section-title { font-family: 'Space Grotesk', sans-serif; font-size: clamp(2rem, 4vw, 3rem); font-weight: 800; line-height: 1.15; margin-bottom: 1rem; }
        .section-subtitle { color: var(--text-muted); font-size: 1.05rem; max-width: 560px; margin: 0 auto; }
        .section-head { text-align: center; margin-bottom: 3.5rem; }
        .section-badge { display: inline-flex; align-items: center; gap: .5rem; background: rgba(255,255,255,.05); border: 1px solid var(--border-md); border-radius: 100px; padding: .35rem 1rem; font-size: .75rem; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 1.25rem; }
        .section-badge .dot { width: 7px; height: 7px; border-radius: 50%; background: var(--primary); animation: pulse-dot 2s ease-in-out infinite; }
        .glass { background: var(--bg-card); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid var(--border); border-radius: var(--radius); transition: border-color .3s, transform .3s, box-shadow .3s; }
        .glass:hover { border-color: var(--border-md); transform: translateY(-4px); box-shadow: 0 20px 60px rgba(0,0,0,.5); }
        .btn-primary { display: inline-flex; align-items: center; gap: .5rem; padding: .75rem 1.75rem; background: linear-gradient(120deg, var(--primary), var(--secondary)); color: #fff; font-weight: 600; border-radius: .75rem; border: none; cursor: pointer; font-size: .95rem; transition: opacity .2s, transform .2s; }
        .btn-primary:hover { opacity: .88; transform: translateY(-2px); }
        .btn-outline { display: inline-flex; align-items: center; gap: .5rem; padding: .75rem 1.75rem; background: transparent; color: var(--text); font-weight: 600; border-radius: .75rem; border: 1px solid var(--border-md); cursor: pointer; font-size: .95rem; transition: background .2s, border-color .2s, transform .2s; }
        .btn-outline:hover { background: rgba(255,255,255,.06); border-color: rgba(255,255,255,.24); transform: translateY(-2px); }
        .reveal { opacity: 0; transform: translateY(32px); transition: opacity .65s ease, transform .65s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal.delay-1 { transition-delay: .1s; }
        .reveal.delay-2 { transition-delay: .2s; }
        .reveal.delay-3 { transition-delay: .3s; }
        .reveal.delay-4 { transition-delay: .4s; }
        .reveal.delay-5 { transition-delay: .5s; }
        @@keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(1.4)} }
        @@keyframes float-orb { 0%,100%{transform:translate(0,0) scale(1)} 33%{transform:translate(30px,-40px) scale(1.05)} 66%{transform:translate(-20px,25px) scale(.96)} }
        @@keyframes float-card { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-12px)} }
        @@keyframes shimmer { 0%{transform:translateX(-100%)} 100%{transform:translateX(200%)} }
        .site-nav { position:sticky;top:0;z-index:999;background:rgba(7,7,23,.82);backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);border-bottom:1px solid var(--border); }
        .nav-inner { display:flex;align-items:center;justify-content:space-between;padding:1rem 1.5rem;max-width:1280px;margin:0 auto; }
        .nav-logo img { height:38px;width:auto;object-fit:contain;border-radius:.5rem; }
        .nav-links { display:flex;align-items:center;gap:2rem; }
        .nav-links a { font-size:.9rem;font-weight:500;color:var(--text-muted);transition:color .2s; }
        .nav-links a:hover { color:var(--text); }
        .hamburger { display:none;flex-direction:column;gap:5px;cursor:pointer;padding:.4rem;background:none;border:none; }
        .hamburger span { display:block;width:22px;height:2px;background:var(--text);border-radius:2px;transition:.3s; }
        .mobile-menu { display:none;flex-direction:column;background:var(--bg2);border-top:1px solid var(--border); }
        .mobile-menu.open { display:flex; }
        .mobile-menu a { padding:.9rem 1.5rem;font-weight:500;color:var(--text-muted);border-bottom:1px solid var(--border);transition:color .2s,background .2s; }
        .mobile-menu a:hover { color:var(--text);background:rgba(255,255,255,.04); }
        .hero { position:relative;min-height:92vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:5rem 1.5rem 0;overflow:hidden;background:var(--bg); }
        .hero::before { content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.03) 1px,transparent 1px);background-size:48px 48px;mask-image:radial-gradient(ellipse 80% 60% at 50% 0%,black 40%,transparent 100%);-webkit-mask-image:radial-gradient(ellipse 80% 60% at 50% 0%,black 40%,transparent 100%);pointer-events:none; }
        .hero-orb { position:absolute;border-radius:50%;filter:blur(80px);opacity:.18;animation:float-orb 10s ease-in-out infinite;pointer-events:none; }
        .hero-orb-1 { width:600px;height:600px;top:-100px;left:-120px;background:var(--primary);animation-duration:10s; }
        .hero-orb-2 { width:500px;height:500px;top:50px;right:-140px;background:var(--secondary);animation-duration:8s;animation-delay:-3s; }
        .hero-orb-3 { width:340px;height:340px;bottom:40px;left:38%;background:var(--primary);opacity:.10;animation-duration:12s;animation-delay:-6s; }
        /* === HERO BG ANIMATIONS === */
        .hero-particles { position:absolute;inset:0;background-image:radial-gradient(circle,rgba(255,255,255,.22) 1px,transparent 1px);background-size:52px 52px;mask-image:radial-gradient(ellipse 80% 70% at 50% 40%,black 20%,transparent 100%);-webkit-mask-image:radial-gradient(ellipse 80% 70% at 50% 40%,black 20%,transparent 100%);animation:hero-particles-drift 22s linear infinite;pointer-events:none;z-index:0;opacity:.55; }
        @@keyframes hero-particles-drift { 0%{background-position:0 0} 100%{background-position:52px 52px} }
        .hero-center-glow { position:absolute;width:900px;height:900px;border-radius:50%;background:radial-gradient(circle,var(--primary) 0%,transparent 65%);opacity:.09;left:50%;top:36%;transform:translate(-50%,-50%);pointer-events:none;z-index:0;animation:hero-center-pulse 5s ease-in-out infinite; }
        @@keyframes hero-center-pulse { 0%,100%{opacity:.07;transform:translate(-50%,-50%) scale(1)} 50%{opacity:.16;transform:translate(-50%,-50%) scale(1.08)} }
        .hero-beam { position:absolute;width:35%;height:200%;top:-50%;left:-35%;background:linear-gradient(90deg,transparent 0%,rgba(255,255,255,.022) 50%,transparent 100%);transform:rotate(28deg);pointer-events:none;z-index:0;animation:hero-beam-sweep 14s ease-in-out infinite; }
        @@keyframes hero-beam-sweep { 0%{left:-35%;opacity:0} 10%{opacity:1} 50%{left:120%;opacity:.7} 60%{opacity:0} 100%{left:120%;opacity:0} }
        .hero-scanline { position:absolute;left:0;right:0;height:1px;top:-5%;background:linear-gradient(90deg,transparent 0%,rgba(255,255,255,.05) 20%,var(--primary) 50%,rgba(255,255,255,.05) 80%,transparent 100%);pointer-events:none;z-index:1;animation:hero-scanline-move 8s linear infinite;opacity:.6; }
        @@keyframes hero-scanline-move { 0%{top:-2%} 100%{top:105%} }
        /* === END HERO BG ANIMATIONS === */
        .hero-content { position:relative;z-index:2;text-align:center;max-width:760px;width:100%; }
        .hero-tag { display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,255,255,.06);border:1px solid var(--border-md);border-radius:100px;padding:.4rem 1.1rem;font-size:.78rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);margin-bottom:1.5rem; }
        .hero-tag .spark { width:6px;height:6px;border-radius:50%;background:var(--primary); }
        .hero h1 { font-family:'Space Grotesk',sans-serif;font-size:clamp(2.6rem,6vw,4.5rem);font-weight:800;line-height:1.1;margin-bottom:1.4rem;color:var(--text); }
        .hero p { font-size:clamp(1rem,2vw,1.2rem);color:var(--text-muted);max-width:600px;margin:0 auto 2.5rem; }
        .hero-btns { display:flex;gap:1rem;justify-content:center;flex-wrap:wrap; }
        .hero-mockups { position:relative;z-index:2;display:flex;gap:1.25rem;justify-content:center;align-items:flex-end;margin-top:4rem;width:100%;max-width:1100px;padding:0 1rem; }
        .mockup-frame { flex:1;min-width:0;max-width:300px;background:var(--bg-card);border:1px solid var(--border-md);border-bottom:none;border-radius:1rem 1rem 0 0;overflow:hidden;position:relative;transition:transform .3s,box-shadow .3s; }
        .mockup-frame:hover { box-shadow:0 -16px 60px rgba(0,0,0,.5);z-index:3; }
        .mockup-frame:nth-child(1){animation:float-card 7s ease-in-out infinite}
        .mockup-frame:nth-child(2){animation:float-card 9s ease-in-out infinite;animation-delay:-2s}
        .mockup-frame:nth-child(3){animation:float-card 8s ease-in-out infinite;animation-delay:-4s}
        .mockup-frame:nth-child(4){animation:float-card 10s ease-in-out infinite;animation-delay:-1s}
        .mockup-bar { display:flex;align-items:center;gap:.4rem;padding:.5rem .75rem;background:rgba(255,255,255,.04);border-bottom:1px solid var(--border); }
        .mockup-dot { width:7px;height:7px;border-radius:50%; }
        .mockup-dot:nth-child(1){background:#ff5f57} .mockup-dot:nth-child(2){background:#febc2e} .mockup-dot:nth-child(3){background:#28c840}
        .mockup-label { font-size:.65rem;color:var(--text-dim);font-weight:500;letter-spacing:.04em;flex:1;text-align:center;white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
        .mockup-iframe-wrap { width:100%;height:420px;overflow:hidden;pointer-events:none; }
        .mockup-iframe-wrap iframe { width:100%;height:100%;border:none;pointer-events:none;zoom:0.5; }
        .stats-bar { background:var(--bg2);border-top:1px solid var(--border);border-bottom:1px solid var(--border);padding:2.5rem 1.5rem; }
        .stats-inner { display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;max-width:1000px;margin:0 auto; }
        .stat-item { text-align:center;padding:1rem; }
        .stat-item+.stat-item { border-left:1px solid var(--border); }
        .stat-num { font-family:'Space Grotesk',sans-serif;font-size:2.4rem;font-weight:800;line-height:1;background:linear-gradient(120deg,var(--primary),var(--secondary));-webkit-background-clip:text;background-clip:text;color:transparent;margin-bottom:.35rem; }
        .stat-label { font-size:.82rem;color:var(--text-muted);font-weight:500;letter-spacing:.04em; }
        .categories-section { padding:6rem 0;background:var(--bg); }
        .categories-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1.25rem; }
        .category-card { padding:1.75rem 1.5rem;cursor:default; }
        .cat-icon-wrap { width:52px;height:52px;border-radius:.875rem;display:flex;align-items:center;justify-content:center;margin-bottom:1.1rem;flex-shrink:0;font-size:1.3rem; }
        .category-card h3 { font-family:'Space Grotesk',sans-serif;font-size:1rem;font-weight:700;margin-bottom:.4rem; }
        .category-card p { font-size:.85rem;color:var(--text-muted);line-height:1.5; }
        .templates-section { padding:6rem 0;background:var(--bg2); }
        .templates-grid { display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem; }
        .template-card { overflow:hidden;border-radius:var(--radius);cursor:pointer; }
        .template-card-badge { padding:.6rem 1rem;background:rgba(255,255,255,.04);border-bottom:1px solid var(--border);font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted); }
        .template-preview { position:relative;height:460px;overflow:hidden;background:var(--bg3); }
        .template-preview iframe { width:100%;height:100%;border:none;pointer-events:none;zoom:0.6; }
        .template-overlay { position:absolute;inset:0;background:rgba(0,0,0,0);display:flex;align-items:center;justify-content:center;transition:background .3s; }
        .template-card:hover .template-overlay { background:rgba(0,0,0,.5); }
        .template-overlay-btn { opacity:0;transform:translateY(8px);transition:opacity .3s,transform .3s;padding:.55rem 1.25rem;background:#fff;color:#0a0a1a;font-weight:700;border-radius:.5rem;font-size:.85rem; }
        .template-card:hover .template-overlay-btn { opacity:1;transform:translateY(0); }
        .template-footer { padding:1rem 1.1rem 1.1rem; }
        .template-footer h3 { font-family:'Space Grotesk',sans-serif;font-size:.95rem;font-weight:700;margin-bottom:.5rem; }
        .template-foot-link { font-size:.82rem;font-weight:600;background:linear-gradient(120deg,var(--primary),var(--secondary));-webkit-background-clip:text;background-clip:text;color:transparent;display:inline-flex;align-items:center;gap:.3rem;transition:gap .2s; }
        .template-card:hover .template-foot-link { gap:.6rem; }
        .features-section { padding:6rem 0;background:var(--bg); }
        .features-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(290px,1fr));gap:1.5rem; }
        .feature-card { padding:2rem 1.75rem;position:relative;overflow:hidden; }
        .feature-card::before { content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--primary),var(--secondary));opacity:0;transition:opacity .3s; }
        .feature-card:hover::before { opacity:1; }
        .feature-card::after { content:'';position:absolute;top:0;left:0;width:50%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.03),transparent);transform:translateX(-100%); }
        .feature-card:hover::after { animation:shimmer .7s ease forwards; }
        .feature-icon { width:50px;height:50px;border-radius:.75rem;background:rgba(255,255,255,.06);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:1.2rem;margin-bottom:1.25rem;transition:background .3s,border-color .3s; }
        .feature-card:hover .feature-icon { background:rgba(255,255,255,.1);border-color:var(--border-md); }
        .feature-card h3 { font-family:'Space Grotesk',sans-serif;font-size:1.05rem;font-weight:700;margin-bottom:.5rem; }
        .feature-card p { font-size:.88rem;color:var(--text-muted);line-height:1.6; }
        .how-section { padding:6rem 0;background:var(--bg2); }
        .how-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1.75rem;max-width:960px;margin:0 auto; }
        .step-card { padding:2rem 1.75rem;text-align:center; }
        .step-badge { width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-family:'Space Grotesk',sans-serif;font-size:1.4rem;font-weight:800;color:#fff;position:relative; }
        .step-badge::after { content:'';position:absolute;inset:-5px;border-radius:50%;border:2px solid rgba(255,255,255,.08); }
        .step-card h3 { font-family:'Space Grotesk',sans-serif;font-size:1.05rem;font-weight:700;margin-bottom:.5rem; }
        .step-card p { font-size:.88rem;color:var(--text-muted); }
        .cta-section { padding:7rem 1.5rem;background:var(--bg);position:relative;overflow:hidden;text-align:center; }
        .cta-glow { position:absolute;width:700px;height:500px;border-radius:50%;background:var(--primary);opacity:.08;filter:blur(120px);left:50%;top:50%;transform:translate(-50%,-50%);pointer-events:none; }
        .cta-inner { position:relative;z-index:2;max-width:640px;margin:0 auto; }
        .cta-inner h2 { font-family:'Space Grotesk',sans-serif;font-size:clamp(2rem,4vw,3rem);font-weight:800;margin-bottom:1rem; }
        .cta-inner p { font-size:1.1rem;color:var(--text-muted);margin-bottom:2.5rem; }
        .cta-btns { display:flex;gap:1rem;justify-content:center;flex-wrap:wrap; }
        .site-footer { background:#050512;border-top:1px solid var(--border);padding:4rem 1.5rem 2rem; }
        .footer-grid { display:grid;grid-template-columns:2fr 1fr 1fr 1.4fr;gap:3rem;max-width:1280px;margin:0 auto 3rem; }
        .footer-logo { height:36px;width:auto;object-fit:contain;border-radius:.375rem;margin-bottom:1rem; }
        .footer-about { font-size:.88rem;color:var(--text-muted);line-height:1.7;margin-bottom:1.5rem; }
        .footer-socials { display:flex;gap:.625rem;flex-wrap:wrap; }
        .footer-social-btn { width:36px;height:36px;border-radius:.5rem;background:rgba(255,255,255,.06);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:.9rem;color:var(--text-muted);transition:background .2s,color .2s,border-color .2s; }
        .footer-social-btn:hover { background:var(--primary);border-color:var(--primary);color:#fff; }
        .footer-col h4 { font-family:'Space Grotesk',sans-serif;font-size:.9rem;font-weight:700;color:var(--text);margin-bottom:1.25rem;letter-spacing:.03em; }
        .footer-col ul { list-style:none;display:flex;flex-direction:column;gap:.625rem; }
        .footer-col ul li a { font-size:.88rem;color:var(--text-muted);transition:color .2s; }
        .footer-col ul li a:hover { color:var(--text); }
        .footer-contact-item { display:flex;align-items:flex-start;gap:.75rem;font-size:.88rem;color:var(--text-muted);margin-bottom:.75rem; }
        .footer-contact-item i { margin-top:3px;opacity:.6;flex-shrink:0; }
        .footer-contact-item a { color:var(--text-muted);transition:color .2s; }
        .footer-contact-item a:hover { color:var(--text); }
        .footer-bottom { max-width:1280px;margin:0 auto;padding-top:2rem;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem; }
        .footer-bottom p { font-size:.82rem;color:var(--text-dim); }
        @media(max-width:960px){ .footer-grid{grid-template-columns:1fr 1fr} .templates-grid{grid-template-columns:repeat(2,1fr)} }
        @media(max-width:768px){ .nav-links,.nav-cta{display:none} .hamburger{display:flex} .hero-mockups{display:none} .stats-inner{grid-template-columns:1fr 1fr} .stat-item+.stat-item{border-left:none} .stat-item:nth-child(2n+1){border-right:1px solid var(--border)} }
        @media(max-width:560px){ .hero h1{font-size:2.2rem} .footer-grid{grid-template-columns:1fr} .section-title{font-size:1.85rem} .templates-grid{grid-template-columns:1fr} }
    </style>
@if(!empty($data['scripts']['head_script']))
{!! $data['scripts']['head_script'] !!}
@endif
</head>
<body>

<!-- NAV -->
<header class="site-nav">
    <div class="nav-inner">
        <a href="/" class="nav-logo">
            <img src="{{ \App\Helpers\BrandingHelper::getFooterLogoUrl() }}" alt="{{ $settings['site_name'] ?? 'ManageHub' }}">
        </a>
        <nav class="nav-links">
            @foreach($navLinks as $navLink)
            <a href="{{ $navLink['url'] }}">{{ $navLink['label'] }}</a>
            @endforeach
        </nav>
        <a href="{{ $headerCta['url'] ?? '#' }}" class="btn-primary nav-cta" style="padding:.6rem 1.25rem;font-size:.88rem;">
            {{ $headerCta['label'] ?? 'Get Started' }}
        </a>
        <button class="hamburger" id="hamburger" aria-label="Toggle menu">
            <span></span><span></span><span></span>
        </button>
    </div>
    <nav class="mobile-menu" id="mobileMenu">
        @foreach($navLinks as $navLink)
        <a href="{{ $navLink['url'] }}">{{ $navLink['label'] }}</a>
        @endforeach
        <a href="{{ $headerCta['url'] ?? '#' }}" style="color:var(--primary);font-weight:600;">{{ $headerCta['label'] ?? 'Get Started' }}</a>
    </nav>
</header>

<!-- HERO -->
<section class="hero">
    <div class="hero-particles" aria-hidden="true"></div>
    <div class="hero-center-glow" aria-hidden="true"></div>
    <div class="hero-beam" aria-hidden="true"></div>
    <div class="hero-scanline" aria-hidden="true"></div>
    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>
    <div class="hero-orb hero-orb-3"></div>
    <div class="hero-content reveal">
        <div class="hero-tag"><span class="spark"></span>{{ $data['hero_tag'] ?? 'Digital Business Cards &mdash; Reimagined' }}</div>
        <h1>
            {{ $data['hero_title'] ?? 'vCards,' }}
            <span class="gradient-text">{{ $data['hero_title_highlight'] ?? 'Reimagined' }}</span>.
        </h1>
        <p>{{ $data['hero_subtitle'] ?? 'ManageHub helps businesses, creators, and professionals share rich, interactive digital business cards instantly, beautifully, and with purpose.' }}</p>
        <div class="hero-btns">
            @if(!empty($heroPrimary['url']) && !empty($heroPrimary['label']))
                <a href="{{ $heroPrimary['url'] }}" class="btn-primary"><i class="fas fa-rocket"></i> {{ $heroPrimary['label'] }}</a>
            @endif
            @if(!empty($heroSecondary['url']) && !empty($heroSecondary['label']))
                <a href="{{ $heroSecondary['url'] }}" class="btn-outline">{{ $heroSecondary['label'] }} <i class="fas fa-arrow-right"></i></a>
            @endif
        </div>
    </div>
    @if(count($heroTemplates) > 0)
    <div class="hero-mockups reveal delay-2">
        @foreach($heroTemplates as $tpl)
        <div class="mockup-frame">
            <div class="mockup-bar">
                <span class="mockup-dot"></span><span class="mockup-dot"></span><span class="mockup-dot"></span>
                <span class="mockup-label">{{ $tpl['title'] }}</span>
            </div>
            <div class="mockup-iframe-wrap">
                <iframe src="{{ $tpl['preview_url'] }}" loading="lazy" title="{{ $tpl['title'] }}" scrolling="no"></iframe>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</section>

<!-- STATS BAR -->
<div class="stats-bar">
    <div class="stats-inner">
        @foreach($stats as $i => $stat)
        @php $delayClass = $i > 0 ? 'delay-' . min(5, $i) : ''; @endphp
        <div class="stat-item reveal {{ $delayClass }}"><div class="stat-num" data-target="{{ $stat['number'] }}" data-suffix="{{ $stat['suffix'] ?? '' }}">0{{ $stat['suffix'] ?? '' }}</div><div class="stat-label">{{ $stat['label'] }}</div></div>
        @endforeach
    </div>
</div>

<!-- CATEGORIES -->
@if(isset($categoryItems) && count($categoryItems) > 0)
<section id="categories" class="categories-section">
    <div class="container">
        <div class="section-head">
            <div class="section-badge reveal"><span class="dot"></span>Categories</div>
            <h2 class="section-title reveal delay-1">
                {{ $categories['title'] ?? '' }}<span class="gradient-text">{{ $categories['highlight'] ?? '' }}</span>{{ $categories['suffix'] ?? '' }}
            </h2>
            <p class="section-subtitle reveal delay-2">{{ $categories['subtitle'] ?? '' }}</p>
        </div>
        <div class="categories-grid">
            @foreach($categoryItems as $i => $item)
            @php
                $bgColor   = $item['icon_bg']   ?? null;
                $textColor = $item['icon_color'] ?? null;
                $bgStyle   = ($bgColor && str_starts_with($bgColor, '#'))     ? "background:$bgColor;"   : "background:rgba(255,255,255,.07);";
                $txtStyle  = ($textColor && str_starts_with($textColor, '#')) ? "color:$textColor;"       : "color:rgba(255,255,255,.7);";
                $delayClass = 'delay-' . min(5, ($i % 5) + 1);
            @endphp
            <div class="glass category-card reveal {{ $delayClass }}">
                <div class="cat-icon-wrap" style="{{ $bgStyle }}">
                    <i class="{{ $item['icon'] ?? 'fas fa-building' }}" style="{{ $txtStyle }}"></i>
                </div>
                <h3>{{ $item['title'] ?? '' }}</h3>
                <p>{{ $item['description'] ?? '' }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- TEMPLATES -->
@if(isset($templates) && count($templates) > 0)
<section class="templates-section">
    <div class="container" style="max-width:1440px;">
        <div class="section-head">
            <div class="section-badge reveal"><span class="dot"></span>Templates</div>
            <h2 class="section-title reveal delay-1">
                {{ $vcardPreviewsSection['title'] ?? 'Stunning vCard' }} <span class="gradient-text">Templates</span>
            </h2>
            <p class="section-subtitle reveal delay-2">
                {{ $vcardPreviewsSection['subtitle'] ?? 'Choose from professionally designed templates. Fully customizable to match your brand.' }}
            </p>
        </div>
        <div class="templates-grid">
            @foreach($templates as $i => $template)
            @php $delayClass = 'delay-' . min(5, ($i % 5) + 1); @endphp
            <div class="glass template-card reveal {{ $delayClass }}">
                <div class="template-card-badge">{{ $template['category'] }}</div>
                <div class="template-preview">
                    <iframe src="{{ $template['preview_url'] }}" loading="lazy" title="{{ $template['title'] }}" scrolling="no"></iframe>
                    <div class="template-overlay">
                        <a href="{{ $template['preview_url'] }}" target="_blank" class="template-overlay-btn">Full Preview</a>
                    </div>
                </div>
                <div class="template-footer">
                    <h3>{{ $template['title'] }}</h3>
                    <a href="{{ $template['preview_url'] }}" target="_blank" class="template-foot-link">
                        View <i class="fas fa-arrow-right" style="font-size:.75rem;"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- FEATURES -->
<section id="features" class="features-section">
    <div class="container">
        <div class="section-head">
            <div class="section-badge reveal"><span class="dot"></span>{{ $features['badge'] ?? 'Why Choose Us' }}</div>
            <h2 class="section-title reveal delay-1">{{ $features['title'] ?? 'Everything You Need in a' }} <span class="gradient-text">{{ $features['title_highlight'] ?? 'Digital vCard' }}</span></h2>
            <p class="section-subtitle reveal delay-2">{{ $features['subtitle'] ?? 'Ditch the paper. Make every connection count with rich, interactive digital business cards.' }}</p>
        </div>
        @php
            $defaultFeatures = [
                ['icon'=>'fas fa-qrcode',     'title'=>'Instant QR Sharing',       'desc'=>'Generate a unique QR code for your vCard. Anyone can scan and save your contact in seconds, no app required.'],
                ['icon'=>'fas fa-paint-brush', 'title'=>'Fully Customizable',       'desc'=>'Choose from professionally designed templates and tweak colors, fonts, content, and sections to match your brand.'],
                ['icon'=>'fas fa-mobile-alt',  'title'=>'Mobile-First Design',      'desc'=>'Every template looks stunning on all devices. Your clients get the best experience whether on phone, tablet, or desktop.'],
                ['icon'=>'fas fa-share-alt',   'title'=>'One-Link Contact Sharing', 'desc'=>'Share your vCard via a unique URL, WhatsApp, email, or SMS. Recipients can save your contact with a single tap.'],
                ['icon'=>'fas fa-chart-line',  'title'=>'Analytics and Insights',   'desc'=>'Track how many people viewed your card, which device they used, and where they came from, all in real time.'],
                ['icon'=>'fas fa-sync-alt',    'title'=>'Always Up-to-Date',        'desc'=>'Update your number, address, or offers anytime. Everyone who has your link always sees the latest version.'],
            ];
            $featuresList = !empty($featureItems) ? $featureItems : $defaultFeatures;
        @endphp
        <div class="features-grid">
            @foreach($featuresList as $i => $feat)
            @php $delayClass = 'delay-' . min(5, ($i % 5) + 1); @endphp
            <div class="glass feature-card reveal {{ $delayClass }}">
                <div class="feature-icon">
                    <i class="{{ $feat['icon'] }}" style="background:linear-gradient(120deg,var(--primary),var(--secondary));-webkit-background-clip:text;background-clip:text;color:transparent;"></i>
                </div>
                <h3>{{ $feat['title'] }}</h3>
                <p>{{ $feat['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
@if(isset($howSteps) && count($howSteps) > 0)
<section id="how-it-works" class="how-section">
    <div class="container">
        <div class="section-head">
            <div class="section-badge reveal"><span class="dot"></span>{{ $how['badge'] ?? 'Process' }}</div>
            <h2 class="section-title reveal delay-1">
                {{ $how['title'] ?? 'How It' }}<span class="gradient-text">{{ $how['highlight'] ?? ' Works' }}</span>{{ $how['suffix'] ?? '' }}
            </h2>
            <p class="section-subtitle reveal delay-2">{{ $how['subtitle'] ?? '' }}</p>
        </div>
        <div class="how-grid">
            @foreach($howSteps as $i => $step)
            @php $delayClass = 'delay-' . min(5, ($i % 5) + 1); @endphp
            <div class="glass step-card reveal {{ $delayClass }}">
                <div class="step-badge">{{ $step['number'] ?? ($i+1) }}</div>
                <h3>{{ $step['title'] ?? '' }}</h3>
                <p>{{ $step['description'] ?? '' }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA BANNER -->
@if(!empty($cta))
<section class="cta-section">
    <div class="cta-glow"></div>
    <div class="cta-inner">
        <div class="section-badge reveal" style="margin:0 auto 1.25rem;"><span class="dot"></span>{{ $cta['badge'] ?? 'Get Started Today' }}</div>
        <h2 class="reveal delay-1">{{ $cta['title'] ?? 'Ready to Go Digital?' }}</h2>
        <p class="reveal delay-2">{{ $cta['subtitle'] ?? 'Join hundreds of businesses already sharing smarter with ManageHub digital vCards.' }}</p>
        <div class="cta-btns reveal delay-3">
            @if(!empty($cta['primary_url']) && !empty($cta['primary_label']))
                <a href="{{ $cta['primary_url'] }}" class="btn-primary"><i class="fas fa-rocket"></i> {{ $cta['primary_label'] }}</a>
            @endif
            @if(!empty($cta['secondary_url']) && !empty($cta['secondary_label']))
                <a href="{{ $cta['secondary_url'] }}" class="btn-outline">{{ $cta['secondary_label'] }}</a>
            @endif
        </div>
    </div>
</section>
@endif

<!-- FOOTER -->
<footer id="contact" class="site-footer">
    <div class="footer-grid">
        <div>
            <img src="{{ \App\Helpers\BrandingHelper::getFooterLogoUrl() }}" alt="{{ $settings['site_name'] ?? 'ManageHub' }}" class="footer-logo">
            <p class="footer-about">{{ $data['footer_about'] ?? '' }}</p>
            <div class="footer-socials">
                @php
                    $socialIconMap = [
                        'facebook'=>'fab fa-facebook-f','twitter'=>'fab fa-twitter','instagram'=>'fab fa-instagram',
                        'linkedin'=>'fab fa-linkedin-in','youtube'=>'fab fa-youtube','tiktok'=>'fab fa-tiktok',
                        'pinterest'=>'fab fa-pinterest-p','snapchat'=>'fab fa-snapchat-ghost',
                        'whatsapp'=>'fab fa-whatsapp','telegram'=>'fab fa-telegram','discord'=>'fab fa-discord',
                        'github'=>'fab fa-github','medium'=>'fab fa-medium','behance'=>'fab fa-behance','dribbble'=>'fab fa-dribbble',
                    ];
                @endphp
                @forelse($socialLinks as $social)
                <a href="{{ $social['url'] ?? '#' }}" class="footer-social-btn" title="{{ $social['platform'] ?? '' }}">
                    <i class="{{ $socialIconMap[strtolower($social['platform'] ?? '')] ?? 'fas fa-globe' }}"></i>
                </a>
                @empty
                @endforelse
            </div>
        </div>
        <div class="footer-col">
            <h4>{{ $footerLinks['product_heading'] ?? 'Product' }}</h4>
            <ul>@foreach($productLinks as $link)<li><a href="{{ $link['url'] ?? '#' }}">{{ $link['label'] ?? '' }}</a></li>@endforeach</ul>
        </div>
        <div class="footer-col">
            <h4>{{ $footerLinks['resources_heading'] ?? 'Resources' }}</h4>
            <ul>@foreach($resourceLinks as $link)<li><a href="{{ $link['url'] ?? '#' }}">{{ $link['label'] ?? '' }}</a></li>@endforeach</ul>
        </div>
        <div class="footer-col">
            <h4>Contact</h4>
            @if(!empty($settings['contact_email']))<div class="footer-contact-item"><i class="fas fa-envelope"></i><a href="mailto:{{ $settings['contact_email'] }}">{{ $settings['contact_email'] }}</a></div>@endif
            @if(!empty($settings['contact_phone']))<div class="footer-contact-item"><i class="fas fa-phone"></i><a href="tel:{{ $settings['contact_phone'] }}">{{ $settings['contact_phone'] }}</a></div>@endif
            @if(!empty($settings['contact_address']))<div class="footer-contact-item"><i class="fas fa-location-dot"></i><span>{{ $settings['contact_address'] }}</span></div>@endif
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} {{ $settings['site_name'] ?? 'ManageHub' }}. All rights reserved.</p>
    </div>
</footer>

<script>
(function(){
    'use strict';
    var hb=document.getElementById('hamburger'), mm=document.getElementById('mobileMenu');
    if(hb&&mm){ hb.addEventListener('click',function(){ mm.classList.toggle('open'); }); mm.querySelectorAll('a').forEach(function(a){ a.addEventListener('click',function(){ mm.classList.remove('open'); }); }); }
    var ro=new IntersectionObserver(function(entries){ entries.forEach(function(e){ if(e.isIntersecting){ e.target.classList.add('visible'); ro.unobserve(e.target); } }); },{threshold:0.12,rootMargin:'0px 0px -40px 0px'});
    document.querySelectorAll('.reveal').forEach(function(el){ ro.observe(el); });
    function easeOut(t){ return 1-Math.pow(1-t,3); }
    function animateCounter(el){ var t=parseInt(el.dataset.target,10),s=el.dataset.suffix||'',d=1600,st=performance.now(); function tick(n){ var p=Math.min((n-st)/d,1); el.textContent=Math.round(easeOut(p)*t)+s; if(p<1) requestAnimationFrame(tick); } requestAnimationFrame(tick); }
    var so=new IntersectionObserver(function(entries){ entries.forEach(function(e){ if(e.isIntersecting){ e.target.querySelectorAll('.stat-num[data-target]').forEach(animateCounter); so.unobserve(e.target); } }); },{threshold:0.5});
    var sb=document.querySelector('.stats-bar'); if(sb) so.observe(sb);
})();
</script>
@if(!empty($data['scripts']['footer_script']))
{!! $data['scripts']['footer_script'] !!}
@endif
</body>
</html>