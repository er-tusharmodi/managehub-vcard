<?php
// Load from default.json (template defaults)
$dataPath = __DIR__ . "/default.json";
$data = [];

if (is_readable($dataPath)) {
    $json = file_get_contents($dataPath);
    $decoded = json_decode($json, true);
    if (is_array($decoded)) {
        $data = $decoded;
    }
}

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}

function data_get($array, $path, $default = "")
{
    if (!is_array($array)) {
        return $default;
    }

    $current = $array;
    foreach (explode(".", $path) as $segment) {
        if (!is_array($current) || !array_key_exists($segment, $current)) {
            return $default;
        }
        $current = $current[$segment];
    }

    return $current ?? $default;
}

function text_with_breaks($value)
{
    $value = (string) $value;
    $value = preg_replace('/<br\s*\/?>(\r\n|\r|\n)?/i', "\n", $value);
    return nl2br(h($value), false);
}

function isSectionEnabled($data, $section)
{
    // If _sections_config doesn't exist, all sections are enabled by default
    if (!isset($data['_sections_config'])) {
        return true;
    }
    
    // If the specific section config doesn't exist, enable by default
    if (!isset($data['_sections_config'][$section])) {
        return true;
    }
    
    // Return the enabled status
    return $data['_sections_config'][$section]['enabled'] ?? true;
}

require_once __DIR__ . "/icons.php";

$assets = $data["assets"] ?? [];
$fallbackImage = $assets["fallbackImage"] ?? "";
$bannerImage = $assets["bannerImage"] ?? $fallbackImage;
$profileImage = $assets["profileImage"] ?? $fallbackImage;

$shop = $data["shop"] ?? [];
$website = $shop["website"] ?? "";

if ($website === "" && isset($_SERVER["HTTP_HOST"], $_SERVER["REQUEST_URI"])) {
    $scheme = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") ? "https" : "http";
    $website = $scheme . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
}

if (!isset($data["shop"])) {
    $data["shop"] = [];
}

if (empty($data["shop"]["website"])) {
    $data["shop"]["website"] = $website;
}

$socialClassMap = [
    "whatsapp" => "ic-wa",
    "facebook" => "ic-fb",
    "instagram" => "ic-ig",
    "youtube" => "ic-yt",
];

