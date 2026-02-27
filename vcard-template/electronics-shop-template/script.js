const $id = (id) => document.getElementById(id);
const money = (value) => Number(value || 0).toLocaleString("en-IN");
const tpl = (template, values = {}) =>
    (template || "").replace(/\{\{(\w+)\}\}/g, (_, key) => values[key] ?? "");
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

const getSubmissionUrl = (type) => {
    // Use injected subdomain from PHP if available
    if (window.__VCARD_SUBDOMAIN__) {
        return `/vcard/${window.__VCARD_SUBDOMAIN__}/submit/${type}`;
    }

    const hostParts = window.location.hostname.split(".");
    const pathParts = window.location.pathname.split("/").filter(Boolean);

    // Check if on subdomain (subdomain.domain.com)
    if (hostParts.length > 2) {
        return `/submit/${type}`;
    }

    // Check if subdomain is in path (/subdomain or /vcard/subdomain)
    if (pathParts.length > 0) {
        // If path starts with 'vcard', subdomain is next part
        if (pathParts[0] === "vcard" && pathParts.length > 1) {
            return `/vcard/${pathParts[1]}/submit/${type}`;
        }
        // Otherwise first part is the subdomain
        return `/vcard/${pathParts[0]}/submit/${type}`;
    }

    // Fallback
    return `/submit/${type}`;
};

const sendSubmission = (type, payload) => {
    const csrfToken =
        document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content") || "";
    return fetch(getSubmissionUrl(type), {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            ...(csrfToken && { "X-CSRF-TOKEN": csrfToken }),
        },
        body: JSON.stringify(payload),
    })
        .then(async (res) => {
            const data = await res.json();
            if (!res.ok) {
                console.error(
                    `Submission ${type} failed with status ${res.status}:`,
                    data,
                );
                return { success: false, ...data };
            }
            return data;
        })
        .catch((err) => {
            console.error("Submission error:", err);
            return { success: false, message: err.message };
        });
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

const PILL_ICONS = () => ({
    shield: () => iconTpl("pill_shield"),
    truck: () => iconTpl("pill_truck"),
    clock: () => iconTpl("pill_clock"),
    price: () => iconTpl("pill_price"),
    chat: () => iconTpl("pill_chat"),
    refresh: () => iconTpl("pill_refresh"),
});

const CATEGORY_ICONS = () => ({
    phone: () => iconTpl("cat_phone"),
    laptop: () => iconTpl("cat_laptop"),
    appliance: () => iconTpl("cat_appliance"),
    tv: () => iconTpl("cat_tv"),
    accessories: () => iconTpl("cat_accessories"),
    gaming: () => iconTpl("cat_gaming"),
});

const PAYMENT_ICONS = () => ({
    upi: () => iconTpl("pay_upi"),
    card: () => iconTpl("pay_card"),
    bank: () => iconTpl("pay_bank"),
    cash: () => iconTpl("pay_cash"),
});

const SOCIAL_ICONS = () => ({
    whatsapp: {
        className: "ic-wa",
        svg: () => iconTpl("social_whatsapp"),
    },
    facebook: {
        className: "ic-fb",
        svg: () => iconTpl("social_facebook"),
    },
    instagram: {
        className: "ic-ig",
        svg: () => iconTpl("social_instagram"),
    },
    youtube: {
        className: "ic-yt",
        svg: () => iconTpl("social_youtube"),
    },
});

const renderProfileBadges = () => {
    setHTML(
        "profile-badges",
        (APP.profile?.badges || [])
            .map(
                (item) => `
                    <span class="badge ${item.className || ""}">${item.text || ""}</span>`,
            )
            .join(""),
    );
};

const renderStats = () => {
    setHTML(
        "statsStrip",
        (APP.stats || [])
            .map(
                (item) => `
                    <div class="stat-card">
                        <div class="stat-num">${item.value || ""}</div>
                        <div class="stat-label">${item.label || ""}</div>
                    </div>`,
            )
            .join(""),
    );
};

const renderWhyChoose = () => {
    const icons = PILL_ICONS();
    setHTML(
        "whyPills",
        (APP.whyChoose || [])
            .map(
                (item) => `
                    <span class="pill ${item.tone || ""}">
                        ${icons[item.icon]?.() || ""}
                        ${item.text || ""}
                    </span>`,
            )
            .join(""),
    );
};

