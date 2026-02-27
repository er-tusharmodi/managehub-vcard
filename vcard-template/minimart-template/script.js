const $id = (id) => document.getElementById(id);
const tpl = (template = "", values = {}) =>
    template.replace(/\{\{(\w+)\}\}/g, (_, key) => values[key] ?? "");
const sq = (value = "") =>
    String(value).replace(/\\/g, "\\\\").replace(/'/g, "\\'");
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

let APP = {};
let SHOP = {};
let PRODUCTS = [];
let cart = {};

const BANNER_ICONS = () => ({
    grocery: () => iconTpl("banner_grocery"),
    fruits: () => iconTpl("banner_fruits"),
    essentials: () => iconTpl("banner_essentials"),
    dairy: () => iconTpl("banner_dairy"),
    beverages: () => iconTpl("banner_beverages"),
});

const BADGE_ICONS = () => ({
    trusted: () => iconTpl("badge_trusted"),
    open: () => iconTpl("badge_open"),
    delivery: () => iconTpl("badge_delivery"),
    prices: () => iconTpl("badge_prices"),
    fresh: () => iconTpl("badge_fresh"),
});

const SOCIAL_ICONS = () => ({
    whatsapp: {
        cls: "ic-wa",
        svg: () => iconTpl("social_whatsapp"),
    },
    facebook: {
        cls: "ic-fb",
        svg: () => iconTpl("social_facebook"),
    },
    instagram: {
        cls: "ic-ig",
        svg: () => iconTpl("social_instagram"),
    },
    youtube: {
        cls: "ic-yt",
        svg: () => iconTpl("social_youtube"),
    },
});

const PAYMENT_ICONS = () => ({
    upi: () => iconTpl("pay_upi"),
    card: () => iconTpl("pay_card"),
    cash: () => iconTpl("pay_cash"),
});

const CART_ICON = () => iconTpl("ui_cart");

const socialAction = (item = {}) => {
    if (item.action === "wa") {
        return "openWA()";
    }
    if (item.action === "url" && item.url) {
        return `window.open('${sq(item.url)}','_blank')`;
    }
    return "";
};

const cartEmptyMarkup = () =>
    `<div class="cart-empty">${CART_ICON()}${pick("sections.cart.emptyHtml")}</div>`;

const renderBanner = () => {
    const icons = BANNER_ICONS();
    setHTML(
        "bannerIcons",
        (APP.banner?.icons || [])
            .map(
                (item) => `
                    <div class="banner-icon-item">
                        ${icons[item.icon]?.() || ""}
                        <span>${item.label || ""}</span>
                    </div>`,
            )
            .join(""),
    );
};

const renderBadges = () => {
    const icons = BADGE_ICONS();
    setHTML(
        "badgeStrip",
        (APP.badges || [])
            .map(
                (item) => `
                    <div class="badge-item">
                        <svg viewBox="0 0 24 24">${icons[item.icon]?.() || ""}</svg>
                        ${item.text || ""}
                    </div>`,
            )
            .join(""),
    );
};

const renderSocial = () => {
    const icons = SOCIAL_ICONS();
    setHTML(
        "socialList",
        (APP.social || [])
            .map((item) => {
                const icon = icons[item.type] || {};
                const click = socialAction(item);
                return `
                    <div class="social-item"${click ? ` onclick="${click}"` : ""}>
                        <div class="s-ico ${icon.cls || ""}">${icon.svg?.() || ""}</div>
                        <div>
                            <div class="s-name">${item.name || ""}</div>
                            <div class="s-val">${item.value || ""}</div>
                        </div>
                        <div class="s-arrow">
                            <svg viewBox="0 0 24 24" stroke-width="2.5" stroke="#bbb" fill="none" width="13" height="13">
                                <polyline points="9 18 15 12 9 6" />
                            </svg>
                        </div>
                    </div>`;
            })
            .join(""),
    );
};

const renderCategories = () => {
    setHTML(
        "categoriesGrid",
        (APP.categories || [])
            .map(
                (item) => `
                    <div class="cat-card" onclick="enquireWA('${sq(item.query || item.name || "")}')">
                        <div class="cat-name">${item.name || ""}</div>
                        <div class="cat-count">${item.count || ""}</div>
                    </div>`,
            )
            .join(""),
    );
};

const renderProducts = () => {
    setHTML(
        "productsGrid",
        PRODUCTS.map(
            (item) => `
                <div class="prod-card">
                    <div class="prod-img">
                        <div class="prod-img-placeholder" style="background:${item.bg || `url('${pick("assets.fallbackImage")}')`};height:100%"></div>
                        ${item.tag ? `<span class="prod-tag" style="background:${item.tagColor || "#dc2626"}">${item.tag}</span>` : ""}
                    </div>
                    <div class="prod-body">
                        <div class="prod-name">${item.name || ""}</div>
                        <div class="prod-desc">${item.desc || ""}</div>
                        <div class="prod-footer">
                            <div>
                                <span class="prod-price">₹${item.price || 0}</span>
                                ${item.oldPrice ? `<span class="prod-old">₹${item.oldPrice}</span>` : ""}
                            </div>
                            <div class="qty-ctrl">
                                <button class="qty-btn" onclick="changeQty(${item.id},-1)">
                                    <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                </button>
                                <span class="qty-num" id="qty-${item.id}">${cart[item.id] || 0}</span>
                                <button class="qty-btn" onclick="changeQty(${item.id},1)">
                                    <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`,
        ).join(""),
    );
};

const renderDeals = () => {
    setHTML(
        "dealsList",
        (APP.deals || [])
            .map((item) => {
                const action =
                    item.action?.type === "wa"
                        ? "openWA()"
                        : `enquireWA('${sq(item.action?.value || item.name || "")}')`;
                return `
                    <div class="deal-item">
                        <div class="deal-badge">${item.badge || ""}</div>
                        <div class="deal-info">
                            <div class="deal-name">${item.name || ""}</div>
                            <div class="deal-desc">${item.desc || ""}</div>
                        </div>
                        <button class="deal-cta" onclick="${action}">${item.action?.label || ""}</button>
                    </div>`;
            })
            .join(""),
    );
};

const renderGallery = () => {
    setHTML(
        "galleryGrid",
        (APP.gallery || [])
            .map(
                (item) => `
                    <div class="g-item">
                        <div style="height:100%;background:${item.bg || `url('${pick("assets.fallbackImage")}') center/cover no-repeat`};display:flex;align-items:center;justify-content:center;"></div>
                    </div>`,
            )
            .join(""),
    );
};

const renderHours = () => {
    const rows = pick("sections.hours.rows", []);
    setHTML(
        "hoursRows",
        rows
            .map((row) => {
                const open = row.status !== "closed";
                return `
                    <tr class="${open ? "open-row" : "closed-row"}">
                        <td class="day">${row.day || ""}</td>
                        <td class="time${open ? "" : " closed"}">${row.time || ""}</td>
                    </tr>`;
            })
            .join(""),
    );
};

const renderPayments = () => {
    const icons = PAYMENT_ICONS();
    setHTML(
        "paymentList",
        (APP.payments || [])
            .map(
                (item) => `
                    <div class="pay-item">
                        <div class="pay-icon-wrap">
                            <svg viewBox="0 0 24 24" stroke="${item.stroke || "#15803d"}" stroke-width="2">
                                ${icons[item.icon]?.() || ""}
                            </svg>
                        </div>
                        <div>
                            <div class="pay-name">${item.name || ""}</div>
                            <div class="pay-detail">${item.detail || ""}</div>
                        </div>
                    </div>`,
            )
            .join(""),
    );
};

const renderStaticText = () => {
    document.title = pick("meta.title");

    setText("topShareLabel", pick("banner.topBar.share"));
    setText("topSaveLabel", pick("banner.topBar.saveCard"));

    setText("profileName", pick("profile.name"));
    setText("profileRole", pick("profile.role"));
    setText("profileBio", pick("profile.bio"));

    setText("actionCallLabel", pick("profile.actions.call"));
    setText("actionWaLabel", pick("profile.actions.whatsapp"));
    setText("actionSaveLabel", pick("profile.actions.save"));
    setText("actionEmailLabel", pick("profile.actions.email"));
    setText("actionDirectionLabel", pick("profile.actions.directions"));
    setText("actionShareLabel", pick("profile.actions.share"));

    setText("sectionLocationTitle", pick("sections.location.title"));
    setText("locationLine1", pick("sections.location.addressLine1"));
    setText("locationLine2", pick("sections.location.addressLine2"));
    setText("locationMapLabel", pick("sections.location.mapLabel"));

    setText("sectionSocialTitle", pick("sections.social.title"));
    setText("sectionCategoriesTitle", pick("sections.categories.title"));
    setText("sectionPicksTitle", pick("sections.picks.title"));
    setText("sectionDealsTitle", pick("sections.deals.title"));
    setText("sectionGalleryTitle", pick("sections.gallery.title"));
    setText("sectionHoursTitle", pick("sections.hours.title"));
    setText("todayBadgeText", pick("sections.hours.todayLabel"));
    setText("hoursSuggestLabel", pick("sections.hours.suggestLabel"));

    setText("sectionQrTitle", pick("sections.qr.title"));
    setText("qrHelpText", pick("sections.qr.helpText"));
    setText("qrDownloadLabel", pick("sections.qr.download"));
    setText("qrCopyLabel", pick("sections.qr.copy"));

    setText("sectionPaymentsTitle", pick("sections.payments.title"));

    setText("sectionContactTitle", pick("sections.contact.title"));
    setText("contactLabelName", pick("sections.contact.form.nameLabel"));
    setText("contactLabelPhone", pick("sections.contact.form.phoneLabel"));
    setText("contactLabelEmail", pick("sections.contact.form.emailLabel"));
    setText("contactLabelMessage", pick("sections.contact.form.messageLabel"));
    setText("contactSubmitLabel", pick("sections.contact.form.submit"));
    setText("contactSuccessTitle", pick("sections.contact.success.title"));
    setText("contactSuccessText", pick("sections.contact.success.text"));
    setText("contactAnotherLabel", pick("sections.contact.success.button"));

    setAttr(
        "cName",
        "placeholder",
        pick("sections.contact.form.namePlaceholder"),
    );
    setAttr(
        "cPhone",
        "placeholder",
        pick("sections.contact.form.phonePlaceholder"),
    );
    setAttr(
        "cEmail",
        "placeholder",
        pick("sections.contact.form.emailPlaceholder"),
    );
    setAttr(
        "cMsg",
        "placeholder",
        pick("sections.contact.form.messagePlaceholder"),
    );

    setHTML(
        "footerLine1",
        `${pick("footer.year")} <strong>${pick("footer.brand")}</strong> ${pick("footer.rights")}`.trim(),
    );
    setHTML(
        "footerLine2",
        `${pick("footer.poweredBy")} <strong>${pick("footer.poweredBrand")}</strong>`,
    );

    setText("floatCallLabel", pick("floatBar.call"));
    setText("floatSaveLabel", pick("floatBar.saveCard"));
    setText("floatWaLabel", pick("floatBar.whatsapp"));
    setText("floatCartLabel", pick("floatBar.cart"));

    setText("cartTitle", pick("sections.cart.title"));

    setText("shareTitle", pick("sections.share.title"));
    setText("shareWaLabel", pick("sections.share.whatsapp"));
    setText("shareCopyLabel", pick("sections.share.copy"));
    setText("shareFbLabel", pick("sections.share.facebook"));
    setText("shareMoreLabel", pick("sections.share.more"));
    setText("shareCancelLabel", pick("sections.share.cancel"));

    setText("promoTitle", pick("promo.title"));
    setText("promoText", pick("promo.text"));
    setText("promoButtonLabel", pick("promo.button"));
};

const renderApp = () => {
    SHOP = { ...(APP.shop || {}) };
    SHOP.website = SHOP.website || window.location.href;
    PRODUCTS = APP.products || [];

    const banner = $id("bannerRoot");
    if (banner && APP.assets?.bannerImage) {
        banner.style.background = `url(${APP.assets.bannerImage}) center/cover no-repeat`;
    }

    setAttr(
        "profileImage",
        "src",
        APP.assets?.profileImage || APP.assets?.fallbackImage || "",
    );
    setAttr("profileImage", "alt", SHOP.name || "");

    renderStaticText();
    renderBanner();
    renderBadges();
    renderSocial();
    renderCategories();
    renderProducts();
    renderDeals();
    renderGallery();
    renderHours();
    renderPayments();

    setHTML("cartBody", cartEmptyMarkup());
    updateCartBadge();
    genQR();

    if ($id("contactForm")) {
        $id("contactForm").style.display = "block";
    }
    if ($id("contactSuccess")) {
        $id("contactSuccess").style.display = "none";
    }

    const promoOverlay = $id("promoOverlay");
    if (promoOverlay) {
        promoOverlay.classList.remove("show");
        if (APP.promo?.enabled) {
            setTimeout(
                () => promoOverlay.classList.add("show"),
                Number(APP.promo?.delayMs) || 2200,
            );
        }
    }
};

function changeQty(id, delta) {
    cart[id] = (cart[id] || 0) + delta;
    if (cart[id] < 0) {
        cart[id] = 0;
    }
    const qty = $id(`qty-${id}`);
    if (qty) {
        qty.textContent = cart[id];
    }
    updateCartBadge();
}

function updateCartBadge() {
    const total = Object.values(cart).reduce(
        (sum, qty) => sum + Number(qty || 0),
        0,
    );
    const badge = $id("cartBadge");
    if (!badge) {
        return;
    }
    badge.textContent = total;
    badge.classList.toggle("show", total > 0);
}

function openCart() {
    const selected = PRODUCTS.filter((item) => cart[item.id] > 0);
    const cartBody = $id("cartBody");
    if (!cartBody) {
        return;
    }

    if (!selected.length) {
        cartBody.innerHTML = cartEmptyMarkup();
        $id("cartOverlay")?.classList.add("show");
        return;
    }

    let total = 0;
    const lines = selected
        .map((item) => {
            const lineAmount = item.price * cart[item.id];
            total += lineAmount;
            return `<div class="cart-item"><div class="ci-name">${item.name}<br><small style="color:var(--muted);font-weight:500">₹${item.price}${item.per || ""}</small></div><div class="ci-qty"><button class="ci-qty-btn" onclick="changeQty(${item.id},-1);openCart()"><svg viewBox="0 0 24 24" width="11" height="11" fill="none" stroke="var(--navy)" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/></svg></button><span class="ci-qty-num">${cart[item.id]}</span><button class="ci-qty-btn" onclick="changeQty(${item.id},1);openCart()"><svg viewBox="0 0 24 24" width="11" height="11" fill="none" stroke="var(--navy)" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></button></div><div class="ci-price">₹${lineAmount}</div></div>`;
        })
        .join("");

    cartBody.innerHTML = `${lines}<div class="cart-total"><span>${pick("sections.cart.totalLabel")}</span><span class="cart-total-amt">₹${total}</span></div><button class="cart-order-btn" onclick="sendCartWA()"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="#fff" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>${pick("sections.cart.orderLabel")}</button>`;
    $id("cartOverlay")?.classList.add("show");
}

function sendCartWA() {
    const selected = PRODUCTS.filter((item) => cart[item.id] > 0);
    if (!selected.length) {
        openCart();
        return;
    }

    let total = 0;
    let message = `${tpl(pick("messages.orderHeader"), { shop: SHOP.name })}\n\n`;
    selected.forEach((item) => {
        const amount = item.price * cart[item.id];
        total += amount;
        message += `${tpl(pick("messages.orderLine"), {
            name: item.name,
            qty: cart[item.id],
            amount,
        })}\n`;
    });
    message += `\n${tpl(pick("messages.orderFooter"), { total })}`;

    window.open(
        `https://wa.me/${SHOP.whatsapp}?text=${encodeURIComponent(message)}`,
        "_blank",
    );
    closeCart();
}

function closeCart() {
    $id("cartOverlay")?.classList.remove("show");
}

function closeCartOutside(event) {
    if (event.target === $id("cartOverlay")) {
        closeCart();
    }
}

function genQR() {
    const box = $id("vcardQR");
    if (!box || typeof QRCode === "undefined") {
        return;
    }
    box.innerHTML = "";
    new QRCode(box, {
        text: SHOP.website,
        width: 160,
        height: 160,
        colorDark: "#0f2744",
        colorLight: "#f7faf8",
        correctLevel: QRCode.CorrectLevel.H,
    });
}

function saveContact() {
    const card = `BEGIN:VCARD\nVERSION:3.0\nFN:${SHOP.name}\nORG:${SHOP.name}\nTEL;TYPE=CELL:${SHOP.phone}\nEMAIL:${SHOP.email}\nADR:;;${SHOP.address};;;;\nURL:${SHOP.website}\nNOTE:${SHOP.tagline}\nEND:VCARD`;
    const link = document.createElement("a");
    link.href = URL.createObjectURL(new Blob([card], { type: "text/vcard" }));
    link.download = pick("files.vcard");
    link.click();
    showToast(pick("messages.contactSaved"));
}

function callShop() {
    window.location.href = `tel:${SHOP.phone}`;
}

function openWA() {
    window.open(
        `https://wa.me/${SHOP.whatsapp}?text=${encodeURIComponent(pick("messages.waGreeting"))}`,
        "_blank",
    );
}

function emailShop() {
    window.location.href = `mailto:${SHOP.email}`;
}

function openMaps() {
    window.open(SHOP.maps, "_blank");
}

function enquireWA(itemName) {
    const message = tpl(pick("messages.enquireTemplate"), {
        item: itemName || "",
    });
    window.open(
        `https://wa.me/${SHOP.whatsapp}?text=${encodeURIComponent(message)}`,
        "_blank",
    );
}

function downloadQR() {
    const canvas = document.querySelector("#vcardQR canvas");
    if (!canvas) {
        showToast(pick("messages.qrNotReady"));
        return;
    }
    const link = document.createElement("a");
    link.href = canvas.toDataURL("image/png");
    link.download = pick("files.qr");
    link.click();
    showToast(pick("messages.qrDownloaded"));
}

function copyLink() {
    const done = () => showToast(pick("messages.linkCopied"));
    if (navigator.clipboard?.writeText) {
        navigator.clipboard.writeText(SHOP.website).then(done).catch(done);
    } else {
        done();
    }
    closeShareModal();
}

function openShare() {
    $id("shareModal")?.classList.add("show");
}

function closeShare(event) {
    if (event.target === $id("shareModal")) {
        closeShareModal();
    }
}

function closeShareModal() {
    $id("shareModal")?.classList.remove("show");
}

function shareWA() {
    window.open(
        `https://wa.me/?text=${encodeURIComponent(`Check out ${SHOP.name}: ${SHOP.website}`)}`,
        "_blank",
    );
    closeShareModal();
}

function shareFB() {
    window.open(
        `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(SHOP.website)}`,
        "_blank",
    );
    closeShareModal();
}

function shareNative() {
    if (navigator.share) {
        navigator.share({ title: SHOP.name, url: SHOP.website });
    } else {
        copyLink();
    }
}

function closePromo() {
    $id("promoOverlay")?.classList.remove("show");
}

function promoAction() {
    openWA();
    closePromo();
}

function submitContact() {
    const name = $id("cName")?.value.trim() || "";
    const phone = $id("cPhone")?.value.trim() || "";
    if (!name || !phone) {
        showToast(pick("messages.missingContact"));
        return;
    }

    const email = $id("cEmail")?.value.trim() || pick("labels.na");
    const msg = $id("cMsg")?.value.trim() || pick("labels.noMessage");
    const text = tpl(pick("messages.contactTemplate"), {
        shop: SHOP.name,
        name,
        phone,
        email,
        message: msg,
    });

    window.open(
        `https://wa.me/${SHOP.whatsapp}?text=${encodeURIComponent(text)}`,
        "_blank",
    );
    if ($id("contactForm")) {
        $id("contactForm").style.display = "none";
    }
    if ($id("contactSuccess")) {
        $id("contactSuccess").style.display = "block";
    }
}

function resetContact() {
    if ($id("contactForm")) {
        $id("contactForm").style.display = "block";
    }
    if ($id("contactSuccess")) {
        $id("contactSuccess").style.display = "none";
    }
    ["cName", "cPhone", "cEmail", "cMsg"].forEach((id) => {
        if ($id(id)) {
            $id(id).value = "";
        }
    });
}

function showToast(message) {
    const toast = $id("toast");
    if (!toast) {
        return;
    }
    toast.innerHTML = `<svg viewBox="0 0 24 24" width="14" height="14" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>${message || pick("labels.done")}`;
    toast.classList.add("show");
    setTimeout(() => toast.classList.remove("show"), 2400);
}

const boot = () => {
    APP = window.__APP__ || {};
    SHOP = APP.shop || {};
    SHOP.website = SHOP.website || window.location.href;
    PRODUCTS = APP.products || [];
    cart = {};
};

document.readyState === "loading"
    ? document.addEventListener("DOMContentLoaded", boot)
    : boot();
