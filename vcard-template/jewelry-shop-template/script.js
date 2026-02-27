const $id = (id) => document.getElementById(id);
const tpl = (template, values = {}) =>
    (template || "").replace(/\{\{(\w+)\}\}/g, (_, key) => values[key] ?? "");
const sq = (value = "") =>
    String(value).replace(/\\/g, "\\\\").replace(/'/g, "\\'");
const money = (value) => Number(value || 0).toLocaleString("en-IN");
const pick = (path, fallback = "") =>
    path.split(".").reduce((acc, key) => acc?.[key], APP) ?? fallback;

const setText = (id, value) => {
    const el = $id(id);
    if (el) {
        el.textContent = value ?? "";
    }
};

const setHTML = (id, value) => {
    const el = $id(id);
    if (el) {
        el.innerHTML = value ?? "";
    }
};

const setAttr = (id, attr, value) => {
    const el = $id(id);
    if (el) {
        el.setAttribute(attr, value ?? "");
    }
};

const iconTpl = (id, fallbackId) => {
    const primary = $id(id)?.innerHTML || "";
    if (primary) {
        return primary;
    }
    return fallbackId ? $id(fallbackId)?.innerHTML || "" : "";
};

const SOCIAL_CLASSES = {
    whatsapp: "ic-wa",
    instagram: "ic-ig",
    facebook: "ic-fb",
    pinterest: "ic-pin",
    youtube: "ic-yt",
};

let APP = {};
let SHOP = {};
let PRODUCTS = [];
let currentCat = "all";
let cart = {};

const socialAction = (item) =>
    item.action === "openWA"
        ? "openWA()"
        : item.url
          ? `openExternal('${sq(item.url)}')`
          : "void(0)";

const fillStaticContent = () => {
    document.title = pick("meta.title");

    setText("banner-share", pick("banner.share"));
    setText("banner-save-contact", pick("banner.saveContact"));
    setText("banner-brand", pick("banner.brand"));
    setText("banner-subtitle", pick("banner.subtitle"));
    setText("banner-divider-symbol", pick("banner.dividerSymbol"));

    setText("profile-name", pick("profile.name"));
    setText("profile-role", pick("profile.role"));
    setText("profile-stars", pick("profile.stars"));
    setText("profile-rating", pick("profile.rating"));
    setText("profile-rating-count", pick("profile.ratingCount"));
    setText("profile-bio", pick("profile.bio"));

    setText("action-call", pick("profile.actions.call"));
    setText("action-whatsapp", pick("profile.actions.whatsapp"));
    setText("action-save", pick("profile.actions.save"));
    setText("action-email", pick("profile.actions.email"));
    setText("action-directions", pick("profile.actions.directions"));
    setText("action-share", pick("profile.actions.share"));

    setText("sec-title-collections", pick("sections.collections"));
    setText("sec-title-purity", pick("sections.purity"));
    setText("sec-title-certifications", pick("sections.certifications"));
    setText("sec-title-services", pick("sections.services"));
    setText("sec-title-customers", pick("sections.customers"));
    setText("sec-title-showroom", pick("sections.showroom"));
    setText("sec-title-hours", pick("sections.hours"));
    setText("sec-title-follow", pick("sections.follow"));
    setText("sec-title-enquiry", pick("sections.enquiry"));
    setText("sec-title-scan", pick("sections.scan"));

    setText("showroom-name", pick("showroom.name"));
    setText("showroom-line1", pick("showroom.line1"));
    setText("showroom-line2", pick("showroom.line2"));
    setText("showroom-map-label", pick("showroom.mapLabel"));

    setText("enquiry-submit-label", pick("enquiryForm.submitLabel"));
    setText("enquiry-success-icon", pick("enquiryForm.successIcon"));
    setText("enquiry-success-title", pick("enquiryForm.successTitle"));
    setText("enquiry-success-text", pick("enquiryForm.successText"));
    setText("enquiry-success-button", pick("enquiryForm.successButton"));

    setText("qr-description", pick("qr.description"));
    setText("qr-download-label", pick("qr.downloadLabel"));

    setText("footer-brand", pick("footer.brand"));
    setText("footer-line2", pick("footer.line2"));
    setText("footer-line3", pick("footer.line3"));
    setText("footer-line4", pick("footer.line4"));

    setText("bb-call", pick("bottomBar.call"));
    setText("bb-save", pick("bottomBar.save"));
    setText("bb-whatsapp", pick("bottomBar.whatsapp"));

    setText("cart-title", pick("cart.title"));
    setText("share-modal-title", pick("shareModal.title"));
    setText("share-wa-label", pick("shareModal.whatsapp"));
    setText("share-fb-label", pick("shareModal.facebook"));
    setText("share-copy-label", pick("shareModal.copy"));
    setText("toastMsg", pick("labels.toastDefault"));

    const img = $id("profile-image");
    if (img) {
        img.src = APP.assets?.profileImage || APP.assets?.fallbackImage || "";
        img.alt = APP.assets?.profileAlt || APP.profile?.name || "";
    }

    setAttr("eName", "placeholder", pick("enquiryForm.namePlaceholder"));
    setAttr("ePhone", "placeholder", pick("enquiryForm.phonePlaceholder"));
    setAttr("eEmail", "placeholder", pick("enquiryForm.emailPlaceholder"));
    setAttr("eBudget", "placeholder", pick("enquiryForm.budgetPlaceholder"));
    setAttr("eMsg", "placeholder", pick("enquiryForm.messagePlaceholder"));
};

const renderStats = () => {
    setHTML(
        "statsStrip",
        (APP.stats || [])
            .map(
                (item) => `
                    <div class="stat-item">
                        <div class="stat-num">${item.value || ""}</div>
                        <div class="stat-label">${item.label || ""}</div>
                    </div>`,
            )
            .join(""),
    );
};

const renderCategoryChips = () =>
    (APP.categories || [])
        .map(
            (cat) => `
                <div class="cat-chip${currentCat === cat.key ? " active" : ""}" onclick="filterCat(this, '${cat.key}')">
                    ${cat.label || ""}
                </div>`,
        )
        .join("");

const mountCategoryChips = () => {
    setHTML("catScroll", renderCategoryChips());
};

const renderPurity = () => {
    setHTML(
        "purityRow",
        (APP.purity?.items || [])
            .map(
                (item) => `
                    <div class="purity-item">
                        <div class="purity-karat">${item.karat || ""}</div>
                        <div class="purity-label">${item.label || ""}</div>
                    </div>`,
            )
            .join(""),
    );

    setText("purity-hallmark-emoji", pick("purity.hallmark.emoji"));
    setText("purity-hallmark-title", pick("purity.hallmark.title"));
    setText("purity-hallmark-separator", pick("purity.hallmark.separator"));
    setText("purity-hallmark-text", pick("purity.hallmark.text"));
};

const renderCertifications = () => {
    setHTML(
        "certGrid",
        (APP.certifications || [])
            .map(
                (item) => `
                    <div class="cert-item">
                        <div class="cert-ico" style="background:${item.bg || "#fff"}">${item.emoji || ""}</div>
                        <div class="cert-text">
                            <div class="cert-name">${item.name || ""}</div>
                            <div class="cert-sub">${item.sub || ""}</div>
                        </div>
                    </div>`,
            )
            .join(""),
    );
};

const renderServices = () => {
    setHTML(
        "servicesList",
        (APP.services || [])
            .map((item) => {
                const iconKey = item.icon || "star";
                const iconHtml = iconTpl(
                    `icon-service-${iconKey}`,
                    "icon-service-star",
                );
                return `
                    <div class="svc-item">
                        <div class="svc-ico">
                            ${iconHtml}
                        </div>
                        <div class="svc-info">
                            <div class="svc-name">${item.name || ""}</div>
                            <div class="svc-desc">${item.desc || ""}</div>
                        </div>
                    </div>`;
            })
            .join(""),
    );
};

const renderTestimonials = () => {
    setHTML(
        "testimonialsList",
        (APP.testimonials || [])
            .map(
                (item) => `
                    <div class="testi-card">
                        <div class="testi-stars">${item.stars || ""}</div>
                        <div class="testi-text">"${item.text || ""}"</div>
                        <div class="testi-author">${item.author || ""}</div>
                        <div class="testi-occasion">${item.occasion || ""}</div>
                    </div>`,
            )
            .join(""),
    );
};

const renderHours = () => {
    setHTML(
        "hoursTable",
        (APP.hours || [])
            .map((row) =>
                row.today
                    ? `
                        <tr class="today">
                            <td class="day">${row.day || ""} <span class="today-badge">${pick("labels.todayBadge")}</span></td>
                            <td class="time" style="color:var(--gold);font-weight:700">${row.time || ""}</td>
                        </tr>`
                    : `
                        <tr>
                            <td class="day">${row.day || ""}</td>
                            <td class="time">${row.time || ""}</td>
                        </tr>`,
            )
            .join(""),
    );
};

const renderFollowLinks = () => {
    setHTML(
        "socialList",
        (APP.followLinks || [])
            .map((item) => {
                const iconHtml = iconTpl(
                    `icon-social-${item.type}`,
                    "icon-social-whatsapp",
                );
                const iconClass = SOCIAL_CLASSES[item.type] || "ic-wa";
                const arrowIcon = iconTpl("icon-ui-arrow-right");
                return `
                    <div class="social-item ${item.type === "pinterest" ? "ic-pin" : ""}" onclick="${socialAction(item)}">
                        <div class="s-ico ${iconClass}">${iconHtml}</div>
                        <div>
                            <div class="s-name">${item.name || ""}</div>
                            <div class="s-val">${item.value || ""}</div>
                        </div>
                        <div class="s-arrow">${arrowIcon}</div>
                    </div>`;
            })
            .join(""),
    );
};

const renderEnquiryCategories = () => {
    const select = $id("eCategory");
    if (!select) {
        return;
    }

    select.innerHTML = `
        <option value="">${pick("enquiryForm.categoryPlaceholder")}</option>
        ${(APP.enquiryForm?.categories || [])
            .map((item) => `<option>${item}</option>`)
            .join("")}`;
};

function filterCat(el, key) {
    document.querySelectorAll(".cat-chip").forEach((chip) => {
        chip.classList.remove("active");
    });

    el.classList.add("active");
    currentCat = key;
    renderCollections();
}

function renderCollections() {
    const list =
        currentCat === "all"
            ? PRODUCTS
            : PRODUCTS.filter((item) => item.cat === currentCat);

    setHTML(
        "collectionsGrid",
        list
            .map((item) => {
                const starIcon = iconTpl("icon-service-star");
                const waIcon = iconTpl("icon-ui-whatsapp");
                return `
                    <div class="coll-card">
                        <div class="coll-img">
                            <div class="coll-img-ph" style="background:${item.bg || ""};height:100%">
                                ${starIcon}
                            </div>
                            ${item.tag ? `<span class="coll-badge" style="background:${item.tagColor}">${item.tag}</span>` : ""}
                        </div>
                        <div class="coll-body">
                            <div class="coll-name">${item.name || ""}</div>
                            <div class="coll-metal">${item.metal || ""}</div>
                            <div style="font-size:.68rem;color:var(--muted);line-height:1.4;margin-bottom:.4rem">${item.desc || ""}</div>
                            <div class="coll-footer">
                                <div>
                                    <div class="coll-price">₹${money(item.price)}</div>
                                    ${item.oldPrice ? `<div class="coll-old">₹${money(item.oldPrice)}</div>` : ""}
                                </div>
                                <button class="enquire-btn" onclick="enquireWA('${sq(item.name)}')">
                                    ${waIcon}
                                    ${pick("labels.enquireButton")}
                                </button>
                            </div>
                        </div>
                    </div>`;
            })
            .join(""),
    );
}

function openCart() {
    const items = PRODUCTS.filter((item) => cart[item.id] > 0);
    const body = $id("cartBody");

    if (!body) {
        return;
    }

    if (!items.length) {
        const emptyIcon = iconTpl("icon-ui-cart");
        body.innerHTML = `<div class="cart-empty">${emptyIcon}<br />${pick("cart.empty")}</div>`;
        $id("cartOverlay")?.classList.add("show");
        return;
    }

    let total = 0;
    const rows = items
        .map((item) => {
            const lineTotal = item.price * cart[item.id];
            total += lineTotal;

            return `<div class="cart-item">
                <div class="ci-name">${item.name}<br /><small style="color:var(--muted);font-weight:400">₹${money(item.price)} · ${item.metal}</small></div>
                <div class="ci-qty">
                    <button class="ci-qty-btn" onclick="changeQty(${item.id},-1);openCart()">${iconTpl("icon-ui-minus")}</button>
                    <span class="ci-qty-num">${cart[item.id]}</span>
                    <button class="ci-qty-btn" onclick="changeQty(${item.id},1);openCart()">${iconTpl("icon-ui-plus")}</button>
                </div>
                <div class="ci-price">₹${money(lineTotal)}</div>
            </div>`;
        })
        .join("");

    body.innerHTML = `${rows}
        <div class="cart-total"><span>${pick("cart.totalLabel")}</span><span class="cart-total-amt">₹${money(total)}</span></div>
        <button class="cart-order-btn" onclick="sendCartWA()">
            ${iconTpl("icon-ui-whatsapp")}
            ${pick("cart.sendLabel")}
        </button>`;

    $id("cartOverlay")?.classList.add("show");
}

function changeQty(id, delta) {
    cart[id] = (cart[id] || 0) + delta;
    cart[id] = cart[id] < 0 ? 0 : cart[id];
}

function sendCartWA() {
    const items = PRODUCTS.filter((item) => cart[item.id] > 0);
    let total = 0;

    let msg = `${tpl(APP.cart?.waHeader, { shopName: SHOP.name })}\n\n${APP.cart?.waItemsLabel || ""}\n`;

    items.forEach((item) => {
        const lineTotal = item.price * cart[item.id];
        total += lineTotal;

        msg += `${tpl(APP.cart?.waLine, {
            name: item.name,
            metal: item.metal,
            qty: cart[item.id],
            total: money(lineTotal),
        })}\n`;
    });

    msg += `\n${tpl(APP.cart?.waTotal, { total: money(total) })}\n\n${APP.cart?.waFooter || ""}`;

    window.open(
        `https://wa.me/${SHOP.whatsapp}?text=${encodeURIComponent(msg)}`,
        "_blank",
    );

    closeCart();
}

function closeCart() {
    $id("cartOverlay")?.classList.remove("show");
}

function closeCartOutside(event) {
    event.target === $id("cartOverlay") && closeCart();
}

function submitEnquiry() {
    const name = $id("eName")?.value.trim();
    const phone = $id("ePhone")?.value.trim();

    if (!name || !phone) {
        showToast(pick("messages.namePhoneRequired"));
        return;
    }

    const email = $id("eEmail")?.value || pick("enquiryForm.defaultEmail");
    const category =
        $id("eCategory")?.value || pick("enquiryForm.defaultCategory");
    const budget = $id("eBudget")?.value || pick("enquiryForm.defaultBudget");
    const message = $id("eMsg")?.value || pick("enquiryForm.defaultMessage");

    const msg = tpl(APP.messages?.enquiryTemplate, {
        shopName: SHOP.name,
        name,
        phone,
        email,
        category,
        budget,
        message,
    });

    window.open(
        `https://wa.me/${SHOP.whatsapp}?text=${encodeURIComponent(msg)}`,
        "_blank",
    );

    $id("enquiryForm").style.display = "none";
    $id("enquirySuccess").style.display = "block";
}

function resetEnquiry() {
    $id("enquiryForm").style.display = "block";
    $id("enquirySuccess").style.display = "none";

    ["eName", "ePhone", "eEmail", "eBudget", "eMsg"].forEach((id) => {
        const field = $id(id);
        field && (field.value = "");
    });

    $id("eCategory").value = "";
}

function genQR() {
    const target = $id("vcardQR");

    if (!target || typeof QRCode === "undefined") {
        return;
    }

    target.innerHTML = "";
    new QRCode(target, {
        text: SHOP.website,
        width: 148,
        height: 148,
        colorDark: "#1a1208",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H,
    });
}

function saveContact() {
    const vcard = `BEGIN:VCARD\nVERSION:3.0\nFN:${SHOP.name}\nORG:${SHOP.name}\nTITLE:${SHOP.vcardTitle || ""}\nTEL;TYPE=CELL:${SHOP.phone}\nEMAIL:${SHOP.email}\nADR:;;${SHOP.addressVcard};;;;\nURL:${SHOP.website}\nNOTE:${SHOP.vcardNote || ""}\nEND:VCARD`;

    const link = document.createElement("a");
    link.href = URL.createObjectURL(new Blob([vcard], { type: "text/vcard" }));
    link.download = APP.files?.vcard || "contact.vcf";
    link.click();

    showToast(pick("messages.contactSaved"));
}

function downloadQR() {
    const canvas = document.querySelector("#vcardQR canvas");

    if (!canvas) {
        showToast(pick("messages.qrNotReady"));
        return;
    }

    const link = document.createElement("a");
    link.href = canvas.toDataURL("image/png");
    link.download = APP.files?.qr || "qr.png";
    link.click();

    showToast(pick("messages.qrDownloaded"));
}

function callShop() {
    window.location.href = `tel:${SHOP.phone}`;
}

function openWA() {
    window.open(
        `https://wa.me/${SHOP.whatsapp}?text=${encodeURIComponent(APP.messages?.waGreeting || "")}`,
        "_blank",
    );
}

function emailShop() {
    window.location.href = `mailto:${SHOP.email}`;
}

function openMaps() {
    window.open(SHOP.maps, "_blank");
}

function enquireWA(item) {
    const msg = tpl(APP.messages?.enquireTemplateQuick, { item });

    window.open(
        `https://wa.me/${SHOP.whatsapp}?text=${encodeURIComponent(msg)}`,
        "_blank",
    );

    showToast(pick("messages.enquireOpening"));
}

function openShare() {
    $id("shareModal")?.classList.add("show");
}

function closeShare(event) {
    event.target === $id("shareModal") &&
        $id("shareModal")?.classList.remove("show");
}

function shareWA() {
    const msg = tpl(APP.messages?.shareTemplate, {
        shopName: SHOP.name,
        tagline: SHOP.tagline,
        website: SHOP.website,
    });

    window.open(`https://wa.me/?text=${encodeURIComponent(msg)}`, "_blank");
    $id("shareModal")?.classList.remove("show");
}

function shareFB() {
    window.open(
        `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(SHOP.website)}`,
        "_blank",
    );

    $id("shareModal")?.classList.remove("show");
}

function copyLink() {
    if (navigator.clipboard?.writeText) {
        navigator.clipboard
            .writeText(SHOP.website)
            .then(() => showToast(pick("messages.linkCopied")));
    } else {
        showToast(pick("messages.linkCopied"));
    }

    $id("shareModal")?.classList.remove("show");
}

function openExternal(url) {
    url && window.open(url, "_blank");
}

function closePromo(event) {
    event.target === $id("promoOverlay") &&
        $id("promoOverlay")?.classList.remove("show");
}

function showToast(message) {
    const toast = $id("toast");
    const msg = $id("toastMsg");

    if (!toast || !msg) {
        return;
    }

    msg.textContent = message || pick("labels.toastDefault");
    toast.classList.add("show");
    setTimeout(() => {
        toast.classList.remove("show");
    }, 2800);
}

function boot() {
    APP = window.__APP__ || {};
    SHOP = APP.shop || {};
    SHOP.website = SHOP.website || window.location.href;
    PRODUCTS = APP.collections || [];
    currentCat =
        (APP.categories || []).find((item) => item.active)?.key || "all";
    cart = {};
}

"loading" === document.readyState
    ? document.addEventListener("DOMContentLoaded", boot)
    : boot();
