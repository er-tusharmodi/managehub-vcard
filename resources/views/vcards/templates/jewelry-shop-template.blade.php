@php
    require_once resource_path('views/vcards/icons/jewelry-shop-template.php');
    $bannerImage = data_get($data, "assets.bannerImage", "");
    $profileImage = data_get($data, "assets.profileImage", data_get($data, "assets.fallbackImage", ""));
    $profileAlt = data_get($data, "assets.profileAlt", data_get($data, "profile.name", ""));
    $categories = data_get($data, "categories", []);
    $categories = is_array($categories) ? $categories : [];
    $currentCat = "all";
    foreach ($categories as $cat) {
        if (!empty($cat["active"])) {
            $currentCat = $cat["key"] ?? "all";
            break;
        }
    }
    $collections = data_get($data, "collections", []);
    $collections = is_array($collections) ? $collections : [];
    if ($currentCat !== "all") {
        $collections = array_values(array_filter($collections, static function ($item) use ($currentCat) {
            $itemCat = $item["category_key"] ?? $item["cat"] ?? "";
            return $itemCat === $currentCat;
        }));
    }
    $socialIconClasses = [
        "whatsapp" => "ic-wa",
        "instagram" => "ic-ig",
        "facebook" => "ic-fb",
        "pinterest" => "ic-pin",
        "youtube" => "ic-yt",
    ];
