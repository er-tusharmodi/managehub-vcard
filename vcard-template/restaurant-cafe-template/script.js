const $id = (id) => document.getElementById(id);
const pick = (path, fallback = "") =>
    path.split(".").reduce((acc, key) => acc?.[key], APP) ?? fallback;
const tpl = (template = "", data = {}) =>
    template.replace(/\{\{(\w+)\}\}/g, (_, key) => data[key] ?? "");
const sq = (value = "") =>
    String(value).replace(/\\/g, "\\\\").replace(/'/g, "\\'");

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

let APP = {};
let R = {};
let MENU = {};
let activeTab = "Starters";
let cart = {};

const RATING_ICONS = {
    star: `<svg class="ic-sm" viewBox="0 0 24 24" fill="#f4c430" stroke="#f4c430" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>`,
    users: `<svg class="ic-sm" viewBox="0 0 24 24" stroke="rgba(255,255,255,.7)" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>`,
    clock: `<svg class="ic-sm" viewBox="0 0 24 24" stroke="rgba(255,255,255,.7)" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`,
};

const HIGHLIGHT_ICONS = {
    oven: `<svg width="28" height="28" viewBox="0 0 64 64" fill="none" stroke="#c2562a" stroke-width="2" stroke-linecap="round"><path d="M32 8 Q28 18 34 22 Q30 16 32 8 Z M20 22 Q14 32 20 42 Q22 48 32 52 Q46 52 50 40 Q54 28 44 20 Q42 30 36 28 Q40 18 32 8 Q28 18 20 22 Z" fill="rgba(194,86,42,0.15)"/></svg>`,
    fresh: `<svg width="28" height="28" viewBox="0 0 64 64" fill="none" stroke="#3a7a45" stroke-width="2" stroke-linecap="round"><path d="M32 52 L32 24"/><path d="M32 36 Q22 30 18 18 Q28 16 34 28" fill="rgba(58,122,69,0.15)"/><path d="M32 28 Q42 22 46 10 Q36 8 30 22" fill="rgba(58,122,69,0.12)"/></svg>`,
    wine: `<svg width="28" height="28" viewBox="0 0 64 64" fill="none" stroke="#880e4f" stroke-width="2" stroke-linecap="round"><path d="M18 8 L18 26 Q18 42 32 42 Q46 42 46 26 L46 8 Z" fill="rgba(136,14,79,0.12)"/><line x1="32" y1="42" x2="32" y2="54"/><line x1="22" y1="54" x2="42" y2="54"/><path d="M18 24 Q32 30 46 24" fill="rgba(136,14,79,0.15)"/></svg>`,
};

const OFFER_ICONS = {
    brunch: `<svg width="30" height="30" viewBox="0 0 64 64" fill="none" stroke="rgba(180,100,30,0.7)" stroke-width="2" stroke-linecap="round"><path d="M8 44 Q32 20 56 44"/><line x1="32" y1="14" x2="32" y2="8"/><line x1="14" y1="20" x2="10" y2="16"/><line x1="50" y1="20" x2="54" y2="16"/><line x1="8" y1="44" x2="56" y2="44"/></svg>`,
    candle: `<svg width="30" height="30" viewBox="0 0 64 64" fill="none" stroke="rgba(180,60,60,0.7)" stroke-width="2" stroke-linecap="round"><rect x="24" y="24" width="16" height="36" rx="2" fill="rgba(255,220,180,0.5)"/><path d="M32 24 Q30 18 32 12 Q34 18 32 24"/><line x1="24" y1="44" x2="40" y2="44"/></svg>`,
    coffee: `<svg width="30" height="30" viewBox="0 0 64 64" fill="none" stroke="rgba(30,100,60,0.7)" stroke-width="2" stroke-linecap="round"><path d="M10 24 L12 48 Q12 52 32 52 Q52 52 52 48 L54 24 Z" fill="rgba(200,240,210,0.4)"/><path d="M10 24 L54 24"/><path d="M54 30 Q62 30 62 38 Q62 46 54 46"/></svg>`,
    cake: `<svg width="30" height="30" viewBox="0 0 64 64" fill="none" stroke="rgba(100,30,120,0.7)" stroke-width="2" stroke-linecap="round"><rect x="8" y="32" width="48" height="24" rx="3" fill="rgba(230,200,240,0.4)"/><path d="M8 32 Q12 24 32 24 Q52 24 56 32" fill="rgba(230,200,240,0.3)"/><line x1="22" y1="24" x2="22" y2="32"/><line x1="32" y1="22" x2="32" y2="30"/><line x1="42" y1="24" x2="42" y2="32"/></svg>`,
};

const TRANSPORT_ICONS = {
    metro: `<svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>`,
    parking: `<svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>`,
    taxi: `<svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>`,
    delivery: `<svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8.56 2.75c4.37 6.03 6.02 9.42 8.03 17.72m2.54-15.38c-3.72 4.35-8.94 5.66-16.88 5.85m19.5 1.9c-3.5-.93-6.63-.82-8.94 0-2.58.92-5.01 2.86-7.44 6.32"/></svg>`,
};

const SOCIAL_ICONS = {
    instagram: {
        cls: "ic-ig",
        svg: `<svg class="ic" viewBox="0 0 24 24" stroke-width="1.8"><path d="M17 2H7a5 5 0 0 0-5 5v10a5 5 0 0 0 5 5h10a5 5 0 0 0 5-5V7a5 5 0 0 0-5-5z"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>`,
    },
    whatsapp: {
        cls: "ic-wa",
        svg: `<svg class="ic" viewBox="0 0 24 24" stroke-width="1.8"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>`,
    },
    youtube: {
        cls: "ic-yt",
        svg: `<svg class="ic" viewBox="0 0 24 24" stroke-width="1.8"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="#fff" stroke="none"/></svg>`,
    },
    facebook: {
        cls: "ic-fb",
        svg: `<svg class="ic" viewBox="0 0 24 24" stroke-width="1.8"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>`,
    },
};

const PAYMENT_ICONS = {
    card: `<svg class="ic-sm" viewBox="0 0 24 24" stroke-width="1.8"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>`,
    upi: `<svg class="ic-sm" viewBox="0 0 24 24" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8v8"/></svg>`,
    wallet: `<svg class="ic-sm" viewBox="0 0 24 24" stroke-width="1.8"><rect x="1" y="4" width="22" height="16" rx="2"/><circle cx="8" cy="12" r="3" fill="none"/><circle cx="14" cy="12" r="3" fill="none"/></svg>`,
    cash: `<svg class="ic-sm" viewBox="0 0 24 24" stroke-width="1.8"><path d="M17 9V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><rect x="9" y="11" width="12" height="10" rx="2"/><circle cx="15" cy="16" r="1"/></svg>`,
};

const SOURCE_ICONS = {
    google: `<svg viewBox="0 0 24 24" width="12" height="12" fill="#4285f4" stroke="none"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>`,
    zomato: `<svg viewBox="0 0 24 24" width="12" height="12" fill="#e53935" stroke="none"><circle cx="12" cy="12" r="12"/></svg>`,
};

const EMPTY_CART_ICON = `<svg viewBox="0 0 24 24" fill="none" stroke-width="1.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>`;

const optionMarkup = (items = []) =>
    items
        .map(
            (item) =>
                `<option value="${sq(item.value ?? item.label)}"${item.selected ? ' selected="selected"' : ""}>${item.label ?? item.value ?? ""}</option>`,
        )
        .join("");

const starMarkup = (filled = 0) =>
    Array.from({ length: 5 }, (_, i) => {
        const on = i < filled;
        return `<svg class="star" viewBox="0 0 24 24" fill="${on ? "#f4c430" : "#e0e0e0"}" stroke="${on ? "#f4c430" : "#e0e0e0"}" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>`;
    }).join("");

const renderRatingStrip = () => {
    const ratings = APP.banner?.ratings || [];
    setHTML(
        "ratingStrip",
        ratings
            .map(
                (item) => `
                    <div class="r-stat">
                        ${RATING_ICONS[item.icon] || ""}
                        ${item.label || ""}
                    </div>`,
            )
            .join(""),
    );
};

const renderCuisineTags = () => {
    setHTML(
        "cuisineTags",
        (APP.profile?.cuisineTags || [])
            .map((item) => `<span class="ctag">${item}</span>`)
            .join(""),
    );
};

const renderHighlights = () => {
    setHTML(
        "highlightsRow",
        (APP.story?.highlights || [])
            .map(
                (item) => `
                    <div class="hl-box">
                        <div class="hl-em">${HIGHLIGHT_ICONS[item.icon] || ""}</div>
                        <div class="hl-lbl">${item.label || ""}</div>
                    </div>`,
            )
            .join(""),
    );
};

const renderGallery = () => {
    setHTML(
        "galleryRow",
        (APP.gallery || [])
            .map(
                (item) => `
                    <div>
                        <div class="gal-item" style="background:url('${item.image || pick("assets.fallbackImage")}') center/cover no-repeat;"></div>
                        <div class="gal-cap">${item.caption || ""}</div>
                    </div>`,
            )
            .join(""),
    );
};

const renderOffers = () => {
    setHTML(
        "offersList",
        (APP.offers || [])
            .map(
                (item) => `
                    <div class="offer-card">
                        <div class="offer-icon" style="background:${item.bg || "#fff3e0"}">${OFFER_ICONS[item.icon] || ""}</div>
                        <div>
                            <div class="offer-title">${item.title || ""}</div>
                            <div class="offer-desc">${item.desc || ""}</div>
                            <span class="offer-tag">${item.tag || ""}</span>
                        </div>
                    </div>`,
            )
            .join(""),
    );
};

const renderHours = () => {
    setHTML(
        "hoursRows",
        (APP.hours?.rows || [])
            .map(
                (row, idx) => `
                    <tr class="${idx === 0 ? "h-today" : ""}">
                        <td class="h-day">${row.day || ""}</td>
                        <td class="h-time">${row.time || ""}</td>
                    </tr>`,
            )
            .join(""),
    );
};

const renderReviews = () => {
    setText("bigRating", APP.reviews?.summaryScore || "");
    setHTML("summaryStars", starMarkup(APP.reviews?.summaryRating || 0));
    setText("ratingSub", APP.reviews?.summarySub || "");
    setText("seeMoreLabel", APP.reviews?.buttonLabel || "");

    setHTML(
        "reviewList",
        (APP.reviews?.items || [])
            .map((item) => {
                const color =
                    item.sourceType === "zomato" ? "#e53935" : "var(--muted)";
                return `
                    <div class="review-card">
                        <div class="rev-top">
                            <div>
                                <div class="rev-name">${item.name || ""}</div>
                                <div class="stars-row" style="margin-top:0.2rem">${starMarkup(item.rating || 0)}</div>
                            </div>
                            <div class="rev-date">${item.date || ""}</div>
                        </div>
                        <div class="rev-text">${item.text || ""}</div>
                        <div class="rev-src" style="color:${color}">
                            ${SOURCE_ICONS[item.sourceType] || ""}
                            ${item.source || ""}
                        </div>
                    </div>`;
            })
            .join(""),
    );
};

const renderTransport = () => {
    setHTML(
        "transportGrid",
        (APP.location?.transport || [])
            .map(
                (item) => `
                    <div class="t-item">
                        <span style="display:flex;color:${item.stroke || "#1565c0"}">${TRANSPORT_ICONS[item.icon] || ""}</span>
                        <div>
                            <div class="t-label">${item.label || ""}</div>
                            <div class="t-val">${item.value || ""}</div>
                        </div>
                    </div>`,
            )
            .join(""),
    );
};

const socialAction = (item) => {
    if (item.action === "wa") {
        return "openWA()";
    }
    if (item.action === "url" && item.url) {
        return `window.open('${sq(item.url)}','_blank')`;
    }
    return "";
};

const renderSocial = () => {
    setHTML(
        "socialList",
        (APP.social || [])
            .map((item) => {
                const icon = SOCIAL_ICONS[item.type] || {};
                const click = socialAction(item);
                return `
                    <div class="soc-item"${click ? ` onclick="${click}"` : ""}>
                        <div class="s-ico ${icon.cls || ""}">${icon.svg || ""}</div>
                        <div>
                            <div class="s-name">${item.name || ""}</div>
                            <div class="s-val">${item.value || ""}</div>
                        </div>
                        <div class="s-arrow">
                            <svg class="ic-sm" viewBox="0 0 24 24" stroke-width="2">
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
        "payGrid",
        (APP.payments || [])
            .map(
                (item) => `
                    <div class="pay-item">
                        <div class="pay-icon">
                            <span style="display:flex;color:${item.stroke || "#1565c0"}">${PAYMENT_ICONS[item.icon] || ""}</span>
                        </div>
                        <div>
                            <div class="pay-name">${item.name || ""}</div>
                            <div class="pay-sub">${item.sub || ""}</div>
                        </div>
                    </div>`,
            )
            .join(""),
    );
};

const renderTabs = () => {
    const tabs = Object.keys(MENU || {});
    setHTML(
        "menuTabs",
        tabs
            .map(
                (tab) =>
                    `<button class="mtab${tab === activeTab ? " active" : ""}" onclick="switchTab('${sq(tab)}')">${tab}</button>`,
            )
            .join(""),
    );
};

const renderItems = () => {
    const items = MENU[activeTab] || [];
    setHTML(
        "menuGrid",
        items
            .map((item) => {
                const qty = cart[item.id]?.qty || 0;
                return `
                    <div class="menu-card">
                        <div class="menu-img">
                            <div class="menu-img-ph" style="background:${item.bg || `url('${pick("assets.fallbackImage")}')`};height:100%;display:flex;align-items:center;justify-content:center;"></div>
                            ${item.tag ? `<span class="mbadge" style="background:${item.tc || "#3a4a2e"}">${item.tag}</span>` : ""}
                            <div class="diet ${item.veg ? "veg-d" : "nonveg-d"}">${item.veg ? "V" : "N"}</div>
                        </div>
                        <div class="menu-body">
                            <div class="menu-name">${item.name || ""}</div>
                            <div class="menu-desc">${item.desc || ""}</div>
                            <div class="menu-footer">
                                <div>
                                    <span class="mprice">&#8377;${item.price || 0}</span>
                                    ${item.op ? `<span class="mold">&#8377;${item.op}</span>` : ""}
                                </div>
                                <div class="qty-ctrl">
                                    <button class="qty-btn" onclick="chQty(${item.id},-1,${item.price || 0},'${sq(item.name || "")}')"><svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/></svg></button>
                                    <span class="qty-num" id="qty-${item.id}">${qty}</span>
                                    <button class="qty-btn" onclick="chQty(${item.id},1,${item.price || 0},'${sq(item.name || "")}')"><svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></button>
                                </div>
                            </div>
                        </div>
                    </div>`;
            })
            .join(""),
    );
};

function switchTab(tab) {
    activeTab = tab;
    renderTabs();
    renderItems();
}

function chQty(id, delta, price, name) {
    cart[id] ||= { qty: 0, price, name };
    cart[id].qty = Math.max(0, cart[id].qty + delta);
    if (!cart[id].qty) {
        delete cart[id];
    }
    const q = $id(`qty-${id}`);
    if (q) {
        q.textContent = cart[id]?.qty || 0;
    }
    const totalQty = Object.values(cart).reduce(
        (sum, item) => sum + item.qty,
        0,
    );
    const badge = $id("cartBadge");
    if (badge) {
        badge.textContent = totalQty;
        badge.classList.toggle("show", totalQty > 0);
    }
}

const cartEmptyMarkup = () =>
    `<div class="cart-empty">${EMPTY_CART_ICON}${pick("cart.emptyLine1")}<br>${pick("cart.emptyLine2")}</div>`;

function openCart() {
    const items = Object.entries(cart);
    const body = $id("cartBody");
    if (!body) {
        return;
    }
    if (!items.length) {
        body.innerHTML = cartEmptyMarkup();
        $id("cartOverlay")?.classList.add("show");
        return;
    }

    let total = 0;
    const rows = items
        .map(([id, item]) => {
            const line = item.price * item.qty;
            total += line;
            return `<div class="cart-item"><div class="ci-name">${item.name}<br><small style="color:var(--muted);font-weight:400">&#8377;${item.price} each</small></div><div class="ci-qty"><button class="ci-qty-btn" onclick="chQty(${id},-1,${item.price},'${sq(item.name)}');openCart()"><svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/></svg></button><span class="ci-qty-num">${item.qty}</span><button class="ci-qty-btn" onclick="chQty(${id},1,${item.price},'${sq(item.name)}');openCart()"><svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></button></div><div class="ci-price">&#8377;${line}</div></div>`;
        })
        .join("");

    body.innerHTML = `${rows}<div class="cart-total"><span>${pick("cart.totalLabel")}</span><span class="cart-total-amt">&#8377;${total}</span></div><textarea class="cart-note-input" id="cartNote" placeholder="${pick("cart.notePlaceholder")}"></textarea><button class="cart-order-btn" onclick="sendCartWA()"><svg class="ic" viewBox="0 0 24 24" stroke="#fff" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>${pick("cart.orderButton")}</button>`;
    $id("cartOverlay")?.classList.add("show");
}

async function sendCartWA() {
    const items = Object.values(cart);
    let total = 0;
    let message = `Meal Order - ${R.name}\n\n`;
    const orderItems = [];
    items.forEach((item) => {
        const amount = item.price * item.qty;
        total += amount;
        message += `* ${item.name} x${item.qty} = Rs.${amount}\n`;
        orderItems.push({
            name: item.name,
            qty: item.qty,
            price: item.price,
            total: amount,
        });
    });
    message += `\nTotal: Rs.${total}`;
    const note = $id("cartNote")?.value.trim();
    if (note) {
        message += `\nNote: ${note}`;
    }
    message += "\n\nPlease confirm my order!";

    await sendSubmission("order", {
        source_template: pick("meta.title") || "restaurant-cafe-template",
        shop_name: R.name || "",
        name: "",
        phone: "",
        email: "",
        message: note || "Meal order",
        items: orderItems,
        total: total,
    });

    window.open(
        `https://wa.me/${R.whatsapp}?text=${encodeURIComponent(message)}`,
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

async function submitReservation() {
    const name = $id("rName")?.value.trim() || "";
    const phone = $id("rPhone")?.value.trim() || "";
    if (!name || !phone) {
        showToast(pick("messages.reservationNeedNamePhone"));
        return;
    }
    const date = $id("rDate")?.value || "";
    const time = $id("rTime")?.value || "";
    if (!date || !time) {
        showToast(pick("messages.reservationNeedDateTime"));
        return;
    }
    const occasion = $id("rOccasion")?.value || "";
    const guests = $id("rGuests")?.value || "";
    const notes = $id("rNote")?.value.trim() || "";
    let message = `Table Reservation - ${R.name}\n\nName: ${name}\nPhone: ${phone}\nDate: ${date} at ${time}\nGuests: ${guests}`;
    if (occasion) {
        message += `\nOccasion: ${occasion}`;
    }
    if (notes) {
        message += `\nNotes: ${notes}`;
    }
    message += "\n\nPlease confirm my reservation!";

    await sendSubmission("booking", {
        source_template: pick("meta.title") || "restaurant-cafe-template",
        shop_name: R.name || "",
        name,
        phone,
        message: notes,
        items: [
            { label: "date", value: date },
            { label: "time", value: time },
            { label: "guests", value: guests },
            { label: "occasion", value: occasion },
        ],
    });

    window.open(
        `https://wa.me/${R.whatsapp}?text=${encodeURIComponent(message)}`,
        "_blank",
    );
    if ($id("reservationForm")) {
        $id("reservationForm").style.display = "none";
    }
    if ($id("reservationSuccess")) {
        $id("reservationSuccess").style.display = "block";
    }
}

function resetReservation() {
    if ($id("reservationForm")) {
        $id("reservationForm").style.display = "block";
    }
    if ($id("reservationSuccess")) {
        $id("reservationSuccess").style.display = "none";
    }
    ["rName", "rPhone", "rDate", "rNote"].forEach((id) => {
        if ($id(id)) {
            $id(id).value = "";
        }
    });
    if ($id("rTime")) {
        $id("rTime").selectedIndex = 0;
    }
    if ($id("rGuests")) {
        $id("rGuests").value = "4";
    }
    if ($id("rOccasion")) {
        $id("rOccasion").selectedIndex = 0;
    }
}

function openReserveModal() {
    $id("reserveOverlay")?.classList.add("show");
}

function closeReserveModal() {
    $id("reserveOverlay")?.classList.remove("show");
}

function closeResOutside(event) {
    if (event.target === $id("reserveOverlay")) {
        closeReserveModal();
    }
}

async function submitReservationModal() {
    const name = $id("rName2")?.value.trim() || "";
    const phone = $id("rPhone2")?.value.trim() || "";
    if (!name || !phone) {
        showToast(pick("messages.reservationModalNeedNamePhone"));
        return;
    }
    const date = $id("rDate2")?.value || "TBD";
    const time = $id("rTime2")?.value || "";
    const guests = $id("rGuests2")?.value || "";
    const message = `Table Reservation - ${R.name}\n\nName: ${name}\nPhone: ${phone}\nDate: ${date} at ${time}\nGuests: ${guests}\n\nPlease confirm!`;

    await sendSubmission("booking", {
        source_template: pick("meta.title") || "restaurant-cafe-template",
        shop_name: R.name || "",
        name,
        phone,
        message: "",
        items: [
            { label: "date", value: date },
            { label: "time", value: time },
            { label: "guests", value: guests },
        ],
    });
    window.open(
        `https://wa.me/${R.whatsapp}?text=${encodeURIComponent(message)}`,
        "_blank",
    );
    closeReserveModal();
    showToast(pick("messages.reservationSent"));
}

function callUs() {
    window.location.href = `tel:${R.phone}`;
}

function openWA() {
    window.open(
        `https://wa.me/${R.whatsapp}?text=${encodeURIComponent(pick("messages.waEnquiry"))}`,
        "_blank",
    );
}

function emailUs() {
    window.location.href = `mailto:${R.email}`;
}

function openMaps() {
    window.open(R.maps, "_blank");
}

function openReviews() {
    const url = APP.reviews?.buttonUrl;
    if (url) {
        window.open(url, "_blank");
    }
}

function genQR() {
    const box = $id("vcardQR");
    if (!box || typeof QRCode === "undefined") {
        return;
    }
    box.innerHTML = "";
    new QRCode(box, {
        text: R.website,
        width: 165,
        height: 165,
        colorDark: "#3a4a2e",
        colorLight: "#faf7f2",
        correctLevel: QRCode.CorrectLevel.H,
    });
}

function saveContact() {
    const vcard = `BEGIN:VCARD\nVERSION:3.0\nFN:${R.name}\nORG:${R.name}\nTEL;TYPE=CELL:${R.phone}\nEMAIL:${R.email}\nURL:${R.website}\nNOTE:${R.tagline}\nEND:VCARD`;
    const link = document.createElement("a");
    link.href = URL.createObjectURL(new Blob([vcard], { type: "text/vcard" }));
    link.download = pick("files.vcard");
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
    link.download = pick("files.qr");
    link.click();
    showToast(pick("messages.qrDownloaded"));
}

function copyLink() {
    const done = () => showToast(pick("messages.linkCopied"));
    if (navigator.clipboard?.writeText) {
        navigator.clipboard.writeText(R.website).then(done).catch(done);
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
        `https://wa.me/?text=${encodeURIComponent(`Check out ${R.name}: ${R.website}`)}`,
        "_blank",
    );
    closeShareModal();
}

function shareFB() {
    window.open(
        `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(R.website)}`,
        "_blank",
    );
    closeShareModal();
}

function shareNative() {
    if (navigator.share) {
        navigator.share({ title: R.name, url: R.website });
    } else {
        copyLink();
    }
}

function showToast(message) {
    const toast = $id("toast");
    if (!toast) {
        return;
    }
    toast.innerHTML = `<svg viewBox="0 0 24 24" stroke-width="2" width="15" height="15"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>${message || pick("messages.defaultDone")}`;
    toast.classList.add("show");
    setTimeout(() => toast.classList.remove("show"), 2500);
}

const renderStatic = () => {
    document.title = pick("meta.title");
    setText("statusLabel", pick("banner.statusLabel"));
    setText("bannerShareLabel", pick("banner.shareLabel"));
    setText("bannerEyebrow", pick("banner.eyebrow"));
    setText("bannerTitle", pick("banner.title"));
    setText("bannerSub", pick("banner.subtitle"));

    const bg = $id("bannerBg");
    if (bg && pick("assets.bannerImage")) {
        bg.style.background = `url(${pick("assets.bannerImage")}) center/cover no-repeat`;
    }

    setText("actionCallLabel", pick("profile.actions.call"));
    setText("actionWaLabel", pick("profile.actions.whatsapp"));
    setText("actionReserveLabel", pick("profile.actions.reserve"));
    setText("actionEmailLabel", pick("profile.actions.email"));
    setText("actionDirectionLabel", pick("profile.actions.directions"));
    setText("actionShareLabel", pick("profile.actions.share"));

    setText("secStoryTitle", pick("sections.story"));
    setText("secMenuTitle", pick("sections.menu"));
    setText("secGalleryTitle", pick("sections.gallery"));
    setText("secReserveTitle", pick("sections.reserve"));
    setText("secOffersTitle", pick("sections.offers"));
    setText("secHoursTitle", pick("sections.hours"));
    setText("secReviewsTitle", pick("sections.reviews"));
    setText("secLocationTitle", pick("sections.location"));
    setText("secFollowTitle", pick("sections.follow"));
    setText("secPaymentTitle", pick("sections.payments"));
    setText("secQrTitle", pick("sections.qr"));

    setAttr("storyImage", "src", pick("story.image"));
    setAttr("storyImage", "alt", pick("R.name"));
    setText("storyP1", pick("story.paragraph1"));
    setText("storyP2", pick("story.paragraph2"));
    setText("chefName", pick("story.chefName"));
    setText("chefRole", pick("story.chefRole"));

    setText("rLabelName", pick("reservation.labels.name"));
    setText("rLabelPhone", pick("reservation.labels.phone"));
    setText("rLabelDate", pick("reservation.labels.date"));
    setText("rLabelTime", pick("reservation.labels.time"));
    setText("rLabelGuests", pick("reservation.labels.guests"));
    setText("rLabelOccasion", pick("reservation.labels.occasion"));
    setText("rLabelNote", pick("reservation.labels.note"));
    setText("rConfirmLabel", pick("reservation.confirmLabel"));
    setText("rSuccessTitle", pick("reservation.successTitle"));
    setText("rSuccessMsg", pick("reservation.successMessage"));
    setText("rSuccessBtnLabel", pick("reservation.successButton"));

    setAttr("rName", "placeholder", pick("reservation.placeholders.name"));
    setAttr("rPhone", "placeholder", pick("reservation.placeholders.phone"));
    setAttr("rNote", "placeholder", pick("reservation.placeholders.note"));

    setText("todayPillLabel", pick("hours.todayLabel"));
    setText("kitchenNote", pick("hours.kitchenNote"));

    setText("locationName", pick("location.name"));
    setText("locationAddress", pick("location.address"));
    setText("mapBtnLabel", pick("location.mapLabel"));

    setText("qrHelpText", pick("qr.helpText"));
    setText("qrDownloadLabel", pick("qr.downloadLabel"));
    setText("qrSaveLabel", pick("qr.saveLabel"));

    setHTML(
        "footerLine1",
        `${pick("footer.year")} <strong>${pick("footer.brand")}</strong> ${pick("footer.rights")}`.trim(),
    );
    setHTML(
        "footerLine2",
        `${pick("footer.poweredBy")} <strong>${pick("footer.poweredBrand")}</strong>`,
    );

    setText("floatCallLabel", pick("floatBar.call"));
    setText("floatReserveLabel", pick("floatBar.reserve"));
    setText("floatWaLabel", pick("floatBar.whatsapp"));
    setText("floatOrderLabel", pick("floatBar.order"));

    setText("cartTitle", pick("cart.title"));

    setText("reserveModalTitle", pick("reserveModal.title"));
    setText("r2LabelName", pick("reserveModal.labels.name"));
    setText("r2LabelPhone", pick("reserveModal.labels.phone"));
    setText("r2LabelDate", pick("reserveModal.labels.date"));
    setText("r2LabelTime", pick("reserveModal.labels.time"));
    setText("r2LabelGuests", pick("reserveModal.labels.guests"));
    setText("r2ConfirmLabel", pick("reserveModal.confirmLabel"));
    setAttr("rName2", "placeholder", pick("reserveModal.placeholders.name"));
    setAttr("rPhone2", "placeholder", pick("reserveModal.placeholders.phone"));

    setText("shareTitle", pick("share.title"));
    setText("shareWaLabel", pick("share.whatsapp"));
    setText("shareCopyLabel", pick("share.copy"));
    setText("shareFbLabel", pick("share.facebook"));
    setText("shareMoreLabel", pick("share.more"));
    setText("shareCancelLabel", pick("share.cancel"));
};

const renderAll = () => {
    R = { ...(APP.R || {}) };
    R.website = R.website || window.location.href;
    MENU = APP.MENU || {};
    cart = {};
    activeTab = Object.keys(MENU)[0] || "Starters";

    renderStatic();
    renderRatingStrip();
    renderCuisineTags();
    renderHighlights();
    renderGallery();
    renderOffers();
    renderHours();
    renderReviews();
    renderTransport();
    renderSocial();
    renderPayments();

    setHTML("rTime", optionMarkup(APP.reservation?.times || []));
    setHTML("rGuests", optionMarkup(APP.reservation?.guests || []));
    setHTML("rOccasion", optionMarkup(APP.reservation?.occasions || []));
    setHTML("rTime2", optionMarkup(APP.reserveModal?.times || []));
    setHTML("rGuests2", optionMarkup(APP.reserveModal?.guests || []));

    renderTabs();
    renderItems();
    setHTML("cartBody", cartEmptyMarkup());

    if ($id("cartBadge")) {
        $id("cartBadge").textContent = 0;
        $id("cartBadge").classList.remove("show");
    }
    if ($id("reservationForm")) {
        $id("reservationForm").style.display = "block";
    }
    if ($id("reservationSuccess")) {
        $id("reservationSuccess").style.display = "none";
    }

    genQR();
};

const boot = () => {
    APP = window.__APP__ || {};
    R = APP.restaurant || {};
    MENU = APP.menu || {};
};

document.readyState === "loading"
    ? document.addEventListener("DOMContentLoaded", boot)
    : boot();
