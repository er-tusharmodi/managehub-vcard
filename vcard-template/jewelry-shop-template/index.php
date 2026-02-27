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
                            <?= getIcon("ui_share"); ?>
                        </svg>
                        <span id="banner-share"><?= e(data_get($data, "banner.share")); ?></span>
                    </button>
                    <button class="save-btn-top" onclick="saveContact()">
                        <svg viewBox="0 0 24 24" width="13" height="13" stroke="currentColor" fill="none" stroke-width="2.2">
                            <?= getIcon("ui_save_disk"); ?>
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
                            <svg viewBox="0 0 24 24"><?= getIcon("ui_check"); ?></svg>
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
                            <?= getIcon("ui_call"); ?>
                        </svg>
                        <span id="action-call"><?= e(data_get($data, "profile.actions.call")); ?></span>
                    </button>
                    <button class="pab whatsapp" onclick="openWA()">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <?= getIcon("ui_whatsapp"); ?>
                        </svg>
                        <span id="action-whatsapp"><?= e(data_get($data, "profile.actions.whatsapp")); ?></span>
                    </button>
                    <button class="pab save" onclick="saveContact()">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <?= getIcon("ui_user"); ?>
                        </svg>
                        <span id="action-save"><?= e(data_get($data, "profile.actions.save")); ?></span>
                    </button>
                    <button class="pab email" onclick="emailShop()">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <?= getIcon("ui_mail"); ?>
                        </svg>
                        <span id="action-email"><?= e(data_get($data, "profile.actions.email")); ?></span>
                    </button>
                    <button class="pab direction" onclick="openMaps()">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <?= getIcon("ui_direction"); ?>
                        </svg>
                        <span id="action-directions"><?= e(data_get($data, "profile.actions.directions")); ?></span>
                    </button>
                    <button class="pab share" onclick="openShare()">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <?= getIcon("ui_share"); ?>
                        </svg>
                        <span id="action-share"><?= e(data_get($data, "profile.actions.share")); ?></span>
                    </button>
                </div>
            </div>

            <div class="sec">
                <div class="sec-header">
                    <div class="sec-icon">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke-width="1.8">
                            <?= getIcon("service_star"); ?>
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
                                            <?= getIcon("service_star"); ?>
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
                                            <svg viewBox="0 0 24 24" width="11" height="11" stroke="currentColor" fill="none" stroke-width="2.5">
                                                <?= getIcon("ui_whatsapp"); ?>
                                            </svg>
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
                            <?= getIcon("ui_shield"); ?>
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
                            <?= getIcon("ui_medal"); ?>
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
                            <?= getIcon("service_wrench"); ?>
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
                            <?= getIcon("service_map"); ?>
                        </svg>
                    </div>
                    <span class="sec-title" id="sec-title-showroom"><?= e(data_get($data, "sections.showroom")); ?></span>
                </div>
                <div class="sec-body">
                    <a class="address-link" href="#" onclick="return (openMaps(), !1);">
                        <div class="addr-icon-wrap">
                            <svg viewBox="0 0 24 24" width="18" height="18" stroke-width="1.8">
                                <?= getIcon("service_map"); ?>
                            </svg>
                        </div>
                        <div class="addr-text">
                            <strong id="showroom-name"><?= e(data_get($data, "showroom.name")); ?></strong>
                            <span id="showroom-line1"><?= e(data_get($data, "showroom.line1")); ?></span><br />
                            <span id="showroom-line2"><?= e(data_get($data, "showroom.line2")); ?></span><br />
                            <a class="map-btn" href="#" onclick="return (openMaps(), !1);">
                                <svg viewBox="0 0 24 24">
                                    <?= getIcon("ui_direction"); ?>
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
                            <?= getIcon("ui_clock"); ?>
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
                            <?= getIcon("ui_share"); ?>
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
                                    <svg viewBox="0 0 24 24"><?= getIcon("ui_arrow_right"); ?></svg>
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
                            <?= getIcon("ui_mail"); ?>
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
                                <?= getIcon("ui_send"); ?>
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
                            <?= getIcon("ui_grid"); ?>
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
                                <?= getIcon("ui_download"); ?>
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
                        <?= getIcon("ui_call"); ?>
                    </svg>
                    <span class="bb-label" id="bb-call"><?= e(data_get($data, "bottomBar.call")); ?></span>
                </button>
                <button class="bb-btn save" onclick="saveContact()">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#b8860b" stroke-width="2">
                        <?= getIcon("ui_save_disk"); ?>
                    </svg>
                    <span class="bb-label" id="bb-save"><?= e(data_get($data, "bottomBar.save")); ?></span>
                </button>
                <button class="bb-btn wa" onclick="openWA()">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#128c7e" stroke-width="2">
                        <?= getIcon("ui_whatsapp"); ?>
                    </svg>
                    <span class="bb-label" id="bb-whatsapp"><?= e(data_get($data, "bottomBar.whatsapp")); ?></span>
                </button>
            </div>

            <div class="cart-overlay" id="cartOverlay" onclick="closeCartOutside(event)">
                <div class="cart-sheet">
                    <div class="cart-handle"></div>
                    <div class="cart-title">
                        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" fill="none" stroke-width="1.8">
                            <?= getIcon("ui_cart"); ?>
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
                                <?= getIcon("ui_whatsapp"); ?>
                            </svg>
                            <span id="share-wa-label"><?= e(data_get($data, "shareModal.whatsapp")); ?></span>
                        </button>
                        <button class="share-opt fb" onclick="shareFB()">
                            <svg viewBox="0 0 24 24" stroke="#1877f2" stroke-width="1.8">
                                <?= getIcon("ui_facebook"); ?>
                            </svg>
                            <span id="share-fb-label"><?= e(data_get($data, "shareModal.facebook")); ?></span>
                        </button>
                        <button class="share-opt copy" onclick="copyLink()">
                            <svg viewBox="0 0 24 24" stroke="#8a7355" stroke-width="1.8">
                                <?= getIcon("ui_copy"); ?>
                            </svg>
                            <span id="share-copy-label"><?= e(data_get($data, "shareModal.copy")); ?></span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="promo-overlay" id="promoOverlay" onclick="closePromo(event)"></div>

            <div class="toast" id="toast">
                <svg viewBox="0 0 24 24" width="14" height="14" stroke-width="2">
                    <?= getIcon("ui_check_circle"); ?>
                </svg>
                <span id="toastMsg"><?= e(data_get($data, "labels.toastDefault")); ?></span>
            </div>

            <div class="icon-templates" aria-hidden="true" style="display:none">
                <span id="icon-service-star"><svg viewBox="0 0 24 24"><?= getIcon("service_star"); ?></svg></span>
                <span id="icon-service-map"><svg viewBox="0 0 24 24"><?= getIcon("service_map"); ?></svg></span>
                <span id="icon-service-wrench"><svg viewBox="0 0 24 24"><?= getIcon("service_wrench"); ?></svg></span>
                <span id="icon-service-card"><svg viewBox="0 0 24 24"><?= getIcon("service_card"); ?></svg></span>
                <span id="icon-service-arrow"><svg viewBox="0 0 24 24"><?= getIcon("service_arrow"); ?></svg></span>
                <span id="icon-service-heart"><svg viewBox="0 0 24 24"><?= getIcon("service_heart"); ?></svg></span>

                <span id="icon-social-whatsapp"><?= getIcon("social_whatsapp"); ?></span>
                <span id="icon-social-instagram"><?= getIcon("social_instagram"); ?></span>
                <span id="icon-social-facebook"><?= getIcon("social_facebook"); ?></span>
                <span id="icon-social-pinterest"><?= getIcon("social_pinterest"); ?></span>
                <span id="icon-social-youtube"><?= getIcon("social_youtube"); ?></span>

                <span id="icon-ui-arrow-right"><svg viewBox="0 0 24 24"><?= getIcon("ui_arrow_right"); ?></svg></span>
                <span id="icon-ui-cart"><svg viewBox="0 0 24 24"><?= getIcon("ui_cart"); ?></svg></span>
                <span id="icon-ui-copy"><svg viewBox="0 0 24 24"><?= getIcon("ui_copy"); ?></svg></span>
                <span id="icon-ui-minus"><svg viewBox="0 0 24 24"><?= getIcon("ui_minus"); ?></svg></span>
                <span id="icon-ui-plus"><svg viewBox="0 0 24 24"><?= getIcon("ui_plus"); ?></svg></span>
                <span id="icon-ui-whatsapp"><svg viewBox="0 0 24 24"><?= getIcon("ui_whatsapp"); ?></svg></span>
            </div>
        </main>
        <script>
            window.__APP__ = <?= json_encode($data ?: [], JSON_UNESCAPED_SLASHES); ?>;
        </script>
        <script src="script.js"></script>
    </body>
</html>