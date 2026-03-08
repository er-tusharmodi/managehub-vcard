@php
    require_once resource_path('views/vcards/icons/restaurant-cafe-template.php');
    $bannerImage = data_get($data, "assets.bannerImage", "");
    $storyImage = data_get($data, "story.image", data_get($data, "assets.fallbackImage", ""));
    $restaurantName = data_get($data, "R.name", "");
    $menu = data_get($data, "MENU", []);
    $menu = is_array($menu) ? $menu : [];
    $menuTabs = array_keys($menu);
    $activeTab = $menuTabs[0] ?? "";
    $activeItems = ($activeTab && isset($menu[$activeTab]) && is_array($menu[$activeTab])) ? $menu[$activeTab] : [];
    $socialIconClasses = [
        "instagram" => "ic-ig",
        "whatsapp" => "ic-wa",
        "youtube" => "ic-yt",
        "facebook" => "ic-fb",
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
        <main id="app-root" aria-live="polite">
            <div class="banner">
                <div class="banner-bg" id="bannerBg"@if($bannerImage) style="background-image:url('{{ $bannerImage }}');background-size:cover;background-position:center;background-repeat:no-repeat;"@endif></div>
                <div class="banner-pattern"></div>
                <div class="banner-overlay"></div>
                <div class="banner-top-bar">
                    <button class="share-btn" onclick="openShare()">
                        <svg class="ic-sm" viewBox="0 0 24 24" stroke="#fff" fill="none">
                            <circle cx="18" cy="5" r="3" />
                            <circle cx="6" cy="12" r="3" />
                            <circle cx="18" cy="19" r="3" />
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                        </svg>
                        <span id="bannerShareLabel">Share</span>
                    </button>
                    <button class="save-btn-top" onclick="saveContact()">
                        <svg class="ic-sm" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span id="bannerSaveLabel">Save Contact</span>
                    </button>
                </div>
                <div class="banner-center">
                    <div class="banner-eyebrow" id="bannerEyebrow">{{ data_get($data, "banner.eyebrow") }}</div>
                    <div class="banner-title" id="bannerTitle">{{ data_get($data, "_common.name") }}</div>
                    <div class="banner-sub" id="bannerSub">{{ data_get($data, "_common.tagline") }}</div>
                </div>
                <div class="rating-strip" id="ratingStrip">
                    @foreach(data_get($data, "banner.ratings", []) as $item)
                        @php $iconKey = "rating_" . ($item["icon"] ?? ""); @endphp
                        <div class="r-stat">
                            {!! getIcon($iconKey) !!}
                            {{ $item["label"] ?? "" }}
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="profile-card">
                <div class="cuisine-tags" id="cuisineTags">
                    @foreach(data_get($data, "profile.cuisineTags", []) as $tag)
                        <span class="ctag">{{ $tag }}</span>
                    @endforeach
                </div>
                <div class="profile-action-btns">
                    <button class="pab call" onclick="callUs()">
                        <svg class="ic-sm" viewBox="0 0 24 24" stroke="#2e7d32" fill="none">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.13 6.13l.96-.96a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.73 16.92z" />
                        </svg>
                        <span id="actionCallLabel">Call Us</span>
                    </button>
                    <button class="pab wa" onclick="openWA()">
                        <svg class="ic-sm" viewBox="0 0 24 24" stroke="#1b5e20" fill="none">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                        </svg>
                        <span id="actionWaLabel">WhatsApp</span>
                    </button>
                    <button class="pab res" onclick="openReserveModal()">
                        <svg class="ic-sm" viewBox="0 0 24 24" stroke="#e65100" fill="none">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        <span id="actionReserveLabel">Reserve</span>
                    </button>
                    <button class="pab email" onclick="emailUs()">
                        <svg class="ic-sm" viewBox="0 0 24 24" stroke="#1565c0" fill="none">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                        <span id="actionEmailLabel">Email</span>
                    </button>
                    <button class="pab dir" onclick="openMaps()">
                        <svg class="ic-sm" viewBox="0 0 24 24" stroke="#880e4f" fill="none">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                        <span id="actionDirectionLabel">Directions</span>
                    </button>
                    <button class="pab share" onclick="openShare()">
                        <svg class="ic-sm" viewBox="0 0 24 24" stroke="#6a1b9a" fill="none">
                            <circle cx="18" cy="5" r="3" />
                            <circle cx="6" cy="12" r="3" />
                            <circle cx="18" cy="19" r="3" />
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                        </svg>
                        <span id="actionShareLabel">Share</span>
                    </button>
                </div>
            </div>

        @if(vcard_section_enabled($data, 'story'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg class="ic" viewBox="0 0 24 24">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                        </svg>
                    </div>
                    <span class="sec-title" id="secStoryTitle">Our Story</span>
                </div>
                <div class="sec-body">
                    <div class="story-wrap">
                        <div class="story-avatar">
                            <img id="storyImage" src="{{ $storyImage }}" alt="{{ $restaurantName }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px" />
                        </div>
                        <div class="story-text">
                            <p id="storyP1">{{ data_get($data, "story.paragraph1") }}</p>
                            <p id="storyP2">{{ data_get($data, "story.paragraph2") }}</p>
                            <div class="chef-sig">
                                <div class="chef-name" id="chefName">{{ data_get($data, "story.chefName") }}</div>
                                <div class="chef-role" id="chefRole">{{ data_get($data, "story.chefRole") }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="hl-row" id="highlightsRow">
                        @foreach(data_get($data, "story.highlights", []) as $item)
                            @php $iconKey = "highlight_" . ($item["icon"] ?? ""); @endphp
                            <div class="hl-box">
                                <div class="hl-em">{!! getIcon($iconKey) !!}</div>
                                <div class="hl-lbl">{{ $item["label"] ?? "" }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if(vcard_section_enabled($data, 'menu'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon no-bg-menu">
                        <svg class="ic" viewBox="0 0 24 24">
                            <path d="M18 8h1a4 4 0 0 1 0 8h-1" />
                            <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z" />
                            <line x1="6" y1="1" x2="6" y2="4" />
                            <line x1="10" y1="1" x2="10" y2="4" />
                            <line x1="14" y1="1" x2="14" y2="4" />
                        </svg>
                    </div>
                    <span class="sec-title" id="secMenuTitle">Our Menu</span>
                </div>
                <div class="sec-body">
                    <div class="menu-tabs" id="menuTabs">
                        @foreach($menuTabs as $tab)
                            <button class="mtab{{ $tab === $activeTab ? " active" : "" }}" onclick="switchTab({!! vcard_js_str($tab) !!})">{{ $tab }}</button>
                        @endforeach
                    </div>
                    <div class="menu-grid" id="menuGrid">
                        @foreach($activeItems as $item)
                            @php
                            $price = (int) ($item["price"] ?? 0);
                            $id = (int) ($item["id"] ?? 0);
                            $tagColor = $item["tc"] ?? "#3a4a2e";
                            $imgSrc = $item["product_image"] ?? $item["bg"] ?? "";
                            if (!$imgSrc) {
                                $imgSrc = data_get($data, "assets.fallbackImage", "");
                            }
                            $veg = !empty($item["veg"]);
@endphp
                            <div class="menu-card">
                                <div class="menu-img">
                                    <div class="menu-img-ph" style="background-image:url('{{ e($imgSrc) }}');background-size:cover;background-position:center;background-repeat:no-repeat;"></div>
                                    @if(!empty($item["tag"]))
                                        <span class="mbadge" style="background:{{ $tagColor }}">{{ $item["tag"] }}</span>
                                    @endif
                                    <div class="diet {{ $veg ? "veg-d" : "nonveg-d" }}">{{ $veg ? "V" : "N" }}</div>
                                </div>
                                <div class="menu-body">
                                    <div class="menu-name">{{ $item["name"] ?? "" }}</div>
                                    <div class="menu-desc">{{ $item["desc"] ?? "" }}</div>
                                    <div class="menu-footer">
                                        <div>
                                            <span class="mprice">&#8377;{{ $price }}</span>
                                            @if(!empty($item["op"]))
                                                <span class="mold">&#8377;{{ $item["op"] }}</span>
                                            @endif
                                        </div>
                                        <div class="qty-ctrl">
                                            <button class="qty-btn" onclick="chQty({{ $id }},-1,{{ $price }},{!! vcard_js_str($item["name"] ?? "") !!})"><svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12" /></svg></button>
                                            <span class="qty-num" id="qty-{{ $id }}">0</span>
                                            <button class="qty-btn" onclick="chQty({{ $id }},1,{{ $price }},{!! vcard_js_str($item["name"] ?? "") !!})"><svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if(vcard_section_enabled($data, 'gallery'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon gold-ic">
                        <svg class="ic" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <polyline points="21 15 16 10 5 21" />
                        </svg>
                    </div>
                    <span class="sec-title" id="secGalleryTitle">Gallery</span>
                </div>
                <div class="sec-body" style="padding-top: 0.5rem; padding-bottom: 0.5rem">
                    <div class="gallery-row" id="galleryRow">
                        @foreach(data_get($data, "gallery", []) as $item)
                            @php $image = $item["image"] ?? data_get($data, "assets.fallbackImage", ""); @endphp
                            <div>
                                <div class="gal-item">
                                    <img src="{{ $image }}" alt="" style="width:100%;height:100%;object-fit:cover;display:block;" />
                                </div>
                                <div class="gal-cap">{{ $item["caption"] ?? "" }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if(vcard_section_enabled($data, 'reserve'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon terra">
                        <svg class="ic" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                    </div>
                    <span class="sec-title" id="secReserveTitle">Reserve a Table</span>
                </div>
                <div class="sec-body">
                    <div id="reservationForm">
                        <div class="bf-row">
                            <div class="bf-grp">
                                <label class="bf-lbl" id="rLabelName">{{ data_get($data, "reservation.labels.name") }}</label>
                                <input class="bf-inp" type="text" id="rName" placeholder="{{ data_get($data, "reservation.placeholders.name") }}" />
                            </div>
                            <div class="bf-grp">
                                <label class="bf-lbl" id="rLabelPhone">{{ data_get($data, "reservation.labels.phone") }}</label>
                                <input class="bf-inp" type="tel" id="rPhone" placeholder="{{ data_get($data, "reservation.placeholders.phone") }}" />
                            </div>
                        </div>
                        <div class="bf-row">
                            <div class="bf-grp">
                                <label class="bf-lbl" id="rLabelDate">{{ data_get($data, "reservation.labels.date") }}</label>
                                <input class="bf-inp" type="date" id="rDate" />
                            </div>
                            <div class="bf-grp">
                                <label class="bf-lbl" id="rLabelTime">{{ data_get($data, "reservation.labels.time") }}</label>
                                <select class="bf-inp" id="rTime">
                                    @foreach(data_get($data, "reservation.times", []) as $option)
                                        <option value="{{ $option["value"] ?? $option["label"] ?? "" }}"{{ !empty($option["selected"]) ? " selected=\"selected\"" : "" }}>{{ $option["label"] ?? $option["value"] ?? "" }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="bf-row">
                            <div class="bf-grp">
                                <label class="bf-lbl" id="rLabelGuests">{{ data_get($data, "reservation.labels.guests") }}</label>
                                <select class="bf-inp" id="rGuests">
                                    @foreach(data_get($data, "reservation.guests", []) as $option)
                                        <option value="{{ $option["value"] ?? $option["label"] ?? "" }}"{{ !empty($option["selected"]) ? " selected=\"selected\"" : "" }}>{{ $option["label"] ?? $option["value"] ?? "" }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="bf-grp">
                                <label class="bf-lbl" id="rLabelOccasion">{{ data_get($data, "reservation.labels.occasion") }}</label>
                                <select class="bf-inp" id="rOccasion">
                                    @foreach(data_get($data, "reservation.occasions", []) as $option)
                                        <option value="{{ $option["value"] ?? $option["label"] ?? "" }}"{{ !empty($option["selected"]) ? " selected=\"selected\"" : "" }}>{{ $option["label"] ?? $option["value"] ?? "" }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="bf-grp">
                            <label class="bf-lbl" id="rLabelNote">{{ data_get($data, "reservation.labels.note") }}</label>
                            <textarea class="bf-inp" id="rNote" placeholder="{{ data_get($data, "reservation.placeholders.note") }}"></textarea>
                        </div>
                        <button class="bf-btn" onclick="submitReservation()">
                            <svg class="ic-sm" viewBox="0 0 24 24">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.5 0 0 1 8 8v.5z" />
                            </svg>
                            <span id="rConfirmLabel">Confirm via WhatsApp</span>
                        </button>
                    </div>
                    <div class="res-done" id="reservationSuccess">
                        <div class="res-done-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" width="24" height="24">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <div class="res-done-title" id="rSuccessTitle">{{ data_get($data, "reservation.successTitle") }}</div>
                        <div class="res-done-msg" id="rSuccessMsg">{{ data_get($data, "reservation.successMessage") }}</div>
                        <button class="bf-btn" style="margin-top: 1rem" onclick="resetReservation()">
                            <span id="rSuccessBtnLabel">Make Another Reservation</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if(vcard_section_enabled($data, 'offers'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon gold-ic">
                        <svg class="ic" viewBox="0 0 24 24">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                        </svg>
                    </div>
                    <span class="sec-title" id="secOffersTitle">Experiences &amp; Offers</span>
                </div>
                <div class="sec-body">
                    <div class="offers-list" id="offersList">
                        @foreach(data_get($data, "offers", []) as $item)
                            @php
                                $iconVal  = $item["icon"] ?? "";
                                $svgKeys  = ['brunch','candle','coffee','cake'];
                                $iconHtml = in_array($iconVal, $svgKeys, true)
                                    ? getIcon('offer_' . $iconVal)
                                    : ($iconVal ? '<span style="font-size:1.4rem;line-height:1;">' . e($iconVal) . '</span>' : '');
                            @endphp
                            <div class="offer-card">
                                <div class="offer-icon" style="background:{{ $item['bg'] ?? '#fff3e0' }}">{!! $iconHtml !!}</div>
                                <div>
                                    <div class="offer-title">{{ $item["title"] ?? "" }}</div>
                                    <div class="offer-desc">{{ $item["desc"] ?? "" }}</div>
                                    <span class="offer-tag">{{ $item["tag"] ?? "" }}</span>
                                </div>
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
                        <svg class="ic" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    <span class="sec-title" id="secHoursTitle">Opening Hours</span>
                </div>
                <div class="sec-body">
                    <div class="today-pill">
                        <svg viewBox="0 0 24 24" width="13" height="13" stroke="#2e7d32" fill="none" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        <span id="todayPillLabel">{{ data_get($data, "hours.todayLabel") }}</span>
                    </div>
                    <table class="hours-table">
                        <tbody id="hoursRows">
                            @foreach(data_get($data, "hours.rows", []) as $idx => $row)
                                <tr class="{{ $idx === 0 ? "h-today" : "" }}">
                                    <td class="h-day">{{ $row["day"] ?? "" }}</td>
                                    <td class="h-time">{{ $row["time"] ?? "" }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="kitchen-note" id="kitchenNote">{{ data_get($data, "hours.kitchenNote") }}</div>
                </div>
            </div>
        @endif

        @if(vcard_section_enabled($data, 'location'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon terra">
                        <svg class="ic" viewBox="0 0 24 24">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                    </div>
                    <span class="sec-title" id="secLocationTitle">Location &amp; How to Reach</span>
                </div>
                <div class="sec-body">
                    <a class="addr-wrap" onclick="openMaps()">
                        <div class="addr-icon">
                            <svg class="ic" viewBox="0 0 24 24">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                        </div>
                        <div class="addr-text">
                            <strong id="locationName">{{ data_get($data, "location.name") }}</strong>
                            <span id="locationAddress">{{ data_get($data, "location.address") }}</span>
                            <div>
                                <a href="#" class="map-btn" onclick="return (openMaps(), !1);">
                                    <svg class="ic-sm" viewBox="0 0 24 24" fill="none">
                                        <polygon points="3 11 22 2 13 21 11 13 3 11" />
                                    </svg>
                                    <span id="mapBtnLabel">Get Directions</span>
                                </a>
                            </div>
                        </div>
                    </a>
                    <div class="transport-grid" id="transportGrid">
                        @foreach(data_get($data, "location.transport", []) as $item)
                            @php $iconKey = "transport_" . ($item["icon"] ?? ""); @endphp
                            <div class="t-item">
                                <span style="display:flex;color:{{ $item["stroke"] ?? "#1565c0" }}">{!! getIcon($iconKey) !!}</span>
                                <div>
                                    <div class="t-label">{{ $item["label"] ?? "" }}</div>
                                    <div class="t-val">{{ $item["value"] ?? "" }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if(vcard_section_enabled($data, 'follow'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg class="ic" viewBox="0 0 24 24">
                            <path d="M17 2H7a5 5 0 0 0-5 5v10a5 5 0 0 0 5 5h10a5 5 0 0 0 5-5V7a5 5 0 0 0-5-5z" />
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
                        </svg>
                    </div>
                    <span class="sec-title" id="secFollowTitle">Follow Us</span>
                </div>
                <div class="sec-body">
                    <div class="social-list" id="socialList">
                        @foreach(data_get($data, "social", []) as $item)
                            @php
                            $type = $item["type"] ?? "instagram";
                            $iconKey = "social_" . $type;
                            $iconClass = $socialIconClasses[$type] ?? $socialIconClasses["instagram"];
                            $action = "";
                            if (($item["action"] ?? "") === "wa") {
                                $action = "openWA()";
                            } elseif (($item["action"] ?? "") === "url" && !empty($item["url"])) {
                                $action = "window.open(" . js_str($item["url"]) . ", '_blank')";
                            }
@endphp
                            <div class="soc-item"{{ $action ? " onclick=\"" . e($action) . "\"" : "" }}>
                                <div class="s-ico {{ $iconClass }}">{!! getIcon($iconKey) !!}</div>
                                <div>
                                    <div class="s-name">{{ $item["name"] ?? "" }}</div>
                                    <div class="s-val">{{ $item["value"] ?? "" }}</div>
                                </div>
                                <div class="s-arrow">
                                    <svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2">
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
                        <svg class="ic" viewBox="0 0 24 24">
                            <rect x="1" y="4" width="22" height="16" rx="2" />
                            <line x1="1" y1="10" x2="23" y2="10" />
                        </svg>
                    </div>
                    <span class="sec-title" id="secPaymentTitle">Payment Accepted</span>
                </div>
                <div class="sec-body">
                    <div class="pay-grid" id="payGrid">
                        @foreach(data_get($data, "payments", []) as $item)
                            @php
                            $iconName = $item["icon"] ?? "";
                            if ($iconName === "") {
                                $label = strtolower((string) ($item["name"] ?? ""));
                                if (str_contains($label, "upi") || str_contains($label, "qr")) {
                                    $iconName = "upi";
                                } elseif (str_contains($label, "wallet")) {
                                    $iconName = "wallet";
                                } elseif (str_contains($label, "card") || str_contains($label, "bank")) {
                                    $iconName = "card";
                                } elseif (str_contains($label, "cash")) {
                                    $iconName = "cash";
                                }
                            }
                            if ($iconName === "") {
                                $iconName = "cash";
                            }
                            $iconKey = "payment_" . $iconName;
@endphp
                            @php
                                $payClr = $item['stroke'] ?? '#1565c0';
                                $prgb = \Illuminate\Support\Str::startsWith($payClr, '#') && strlen($payClr) === 7
                                    ? 'rgba(' . hexdec(substr($payClr,1,2)) . ',' . hexdec(substr($payClr,3,2)) . ',' . hexdec(substr($payClr,5,2)) . ',0.12)'
                                    : 'rgba(21,101,192,0.12)';
                            @endphp
                            <div class="pay-item" style="--pay-clr:{{ $payClr }}">
                                <div class="pay-icon" style="background:{{ $prgb }};color:{{ $payClr }}">
                                    {!! getIcon($iconKey) !!}
                                </div>
                                <div>
                                    <div class="pay-name">{{ $item["name"] ?? "" }}</div>
                                    <div class="pay-sub">{{ $item["sub"] ?? "" }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if(vcard_section_enabled($data, 'qr'))
            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon gold-ic">
                        <svg class="ic" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7" />
                            <rect x="14" y="3" width="7" height="7" />
                            <rect x="3" y="14" width="7" height="7" />
                            <path d="M14 14h3v3h-3zM17 17h3v3h-3zM14 20h3v1h-3z" />
                        </svg>
                    </div>
                    <span class="sec-title" id="secQrTitle">QR Code &amp; Save Contact</span>
                </div>
                <div class="sec-body">
                    <div class="qr-inner">
                        <div id="qrHelpText" style="font-size: 0.8rem; color: var(--muted); margin-bottom: 0.5rem">{{ data_get($data, "qr.helpText") }}</div>
                        <div id="vcardQR"></div>
                        <div class="qr-actions">
                            <button class="qr-btn" onclick="downloadQR()">
                                <svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="7 10 12 15 17 10" />
                                    <line x1="12" y1="15" x2="12" y2="3" />
                                </svg>
                                <span id="qrDownloadLabel">Download QR</span>
                            </button>
                            <button class="qr-btn" onclick="saveContact()">
                                <svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                                    <polyline points="17 21 17 13 7 13 7 21" />
                                    <polyline points="7 3 7 8 15 8" />
                                </svg>
                                <span id="qrSaveLabel">Save Contact</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

            <div class="vcard-footer">
                <p id="footerLine1">{{ data_get($data, "footer.year") }} <strong>{{ data_get($data, "footer.brand") }}</strong> {{ data_get($data, "footer.rights") }}</p>
                <p id="footerLine2" style="margin-top: 0.3rem; font-size: 0.68rem">Powered by <a href="{{ config('app.url') }}" target="_blank" rel="noopener" style="text-decoration:none;font-weight:600;">{{ config('app.name') }}</a></p>
            </div>

            <div class="float-bar">
                <button class="fab cfab" onclick="callUs()">
                    <svg class="ic-lg" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.13 6.13l.96-.96a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.73 16.92z" />
                    </svg>
                    <span id="floatCallLabel">Call</span>
                </button>
                <button class="fab rfab" onclick="openReserveModal()">
                    <svg class="ic-lg" viewBox="0 0 24 24" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    <span id="floatReserveLabel">Reserve</span>
                </button>
                <button class="fab wfab" onclick="openWA()">
                    <svg class="ic-lg" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                    </svg>
                    <span id="floatWaLabel">WhatsApp</span>
                </button>
                <div class="fab-wrap" onclick="openCart()">
                    <div class="cart-badge" id="cartBadge"></div>
                    <svg class="ic-lg" viewBox="0 0 24 24" stroke="var(--terracotta)" stroke-width="2" fill="none">
                        <circle cx="9" cy="21" r="1" />
                        <circle cx="20" cy="21" r="1" />
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                    </svg>
                    <span id="floatOrderLabel" style="font-size: 0.66rem; font-weight: 700; color: var(--terracotta)">Order</span>
                </div>
            </div>

            <div class="cart-overlay" id="cartOverlay" onclick="closeCartOutside(event)">
                <div class="cart-box">
                    <div class="cart-header">
                        <div class="cart-title">
                            <svg class="ic" viewBox="0 0 24 24" stroke="var(--olive)" stroke-width="2" fill="none">
                                <circle cx="9" cy="21" r="1" />
                                <circle cx="20" cy="21" r="1" />
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                            </svg>
                            <span id="cartTitle">Your Order</span>
                        </div>
                        <button class="cart-close" onclick="closeCart()">
                            <svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2.5">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </button>
                    </div>
                    <div id="cartBody"></div>
                </div>
            </div>

            <div class="res-overlay" id="reserveOverlay" onclick="closeResOutside(event)">
                <div class="res-box">
                    <div class="res-header">
                        <div class="res-header-title" id="reserveModalTitle">Reserve a Table</div>
                        <button class="cart-close" onclick="closeReserveModal()">
                            <svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2.5">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </button>
                    </div>
                    <div class="bf-row">
                        <div class="bf-grp">
                            <label class="bf-lbl" id="r2LabelName">{{ data_get($data, "reservation.labels.name", data_get($data, "reserveModal.labels.name")) }}</label>
                            <input class="bf-inp" type="text" id="rName2" placeholder="{{ data_get($data, 'reservation.placeholders.name', data_get($data, 'reserveModal.placeholders.name')) }}" />
                        </div>
                        <div class="bf-grp">
                            <label class="bf-lbl" id="r2LabelPhone">{{ data_get($data, "reservation.labels.phone", data_get($data, "reserveModal.labels.phone")) }}</label>
                            <input class="bf-inp" type="tel" id="rPhone2" placeholder="{{ data_get($data, 'reservation.placeholders.phone', data_get($data, 'reserveModal.placeholders.phone')) }}" />
                        </div>
                    </div>
                    <div class="bf-row">
                        <div class="bf-grp">
                            <label class="bf-lbl" id="r2LabelDate">{{ data_get($data, "reservation.labels.date", data_get($data, "reserveModal.labels.date")) }}</label>
                            <input class="bf-inp" type="date" id="rDate2" />
                        </div>
                        <div class="bf-grp">
                            <label class="bf-lbl" id="r2LabelTime">{{ data_get($data, "reservation.labels.time", data_get($data, "reserveModal.labels.time")) }}</label>
                            <select class="bf-inp" id="rTime2">
                                @foreach(data_get($data, "reservation.times", data_get($data, "reserveModal.times", [])) as $option)
                                    <option value="{{ $option["value"] ?? $option["label"] ?? "" }}"{{ !empty($option["selected"]) ? " selected=\"selected\"" : "" }}>{{ $option["label"] ?? $option["value"] ?? "" }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="bf-grp">
                        <label class="bf-lbl" id="r2LabelGuests">{{ data_get($data, "reservation.labels.guests", data_get($data, "reserveModal.labels.guests")) }}</label>
                        <select class="bf-inp" id="rGuests2">
                            @foreach(data_get($data, "reservation.guests", data_get($data, "reserveModal.guests", [])) as $option)
                                <option value="{{ $option["value"] ?? $option["label"] ?? "" }}"{{ !empty($option["selected"]) ? " selected=\"selected\"" : "" }}>{{ $option["label"] ?? $option["value"] ?? "" }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="bf-btn" onclick="submitReservationModal()">
                        <svg class="ic-sm" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        <span id="r2ConfirmLabel">Confirm via WhatsApp</span>
                    </button>
                </div>
            </div>

            <div class="modal-overlay" id="shareModal" onclick="closeShare(event)">
                <div class="modal-box">
                    <div class="modal-title" id="shareTitle">Share</div>
                    <div class="share-options">
                        <div class="share-opt" onclick="shareWA()">
                            <svg viewBox="0 0 24 24" stroke="#128c7e" stroke-width="2">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                            </svg>
                            <span id="shareWaLabel">WhatsApp</span>
                        </div>
                        <div class="share-opt" onclick="copyLink()">
                            <svg viewBox="0 0 24 24" stroke="#555" stroke-width="2">
                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                            </svg>
                            <span id="shareCopyLabel">Copy Link</span>
                        </div>
                        <div class="share-opt" onclick="shareFB()">
                            <svg viewBox="0 0 24 24" stroke="#1877f2" stroke-width="2">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                            </svg>
                            <span id="shareFbLabel">Facebook</span>
                        </div>
                        <div class="share-opt" onclick="shareNative()">
                            <svg viewBox="0 0 24 24" stroke="#555" stroke-width="2">
                                <circle cx="18" cy="5" r="3" />
                                <circle cx="6" cy="12" r="3" />
                                <circle cx="18" cy="19" r="3" />
                                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                            </svg>
                            <span id="shareMoreLabel">More</span>
                        </div>
                    </div>
                    <button class="modal-close-btn" onclick="closeShareModal()">
                        <span id="shareCancelLabel">Cancel</span>
                    </button>
                </div>
            </div>

            <div class="toast" id="toast"></div>

            <!-- Icon Templates (hidden) -->
            <div class="icon-templates" style="display:none;">
                <span id="rating_star">{!! getIcon("rating_star") !!}</span>
                <span id="rating_users">{!! getIcon("rating_users") !!}</span>
                <span id="rating_clock">{!! getIcon("rating_clock") !!}</span>
                <span id="highlight_oven">{!! getIcon("highlight_oven") !!}</span>
                <span id="highlight_fresh">{!! getIcon("highlight_fresh") !!}</span>
                <span id="highlight_wine">{!! getIcon("highlight_wine") !!}</span>
                <span id="offer_brunch">{!! getIcon("offer_brunch") !!}</span>
                <span id="offer_candle">{!! getIcon("offer_candle") !!}</span>
                <span id="offer_coffee">{!! getIcon("offer_coffee") !!}</span>
                <span id="offer_cake">{!! getIcon("offer_cake") !!}</span>
                <span id="transport_metro">{!! getIcon("transport_metro") !!}</span>
                <span id="transport_parking">{!! getIcon("transport_parking") !!}</span>
                <span id="transport_taxi">{!! getIcon("transport_taxi") !!}</span>
                <span id="transport_delivery">{!! getIcon("transport_delivery") !!}</span>
                <span id="social_instagram"><svg class="ic" viewBox="0 0 24 24" stroke-width="1.8">{!! getIcon("social_instagram") !!}</svg></span>
                <span id="social_whatsapp"><svg class="ic" viewBox="0 0 24 24" stroke-width="1.8">{!! getIcon("social_whatsapp") !!}</svg></span>
                <span id="social_youtube"><svg class="ic" viewBox="0 0 24 24" stroke-width="1.8">{!! getIcon("social_youtube") !!}</svg></span>
                <span id="social_facebook"><svg class="ic" viewBox="0 0 24 24" stroke-width="1.8">{!! getIcon("social_facebook") !!}</svg></span>
                <span id="payment_card">{!! getIcon("payment_card") !!}</span>
                <span id="payment_upi">{!! getIcon("payment_upi") !!}</span>
                <span id="payment_wallet">{!! getIcon("payment_wallet") !!}</span>
                <span id="payment_cash">{!! getIcon("payment_cash") !!}</span>
                <span id="source_google">{!! getIcon("source_google") !!}</span>
                <span id="source_zomato">{!! getIcon("source_zomato") !!}</span>
                <span id="ui_arrow_right"><svg viewBox="0 0 24 24">{!! getIcon("ui_arrow_right") !!}</svg></span>
                <span id="ui_check"><svg viewBox="0 0 24 24">{!! getIcon("ui_check") !!}</svg></span>
                <span id="ui_star"><svg viewBox="0 0 24 24">{!! getIcon("ui_star") !!}</svg></span>
                <span id="ui_cart"><svg viewBox="0 0 24 24">{!! getIcon("ui_cart") !!}</svg></span>
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