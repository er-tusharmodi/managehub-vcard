const $id = (id) => document.getElementById(id);
const $$ = (selector, root = document) =>
    Array.from(root.querySelectorAll(selector));

let APP = {};
let SHOP = {};
let PRODUCTS = [];
let cart = {};

const SOCIAL_ICON_MAP = {
    whatsapp: {
        className: "ic-wa",
        svg: `<svg class="ic" viewBox="0 0 24 24" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>`,
    },
    facebook: {
        className: "ic-fb",
        svg: `<svg class="ic" viewBox="0 0 24 24" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>`,
    },
    instagram: {
        className: "ic-ig",
        svg: `<svg class="ic" viewBox="0 0 24 24" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>`,
    },
    youtube: {
        className: "ic-yt",
        svg: `<svg class="ic" viewBox="0 0 24 24" stroke-width="2"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/></svg>`,
    },
};

const PAYMENT_ICON_MAP = {
    upi: `<svg class="ic" viewBox="0 0 24 24" stroke="#6a1b9a" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>`,
    bank: `<svg class="ic" viewBox="0 0 24 24" stroke="#1565c0" stroke-width="2"><line x1="3" y1="22" x2="21" y2="22"/><line x1="6" y1="18" x2="6" y2="11"/><line x1="10" y1="18" x2="10" y2="11"/><line x1="14" y1="18" x2="14" y2="11"/><line x1="18" y1="18" x2="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg>`,
    cash: `<svg class="ic" viewBox="0 0 24 24" stroke="#2e7d32" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>`,
};

const renderTemplate = (template, values = {}) =>
    (template || "").replace(/\{\{(\w+)\}\}/g, (_, key) => values[key] ?? "");

const setText = (id, value = "") => {
    const el = $id(id);
    if (el) {
        el.textContent = value;
    }
};

const setHTML = (id, value = "") => {
    const el = $id(id);
    if (el) {
        el.innerHTML = value;
    }
};

