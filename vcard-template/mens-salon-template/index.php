<?php
declare(strict_types=1);

// Load from data.json (vCard data) first, fallback to default.json (template defaults)
$dataPath = __DIR__ . "/../data.json";
if (!file_exists($dataPath)) {
    $dataPath = __DIR__ . "/default.json";
}
$rawData = @file_get_contents($dataPath);
$data = $rawData ? json_decode($rawData, true) : [];

if (!is_array($data)) {
    $data = [];
}

require_once __DIR__ . "/icons.php";

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}

function data_get(array $data, string $path, $default = "")
{
    $segments = explode(".", $path);
    $value = $data;

    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }

    return $value;
}

function data_list(array $data, string $path): array
{
    $value = data_get($data, $path, []);
    return is_array($value) ? $value : [];
}

function js_str($value): string
{
    return json_encode($value ?? "", JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
}

function isSectionEnabled($data, $section)
{
    if (!isset($data['_sections_config'])) {
        return true;
    }
    if (!isset($data['_sections_config'][$section])) {
        return true;
    }
    return $data['_sections_config'][$section]['enabled'] ?? true;
}

$bannerImage = data_get($data, "assets.bannerImage", "");
$profileImage = data_get($data, "assets.profileImage", data_get($data, "assets.fallbackImage", ""));
$profileAlt = data_get($data, "shop.name", "");

$slots = data_list($data, "booking.slots");
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
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1" />
        <title><?= e(data_get($data, "meta.title")); ?></title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
        <main id="app-root" aria-live="polite" style="min-height:100vh">
            <div class="banner">
                <div class="banner-bg"<?= $bannerImage ? " style=\"background:url('" . e($bannerImage) . "') center/cover no-repeat\"" : ""; ?>>
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
                        <div class="banner-title" id="banner-title"><?= e(data_get($data, "shop.name")); ?></div>
                        <div class="banner-sub" id="banner-subtitle"><?= e(data_get($data, "shop.subtitle")); ?></div>
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
                        <span id="banner-share-label"><?= e(data_get($data, "banner.shareLabel")); ?></span>
                    </button>
                    <div class="verified-badge">
                        <svg width="11" height="11" viewBox="0 0 24 24">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                        <span id="banner-verified-label"><?= e(data_get($data, "banner.verifiedLabel")); ?></span>
                    </div>
                </div>
            </div>

            <div class="status-bar">
                <div class="status-open">
                    <div class="dot-pulse"></div>
                    <span id="status-open-label"><?= e(data_get($data, "status.openLabel")); ?></span>
                </div>
                <div class="next-slot">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                    <span id="status-next-slot"><?= e(data_get($data, "status.nextSlotLabel")); ?></span>
                </div>
            </div>

            <div class="profile-card">
                <div class="profile-avatar-wrap">
                    <div class="profile-avatar">
                        <img id="profile-image" src="<?= e($profileImage); ?>" alt="<?= e($profileAlt); ?>" style="width:100%;height:100%;object-fit:cover" />
                    </div>
                    <span class="owner-tag" id="profile-owner-tag"><?= e(data_get($data, "profile.ownerTag")); ?></span>
                </div>
                <div class="profile-name" id="profile-name"><?= e(data_get($data, "profile.name")); ?></div>
                <div class="profile-role" id="profile-role"><?= e(data_get($data, "profile.role")); ?></div>
                <div class="profile-tagline" id="profile-tagline"><?= e(data_get($data, "profile.tagline")); ?></div>
                <div class="profile-stats" id="profileStats">
                    <?php $stats = data_list($data, "profile.stats"); ?>
                    <?php foreach ($stats as $index => $item): ?>
                        <div class="pstat">
                            <div class="pstat-num"><?= e($item["value"] ?? ""); ?></div>
                            <div class="pstat-lbl"><?= e($item["label"] ?? ""); ?></div>
                        </div>
                        <?php if ($index < count($stats) - 1): ?>
                            <div class="stat-div"></div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="profile-action-btns">
                    <button class="pab call" onclick="callShop()">
                        <svg viewBox="0 0 24 24">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.13 6.13l.96-.96a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.73 16.92z" />
                        </svg>
                        <span id="action-call-label"><?= e(data_get($data, "profile.actions.call")); ?></span>
                    </button>
                    <button class="pab whatsapp" onclick="openWA()">
                        <svg viewBox="0 0 24 24">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                        </svg>
                        <span id="action-whatsapp-label"><?= e(data_get($data, "profile.actions.whatsapp")); ?></span>
                    </button>
                    <button class="pab book" onclick="scrollToBooking()">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        <span id="action-book-label"><?= e(data_get($data, "profile.actions.book")); ?></span>
                    </button>
                    <button class="pab email" onclick="emailShop()">
                        <svg viewBox="0 0 24 24">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                        <span id="action-email-label"><?= e(data_get($data, "profile.actions.email")); ?></span>
                    </button>
                    <button class="pab direction" onclick="openMaps()">
                        <svg viewBox="0 0 24 24">
                            <polygon points="3 11 22 2 13 21 11 13 3 11" />
                        </svg>
                        <span id="action-direction-label"><?= e(data_get($data, "profile.actions.direction")); ?></span>
                    </button>
                    <button class="pab share" onclick="openShare()">
                        <svg viewBox="0 0 24 24">
                            <circle cx="18" cy="5" r="3" />
                            <circle cx="6" cy="12" r="3" />
                            <circle cx="18" cy="19" r="3" />
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                        </svg>
                        <span id="action-share-label"><?= e(data_get($data, "profile.actions.share")); ?></span>
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
                    <div class="sec-title" id="sec-title-services"><?= e(data_get($data, "sections.services.title")); ?></div>
                    <div class="sec-sub" id="sec-sub-services"><?= e(data_get($data, "sections.services.sub")); ?></div>
                </div>
                <div class="sec-body">
                    <div class="services-grid" id="servicesGrid">
                        <?php foreach (data_list($data, "services") as $item): ?>
                            <?php $iconKey = "service_" . ($item["icon"] ?? ""); ?>
                            <div class="svc-card" onclick="bookSvc(<?= js_str($item["name"] ?? ""); ?>)">
                                <div class="svc-thumb" style="background:<?= e($item["bg"] ?? ""); ?>">
                                    <div class="svc-thumb-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" width="22" height="22"><?= getIcon($iconKey); ?></svg>
                                    </div>
                                    <div class="svc-price-tag"><?= e($item["price"] ?? ""); ?></div>
                                </div>
                                <div class="svc-body">
                                    <div class="svc-name"><?= e($item["name"] ?? ""); ?></div>
                                    <div class="svc-desc"><?= e($item["desc"] ?? ""); ?></div>
                                    <div class="svc-footer">
                                        <div class="svc-dur"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg><?= e($item["dur"] ?? ""); ?></div>
                                        <button class="svc-book-chip" onclick="event.stopPropagation();bookSvc(<?= js_str($item["name"] ?? ""); ?>)"><?= e(data_get($data, "labels.bookChip")); ?></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
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
                    <div class="sec-title" id="sec-title-packages"><?= e(data_get($data, "sections.packages.title")); ?></div>
                    <div class="sec-sub" id="sec-sub-packages"><?= e(data_get($data, "sections.packages.sub")); ?></div>
                </div>
                <div class="sec-body">
                    <div class="pkg-list" id="pkgList">
                        <?php foreach (data_list($data, "packages") as $item): ?>
                            <?php
                            $badgeClass = $item["badgeClass"] ?? "";
                            $pkgBg = "linear-gradient(135deg,#e0f2fe,#f0f9ff)";
                            if ($badgeClass === "hot") {
                                $pkgBg = "linear-gradient(135deg,#fdf3d0,#fefce8)";
                            } elseif ($badgeClass === "value") {
                                $pkgBg = "linear-gradient(135deg,#f0fdf4,#dcfce7)";
                            }
                            ?>
                            <div class="pkg-card<?= $badgeClass === "hot" ? " hot" : ""; ?>">
                                <div class="pkg-top" style="background:<?= e($pkgBg); ?>">
                                    <div class="pkg-name"><?= e($item["name"] ?? ""); ?></div>
                                    <span class="pkg-badge badge-<?= e($badgeClass); ?>"><?= e($item["badge"] ?? ""); ?></span>
                                </div>
                                <div class="pkg-items">
                                    <?php foreach ($item["items"] ?? [] as $label): ?>
                                        <div class="pkg-item">
                                            <svg viewBox="0 0 24 24" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                            <?= e($label); ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="pkg-footer">
                                    <div class="pkg-price-wrap">
                                        <div class="pkg-price"><?= e($item["price"] ?? ""); ?></div>
                                        <?php if (!empty($item["old"])): ?>
                                            <div class="pkg-old"><?= e($item["old"]); ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($item["save"])): ?>
                                            <div class="pkg-save"><?= e($item["save"]); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <button class="pkg-btn" onclick="bookSvc(<?= js_str(($item["name"] ?? "") . " Package"); ?>)">
                                        <svg viewBox="0 0 24 24" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                                        <?= e(data_get($data, "labels.bookNow")); ?>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
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
                    <div class="sec-title" id="sec-title-booking"><?= e(data_get($data, "sections.booking.title")); ?></div>
                </div>
                <div class="sec-body">
                    <div id="bookForm">
                        <div class="slot-row" id="slotGrid">
                            <?php foreach ($slots as $slotItem): ?>
                                <?php
                                $slotLabel = $slotItem["slot"] ?? "";
                                $isFull = !empty($slotItem["full"]);
                                $isSelected = !$isFull && $selectedSlot && $slotLabel === $selectedSlot;
                                ?>
                                <?php if ($isFull): ?>
                                    <div class="slot-card full" data-slot="<?= e($slotLabel); ?>">
                                        <div class="slot-session"><?= e($slotItem["session"] ?? ""); ?></div>
                                        <div class="slot-time"><?= e($slotItem["time"] ?? ""); ?></div>
                                        <div class="slot-full-lbl"><?= e($slotItem["fullLabel"] ?? data_get($data, "labels.fullyBooked")); ?></div>
                                    </div>
                                <?php else: ?>
                                    <div class="slot-card<?= $isSelected ? " selected" : ""; ?>" onclick="selectSlot(this)" data-slot="<?= e($slotLabel); ?>">
                                        <div class="slot-check">
                                            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12" /></svg>
                                        </div>
                                        <div class="slot-session"><?= e($slotItem["session"] ?? ""); ?></div>
                                        <div class="slot-time"><?= e($slotItem["time"] ?? ""); ?></div>
                                        <div class="slot-avail">
                                            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                            <?= e($slotItem["availability"] ?? ""); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <div class="bf-row">
                            <div class="bf-group">
                                <label class="bf-label" id="booking-name-label"><?= e(data_get($data, "booking.form.nameLabel")); ?></label>
                                <input class="bf-input" id="bName" placeholder="<?= e(data_get($data, "booking.form.namePlaceholder")); ?>" type="text" />
                            </div>
                            <div class="bf-group">
                                <label class="bf-label" id="booking-phone-label"><?= e(data_get($data, "booking.form.phoneLabel")); ?></label>
                                <input class="bf-input" id="bPhone" placeholder="<?= e(data_get($data, "booking.form.phonePlaceholder")); ?>" type="tel" />
                            </div>
                        </div>
                        <div class="bf-row">
                            <div class="bf-group">
                                <label class="bf-label" id="booking-service-label"><?= e(data_get($data, "booking.form.serviceLabel")); ?></label>
                                <select class="bf-input" id="bService">
                                    <option value=""><?= e(data_get($data, "booking.form.servicePlaceholder")); ?></option>
                                    <?php foreach (data_list($data, "booking.form.services") as $item): ?>
                                        <option><?= e($item); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="bf-group">
                                <label class="bf-label" id="booking-barber-label"><?= e(data_get($data, "booking.form.barberLabel")); ?></label>
                                <select class="bf-input" id="bBarber">
                                    <option value=""><?= e(data_get($data, "booking.form.barberPlaceholder")); ?></option>
                                    <?php foreach (data_list($data, "booking.form.barbers") as $item): ?>
                                        <option><?= e($item); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="bf-group">
                            <label class="bf-label" id="booking-note-label"><?= e(data_get($data, "booking.form.noteLabel")); ?></label>
                            <input class="bf-input" id="bNote" placeholder="<?= e(data_get($data, "booking.form.notePlaceholder")); ?>" />
                        </div>
                        <button class="bf-submit" onclick="confirmBooking()">
                            <svg class="ic" viewBox="0 0 24 24">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                            </svg>
                            <span id="booking-submit-label"><?= e(data_get($data, "booking.form.submitLabel")); ?></span>
                        </button>
                    </div>
                    <div class="book-success" id="bookSuccess">
                        <div class="book-success-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                <polyline points="22 4 12 14.01 9 11.01" />
                            </svg>
                        </div>
                        <h4 id="booking-success-title"><?= e(data_get($data, "booking.success.title")); ?></h4>
                        <p id="booking-success-text"><?= e(data_get($data, "booking.success.text")); ?></p>
                        <button class="book-reset" onclick="resetBooking()">
                            <span id="booking-success-button"><?= e(data_get($data, "booking.success.button")); ?></span>
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
                    <div class="sec-title" id="sec-title-barbers"><?= e(data_get($data, "sections.barbers.title")); ?></div>
                </div>
                <div class="sec-body">
                    <div class="barbers-list" id="barbersList">
                        <?php foreach (data_list($data, "barbers") as $item): ?>
                            <div class="barber-card">
                                <div class="barber-avatar" style="background:<?= e($item["gradient"] ?? "linear-gradient(135deg,#0f1923,#2e4a62)"); ?>"><?= e($item["avatar"] ?? ""); ?></div>
                                <div class="barber-info">
                                    <div class="barber-name"><?= e($item["name"] ?? ""); ?></div>
                                    <div class="barber-role"><?= e($item["role"] ?? ""); ?></div>
                                    <div class="barber-exp"><?= e($item["exp"] ?? ""); ?></div>
                                    <div class="barber-skills">
                                        <?php foreach ($item["skills"] ?? [] as $skill): ?>
                                            <span class="b-chip"><?= e($skill); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <button class="barber-book-btn" onclick="bookBarber(<?= js_str($item["name"] ?? ""); ?>)"><?= e(data_get($data, "labels.bookChip")); ?></button>
                            </div>
                        <?php endforeach; ?>
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
                    <div class="sec-title" id="sec-title-products"><?= e(data_get($data, "sections.products.title")); ?></div>
                    <div class="sec-sub" id="sec-sub-products"><?= e(data_get($data, "sections.products.sub")); ?></div>
                </div>
                <div class="sec-body">
                    <div class="products-grid" id="productsGrid">
                        <?php foreach (data_list($data, "products") as $item): ?>
                            <div class="prod-card">
                                <div class="prod-thumb" style="background:<?= e($item["thumbBg"] ?? ""); ?>">
                                    <?php if (!empty($item["tag"])): ?>
                                        <span class="prod-tag" style="background:<?= e($item["tagBg"] ?? ""); ?>;color:<?= e($item["tagColor"] ?? ""); ?>"><?= e($item["tag"]); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="prod-body">
                                    <div class="prod-name"><?= e($item["name"] ?? ""); ?></div>
                                    <div class="prod-desc"><?= e($item["desc"] ?? ""); ?></div>
                                    <div class="prod-footer">
                                        <span>
                                            <span class="prod-price"><?= e($item["price"] ?? ""); ?></span>
                                            <?php if (!empty($item["old"])): ?>
                                                <span class="prod-old"><?= e($item["old"]); ?></span>
                                            <?php endif; ?>
                                        </span>
                                        <button class="buy-btn" onclick="enquireProduct(<?= js_str($item["name"] ?? ""); ?>)"><?= e(data_get($data, "labels.buy")); ?></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
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
                    <div class="sec-title" id="sec-title-hours"><?= e(data_get($data, "sections.hours.title")); ?></div>
                </div>
                <div class="sec-body">
                    <div class="today-badge">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        <span id="hours-today-label"><?= e(data_get($data, "hours.today")); ?></span>
                    </div>
                    <table class="hours-table" id="hoursTable">
                        <?php foreach (data_list($data, "hours.rows") as $row): ?>
                            <tr class="<?= e($row["rowClass"] ?? ""); ?>">
                                <td class="day"><?= e($row["day"] ?? ""); ?></td>
                                <td class="time"><?= e($row["time"] ?? ""); ?></td>
                            </tr>
                        <?php endforeach; ?>
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
                    <div class="sec-title" id="sec-title-location"><?= e(data_get($data, "sections.location.title")); ?></div>
                </div>
                <div class="sec-body">
                    <a href="#" class="address-link" onclick="return (openMaps(), !1);">
                        <div class="addr-icon-wrap">
                            <svg class="ic" viewBox="0 0 24 24">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                        </div>
                        <div class="addr-text">
                            <strong id="location-name"><?= e(data_get($data, "location.title")); ?></strong>
                            <span id="location-line1"><?= e(data_get($data, "location.line1")); ?></span><br />
                            <span id="location-line2"><?= e(data_get($data, "location.line2")); ?></span><br />
                            <span id="location-line3"><?= e(data_get($data, "location.line3")); ?></span>
                            <div>
                                <a class="map-btn" href="#" onclick="return (openMaps(), !1);">
                                    <svg viewBox="0 0 24 24">
                                        <polygon points="3 11 22 2 13 21 11 13 3 11" />
                                    </svg>
                                    <span id="location-map-label"><?= e(data_get($data, "location.mapLabel")); ?></span>
                                </a>
                            </div>
                        </div>
                    </a>
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
                    <div class="sec-title" id="sec-title-social"><?= e(data_get($data, "sections.social.title")); ?></div>
                </div>
                <div class="sec-body">
                    <div class="social-list" id="socialList">
                        <?php foreach (data_list($data, "social") as $item): ?>
                            <?php
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
                            ?>
                            <a class="social-item" href="#" onclick="<?= e($action); ?>">
                                <div class="s-ico <?= e($iconClass); ?>"><?= getIcon($iconKey); ?></div>
                                <div>
                                    <div class="s-name"><?= e($item["name"] ?? ""); ?></div>
                                    <div class="s-val"><?= e($item["value"] ?? ""); ?></div>
                                </div>
                                <div class="s-arrow">
                                    <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6" /></svg>
                                </div>
                            </a>
                        <?php endforeach; ?>
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
                    <div class="sec-title" id="sec-title-payments"><?= e(data_get($data, "sections.payments.title")); ?></div>
                </div>
                <div class="sec-body">
                    <div class="payment-list" id="paymentList">
                        <?php foreach (data_list($data, "payments") as $item): ?>
                            <?php $iconKey = "pay_" . ($item["icon"] ?? ""); ?>
                            <div class="pay-item">
                                <div class="pay-icon-wrap">
                                    <span style="display:flex;color:<?= e($item["stroke"] ?? "#15803d"); ?>"><?= getIcon($iconKey); ?></span>
                                </div>
                                <div>
                                    <div class="pay-name"><?= e($item["name"] ?? ""); ?></div>
                                    <div class="pay-detail"><?= e($item["detail"] ?? ""); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
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
                    <div class="sec-title" id="sec-title-qr"><?= e(data_get($data, "sections.qr.title")); ?></div>
                </div>
                <div class="sec-body">
                    <div class="qr-card-inner">
                        <p style="font-size:0.78rem;color:var(--muted);margin-bottom:0.3rem;" id="qr-note"><?= e(data_get($data, "qr.note")); ?></p>
                        <div id="vcardQR"></div>
                        <div class="qr-actions">
                            <button class="qr-btn" onclick="saveContact()">
                                <svg viewBox="0 0 24 24">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                                    <polyline points="17 21 17 13 7 13 7 21" />
                                    <polyline points="7 3 7 8 15 8" />
                                </svg>
                                <span id="qr-save-label"><?= e(data_get($data, "qr.saveLabel")); ?></span>
                            </button>
                            <button class="qr-btn" onclick="downloadQR()">
                                <svg viewBox="0 0 24 24">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="7 10 12 15 17 10" />
                                    <line x1="12" y1="15" x2="12" y2="3" />
                                </svg>
                                <span id="qr-download-label"><?= e(data_get($data, "qr.downloadLabel")); ?></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="vcard-footer">
                <p><span id="footer-line1"><?= e(data_get($data, "footer.line1")); ?></span><strong id="footer-brand"><?= e(data_get($data, "footer.brand")); ?></strong><span id="footer-line2"><?= e(data_get($data, "footer.line2")); ?></span></p>
                <p style="margin-top:0.28rem;font-size:0.66rem;"><span id="footer-line3"><?= e(data_get($data, "footer.line3")); ?></span><strong style="color:var(--gold)" id="footer-powered"><?= e(data_get($data, "footer.powered")); ?></strong></p>
            </div>

            <div class="float-bar">
                <button class="fab call-fab" onclick="callShop()">
                    <svg class="ic-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.13 6.13l.96-.96a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.73 16.92z" />
                    </svg>
                    <span id="fab-call-label"><?= e(data_get($data, "floatBar.call")); ?></span>
                </button>
                <button class="fab book-fab" onclick="scrollToBooking()">
                    <svg class="ic-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    <span id="fab-book-label"><?= e(data_get($data, "floatBar.book")); ?></span>
                </button>
                <button class="fab wa-fab" onclick="openWA()">
                    <svg class="ic-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                    </svg>
                    <span id="fab-whatsapp-label"><?= e(data_get($data, "floatBar.whatsapp")); ?></span>
                </button>
                <button class="fab save-fab" onclick="saveContact()">
                    <svg class="ic-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                    <span id="fab-save-label"><?= e(data_get($data, "floatBar.save")); ?></span>
                </button>
            </div>

            <div class="modal-overlay" id="shareModal" onclick="closeShare(event)">
                <div class="modal-box">
                    <div class="modal-title" id="share-title"><?= e(data_get($data, "share.title")); ?></div>
                    <div class="share-options">
                        <div class="sh-opt" onclick="shareWA()" style="color:#128c7e">
                            <svg width="20" height="20" viewBox="0 0 24 24" stroke="#128c7e" stroke-width="2" fill="none">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                            </svg>
                            <span id="share-wa-label"><?= e(data_get($data, "share.whatsapp")); ?></span>
                        </div>
                        <div class="sh-opt" onclick="copyLink()" style="color:var(--steel)">
                            <svg width="20" height="20" viewBox="0 0 24 24" stroke="var(--steel)" stroke-width="2" fill="none">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2" />
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                            </svg>
                            <span id="share-copy-label"><?= e(data_get($data, "share.copy")); ?></span>
                        </div>
                        <div class="sh-opt" onclick="shareNative()" style="color:#0369a1">
                            <svg width="20" height="20" viewBox="0 0 24 24" stroke="#0369a1" stroke-width="2" fill="none">
                                <circle cx="18" cy="5" r="3" />
                                <circle cx="6" cy="12" r="3" />
                                <circle cx="18" cy="19" r="3" />
                                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                            </svg>
                            <span id="share-more-label"><?= e(data_get($data, "share.more")); ?></span>
                        </div>
                        <div class="sh-opt" onclick="shareFB()" style="color:#1877f2">
                            <svg width="20" height="20" viewBox="0 0 24 24" stroke="#1877f2" stroke-width="2" fill="none">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                            </svg>
                            <span id="share-facebook-label"><?= e(data_get($data, "share.facebook")); ?></span>
                        </div>
                    </div>
                    <button class="modal-cancel" onclick="closeShareModal()">
                        <span id="share-cancel-label"><?= e(data_get($data, "share.cancel")); ?></span>
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
                    <h3 id="promo-title"><?= e(data_get($data, "promo.title")); ?></h3>
                    <p id="promo-text"><?= e(data_get($data, "promo.text")); ?></p>
                    <button class="promo-cta" onclick="promoAction()">
                        <svg viewBox="0 0 24 24">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                        </svg>
                        <span id="promo-cta-label"><?= e(data_get($data, "promo.cta")); ?></span>
                    </button>
                </div>
            </div>

            <div class="toast" id="toast">
                <svg viewBox="0 0 24 24" stroke-width="2" width="13" height="13">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                <span id="toastMsg"><?= e(data_get($data, "messages.defaultToast")); ?></span>
            </div>
        </main>
        <script>
            window.__APP__ = <?= json_encode($data ?: [], JSON_UNESCAPED_SLASHES); ?>;
        </script>
        <script src="script.js"></script>
    </body>
</html>