const renderCategories = () => {
    setHTML(
        "categoriesGrid",
        (APP.categories || [])
            .map(
                (item) => `
                    <div class="cat-card" onclick="enquireWA('${sq(item.query || item.name)}')">
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
                        <div class="prod-img-placeholder" style="background:${item.bg || ""};height:100%">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.5)" stroke-width="1.4">
                                ${iconTpl("cat_laptop")}
                            </svg>
                        </div>
                        ${item.tag ? `<span class="prod-tag ${String(item.tag).toLowerCase()}" style="background:${item.tagColor || ""}">${item.tag}</span>` : ""}
                    </div>
                    <div class="prod-body">
                        <div class="prod-brand">${item.brand || ""}</div>
                        <div class="prod-name">${item.name || ""}</div>
                        <div class="prod-spec">${item.spec || ""}</div>
                        <div class="prod-footer">
                            <div>
                                <div class="prod-price">₹${money(item.price)}</div>
                                ${item.oldPrice ? `<span class="prod-old">₹${money(item.oldPrice)}</span>` : ""}
                                ${item.oldPrice ? `<div class="prod-discount">${Math.round(((item.oldPrice - item.price) / item.oldPrice) * 100)}% OFF</div>` : ""}
                            </div>
                            <div class="qty-ctrl">
                                <button class="qty-btn" onclick="changeQty(${item.id},-1)">
                                    <svg viewBox="0 0 24 24">${iconTpl("minus")}</svg>
                                </button>
                                <span class="qty-num" id="qty-${item.id}">${cart[item.id] || 0}</span>
                                <button class="qty-btn" onclick="changeQty(${item.id},1)">
                                    <svg viewBox="0 0 24 24">${iconTpl("plus")}</svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`,
        ).join(""),
    );
};

const renderRepairs = () => {
    setHTML(
        "repairList",
        (APP.repairServices || [])
            .map(
                (item) => `
                    <div class="repair-item">
                        <div class="repair-info">
                            <div class="repair-name">${item.name || ""}</div>
                            <div class="repair-sub">${item.sub || ""}</div>
                        </div>
                        <div class="repair-price">${item.price || ""}</div>
                        <button class="repair-wa" onclick="enquireWA('${sq(item.query || item.name)}')">
                            <svg width="22" height="22" viewBox="0 0 24 24" stroke-width="2">
                                ${iconTpl("social_whatsapp")}
                            </svg>
                        </button>
                    </div>`,
            )
            .join(""),
    );
};

const renderBrands = () => {
    setHTML(
        "brandsGrid",
        (APP.brands || [])
            .map((item) => `<span class="brand-chip">${item || ""}</span>`)
            .join(""),
    );
};

const renderGallery = () => {
    setHTML(
        "galleryGrid",
        (APP.gallery || [])
            .map(
                (url) => `
                    <div class="gal-item">
                        <div class="gal-placeholder" style="background:url('${url}') center/cover no-repeat;"></div>
                    </div>`,
            )
            .join(""),
    );
};

const renderHours = () => {
    setHTML(
        "hoursTable",
        (APP.hours?.rows || [])
            .map(
                (row) => `
                    <tr class="${row.rowClass || ""}">
                        <td class="day">${row.day || ""}</td>
                        <td class="time ${row.timeClass || ""}">${row.time || ""}</td>
                    </tr>`,
            )
            .join(""),
    );
};

const socialAction = (item) =>
    item.action === "openWA"
        ? "openWA()"
        : item.url
          ? `openExternal('${sq(item.url)}')`
          : "";

const renderSocial = () => {
    setHTML(
        "socialList",
        (APP.socialLinks || [])
            .map((item) => {
                const icon = SOCIAL_ICONS[item.type] || SOCIAL_ICONS.whatsapp;
                const action = socialAction(item);

                return `
                    <div class="social-item"${action ? ` onclick="${action}"` : ""}>
                        <div class="s-ico ${icon.className}">${icon.svg}</div>
                        <div>
                            <div class="s-name">${item.name || ""}</div>
                            <div class="s-val">${item.value || ""}</div>
                        </div>
                        <div class="s-arrow">
                            <svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2.5">
                                <polyline points="9 18 15 12 9 6" />
                            </svg>
                        </div>
                    </div>`;
            })
            .join(""),
    );
};

