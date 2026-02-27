<?php
declare(strict_types=1);

// Load from default.json (template defaults)
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

$socialIconClasses = [
    "whatsapp" => "ic-wa",
    "facebook" => "ic-fb",
    "instagram" => "ic-ig",
    "youtube" => "ic-yt",
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
        <main id="app-root" aria-live="polite" style="min-height: 100vh">
            <div class="banner" id="bannerRoot"<?= $bannerImage ? " style=\"background:url('" . e($bannerImage) . "') center/cover no-repeat\"" : ""; ?>>
                <div class="banner-pattern">
                    <svg viewBox="0 0 480 200" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
                        <defs>
                            <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#fff" stroke-width=".8" />
                            </pattern>
                        </defs>
                        <rect width="480" height="200" fill="url(#grid)" />
                    </svg>
                </div>
                <div class="banner-icons" id="bannerIcons">
                    <?php foreach (data_list($data, "banner.icons") as $item): ?>
                        <?php $iconKey = "banner_" . ($item["icon"] ?? ""); ?>
                        <div class="banner-icon-item">
                            <?= getIcon($iconKey); ?>
                            <span><?= e($item["label"] ?? ""); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="banner-shelf"></div>
                <div class="banner-top-bar">
                    <button class="share-btn" onclick="openShare()">
                        <svg class="ic-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <circle cx="18" cy="5" r="3" />
                            <circle cx="6" cy="12" r="3" />
                            <circle cx="18" cy="19" r="3" />
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                        </svg>
                        <span id="topShareLabel"><?= e(data_get($data, "banner.topBar.share")); ?></span>
                    </button>
                    <button class="save-btn-top" onclick="saveContact()">
                        <svg class="ic-sm" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        <span id="topSaveLabel"><?= e(data_get($data, "banner.topBar.saveCard")); ?></span>
                    </button>
                </div>
            </div>

            <div class="profile-card">
                <div class="profile-avatar-wrap">
                    <div class="profile-avatar">
                        <img id="profileImage" src="<?= e($profileImage); ?>" alt="<?= e($profileAlt); ?>" style="width: 100%; height: 100%; object-fit: cover" />
                    </div>
                </div>
                <div class="profile-name" id="profileName"><?= e(data_get($data, "profile.name")); ?></div>
                <div class="profile-role" id="profileRole"><?= e(data_get($data, "profile.role")); ?></div>
                <div class="profile-bio" id="profileBio"><?= e(data_get($data, "profile.bio")); ?></div>
                <div class="profile-action-btns">
                    <button class="pab call" onclick="callShop()">
                        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.13 6.13l.96-.96a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                        </svg>
                        <span id="actionCallLabel"><?= e(data_get($data, "profile.actions.call")); ?></span>
                    </button>
                    <button class="pab whatsapp" onclick="openWA()">
                        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                        </svg>
                        <span id="actionWaLabel"><?= e(data_get($data, "profile.actions.whatsapp")); ?></span>
                    </button>
                    <button class="pab save" onclick="saveContact()">
                        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        <span id="actionSaveLabel"><?= e(data_get($data, "profile.actions.save")); ?></span>
                    </button>
                    <button class="pab email" onclick="emailShop()">
                        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                        <span id="actionEmailLabel"><?= e(data_get($data, "profile.actions.email")); ?></span>
                    </button>
                    <button class="pab direction" onclick="openMaps()">
                        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="#9d174d" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                        <span id="actionDirectionLabel"><?= e(data_get($data, "profile.actions.directions")); ?></span>
                    </button>
                    <button class="pab share" onclick="openShare()">
                        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="#6d28d9" stroke-width="2">
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

            <div class="badge-strip" id="badgeStrip">
                <?php foreach (data_list($data, "badges") as $item): ?>
                    <?php $badgeKey = "badge_" . ($item["icon"] ?? ""); ?>
                    <div class="badge-item">
                        <svg viewBox="0 0 24 24"><?= getIcon($badgeKey); ?></svg>
                        <?= e($item["text"] ?? ""); ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="section-space"></div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sectionLocationTitle"><?= e(data_get($data, "sections.location.title")); ?></div>
                </div>
                <div class="sec-body">
                    <a class="address-link" href="#" onclick="return (openMaps(), !1);">
                        <div class="addr-icon-wrap">
                            <svg class="ic" viewBox="0 0 24 24" stroke="#9d174d" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                        </div>
                        <div class="addr-text">
                            <strong id="locationLine1"><?= e(data_get($data, "sections.location.addressLine1")); ?></strong>
                            <span id="locationLine2"><?= e(data_get($data, "sections.location.addressLine2")); ?></span>
                            <span class="map-btn">
                                <svg style="stroke: #fff; fill: none; width: 11px; height: 11px; stroke-width: 2" viewBox="0 0 24 24">
                                    <polygon points="3 11 22 2 13 21 11 13 3 11" />
                                </svg>
                                <span id="locationMapLabel"><?= e(data_get($data, "sections.location.mapLabel")); ?></span>
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="2" y1="12" x2="22" y2="12" />
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sectionSocialTitle"><?= e(data_get($data, "sections.social.title")); ?></div>
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
                            <div class="social-item"<?= $action ? " onclick=\"" . e($action) . "\"" : ""; ?>>
                                <div class="s-ico <?= e($iconClass); ?>"><?= getIcon($iconKey); ?></div>
                                <div>
                                    <div class="s-name"><?= e($item["name"] ?? ""); ?></div>
                                    <div class="s-val"><?= e($item["value"] ?? ""); ?></div>
                                </div>
                                <div class="s-arrow">
                                    <svg viewBox="0 0 24 24" stroke-width="2.5" stroke="#bbb" fill="none" width="13" height="13">
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
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7" rx="1" />
                            <rect x="14" y="3" width="7" height="7" rx="1" />
                            <rect x="3" y="14" width="7" height="7" rx="1" />
                            <rect x="14" y="14" width="7" height="7" rx="1" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sectionCategoriesTitle"><?= e(data_get($data, "sections.categories.title")); ?></div>
                </div>
                <div class="sec-body">
                    <div class="cat-grid" id="categoriesGrid">
                        <?php foreach (data_list($data, "categories") as $item): ?>
                            <?php $query = $item["query"] ?? $item["name"] ?? ""; ?>
                            <div class="cat-card" onclick="enquireWA(<?= js_str($query); ?>)">
                                <div class="cat-name"><?= e($item["name"] ?? ""); ?></div>
                                <div class="cat-count"><?= e($item["count"] ?? ""); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <circle cx="9" cy="21" r="1" />
                            <circle cx="20" cy="21" r="1" />
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sectionPicksTitle"><?= e(data_get($data, "sections.picks.title")); ?></div>
                </div>
                <div class="sec-body">
                    <div class="products-grid" id="productsGrid">
                        <?php foreach (data_list($data, "products") as $item): ?>
                            <?php
                            $bg = $item["bg"] ?? "";
                            if (!$bg) {
                                $fallback = data_get($data, "assets.fallbackImage", "");
                                $bg = $fallback ? "url('" . e($fallback) . "')" : "";
                            }
                            $id = (int) ($item["id"] ?? 0);
                            ?>
                            <div class="prod-card">
                                <div class="prod-img">
                                    <div class="prod-img-placeholder" style="background:<?= e($bg); ?>;height:100%"></div>
                                    <?php if (!empty($item["tag"])): ?>
                                        <span class="prod-tag" style="background:<?= e($item["tagColor"] ?? "#dc2626"); ?>"><?= e($item["tag"]); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="prod-body">
                                    <div class="prod-name"><?= e($item["name"] ?? ""); ?></div>
                                    <div class="prod-desc"><?= e($item["desc"] ?? ""); ?></div>
                                    <div class="prod-footer">
                                        <div>
                                            <span class="prod-price">&#8377;<?= e($item["price"] ?? 0); ?></span>
                                            <?php if (!empty($item["oldPrice"])): ?>
                                                <span class="prod-old">&#8377;<?= e($item["oldPrice"]); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="qty-ctrl">
                                            <button class="qty-btn" onclick="changeQty(<?= e($id); ?>,-1)">
                                                <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12" /></svg>
                                            </button>
                                            <span class="qty-num" id="qty-<?= e($id); ?>">0</span>
                                            <button class="qty-btn" onclick="changeQty(<?= e($id); ?>,1)">
                                                <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                                            </button>
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
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sectionDealsTitle"><?= e(data_get($data, "sections.deals.title")); ?></div>
                </div>
                <div class="sec-body">
                    <div class="deals-list" id="dealsList">
                        <?php foreach (data_list($data, "deals") as $item): ?>
                            <?php
                            $actionType = $item["action"]["type"] ?? "";
                            $actionLabel = $item["action"]["label"] ?? "";
                            $actionValue = $item["action"]["value"] ?? ($item["name"] ?? "");
                            $action = $actionType === "wa"
                                ? "openWA()"
                                : "enquireWA(" . js_str($actionValue) . ")";
                            ?>
                            <div class="deal-item">
                                <div class="deal-badge"><?= $item["badge"] ?? ""; ?></div>
                                <div class="deal-info">
                                    <div class="deal-name"><?= e($item["name"] ?? ""); ?></div>
                                    <div class="deal-desc"><?= e($item["desc"] ?? ""); ?></div>
                                </div>
                                <button class="deal-cta" onclick="<?= e($action); ?>"><?= e($actionLabel); ?></button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <polyline points="21 15 16 10 5 21" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sectionGalleryTitle"><?= e(data_get($data, "sections.gallery.title")); ?></div>
                </div>
                <div class="sec-body">
                    <div class="gallery-grid" id="galleryGrid">
                        <?php foreach (data_list($data, "gallery") as $item): ?>
                            <?php
                                $bg = $item["bg"] ?? "";
                                $imageUrl = $bg;
                                if (preg_match('/url\([\'\"]?(.*?)[\'\"]?\)/i', $bg, $matches)) {
                                    $imageUrl = $matches[1];
                                }
                            ?>
                            <div class="g-item">
                                <?php if (!empty($imageUrl)): ?>
                                    <img src="<?= e($imageUrl); ?>" alt="" style="width:100%;height:100%;object-fit:cover;display:block;" />
                                <?php endif; ?>
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
                    <div class="sec-title" id="sectionHoursTitle"><?= e(data_get($data, "sections.hours.title")); ?></div>
                </div>
                <div class="sec-body">
                    <div class="today-badge">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        <span id="todayBadgeText"><?= e(data_get($data, "sections.hours.todayLabel")); ?></span>
                    </div>
                    <table class="hours-table">
                        <tbody id="hoursRows">
                            <?php foreach (data_list($data, "sections.hours.rows") as $row): ?>
                                <?php $open = ($row["status"] ?? "") !== "closed"; ?>
                                <tr class="<?= $open ? "open-row" : "closed-row"; ?>">
                                    <td class="day"><?= e($row["day"] ?? ""); ?></td>
                                    <td class="time<?= $open ? "" : " closed"; ?>"><?= e($row["time"] ?? ""); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="suggest-link" onclick="emailShop()">
                        <svg viewBox="0 0 24 24" stroke-width="2" fill="none">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        <span id="hoursSuggestLabel"><?= e(data_get($data, "sections.hours.suggestLabel")); ?></span>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="3" width="5" height="5" />
                            <rect x="16" y="3" width="5" height="5" />
                            <rect x="3" y="16" width="5" height="5" />
                            <path d="M21 16h-3a2 2 0 0 0-2 2v3" />
                            <path d="M21 21v.01" />
                            <path d="M12 7v3a2 2 0 0 1-2 2H7" />
                            <path d="M3 12h.01" />
                            <path d="M12 3h.01" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sectionQrTitle"><?= e(data_get($data, "sections.qr.title")); ?></div>
                </div>
                <div class="sec-body">
                    <div class="qr-card-inner">
                        <p id="qrHelpText" style="font-size: 0.77rem; color: var(--muted); margin-bottom: 0.2rem"><?= e(data_get($data, "sections.qr.helpText")); ?></p>
                        <div id="vcardQR"></div>
                        <div class="qr-actions">
                            <button class="qr-btn" onclick="downloadQR()">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="7 10 12 15 17 10" />
                                    <line x1="12" y1="15" x2="12" y2="3" />
                                </svg>
                                <span id="qrDownloadLabel"><?= e(data_get($data, "sections.qr.download")); ?></span>
                            </button>
                            <button class="qr-btn" onclick="copyLink()">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                                </svg>
                                <span id="qrCopyLabel"><?= e(data_get($data, "sections.qr.copy")); ?></span>
                            </button>
                        </div>
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
                    <div class="sec-title" id="sectionPaymentsTitle"><?= e(data_get($data, "sections.payments.title")); ?></div>
                </div>
                <div class="sec-body">
                    <div class="payment-list" id="paymentList">
                        <?php foreach (data_list($data, "payments") as $item): ?>
                            <?php
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
                            ?>
                            <div class="pay-item">
                                <div class="pay-icon-wrap">
                                    <svg viewBox="0 0 24 24" stroke="<?= e($item["stroke"] ?? "#15803d"); ?>" stroke-width="2">
                                        <?= getIcon($iconKey); ?>
                                    </svg>
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
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                    </div>
                    <div class="sec-title" id="sectionContactTitle"><?= e(data_get($data, "sections.contact.title")); ?></div>
                </div>
                <div class="sec-body">
                    <div id="contactForm">
                        <div class="bf-row">
                            <div class="bf-group">
                                <label class="bf-label" id="contactLabelName"><?= e(data_get($data, "sections.contact.form.nameLabel")); ?></label>
                                <input class="bf-input" id="cName" placeholder="<?= e(data_get($data, "sections.contact.form.namePlaceholder")); ?>" />
                            </div>
                            <div class="bf-group">
                                <label class="bf-label" id="contactLabelPhone"><?= e(data_get($data, "sections.contact.form.phoneLabel")); ?></label>
                                <input class="bf-input" id="cPhone" type="tel" placeholder="<?= e(data_get($data, "sections.contact.form.phonePlaceholder")); ?>" />
                            </div>
                        </div>
                        <div class="bf-group">
                            <label class="bf-label" id="contactLabelEmail"><?= e(data_get($data, "sections.contact.form.emailLabel")); ?></label>
                            <input class="bf-input" id="cEmail" placeholder="<?= e(data_get($data, "sections.contact.form.emailPlaceholder")); ?>" />
                        </div>
                        <div class="bf-group">
                            <label class="bf-label" id="contactLabelMessage"><?= e(data_get($data, "sections.contact.form.messageLabel")); ?></label>
                            <textarea class="bf-input" id="cMsg" placeholder="<?= e(data_get($data, "sections.contact.form.messagePlaceholder")); ?>"></textarea>
                        </div>
                        <button class="cf-submit" onclick="submitContact()">
                            <svg viewBox="0 0 24 24" fill="none" width="16" height="16">
                                <line x1="22" y1="2" x2="11" y2="13" />
                                <polygon points="22 2 15 22 11 13 2 9 22 2" />
                            </svg>
                            <span id="contactSubmitLabel"><?= e(data_get($data, "sections.contact.form.submit")); ?></span>
                        </button>
                    </div>
                    <div id="contactSuccess" style="display: none; text-align: center; padding: 1.6rem 1rem">
                        <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="1.8" style="display: block; margin: 0 auto 0.75rem">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                        <div id="contactSuccessTitle" style="font-size: 0.96rem; font-weight: 800; color: var(--text); margin-bottom: 0.38rem"><?= e(data_get($data, "sections.contact.success.title")); ?></div>
                        <div id="contactSuccessText" style="font-size: 0.78rem; color: var(--muted); margin-bottom: 0.9rem"><?= e(data_get($data, "sections.contact.success.text")); ?></div>
                        <button class="cf-submit" onclick="resetContact()">
                            <svg viewBox="0 0 24 24" fill="none" width="15" height="15" stroke="#fff" stroke-width="2">
                                <polyline points="1 4 1 10 7 10" />
                                <path d="M3.51 15a9 9 0 1 0 .49-3.51" />
                            </svg>
                            <span id="contactAnotherLabel"><?= e(data_get($data, "sections.contact.success.button")); ?></span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="vcard-footer">
                <p id="footerLine1"><?= e(data_get($data, "footer.year")); ?> <strong><?= e(data_get($data, "footer.brand")); ?></strong> <?= e(data_get($data, "footer.rights")); ?></p>
                <p id="footerLine2" style="margin-top: 0.3rem; font-size: 0.66rem"><?= e(data_get($data, "footer.poweredBy")); ?> <strong><?= e(data_get($data, "footer.poweredBrand")); ?></strong></p>
            </div>

            <div class="float-bar">
                <button class="fab call-fab" onclick="callShop()">
                    <svg class="ic-lg" viewBox="0 0 24 24" stroke="#15803d" fill="none" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.13 6.13l.96-.96a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                    </svg>
                    <span id="floatCallLabel"><?= e(data_get($data, "floatBar.call")); ?></span>
                </button>
                <button class="fab save-fab" onclick="saveContact()">
                    <svg class="ic-lg" viewBox="0 0 24 24" stroke="#ea580c" fill="none" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                    <span id="floatSaveLabel"><?= e(data_get($data, "floatBar.saveCard")); ?></span>
                </button>
                <button class="fab wa-fab" onclick="openWA()">
                    <svg class="ic-lg" viewBox="0 0 24 24" stroke="#0f9e5e" fill="none" stroke-width="2">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                    </svg>
                    <span id="floatWaLabel"><?= e(data_get($data, "floatBar.whatsapp")); ?></span>
                </button>
                <div class="fab cart-fab fab-wrap" onclick="openCart()">
                    <div class="cart-badge" id="cartBadge"></div>
                    <svg class="ic-lg" viewBox="0 0 24 24" stroke="var(--navy)" stroke-width="2" fill="none">
                        <circle cx="9" cy="21" r="1" />
                        <circle cx="20" cy="21" r="1" />
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                    </svg>
                    <span id="floatCartLabel" style="font-size: 0.65rem; font-weight: 800; color: var(--navy)"><?= e(data_get($data, "floatBar.cart")); ?></span>
                </div>
            </div>

            <div class="cart-overlay" id="cartOverlay" onclick="closeCartOutside(event)">
                <div class="cart-box">
                    <div class="cart-header">
                        <div class="cart-title">
                            <svg viewBox="0 0 24 24" width="17" height="17" stroke="var(--navy)" fill="none" stroke-width="2">
                                <circle cx="9" cy="21" r="1" />
                                <circle cx="20" cy="21" r="1" />
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                            </svg>
                            <span id="cartTitle"><?= e(data_get($data, "sections.cart.title")); ?></span>
                        </div>
                        <button class="cart-close" onclick="closeCart()">
                            <svg viewBox="0 0 24 24" width="13" height="13" stroke-width="2.5" fill="none" stroke="var(--muted)">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </button>
                    </div>
                    <div id="cartBody"></div>
                </div>
            </div>

            <div class="modal-overlay" id="shareModal" onclick="closeShare(event)">
                <div class="modal-box">
                    <div class="modal-title" id="shareTitle"><?= e(data_get($data, "sections.share.title")); ?></div>
                    <div class="share-options">
                        <div class="share-opt" onclick="shareWA()">
                            <svg viewBox="0 0 24 24" stroke="#15803d" stroke-width="2">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                            </svg>
                            <span id="shareWaLabel"><?= e(data_get($data, "sections.share.whatsapp")); ?></span>
                        </div>
                        <div class="share-opt" onclick="copyLink()">
                            <svg viewBox="0 0 24 24" stroke="#555" stroke-width="2">
                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                            </svg>
                            <span id="shareCopyLabel"><?= e(data_get($data, "sections.share.copy")); ?></span>
                        </div>
                        <div class="share-opt" onclick="shareFB()">
                            <svg viewBox="0 0 24 24" stroke="#1877f2" stroke-width="2">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                            </svg>
                            <span id="shareFbLabel"><?= e(data_get($data, "sections.share.facebook")); ?></span>
                        </div>
                        <div class="share-opt" onclick="shareNative()">
                            <svg viewBox="0 0 24 24" stroke="#555" stroke-width="2">
                                <circle cx="18" cy="5" r="3" />
                                <circle cx="6" cy="12" r="3" />
                                <circle cx="18" cy="19" r="3" />
                                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                            </svg>
                            <span id="shareMoreLabel"><?= e(data_get($data, "sections.share.more")); ?></span>
                        </div>
                    </div>
                    <button class="modal-close-btn" onclick="closeShareModal()">
                        <span id="shareCancelLabel"><?= e(data_get($data, "sections.share.cancel")); ?></span>
                    </button>
                </div>
            </div>

            <div class="promo-overlay" id="promoOverlay">
                <div class="promo-box">
                    <button class="promo-close" onclick="closePromo()">
                        <svg viewBox="0 0 24 24">
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                    </button>
                    <div class="promo-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                        </svg>
                    </div>
                    <h3 id="promoTitle"><?= e(data_get($data, "promo.title")); ?></h3>
                    <p id="promoText"><?= e(data_get($data, "promo.text")); ?></p>
                    <button class="promo-cta" onclick="promoAction()">
                        <svg viewBox="0 0 24 24">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.5 0 0 1 8 8v.5z" />
                        </svg>
                        <span id="promoButtonLabel"><?= e(data_get($data, "promo.button")); ?></span>
                    </button>
                </div>
            </div>

            <div class="toast" id="toast"></div>
            
            <!-- Hidden Icon Templates -->
            <div class="icon-templates" style="display: none;">
                <!-- Banner Icons -->
                <span id="banner_grocery"><svg viewBox="0 0 24 24" width="30" height="30"><?= getIcon("banner_grocery"); ?></svg></span>
                <span id="banner_fruits"><svg viewBox="0 0 24 24" width="30" height="30"><?= getIcon("banner_fruits"); ?></svg></span>
                <span id="banner_essentials"><svg viewBox="0 0 24 24" width="30" height="30"><?= getIcon("banner_essentials"); ?></svg></span>
                <span id="banner_dairy"><svg viewBox="0 0 24 24" width="30" height="30"><?= getIcon("banner_dairy"); ?></svg></span>
                <span id="banner_beverages"><svg viewBox="0 0 24 24" width="30" height="30"><?= getIcon("banner_beverages"); ?></svg></span>
                
                <!-- Badge Icons -->
                <span id="badge_trusted"><svg viewBox="0 0 24 24"><?= getIcon("badge_trusted"); ?></svg></span>
                <span id="badge_open"><svg viewBox="0 0 24 24"><?= getIcon("badge_open"); ?></svg></span>
                <span id="badge_delivery"><svg viewBox="0 0 24 24"><?= getIcon("badge_delivery"); ?></svg></span>
                <span id="badge_prices"><svg viewBox="0 0 24 24"><?= getIcon("badge_prices"); ?></svg></span>
                <span id="badge_fresh"><svg viewBox="0 0 24 24"><?= getIcon("badge_fresh"); ?></svg></span>
                
                <!-- Social Icons -->
                <span id="social_whatsapp"><svg class="ic" viewBox="0 0 24 24" stroke-width="2"><?= getIcon("social_whatsapp"); ?></svg></span>
                <span id="social_facebook"><svg class="ic" viewBox="0 0 24 24" stroke-width="2"><?= getIcon("social_facebook"); ?></svg></span>
                <span id="social_instagram"><svg class="ic" viewBox="0 0 24 24" stroke-width="2"><?= getIcon("social_instagram"); ?></svg></span>
                <span id="social_youtube"><svg class="ic" viewBox="0 0 24 24" stroke-width="2"><?= getIcon("social_youtube"); ?></svg></span>
                
                <!-- Payment Icons -->
                <span id="pay_upi"><svg viewBox="0 0 24 24"><?= getIcon("pay_upi"); ?></svg></span>
                <span id="pay_card"><svg viewBox="0 0 24 24"><?= getIcon("pay_card"); ?></svg></span>
                <span id="pay_cash"><svg viewBox="0 0 24 24"><?= getIcon("pay_cash"); ?></svg></span>
                
                <!-- UI Icons -->
                <span id="ui_arrow_right"><svg viewBox="0 0 24 24"><?= getIcon("ui_arrow_right"); ?></svg></span>
                <span id="ui_check"><svg viewBox="0 0 24 24"><?= getIcon("ui_check"); ?></svg></span>
                <span id="ui_star"><svg viewBox="0 0 24 24"><?= getIcon("ui_star"); ?></svg></span>
                <span id="ui_cart"><svg viewBox="0 0 24 24"><?= getIcon("ui_cart"); ?></svg></span>
            </div>
        </main>
        <script>
            window.__APP__ = <?= json_encode($data ?: [], JSON_UNESCAPED_SLASHES); ?>;
        </script>
        <script src="script.js"></script>
    </body>
</html>