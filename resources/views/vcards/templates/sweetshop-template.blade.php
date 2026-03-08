@php
    require_once resource_path('views/vcards/icons/sweetshop-template.php');
    $assets = $data["assets"] ?? [];
    $assets = is_array($assets) ? $assets : [];
    $fallbackImage = $assets["fallbackImage"] ?? "";
    $bannerImage = $assets["bannerImage"] ?? $fallbackImage;
    $profileImage = $assets["profileImage"] ?? $fallbackImage;
    $shop = $data["shop"] ?? [];
    $shop = is_array($shop) ? $shop : [];
    $website = $shop["website"] ?? "";
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
@endphp
<!doctype html>
<html lang="en">
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta charset="UTF-8" />
        <meta
            name="viewport"
            content="width=device-width,initial-scale=1,maximum-scale=1"
        />
        <title>{{ data_get($data, "meta.title") }}</title>
        <meta name="description" content="{{ data_get($data, 'meta.description', '') }}">
        <meta name="keywords" content="{{ data_get($data, 'meta.keywords', '') }}">
        <meta property="og:title" content="{{ data_get($data, 'meta.title', '') }}">
        <meta property="og:description" content="{{ data_get($data, 'meta.description', '') }}">
        @if(data_get($data, 'meta.og_image'))
        <meta property="og:image" content="{{ url(data_get($data, 'meta.og_image')) }}">
        @endif
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
        <link rel="stylesheet" href="{{ $assetBase }}style.css" />
        @if(!empty($vcard->head_script))
        {!! $vcard->head_script !!}
        @endif
    </head>
    <body>
        <div class="banner">
            <img
                class="cover"
                id="banner-cover"
                src="{{ $bannerImage }}"
                alt="{{ data_get($data, "meta.bannerAlt") }}"
            />
            <div class="banner-overlay"></div>
            <div class="banner-top-bar">
                <button class="share-btn" onclick="openShare()">
                    {!! getIcon("share", "ic-sm", "currentColor") !!}
                    <span id="top-share-label">Share</span>
                </button>
                <button class="save-btn-top" onclick="saveContact()">
                    {!! getIcon("save", "ic-sm", "currentColor") !!}
                    <span id="top-save-label">Save Contact</span>
                </button>
            </div>
        </div>

        <div class="profile-card">
            <div class="profile-avatar-wrap">
                <div class="profile-avatar">
                    <img
                        id="profile-image"
                        src="{{ $profileImage }}"
                        alt="{{ data_get($data, "meta.profileAlt") }}"
                    />
                </div>
            </div>
            <div class="profile-name" id="profile-name">{{ data_get($data, "profile.name") }}</div>
            <div class="profile-role" id="profile-role">{{ data_get($data, "profile.role") }}</div>
            <div class="profile-bio" id="profile-bio">{{ data_get($data, "profile.bio") }}</div>

            <div class="profile-action-btns">
                <button class="pab call" onclick="callShop()">
                    {!! getIcon("phone", "ic", "#2e7d32") !!}
                    <span id="action-call-label">Call</span>
                </button>
                <button class="pab whatsapp" onclick="openWA()">
                    {!! getIcon("whatsapp", "ic", "#1b5e20") !!}
                    <span id="action-whatsapp-label">WhatsApp</span>
                </button>
                <button class="pab save" onclick="saveContact()">
                    {!! getIcon("save", "ic", "#e65100") !!}
                    <span id="action-save-label">Save</span>
                </button>
                <button class="pab email" onclick="emailShop()">
                    {!! getIcon("email", "ic", "#1565c0") !!}
                    <span id="action-email-label">Email</span>
                </button>
                <button class="pab direction" onclick="openMaps()">
                    {!! getIcon("directions", "ic", "#880e4f") !!}
                    <span id="action-directions-label">Directions</span>
                </button>
                <button class="pab share" onclick="openShare()">
                    {!! getIcon("share", "ic", "#6a1b9a") !!}
                    <span id="action-share-label">Share</span>
                </button>
            </div>
        </div>

        <div class="section-space"></div>

        @if(vcard_section_enabled($data, 'location'))
        <div class="sec">
            <div class="sec-header">
                <div class="sec-icon">
                    {!! getIcon("location", "ic", "currentColor") !!}
                </div>
                <div class="sec-title" id="location-title">Our Location</div>
            </div>
            <div class="sec-body">
                <a
                    class="address-link"
                    href="{{ data_get($data, "shop.maps", "#") }}"
                    onclick="return (openMaps(), !1);"
                >
                    <div class="addr-icon-wrap">
                        {!! getIcon("location", "ic", "#c0392b") !!}
                    </div>
                    <div class="addr-text">
                        <strong id="location-line1">{{ data_get($data, "location.line1") }}</strong>
                        <span id="location-line2">{{ data_get($data, "location.line2") }}</span>
                        <span class="map-btn">
                            {!! getIcon("map-arrow", "ic-sm", "currentColor") !!}
                            <span id="location-map-label">Open in Maps</span>
                        </span>
                    </div>
                </a>
            </div>
        </div>
        @endif

        @if(vcard_section_enabled($data, 'socialLinks'))
        <div class="sec">
            <div class="sec-header">
                <div class="sec-icon">
                    {!! getIcon("globe", "ic", "currentColor") !!}
                </div>
                <div class="sec-title" id="social-title">Social Links</div>
            </div>
            <div class="sec-body">
                <div class="social-list" id="social-list">
                    @foreach($data["socialLinks"] ?? [] as $index => $item)
                        @php
                        $type = $item["type"] ?? "whatsapp";
                        $iconKey = in_array($type, ["whatsapp", "facebook", "instagram", "youtube"], true)
                            ? $type
                            : "whatsapp";
                        $socialClass = $socialClassMap[$iconKey] ?? "";
