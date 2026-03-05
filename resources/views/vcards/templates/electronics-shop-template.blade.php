@php
    require_once resource_path('views/vcards/icons/electronics-shop-template.php');
    $bannerImage = data_get($data, "assets.bannerImage", "");
    $profileImage = data_get($data, "assets.profileImage", data_get($data, "assets.fallbackImage", ""));
    $profileAlt = data_get($data, "assets.profileAlt", data_get($data, "profile.name", ""));
    $socialIconClasses = [
        "whatsapp" => "ic-wa",
        "facebook" => "ic-fb",
        "instagram" => "ic-ig",
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
    </head>
    <body>
        <main id="app-root" aria-live="polite" style="min-height:100vh">
            <div class="banner"{{ $bannerImage ? " style=\"background:url('" . e($bannerImage) . "') center/cover no-repeat\"" : "" }}>
                <div class="banner-circuit">
                    <svg viewBox="0 0 480 230" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                        <line x1="0" y1="50" x2="120" y2="50" stroke="#00c2ff" stroke-width=".8" />
                        <circle cx="120" cy="50" r="4" stroke="#00c2ff" stroke-width=".8" fill="none" />
                        <line x1="120" y1="50" x2="120" y2="100" stroke="#00c2ff" stroke-width=".8" />
                        <line x1="120" y1="100" x2="220" y2="100" stroke="#00c2ff" stroke-width=".8" />
                        <circle cx="220" cy="100" r="3" stroke="#00c2ff" stroke-width=".8" fill="rgba(0,194,255,.3)" />
                        <line x1="220" y1="100" x2="220" y2="150" stroke="#00c2ff" stroke-width=".8" />
                        <line x1="220" y1="150" x2="340" y2="150" stroke="#00c2ff" stroke-width=".8" />
                        <circle cx="340" cy="150" r="4" stroke="#00c2ff" stroke-width=".8" fill="none" />
                        <line x1="340" y1="150" x2="340" y2="80" stroke="#00c2ff" stroke-width=".8" />
                        <line x1="340" y1="80" x2="480" y2="80" stroke="#00c2ff" stroke-width=".8" />
                        <line x1="60" y1="0" x2="60" y2="170" stroke="#0077ff" stroke-width=".6" />
                        <circle cx="60" cy="170" r="3" stroke="#0077ff" stroke-width=".6" fill="none" />
                        <line x1="60" y1="170" x2="180" y2="170" stroke="#0077ff" stroke-width=".6" />
                        <line x1="180" y1="170" x2="180" y2="120" stroke="#0077ff" stroke-width=".6" />
                        <rect x="175" y="115" width="10" height="10" stroke="#0077ff" stroke-width=".6" fill="none" />
                        <line x1="390" y1="0" x2="390" y2="50" stroke="#00c2ff" stroke-width=".8" />
                        <rect x="380" y="50" width="20" height="14" rx="2" stroke="#00c2ff" stroke-width=".8" fill="none" />
                        <line x1="390" y1="64" x2="390" y2="110" stroke="#00c2ff" stroke-width=".8" />
                        <circle cx="390" cy="110" r="5" stroke="#00c2ff" stroke-width=".8" fill="none" />
                        <line x1="270" y1="0" x2="270" y2="60" stroke="#0077ff" stroke-width=".5" />
                        <rect x="260" y="60" width="8" height="8" rx="1" stroke="#0077ff" stroke-width=".5" fill="none" />
                        <line x1="268" y1="64" x2="310" y2="64" stroke="#0077ff" stroke-width=".5" />
                        <line x1="310" y1="64" x2="310" y2="130" stroke="#0077ff" stroke-width=".5" />
                        <circle cx="310" cy="130" r="2.5" fill="#0077ff" />
                        <line x1="140" y1="200" x2="280" y2="200" stroke="#00c2ff" stroke-width=".7" />
                        <circle cx="140" cy="200" r="3.5" stroke="#00c2ff" stroke-width=".7" fill="none" />
                        <circle cx="280" cy="200" r="3.5" stroke="#00c2ff" stroke-width=".7" fill="none" />
                        <line x1="420" y1="120" x2="480" y2="120" stroke="#0077ff" stroke-width=".6" />
                        <rect x="415" y="115" width="5" height="10" stroke="#0077ff" stroke-width=".6" fill="none" />
                    </svg>
                </div>
                <div class="banner-glow"></div>
                <div class="banner-glow2"></div>
                <div class="banner-overlay"></div>
                <div class="banner-top-bar">
                    <button class="share-btn" onclick="openShare()">
                        <svg class="ic-sm" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                            <circle cx="18" cy="5" r="3" />
                            <circle cx="6" cy="12" r="3" />
                            <circle cx="18" cy="19" r="3" />
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                        </svg>
                        <span id="banner-share">Share</span>
                    </button>
                    <button class="save-btn-top" onclick="saveContact()">
                        <svg class="ic-sm" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span id="banner-save">Save Contact</span>
                    </button>
                </div>
                <div class="banner-tagline">
                    <div class="btag" id="banner-tagline-main">{{ data_get($data, "banner.mainTagline") }}</div>
                    <div class="bsub" id="banner-tagline-sub">{{ data_get($data, "banner.subTagline") }}</div>
                </div>
            </div>

            <div class="profile-card">
                <div class="profile-avatar-wrap">
                    <div class="profile-avatar">
                        <img id="profile-image" src="{{ $profileImage }}" alt="{{ $profileAlt }}" style="width:100%;height:100%;object-fit:cover" />
                    </div>
                </div>
                <div class="profile-name" id="profile-name">{{ data_get($data, "profile.name") }}</div>
                <div class="profile-role" id="profile-role">{{ data_get($data, "profile.role") }}</div>
                <div class="profile-badges" id="profile-badges">
                    @foreach(data_get($data, "profile.badges", []) as $item)
                        <span class="badge {{ $item["className"] ?? "" }}">{{ $item["text"] ?? "" }}</span>
                    @endforeach
                </div>
                <div class="profile-bio" id="profile-bio">{{ data_get($data, "profile.bio") }}</div>
                <div class="profile-action-btns">
                    <button class="pab call" onclick="callShop()">
                        <svg class="ic" viewBox="0 0 24 24" fill="none">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.61 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.59a16 16 0 0 0 6 6l.96-.96a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
                        </svg>
                        <span id="action-call">Call Us</span>
                    </button>
                    <button class="pab whatsapp" onclick="openWA()">
                        <svg class="ic" viewBox="0 0 24 24" fill="none">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                        </svg>
                        <span id="action-whatsapp">WhatsApp</span>
                    </button>
                    <button class="pab save" onclick="saveContact()">
                        <svg class="ic" viewBox="0 0 24 24" fill="none">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        <span id="action-save">Save</span>
                    </button>
                    <button class="pab email" onclick="emailShop()">
                        <svg class="ic" viewBox="0 0 24 24" fill="none">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                        <span id="action-email">Email</span>
                    </button>
                    <button class="pab direction" onclick="openMaps()">
                        <svg class="ic" viewBox="0 0 24 24" fill="none">
                            <polygon points="3 11 22 2 13 21 11 13 3 11" />
                        </svg>
                        <span id="action-direction">Direction</span>
                    </button>
                    <button class="pab enquiry" onclick="openEnquiry()">
                        <svg class="ic" viewBox="0 0 24 24" fill="none">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                        </svg>
                        <span id="action-enquiry">Enquire</span>
                    </button>
                </div>
            </div>

            @if(vcard_section_enabled($data, 'whyChoose'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg class="ic" viewBox="0 0 24 24" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-why">Why Choose Us</div>
                </div>
                <div class="sec-body">
                    <div class="info-pills" id="whyPills">
                        @foreach(data_get($data, "whyChoose", []) as $item)
                            @php $iconKey = "pill_" . ($item["icon"] ?? ""); @endphp
                            <span class="pill {{ $item["tone"] ?? "" }}">
                                {!! getIcon($iconKey) !!}
                                {{ $item["text"] ?? "" }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if(vcard_section_enabled($data, 'categories'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg class="ic" viewBox="0 0 24 24" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" rx="1" />
                            <rect x="14" y="3" width="7" height="7" rx="1" />
                            <rect x="3" y="14" width="7" height="7" rx="1" />
                            <rect x="14" y="14" width="7" height="7" rx="1" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-categories">Product Categories</div>
                </div>
                <div class="sec-body">
                    <div class="cat-grid" id="categoriesGrid">
                        @foreach(data_get($data, "categories", []) as $item)
                            @php
                            $query = $item["query"] ?? $item["name"] ?? "";
@endphp
                            <div class="cat-card" onclick="enquireWA({!! vcard_js_str($query) !!})">
                                <div class="cat-name">{{ $item["name"] ?? "" }}</div>
                                <div class="cat-count">{{ $item["count"] ?? "" }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if(vcard_section_enabled($data, 'featuredProducts'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg class="ic" viewBox="0 0 24 24" stroke-width="2">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <path d="M16 10a4 4 0 0 1-8 0" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-featured">Featured Products</div>
                    <div class="sec-badge" id="sec-badge-featured">{{ data_get($data, "featured.badge") }}</div>
                </div>
                <div class="sec-body">
                    <div class="emi-note">
                        <svg class="ic" viewBox="0 0 24 24">
                            <rect x="1" y="4" width="22" height="16" rx="2" />
                            <line x1="1" y1="10" x2="23" y2="10" />
                        </svg>
                        <span id="featured-emi-note">{{ data_get($data, "featured.emiNote") }}</span>
                    </div>
                    <div class="products-grid" id="productsGrid">
                        @foreach(data_get($data, "products", []) as $item)
                            @php
                            $oldPrice = (int) ($item["oldPrice"] ?? 0);
                            $price = (int) ($item["price"] ?? 0);
                            $discount = $oldPrice > 0 ? (int) round((($oldPrice - $price) / $oldPrice) * 100) : 0;
@endphp
                            <div class="prod-card">
                                <div class="prod-img">
                                    <div class="prod-img-placeholder" style="background:{{ $item["bg"] ?? "" }};height:100%">
                                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.5)" stroke-width="1.4">
                                            <rect x="2" y="3" width="20" height="14" rx="2"/>
                                            <path d="M8 21h8M12 17v4"/>
                                        </svg>
                                    </div>
                                    @if(!empty($item["tag"]))
                                        <span class="prod-tag {{ strtolower((string) $item["tag"]) }}" style="background:{{ $item["tagColor"] ?? "" }}">
                                            {{ $item["tag"] }}
                                        </span>
                                    @endif
                                </div>
                                <div class="prod-body">
                                    <div class="prod-brand">{{ $item["brand"] ?? "" }}</div>
                                    <div class="prod-name">{{ $item["name"] ?? "" }}</div>
                                    <div class="prod-spec">{{ $item["spec"] ?? "" }}</div>
                                    <div class="prod-footer">
                                        <div>
                                            <div class="prod-price">₹{{ format_inr($price) }}</div>
                                            @if($oldPrice > 0)
                                                <span class="prod-old">₹{{ format_inr($oldPrice) }}</span>
                                                <div class="prod-discount">{{ $discount }}% OFF</div>
                                            @endif
                                        </div>
                                        <div class="qty-ctrl">
                                            <button class="qty-btn" onclick="changeQty({{ (int) ($item["id"] ?? 0) }},-1)">
                                                <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                            </button>
                                            <span class="qty-num" id="qty-{{ (int) ($item["id"] ?? 0) }}">0</span>
                                            <button class="qty-btn" onclick="changeQty({{ (int) ($item["id"] ?? 0) }},1)">
                                                <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if(vcard_section_enabled($data, 'repairServices'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg class="ic" viewBox="0 0 24 24" stroke-width="2">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-repair">Repair Services</div>
                    <div class="sec-badge" id="sec-badge-repair">{{ data_get($data, "repair.badge") }}</div>
                </div>
                <div class="sec-body">
                    <div class="repair-list" id="repairList">
                        @foreach(data_get($data, "repairServices", []) as $item)
                            @php $query = $item["query"] ?? $item["name"] ?? ""; @endphp
                            <div class="repair-item">
                                <div class="repair-info">
                                    <div class="repair-name">{{ $item["name"] ?? "" }}</div>
                                    <div class="repair-sub">{{ $item["sub"] ?? "" }}</div>
                                </div>
                                <div class="repair-price">{{ $item["price"] ?? "" }}</div>
                                <button class="repair-wa" onclick="enquireWA({!! vcard_js_str($query) !!})">
                                    <svg width="22" height="22" viewBox="0 0 24 24" stroke-width="2">
                                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if(vcard_section_enabled($data, 'brands'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg class="ic" viewBox="0 0 24 24" stroke-width="2">
                            <polygon points="12 2 2 7 12 12 22 7 12 2" />
                            <polyline points="2 17 12 22 22 17" />
                            <polyline points="2 12 12 17 22 12" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-brands">Brands We Carry</div>
                </div>
                <div class="sec-body">
                    <div class="brands-grid" id="brandsGrid">
                        @foreach(data_get($data, "brands", []) as $item)
                            <span class="brand-chip">{{ $item }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if(vcard_section_enabled($data, 'gallery'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg class="ic" viewBox="0 0 24 24" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <polyline points="21 15 16 10 5 21" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-gallery">Our Store Gallery</div>
                </div>
                <div class="sec-body">
                    <div class="gallery-grid" id="galleryGrid">
                        @foreach(data_get($data, "gallery", []) as $url)
                            <div class="gal-item">
                                <img class="gal-placeholder" src="{{ $url }}" alt="" style="width:100%;height:100%;object-fit:cover;display:block;" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if(vcard_section_enabled($data, 'hours'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg class="ic" viewBox="0 0 24 24" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-hours">Store Hours</div>
                </div>
                <div class="sec-body">
                    <div class="today-badge">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        <span id="hours-open-label">{{ data_get($data, "hours.openLabel") }}</span>
                    </div>
                    <table class="hours-table" id="hoursTable">
                        @foreach(data_get($data, "hours.rows", []) as $row)
                            <tr class="{{ $row["rowClass"] ?? "" }}">
                                <td class="day">{{ $row["day"] ?? "" }}</td>
                                <td class="time {{ $row["timeClass"] ?? "" }}">{{ $row["time"] ?? "" }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            @endif

            @if(vcard_section_enabled($data, 'location'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg class="ic" viewBox="0 0 24 24" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-location">Our Location</div>
                </div>
                <div class="sec-body">
                    <a class="address-link" href="#" onclick="return (openMaps(), !1);">
                        <div class="addr-icon-wrap">
                            <svg class="ic" viewBox="0 0 24 24" stroke="#c0392b" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                        </div>
                        <div class="addr-text">
                            <strong id="location-strong">{{ data_get($data, "location.titleLine") }}</strong>
                            <span id="location-line">{{ data_get($data, "location.addressLine") }}</span>
                            <span class="map-btn">
                                <svg class="ic-sm" viewBox="0 0 24 24" stroke="#fff" stroke-width="2">
                                    <polygon points="3 11 22 2 13 21 11 13 3 11" />
                                </svg>
                                <span id="location-map-label">{{ data_get($data, "location.mapLabel") }}</span>
                            </span>
                        </div>
                    </a>
                </div>
            </div>
            @endif

            @if(vcard_section_enabled($data, 'follow'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg class="ic" viewBox="0 0 24 24" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="2" y1="12" x2="22" y2="12" />
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-follow">Follow &amp; Connect</div>
                </div>
                <div class="sec-body">
                    <div class="social-list" id="socialList">
                        @foreach(data_get($data, "socialLinks", []) as $item)
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
                            <div class="social-item"{{ $action ? " onclick=\"{$action}\"" : "" }}>
                                <div class="s-ico {{ $iconClass }}">{!! getIcon($iconKey) !!}</div>
                                <div>
                                    <div class="s-name">{{ $item["name"] ?? "" }}</div>
                                    <div class="s-val">{{ $item["value"] ?? "" }}</div>
                                </div>
                                <div class="s-arrow">
                                    <svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2.5">
                                        <polyline points="9 18 15 12 9 6" />
                                    </svg>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if(vcard_section_enabled($data, 'payments'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg class="ic" viewBox="0 0 24 24" stroke-width="2">
                            <rect x="1" y="4" width="22" height="16" rx="2" />
                            <line x1="1" y1="10" x2="23" y2="10" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-payments">Payment Options</div>
                </div>
                <div class="sec-body">
                    <div class="payment-list" id="paymentList">
                        @foreach(data_get($data, "payments", []) as $item)
                            @php
                            $iconName = $item["icon"] ?? "";
                            if (!$iconName) {
                                $label = strtolower((string) ($item["name"] ?? ""));
                                if (str_contains($label, "upi") || str_contains($label, "qr")) {
                                    $iconName = "upi";
                                } elseif (str_contains($label, "card")) {
                                    $iconName = "card";
                                } elseif (str_contains($label, "bank")) {
                                    $iconName = "bank";
                                } elseif (str_contains($label, "cash")) {
                                    $iconName = "cash";
                                }
                            }
                            if ($iconName === "") {
                                $iconName = "cash";
                            }
                            $iconKey = "pay_" . $iconName;
@endphp
                            <div class="pay-item">
                                <div class="pay-icon-wrap">
                                    <span style="display:flex;color:{{ $item["stroke"] ?? "#3b82f6" }}">{!! getIcon($iconKey) !!}</span>
                                </div>
                                <div>
                                    <div class="pay-name">{{ $item["name"] ?? "" }}</div>
                                    <div class="pay-detail">{{ $item["detail"] ?? "" }}</div>
                                </div>
                                <div class="pay-badge">{{ $item["badge"] ?? "" }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if(vcard_section_enabled($data, 'enquiry'))
            <div class="sec" id="enquirySection">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg class="ic" viewBox="0 0 24 24" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-enquiry">Send Enquiry</div>
                </div>
                <div class="sec-body">
                    <div id="contactForm">
                        <div class="bf-row">
                            <div class="bf-group">
                                <label class="bf-label" id="label-name">{{ data_get($data, "enquiryForm.nameLabel") }}</label>
                                <input class="bf-input" id="cName" placeholder="{{ data_get($data, "enquiryForm.namePlaceholder") }}" type="text" />
                            </div>
                            <div class="bf-group">
                                <label class="bf-label" id="label-phone">{{ data_get($data, "enquiryForm.phoneLabel") }}</label>
                                <input class="bf-input" id="cPhone" placeholder="{{ data_get($data, "enquiryForm.phonePlaceholder") }}" type="tel" />
                            </div>
                        </div>
                        <div class="bf-group">
                            <label class="bf-label" id="label-email">{{ data_get($data, "enquiryForm.emailLabel") }}</label>
                            <input class="bf-input" id="cEmail" placeholder="{{ data_get($data, "enquiryForm.emailPlaceholder") }}" type="email" />
                        </div>
                        <div class="bf-group">
                            <label class="bf-label" id="label-category">{{ data_get($data, "enquiryForm.categoryLabel") }}</label>
                            <select class="bf-input" id="cCat">
                                <option value="">{{ data_get($data, "enquiryForm.categoryPlaceholder") }}</option>
                                @foreach(data_get($data, "enquiryForm.categories", []) as $item)
                                    <option>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="bf-group">
                            <label class="bf-label" id="label-message">{{ data_get($data, "enquiryForm.messageLabel") }}</label>
                            <textarea class="bf-input" id="cMsg" placeholder="{{ data_get($data, "enquiryForm.messagePlaceholder") }}"></textarea>
                        </div>
                        <button class="bf-submit" onclick="submitContact()">
                            <svg class="ic" viewBox="0 0 24 24">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                            </svg>
                            <span id="enquiry-submit">Send via WhatsApp</span>
                        </button>
                    </div>
                    <div class="contact-success" id="contactSuccess">
                        <svg viewBox="0 0 24 24">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                        <h4 id="enquiry-success-title">{{ data_get($data, "enquiryForm.successTitle") }}</h4>
                        <p id="enquiry-success-text">{{ data_get($data, "enquiryForm.successText") }}</p>
                        <button class="cf-reset" onclick="resetContact()">
                            <span id="enquiry-success-button">Send another enquiry</span>
                        </button>
                    </div>
                </div>
            </div>
            @endif

            @if(vcard_section_enabled($data, 'qr'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg class="ic" viewBox="0 0 24 24" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" rx="1" />
                            <rect x="14" y="3" width="7" height="7" rx="1" />
                            <rect x="3" y="14" width="7" height="7" rx="1" />
                            <rect x="9" y="9" width="2" height="2" />
                            <rect x="13" y="13" width="2" height="2" />
                            <rect x="17" y="13" width="4" height="2" />
                            <rect x="15" y="17" width="6" height="4" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-qr">QR Code &amp; Contact</div>
                </div>
                <div class="sec-body">
                    <div class="qr-card-inner">
                        <p style="font-size:0.77rem;color:var(--muted);margin-bottom:0.25rem;" id="qr-note">{{ data_get($data, "qr.note") }}</p>
                        <div id="vcardQR"></div>
                        <div class="qr-actions">
                            <button class="qr-btn" onclick="downloadQR()">
                                <svg viewBox="0 0 24 24">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="7 10 12 15 17 10" />
                                    <line x1="12" y1="15" x2="12" y2="3" />
                                </svg>
                                <span id="qr-download">Download QR</span>
                            </button>
                            <button class="qr-btn" onclick="saveContact()">
                                <svg viewBox="0 0 24 24">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                                <span id="qr-save-contact">Save Contact</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div style="height:0.5rem"></div>

            <div class="float-bar">
                <button class="fab call-fab" onclick="callShop()">
                    <svg class="ic-lg" viewBox="0 0 24 24">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.61 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.59a16 16 0 0 0 6 6l.96-.96a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
                    </svg>
                    <span id="fab-call">Call</span>
                </button>
                <button class="fab wa-fab" onclick="openWA()">
                    <svg class="ic-lg" viewBox="0 0 24 24">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                    </svg>
                    <span id="fab-whatsapp">WhatsApp</span>
                </button>
                <button class="fab save-fab" onclick="saveContact()">
                    <svg class="ic-lg" viewBox="0 0 24 24">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                    <span id="fab-save">Save</span>
                </button>
                <div class="fab-wrap" onclick="openCart()">
                    <button class="fab cart-fab" style="padding:0;background:0 0;border:none;cursor:pointer;">
                        <svg class="ic-lg" viewBox="0 0 24 24">
                            <circle cx="9" cy="21" r="1" />
                            <circle cx="20" cy="21" r="1" />
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                        </svg>
                        <span id="fab-cart">Cart</span>
                    </button>
                    <span class="cart-badge" id="cartBadge"></span>
                </div>
            </div>

            <div class="cart-overlay" id="cartOverlay" onclick="closeCartOutside(event)">
                <div class="cart-box">
                    <div class="cart-header">
                        <div class="cart-title">
                            <svg class="ic" viewBox="0 0 24 24">
                                <circle cx="9" cy="21" r="1" />
                                <circle cx="20" cy="21" r="1" />
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                            </svg>
                            <span id="cart-title">Your Cart</span>
                        </div>
                        <button class="cart-close" onclick="closeCart()">
                            <svg viewBox="0 0 24 24" width="16" height="16">
                                <line x1="18" y1="6" x2="6" y2="18" stroke-width="2" />
                                <line x1="6" y1="6" x2="18" y2="18" stroke-width="2" />
                            </svg>
                        </button>
                    </div>
                    <div id="cartBody"></div>
                </div>
            </div>

            <div class="modal-overlay" id="shareModal" onclick="closeShare(event)">
                <div class="modal-box">
                    <div class="modal-title" id="share-title">Share</div>
                    <div class="share-options">
                        <div class="share-opt" onclick="shareWA()">
                            <svg viewBox="0 0 24 24" stroke="#128c7e" stroke-width="2">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                            </svg>
                            <span id="share-wa">WhatsApp</span>
                        </div>
                        <div class="share-opt" onclick="shareFB()">
                            <svg viewBox="0 0 24 24" stroke="#1877f2" stroke-width="2">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                            </svg>
                            <span id="share-fb">Facebook</span>
                        </div>
                        <div class="share-opt" onclick="copyLink()">
                            <svg viewBox="0 0 24 24" stroke="#1565c0" stroke-width="2">
                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                            </svg>
                            <span id="share-copy">Copy Link</span>
                        </div>
                        <div class="share-opt" onclick="shareNative()">
                            <svg viewBox="0 0 24 24" stroke="#6a1b9a" stroke-width="2">
                                <circle cx="18" cy="5" r="3" />
                                <circle cx="6" cy="12" r="3" />
                                <circle cx="18" cy="19" r="3" />
                                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                            </svg>
                            <span id="share-more">More</span>
                        </div>
                    </div>
                    <button class="modal-close" onclick="closeShareModal()">
                        <span id="share-cancel">Cancel</span>
                    </button>
                </div>
            </div>

            <div class="promo-overlay" id="promoOverlay" onclick="closePromo(event)">
                <div class="promo-box" onclick="event.stopPropagation()">
                    <button class="promo-close" onclick="closePromo()">
                        <svg viewBox="0 0 24 24" width="16" height="16">
                            <line x1="18" y1="6" x2="6" y2="18" stroke-width="2" />
                            <line x1="6" y1="6" x2="18" y2="18" stroke-width="2" />
                        </svg>
                    </button>
                    <div class="promo-icon">
                        <svg viewBox="0 0 24 24" width="26" height="26">
                            <path d="M20 12v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7" />
                            <polyline points="17 8 12 3 7 8" />
                            <line x1="12" y1="3" x2="12" y2="15" />
                        </svg>
                    </div>
                    <h3 id="promo-title">{{ data_get($data, "promo.title") }}</h3>
                    <p id="promo-text">{{ data_get($data, "promo.text") }}</p>
                    <button class="promo-cta" onclick="promoAction()">
                        <svg viewBox="0 0 24 24" width="18" height="18">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                        </svg>
                        <span id="promo-cta">{{ data_get($data, "promo.cta") }}</span>
                    </button>
                </div>
            </div>

            <div class="toast" id="toast">
                <svg viewBox="0 0 24 24" width="15" height="15">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                <span id="toastMsg">{{ data_get($data, "messages.defaultToast", "") }}</span>
            </div>

            <!-- Icon Templates (hidden) -->
            <div class="icon-templates" style="display:none;">
                <span id="pill_shield"><svg class="ic-sm" viewBox="0 0 24 24">{!! getIcon("pill_shield") !!}</svg></span>
                <span id="pill_truck"><svg class="ic-sm" viewBox="0 0 24 24">{!! getIcon("pill_truck") !!}</svg></span>
                <span id="pill_clock"><svg class="ic-sm" viewBox="0 0 24 24">{!! getIcon("pill_clock") !!}</svg></span>
                <span id="pill_price"><svg class="ic-sm" viewBox="0 0 24 24">{!! getIcon("pill_price") !!}</svg></span>
                <span id="pill_chat"><svg class="ic-sm" viewBox="0 0 24 24">{!! getIcon("pill_chat") !!}</svg></span>
                <span id="pill_refresh"><svg class="ic-sm" viewBox="0 0 24 24">{!! getIcon("pill_refresh") !!}</svg></span>
                <span id="cat_phone"><svg width="22" height="22" viewBox="0 0 24 24" stroke-width="1.8">{!! getIcon("cat_phone") !!}</svg></span>
                <span id="cat_laptop"><svg width="22" height="22" viewBox="0 0 24 24" stroke-width="1.8">{!! getIcon("cat_laptop") !!}</svg></span>
                <span id="cat_appliance"><svg width="22" height="22" viewBox="0 0 24 24" stroke-width="1.8">{!! getIcon("cat_appliance") !!}</svg></span>
                <span id="cat_tv"><svg width="22" height="22" viewBox="0 0 24 24" stroke-width="1.8">{!! getIcon("cat_tv") !!}</svg></span>
                <span id="cat_accessories"><svg width="22" height="22" viewBox="0 0 24 24" stroke-width="1.8">{!! getIcon("cat_accessories") !!}</svg></span>
                <span id="cat_gaming"><svg width="22" height="22" viewBox="0 0 24 24" stroke-width="1.8">{!! getIcon("cat_gaming") !!}</svg></span>
                <span id="repair_mobile"><svg width="20" height="20" viewBox="0 0 24 24" stroke-width="1.8">{!! getIcon("repair_mobile") !!}</svg></span>
                <span id="repair_laptop"><svg width="20" height="20" viewBox="0 0 24 24" stroke-width="1.8">{!! getIcon("repair_laptop") !!}</svg></span>
                <span id="repair_ac"><svg width="20" height="20" viewBox="0 0 24 24" stroke-width="1.8">{!! getIcon("repair_ac") !!}</svg></span>
                <span id="repair_battery"><svg width="20" height="20" viewBox="0 0 24 24" stroke-width="1.8">{!! getIcon("repair_battery") !!}</svg></span>
                <span id="pay_upi"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke-width="2">{!! getIcon("pay_upi") !!}</svg></span>
                <span id="pay_card"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke-width="2">{!! getIcon("pay_card") !!}</svg></span>
                <span id="pay_bank"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke-width="2">{!! getIcon("pay_bank") !!}</svg></span>
                <span id="pay_cash"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke-width="2">{!! getIcon("pay_cash") !!}</svg></span>
                <span id="ui_arrow_right"><svg viewBox="0 0 24 24">{!! getIcon("ui_arrow_right") !!}</svg></span>
                <span id="ui_check"><svg viewBox="0 0 24 24">{!! getIcon("ui_check") !!}</svg></span>
                <span id="ui_star"><svg viewBox="0 0 24 24">{!! getIcon("ui_star") !!}</svg></span>
                <span id="ui_cart"><svg viewBox="0 0 24 24">{!! getIcon("ui_cart") !!}</svg></span>
                <span id="minus"><svg viewBox="0 0 24 24">{!! getIcon("minus") !!}</svg></span>
                <span id="plus"><svg viewBox="0 0 24 24">{!! getIcon("plus") !!}</svg></span>
                <span id="social_whatsapp"><svg class="ic" viewBox="0 0 24 24" stroke-width="2">{!! getIcon("social_whatsapp") !!}</svg></span>
                <span id="social_facebook"><svg class="ic" viewBox="0 0 24 24" stroke-width="2">{!! getIcon("social_facebook") !!}</svg></span>
                <span id="social_instagram"><svg class="ic" viewBox="0 0 24 24" stroke-width="2">{!! getIcon("social_instagram") !!}</svg></span>
                <span id="social_youtube"><svg class="ic" viewBox="0 0 24 24" stroke-width="2">{!! getIcon("social_youtube") !!}</svg></span>
            </div>
        </main>
        <script>
            window.__APP__ = {!! vcard_js_str($data) !!};
            window.__VCARD_SUBDOMAIN__ = {!! json_encode($subdomain) !!};
        </script>
        <script src="{{ $assetBase }}script.js"></script>
    </body>
</html>