const renderPayments = () => {
    setHTML(
        "paymentList",
        (APP.payments || [])
            .map(
                (item) => `
                    <div class="pay-item">
                        <div class="pay-icon-wrap">
                            <span style="display:flex;color:${item.stroke || "#3b82f6"}">${PAYMENT_ICONS[item.icon] || ""}</span>
                        </div>
                        <div>
                            <div class="pay-name">${item.name || ""}</div>
                            <div class="pay-detail">${item.detail || ""}</div>
                        </div>
                        <div class="pay-badge">${item.badge || ""}</div>
                    </div>`,
            )
            .join(""),
    );
};

const renderEnquiryCategories = () => {
    const select = $id("cCat");
    if (!select) {
        return;
    }

    select.innerHTML = `
        <option value="">${pick("enquiryForm.categoryPlaceholder")}</option>
        ${(APP.enquiryForm?.categories || [])
            .map((item) => `<option>${item}</option>`)
            .join("")}`;
};

const fillStaticContent = () => {
    document.title = pick("meta.title");

    setText("banner-label", pick("banner.label"));
    setText("banner-share", pick("banner.share"));
    setText("banner-tagline-main", pick("banner.mainTagline"));
    setText("banner-tagline-sub", pick("banner.subTagline"));

    setText("profile-name", pick("profile.name"));
    setText("profile-role", pick("profile.role"));
    setText("profile-bio", pick("profile.bio"));
    setText("action-call", pick("profile.actions.call"));
    setText("action-whatsapp", pick("profile.actions.whatsapp"));
    setText("action-save", pick("profile.actions.save"));
    setText("action-email", pick("profile.actions.email"));
    setText("action-direction", pick("profile.actions.direction"));
    setText("action-enquiry", pick("profile.actions.enquiry"));

    setText("sec-title-why", pick("sections.whyChoose"));
    setText("sec-title-categories", pick("sections.categories"));
    setText("sec-title-featured", pick("sections.featuredProducts"));
    setText("sec-badge-featured", pick("featured.badge"));
    setText("featured-emi-note", pick("featured.emiNote"));
    setText("sec-title-repair", pick("sections.repairServices"));
    setText("sec-badge-repair", pick("repair.badge"));
    setText("sec-title-brands", pick("sections.brands"));
    setText("sec-title-gallery", pick("sections.gallery"));
    setText("sec-title-hours", pick("sections.hours"));
    setText("hours-open-label", pick("hours.openLabel"));
    setText("sec-title-location", pick("sections.location"));
    setText("location-strong", pick("location.titleLine"));
    setText("location-line", pick("location.addressLine"));
    setText("location-map-label", pick("location.mapLabel"));
    setText("sec-title-follow", pick("sections.follow"));
    setText("sec-title-payments", pick("sections.payments"));
    setText("sec-title-enquiry", pick("sections.enquiry"));
    setText("sec-title-qr", pick("sections.qr"));

    setText("label-name", pick("enquiryForm.nameLabel"));
    setText("label-phone", pick("enquiryForm.phoneLabel"));
    setText("label-email", pick("enquiryForm.emailLabel"));
    setText("label-category", pick("enquiryForm.categoryLabel"));
    setText("label-message", pick("enquiryForm.messageLabel"));
    setText("enquiry-submit", pick("enquiryForm.submitLabel"));
    setText("enquiry-success-title", pick("enquiryForm.successTitle"));
    setText("enquiry-success-text", pick("enquiryForm.successText"));
    setText("enquiry-success-button", pick("enquiryForm.successButton"));

    setAttr("cName", "placeholder", pick("enquiryForm.namePlaceholder"));
    setAttr("cPhone", "placeholder", pick("enquiryForm.phonePlaceholder"));
    setAttr("cEmail", "placeholder", pick("enquiryForm.emailPlaceholder"));
    setAttr("cMsg", "placeholder", pick("enquiryForm.messagePlaceholder"));

    setText("qr-note", pick("qr.note"));
    setText("qr-download", pick("qr.downloadLabel"));
    setText("qr-save-contact", pick("qr.saveLabel"));

    setText("fab-call", pick("floatBar.call"));
    setText("fab-whatsapp", pick("floatBar.whatsapp"));
    setText("fab-save", pick("floatBar.save"));
    setText("fab-cart", pick("floatBar.cart"));

    setText("cart-title", pick("cart.title"));

    setText("share-title", pick("share.title"));
    setText("share-wa", pick("share.whatsapp"));
    setText("share-fb", pick("share.facebook"));
    setText("share-copy", pick("share.copy"));
    setText("share-more", pick("share.more"));
    setText("share-cancel", pick("share.cancel"));

    setText("promo-title", pick("promo.title"));
    setText("promo-text", pick("promo.text"));
    setText("promo-cta", pick("promo.cta"));
    setText("toastMsg", pick("messages.defaultToast", ""));

    const img = $id("profile-image");
    if (img) {
        img.src = APP.assets?.profileImage || APP.assets?.fallbackImage || "";
        img.alt = APP.assets?.profileAlt || APP.profile?.name || "";
    }
};