$paymentStrokeMap = [
    "upi" => "#6a1b9a",
    "bank" => "#1565c0",
    "cash" => "#2e7d32",
];
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta
            name="viewport"
            content="width=device-width,initial-scale=1,maximum-scale=1"
        />
        <title><?= h(data_get($data, "meta.title")); ?></title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
        <div class="banner">
            <img
                class="cover"
                id="banner-cover"
                src="<?= h($bannerImage); ?>"
                alt="<?= h(data_get($data, "meta.bannerAlt")); ?>"
            />
            <div class="banner-overlay"></div>
            <div class="banner-top-bar">
                <button class="share-btn" onclick="openShare()">
                    <?= get_icon("share", "ic-sm", "currentColor"); ?>
                    <span id="top-share-label"><?= h(data_get($data, "header.shareLabel")); ?></span>
                </button>
                <button class="save-btn-top" onclick="saveContact()">
                    <?= get_icon("save", "ic-sm", "currentColor"); ?>
                    <span id="top-save-label"><?= h(data_get($data, "header.saveContactLabel")); ?></span>
                </button>
            </div>
        </div>

        <div class="profile-card">
            <div class="profile-avatar-wrap">
                <div class="profile-avatar">
                    <img
                        id="profile-image"
                        src="<?= h($profileImage); ?>"
                        alt="<?= h(data_get($data, "meta.profileAlt")); ?>"
                    />
                </div>
            </div>
            <div class="profile-name" id="profile-name"><?= h(data_get($data, "profile.name")); ?></div>
            <div class="profile-role" id="profile-role"><?= h(data_get($data, "profile.role")); ?></div>
            <div class="profile-bio" id="profile-bio"><?= h(data_get($data, "profile.bio")); ?></div>

            <div class="profile-action-btns">
                <button class="pab call" onclick="callShop()">
                    <?= get_icon("phone", "ic", "#2e7d32"); ?>
                    <span id="action-call-label"><?= h(data_get($data, "profile.actions.call")); ?></span>
                </button>
                <button class="pab whatsapp" onclick="openWA()">
                    <?= get_icon("whatsapp", "ic", "#1b5e20"); ?>
                    <span id="action-whatsapp-label"><?= h(data_get($data, "profile.actions.whatsapp")); ?></span>
                </button>
                <button class="pab save" onclick="saveContact()">
                    <?= get_icon("save", "ic", "#e65100"); ?>
                    <span id="action-save-label"><?= h(data_get($data, "profile.actions.save")); ?></span>
                </button>
                <button class="pab email" onclick="emailShop()">
                    <?= get_icon("email", "ic", "#1565c0"); ?>
                    <span id="action-email-label"><?= h(data_get($data, "profile.actions.email")); ?></span>
                </button>
                <button class="pab direction" onclick="openMaps()">
                    <?= get_icon("directions", "ic", "#880e4f"); ?>
                    <span id="action-directions-label"><?= h(data_get($data, "profile.actions.directions")); ?></span>
                </button>
                <button class="pab share" onclick="openShare()">
                    <?= get_icon("share", "ic", "#6a1b9a"); ?>
                    <span id="action-share-label"><?= h(data_get($data, "profile.actions.share")); ?></span>
                </button>
            </div>
        </div>

        <div class="section-space"></div>

        <?php if (isSectionEnabled($data, 'location')): ?>
        <div class="sec">
            <div class="sec-header">
                <div class="sec-icon">
                    <?= get_icon("location", "ic", "currentColor"); ?>
                </div>
                <div class="sec-title" id="location-title"><?= h(data_get($data, "sections.locationTitle")); ?></div>
            </div>
            <div class="sec-body">
                <a
                    class="address-link"
                    href="<?= h(data_get($data, "shop.maps", "#")); ?>"
                    onclick="return (openMaps(), !1);"
                >
                    <div class="addr-icon-wrap">
                        <?= get_icon("location", "ic", "#c0392b"); ?>
                    </div>
                    <div class="addr-text">
                        <strong id="location-line1"><?= h(data_get($data, "location.line1")); ?></strong>
                        <span id="location-line2"><?= h(data_get($data, "location.line2")); ?></span>
                        <span class="map-btn">
                            <?= get_icon("map-arrow", "ic-sm", "currentColor"); ?>
                            <span id="location-map-label"><?= h(data_get($data, "location.mapButtonLabel")); ?></span>
                        </span>
                    </div>
                </a>
            </div>
        </div>
        <?php endif; ?>

        <?php if (isSectionEnabled($data, 'socialLinks')): ?>
        <div class="sec">
            <div class="sec-header">
                <div class="sec-icon">
                    <?= get_icon("globe", "ic", "currentColor"); ?>
                </div>
                <div class="sec-title" id="social-title"><?= h(data_get($data, "sections.socialTitle")); ?></div>
            </div>
            <div class="sec-body">
                <div class="social-list" id="social-list">
                    <?php foreach ($data["socialLinks"] ?? [] as $index => $item): ?>
                        <?php
                        $type = $item["type"] ?? "whatsapp";
                        $iconKey = in_array($type, ["whatsapp", "facebook", "instagram", "youtube"], true)
                            ? $type
                            : "whatsapp";
                        $socialClass = $socialClassMap[$iconKey] ?? "";
                        ?>
                        <div class="social-item" data-index="<?= h((string) $index); ?>">
                            <div class="s-ico <?= h($socialClass); ?>">
                                <?= get_icon($iconKey, "ic", "currentColor"); ?>
                            </div>
                            <div>
                                <div class="s-name"><?= h($item["name"] ?? ""); ?></div>
                                <div class="s-val"><?= h($item["value"] ?? ""); ?></div>
                            </div>
                            <div class="s-arrow">
                                <?= get_icon("chevron-right", "ic-sm", "currentColor"); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (isSectionEnabled($data, 'services')): ?>
        <div class="sec">
            <div class="sec-header">
                <div class="sec-icon">
                    <?= get_icon("services", "ic", "currentColor"); ?>
                </div>
                <div class="sec-title" id="services-title"><?= h(data_get($data, "sections.servicesTitle")); ?></div>
            </div>
            <div class="sec-body">
                <div class="services-grid" id="services-grid">
                    <?php foreach ($data["services"] ?? [] as $service): ?>
                        <?php $serviceImage = $service["image"] ?? $fallbackImage; ?>
                        <div class="svc-card">
                            <div class="svc-img">
                                <div
                                    class="svc-img-placeholder"
                                    style="background:url('<?= h($serviceImage); ?>') center/cover no-repeat"
                                ></div>
                            </div>
                            <div class="svc-body">
                                <div class="svc-name"><?= h($service["name"] ?? ""); ?></div>
                                <div class="svc-desc"><?= h($service["description"] ?? ""); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (isSectionEnabled($data, 'products')): ?>
        <div class="sec">
            <div class="sec-header">
                <div class="sec-icon">
                    <?= get_icon("products", "ic", "currentColor"); ?>
                </div>
                <div class="sec-title" id="products-title"><?= h(data_get($data, "sections.productsTitle")); ?></div>
            </div>
            <div class="sec-body">
                <div class="products-grid" id="productsGrid">
                    <?php foreach ($data["products"] ?? [] as $item): ?>
                        <?php
                        $tag = $item["tag"] ?? "";
                        $tagColor = $item["tagColor"] ?? "";
                        $background = $item["bg"] ?? "";
                        $productId = $item["id"] ?? "";
                        ?>
                        <div class="prod-card">
                            <div class="prod-img">
                                <div
                                    class="prod-img-placeholder"
                                    style="background:<?= h($background); ?>;height:100%"
                                ></div>
                                <?php if (!empty($tag)): ?>
                                    <span
                                        class="prod-tag"
                                        style="background:<?= h($tagColor); ?>;color:#fff"
                                    ><?= h($tag); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="prod-body">
                                <div class="prod-name"><?= h($item["name"] ?? ""); ?></div>
                                <div class="prod-desc"><?= h($item["desc"] ?? ""); ?></div>
                                <div class="prod-footer">
                                    <div>
                                        <span class="prod-price">₹<?= h($item["price"] ?? ""); ?></span>
                                        <?php if (!empty($item["oldPrice"])): ?>
                                            <span class="prod-old">₹<?= h($item["oldPrice"] ?? ""); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="qty-ctrl">
                                        <button
                                            class="qty-btn"
                                            onclick="changeQty(<?= h($productId); ?>,-1)"
                                        >
                                            <?= get_icon("minus", "", "currentColor"); ?>
                                        </button>
                                        <span class="qty-num" id="qty-<?= h($productId); ?>">0</span>
                                        <button
                                            class="qty-btn"
                                            onclick="changeQty(<?= h($productId); ?>,1)"
                                        >
                                            <?= get_icon("plus", "", "currentColor"); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (isSectionEnabled($data, 'gallery')): ?>
        <div class="sec">
            <div class="sec-header">
                <div class="sec-icon">
                    <?= get_icon("gallery", "ic", "currentColor"); ?>
                </div>
                <div class="sec-title" id="gallery-title"><?= h(data_get($data, "sections.galleryTitle")); ?></div>
            </div>
            <div class="sec-body">
                <div class="gallery-grid" id="gallery-grid">
                    <?php foreach ($data["gallery"] ?? [] as $item): ?>
                        <?php $image = $item["image"] ?? $fallbackImage; ?>
                        <div class="g-item">
                            <img src="<?= h($image); ?>" alt="" style="width:100%;height:100%;object-fit:cover;display:block;" />
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (isSectionEnabled($data, 'hours')): ?>
        <div class="sec">
            <div class="sec-header">
                <div class="sec-icon">
                    <?= get_icon("clock", "ic", "currentColor"); ?>
                </div>
                <div class="sec-title" id="hours-title"><?= h(data_get($data, "sections.hoursTitle")); ?></div>
            </div>
            <div class="sec-body">
                <div class="today-badge">
                    <?= get_icon("clock", "", "currentColor"); ?>
                    <span id="hours-badge-label"><?= h(data_get($data, "businessHours.badge")); ?></span>
                </div>
                <table class="hours-table">
                    <tbody id="hours-rows">
                        <?php foreach ($data["businessHours"]["days"] ?? [] as $row): ?>
                            <?php
                            $isOpen = !empty($row["open"]);
                            $time = $row["time"] ?? "";
                            $isClosed = !$isOpen || stripos($time, "closed") !== false;
                            ?>
                            <tr class="<?= $isOpen ? "open-row" : ""; ?>">
                                <td class="day"><?= h($row["day"] ?? ""); ?></td>
                                <td class="time<?= $isClosed ? " closed" : ""; ?>">
                                    <?= h($time); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="suggest-link" onclick="emailShop()">
                    <?= get_icon("info", "ic-sm", "currentColor"); ?>
                    <span id="suggest-hours-label"><?= h(data_get($data, "businessHours.suggestLabel")); ?></span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (isSectionEnabled($data, 'qr')): ?>
        <div class="sec">
            <div class="sec-header">
                <div class="sec-icon">
                    <?= get_icon("qr", "ic", "currentColor"); ?>
                </div>
                <div class="sec-title" id="qr-title"><?= h(data_get($data, "sections.qrTitle")); ?></div>
            </div>
            <div class="sec-body">
                <div class="qr-card-inner">
                    <p
                        id="qr-description"
                        style="
                            font-size: 0.79rem;
                            color: var(--muted);
                            margin-bottom: 0.2rem;
                        "
                    ><?= h(data_get($data, "qr.description")); ?></p>
                    <div id="vcardQR"></div>
                    <div class="qr-actions">
                        <button class="qr-btn" onclick="downloadQR()">
                            <?= get_icon("download", "ic-sm", "currentColor"); ?>
                            <span id="qr-download-label"><?= h(data_get($data, "qr.downloadLabel")); ?></span>
                        </button>
                        <button class="qr-btn" onclick="copyLink()">
                            <?= get_icon("link", "ic-sm", "currentColor"); ?>
                            <span id="qr-copy-label"><?= h(data_get($data, "qr.copyLabel")); ?></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (isSectionEnabled($data, 'payments')): ?>
        <div class="sec">
            <div class="sec-header">
                <div class="sec-icon">
                    <?= get_icon("payment", "ic", "currentColor"); ?>
                </div>
                <div class="sec-title" id="payment-title"><?= h(data_get($data, "sections.paymentTitle")); ?></div>
            </div>
            <div class="sec-body">
                <div class="payment-list" id="payment-list">
                    <?php foreach ($data["paymentMethods"] ?? [] as $item): ?>
                        <?php
                        $type = $item["type"] ?? "";
                        $iconKey = in_array($type, ["upi", "bank", "cash"], true) ? $type : "";
                        if ($iconKey === "") {
                            $label = strtolower((string) ($item["name"] ?? ""));
                            if (str_contains($label, "upi") || str_contains($label, "qr")) {
                                $iconKey = "upi";
                            } elseif (str_contains($label, "bank") || str_contains($label, "card")) {
                                $iconKey = "bank";
                            } elseif (str_contains($label, "cash")) {
                                $iconKey = "cash";
                            }
                        }
                        if ($iconKey === "") {
                            $iconKey = "cash";
                        }
                        $stroke = $paymentStrokeMap[$iconKey] ?? "currentColor";
                        ?>
                        <div class="pay-item">
                            <div class="pay-icon-wrap">
                                <?= get_icon("payment-" . $iconKey, "ic", $stroke); ?>
                            </div>
                            <div>
                                <div class="pay-name"><?= h($item["name"] ?? ""); ?></div>
                                <div class="pay-detail"><?= h($item["detail"] ?? ""); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (isSectionEnabled($data, 'contact')): ?>
        <div class="sec">
            <div class="sec-header">
                <div class="sec-icon">
                    <?= get_icon("mail", "ic", "currentColor"); ?>
                </div>
                <div class="sec-title" id="contact-title"><?= h(data_get($data, "sections.contactTitle")); ?></div>
            </div>
            <div class="sec-body">
                <div id="contactForm">
                    <div class="bf-row">
                        <div class="bf-group">
                            <label
                                class="bf-label"
                                id="contact-label-name"
                            ><?= h(data_get($data, "contactForm.labels.name")); ?></label
                            ><input
                                class="bf-input"
                                id="cName"
                                placeholder="<?= h(data_get($data, "contactForm.placeholders.name")); ?>"
                            />
                        </div>
                        <div class="bf-group">
                            <label
                                class="bf-label"
                                id="contact-label-mobile"
                            ><?= h(data_get($data, "contactForm.labels.mobile")); ?></label
                            ><input
                                class="bf-input"
                                id="cPhone"
                                type="tel"
                                placeholder="<?= h(data_get($data, "contactForm.placeholders.mobile")); ?>"
                            />
                        </div>
                    </div>
                    <div class="bf-group">
                        <label
                            class="bf-label"
                            id="contact-label-email"
                        ><?= h(data_get($data, "contactForm.labels.email")); ?></label
                        ><input
                            class="bf-input"
                            id="cEmail"
                            placeholder="<?= h(data_get($data, "contactForm.placeholders.email")); ?>"
                        />
                    </div>
                    <div class="bf-group">
                        <label
                            class="bf-label"
                            id="contact-label-message"
                        ><?= h(data_get($data, "contactForm.labels.message")); ?></label
                        ><textarea
                            class="bf-input"
                            id="cMsg"
                            placeholder="<?= h(data_get($data, "contactForm.placeholders.message")); ?>"
                        ></textarea>
                    </div>
                    <button class="cf-submit" onclick="submitContact()">
                        <?= get_icon("send", "ic", "#fff"); ?>
                        <span id="contact-submit-label"><?= h(data_get($data, "contactForm.submitLabel")); ?></span>
                    </button>
                </div>
                <div
                    id="contactSuccess"
                    style="
                        display: none;
                        text-align: center;
                        padding: 1.8rem 1rem;
                    "
                >
                    <?= get_icon("check-large", "", "#2e7d32"); ?>
                    <div
                        id="contact-success-title"
                        style="
                            font-size: 0.98rem;
                            font-weight: 700;
                            color: var(--text);
                            margin-bottom: 0.4rem;
                        "
                    ><?= h(data_get($data, "contactForm.successTitle")); ?></div>
                    <div
                        id="contact-success-desc"
                        style="
                            font-size: 0.8rem;
                            color: var(--muted);
                            margin-bottom: 1rem;
                        "
                    ><?= h(data_get($data, "contactForm.successDescription")); ?></div>
                    <button class="cf-submit" onclick="resetContact()">
                        <?= get_icon("reset", "ic", "#fff"); ?>
                        <span id="contact-success-btn-label"><?= h(data_get($data, "contactForm.successButtonLabel")); ?></span>
                    </button>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="vcard-footer">
            <p id="footer-copy">
                <?= h(data_get($data, "footer.copyright")); ?>
                <strong><?= h(data_get($data, "footer.brand")); ?></strong>
                ·
                <?= h(data_get($data, "footer.rights")); ?>
            </p>
            <p
                id="footer-powered"
                style="margin-top: 0.3rem; font-size: 0.68rem"
            >
                <?= h(data_get($data, "footer.poweredBy")); ?>
                <strong><?= h(data_get($data, "footer.poweredBrand")); ?></strong>
            </p>
        </div>

        <div class="float-bar">
            <button class="fab call-fab" onclick="callShop()">
                <?= get_icon("phone", "ic-lg", "currentColor"); ?>
                <span id="float-call-label"><?= h(data_get($data, "floatingBar.call")); ?></span>
            </button>
            <button class="fab save-fab" onclick="saveContact()">
                <?= get_icon("save", "ic-lg", "currentColor"); ?>
                <span id="float-save-label"><?= h(data_get($data, "floatingBar.save")); ?></span>
            </button>
            <button class="fab wa-fab" onclick="openWA()">
                <?= get_icon("whatsapp", "ic-lg", "currentColor"); ?>
                <span id="float-wa-label"><?= h(data_get($data, "floatingBar.whatsapp")); ?></span>
            </button>
            <div class="fab cart-fab fab-wrap" onclick="openCart()">
                <div class="cart-badge" id="cartBadge"></div>
                <?= get_icon("cart", "ic-lg", "#b5341a"); ?>
                <span
                    id="float-cart-label"
                    style="font-size: 0.67rem; font-weight: 700; color: #b5341a"
                ><?= h(data_get($data, "floatingBar.cart")); ?></span>
            </div>
        </div>

        <div
            class="cart-overlay"
            id="cartOverlay"
            onclick="closeCartOutside(event)"
        >
            <div class="cart-box">
                <div class="cart-header">
                    <div class="cart-title">
                        <?= get_icon("cart", "ic", "#2e1503"); ?>
                        <span id="cart-title"><?= h(data_get($data, "cart.title")); ?></span>
                    </div>
                    <button class="cart-close" onclick="closeCart()">
                        <?= get_icon("close", "ic-sm", "currentColor"); ?>
                    </button>
                </div>
                <div id="cartBody">
                    <div class="cart-empty">
                        <?= get_icon("cart-thin", "", "currentColor"); ?>
                        <span id="cart-empty-message"><?= text_with_breaks(data_get($data, "cart.emptyMessage")); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-overlay" id="shareModal" onclick="closeShare(event)">
            <div class="modal-box">
                <div class="modal-title" id="share-modal-title"><?= h(data_get($data, "shareModal.title")); ?></div>
                <div class="share-options">
                    <div class="share-opt" onclick="shareWA()">
                        <?= get_icon("whatsapp", "", "#128c7e"); ?>
                        <span id="share-opt-wa"><?= h(data_get($data, "shareModal.whatsapp")); ?></span>
                    </div>
                    <div class="share-opt" onclick="copyLink()">
                        <?= get_icon("link", "", "#666"); ?>
                        <span id="share-opt-copy"><?= h(data_get($data, "shareModal.copy")); ?></span>
                    </div>
                    <div class="share-opt" onclick="shareFB()">
                        <?= get_icon("facebook", "", "#1877f2"); ?>
                        <span id="share-opt-fb"><?= h(data_get($data, "shareModal.facebook")); ?></span>
                    </div>
                    <div class="share-opt" onclick="shareNative()">
                        <?= get_icon("share", "", "#666"); ?>
                        <span id="share-opt-more"><?= h(data_get($data, "shareModal.more")); ?></span>
                    </div>
                </div>
                <button
                    class="modal-close-btn"
                    onclick="
                        document
                            .getElementById('shareModal')
                            .classList.remove('show')
                    "
                >
                    <span id="share-cancel-label"><?= h(data_get($data, "shareModal.cancel")); ?></span>
                </button>
            </div>
        </div>

        <div class="toast" id="toast"></div>

        <!-- Icon Templates (hidden) -->
        <div class="icon-templates" style="display:none;">
            <span id="share"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('share')); ?></svg></span>
            <span id="save"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('save')); ?></svg></span>
            <span id="phone"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('phone')); ?></svg></span>
            <span id="whatsapp"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('whatsapp')); ?></svg></span>
            <span id="email"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('email')); ?></svg></span>
            <span id="directions"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('directions')); ?></svg></span>
            <span id="location"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('location')); ?></svg></span>
            <span id="globe"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('globe')); ?></svg></span>
            <span id="services"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('services')); ?></svg></span>
            <span id="products"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('products')); ?></svg></span>
            <span id="gallery"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('gallery')); ?></svg></span>
            <span id="clock"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('clock')); ?></svg></span>
            <span id="qr"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('qr')); ?></svg></span>
            <span id="payment"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('payment')); ?></svg></span>
            <span id="mail"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('mail')); ?></svg></span>
            <span id="map-arrow"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('map-arrow')); ?></svg></span>
            <span id="info"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('info')); ?></svg></span>
            <span id="download"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('download')); ?></svg></span>
            <span id="link"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('link')); ?></svg></span>
            <span id="send"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('send')); ?></svg></span>
            <span id="check"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('check')); ?></svg></span>
            <span id="check-large"><?= get_icon('check-large'); ?></span>
            <span id="reset"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('reset')); ?></svg></span>
            <span id="cart"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('cart')); ?></svg></span>
            <span id="cart-thin"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('cart-thin')); ?></svg></span>
            <span id="close"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('close')); ?></svg></span>
            <span id="facebook"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('facebook')); ?></svg></span>
            <span id="instagram"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('instagram')); ?></svg></span>
            <span id="youtube"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('youtube')); ?></svg></span>
            <span id="chevron-right"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('chevron-right')); ?></svg></span>
            <span id="plus"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('plus')); ?></svg></span>
            <span id="minus"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('minus')); ?></svg></span>
            <span id="ui_arrow_right"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('ui_arrow_right')); ?></svg></span>
            <span id="ui_check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('ui_check')); ?></svg></span>
            <span id="ui_star"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('ui_star')); ?></svg></span>
            <span id="ui_cart"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('ui_cart')); ?></svg></span>
            <span id="payment-upi"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('payment-upi')); ?></svg></span>
            <span id="payment-bank"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('payment-bank')); ?></svg></span>
            <span id="payment-cash"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"><?php echo preg_replace('/viewBox.*?>/', '', get_icon('payment-cash')); ?></svg></span>
        </div>

        <script>
            window.APP_DATA = <?= json_encode(
                $data,
                JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
            ); ?>;
            window.__VCARD_SUBDOMAIN__ = <?= json_encode(basename(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH))); ?>;
        </script>
        <script src="script.js?v=<?= time(); ?>"></script>
    </body>
</html>