@endphp
                        <div class="social-item" data-index="{{ (string) $index }}">
                            <div class="s-ico {{ $socialClass }}">
                                {!! getIcon($iconKey, "ic", "currentColor") !!}
                            </div>
                            <div>
                                <div class="s-name">{{ $item["name"] ?? "" }}</div>
                                <div class="s-val">{{ $item["value"] ?? "" }}</div>
                            </div>
                            <div class="s-arrow">
                                {!! getIcon("chevron-right", "ic-sm", "currentColor") !!}
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
                    {!! getIcon("services", "ic", "currentColor") !!}
                </div>
                <div class="sec-title" id="services-title">Our Services</div>
            </div>
            <div class="sec-body">
                <div class="services-grid" id="services-grid">
                    @foreach($data["services"] ?? [] as $service)
                        @php $serviceImage = $service["image"] ?? $fallbackImage; @endphp
                        <div class="svc-card">
                            <div class="svc-img">
                                <div
                                    class="svc-img-placeholder"
                                    style="background:url('{{ $serviceImage }}') center/cover no-repeat"
                                ></div>
                            </div>
                            <div class="svc-body">
                                <div class="svc-name">{{ $service["name"] ?? "" }}</div>
                                <div class="svc-desc">{{ $service["description"] ?? "" }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        @if(vcard_section_enabled($data, 'products'))
        <div class="sec">
            <div class="sec-header">
                <div class="sec-icon">
                    {!! getIcon("products", "ic", "currentColor") !!}
                </div>
                <div class="sec-title" id="products-title">Our Products</div>
            </div>
            <div class="sec-body">
                <div class="prod-tabs" id="prodTabs"></div>
                <div class="products-grid" id="productsGrid">
                    @foreach($data["products"] ?? [] as $item)
                        @php
                        $tag = $item["tag"] ?? "";
                        $tagColor = $item["tagColor"] ?? "";
                        $background = $item["bg"] ?? "";
                        $productId = $item["id"] ?? "";
@endphp
                        <div class="prod-card">
                            <div class="prod-img">
                                @php
                                $prodImg = $item["product_image"] ?? "";
                                // Strip CSS url() wrapper if stored with it
                                if ($prodImg && preg_match('/url\([\'"]?(.*?)[\'"]?\)/i', $prodImg, $_spm)) { $prodImg = $_spm[1]; }
                            @endphp
                                <div
                                    class="prod-img-placeholder"
                                    style="background:{{ $prodImg ? 'url(\'' . e($prodImg) . '\') center/cover no-repeat' : $background }};height:100%"
                                ></div>
                                @if(!empty($tag))
                                    <span
                                        class="prod-tag"
                                        style="background:{{ $tagColor }};color:#fff"
                                    >{{ $tag }}</span>
                                @endif
                            </div>
                            <div class="prod-body">
                                <div class="prod-name">{{ $item["name"] ?? "" }}</div>
                                <div class="prod-desc">{{ $item["desc"] ?? "" }}</div>
                                <div class="prod-footer">
                                    <div>
                                        <span class="prod-price">₹{{ $item["price"] ?? "" }}</span>
                                        @if(!empty($item["oldPrice"]))
                                            <span class="prod-old">₹{{ $item["oldPrice"] ?? "" }}</span>
                                        @endif
                                    </div>
                                    <div class="qty-ctrl">
                                        <button
                                            class="qty-btn"
                                            onclick="changeQty({{ $productId }},-1)"
                                        >
                                            {!! getIcon("minus", "", "currentColor") !!}
                                        </button>
                                        <span class="qty-num" id="qty-{{ $productId }}">0</span>
                                        <button
                                            class="qty-btn"
                                            onclick="changeQty({{ $productId }},1)"
                                        >
                                            {!! getIcon("plus", "", "currentColor") !!}
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

        @if(vcard_section_enabled($data, 'gallery'))
        <div class="sec">
            <div class="sec-header">
                <div class="sec-icon">
                    {!! getIcon("gallery", "ic", "currentColor") !!}
                </div>
                <div class="sec-title" id="gallery-title">Gallery</div>
            </div>
            <div class="sec-body">
                <div class="gallery-grid" id="gallery-grid">
                    @foreach($data["gallery"] ?? [] as $item)
                        @php $image = $item["image"] ?? $fallbackImage; @endphp
                        <div class="g-item">
                            <img src="{{ $image }}" alt="" style="width:100%;height:100%;object-fit:cover;display:block;" />
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
                    {!! getIcon("clock", "ic", "currentColor") !!}
                </div>
                <div class="sec-title" id="hours-title">Business Hours</div>
            </div>
            <div class="sec-body">
                <div class="today-badge">
                    {!! getIcon("clock", "", "currentColor") !!}
                    <span id="hours-badge-label">Open Now</span>
                </div>
                <table class="hours-table">
                    <tbody id="hours-rows">
                        @foreach($data["businessHours"]["days"] ?? [] as $row)
                            @php
                            $isOpen = !empty($row["open"]);
                            $time = $row["time"] ?? "";
                            $isClosed = !$isOpen || stripos($time, "closed") !== false;
@endphp
                            <tr class="{{ $isOpen ? "open-row" : "" }}">
                                <td class="day">{{ $row["day"] ?? "" }}</td>
                                <td class="time{{ $isClosed ? " closed" : "" }}">
                                    {{ $time }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="suggest-link" onclick="emailShop()">
                    {!! getIcon("info", "ic-sm", "currentColor") !!}
                    <span id="suggest-hours-label">Suggest new hours</span>
                </div>
            </div>
        </div>
        @endif

        @if(vcard_section_enabled($data, 'payments'))
        <div class="sec">
            <div class="sec-header">
                <div class="sec-icon">
                    {!! getIcon("payment", "ic", "currentColor") !!}
                </div>
                <div class="sec-title" id="payment-title">Payment Methods</div>
            </div>
            <div class="sec-body">
                <div class="payment-list" id="payment-list">
                    @foreach($data["paymentMethods"] ?? [] as $item)
                        @php
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
@endphp
                        <div class="pay-item">
                            <div class="pay-icon-wrap">
                                {!! getIcon("payment-" . $iconKey, "ic", $stroke) !!}
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
        @endif

        @if(vcard_section_enabled($data, 'contact'))
        <div class="sec">
            <div class="sec-header">
                <div class="sec-icon">
                    {!! getIcon("mail", "ic", "currentColor") !!}
                </div>
                <div class="sec-title" id="contact-title">Contact Us</div>
            </div>
            <div class="sec-body">
                <div id="contactForm">
                    <div class="bf-row">
                        <div class="bf-group">
                            <label
                                class="bf-label"
                                id="contact-label-name"
                            >{{ data_get($data, "contactForm.labels.name") }}</label
                            ><input
                                class="bf-input"
                                id="cName"
                                placeholder="{{ data_get($data, "contactForm.placeholders.name") }}"
                            />
                        </div>
                        <div class="bf-group">
                            <label
                                class="bf-label"
                                id="contact-label-mobile"
                            >{{ data_get($data, "contactForm.labels.mobile") }}</label
                            ><input
                                class="bf-input"
                                id="cPhone"
                                type="tel"
                                placeholder="{{ data_get($data, "contactForm.placeholders.mobile") }}"
                            />
                        </div>
                    </div>
                    <div class="bf-group">
                        <label
                            class="bf-label"
                            id="contact-label-email"
                        >{{ data_get($data, "contactForm.labels.email") }}</label
                        ><input
                            class="bf-input"
                            id="cEmail"
                            placeholder="{{ data_get($data, "contactForm.placeholders.email") }}"
                        />
                    </div>
                    <div class="bf-group">
                        <label
                            class="bf-label"
                            id="contact-label-message"
                        >{{ data_get($data, "contactForm.labels.message") }}</label
                        ><textarea
                            class="bf-input"
                            id="cMsg"
                            placeholder="{{ data_get($data, "contactForm.placeholders.message") }}"
                        ></textarea>
                    </div>
                    <button class="cf-submit" onclick="submitContact()">
                        {!! getIcon("send", "ic", "#fff") !!}
                        <span id="contact-submit-label">Send Message</span>
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
                    {!! getIcon("check-large", "", "#2e7d32") !!}
                    <div
                        id="contact-success-title"
                        style="
                            font-size: 0.98rem;
                            font-weight: 700;
                            color: var(--text);
                            margin-bottom: 0.4rem;
                        "
                    >{{ data_get($data, "contactForm.successTitle") }}</div>
                    <div
                        id="contact-success-desc"
                        style="
                            font-size: 0.8rem;
                            color: var(--muted);
                            margin-bottom: 1rem;
                        "
                    >{{ data_get($data, "contactForm.successDescription") }}</div>
                    <button class="cf-submit" onclick="resetContact()">
                        {!! getIcon("reset", "ic", "#fff") !!}
                        <span id="contact-success-btn-label">Send Another</span>
                    </button>
                </div>
            </div>
        </div>
        @endif

        @if(vcard_section_enabled($data, 'qr'))
        <div class="sec">
            <div class="sec-header">
                <div class="sec-icon">
                    {!! getIcon("qr", "ic", "currentColor") !!}
                </div>
                <div class="sec-title" id="qr-title">Scan &amp; Save Our Contact</div>
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
                    >{{ data_get($data, "qr.description") }}</p>
                    <div id="vcardQR"></div>
                    <div class="qr-actions">
                        <button class="qr-btn" onclick="downloadQR()">
                            {!! getIcon("download", "ic-sm", "currentColor") !!}
                            <span id="qr-download-label">Download QR</span>
                        </button>
                        <button class="qr-btn" onclick="copyLink()">
                            {!! getIcon("link", "ic-sm", "currentColor") !!}
                            <span id="qr-copy-label">Copy Link</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="vcard-footer">
            <p id="footer-copy">
                {{ data_get($data, "footer.copyright") }}
                <strong>{{ data_get($data, "footer.brand") }}</strong>
                ·
                {{ data_get($data, "footer.rights") }}
            </p>
            <p
                id="footer-powered"
                style="margin-top: 0.3rem; font-size: 0.68rem"
            >
                Powered by <a href="{{ config('app.url') }}" target="_blank" rel="noopener" style="color:inherit;text-decoration:none;font-weight:600;">{{ config('app.name') }}</a>
            </p>
        </div>

        <div class="float-bar">
            <button class="fab call-fab" onclick="callShop()">
                {!! getIcon("phone", "ic-lg", "currentColor") !!}
                <span id="float-call-label">Call</span>
            </button>
            <button class="fab save-fab" onclick="saveContact()">
                {!! getIcon("save", "ic-lg", "currentColor") !!}
                <span id="float-save-label">Save Contact</span>
            </button>
            <button class="fab wa-fab" onclick="openWA()">
                {!! getIcon("whatsapp", "ic-lg", "currentColor") !!}
                <span id="float-wa-label">WhatsApp</span>
            </button>
            <div class="fab cart-fab fab-wrap" onclick="openCart()">
                <div class="cart-badge" id="cartBadge"></div>
                {!! getIcon("cart", "ic-lg", "#b5341a") !!}
                <span
                    id="float-cart-label"
                    style="font-size: 0.67rem; font-weight: 700; color: #b5341a"
                >Cart</span>
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
                        {!! getIcon("cart", "ic", "#2e1503") !!}
                        <span id="cart-title">Your Cart</span>
                    </div>
                    <button class="cart-close" onclick="closeCart()">
                        {!! getIcon("close", "ic-sm", "currentColor") !!}
                    </button>
                </div>
                <div id="cartBody">
                    <div class="cart-empty">
                        {!! getIcon("cart-thin", "", "currentColor") !!}
                        <span id="cart-empty-message">{!! data_get($data, "cart.emptyMessage") !!}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-overlay" id="shareModal" onclick="closeShare(event)">
            <div class="modal-box">
                <div class="modal-title" id="share-modal-title">Share This Card</div>
                <div class="share-options">
                    <div class="share-opt" onclick="shareWA()">
                        {!! getIcon("whatsapp", "", "#128c7e") !!}
                        <span id="share-opt-wa">WhatsApp</span>
                    </div>
                    <div class="share-opt" onclick="copyLink()">
                        {!! getIcon("link", "", "#666") !!}
                        <span id="share-opt-copy">Copy Link</span>
                    </div>
                    <div class="share-opt" onclick="shareFB()">
                        {!! getIcon("facebook", "", "#1877f2") !!}
                        <span id="share-opt-fb">Facebook</span>
                    </div>
                    <div class="share-opt" onclick="shareNative()">
                        {!! getIcon("share", "", "#666") !!}
                        <span id="share-opt-more">More Options</span>
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
                    <span id="share-cancel-label">Cancel</span>
                </button>
            </div>
        </div>

        <div class="toast" id="toast"></div>

        <!-- Icon Templates (hidden) -->
        <div class="icon-templates" style="display:none;">
            <span id="share"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('share')); ?></svg></span>
            <span id="save"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('save')); ?></svg></span>
            <span id="phone"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('phone')); ?></svg></span>
            <span id="whatsapp"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('whatsapp')); ?></svg></span>
            <span id="email"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('email')); ?></svg></span>
            <span id="directions"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('directions')); ?></svg></span>
            <span id="location"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('location')); ?></svg></span>
            <span id="globe"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('globe')); ?></svg></span>
            <span id="services"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('services')); ?></svg></span>
            <span id="products"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('products')); ?></svg></span>
            <span id="gallery"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('gallery')); ?></svg></span>
            <span id="clock"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('clock')); ?></svg></span>
            <span id="qr"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('qr')); ?></svg></span>
            <span id="payment"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('payment')); ?></svg></span>
            <span id="mail"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('mail')); ?></svg></span>
            <span id="map-arrow"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('map-arrow')); ?></svg></span>
            <span id="info"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('info')); ?></svg></span>
            <span id="download"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('download')); ?></svg></span>
            <span id="link"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('link')); ?></svg></span>
            <span id="send"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('send')); ?></svg></span>
            <span id="check"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('check')); ?></svg></span>
            <span id="check-large">{!! getIcon('check-large') !!}</span>
            <span id="reset"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('reset')); ?></svg></span>
            <span id="cart"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('cart')); ?></svg></span>
            <span id="cart-thin"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('cart-thin')); ?></svg></span>
            <span id="close"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('close')); ?></svg></span>
            <span id="facebook"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('facebook')); ?></svg></span>
            <span id="instagram"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('instagram')); ?></svg></span>
            <span id="youtube"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('youtube')); ?></svg></span>
            <span id="chevron-right"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('chevron-right')); ?></svg></span>
            <span id="plus"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('plus')); ?></svg></span>
            <span id="minus"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('minus')); ?></svg></span>
            <span id="ui_arrow_right"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('ui_arrow_right')); ?></svg></span>
            <span id="ui_check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('ui_check')); ?></svg></span>
            <span id="ui_star"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('ui_star')); ?></svg></span>
            <span id="ui_cart"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('ui_cart')); ?></svg></span>
            <span id="payment-upi"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('payment-upi')); ?></svg></span>
            <span id="payment-bank"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('payment-bank')); ?></svg></span>
            <span id="payment-cash"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor">@php echo preg_replace('/viewBox.* @endphp/', '', getIcon('payment-cash')); ?></svg></span>
        </div>

        <script>
            window.APP_DATA = {!! vcard_js_str($data) !!};
            window.__VCARD_SUBDOMAIN__ = {!! json_encode($subdomain) !!};
            window.__APP_URL__ = {!! json_encode('https://' . $vcard->subdomain . '.' . config('vcard.base_domain')) !!};
        </script>
        <script src="{{ $assetBase }}script.js"></script>
        @if(!empty($vcard->footer_script))
        {!! $vcard->footer_script !!}
        @endif
    </body>
</html>
