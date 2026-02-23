<?php
declare(strict_types=1);

$dataPath = __DIR__ . "/default.json";
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

function star_markup(int $filled): string
{
    $stars = [];
    for ($i = 0; $i < 5; $i++) {
        $on = $i < $filled;
        $fill = $on ? "#f4c430" : "#e0e0e0";
        $stars[] = '<svg class="star" viewBox="0 0 24 24" fill="' . $fill . '" stroke="' . $fill . '" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>';
    }

    return implode("", $stars);
}

$bannerImage = data_get($data, "assets.bannerImage", "");
$storyImage = data_get($data, "story.image", data_get($data, "assets.fallbackImage", ""));
$restaurantName = data_get($data, "R.name", "");
$menu = data_get($data, "MENU", []);
$menuTabs = is_array($menu) ? array_keys($menu) : [];
$activeTab = $menuTabs[0] ?? "";
$activeItems = $activeTab && isset($menu[$activeTab]) && is_array($menu[$activeTab]) ? $menu[$activeTab] : [];
$socialIconClasses = [
    "instagram" => "ic-ig",
    "whatsapp" => "ic-wa",
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
        <main id="app-root" aria-live="polite">
            <div class="banner">
                <div class="banner-bg" id="bannerBg"<?= $bannerImage ? " style=\"background:url('" . e($bannerImage) . "') center/cover no-repeat\"" : ""; ?>></div>
                <div class="banner-pattern"></div>
                <div class="banner-overlay"></div>
                <div class="banner-top-bar">
                    <div class="status-pill">
                        <div class="status-dot"></div>
                        <span id="statusLabel"><?= e(data_get($data, "banner.statusLabel")); ?></span>
                    </div>
                    <button class="share-btn" onclick="openShare()">
                        <svg class="ic-sm" viewBox="0 0 24 24" stroke="#fff" fill="none">
                            <circle cx="18" cy="5" r="3" />
                            <circle cx="6" cy="12" r="3" />
                            <circle cx="18" cy="19" r="3" />
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                        </svg>
                        <span id="bannerShareLabel"><?= e(data_get($data, "banner.shareLabel")); ?></span>
                    </button>
                </div>
                <div class="banner-center">
                    <div class="banner-eyebrow" id="bannerEyebrow"><?= e(data_get($data, "banner.eyebrow")); ?></div>
                    <div class="banner-title" id="bannerTitle"><?= e(data_get($data, "banner.title")); ?></div>
                    <div class="banner-sub" id="bannerSub"><?= e(data_get($data, "banner.subtitle")); ?></div>
                </div>
                <div class="rating-strip" id="ratingStrip">
                    <?php foreach (data_list($data, "banner.ratings") as $item): ?>
                        <?php $iconKey = "rating_" . ($item["icon"] ?? ""); ?>
                        <div class="r-stat">
                            <?= getIcon($iconKey); ?>
                            <?= e($item["label"] ?? ""); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="profile-card">
                <div class="cuisine-tags" id="cuisineTags">
                    <?php foreach (data_list($data, "profile.cuisineTags") as $tag): ?>
                        <span class="ctag"><?= e($tag); ?></span>
                    <?php endforeach; ?>
                </div>
                <div class="profile-action-btns">
                    <button class="pab call" onclick="callUs()">
                        <svg class="ic-sm" viewBox="0 0 24 24" stroke="#2e7d32" fill="none">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.13 6.13l.96-.96a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.73 16.92z" />
                        </svg>
                        <span id="actionCallLabel"><?= e(data_get($data, "profile.actions.call")); ?></span>
                    </button>
                    <button class="pab wa" onclick="openWA()">
                        <svg class="ic-sm" viewBox="0 0 24 24" stroke="#1b5e20" fill="none">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                        </svg>
                        <span id="actionWaLabel"><?= e(data_get($data, "profile.actions.whatsapp")); ?></span>
                    </button>
                    <button class="pab res" onclick="openReserveModal()">
                        <svg class="ic-sm" viewBox="0 0 24 24" stroke="#e65100" fill="none">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        <span id="actionReserveLabel"><?= e(data_get($data, "profile.actions.reserve")); ?></span>
                    </button>
                    <button class="pab email" onclick="emailUs()">
                        <svg class="ic-sm" viewBox="0 0 24 24" stroke="#1565c0" fill="none">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                        <span id="actionEmailLabel"><?= e(data_get($data, "profile.actions.email")); ?></span>
                    </button>
                    <button class="pab dir" onclick="openMaps()">
                        <svg class="ic-sm" viewBox="0 0 24 24" stroke="#880e4f" fill="none">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                        <span id="actionDirectionLabel"><?= e(data_get($data, "profile.actions.directions")); ?></span>
                    </button>
                    <button class="pab share" onclick="openShare()">
                        <svg class="ic-sm" viewBox="0 0 24 24" stroke="#6a1b9a" fill="none">
                            <circle cx="18" cy="5" r="3" />
                            <circle cx="6" cy="12" r="3" />
                            <circle cx="18" cy="19" r="3" />
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                        </svg>
                        <span id="actionShareLabel"><?= e(data_get($data, "profile.actions.share")); ?></span>
                    </button>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg class="ic" viewBox="0 0 24 24">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                        </svg>
                    </div>
                    <span class="sec-title" id="secStoryTitle"><?= e(data_get($data, "sections.story")); ?></span>
                </div>
                <div class="sec-body">
                    <div class="story-wrap">
                        <div class="story-avatar">
                            <img id="storyImage" src="<?= e($storyImage); ?>" alt="<?= e($restaurantName); ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px" />
                        </div>
                        <div class="story-text">
                            <p id="storyP1"><?= e(data_get($data, "story.paragraph1")); ?></p>
                            <p id="storyP2"><?= e(data_get($data, "story.paragraph2")); ?></p>
                            <div class="chef-sig">
                                <div class="chef-name" id="chefName"><?= e(data_get($data, "story.chefName")); ?></div>
                                <div class="chef-role" id="chefRole"><?= e(data_get($data, "story.chefRole")); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="hl-row" id="highlightsRow">
                        <?php foreach (data_list($data, "story.highlights") as $item): ?>
                            <?php $iconKey = "highlight_" . ($item["icon"] ?? ""); ?>
                            <div class="hl-box">
                                <div class="hl-em"><?= getIcon($iconKey); ?></div>
                                <div class="hl-lbl"><?= e($item["label"] ?? ""); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon terra">
                        <svg class="ic" viewBox="0 0 24 24">
                            <path d="M18 8h1a4 4 0 0 1 0 8h-1" />
                            <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z" />
                            <line x1="6" y1="1" x2="6" y2="4" />
                            <line x1="10" y1="1" x2="10" y2="4" />
                            <line x1="14" y1="1" x2="14" y2="4" />
                        </svg>
                    </div>
                    <span class="sec-title" id="secMenuTitle"><?= e(data_get($data, "sections.menu")); ?></span>
                </div>
                <div class="sec-body">
                    <div class="menu-tabs" id="menuTabs">
                        <?php foreach ($menuTabs as $tab): ?>
                            <button class="mtab<?= $tab === $activeTab ? " active" : ""; ?>" onclick="switchTab(<?= js_str($tab); ?>)"><?= e($tab); ?></button>
                        <?php endforeach; ?>
                    </div>
                    <div class="menu-grid" id="menuGrid">
                        <?php foreach ($activeItems as $item): ?>
                            <?php
                            $price = (int) ($item["price"] ?? 0);
                            $id = (int) ($item["id"] ?? 0);
                            $tagColor = $item["tc"] ?? "#3a4a2e";
                            $bg = $item["bg"] ?? "";
                            if (!$bg) {
                                $fallback = data_get($data, "assets.fallbackImage", "");
                                $bg = $fallback ? "url('" . e($fallback) . "')" : "";
                            }
                            $veg = !empty($item["veg"]);
                            ?>
                            <div class="menu-card">
                                <div class="menu-img">
                                    <div class="menu-img-ph" style="background:<?= e($bg); ?>;height:100%;display:flex;align-items:center;justify-content:center;"></div>
                                    <?php if (!empty($item["tag"])): ?>
                                        <span class="mbadge" style="background:<?= e($tagColor); ?>"><?= e($item["tag"]); ?></span>
                                    <?php endif; ?>
                                    <div class="diet <?= $veg ? "veg-d" : "nonveg-d"; ?>"><?= $veg ? "V" : "N"; ?></div>
                                </div>
                                <div class="menu-body">
                                    <div class="menu-name"><?= e($item["name"] ?? ""); ?></div>
                                    <div class="menu-desc"><?= e($item["desc"] ?? ""); ?></div>
                                    <div class="menu-footer">
                                        <div>
                                            <span class="mprice">&#8377;<?= e($price); ?></span>
                                            <?php if (!empty($item["op"])): ?>
                                                <span class="mold">&#8377;<?= e($item["op"]); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="qty-ctrl">
                                            <button class="qty-btn" onclick="chQty(<?= e($id); ?>,-1,<?= e($price); ?>,<?= js_str($item["name"] ?? ""); ?>)"><svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12" /></svg></button>
                                            <span class="qty-num" id="qty-<?= e($id); ?>">0</span>
                                            <button class="qty-btn" onclick="chQty(<?= e($id); ?>,1,<?= e($price); ?>,<?= js_str($item["name"] ?? ""); ?>)"><svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon gold-ic">
                        <svg class="ic" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <polyline points="21 15 16 10 5 21" />
                        </svg>
                    </div>
                    <span class="sec-title" id="secGalleryTitle"><?= e(data_get($data, "sections.gallery")); ?></span>
                </div>
                <div class="sec-body" style="padding-top: 0.5rem; padding-bottom: 0.5rem">
                    <div class="gallery-row" id="galleryRow">
                        <?php foreach (data_list($data, "gallery") as $item): ?>
                            <?php $image = $item["image"] ?? data_get($data, "assets.fallbackImage", ""); ?>
                            <div>
                                <div class="gal-item">
                                    <img src="<?= e($image); ?>" alt="" style="width:100%;height:100%;object-fit:cover;display:block;" />
                                </div>
                                <div class="gal-cap"><?= e($item["caption"] ?? ""); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

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
                    <span class="sec-title" id="secReserveTitle"><?= e(data_get($data, "sections.reserve")); ?></span>
                </div>
                <div class="sec-body">
                    <div id="reservationForm">
                        <div class="bf-row">
                            <div class="bf-grp">
                                <label class="bf-lbl" id="rLabelName"><?= e(data_get($data, "reservation.labels.name")); ?></label>
                                <input class="bf-inp" type="text" id="rName" placeholder="<?= e(data_get($data, "reservation.placeholders.name")); ?>" />
                            </div>
                            <div class="bf-grp">
                                <label class="bf-lbl" id="rLabelPhone"><?= e(data_get($data, "reservation.labels.phone")); ?></label>
                                <input class="bf-inp" type="tel" id="rPhone" placeholder="<?= e(data_get($data, "reservation.placeholders.phone")); ?>" />
                            </div>
                        </div>
                        <div class="bf-row">
                            <div class="bf-grp">
                                <label class="bf-lbl" id="rLabelDate"><?= e(data_get($data, "reservation.labels.date")); ?></label>
                                <input class="bf-inp" type="date" id="rDate" />
                            </div>
                            <div class="bf-grp">
                                <label class="bf-lbl" id="rLabelTime"><?= e(data_get($data, "reservation.labels.time")); ?></label>
                                <select class="bf-inp" id="rTime">
                                    <?php foreach (data_list($data, "reservation.times") as $option): ?>
                                        <option value="<?= e($option["value"] ?? $option["label"] ?? ""); ?>"<?= !empty($option["selected"]) ? " selected=\"selected\"" : ""; ?>><?= e($option["label"] ?? $option["value"] ?? ""); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="bf-row">
                            <div class="bf-grp">
                                <label class="bf-lbl" id="rLabelGuests"><?= e(data_get($data, "reservation.labels.guests")); ?></label>
                                <select class="bf-inp" id="rGuests">
                                    <?php foreach (data_list($data, "reservation.guests") as $option): ?>
                                        <option value="<?= e($option["value"] ?? $option["label"] ?? ""); ?>"<?= !empty($option["selected"]) ? " selected=\"selected\"" : ""; ?>><?= e($option["label"] ?? $option["value"] ?? ""); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="bf-grp">
                                <label class="bf-lbl" id="rLabelOccasion"><?= e(data_get($data, "reservation.labels.occasion")); ?></label>
                                <select class="bf-inp" id="rOccasion">
                                    <?php foreach (data_list($data, "reservation.occasions") as $option): ?>
                                        <option value="<?= e($option["value"] ?? $option["label"] ?? ""); ?>"<?= !empty($option["selected"]) ? " selected=\"selected\"" : ""; ?>><?= e($option["label"] ?? $option["value"] ?? ""); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="bf-grp">
                            <label class="bf-lbl" id="rLabelNote"><?= e(data_get($data, "reservation.labels.note")); ?></label>
                            <textarea class="bf-inp" id="rNote" placeholder="<?= e(data_get($data, "reservation.placeholders.note")); ?>"></textarea>
                        </div>
                        <button class="bf-btn" onclick="submitReservation()">
                            <svg class="ic-sm" viewBox="0 0 24 24">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.5 0 0 1 8 8v.5z" />
                            </svg>
                            <span id="rConfirmLabel"><?= e(data_get($data, "reservation.confirmLabel")); ?></span>
                        </button>
                    </div>
                    <div class="res-done" id="reservationSuccess">
                        <div class="res-done-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" width="24" height="24">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <div class="res-done-title" id="rSuccessTitle"><?= e(data_get($data, "reservation.successTitle")); ?></div>
                        <div class="res-done-msg" id="rSuccessMsg"><?= e(data_get($data, "reservation.successMessage")); ?></div>
                        <button class="bf-btn" style="margin-top: 1rem" onclick="resetReservation()">
                            <span id="rSuccessBtnLabel"><?= e(data_get($data, "reservation.successButton")); ?></span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon gold-ic">
                        <svg class="ic" viewBox="0 0 24 24">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                        </svg>
                    </div>
                    <span class="sec-title" id="secOffersTitle"><?= e(data_get($data, "sections.offers")); ?></span>
                </div>
                <div class="sec-body">
                    <div class="offers-list" id="offersList">
                        <?php foreach (data_list($data, "offers") as $item): ?>
                            <?php $iconKey = "offer_" . ($item["icon"] ?? ""); ?>
                            <div class="offer-card">
                                <div class="offer-icon" style="background:<?= e($item["bg"] ?? "#fff3e0"); ?>"><?= getIcon($iconKey); ?></div>
                                <div>
                                    <div class="offer-title"><?= e($item["title"] ?? ""); ?></div>
                                    <div class="offer-desc"><?= e($item["desc"] ?? ""); ?></div>
                                    <span class="offer-tag"><?= e($item["tag"] ?? ""); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg class="ic" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    <span class="sec-title" id="secHoursTitle"><?= e(data_get($data, "sections.hours")); ?></span>
                </div>
                <div class="sec-body">
                    <div class="today-pill">
                        <svg viewBox="0 0 24 24" width="13" height="13" stroke="#2e7d32" fill="none" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        <span id="todayPillLabel"><?= e(data_get($data, "hours.todayLabel")); ?></span>
                    </div>
                    <table class="hours-table">
                        <tbody id="hoursRows">
                            <?php foreach (data_list($data, "hours.rows") as $idx => $row): ?>
                                <tr class="<?= $idx === 0 ? "h-today" : ""; ?>">
                                    <td class="h-day"><?= e($row["day"] ?? ""); ?></td>
                                    <td class="h-time"><?= e($row["time"] ?? ""); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="kitchen-note" id="kitchenNote"><?= e(data_get($data, "hours.kitchenNote")); ?></div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon terra">
                        <svg class="ic" viewBox="0 0 24 24">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                    </div>
                    <span class="sec-title" id="secLocationTitle"><?= e(data_get($data, "sections.location")); ?></span>
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
                            <strong id="locationName"><?= e(data_get($data, "location.name")); ?></strong>
                            <span id="locationAddress"><?= e(data_get($data, "location.address")); ?></span>
                            <div>
                                <a href="#" class="map-btn" onclick="return (openMaps(), !1);">
                                    <svg class="ic-sm" viewBox="0 0 24 24" fill="none">
                                        <polygon points="3 11 22 2 13 21 11 13 3 11" />
                                    </svg>
                                    <span id="mapBtnLabel"><?= e(data_get($data, "location.mapLabel")); ?></span>
                                </a>
                            </div>
                        </div>
                    </a>
                    <div class="transport-grid" id="transportGrid">
                        <?php foreach (data_list($data, "location.transport") as $item): ?>
                            <?php $iconKey = "transport_" . ($item["icon"] ?? ""); ?>
                            <div class="t-item">
                                <span style="display:flex;color:<?= e($item["stroke"] ?? "#1565c0"); ?>"><?= getIcon($iconKey); ?></span>
                                <div>
                                    <div class="t-label"><?= e($item["label"] ?? ""); ?></div>
                                    <div class="t-val"><?= e($item["value"] ?? ""); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg class="ic" viewBox="0 0 24 24">
                            <path d="M17 2H7a5 5 0 0 0-5 5v10a5 5 0 0 0 5 5h10a5 5 0 0 0 5-5V7a5 5 0 0 0-5-5z" />
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
                        </svg>
                    </div>
                    <span class="sec-title" id="secFollowTitle"><?= e(data_get($data, "sections.follow")); ?></span>
                </div>
                <div class="sec-body">
                    <div class="social-list" id="socialList">
                        <?php foreach (data_list($data, "social") as $item): ?>
                            <?php
                            $type = $item["type"] ?? "instagram";
                            $iconKey = "social_" . $type;
                            $iconClass = $socialIconClasses[$type] ?? $socialIconClasses["instagram"];
                            $action = "";
                            if (($item["action"] ?? "") === "wa") {
                                $action = "openWA()";
                            } elseif (($item["action"] ?? "") === "url" && !empty($item["url"])) {
                                $action = "window.open(" . js_str($item["url"]) . ", '_blank')";
                            }
                            ?>
                            <div class="soc-item"<?= $action ? " onclick=\"" . e($action) . "\"" : ""; ?>>
                                <div class="s-ico <?= e($iconClass); ?>"><?= getIcon($iconKey); ?></div>
                                <div>
                                    <div class="s-name"><?= e($item["name"] ?? ""); ?></div>
                                    <div class="s-val"><?= e($item["value"] ?? ""); ?></div>
                                </div>
                                <div class="s-arrow">
                                    <svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2">
                                        <polyline points="9 18 15 12 9 6" />
                                    </svg>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg class="ic" viewBox="0 0 24 24">
                            <rect x="1" y="4" width="22" height="16" rx="2" />
                            <line x1="1" y1="10" x2="23" y2="10" />
                        </svg>
                    </div>
                    <span class="sec-title" id="secPaymentTitle"><?= e(data_get($data, "sections.payments")); ?></span>
                </div>
                <div class="sec-body">
                    <div class="pay-grid" id="payGrid">
                        <?php foreach (data_list($data, "payments") as $item): ?>
                            <?php $iconKey = "payment_" . ($item["icon"] ?? ""); ?>
                            <div class="pay-item">
                                <div class="pay-icon">
                                    <span style="display:flex;color:<?= e($item["stroke"] ?? "#1565c0"); ?>"><?= getIcon($iconKey); ?></span>
                                </div>
                                <div>
                                    <div class="pay-name"><?= e($item["name"] ?? ""); ?></div>
                                    <div class="pay-sub"><?= e($item["sub"] ?? ""); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

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
                    <span class="sec-title" id="secQrTitle"><?= e(data_get($data, "sections.qr")); ?></span>
                </div>
                <div class="sec-body">
                    <div class="qr-inner">
                        <div id="qrHelpText" style="font-size: 0.8rem; color: var(--muted); margin-bottom: 0.5rem"><?= e(data_get($data, "qr.helpText")); ?></div>
                        <div id="vcardQR"></div>
                        <div class="qr-actions">
                            <button class="qr-btn" onclick="downloadQR()">
                                <svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="7 10 12 15 17 10" />
                                    <line x1="12" y1="15" x2="12" y2="3" />
                                </svg>
                                <span id="qrDownloadLabel"><?= e(data_get($data, "qr.downloadLabel")); ?></span>
                            </button>
                            <button class="qr-btn" onclick="saveContact()">
                                <svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                                    <polyline points="17 21 17 13 7 13 7 21" />
                                    <polyline points="7 3 7 8 15 8" />
                                </svg>
                                <span id="qrSaveLabel"><?= e(data_get($data, "qr.saveLabel")); ?></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="vcard-footer">
                <p id="footerLine1"><?= e(data_get($data, "footer.year")); ?> <strong><?= e(data_get($data, "footer.brand")); ?></strong> <?= e(data_get($data, "footer.rights")); ?></p>
                <p id="footerLine2" style="margin-top: 0.3rem; font-size: 0.68rem"><?= e(data_get($data, "footer.poweredBy")); ?> <strong><?= e(data_get($data, "footer.poweredBrand")); ?></strong></p>
            </div>

            <div class="float-bar">
                <button class="fab cfab" onclick="callUs()">
                    <svg class="ic-lg" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.13 6.13l.96-.96a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.73 16.92z" />
                    </svg>
                    <span id="floatCallLabel"><?= e(data_get($data, "floatBar.call")); ?></span>
                </button>
                <button class="fab rfab" onclick="openReserveModal()">
                    <svg class="ic-lg" viewBox="0 0 24 24" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    <span id="floatReserveLabel"><?= e(data_get($data, "floatBar.reserve")); ?></span>
                </button>
                <button class="fab wfab" onclick="openWA()">
                    <svg class="ic-lg" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                    </svg>
                    <span id="floatWaLabel"><?= e(data_get($data, "floatBar.whatsapp")); ?></span>
                </button>
                <div class="fab-wrap" onclick="openCart()">
                    <div class="cart-badge" id="cartBadge"></div>
                    <svg class="ic-lg" viewBox="0 0 24 24" stroke="var(--terracotta)" stroke-width="2" fill="none">
                        <circle cx="9" cy="21" r="1" />
                        <circle cx="20" cy="21" r="1" />
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                    </svg>
                    <span id="floatOrderLabel" style="font-size: 0.66rem; font-weight: 700; color: var(--terracotta)"><?= e(data_get($data, "floatBar.order")); ?></span>
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
                            <span id="cartTitle"><?= e(data_get($data, "cart.title")); ?></span>
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
                        <div class="res-header-title" id="reserveModalTitle"><?= e(data_get($data, "reserveModal.title")); ?></div>
                        <button class="cart-close" onclick="closeReserveModal()">
                            <svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2.5">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </button>
                    </div>
                    <div class="bf-row">
                        <div class="bf-grp">
                            <label class="bf-lbl" id="r2LabelName"><?= e(data_get($data, "reserveModal.labels.name")); ?></label>
                            <input class="bf-inp" type="text" id="rName2" placeholder="<?= e(data_get($data, "reserveModal.placeholders.name")); ?>" />
                        </div>
                        <div class="bf-grp">
                            <label class="bf-lbl" id="r2LabelPhone"><?= e(data_get($data, "reserveModal.labels.phone")); ?></label>
                            <input class="bf-inp" type="tel" id="rPhone2" placeholder="<?= e(data_get($data, "reserveModal.placeholders.phone")); ?>" />
                        </div>
                    </div>
                    <div class="bf-row">
                        <div class="bf-grp">
                            <label class="bf-lbl" id="r2LabelDate"><?= e(data_get($data, "reserveModal.labels.date")); ?></label>
                            <input class="bf-inp" type="date" id="rDate2" />
                        </div>
                        <div class="bf-grp">
                            <label class="bf-lbl" id="r2LabelTime"><?= e(data_get($data, "reserveModal.labels.time")); ?></label>
                            <select class="bf-inp" id="rTime2">
                                <?php foreach (data_list($data, "reserveModal.times") as $option): ?>
                                    <option value="<?= e($option["value"] ?? $option["label"] ?? ""); ?>"<?= !empty($option["selected"]) ? " selected=\"selected\"" : ""; ?>><?= e($option["label"] ?? $option["value"] ?? ""); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="bf-grp">
                        <label class="bf-lbl" id="r2LabelGuests"><?= e(data_get($data, "reserveModal.labels.guests")); ?></label>
                        <select class="bf-inp" id="rGuests2">
                            <?php foreach (data_list($data, "reserveModal.guests") as $option): ?>
                                <option value="<?= e($option["value"] ?? $option["label"] ?? ""); ?>"<?= !empty($option["selected"]) ? " selected=\"selected\"" : ""; ?>><?= e($option["label"] ?? $option["value"] ?? ""); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button class="bf-btn" onclick="submitReservationModal()">
                        <svg class="ic-sm" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        <span id="r2ConfirmLabel"><?= e(data_get($data, "reserveModal.confirmLabel")); ?></span>
                    </button>
                </div>
            </div>

            <div class="modal-overlay" id="shareModal" onclick="closeShare(event)">
                <div class="modal-box">
                    <div class="modal-title" id="shareTitle"><?= e(data_get($data, "share.title")); ?></div>
                    <div class="share-options">
                        <div class="share-opt" onclick="shareWA()">
                            <svg viewBox="0 0 24 24" stroke="#128c7e" stroke-width="2">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                            </svg>
                            <span id="shareWaLabel"><?= e(data_get($data, "share.whatsapp")); ?></span>
                        </div>
                        <div class="share-opt" onclick="copyLink()">
                            <svg viewBox="0 0 24 24" stroke="#555" stroke-width="2">
                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                            </svg>
                            <span id="shareCopyLabel"><?= e(data_get($data, "share.copy")); ?></span>
                        </div>
                        <div class="share-opt" onclick="shareFB()">
                            <svg viewBox="0 0 24 24" stroke="#1877f2" stroke-width="2">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                            </svg>
                            <span id="shareFbLabel"><?= e(data_get($data, "share.facebook")); ?></span>
                        </div>
                        <div class="share-opt" onclick="shareNative()">
                            <svg viewBox="0 0 24 24" stroke="#555" stroke-width="2">
                                <circle cx="18" cy="5" r="3" />
                                <circle cx="6" cy="12" r="3" />
                                <circle cx="18" cy="19" r="3" />
                                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                            </svg>
                            <span id="shareMoreLabel"><?= e(data_get($data, "share.more")); ?></span>
                        </div>
                    </div>
                    <button class="modal-close-btn" onclick="closeShareModal()">
                        <span id="shareCancelLabel"><?= e(data_get($data, "share.cancel")); ?></span>
                    </button>
                </div>
            </div>

            <div class="toast" id="toast"></div>
        </main>
        <script>
            window.__APP__ = <?= json_encode($data ?: [], JSON_UNESCAPED_SLASHES); ?>;
        </script>
        <script src="script.js"></script>
    </body>
</html>