@endphp
<!doctype html>
<html lang="en">
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1" />
        <title>{{ data_get($data, "meta.title") }}</title>
        <meta name="description" content="{{ data_get($data, 'meta.description', '') }}">
        <meta name="keywords" content="{{ data_get($data, 'meta.keywords', '') }}">
        <meta property="og:title" content="{{ data_get($data, 'meta.title', '') }}">
        <meta property="og:description" content="{{ data_get($data, 'meta.description', '') }}">
        @if(data_get($data, 'meta.og_image'))
        <meta property="og:image" content="{{ url(data_get($data, 'meta.og_image')) }}">
        @endif
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
        <link rel="stylesheet" href="{{ $assetBase }}style.css" />
        @if(!empty($vcard->head_script))
        {!! $vcard->head_script !!}
        @endif
    </head>
    <body>
        <main id="app-root" aria-live="polite" style="min-height:100vh">
            <div class="banner"{{ $bannerImage ? " style=\"background:url('" . e($bannerImage) . "') center/cover no-repeat\"" : "" }}>
                <div class="banner-pattern">
                    <svg viewBox="0 0 400 230" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
                        <defs>
                            <pattern id="diamond" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                                <polygon points="20,2 38,20 20,38 2,20" fill="none" stroke="#c9a84c" stroke-width="0.8" />
                                <circle cx="20" cy="20" r="2.5" fill="#c9a84c" />
                            </pattern>
                        </defs>
                        <rect width="400" height="230" fill="url(#diamond)" />
                    </svg>
                </div>
                <div class="banner-shine"></div>
                <div class="banner-shine2"></div>
                <div class="banner-top-bar">
                    <button class="share-btn" onclick="openShare()">
                        <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" fill="none" stroke-width="2">
                            {!! getIcon("ui_share") !!}
                        </svg>
                        <span id="banner-share">Share</span>
                    </button>
                    <button class="save-btn-top" onclick="saveContact()">
                        <svg viewBox="0 0 24 24" width="13" height="13" stroke="currentColor" fill="none" stroke-width="2.2">
                            {!! getIcon("ui_save_disk") !!}
                        </svg>
                        <span id="banner-save-contact">Save Contact</span>
                    </button>
                </div>
                <div class="banner-text">
                    <div class="banner-brandname" id="banner-brand">{{ data_get($data, "banner.brand") }}</div>
                    <div class="banner-tagline" id="banner-subtitle">{{ data_get($data, "banner.subtitle") }}</div>
                    <div class="banner-divider"><span></span><span class="diamond" id="banner-divider-symbol">{{ data_get($data, "banner.dividerSymbol") }}</span><span></span></div>
                </div>
            </div>

            <div class="profile-card">
                <div class="profile-avatar-wrap">
                    <div class="profile-avatar">
                        <img id="profile-image" src="{{ $profileImage }}" alt="{{ $profileAlt }}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;" />
                        <div class="verified-badge">
                            <svg viewBox="0 0 24 24">{!! getIcon("ui_check") !!}</svg>
                        </div>
                    </div>
                </div>
                <div class="profile-name" id="profile-name">{{ data_get($data, "profile.name") }}</div>
                <div class="profile-role" id="profile-role">{{ data_get($data, "profile.role") }}</div>
                <div class="rating-row">
                    <span class="stars" id="profile-stars">{{ data_get($data, "profile.stars") }}</span>
                    <span class="rating-num" id="profile-rating">{{ data_get($data, "profile.rating") }}</span>
                    <span class="rating-count" id="profile-rating-count">{{ data_get($data, "profile.ratingCount") }}</span>
                </div>
                <div class="profile-bio" id="profile-bio">{{ data_get($data, "profile.bio") }}</div>
                <div class="profile-action-btns">
                    <button class="pab call" onclick="callShop()">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            {!! getIcon("ui_call") !!}
                        </svg>
                        <span id="action-call">Call Now</span>
                    </button>
                    <button class="pab whatsapp" onclick="openWA()">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            {!! getIcon("ui_whatsapp") !!}
                        </svg>
                        <span id="action-whatsapp">WhatsApp</span>
                    </button>
                    <button class="pab save" onclick="saveContact()">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            {!! getIcon("ui_user") !!}
                        </svg>
                        <span id="action-save">Save Card</span>
                    </button>
                    <button class="pab email" onclick="emailShop()">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            {!! getIcon("ui_mail") !!}
                        </svg>
                        <span id="action-email">Email Us</span>
                    </button>
                    <button class="pab direction" onclick="openMaps()">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            {!! getIcon("ui_direction") !!}
                        </svg>
                        <span id="action-directions">Directions</span>
                    </button>
                    <button class="pab share" onclick="openShare()">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            {!! getIcon("ui_share") !!}
                        </svg>
                        <span id="action-share">Share Card</span>
                    </button>
                </div>
            </div>

        @if(vcard_section_enabled($data, 'collections'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke-width="1.8">
                            {!! getIcon("service_star") !!}
                        </svg>
                    </div>
                    <span class="sec-title" id="sec-title-collections">Our Collections</span>
                </div>
                <div class="sec-body" style="padding:0.75rem 0.85rem">
                    <div class="cat-scroll" id="catScroll">
                        @foreach($categories as $cat)
                            @php $catKey = $cat["key"] ?? ""; @endphp
                            <div class="cat-chip{{ $catKey === $currentCat ? " active" : "" }}" onclick="filterCat(this, {!! vcard_js_str($catKey) !!})">
                                {{ $cat["label"] ?? "" }}
                            </div>
                        @endforeach
                    </div>
                    <div style="height:0.6rem"></div>
                    <div class="collections-grid" id="collectionsGrid">
                        @foreach($collections as $item)
                            @php
                            $price = (int) ($item["price"] ?? 0);
                            $oldPrice = (int) ($item["oldPrice"] ?? 0);
                            $collImg = $item["product_image"] ?? "";
                            // Strip CSS url() wrapper if stored with it (data may use url('...') format)
                            if ($collImg && preg_match('/url\([\'"]?(.*?)[\'"]?\)/i', $collImg, $_colm)) { $collImg = $_colm[1]; }
@endphp
                            <div class="coll-card">
                                <div class="coll-img">
                                    <div class="coll-img-ph" style="background:{{ $collImg ? 'url(\'' . e($collImg) . '\') center/cover no-repeat' : ($item['bg'] ?? '') }};height:100%">
                                        @if(!$collImg)
                                            <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="rgba(0,0,0,0.25)" stroke-width="1.2">
                                                {!! getIcon("service_star") !!}
                                            </svg>
                                        @endif
                                    </div>
                                    @if(!empty($item["tag"]))
                                        <span class="coll-badge" style="background:{{ $item["tagColor"] ?? "" }}">{{ $item["tag"] }}</span>
                                    @endif
                                </div>
                                <div class="coll-body">
                                    <div class="coll-name">{{ $item["name"] ?? "" }}</div>
                                    <div class="coll-metal">{{ $item["metal"] ?? "" }}</div>
                                    <div style="font-size:.68rem;color:var(--muted);line-height:1.4;margin-bottom:.4rem">{{ $item["desc"] ?? "" }}</div>
                                    <div class="coll-footer">
                                        <div>
                                            <div class="coll-price">₹{{ format_inr($price) }}</div>
                                            @if($oldPrice > 0)
                                                <div class="coll-old">₹{{ format_inr($oldPrice) }}</div>
                                            @endif
                                        </div>
                                        <button class="enquire-btn" onclick="enquireWA({!! vcard_js_str($item["name"] ?? "") !!})">
                                            <svg viewBox="0 0 24 24" width="11" height="11" stroke="currentColor" fill="none" stroke-width="2.5">
                                                {!! getIcon("ui_whatsapp") !!}
                                            </svg>
                                            Enquire
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if(vcard_section_enabled($data, 'purity'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke-width="1.8">
                            {!! getIcon("ui_shield") !!}
                        </svg>
                    </div>
                    <span class="sec-title" id="sec-title-purity">Gold Purity We Offer</span>
                </div>
                <div class="sec-body">
                    <div class="purity-row" id="purityRow">
                        @foreach(data_get($data, "purity.items", []) as $item)
                            <div class="purity-item">
                                <div class="purity-karat">{{ $item["karat"] ?? "" }}</div>
                                <div class="purity-label">{{ $item["label"] ?? "" }}</div>
                            </div>
                        @endforeach
                    </div>
                    <div style="margin-top:0.8rem;padding:0.65rem;background:linear-gradient(135deg,#fff8e1,#fff3cd);border-radius:10px;border:1px solid #e8d5a0;display:flex;align-items:center;gap:0.6rem;">
                        <span style="font-size:1.1rem" id="purity-hallmark-emoji">{{ data_get($data, "purity.hallmark.emoji") }}</span>
                        <span style="font-size:0.76rem;color:var(--muted);line-height:1.5;">
                            <strong style="color:var(--dark)" id="purity-hallmark-title">{{ data_get($data, "purity.hallmark.title") }}</strong>
                            <span id="purity-hallmark-separator">{{ data_get($data, "purity.hallmark.separator") }}</span>
                            <span id="purity-hallmark-text">{{ data_get($data, "purity.hallmark.text") }}</span>
                        </span>
                    </div>
                </div>
            </div>
        @endif

        @if(vcard_section_enabled($data, 'certifications'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke-width="1.8">
                            {!! getIcon("ui_medal") !!}
                        </svg>
                    </div>
                    <span class="sec-title" id="sec-title-certifications">Our Certifications</span>
                </div>
                <div class="sec-body">
                    <div class="cert-grid" id="certGrid">
                        @foreach(data_get($data, "certifications", []) as $item)
                            <div class="cert-item">
                                <div class="cert-ico" style="background:{{ $item["bg"] ?? "#fff" }}">{{ $item["emoji"] ?? "" }}</div>
                                <div class="cert-text">
                                    <div class="cert-name">{{ $item["name"] ?? "" }}</div>
                                    <div class="cert-sub">{{ $item["sub"] ?? "" }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if(vcard_section_enabled($data, 'services'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke-width="1.8">
                            {!! getIcon("service_wrench") !!}
                        </svg>
                    </div>
                    <span class="sec-title" id="sec-title-services">Our Services</span>
                </div>
                <div class="sec-body">
                    <div class="svc-list" id="servicesList">
                        @foreach(data_get($data, "services", []) as $item)
                            @php $iconKey = "service_" . ($item["icon"] ?? "star"); @endphp
                            <div class="svc-item">
                                <div class="svc-ico">
                                    <svg viewBox="0 0 24 24">{!! getIcon($iconKey) ?: getIcon("service_star") !!}</svg>
                                </div>
                                <div class="svc-info">
                                    <div class="svc-name">{{ $item["name"] ?? "" }}</div>
                                    <div class="svc-desc">{{ $item["desc"] ?? "" }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if(vcard_section_enabled($data, 'showroom'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke-width="1.8">
                            {!! getIcon("service_map") !!}
                        </svg>
                    </div>
                    <span class="sec-title" id="sec-title-showroom">Visit Our Showroom</span>
                </div>
                <div class="sec-body">
                    <a class="address-link" href="#" onclick="return (openMaps(), !1);">
                        <div class="addr-icon-wrap">
                            <svg viewBox="0 0 24 24" width="18" height="18" stroke-width="1.8">
                                {!! getIcon("service_map") !!}
                            </svg>
                        </div>
                        <div class="addr-text">
                            <strong id="showroom-name">{{ data_get($data, "showroom.name") }}</strong>
                            <span id="showroom-line1">{{ data_get($data, "showroom.line1") }}</span><br />
                            <span id="showroom-line2">{{ data_get($data, "showroom.line2") }}</span><br />
                            <a class="map-btn" href="#" onclick="return (openMaps(), !1);">
                                <svg viewBox="0 0 24 24">
                                    {!! getIcon("ui_direction") !!}
                                </svg>
                                <span id="showroom-map-label">Get Directions</span>
                            </a>
                        </div>
                    </a>
                </div>
            </div>
        @endif

        @if(vcard_section_enabled($data, 'hours'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke-width="1.8">
                            {!! getIcon("ui_clock") !!}
                        </svg>
                    </div>
                    <span class="sec-title" id="sec-title-hours">Store Hours</span>
                </div>
                <div class="sec-body">
                    <table class="hours-table" id="hoursTable">
                        @foreach(data_get($data, "hours", []) as $row)
                            @if(!empty($row["today"]))
                                <tr class="today">
                                    <td class="day">{{ $row["day"] ?? "" }} <span class="today-badge">TODAY</span></td>
                                    <td class="time" style="color:var(--gold);font-weight:700">{{ $row["time"] ?? "" }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td class="day">{{ $row["day"] ?? "" }}</td>
                                    <td class="time">{{ $row["time"] ?? "" }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
                </div>
            </div>
        @endif

        @if(vcard_section_enabled($data, 'follow'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke-width="1.8">
                            {!! getIcon("ui_share") !!}
                        </svg>
                    </div>
                    <span class="sec-title" id="sec-title-follow">Follow &amp; Connect</span>
                </div>
                <div class="sec-body">
                    <div class="social-list" id="socialList">
                        @foreach(data_get($data, "followLinks", []) as $item)
                            @php
                            $type = $item["type"] ?? "whatsapp";
                            $iconKey = "social_" . $type;
                            $iconClass = $socialIconClasses[$type] ?? $socialIconClasses["whatsapp"];
                            $action = "";
                            if (($item["action"] ?? "") === "openWA") {
                                $action = "openWA()";
                            } elseif (!empty($item["url"])) {
                                $action = "openExternal(" . js_str($item["url"]) . ")";
                            }
@endphp
                            <div class="social-item{{ $type === "pinterest" ? " ic-pin" : "" }}"{{ $action ? " onclick=\"{$action}\"" : "" }}>
                                <div class="s-ico {{ $iconClass }}">{!! getIcon($iconKey) !!}</div>
                                <div>
                                    <div class="s-name">{{ $item["name"] ?? "" }}</div>
                                    <div class="s-val">{{ $item["value"] ?? "" }}</div>
                                </div>
                                <div class="s-arrow">
                                    <svg viewBox="0 0 24 24">{!! getIcon("ui_arrow_right") !!}</svg>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if(vcard_section_enabled($data, 'enquiry'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke-width="1.8">
                            {!! getIcon("ui_mail") !!}
                        </svg>
                    </div>
                    <span class="sec-title" id="sec-title-enquiry">Send Enquiry</span>
                </div>
                <div class="sec-body">
                    <div id="enquiryForm">
                        <input class="form-field" type="text" id="eName" placeholder="{{ data_get($data, "enquiryForm.namePlaceholder") }}" />
                        <input class="form-field" type="tel" id="ePhone" placeholder="{{ data_get($data, "enquiryForm.phonePlaceholder") }}" />
                        <input class="form-field" type="email" id="eEmail" placeholder="{{ data_get($data, "enquiryForm.emailPlaceholder") }}" />
                        <select class="form-field form-select" id="eCategory">
                            <option value="">{{ data_get($data, "enquiryForm.categoryPlaceholder") }}</option>
                            @foreach(data_get($data, "enquiryForm.categories", []) as $item)
                                <option>{{ $item }}</option>
                            @endforeach
                        </select>
                        <input class="form-field" type="text" id="eBudget" placeholder="{{ data_get($data, "enquiryForm.budgetPlaceholder") }}" />
                        <textarea class="form-field" id="eMsg" placeholder="{{ data_get($data, "enquiryForm.messagePlaceholder") }}"></textarea>
                        <button class="form-submit" onclick="submitEnquiry()">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none" stroke-width="2">
                                {!! getIcon("ui_send") !!}
                            </svg>
                            <span id="enquiry-submit-label">Send Enquiry via WhatsApp</span>
                        </button>
                    </div>
                    <div class="form-success" id="enquirySuccess">
                        <div class="tick" id="enquiry-success-icon">{{ data_get($data, "enquiryForm.successIcon") }}</div>
                        <p>
                            <strong id="enquiry-success-title">{{ data_get($data, "enquiryForm.successTitle") }}</strong><br />
                            <span id="enquiry-success-text">{{ data_get($data, "enquiryForm.successText") }}</span>
                        </p>
                        <button onclick="resetEnquiry()" style="margin-top:1rem;background:linear-gradient(135deg,var(--gold),var(--gold2));border:none;border-radius:10px;padding:0.6rem 1.5rem;font-size:0.8rem;font-weight:700;color:var(--deep);cursor:pointer;">
                            <span id="enquiry-success-button">New Enquiry</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if(vcard_section_enabled($data, 'qr'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke-width="1.8">
                            {!! getIcon("ui_grid") !!}
                        </svg>
                    </div>
                    <span class="sec-title" id="sec-title-scan">Scan &amp; Share</span>
                </div>
                <div class="sec-body">
                    <div class="qr-section">
                        <div id="vcardQR"></div>
                        <div class="qr-desc" id="qr-description">{{ data_get($data, "qr.description") }}</div>
                        <button class="qr-download-btn" onclick="downloadQR()">
                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" fill="none" stroke-width="2">
                                {!! getIcon("ui_download") !!}
                            </svg>
                            <span id="qr-download-label">Download QR Code</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

            <div class="footer">
                <div class="footer-divider"></div>
                <strong id="footer-brand">{{ data_get($data, "footer.brand") }}</strong><br />
                <span id="footer-line2">{{ data_get($data, "footer.line2") }}</span><br />
                <span id="footer-line3" style="color:var(--gold);letter-spacing:2px">{{ data_get($data, "footer.line3") }}</span><br />
                <span id="footer-line4" style="font-size:0.68rem">{{ data_get($data, "footer.line4") }}</span><br />
                <span style="font-size:0.63rem;color:#aaa;margin-top:0.35rem;display:inline-block;">Powered by <a href="{{ config('app.url') }}" target="_blank" rel="noopener" style="color:var(--gold);text-decoration:none;font-weight:600;">{{ config('app.name') }}</a></span>
            </div>

            <div class="bottom-bar">
                <button class="bb-btn call" onclick="callShop()">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#2e7d32" stroke-width="2">
                        {!! getIcon("ui_call") !!}
                    </svg>
                    <span class="bb-label" id="bb-call">Call</span>
                </button>
                <button class="bb-btn save" onclick="saveContact()">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#b8860b" stroke-width="2">
                        {!! getIcon("ui_save_disk") !!}
                    </svg>
                    <span class="bb-label" id="bb-save">Save Contact</span>
                </button>
                <button class="bb-btn wa" onclick="openWA()">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#128c7e" stroke-width="2">
                        {!! getIcon("ui_whatsapp") !!}
                    </svg>
                    <span class="bb-label" id="bb-whatsapp">WhatsApp</span>
                </button>
            </div>

            <div class="cart-overlay" id="cartOverlay" onclick="closeCartOutside(event)">
                <div class="cart-sheet">
                    <div class="cart-handle"></div>
                    <div class="cart-title">
                        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" fill="none" stroke-width="1.8">
                            {!! getIcon("ui_cart") !!}
                        </svg>
                        <span id="cart-title">Your Wishlist / Cart</span>
                    </div>
                    <div id="cartBody"></div>
                </div>
            </div>

            <div class="share-modal" id="shareModal" onclick="closeShare(event)">
                <div class="share-sheet">
                    <div class="cart-handle"></div>
                    <div class="share-title" id="share-modal-title">Share</div>
                    <div class="share-btns">
                        <button class="share-opt wa" onclick="shareWA()">
                            <svg viewBox="0 0 24 24" stroke="#128c7e" stroke-width="1.8">
                                {!! getIcon("ui_whatsapp") !!}
                            </svg>
                            <span id="share-wa-label">WhatsApp</span>
                        </button>
                        <button class="share-opt fb" onclick="shareFB()">
                            <svg viewBox="0 0 24 24" stroke="#1877f2" stroke-width="1.8">
                                {!! getIcon("ui_facebook") !!}
                            </svg>
                            <span id="share-fb-label">Facebook</span>
                        </button>
                        <button class="share-opt copy" onclick="copyLink()">
                            <svg viewBox="0 0 24 24" stroke="#8a7355" stroke-width="1.8">
                                {!! getIcon("ui_copy") !!}
                            </svg>
                            <span id="share-copy-label">Copy Link</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="promo-overlay" id="promoOverlay" onclick="closePromo(event)"></div>

            <div class="toast" id="toast">
                <svg viewBox="0 0 24 24" width="14" height="14" stroke-width="2">
                    {!! getIcon("ui_check_circle") !!}
                </svg>
                <span id="toastMsg">{{ data_get($data, "labels.toastDefault") }}</span>
            </div>

            <div class="icon-templates" aria-hidden="true" style="display:none">
                <span id="icon-service-star"><svg viewBox="0 0 24 24">{!! getIcon("service_star") !!}</svg></span>
                <span id="icon-service-map"><svg viewBox="0 0 24 24">{!! getIcon("service_map") !!}</svg></span>
                <span id="icon-service-wrench"><svg viewBox="0 0 24 24">{!! getIcon("service_wrench") !!}</svg></span>
                <span id="icon-service-card"><svg viewBox="0 0 24 24">{!! getIcon("service_card") !!}</svg></span>
                <span id="icon-service-arrow"><svg viewBox="0 0 24 24">{!! getIcon("service_arrow") !!}</svg></span>
                <span id="icon-service-heart"><svg viewBox="0 0 24 24">{!! getIcon("service_heart") !!}</svg></span>

                <span id="icon-social-whatsapp">{!! getIcon("social_whatsapp") !!}</span>
                <span id="icon-social-instagram">{!! getIcon("social_instagram") !!}</span>
                <span id="icon-social-facebook">{!! getIcon("social_facebook") !!}</span>
                <span id="icon-social-pinterest">{!! getIcon("social_pinterest") !!}</span>
                <span id="icon-social-youtube">{!! getIcon("social_youtube") !!}</span>

                <span id="icon-ui-arrow-right"><svg viewBox="0 0 24 24">{!! getIcon("ui_arrow_right") !!}</svg></span>
                <span id="icon-ui-cart"><svg viewBox="0 0 24 24">{!! getIcon("ui_cart") !!}</svg></span>
                <span id="icon-ui-copy"><svg viewBox="0 0 24 24">{!! getIcon("ui_copy") !!}</svg></span>
                <span id="icon-ui-minus"><svg viewBox="0 0 24 24">{!! getIcon("ui_minus") !!}</svg></span>
                <span id="icon-ui-plus"><svg viewBox="0 0 24 24">{!! getIcon("ui_plus") !!}</svg></span>
                <span id="icon-ui-whatsapp"><svg viewBox="0 0 24 24">{!! getIcon("ui_whatsapp") !!}</svg></span>
            </div>
        </main>
        <script>
            window.__APP__ = {!! vcard_js_str($data) !!};
            window.__VCARD_SUBDOMAIN__ = {!! json_encode($subdomain) !!};
            window.__APP_URL__ = {!! json_encode('https://' . $vcard->subdomain . '.' . config('vcard.base_domain')) !!};
        </script>
        <script src="{{ $assetBase }}script.js"></script>
        @if(!empty($vcard->footer_script))
        {!! $vcard->footer_script !!}
        @endif
    </body>
</html>