const $id = (id) => document.getElementById(id);
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

const iconTpl = (id, fallbackId) => {
    const primary = $id(id)?.innerHTML || "";
    if (primary) {
        return primary;
    }
    return fallbackId ? $id(fallbackId)?.innerHTML || "" : "";
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

let APP = {};
let SHOP = {};
let selectedSlot = "";

const SERVICE_ICONS = {
    scissor: `<path d="M6 2v6M6 22v-6M6 8c2 0 4 2 4 4s-2 4-4 4M18 2v6M18 22v-6M18 8c-2 0-4 2-4 4s2 4 4 4"/>`,
    heart: `<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>`,
    razor: `<path d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 0 0 6.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 0 0 6.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>`,
    sun: `<circle cx="12" cy="12" r="3"/><path d="M12 2v3m0 14v3M4.22 4.22l2.12 2.12m11.32 11.32 2.12 2.12M2 12h3m14 0h3M4.22 19.78l2.12-2.12m11.32-11.32 2.12-2.12"/>`,
    leaf: `<path d="M17 8C8 10 5.9 16.17 3.82 19.06A1 1 0 0 0 4.5 21 8 8 0 0 0 12 17a1 1 0 0 0 1-1 3 3 0 0 0 3-3 5 5 0 0 0 1.83-3.94C17.83 8.42 17 8 17 8z"/>`,
};

const STAR_ICON = `<svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>`;
const CHECK_ICON = `<svg viewBox="0 0 24 24" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`;
const SLOT_AVAIL_ICON = `<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`;

const TIP_ICONS = {
    clock: `<svg style="stroke:currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`,
    shield: `<svg style="stroke:currentColor" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>`,
    leaf: `<svg style="stroke:currentColor" viewBox="0 0 24 24"><path d="M17 8C8 10 5.9 16.17 3.82 19.06A1 1 0 0 0 4.5 21 8 8 0 0 0 12 17a1 1 0 0 0 1-1 3 3 0 0 0 3-3 5 5 0 0 0 1.83-3.94C17.83 8.42 17 8 17 8z"/></svg>`,
    heart: `<svg style="stroke:currentColor" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>`,
    layers: `<svg style="stroke:currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>`,
};

const SOCIAL_ICONS = {
    whatsapp: {
        cls: "ic-wa",
        svg: `<svg class="ic" viewBox="0 0 24 24" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>`,
    },
    instagram: {
        cls: "ic-ig",
        svg: `<svg class="ic" viewBox="0 0 24 24" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>`,
    },
    youtube: {
        cls: "ic-yt",
        svg: `<svg class="ic" viewBox="0 0 24 24" stroke-width="2"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-1.96C18.88 4 12 4 12 4s-6.88 0-8.6.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.94 1.96C5.12 20 12 20 12 20s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/></svg>`,
    },
    facebook: {
        cls: "ic-fb",
        svg: `<svg class="ic" viewBox="0 0 24 24" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>`,
    },
};

const PAYMENT_ICONS = {
    cash: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>`,
    card: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>`,
};

const renderProfileStats = () => {
    const stats = APP.profile?.stats || [];
    setHTML(
        "profileStats",
        stats
            .map(
                (item, idx) => `
                    <div class="pstat">
                        <div class="pstat-num">${item.value || ""}</div>
                        <div class="pstat-lbl">${item.label || ""}</div>
                    </div>
                    ${idx < stats.length - 1 ? `<div class="stat-div"></div>` : ""}`,
            )
            .join(""),
    );
};

const renderServices = () => {
    setHTML(
        "servicesGrid",
        (APP.services || [])
            .map(
                (item) => `
                    <div class="svc-card">
                        <div class="svc-thumb" style="background:${item.bg || ""}">
                            <div class="svc-thumb-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" width="22" height="22">${SERVICE_ICONS[item.icon] || ""}</svg>
                            </div>
                            <div class="svc-price-tag">${item.price || ""}</div>
                        </div>
                        <div class="svc-body">
                            <div class="svc-name">${item.name || ""}</div>
                            <div class="svc-desc">${item.desc || ""}</div>
                            <div class="svc-footer">
                                <div class="svc-dur"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>${item.dur || ""}</div>
                            </div>
                        </div>
                    </div>`,
            )
            .join(""),
    );
};

const packageBg = (badgeClass) =>
    "hot" === badgeClass
        ? "linear-gradient(135deg,#fdf3d0,#fefce8)"
        : "value" === badgeClass
          ? "linear-gradient(135deg,#f0fdf4,#dcfce7)"
          : "linear-gradient(135deg,#e0f2fe,#f0f9ff)";

const renderPackages = () => {
    setHTML(
        "pkgList",
        (APP.packages || [])
            .map(
                (item) => `
                    <div class="pkg-card${"hot" === item.badgeClass ? " hot" : ""}">
                        <div class="pkg-top" style="background:${packageBg(item.badgeClass)}">
                            <div class="pkg-name">${item.name || ""}</div>
                            <span class="pkg-badge badge-${item.badgeClass || ""}">${item.badge || ""}</span>
                        </div>
                        <div class="pkg-items">
                            ${(item.items || [])
                                .map(
                                    (label) =>
                                        `<div class="pkg-item">${CHECK_ICON}${label}</div>`,
                                )
                                .join("")}
                        </div>
                        <div class="pkg-footer">
                            <div class="pkg-price-wrap">
                                <div class="pkg-price">${item.price || ""}</div>
                                ${item.old ? `<div class="pkg-old">${item.old}</div>` : ""}
                                ${item.save ? `<div class="pkg-save">${item.save}</div>` : ""}
                            </div>
                        </div>
                    </div>`,
            )
            .join(""),
    );
};

const renderSlots = () => {
    const slots = APP.booking?.slots || [];
    const defaultSlot =
        slots.find((slot) => slot.selected && !slot.full)?.slot ||
        APP.booking?.defaultSlot ||
        slots.find((slot) => !slot.full)?.slot ||
        "";

    selectedSlot = defaultSlot;

    setHTML(
        "slotGrid",
        slots
            .map((slot) => {
                if (slot.full) {
                    return `
                        <div class="slot-card full" data-slot="${slot.slot || ""}">
                            <div class="slot-session">${slot.session || ""}</div>
                            <div class="slot-time">${slot.time || ""}</div>
                            <div class="slot-full-lbl">${slot.fullLabel || pick("labels.fullyBooked")}</div>
                        </div>`;
                }

                return `
                    <div class="slot-card${selectedSlot === slot.slot ? " selected" : ""}" onclick="selectSlot(this)" data-slot="${slot.slot || ""}">
                        <div class="slot-check">
                            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12" /></svg>
                        </div>
                        <div class="slot-session">${slot.session || ""}</div>
                        <div class="slot-time">${slot.time || ""}</div>
                        <div class="slot-avail">${SLOT_AVAIL_ICON}${slot.availability || ""}</div>
                    </div>`;
            })
            .join(""),
    );
};

const renderBookingOptions = () => {
    const serviceSelect = $id("bService");
    if (serviceSelect) {
        serviceSelect.innerHTML = `
            <option value="">${pick("booking.form.servicePlaceholder")}</option>
            ${(APP.booking?.form?.services || [])
                .map((item) => `<option>${item}</option>`)
                .join("")}`;
    }

    const barberSelect = $id("bBarber");
    if (barberSelect) {
        barberSelect.innerHTML = `
            <option value="">${pick("booking.form.barberPlaceholder")}</option>
            ${(APP.booking?.form?.barbers || [])
                .map((item) => `<option>${item}</option>`)
                .join("")}`;
    }
};

const renderBarbers = () => {
    setHTML(
        "barbersList",
        (APP.barbers || [])
            .map(
                (item) => `
                    <div class="barber-card">
                        <div class="barber-avatar" style="background:${item.gradient || "linear-gradient(135deg,#0f1923,#2e4a62)"}">${item.avatar || ""}</div>
                        <div class="barber-info">
                            <div class="barber-name">${item.name || ""}</div>
                            <div class="barber-role">${item.role || ""}</div>
                            <div class="barber-exp">${item.exp || ""}</div>
                            <div class="barber-skills">
                                ${(item.skills || [])
                                    .map(
                                        (skill) =>
                                            `<span class="b-chip">${skill}</span>`,
                                    )
                                    .join("")}
                            </div>
                        </div>
                    </div>`,
            )
            .join(""),
    );
};

const renderGallery = () => {
    setHTML(
        "galleryScroll",
        (APP.gallery || [])
            .map(
                (item) => `
                    <div class="gallery-item">
                        <div class="gallery-inner" style="background:${item.bg || ""}">${item.emoji || ""}</div>
                        <div class="gallery-badge">${item.badge || ""}</div>
                        <div class="gallery-label">${item.label || ""}</div>
                    </div>`,
            )
            .join(""),
    );
};

const renderReviews = () => {
    setText("reviews-rating-big", APP.reviews?.rating || "");
    setText("reviews-rating-count", APP.reviews?.count || "");

    setHTML(
        "reviewsList",
        (APP.reviews?.items || [])
            .map(
                (item) => `
                    <div class="review-card">
                        <div class="rv-head">
                            <div class="rv-avatar" style="background:${item.gradient || "linear-gradient(135deg,#0f1923,#2e4a62)"}">${item.avatar || ""}</div>
                            <div>
                                <div class="rv-name">${item.name || ""}</div>
                                <div class="rv-date">${item.date || ""}</div>
                            </div>
                            <div class="rv-stars">${STAR_ICON}${STAR_ICON}${STAR_ICON}${STAR_ICON}${STAR_ICON}</div>
                        </div>
                        <div class="rv-text">${item.text || ""}</div>
                        <span class="rv-service">${item.service || ""}</span>
                    </div>`,
            )
            .join(""),
    );
};

const renderTips = () => {
    setHTML(
        "tipsScroll",
        (APP.tips || [])
            .map(
                (item) => `
                    <div class="tip-card">
                        <div class="tip-icon" style="background:${item.bg || "#fdf3d0"};color:${item.color || "#92640a"}">
                            ${TIP_ICONS[item.icon] || ""}
                        </div>
                        <div class="tip-tag" style="color:${item.color || "#92640a"}">${item.tag || ""}</div>
                        <div class="tip-text">${item.text || ""}</div>
                    </div>`,
            )
            .join(""),
    );
};

const renderProducts = () => {
    setHTML(
        "productsGrid",
        (APP.products || [])
            .map(
                (item) => `
                    <div class="prod-card">
                        <div class="prod-thumb" style="background:${item.thumbBg || ""}">
                            ${item.tag ? `<span class="prod-tag" style="background:${item.tagBg};color:${item.tagColor}">${item.tag}</span>` : ""}
                        </div>
                        <div class="prod-body">
                            <div class="prod-name">${item.name || ""}</div>
                            <div class="prod-desc">${item.desc || ""}</div>
                            <div class="prod-footer">
                                <span><span class="prod-price">${item.price || ""}</span>${item.old ? `<span class="prod-old">${item.old}</span>` : ""}</span>
                            </div>
                        </div>
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
                        <td class="time">${row.time || ""}</td>
                    </tr>`,
            )
            .join(""),
    );
};

const socialAction = (item) => {
    if ("openWA" === item.action) {
        return "return (openWA(), !1);";
    }
    if (item.url) {
        return `return (openExternal('${sq(item.url)}'), !1);`;
    }
    return "return !1;";
};

const renderSocial = () => {
    setHTML(
        "socialList",
        (APP.social || [])
            .map((item) => {
                const icon = SOCIAL_ICONS[item.type] || SOCIAL_ICONS.instagram;
                return `
                    <a class="social-item" href="#" onclick="${socialAction(item)}">
                        <div class="s-ico ${icon.cls}">${icon.svg}</div>
                        <div>
                            <div class="s-name">${item.name || ""}</div>
                            <div class="s-val">${item.value || ""}</div>
                        </div>
                        <div class="s-arrow">
                            <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6" /></svg>
                        </div>
                    </a>`;
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
                            <span style="display:flex;color:${item.stroke || "#15803d"}">${PAYMENT_ICONS[item.icon] || ""}</span>
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

const fillStatic = () => {
    document.title = pick("meta.title");

    setText("banner-title", pick("shop.name"));
    setText("banner-subtitle", pick("shop.subtitle"));
    setText("banner-share-label", pick("banner.shareLabel"));
    setText("banner-verified-label", pick("banner.verifiedLabel"));
    setText("status-open-label", pick("status.openLabel"));
    setText("status-next-slot", pick("status.nextSlotLabel"));

    setText("profile-owner-tag", pick("profile.ownerTag"));
    setText("profile-name", pick("profile.name"));
    setText("profile-role", pick("profile.role"));
    setText("profile-tagline", pick("profile.tagline"));

    setText("action-call-label", pick("profile.actions.call"));
    setText("action-whatsapp-label", pick("profile.actions.whatsapp"));
    setText("action-book-label", pick("profile.actions.book"));
    setText("action-email-label", pick("profile.actions.email"));
    setText("action-direction-label", pick("profile.actions.direction"));
    setText("action-share-label", pick("profile.actions.share"));

    setText("sec-title-services", pick("sections.services.title"));
    setText("sec-sub-services", pick("sections.services.sub"));
    setText("sec-title-packages", pick("sections.packages.title"));
    setText("sec-sub-packages", pick("sections.packages.sub"));
    setText("sec-title-booking", pick("sections.booking.title"));
    setText("sec-title-barbers", pick("sections.barbers.title"));
    setText("sec-title-gallery", pick("sections.gallery.title"));
    setText("sec-sub-gallery", pick("sections.gallery.sub"));
    setText("sec-title-reviews", pick("sections.reviews.title"));
    setText("sec-sub-reviews", pick("sections.reviews.sub"));
    setText("sec-title-tips", pick("sections.tips.title"));
    setText("sec-sub-tips", pick("sections.tips.sub"));
    setText("sec-title-products", pick("sections.products.title"));
    setText("sec-sub-products", pick("sections.products.sub"));
    setText("sec-title-hours", pick("sections.hours.title"));
    setText("sec-title-location", pick("sections.location.title"));
    setText("sec-title-social", pick("sections.social.title"));
    setText("sec-title-payments", pick("sections.payments.title"));
    setText("sec-title-qr", pick("sections.qr.title"));

    setText("booking-name-label", pick("booking.form.nameLabel"));
    setText("booking-phone-label", pick("booking.form.phoneLabel"));
    setText("booking-service-label", pick("booking.form.serviceLabel"));
    setText("booking-barber-label", pick("booking.form.barberLabel"));
    setText("booking-note-label", pick("booking.form.noteLabel"));
    setText("booking-submit-label", pick("booking.form.submitLabel"));
    setText("booking-success-title", pick("booking.success.title"));
    setText("booking-success-text", pick("booking.success.text"));
    setText("booking-success-button", pick("booking.success.button"));

    setAttr("bName", "placeholder", pick("booking.form.namePlaceholder"));
    setAttr("bPhone", "placeholder", pick("booking.form.phonePlaceholder"));
    setAttr("bNote", "placeholder", pick("booking.form.notePlaceholder"));

    setText("hours-today-label", pick("hours.today"));

    setText("location-name", pick("location.title"));
    setText("location-line1", pick("location.line1"));
    setText("location-line2", pick("location.line2"));
    setText("location-line3", pick("location.line3"));
    setText("location-map-label", pick("location.mapLabel"));

    setText("qr-note", pick("qr.note"));
    setText("qr-save-label", pick("qr.saveLabel"));
    setText("qr-download-label", pick("qr.downloadLabel"));

    setText("footer-line1", pick("footer.line1"));
    setText("footer-brand", pick("footer.brand"));
    setText("footer-line2", pick("footer.line2"));
    setText("footer-line3", pick("footer.line3"));
    setText("footer-powered", pick("footer.powered"));

    setText("fab-call-label", pick("floatBar.call"));
    setText("fab-book-label", pick("floatBar.book"));
    setText("fab-whatsapp-label", pick("floatBar.whatsapp"));
    setText("fab-save-label", pick("floatBar.save"));

    setText("share-title", pick("share.title"));
    setText("share-wa-label", pick("share.whatsapp"));
    setText("share-copy-label", pick("share.copy"));
    setText("share-more-label", pick("share.more"));
    setText("share-facebook-label", pick("share.facebook"));
    setText("share-cancel-label", pick("share.cancel"));

    setText("promo-title", pick("promo.title"));
    setText("promo-text", pick("promo.text"));
    setText("promo-cta-label", pick("promo.cta"));
    setText("toastMsg", pick("messages.defaultToast"));

    const image = $id("profile-image");
    if (image) {
        image.src = APP.assets?.profileImage || APP.assets?.fallbackImage || "";
        image.alt = APP.shop?.name || "";
    }
};

function selectSlot(el) {
    if (el.classList.contains("full")) {
        return;
    }

    document.querySelectorAll(".slot-card").forEach((node) => {
        node.classList.remove("selected");
    });

    el.classList.add("selected");
    selectedSlot = el.dataset.slot || selectedSlot;
}

async function confirmBooking() {
    const name = $id("bName")?.value.trim();
    const phone = $id("bPhone")?.value.trim();

    if (!name || !phone) {
        showToast(pick("messages.namePhoneRequired"));
        return;
    }

    const service =
        $id("bService")?.value || pick("booking.form.defaultService");
    const barber = $id("bBarber")?.value || pick("booking.form.defaultBarber");
    const note = $id("bNote")?.value || pick("booking.form.defaultNote");
    const slot = selectedSlot || APP.booking?.defaultSlot || "";

    const msg = tpl(APP.messages?.bookingTemplate, {
        shopName: SHOP.name,
        name,
        phone,
        service,
        barber,
        slot,
        note,
    });

    await sendSubmission("booking", {
        source_template: pick("meta.title") || "mens-salon-template",
        shop_name: SHOP.name || "",
        name,
        phone,
        message: note,
        items: [
            { label: "service", value: service },
            { label: "barber", value: barber },
            { label: "slot", value: slot },
        ],
    });

    window.open(
        `https://wa.me/${SHOP.whatsapp}?text=${encodeURIComponent(msg)}`,
        "_blank",
    );

    $id("bookForm").style.display = "none";
    $id("bookSuccess").style.display = "block";
}

function resetBooking() {
    $id("bookForm").style.display = "block";
    $id("bookSuccess").style.display = "none";

    ["bName", "bPhone", "bNote"].forEach((id) => {
        const field = $id(id);
        field && (field.value = "");
    });

    ["bService", "bBarber"].forEach((id) => {
        const select = $id(id);
        select && (select.selectedIndex = 0);
    });
}

function scrollToBooking() {
    $id("bookingSection")?.scrollIntoView({ behavior: "smooth" });
}

function bookSvc(service) {
    const msg = tpl(APP.messages?.bookServiceTemplate, { service });
    window.open(
        `https://wa.me/${SHOP.whatsapp}?text=${encodeURIComponent(msg)}`,
        "_blank",
    );
}

function bookBarber(barber) {
    const msg = tpl(APP.messages?.bookBarberTemplate, { barber });
    window.open(
        `https://wa.me/${SHOP.whatsapp}?text=${encodeURIComponent(msg)}`,
        "_blank",
    );
}

function enquireProduct(product) {
    const msg = tpl(APP.messages?.productTemplate, { product });
    window.open(
        `https://wa.me/${SHOP.whatsapp}?text=${encodeURIComponent(msg)}`,
        "_blank",
    );
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

function saveContact() {
    const vcard = `BEGIN:VCARD\nVERSION:3.0\nFN:${SHOP.name}\nORG:${SHOP.name}\nTEL;TYPE=CELL:${SHOP.phone}\nEMAIL:${SHOP.email}\nADR:;;${SHOP.address};;;;\nURL:${SHOP.website}\nNOTE:${SHOP.tagline}\nEND:VCARD`;
    const link = document.createElement("a");

    link.href = URL.createObjectURL(new Blob([vcard], { type: "text/vcard" }));
    link.download = APP.files?.vcard || "contact.vcf";
    link.click();

    showToast(pick("messages.contactSaved"));
}

function genQR() {
    const target = $id("vcardQR");
    if (!target || "undefined" === typeof QRCode) {
        return;
    }

    target.innerHTML = "";
    new QRCode(target, {
        text: SHOP.website,
        width: 165,
        height: 165,
        colorDark: "#0f1923",
        colorLight: "#f4f7fa",
        correctLevel: QRCode.CorrectLevel.H,
    });
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
    const msg = tpl(APP.messages?.shareTemplate, { website: SHOP.website });
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

function showToast(message) {
    const toast = $id("toast");
    const msg = $id("toastMsg");
    if (!toast || !msg) {
        return;
    }

    msg.textContent = message || pick("messages.defaultToast");
    toast.classList.add("show");
    setTimeout(() => {
        toast.classList.remove("show");
    }, 2600);
}

const renderAll = () => {
    fillStatic();
    renderProfileStats();
    renderServices();
    renderPackages();
    renderSlots();
    renderBookingOptions();
    renderBarbers();
    renderGallery();
    renderReviews();
    renderTips();
    renderProducts();
    renderHours();
    renderSocial();
    renderPayments();
    genQR();
};

function boot() {
    APP = window.__APP__ || {};
    SHOP = APP.shop || {};
    SHOP.website = SHOP.website || window.location.href;
}

"loading" === document.readyState
    ? document.addEventListener("DOMContentLoaded", boot)
    : boot();
