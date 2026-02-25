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

function format_inr($value): string
{
    $number = (string) (int) ($value ?? 0);
    $length = strlen($number);

    if ($length <= 3) {
        return $number;
    }

    $lastThree = substr($number, -3);
    $rest = substr($number, 0, -3);
    $rest = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $rest);

    return $rest . "," . $lastThree;
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
$profileAlt = data_get($data, "assets.profileAlt", data_get($data, "profile.name", ""));

$categories = data_list($data, "categories");
$currentCat = "all";
foreach ($categories as $cat) {
    if (!empty($cat["active"])) {
        $currentCat = $cat["key"] ?? "all";
        break;
    }
}

$collections = data_list($data, "collections");
if ($currentCat !== "all") {
    $collections = array_values(array_filter($collections, static function ($item) use ($currentCat) {
        return ($item["cat"] ?? "") === $currentCat;
    }));
}

$socialIconClasses = [
    "whatsapp" => "ic-wa",
    "instagram" => "ic-ig",
    "facebook" => "ic-fb",
    "pinterest" => "ic-pin",
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
        <main id="app-root" aria-live="polite" style="min-height:100vh">
            <div class="banner"<?= $bannerImage ? " style=\"background:url('" . e($bannerImage) . "') center/cover no-repeat\"" : ""; ?>>
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
                            <circle cx="18" cy="5" r="3" />
                            <circle cx="6" cy="12" r="3" />
                            <circle cx="18" cy="19" r="3" />
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                        </svg>
                        <span id="banner-share"><?= e(data_get($data, "banner.share")); ?></span>
                    </button>
                    <button class="save-btn-top" onclick="saveContact()">
                        <svg viewBox="0 0 24 24" width="13" height="13" stroke="currentColor" fill="none" stroke-width="2.2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        <span id="banner-save-contact"><?= e(data_get($data, "banner.saveContact")); ?></span>
                    </button>
                </div>
                <div class="banner-text">
                    <div class="banner-brandname" id="banner-brand"><?= e(data_get($data, "banner.brand")); ?></div>
                    <div class="banner-tagline" id="banner-subtitle"><?= e(data_get($data, "banner.subtitle")); ?></div>
                    <div class="banner-divider"><span></span><span class="diamond" id="banner-divider-symbol"><?= e(data_get($data, "banner.dividerSymbol")); ?></span><span></span></div>
                </div>
            </div>

            <div class="profile-card">
                <div class="profile-avatar-wrap">
                    <div class="profile-avatar">
                        <img id="profile-image" src="<?= e($profileImage); ?>" alt="<?= e($profileAlt); ?>" style="width:100%;height:100%;object-fit:cover;border-radius:50%;" />
                        <div class="verified-badge">
                            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12" /></svg>
                        </div>
                    </div>
                </div>
                <div class="profile-name" id="profile-name"><?= e(data_get($data, "profile.name")); ?></div>
                <div class="profile-role" id="profile-role"><?= e(data_get($data, "profile.role")); ?></div>
                <div class="rating-row">
                    <span class="stars" id="profile-stars"><?= e(data_get($data, "profile.stars")); ?></span>
                    <span class="rating-num" id="profile-rating"><?= e(data_get($data, "profile.rating")); ?></span>
                    <span class="rating-count" id="profile-rating-count"><?= e(data_get($data, "profile.ratingCount")); ?></span>
                </div>
                <div class="profile-bio" id="profile-bio"><?= e(data_get($data, "profile.bio")); ?></div>
                <div class="profile-action-btns">
                    <button class="pab call" onclick="callShop()">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.35 2 2 0 0 1 3.6 1.14h3a2 2 0 0 1 2 1.72c.12.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.76a16 16 0 0 0 6.37 6.37l.97-.97a2 2 0 0 1 2.11-.45c.91.34 1.85.58 2.81.7a2 2 0 0 1 1.73 2.01z" />
                        </svg>
                        <span id="action-call"><?= e(data_get($data, "profile.actions.call")); ?></span>
                    </button>
                    <button class="pab whatsapp" onclick="openWA()">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                        </svg>
                        <span id="action-whatsapp"><?= e(data_get($data, "profile.actions.whatsapp")); ?></span>
                    </button>
                    <button class="pab save" onclick="saveContact()">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        <span id="action-save"><?= e(data_get($data, "profile.actions.save")); ?></span>
                    </button>
                    <button class="pab email" onclick="emailShop()">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                        <span id="action-email"><?= e(data_get($data, "profile.actions.email")); ?></span>
                    </button>
                    <button class="pab direction" onclick="openMaps()">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="3 11 22 2 13 21 11 13 3 11" />
                        </svg>
                        <span id="action-directions"><?= e(data_get($data, "profile.actions.directions")); ?></span>
                    </button>
                    <button class="pab share" onclick="openShare()">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="18" cy="5" r="3" />
                            <circle cx="6" cy="12" r="3" />
                            <circle cx="18" cy="19" r="3" />
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                        </svg>
                        <span id="action-share"><?= e(data_get($data, "profile.actions.share")); ?></span>
                    </button>
                </div>
            </div>

            <div class="stats-strip" id="statsStrip">
                <?php foreach (data_list($data, "stats") as $item): ?>
                    <div class="stat-item">
                        <div class="stat-num"><?= e($item["value"] ?? ""); ?></div>
                        <div class="stat-label"><?= e($item["label"] ?? ""); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke-width="1.8">
                            <path d="M12 2l2.4 4.8L20 8l-4 3.9L17 18l-5-2.6L7 18l1-6.1L4 8l5.6-1.2z" />
                        </svg>
                    </div>
                    <span class="sec-title" id="sec-title-collections"><?= e(data_get($data, "sections.collections")); ?></span>
                </div>
                <div class="sec-body" style="padding:0.75rem 0.85rem">
                    <div class="cat-scroll" id="catScroll">
                        <?php foreach ($categories as $cat): ?>
                            <?php $catKey = $cat["key"] ?? ""; ?>
                            <div class="cat-chip<?= $catKey === $currentCat ? " active" : ""; ?>" onclick="filterCat(this, <?= js_str($catKey); ?>)">
                                <?= e($cat["label"] ?? ""); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div style="height:0.6rem"></div>
                    <div class="collections-grid" id="collectionsGrid">
                        <?php foreach ($collections as $item): ?>
                            <?php
                            $price = (int) ($item["price"] ?? 0);
                            $oldPrice = (int) ($item["oldPrice"] ?? 0);
                            ?>
                            <div class="coll-card">
                                <div class="coll-img">
                                    <div class="coll-img-ph" style="background:<?= e($item["bg"] ?? ""); ?>;height:100%">
                                        <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="rgba(0,0,0,0.25)" stroke-width="1.2">
                                            <path d="M12 2l2.4 4.8L20 8l-4 3.9L17 18l-5-2.6L7 18l1-6.1L4 8l5.6-1.2z"/>
                                        </svg>
                                    </div>
                                    <?php if (!empty($item["tag"])): ?>
                                        <span class="coll-badge" style="background:<?= e($item["tagColor"] ?? ""); ?>"><?= e($item["tag"]); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="coll-body">
                                    <div class="coll-name"><?= e($item["name"] ?? ""); ?></div>
                                    <div class="coll-metal"><?= e($item["metal"] ?? ""); ?></div>
                                    <div style="font-size:.68rem;color:var(--muted);line-height:1.4;margin-bottom:.4rem"><?= e($item["desc"] ?? ""); ?></div>
                                    <div class="coll-footer">
                                        <div>
                                            <div class="coll-price">₹<?= e(format_inr($price)); ?></div>
                                            <?php if ($oldPrice > 0): ?>
                                                <div class="coll-old">₹<?= e(format_inr($oldPrice)); ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <button class="enquire-btn" onclick="enquireWA(<?= js_str($item["name"] ?? ""); ?>)">
                                            <svg viewBox="0 0 24 24" width="11" height="11" stroke="currentColor" fill="none" stroke-width="2.5"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                                            <?= e(data_get($data, "labels.enquireButton")); ?>
                                        </button>
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
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke-width="1.8">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                    </div>
                    <span class="sec-title" id="sec-title-purity"><?= e(data_get($data, "sections.purity")); ?></span>
                </div>
                <div class="sec-body">
                    <div class="purity-row" id="purityRow">
                        <?php foreach (data_list($data, "purity.items") as $item): ?>
                            <div class="purity-item">
                                <div class="purity-karat"><?= e($item["karat"] ?? ""); ?></div>
                                <div class="purity-label"><?= e($item["label"] ?? ""); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div style="margin-top:0.8rem;padding:0.65rem;background:linear-gradient(135deg,#fff8e1,#fff3cd);border-radius:10px;border:1px solid #e8d5a0;display:flex;align-items:center;gap:0.6rem;">
                        <span style="font-size:1.1rem" id="purity-hallmark-emoji"><?= e(data_get($data, "purity.hallmark.emoji")); ?></span>
                        <span style="font-size:0.76rem;color:var(--muted);line-height:1.5;">
                            <strong style="color:var(--dark)" id="purity-hallmark-title"><?= e(data_get($data, "purity.hallmark.title")); ?></strong>
                            <span id="purity-hallmark-separator"><?= e(data_get($data, "purity.hallmark.separator")); ?></span>
                            <span id="purity-hallmark-text"><?= e(data_get($data, "purity.hallmark.text")); ?></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke-width="1.8">
                            <circle cx="12" cy="8" r="6" />
                            <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11" />
                        </svg>
                    </div>
                    <span class="sec-title" id="sec-title-certifications"><?= e(data_get($data, "sections.certifications")); ?></span>
                </div>
                <div class="sec-body">
                    <div class="cert-grid" id="certGrid">
                        <?php foreach (data_list($data, "certifications") as $item): ?>
                            <div class="cert-item">
                                <div class="cert-ico" style="background:<?= e($item["bg"] ?? "#fff"); ?>"><?= e($item["emoji"] ?? ""); ?></div>
                                <div class="cert-text">
                                    <div class="cert-name"><?= e($item["name"] ?? ""); ?></div>
                                    <div class="cert-sub"><?= e($item["sub"] ?? ""); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke-width="1.8">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
                        </svg>
                    </div>
                    <span class="sec-title" id="sec-title-services"><?= e(data_get($data, "sections.services")); ?></span>
                </div>
                <div class="sec-body">
                    <div class="svc-list" id="servicesList">
                        <?php foreach (data_list($data, "services") as $item): ?>
                            <?php $iconKey = "service_" . ($item["icon"] ?? "star"); ?>
                            <div class="svc-item">
                                <div class="svc-ico">
                                    <svg viewBox="0 0 24 24"><?= getIcon($iconKey) ?: getIcon("service_star"); ?></svg>
                                </div>
                                <div class="svc-info">
                                    <div class="svc-name"><?= e($item["name"] ?? ""); ?></div>
                                    <div class="svc-desc"><?= e($item["desc"] ?? ""); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke-width="1.8">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                    </div>
                    <span class="sec-title" id="sec-title-showroom"><?= e(data_get($data, "sections.showroom")); ?></span>
                </div>
                <div class="sec-body">
                    <a class="address-link" href="#" onclick="return (openMaps(), !1);">
                        <div class="addr-icon-wrap">
                            <svg viewBox="0 0 24 24" width="18" height="18" stroke-width="1.8">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                        </div>
                        <div class="addr-text">
                            <strong id="showroom-name"><?= e(data_get($data, "showroom.name")); ?></strong>
                            <span id="showroom-line1"><?= e(data_get($data, "showroom.line1")); ?></span><br />
                            <span id="showroom-line2"><?= e(data_get($data, "showroom.line2")); ?></span><br />
                            <a class="map-btn" href="#" onclick="return (openMaps(), !1);">
                                <svg viewBox="0 0 24 24">
                                    <polygon points="3 11 22 2 13 21 11 13 3 11" />
                                </svg>
                                <span id="showroom-map-label"><?= e(data_get($data, "showroom.mapLabel")); ?></span>
                            </a>
                        </div>
                    </a>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke-width="1.8">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    <span class="sec-title" id="sec-title-hours"><?= e(data_get($data, "sections.hours")); ?></span>
                </div>
                <div class="sec-body">
                    <table class="hours-table" id="hoursTable">
                        <?php foreach (data_list($data, "hours") as $row): ?>
                            <?php if (!empty($row["today"])): ?>
                                <tr class="today">
                                    <td class="day"><?= e($row["day"] ?? ""); ?> <span class="today-badge"><?= e(data_get($data, "labels.todayBadge")); ?></span></td>
                                    <td class="time" style="color:var(--gold);font-weight:700"><?= e($row["time"] ?? ""); ?></td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td class="day"><?= e($row["day"] ?? ""); ?></td>
                                    <td class="time"><?= e($row["time"] ?? ""); ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke-width="1.8">
                            <circle cx="18" cy="5" r="3" />
                            <circle cx="6" cy="12" r="3" />
                            <circle cx="18" cy="19" r="3" />
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                        </svg>
                    </div>
                    <span class="sec-title" id="sec-title-follow"><?= e(data_get($data, "sections.follow")); ?></span>
                </div>
                <div class="sec-body">
                    <div class="social-list" id="socialList">
                        <?php foreach (data_list($data, "followLinks") as $item): ?>
                            <?php
                            $type = $item["type"] ?? "whatsapp";
                            $iconKey = "social_" . $type;
                            $iconClass = $socialIconClasses[$type] ?? $socialIconClasses["whatsapp"];
                            $action = "";
                            if (($item["action"] ?? "") === "openWA") {
                                $action = "openWA()";
                            } elseif (!empty($item["url"])) {
                                $action = "openExternal(" . js_str($item["url"]) . ")";
                            }
                            ?>
                            <div class="social-item<?= $type === "pinterest" ? " ic-pin" : ""; ?>"<?= $action ? " onclick=\"{$action}\"" : ""; ?>>
                                <div class="s-ico <?= e($iconClass); ?>"><?= getIcon($iconKey); ?></div>
                                <div>
                                    <div class="s-name"><?= e($item["name"] ?? ""); ?></div>
                                    <div class="s-val"><?= e($item["value"] ?? ""); ?></div>
                                </div>
                                <div class="s-arrow">
                                    <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6" /></svg>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke-width="1.8">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                    </div>
                    <span class="sec-title" id="sec-title-enquiry"><?= e(data_get($data, "sections.enquiry")); ?></span>
                </div>
                <div class="sec-body">
                    <div id="enquiryForm">
                        <input class="form-field" type="text" id="eName" placeholder="<?= e(data_get($data, "enquiryForm.namePlaceholder")); ?>" />
                        <input class="form-field" type="tel" id="ePhone" placeholder="<?= e(data_get($data, "enquiryForm.phonePlaceholder")); ?>" />
                        <input class="form-field" type="email" id="eEmail" placeholder="<?= e(data_get($data, "enquiryForm.emailPlaceholder")); ?>" />
                        <select class="form-field form-select" id="eCategory">
                            <option value=""><?= e(data_get($data, "enquiryForm.categoryPlaceholder")); ?></option>
                            <?php foreach (data_list($data, "enquiryForm.categories") as $item): ?>
                                <option><?= e($item); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input class="form-field" type="text" id="eBudget" placeholder="<?= e(data_get($data, "enquiryForm.budgetPlaceholder")); ?>" />
                        <textarea class="form-field" id="eMsg" placeholder="<?= e(data_get($data, "enquiryForm.messagePlaceholder")); ?>"></textarea>
                        <button class="form-submit" onclick="submitEnquiry()">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none" stroke-width="2">
                                <line x1="22" y1="2" x2="11" y2="13" />
                                <polygon points="22 2 15 22 11 13 2 9 22 2" />
                            </svg>
                            <span id="enquiry-submit-label"><?= e(data_get($data, "enquiryForm.submitLabel")); ?></span>
                        </button>
                    </div>
                    <div class="form-success" id="enquirySuccess">
                        <div class="tick" id="enquiry-success-icon"><?= e(data_get($data, "enquiryForm.successIcon")); ?></div>
                        <p>
                            <strong id="enquiry-success-title"><?= e(data_get($data, "enquiryForm.successTitle")); ?></strong><br />
                            <span id="enquiry-success-text"><?= e(data_get($data, "enquiryForm.successText")); ?></span>
                        </p>
                        <button onclick="resetEnquiry()" style="margin-top:1rem;background:linear-gradient(135deg,var(--gold),var(--gold2));border:none;border-radius:10px;padding:0.6rem 1.5rem;font-size:0.8rem;font-weight:700;color:var(--deep);cursor:pointer;">
                            <span id="enquiry-success-button"><?= e(data_get($data, "enquiryForm.successButton")); ?></span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke-width="1.8">
                            <rect x="3" y="3" width="7" height="7" />
                            <rect x="14" y="3" width="7" height="7" />
                            <rect x="14" y="14" width="7" height="7" />
                            <rect x="3" y="14" width="7" height="7" />
                        </svg>
                    </div>
                    <span class="sec-title" id="sec-title-scan"><?= e(data_get($data, "sections.scan")); ?></span>
                </div>
                <div class="sec-body">
                    <div class="qr-section">
                        <div id="vcardQR"></div>
                        <div class="qr-desc" id="qr-description"><?= e(data_get($data, "qr.description")); ?></div>
                        <button class="qr-download-btn" onclick="downloadQR()">
                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" fill="none" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="7 10 12 15 17 10" />
                                <line x1="12" y1="15" x2="12" y2="3" />
                            </svg>
                            <span id="qr-download-label"><?= e(data_get($data, "qr.downloadLabel")); ?></span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="footer">
                <div class="footer-divider"></div>
                <strong id="footer-brand"><?= e(data_get($data, "footer.brand")); ?></strong><br />
                <span id="footer-line2"><?= e(data_get($data, "footer.line2")); ?></span><br />
                <span id="footer-line3" style="color:var(--gold);letter-spacing:2px"><?= e(data_get($data, "footer.line3")); ?></span><br />
                <span id="footer-line4" style="font-size:0.68rem"><?= e(data_get($data, "footer.line4")); ?></span>
            </div>

            <div class="bottom-bar">
                <button class="bb-btn call" onclick="callShop()">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#2e7d32" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.35 2 2 0 0 1 3.6 1.14h3a2 2 0 0 1 2 1.72c.12.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.76a16 16 0 0 0 6.37 6.37l.97-.97a2 2 0 0 1 2.11-.45c.91.34 1.85.58 2.81.7a2 2 0 0 1 1.73 2.01z" />
                    </svg>
                    <span class="bb-label" id="bb-call"><?= e(data_get($data, "bottomBar.call")); ?></span>
                </button>
                <button class="bb-btn save" onclick="saveContact()">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#b8860b" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                    <span class="bb-label" id="bb-save"><?= e(data_get($data, "bottomBar.save")); ?></span>
                </button>
                <button class="bb-btn wa" onclick="openWA()">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#128c7e" stroke-width="2">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                    </svg>
                    <span class="bb-label" id="bb-whatsapp"><?= e(data_get($data, "bottomBar.whatsapp")); ?></span>
                </button>
            </div>

            <div class="cart-overlay" id="cartOverlay" onclick="closeCartOutside(event)">
                <div class="cart-sheet">
                    <div class="cart-handle"></div>
                    <div class="cart-title">
                        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" fill="none" stroke-width="1.8">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <path d="M16 10a4 4 0 0 1-8 0" />
                        </svg>
                        <span id="cart-title"><?= e(data_get($data, "cart.title")); ?></span>
                    </div>
                    <div id="cartBody"></div>
                </div>
            </div>

            <div class="share-modal" id="shareModal" onclick="closeShare(event)">
                <div class="share-sheet">
                    <div class="cart-handle"></div>
                    <div class="share-title" id="share-modal-title"><?= e(data_get($data, "shareModal.title")); ?></div>
                    <div class="share-btns">
                        <button class="share-opt wa" onclick="shareWA()">
                            <svg viewBox="0 0 24 24" stroke="#128c7e" stroke-width="1.8">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
                            </svg>
                            <span id="share-wa-label"><?= e(data_get($data, "shareModal.whatsapp")); ?></span>
                        </button>
                        <button class="share-opt fb" onclick="shareFB()">
                            <svg viewBox="0 0 24 24" stroke="#1877f2" stroke-width="1.8">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                            </svg>
                            <span id="share-fb-label"><?= e(data_get($data, "shareModal.facebook")); ?></span>
                        </button>
                        <button class="share-opt copy" onclick="copyLink()">
                            <svg viewBox="0 0 24 24" stroke="#8a7355" stroke-width="1.8">
                                <rect x="9" y="9" width="13" height="13" rx="2" />
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                            </svg>
                            <span id="share-copy-label"><?= e(data_get($data, "shareModal.copy")); ?></span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="promo-overlay" id="promoOverlay" onclick="closePromo(event)"></div>

            <div class="toast" id="toast">
                <svg viewBox="0 0 24 24" width="14" height="14" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                <span id="toastMsg"><?= e(data_get($data, "labels.toastDefault")); ?></span>
            </div>
        </main>
        <script>
            window.__APP__ = <?= json_encode($data ?: [], JSON_UNESCAPED_SLASHES); ?>;
        </script>
        <script src="script.js"></script>
    </body>
</html>