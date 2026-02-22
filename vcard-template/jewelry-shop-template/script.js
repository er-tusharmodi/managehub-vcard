const $id = (id) => document.getElementById(id);
const tpl = (template, values = {}) =>
    (template || "").replace(/\{\{(\w+)\}\}/g, (_, key) => values[key] ?? "");
const sq = (value = "") =>
    String(value)
        .replace(/\\/g, "\\\\")
        .replace(/'/g, "\\'");
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

let APP = {};
let SHOP = {};
let PRODUCTS = [];
let currentCat = "all";
let cart = {};

const SERVICE_ICONS = {
    star: `<path d="M12 2l2.4 4.8L20 8l-4 3.9L17 18l-5-2.6L7 18l1-6.1L4 8l5.6-1.2z"/>`,
    map: `<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>`,
    wrench: `<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>`,
    card: `<rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/>`,
    arrow: `<path d="M5 12h14M12 5l7 7-7 7"/>`,
    heart: `<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>`,
};

const SOCIAL_ICONS = {
    whatsapp: {
        cls: "ic-wa",
        svg: `<svg viewBox="0 0 24 24" width="18" height="18" stroke-width="1.8"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>`,
    },
    instagram: {
        cls: "ic-ig",
        svg: `<svg viewBox="0 0 24 24" width="18" height="18" stroke-width="1.8"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>`,
    },
    facebook: {
        cls: "ic-fb",
        svg: `<svg viewBox="0 0 24 24" width="18" height="18" stroke-width="1.8"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>`,
    },
    pinterest: {
        cls: "ic-pin",
        svg: `<svg viewBox="0 0 24 24" width="18" height="18" stroke-width="1.8" stroke="#e60023" fill="none"><path d="M12 2C6.48 2 2 6.48 2 12c0 4.24 2.65 7.86 6.39 9.29-.09-.78-.17-1.98.04-2.83.18-.77 1.23-5.22 1.23-5.22s-.31-.63-.31-1.56c0-1.46.85-2.55 1.9-2.55.9 0 1.33.67 1.33 1.48 0 .9-.58 2.26-.87 3.51-.25 1.05.52 1.9 1.55 1.9 1.86 0 3.1-2.4 3.1-5.24 0-2.16-1.46-3.67-3.56-3.67-2.42 0-3.84 1.82-3.84 3.7 0 .73.28 1.52.63 1.95.07.08.08.15.06.23l-.23.95c-.04.15-.12.18-.28.11-1.03-.48-1.68-2-1.68-3.22 0-2.62 1.9-5.03 5.49-5.03 2.88 0 5.12 2.05 5.12 4.79 0 2.86-1.8 5.16-4.3 5.16-.84 0-1.63-.44-1.9-.95l-.52 1.93c-.19.72-.69 1.62-1.03 2.17.78.24 1.6.37 2.46.37 5.52 0 10-4.48 10-10S17.52 2 12 2z"/></svg>`,
    },
    youtube: {
        cls: "ic-yt",
        svg: `<svg viewBox="0 0 24 24" width="18" height="18" stroke-width="1.8"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/></svg>`,
    },
};

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
            .map(
                (item) => `
                    <div class="svc-item">
                        <div class="svc-ico">
                            <svg viewBox="0 0 24 24">${SERVICE_ICONS[item.icon] || SERVICE_ICONS.star}</svg>
                        </div>
                        <div class="svc-info">
                            <div class="svc-name">${item.name || ""}</div>
                            <div class="svc-desc">${item.desc || ""}</div>
                        </div>
                    </div>`,
            )
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
                const icon = SOCIAL_ICONS[item.type] || SOCIAL_ICONS.whatsapp;
                return `
                    <div class="social-item ${item.type === "pinterest" ? "ic-pin" : ""}" onclick="${socialAction(item)}">
                        <div class="s-ico ${icon.cls}">${icon.svg}</div>
                        <div>
                            <div class="s-name">${item.name || ""}</div>
                            <div class="s-val">${item.value || ""}</div>
                        </div>
                        <div class="s-arrow">
                            <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6" /></svg>
                        </div>
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
            .map(
                (item) => `
                    <div class="coll-card">
                        <div class="coll-img">
                            <div class="coll-img-ph" style="background:${item.bg || ""};height:100%">
                                <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="rgba(0,0,0,0.25)" stroke-width="1.2">
                                    <path d="M12 2l2.4 4.8L20 8l-4 3.9L17 18l-5-2.6L7 18l1-6.1L4 8l5.6-1.2z"/>
                                </svg>
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
                                    <svg viewBox="0 0 24 24" width="11" height="11" stroke="currentColor" fill="none" stroke-width="2.5"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                                    ${pick("labels.enquireButton")}
                                </button>
                            </div>
                        </div>
                    </div>`,
            )
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
        body.innerHTML = `<div class="cart-empty"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg><br />${pick("cart.empty")}</div>`;
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
                    <button class="ci-qty-btn" onclick="changeQty(${item.id},-1);openCart()"><svg viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2.5" width="12" height="12"><line x1="5" y1="12" x2="19" y2="12"/></svg></button>
                    <span class="ci-qty-num">${cart[item.id]}</span>
                    <button class="ci-qty-btn" onclick="changeQty(${item.id},1);openCart()"><svg viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2.5" width="12" height="12"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></button>
                </div>
                <div class="ci-price">₹${money(lineTotal)}</div>
            </div>`;
        })
        .join("");

    body.innerHTML = `${rows}
        <div class="cart-total"><span>${pick("cart.totalLabel")}</span><span class="cart-total-amt">₹${money(total)}</span></div>
        <button class="cart-order-btn" onclick="sendCartWA()">
            <svg viewBox="0 0 24 24" width="18" height="18" stroke="#fff" fill="none" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
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
    const category = $id("eCategory")?.value || pick("enquiryForm.defaultCategory");
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

async function boot() {
    try {
        const res = await fetch("default.json", { cache: "no-cache" });
        if (!res.ok) {
            throw new Error(`default.json load failed with status ${res.status}`);
        }

        APP = await res.json();
        SHOP = APP.shop || {};
        SHOP.website = SHOP.website || window.location.href;

        PRODUCTS = APP.collections || [];
        currentCat = (APP.categories || []).find((item) => item.active)?.key || "all";
        cart = {};

        fillStaticContent();
        renderStats();
        mountCategoryChips();
        renderCollections();
        renderPurity();
        renderCertifications();
        renderServices();
        renderTestimonials();
        renderHours();
        renderFollowLinks();
        renderEnquiryCategories();
        genQR();
    } catch (error) {
        console.error("Failed to load default.json", error);
    }
}

"loading" === document.readyState
    ? document.addEventListener("DOMContentLoaded", boot)
    : boot();
