@php
    require_once resource_path('views/vcards/icons/mens-salon-template.php');
    $bannerImage = data_get($data, "assets.bannerImage", "");
    $profileImage = data_get($data, "assets.profileImage", data_get($data, "assets.fallbackImage", ""));
    $profileAlt = data_get($data, "shop.name", "");
    $slots = data_get($data, "booking.slots", []);
    $slots = is_array($slots) ? $slots : [];
    $selectedSlot = "";
    foreach ($slots as $slotItem) {
        if (!empty($slotItem["selected"]) && empty($slotItem["full"])) {
            $selectedSlot = $slotItem["slot"] ?? "";
            break;
        }
    }
    if (!$selectedSlot) {
        $selectedSlot = (string) data_get($data, "booking.defaultSlot", "");
    }
    if (!$selectedSlot) {
        foreach ($slots as $slotItem) {
            if (empty($slotItem["full"])) {
                $selectedSlot = $slotItem["slot"] ?? "";
                break;
            }
        }
    }
    $socialIconClasses = [
        "whatsapp" => "ic-wa",
        "instagram" => "ic-ig",
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
    </head>
    <body>
        <main id="app-root" aria-live="polite" style="min-height:100vh">
            <div class="banner">
                <div class="banner-bg"{{ $bannerImage ? " style=\"background:url('" . e($bannerImage) . "') center/cover no-repeat\"" : "" }}>
                    <div class="banner-lines">
                        <div class="bl bl1"></div>
                        <div class="bl bl2"></div>
                        <div class="bl bl3"></div>
                        <div class="bl bl4"></div>
                        <div class="bl bl5"></div>
                    </div>
                    <div class="banner-pole">
                        <div class="pole-stripe" style="background:#dc2626"></div>
                        <div class="pole-stripe" style="background:#fff"></div>
                        <div class="pole-stripe" style="background:#dc2626"></div>
                        <div class="pole-stripe" style="background:#fff"></div>
                        <div class="pole-stripe" style="background:#dc2626"></div>
                        <div class="pole-stripe" style="background:#fff"></div>
                        <div class="pole-stripe" style="background:#1d4ed8"></div>
                        <div class="pole-stripe" style="background:#fff"></div>
                        <div class="pole-stripe" style="background:#1d4ed8"></div>
                        <div class="pole-stripe" style="background:#fff"></div>
                        <div class="pole-stripe" style="background:#dc2626"></div>
                    </div>
                    <div class="banner-center">
                        <div class="banner-icon-ring">
                            <svg viewBox="0 0 24 24">
                                <path d="M6 2v6M6 22v-6M6 8c2 0 4 2 4 4s-2 4-4 4M18 2v6M18 22v-6M18 8c-2 0-4 2-4 4s2 4 4 4" />
                            </svg>
                        </div>
                        <div class="banner-title" id="banner-title">{{ data_get($data, "shop.name") }}</div>
                        <div class="banner-sub" id="banner-subtitle">{{ data_get($data, "shop.subtitle") }}</div>
                    </div>
                </div>
                <div class="banner-top-bar">
                    <button class="share-btn" onclick="openShare()">
                        <svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2">
                            <circle cx="18" cy="5" r="3" />
                            <circle cx="6" cy="12" r="3" />
                            <circle cx="18" cy="19" r="3" />
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                        </svg>
                        <span id="banner-share-label">Share</span>
                    </button>
                    <button class="save-btn-top" onclick="saveContact()">
                        <svg class="ic-sm" viewBox="0 0 24 24" fill="none" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span id="banner-save-label">Save Contact</span>
                    </button>
                </div>
            </div>

            <div class="status-bar">
                <div class="status-open">
                    <div class="dot-pulse"></div>
                    <span id="status-open-label">{{ data_get($data, "status.openLabel") }}</span>
                </div>
                <div class="next-slot">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                    <span id="status-next-slot">{{ data_get($data, "status.nextSlotLabel") }}</span>
                </div>
            </div>

            <div class="profile-card">
                <div class="profile-avatar-wrap">
                    <div class="profile-avatar">
                        <img id="profile-image" src="{{ $profileImage }}" alt="{{ $profileAlt }}" style="width:100%;height:100%;object-fit:cover" />
                    </div>
                    <span class="owner-tag" id="profile-owner-tag">{{ data_get($data, "profile.ownerTag") }}</span>
                </div>
                <div class="profile-name" id="profile-name">{{ data_get($data, "profile.name") }}</div>
                <div class="profile-role" id="profile-role">{{ data_get($data, "profile.role") }}</div>
                <div class="profile-tagline" id="profile-tagline">{{ data_get($data, "profile.tagline") }}</div>
                <div class="profile-action-btns">
                    <button class="pab call" onclick="callShop()">
                        <svg viewBox="0 0 24 24">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.13 6.13l.96-.96a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.73 16.92z" />
                        </svg>
                        <span id="action-call-label">Call</span>
                    </button>
                    <button class="pab whatsapp" onclick="openWA()">
                        <svg viewBox="0 0 24 24">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                        </svg>
                        <span id="action-whatsapp-label">WhatsApp</span>
                    </button>
                    <button class="pab book" onclick="scrollToBooking()">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        <span id="action-book-label">Book Now</span>
                    </button>
                    <button class="pab email" onclick="emailShop()">
                        <svg viewBox="0 0 24 24">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                        <span id="action-email-label">Email</span>
                    </button>
                    <button class="pab direction" onclick="openMaps()">
                        <svg viewBox="0 0 24 24">
                            <polygon points="3 11 22 2 13 21 11 13 3 11" />
                        </svg>
                        <span id="action-direction-label">Directions</span>
                    </button>
                    <button class="pab share" onclick="openShare()">
                        <svg viewBox="0 0 24 24">
                            <circle cx="18" cy="5" r="3" />
                            <circle cx="6" cy="12" r="3" />
                            <circle cx="18" cy="19" r="3" />
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                        </svg>
                        <span id="action-share-label">Share</span>
                    </button>
                </div>
            </div>

            <div class="sec" style="margin-top:0.55rem">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M6 2v6M6 22v-6M6 8c2 0 4 2 4 4s-2 4-4 4M18 2v6M18 22v-6M18 8c-2 0-4 2-4 4s2 4 4 4" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-services">Our Services</div>
                    <div class="sec-sub" id="sec-sub-services">{{ data_get($data, "sections.services.sub") }}</div>
                </div>
                <div class="sec-body">
                    <div class="services-grid" id="servicesGrid">
                        @foreach(data_get($data, "services", []) as $item)
                            @php $iconKey = "service_" . ($item["icon"] ?? ""); @endphp
                            <div class="svc-card">
                                <div class="svc-thumb" style="background:{{ $item["bg"] ?? "" }}">
                                    <div class="svc-thumb-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" width="22" height="22">{!! getIcon($iconKey) !!}</svg>
                                    </div>
                                    <div class="svc-price-tag">{{ $item["price"] ?? "" }}</div>
                                </div>
                                <div class="svc-body">
                                    <div class="svc-name">{{ $item["name"] ?? "" }}</div>
                                    <div class="svc-desc">{{ $item["desc"] ?? "" }}</div>
                                    <div class="svc-footer">
                                        <div class="svc-dur"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>{{ $item["dur"] ?? "" }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5z" />
                            <path d="M2 17l10 5 10-5" />
                            <path d="M2 12l10 5 10-5" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-packages">Grooming Packages</div>
                    <div class="sec-sub" id="sec-sub-packages">{{ data_get($data, "sections.packages.sub") }}</div>
                </div>
                <div class="sec-body">
                    <div class="pkg-list" id="pkgList">
                        @foreach(data_get($data, "packages", []) as $item)
                            @php
                            $badgeClass = $item["badgeClass"] ?? "";
                            $pkgBg = "linear-gradient(135deg,#e0f2fe,#f0f9ff)";
                            if ($badgeClass === "hot") {
                                $pkgBg = "linear-gradient(135deg,#fdf3d0,#fefce8)";
                            } elseif ($badgeClass === "value") {
                                $pkgBg = "linear-gradient(135deg,#f0fdf4,#dcfce7)";
                            }
@endphp
                            <div class="pkg-card{{ $badgeClass === "hot" ? " hot" : "" }}">
                                <div class="pkg-top" style="background:{{ $pkgBg }}">
                                    <div class="pkg-name">{{ $item["name"] ?? "" }}</div>
                                    <span class="pkg-badge badge-{{ $badgeClass }}">{{ $item["badge"] ?? "" }}</span>
                                </div>
                                <div class="pkg-items">
                                    @foreach($item["items"] ?? [] as $label)
                                        <div class="pkg-item">
                                            <svg viewBox="0 0 24 24" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                            {{ $label }}
                                        </div>
                                    @endforeach
                                </div>
                                <div class="pkg-footer">
                                    <div class="pkg-price-wrap">
                                        <div class="pkg-price">{{ $item["price"] ?? "" }}</div>
                                        @if(!empty($item["old"]))
                                            <div class="pkg-old">{{ $item["old"] }}</div>
                                        @endif
                                        @if(!empty($item["save"]))
                                            <div class="pkg-save">{{ $item["save"] }}</div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="sec" id="bookingSection">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-booking">Book Appointment</div>
                </div>
                <div class="sec-body">
                    <div id="bookForm">
                        <div class="slot-row" id="slotGrid">
                            @foreach($slots as $slotItem)
                                @php
                                $slotLabel = $slotItem["slot"] ?? "";
                                $isFull = !empty($slotItem["full"]);
                                $isSelected = !$isFull && $selectedSlot && $slotLabel === $selectedSlot;
@endphp
                                @if($isFull)
                                    <div class="slot-card full" data-slot="{{ $slotLabel }}">
                                        <div class="slot-session">{{ $slotItem["session"] ?? "" }}</div>
                                        <div class="slot-time">{{ $slotItem["time"] ?? "" }}</div>
                                        <div class="slot-full-lbl">{{ $slotItem["fullLabel"] ?? data_get($data, "labels.fullyBooked") }}</div>
                                    </div>
                                @else
                                    <div class="slot-card{{ $isSelected ? " selected" : "" }}" onclick="selectSlot(this)" data-slot="{{ $slotLabel }}">
                                        <div class="slot-check">
                                            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12" /></svg>
                                        </div>
                                        <div class="slot-session">{{ $slotItem["session"] ?? "" }}</div>
                                        <div class="slot-time">{{ $slotItem["time"] ?? "" }}</div>
                                        <div class="slot-avail">
                                            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                            {{ $slotItem["availability"] ?? "" }}
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="bf-row">
                            <div class="bf-group">
                                <label class="bf-label" id="booking-name-label">{{ data_get($data, "booking.form.nameLabel") }}</label>
                                <input class="bf-input" id="bName" placeholder="{{ data_get($data, "booking.form.namePlaceholder") }}" type="text" />
                            </div>
                            <div class="bf-group">
                                <label class="bf-label" id="booking-phone-label">{{ data_get($data, "booking.form.phoneLabel") }}</label>
                                <input class="bf-input" id="bPhone" placeholder="{{ data_get($data, "booking.form.phonePlaceholder") }}" type="tel" />
                            </div>
                        </div>
                        <div class="bf-row">
                            <div class="bf-group">
                                <label class="bf-label" id="booking-service-label">{{ data_get($data, "booking.form.serviceLabel") }}</label>
                                <select class="bf-input" id="bService">
                                    <option value="">{{ data_get($data, "booking.form.servicePlaceholder") }}</option>
                                    @foreach(data_get($data, "booking.form.services", []) as $item)
                                        <option>{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="bf-group">
                                <label class="bf-label" id="booking-barber-label">{{ data_get($data, "booking.form.barberLabel") }}</label>
                                <select class="bf-input" id="bBarber">
                                    <option value="">{{ data_get($data, "booking.form.barberPlaceholder") }}</option>
                                    @foreach(data_get($data, "booking.form.barbers", []) as $item)
                                        <option>{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="bf-group">
                            <label class="bf-label" id="booking-note-label">{{ data_get($data, "booking.form.noteLabel") }}</label>
                            <input class="bf-input" id="bNote" placeholder="{{ data_get($data, "booking.form.notePlaceholder") }}" />
                        </div>
                        <button class="bf-submit" onclick="confirmBooking()">
                            <svg class="ic" viewBox="0 0 24 24">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                            </svg>
                            <span id="booking-submit-label">Confirm Booking via WhatsApp</span>
                        </button>
                    </div>
                    <div class="book-success" id="bookSuccess">
                        <div class="book-success-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                <polyline points="22 4 12 14.01 9 11.01" />
                            </svg>
                        </div>
                        <h4 id="booking-success-title">{{ data_get($data, "booking.success.title") }}</h4>
                        <p id="booking-success-text">{{ data_get($data, "booking.success.text") }}</p>
                        <button class="book-reset" onclick="resetBooking()">
                            <span id="booking-success-button">Book Another</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-barbers">Meet Our Barbers</div>
                </div>
                <div class="sec-body">
                    <div class="barbers-list" id="barbersList">
                        @foreach(data_get($data, "barbers", []) as $item)
                            <div class="barber-card">
                                <div class="barber-avatar" style="background:{{ $item["gradient"] ?? "linear-gradient(135deg,#0f1923,#2e4a62)" }}">{{ $item["avatar"] ?? "" }}</div>
                                <div class="barber-info">
                                    <div class="barber-name">{{ $item["name"] ?? "" }}</div>
                                    <div class="barber-role">{{ $item["role"] ?? "" }}</div>
                                    <div class="barber-exp">{{ $item["exp"] ?? "" }}</div>
                                    <div class="barber-skills">
                                        @foreach($item["skills"] ?? [] as $skill)
                                            <span class="b-chip">{{ $skill }}</span>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <path d="M16 10a4 4 0 0 1-8 0" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-products">Grooming Products</div>
                    <div class="sec-sub" id="sec-sub-products">{{ data_get($data, "sections.products.sub") }}</div>
                </div>
                <div class="sec-body">
                    <div class="products-grid" id="productsGrid">
                        @foreach(data_get($data, "products", []) as $item)
                            <div class="prod-card">
                                <div class="prod-thumb" style="background:{{ $item["thumbBg"] ?? "" }}">
                                    @if(!empty($item["tag"]))
                                        <span class="prod-tag" style="color:{{ $item["tagColor"] ?? "" }}">{{ $item["tag"] }}</span>
                                    @endif
                                </div>
                                <div class="prod-body">
                                    <div class="prod-name">{{ $item["name"] ?? "" }}</div>
                                    <div class="prod-desc">{{ $item["desc"] ?? "" }}</div>
                                    <div class="prod-footer">
                                        <span>
                                            <span class="prod-price">{{ $item["price"] ?? "" }}</span>
                                            @if(!empty($item["old"]))
                                                <span class="prod-old">{{ $item["old"] }}</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-hours">Working Hours</div>
                </div>
                <div class="sec-body">
                    <div class="today-badge">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        <span id="hours-today-label">{{ data_get($data, "hours.today") }}</span>
                    </div>
                    <table class="hours-table" id="hoursTable">
                        @foreach(data_get($data, "hours.rows", []) as $row)
                            <tr class="{{ $row["rowClass"] ?? "" }}">
                                <td class="day">{{ $row["day"] ?? "" }}</td>
                                <td class="time">{{ $row["time"] ?? "" }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-location">Find Us</div>
                </div>
                <div class="sec-body">
                    <div class="location-wrapper">
                        <div class="location-title" id="location-name">{{ data_get($data, "location.title") }}</div>
                        <a href="#" class="address-link" onclick="return (openMaps(), !1);">
                            <div class="addr-icon-wrap">
                                <svg class="ic" viewBox="0 0 24 24">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                    <circle cx="12" cy="10" r="3" />
                                </svg>
                            </div>
                            <div class="addr-text">
                                <span id="location-address">{{ data_get($data, "location.address") }}</span>
                                <div>
                                    <a class="map-btn" href="#" onclick="return (openMaps(), !1);">
                                        <svg viewBox="0 0 24 24">
                                            <polygon points="3 11 22 2 13 21 11 13 3 11" />
                                        </svg>
                                        <span id="location-map-label">Get Directions</span>
                                    </a>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <circle cx="18" cy="5" r="3" />
                            <circle cx="6" cy="12" r="3" />
                            <circle cx="18" cy="19" r="3" />
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-social">Follow Us</div>
                </div>
                <div class="sec-body">
                    <div class="social-list" id="socialList">
                        @foreach(data_get($data, "social", []) as $item)
                            @php
                            $type = $item["type"] ?? "instagram";
                            $iconKey = "social_" . $type;
                            $iconClass = $socialIconClasses[$type] ?? $socialIconClasses["instagram"];
                            $action = "";
                            if (($item["action"] ?? "") === "openWA") {
                                $action = "return (openWA(), !1);";
                            } elseif (!empty($item["url"])) {
                                $action = "return (openExternal(" . js_str($item["url"]) . "), !1);";
                            } else {
                                $action = "return !1;";
                            }
@endphp
                            <a class="social-item" href="#" onclick="{{ $action }}">
                                <div class="s-ico {{ $iconClass }}">{!! getIcon($iconKey) !!}</div>
                                <div>
                                    <div class="s-name">{{ $item["name"] ?? "" }}</div>
                                    <div class="s-val">{{ $item["value"] ?? "" }}</div>
                                </div>
                                <div class="s-arrow">
                                    <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6" /></svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
                            <line x1="1" y1="10" x2="23" y2="10" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-payments">Payment Modes</div>
                </div>
                <div class="sec-body">
                    <div class="payment-list" id="paymentList">
                        @foreach(data_get($data, "payments", []) as $item)
                            @php
                            $iconName = $item["icon"] ?? "";
                            if ($iconName === "") {
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
                                    <span style="display:flex;color:{{ $item["stroke"] ?? "#15803d" }}">{!! getIcon($iconKey) !!}</span>
                                </div>
                                <div>
                                    <div class="pay-name">{{ $item["name"] ?? "" }}</div>
                                    <div class="pay-detail">{{ $item["detail"] ?? "" }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7" rx="1" />
                            <rect x="14" y="3" width="7" height="7" rx="1" />
                            <rect x="3" y="14" width="7" height="7" rx="1" />
                            <rect x="14" y="14" width="3" height="3" />
                            <rect x="18" y="14" width="3" height="3" />
                            <rect x="14" y="18" width="3" height="3" />
                            <rect x="18" y="18" width="3" height="3" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sec-title-qr">Scan &amp; Save</div>
                </div>
                <div class="sec-body">
                    <div class="qr-card-inner">
                        <p style="font-size:0.78rem;color:var(--muted);margin-bottom:0.3rem;" id="qr-note">{{ data_get($data, "qr.note") }}</p>
                        <div id="vcardQR"></div>
                        <div class="qr-actions">
                            <button class="qr-btn" onclick="saveContact()">
                                <svg viewBox="0 0 24 24">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                                    <polyline points="17 21 17 13 7 13 7 21" />
                                    <polyline points="7 3 7 8 15 8" />
                                </svg>
                                <span id="qr-save-label">Save Contact</span>
                            </button>
                            <button class="qr-btn" onclick="downloadQR()">
                                <svg viewBox="0 0 24 24">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="7 10 12 15 17 10" />
                                    <line x1="12" y1="15" x2="12" y2="3" />
                                </svg>
                                <span id="qr-download-label">Download QR</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="vcard-footer">
                <p><span id="footer-line1">{{ data_get($data, "footer.line1") }}</span><strong id="footer-brand">{{ data_get($data, "footer.brand") }}</strong><span id="footer-line2">{{ data_get($data, "footer.line2") }}</span></p>
                <p style="margin-top:0.28rem;font-size:0.66rem;"><span id="footer-line3">{{ data_get($data, "footer.line3") }}</span><strong style="color:var(--gold)" id="footer-powered">{{ data_get($data, "footer.powered") }}</strong></p>
            </div>

            <div class="float-bar">
                <button class="fab call-fab" onclick="callShop()">
                    <svg class="ic-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.13 6.13l.96-.96a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.73 16.92z" />
                    </svg>
                    <span id="fab-call-label">Call</span>
                </button>
                <button class="fab book-fab" onclick="scrollToBooking()">
                    <svg class="ic-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    <span id="fab-book-label">Book Now</span>
                </button>
                <button class="fab wa-fab" onclick="openWA()">
                    <svg class="ic-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                    </svg>
                    <span id="fab-whatsapp-label">WhatsApp</span>
                </button>
                <button class="fab save-fab" onclick="saveContact()">
                    <svg class="ic-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                    <span id="fab-save-label">Save</span>
                </button>
            </div>

            <div class="modal-overlay" id="shareModal" onclick="closeShare(event)">
                <div class="modal-box">
                    <div class="modal-title" id="share-title">Share This Card</div>
                    <div class="share-options">
                        <div class="sh-opt" onclick="shareWA()" style="color:#128c7e">
                            <svg width="20" height="20" viewBox="0 0 24 24" stroke="#128c7e" stroke-width="2" fill="none">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                            </svg>
                            <span id="share-wa-label">WhatsApp</span>
                        </div>
                        <div class="sh-opt" onclick="copyLink()" style="color:var(--steel)">
                            <svg width="20" height="20" viewBox="0 0 24 24" stroke="var(--steel)" stroke-width="2" fill="none">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2" />
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                            </svg>
                            <span id="share-copy-label">Copy Link</span>
                        </div>
                        <div class="sh-opt" onclick="shareNative()" style="color:#0369a1">
                            <svg width="20" height="20" viewBox="0 0 24 24" stroke="#0369a1" stroke-width="2" fill="none">
                                <circle cx="18" cy="5" r="3" />
                                <circle cx="6" cy="12" r="3" />
                                <circle cx="18" cy="19" r="3" />
                                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                            </svg>
                            <span id="share-more-label">More…</span>
                        </div>
                        <div class="sh-opt" onclick="shareFB()" style="color:#1877f2">
                            <svg width="20" height="20" viewBox="0 0 24 24" stroke="#1877f2" stroke-width="2" fill="none">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                            </svg>
                            <span id="share-facebook-label">Facebook</span>
                        </div>
                    </div>
                    <button class="modal-cancel" onclick="closeShareModal()">
                        <span id="share-cancel-label">Cancel</span>
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
                        <svg viewBox="0 0 24 24">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                        </svg>
                    </div>
                    <h3 id="promo-title">{{ data_get($data, "promo.title") }}</h3>
                    <p id="promo-text">{{ data_get($data, "promo.text") }}</p>
                    <button class="promo-cta" onclick="promoAction()">
                        <svg viewBox="0 0 24 24">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                        </svg>
                        <span id="promo-cta-label">{{ data_get($data, "promo.cta") }}</span>
                    </button>
                </div>
            </div>

            <div class="toast" id="toast">
                <svg viewBox="0 0 24 24" stroke-width="2" width="13" height="13">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                <span id="toastMsg">{{ data_get($data, "messages.defaultToast") }}</span>
            </div>

            <!-- Icon Templates (hidden) -->
            <div class="icon-templates" style="display:none;">
                <span id="service_scissor"><svg viewBox="0 0 24 24">{!! getIcon("service_scissor") !!}</svg></span>
                <span id="service_heart"><svg viewBox="0 0 24 24">{!! getIcon("service_heart") !!}</svg></span>
                <span id="service_razor"><svg viewBox="0 0 24 24">{!! getIcon("service_razor") !!}</svg></span>
                <span id="service_sun"><svg viewBox="0 0 24 24">{!! getIcon("service_sun") !!}</svg></span>
                <span id="service_leaf"><svg viewBox="0 0 24 24">{!! getIcon("service_leaf") !!}</svg></span>
                <span id="tip_clock">{!! getIcon("tip_clock") !!}</span>
                <span id="tip_shield">{!! getIcon("tip_shield") !!}</span>
                <span id="tip_leaf">{!! getIcon("tip_leaf") !!}</span>
                <span id="tip_heart">{!! getIcon("tip_heart") !!}</span>
                <span id="tip_layers">{!! getIcon("tip_layers") !!}</span>
                <span id="social_whatsapp"><svg class="ic" viewBox="0 0 24 24" stroke-width="2">{!! getIcon("social_whatsapp") !!}</svg></span>
                <span id="social_instagram"><svg class="ic" viewBox="0 0 24 24" stroke-width="2">{!! getIcon("social_instagram") !!}</svg></span>
                <span id="social_youtube"><svg class="ic" viewBox="0 0 24 24" stroke-width="2">{!! getIcon("social_youtube") !!}</svg></span>
                <span id="social_facebook"><svg class="ic" viewBox="0 0 24 24" stroke-width="2">{!! getIcon("social_facebook") !!}</svg></span>
                <span id="pay_cash"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! getIcon("pay_cash") !!}</svg></span>
                <span id="pay_card"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! getIcon("pay_card") !!}</svg></span>
                <span id="ui_arrow_right"><svg viewBox="0 0 24 24">{!! getIcon("ui_arrow_right") !!}</svg></span>
                <span id="ui_check"><svg viewBox="0 0 24 24">{!! getIcon("ui_check") !!}</svg></span>
                <span id="ui_star"><svg viewBox="0 0 24 24">{!! getIcon("ui_star") !!}</svg></span>
                <span id="ui_cart"><svg viewBox="0 0 24 24">{!! getIcon("ui_cart") !!}</svg></span>
            </div>
        </main>
        <script>
            window.__APP__ = {!! vcard_js_str($data) !!};
            window.__VCARD_SUBDOMAIN__ = {!! json_encode($subdomain) !!};
        </script>
        <script src="{{ $assetBase }}script.js"></script>
    </body>
</html>