function changeQty(id, delta) {
    cart[id] = (cart[id] || 0) + delta;
    cart[id] = cart[id] < 0 ? 0 : cart[id];

    const qty = $id(`qty-${id}`);
    if (qty) {
        qty.textContent = cart[id];
    }

    updateCartBadge();
}

function updateCartBadge() {
    const total = Object.values(cart).reduce((sum, qty) => sum + qty, 0);
    const badge = $id("cartBadge");

    if (!badge) {
        return;
    }

    badge.textContent = total;
    badge.classList.toggle("show", total > 0);
}

function openCart() {
    const items = PRODUCTS.filter((item) => cart[item.id] > 0);
    const body = $id("cartBody");

    if (!body) {
        return;
    }

    if (!items.length) {
        const cartSvg = iconTpl("ui_cart");
        body.innerHTML = `<div class="cart-empty"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.5">${cartSvg}</svg>${pick("cart.empty")}</div>`;
        $id("cartOverlay")?.classList.add("show");
        return;
    }

    let total = 0;
    const rows = items
        .map((item) => {
            const lineTotal = item.price * cart[item.id];
            total += lineTotal;

            return `<div class="cart-item">
                <div class="ci-name">${item.name}<br><small style="color:var(--muted);font-weight:400">${item.brand} · ₹${money(item.price)}</small></div>
                <div class="ci-qty">
                    <button class="ci-qty-btn" onclick="changeQty(${item.id},-1);openCart()"><svg viewBox="0 0 24 24">${iconTpl("minus")}</svg></button>
                    <span class="ci-qty-num">${cart[item.id]}</span>
                    <button class="ci-qty-btn" onclick="changeQty(${item.id},1);openCart()"><svg viewBox="0 0 24 24">${iconTpl("plus")}</svg></button>
                </div>
                <div class="ci-price">₹${money(lineTotal)}</div>
            </div>`;
        })
        .join("");

    body.innerHTML = `${rows}
        <div class="cart-total"><span>${pick("cart.totalLabel")}</span><span class="cart-total-amt">₹${money(total)}</span></div>
        <button class="cart-order-btn" onclick="sendCartWA()">
            <svg class="ic" viewBox="0 0 24 24" stroke="#fff" stroke-width="2">${iconTpl("social_whatsapp")}</svg>
            ${pick("cart.orderButton")}
        </button>`;

    $id("cartOverlay")?.classList.add("show");
}

async function sendCartWA() {
    const items = PRODUCTS.filter((item) => cart[item.id] > 0);
    let total = 0;
    let message = `${tpl(APP.cart?.waHeader, { shopName: SHOP.name })}\n\n`;

    const orderItems = [];
    items.forEach((item) => {
        const lineTotal = item.price * cart[item.id];
        total += lineTotal;

        message += `${tpl(APP.cart?.waLine, {
            brand: item.brand,
            name: item.name,
            qty: cart[item.id],
            total: money(lineTotal),
        })}\n`;

        orderItems.push({
            name: item.name,
            brand: item.brand,
            qty: cart[item.id],
            price: item.price,
            total: lineTotal,
        });
    });

    message += `\n${tpl(APP.cart?.waTotal, { total: money(total) })}\n\n${APP.cart?.waFooter || ""}`;

    await sendSubmission("order", {
        source_template: pick("meta.title") || "electronics-shop-template",
        shop_name: SHOP.name || "",
        name: "",
        phone: "",
        email: "",
        message: "Cart order",
        items: orderItems,
        total: total,
    });

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
    event.target === $id("cartOverlay") && closeCart();
}