const setPlaceholder = (id, value = "") => {
    const el = $id(id);
    if (el) {
        el.placeholder = value;
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

const hydrateImages = () => {
    const banner = $id("banner-cover");
    const profile = $id("profile-image");

    if (banner) {
        const bannerSrc =
            APP.assets?.bannerImage || APP.assets?.fallbackImage || "";
        banner.src = bannerSrc;
        banner.alt = APP.meta?.bannerAlt || "";
        banner.onerror = () => {
            banner.style.background = `url(${APP.assets?.fallbackImage || bannerSrc})`;
            banner.style.display = "block";
            banner.style.height = "220px";
        };
    }

    if (profile) {
        profile.src =
            APP.assets?.profileImage || APP.assets?.fallbackImage || "";
        profile.alt = APP.meta?.profileAlt || APP.profile?.name || "";
        profile.onerror = () => {
            profile.style.display = "none";
            profile.parentNode.innerHTML = "🍬";
        };
    }
};

const hydrateStaticText = () => {
    document.title = APP.meta?.title || "";

    setText("top-share-label", APP.header?.shareLabel);
    setText("top-save-label", APP.header?.saveContactLabel);

    setText("profile-name", APP.profile?.name);
    setText("profile-role", APP.profile?.role);
    setText("profile-bio", APP.profile?.bio);

    setText("action-call-label", APP.profile?.actions?.call);
    setText("action-whatsapp-label", APP.profile?.actions?.whatsapp);
    setText("action-save-label", APP.profile?.actions?.save);
    setText("action-email-label", APP.profile?.actions?.email);
    setText("action-directions-label", APP.profile?.actions?.directions);
    setText("action-share-label", APP.profile?.actions?.share);

    setText("location-title", APP.sections?.locationTitle);
    setText("location-line1", APP.location?.line1);
    setText("location-line2", APP.location?.line2);
    setText("location-map-label", APP.location?.mapButtonLabel);

    setText("social-title", APP.sections?.socialTitle);
    setText("services-title", APP.sections?.servicesTitle);
    setText("products-title", APP.sections?.productsTitle);
    setText("gallery-title", APP.sections?.galleryTitle);
    setText("hours-title", APP.sections?.hoursTitle);
    setText("hours-badge-label", APP.businessHours?.badge);
    setText("suggest-hours-label", APP.businessHours?.suggestLabel);

    setText("qr-title", APP.sections?.qrTitle);
    setText("qr-description", APP.qr?.description);
    setText("qr-download-label", APP.qr?.downloadLabel);
    setText("qr-copy-label", APP.qr?.copyLabel);

    setText("payment-title", APP.sections?.paymentTitle);

    setText("contact-title", APP.sections?.contactTitle);
    setText("contact-label-name", APP.contactForm?.labels?.name);
    setText("contact-label-mobile", APP.contactForm?.labels?.mobile);
    setText("contact-label-email", APP.contactForm?.labels?.email);
    setText("contact-label-message", APP.contactForm?.labels?.message);

    setPlaceholder("cName", APP.contactForm?.placeholders?.name);
    setPlaceholder("cPhone", APP.contactForm?.placeholders?.mobile);
    setPlaceholder("cEmail", APP.contactForm?.placeholders?.email);
    setPlaceholder("cMsg", APP.contactForm?.placeholders?.message);

    setText("contact-submit-label", APP.contactForm?.submitLabel);
    setText("contact-success-title", APP.contactForm?.successTitle);
    setText("contact-success-desc", APP.contactForm?.successDescription);
    setText("contact-success-btn-label", APP.contactForm?.successButtonLabel);

    setHTML(
        "footer-copy",
        `${APP.footer?.copyright || ""} <strong>${APP.footer?.brand || ""}</strong> · ${APP.footer?.rights || ""}`,
    );
    setHTML(
        "footer-powered",
        `${APP.footer?.poweredBy || ""} <strong>${APP.footer?.poweredBrand || ""}</strong>`,
    );

    setText("float-call-label", APP.floatingBar?.call);
    setText("float-save-label", APP.floatingBar?.save);
    setText("float-wa-label", APP.floatingBar?.whatsapp);
    setText("float-cart-label", APP.floatingBar?.cart);

    setText("cart-title", APP.cart?.title);
    setHTML("cart-empty-message", APP.cart?.emptyMessage || "");

    setText("share-modal-title", APP.shareModal?.title);
    setText("share-opt-wa", APP.shareModal?.whatsapp);
    setText("share-opt-copy", APP.shareModal?.copy);
    setText("share-opt-fb", APP.shareModal?.facebook);
    setText("share-opt-more", APP.shareModal?.more);
    setText("share-cancel-label", APP.shareModal?.cancel);

    hydrateImages();
};

const bindSocialLinks = () => {
    const links = APP.socialLinks || [];
    const socialList = $id("social-list");

    if (!socialList) {
        return;
    }

    $$(".social-item", socialList).forEach((el) => {
        const item = links[Number(el.dataset.index)];

        if (item?.action === "openWA") {
            el.addEventListener("click", openWA);
            return;
        }

        if (item?.url) {
            el.addEventListener("click", () => window.open(item.url, "_blank"));
        }
    });
};

const renderServices = () => {
    const servicesGrid = $id("services-grid");
    const fallback = APP.assets?.fallbackImage || "";

    if (!servicesGrid) {
        return;
    }

    servicesGrid.innerHTML = (APP.services || [])
        .map(
            (service) => `
            <div class="svc-card">
                <div class="svc-img">
                    <div class="svc-img-placeholder" style="background:url('${service.image || fallback}') center/cover no-repeat"></div>
                </div>
                <div class="svc-body">
                    <div class="svc-name">${service.name || ""}</div>
                    <div class="svc-desc">${service.description || ""}</div>
                </div>
            </div>`,
        )
        .join("");
};

function renderProducts() {
    $id("productsGrid").innerHTML = PRODUCTS.map(
        (item) => `
        <div class="prod-card">
            <div class="prod-img">
                <div class="prod-img-placeholder" style="background:${item.bg};height:100%"></div>
                ${
                    item.tag
                        ? `<span class="prod-tag" style="background:${item.tagColor};color:#fff">${item.tag}</span>`
                        : ""
                }
            </div>
            <div class="prod-body">
                <div class="prod-name">${item.name}</div>
                <div class="prod-desc">${item.desc}</div>
                <div class="prod-footer">
                    <div>
                        <span class="prod-price">₹${item.price}</span>
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
    ).join("");
}

const renderGallery = () => {
    const galleryGrid = $id("gallery-grid");

    if (!galleryGrid) {
        return;
    }

    galleryGrid.innerHTML = (APP.gallery || [])
        .map(
            (item) => `
            <div class="g-item">
                <div style="height:100%;background:url('${item.image || APP.assets?.fallbackImage || ""}') center/cover no-repeat"></div>
            </div>`,
        )
        .join("");
};

const renderHours = () => {
    const hoursRows = $id("hours-rows");

    if (!hoursRows) {
        return;
    }

    hoursRows.innerHTML = (APP.businessHours?.days || [])
        .map((row) => {
            const isClosed = !row.open || /closed/i.test(row.time || "");
            return `
                <tr class="${row.open ? "open-row" : ""}">
                    <td class="day">${row.day || ""}</td>
                    <td class="time${isClosed ? " closed" : ""}">${row.time || ""}</td>
                </tr>`;
        })
        .join("");
};

const renderPaymentMethods = () => {
    const paymentList = $id("payment-list");

    if (!paymentList) {
        return;
    }

    paymentList.innerHTML = (APP.paymentMethods || [])
        .map((item) => {
            const icon = PAYMENT_ICON_MAP[item.type] || PAYMENT_ICON_MAP.cash;
            return `
                <div class="pay-item">
                    <div class="pay-icon-wrap">${icon}</div>
                    <div>
                        <div class="pay-name">${item.name || ""}</div>
                        <div class="pay-detail">${item.detail || ""}</div>
                    </div>
                </div>`;
        })
        .join("");
};

function changeQty(id, delta) {
    cart[id] = (cart[id] || 0) + delta;
    if (cart[id] < 0) {
        cart[id] = 0;
    }

    const qtyNode = $id(`qty-${id}`);
    if (qtyNode) {
        qtyNode.textContent = cart[id];
    }

    updateCartBadge();
}

function updateCartBadge() {
    const total = Object.values(cart).reduce((sum, qty) => sum + qty, 0);
    const cartBadge = $id("cartBadge");

    cartBadge.textContent = total;
    cartBadge.classList.toggle("show", total > 0);
}

function openCart() {
    const items = PRODUCTS.filter((item) => cart[item.id] > 0);
    const cartBody = $id("cartBody");

    if (items.length) {
        let total = 0;
        let html = items
            .map((item) => {
                const itemTotal = item.price * cart[item.id];
                total += itemTotal;

                return `<div class="cart-item">
                    <div class="ci-name">${item.name}<br><small style="color:var(--muted);font-weight:400">₹${item.price}${item.per}</small></div>
                    <div class="ci-qty">
                        <button class="ci-qty-btn" onclick="changeQty(${item.id},-1);openCart()"><svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/></svg></button>
                        <span class="ci-qty-num">${cart[item.id]}</span>
                        <button class="ci-qty-btn" onclick="changeQty(${item.id},1);openCart()"><svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></button>
                    </div>
                    <div class="ci-price">₹${itemTotal}</div>
                </div>`;
            })
            .join("");

        html += `<div class="cart-total"><span>${APP.cart?.totalLabel || "Total"}</span><span class="cart-total-amt">₹${total}</span></div>`;
        html += `<button class="cart-order-btn" onclick="sendCartWA()">
            <svg class="ic" viewBox="0 0 24 24" stroke="#fff" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
            ${APP.cart?.orderButton || ""}
        </button>`;

        cartBody.innerHTML = html;
    } else {
        cartBody.innerHTML = `<div class="cart-empty"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>${APP.cart?.emptyMessage || ""}</div>`;
    }

    $id("cartOverlay").classList.add("show");
}

async function sendCartWA() {
    const items = PRODUCTS.filter((item) => cart[item.id] > 0);
    let total = 0;
    let message = `${renderTemplate(APP.messages?.cartHeader, { name: SHOP.name })}\n\n`;

    const orderItems = [];
    items.forEach((item) => {
        const lineTotal = item.price * cart[item.id];
        total += lineTotal;
        message += `• ${item.name} x${cart[item.id]} = ₹${lineTotal}\n`;
        orderItems.push({
            name: item.name,
            qty: cart[item.id],
            price: item.price,
            total: lineTotal,
        });
    });

    message += `\n💰 *${APP.cart?.totalLabel || "Total"}: ₹${total}*\n\n${APP.messages?.cartConfirm || ""}`;

    await sendSubmission("order", {
        source_template: APP.meta?.title || "sweetshop-template",
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
    $id("cartOverlay").classList.remove("show");
}

function closeCartOutside(event) {
    if (event.target === $id("cartOverlay")) {
        closeCart();
    }
}

function genQR() {
    const qrNode = $id("vcardQR");
    qrNode.innerHTML = "";

    new QRCode(qrNode, {
        text: SHOP.website,
        width: 165,
        height: 165,
        colorDark: "#2e1503",
        colorLight: "#fff9f0",
        correctLevel: QRCode.CorrectLevel.H,
    });
}

function saveContact() {
    const vCard = `BEGIN:VCARD\nVERSION:3.0\nFN:${SHOP.name}\nORG:${SHOP.name}\nTEL;TYPE=CELL:${SHOP.phone}\nEMAIL:${SHOP.email}\nADR:;;${SHOP.address};;;;\nURL:${SHOP.website}\nNOTE:${SHOP.tagline}\nEND:VCARD`;
    const anchor = document.createElement("a");

    anchor.href = URL.createObjectURL(
        new Blob([vCard], { type: "text/vcard" }),
    );
    anchor.download = APP.files?.vcardName || "contact.vcf";
    anchor.click();

    showToast(APP.messages?.contactSaved || "");
}

function callShop() {
    window.location.href = `tel:${SHOP.phone}`;
}

function openWA() {
    window.open(
        `https://wa.me/${SHOP.whatsapp}?text=${encodeURIComponent(APP.messages?.waIntro || "")}`,
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
    const text = renderTemplate(APP.messages?.waEnquiryTemplate, { item });
    window.open(
        `https://wa.me/${SHOP.whatsapp}?text=${encodeURIComponent(text)}`,
        "_blank",
    );
}

function downloadQR() {
    const canvas = document.querySelector("#vcardQR canvas");

    if (!canvas) {
        showToast(APP.messages?.qrNotReady || "");
        return;
    }

    const anchor = document.createElement("a");
    anchor.href = canvas.toDataURL("image/png");
    anchor.download = APP.files?.qrName || "qr.png";
    anchor.click();

    showToast(APP.messages?.qrDownloaded || "");
}

function copyLink() {
    navigator.clipboard
        .writeText(SHOP.website)
        .then(() => showToast(APP.messages?.linkCopied || ""));

    $id("shareModal").classList.remove("show");
}

function openShare() {
    $id("shareModal").classList.add("show");
}

function closeShare(event) {
    if (event.target === $id("shareModal")) {
        $id("shareModal").classList.remove("show");
    }
}

function shareWA() {
    const text = renderTemplate(APP.messages?.shareTemplate, {
        name: SHOP.name,
        website: SHOP.website,
    });

    window.open(`https://wa.me/?text=${encodeURIComponent(text)}`, "_blank");
    $id("shareModal").classList.remove("show");
}

function shareFB() {
    window.open(
        `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(SHOP.website)}`,
        "_blank",
    );

    $id("shareModal").classList.remove("show");
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

async function submitContact() {
    const name = $id("cName").value.trim();
    const phone = $id("cPhone").value.trim();
    const email = $id("cEmail").value.trim();
    const note = $id("cMsg").value.trim();

    if (!name || !phone) {
        showToast(APP.messages?.contactRequired || "");
        return;
    }

    const message = `✉️ *Message – ${SHOP.name}*\n👤 ${name}\n📞 ${phone}\n📧 ${email || "—"}\n💬 ${note || "No message"}`;

    await sendSubmission("contact", {
        source_template: APP.meta?.title || "",
        shop_name: SHOP.name || "",
        name,
        phone,
        email,
        message: note,
    });

    window.open(
        `https://wa.me/${SHOP.whatsapp}?text=${encodeURIComponent(message)}`,
        "_blank",
    );

    $id("contactForm").style.display = "none";
    $id("contactSuccess").style.display = "block";
}

function resetContact() {
    $id("contactForm").style.display = "block";
    $id("contactSuccess").style.display = "none";

    ["cName", "cPhone", "cEmail", "cMsg"].forEach((id) => {
        $id(id).value = "";
    });
}

function showToast(message) {
    const toast = $id("toast");

    toast.innerHTML = `<svg viewBox="0 0 24 24" stroke-width="2" width="15" height="15"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>${message}`;
    toast.classList.add("show");

    setTimeout(() => toast.classList.remove("show"), 2500);
}

const bootstrap = () => {
    APP = window.APP_DATA || {};
    SHOP = {
        ...(APP.shop || {}),
        website: APP.shop?.website || window.location.href,
    };
    PRODUCTS = APP.products || [];

    hydrateStaticText();
    bindSocialLinks();
    updateCartBadge();
    genQR();
};

window.addEventListener("DOMContentLoaded", bootstrap);