function genQR() {
    const el = $id("vcardQR");
    if (!el || typeof QRCode === "undefined") {
        return;
    }

    el.innerHTML = "";
    new QRCode(el, {
        text: SHOP.website,
        width: 165,
        height: 165,
        colorDark: "#0a0f1e",
        colorLight: "#f0f4fa",
        correctLevel: QRCode.CorrectLevel.H,
    });
}

function saveContact() {
    const vcard = `BEGIN:VCARD\nVERSION:3.0\nFN:${SHOP.name}\nORG:${SHOP.name}\nTEL;TYPE=CELL:${SHOP.phone}\nEMAIL:${SHOP.email}\nADR:;;${SHOP.address};;;;\nURL:${SHOP.website}\nNOTE:${SHOP.tagline}\nEND:VCARD`;
    const link = document.createElement("a");

    link.href = URL.createObjectURL(new Blob([vcard], { type: "text/vcard" }));
    link.download = APP.files?.vcard || "contact.vcf";
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

function openEnquiry() {
    $id("enquirySection")?.scrollIntoView({
        behavior: "smooth",
        block: "start",
    });
}

function enquireWA(item) {
    const msg = tpl(APP.messages?.quickEnquiryTemplate, { item });
    window.open(
        `https://wa.me/${SHOP.whatsapp}?text=${encodeURIComponent(msg)}`,
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
    link.download = APP.files?.qr || "qr.png";
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
    event.target === $id("shareModal") && closeShareModal();
}

function closeShareModal() {
    $id("shareModal")?.classList.remove("show");
}

function shareWA() {
    const msg = tpl(APP.messages?.shareTemplate, {
        shopName: SHOP.name,
        website: SHOP.website,
    });

    window.open(`https://wa.me/?text=${encodeURIComponent(msg)}`, "_blank");
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
        closeShareModal();
        return;
    }

    copyLink();
}

function openExternal(url) {
    url && window.open(url, "_blank");
}

function closePromo(event) {
    const overlay = $id("promoOverlay");
    if (!overlay) {
        return;
    }

    if (!event || event.target === overlay) {
        overlay.classList.remove("show");
    }
}

function promoAction() {
    openWA();
    closePromo();
}

async function submitContact() {
    const name = $id("cName")?.value.trim();
    const phone = $id("cPhone")?.value.trim();
    const email = $id("cEmail")?.value.trim();
    const category = $id("cCat")?.value || pick("enquiryForm.defaultCategory");
    const note = $id("cMsg")?.value || pick("enquiryForm.defaultMessage");

    if (!name || !phone) {
        showToast(pick("messages.namePhoneRequired"));
        return;
    }

    const msg = tpl(APP.messages?.formTemplate, {
        shopName: SHOP.name,
        name,
        phone,
        email: email || pick("enquiryForm.defaultEmail"),
        category,
        message: note,
    });

    await sendSubmission("enquiry", {
        source_template: pick("meta.title") || "electronics-shop-template",
        shop_name: SHOP.name || "",
        name,
        phone,
        email,
        message: note,
        items: [{ label: "category", value: category }],
    });

    window.open(
        `https://wa.me/${SHOP.whatsapp}?text=${encodeURIComponent(msg)}`,
        "_blank",
    );

    $id("contactForm").style.display = "none";
    $id("contactSuccess").style.display = "block";
}

function resetContact() {
    $id("contactForm").style.display = "block";
    $id("contactSuccess").style.display = "none";

    ["cName", "cPhone", "cEmail", "cCat", "cMsg"].forEach((id) => {
        const field = $id(id);
        field && (field.value = "");
    });
}

function showToast(message) {
    const toast = $id("toast");
    const msg = $id("toastMsg");

    if (!toast || !msg) {
        return;
    }

    msg.textContent = message;
    toast.classList.add("show");

    setTimeout(() => {
        toast.classList.remove("show");
    }, 2800);
}

const renderAll = () => {
    fillStaticContent();
    renderProfileBadges();
    renderStats();
    renderWhyChoose();
    renderCategories();
    renderProducts();
    renderRepairs();
    renderBrands();
    renderGallery();
    renderHours();
    renderSocial();
    renderPayments();
    renderEnquiryCategories();
    updateCartBadge();
    genQR();
};

function boot() {
    APP = window.__APP__ || {};
    SHOP = APP.shop || {};
    SHOP.website = SHOP.website || window.location.href;
    PRODUCTS = APP.products || [];
    cart = {};
}

"loading" === document.readyState
    ? document.addEventListener("DOMContentLoaded", boot)
    : boot();
