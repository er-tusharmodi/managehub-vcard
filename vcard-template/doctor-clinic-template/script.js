const $id = (id) => document.getElementById(id);
const tpl = (template, values = {}) =>
    (template || "").replace(/\{\{(\w+)\}\}/g, (_, key) => values[key] ?? "");
const sq = (value = "") =>
    String(value)
        .replace(/\\/g, "\\\\")
        .replace(/'/g, "\\'");
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
let DOC = {};
let selectedSlot = "";

const CHIP_ICONS = {
    pulse: `<svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>`,
    heart: `<svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>`,
    info: `<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`,
    respiratory: `<svg viewBox="0 0 24 24"><path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96-.46 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 1.44-4.12"/></svg>`,
    home: `<svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>`,
    search: `<svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>`,
    preventive: `<svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>`,
};

const SLOT_AVAIL_ICON = `<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`;
const WA_ICON = `<svg viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>`;
const STAR_ICON = `<svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>`;

const FEE_ICONS = {
    patient: `<svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>`,
    home: `<svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>`,
    video: `<svg viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>`,
    clock: `<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`,
};

const TIP_ICONS = {
    sun: `<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>`,
    heart: `<svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>`,
    drop: `<svg viewBox="0 0 24 24"><path d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"/></svg>`,
    cup: `<svg viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>`,
};

const AWARD_ICONS = {
    medal: `<svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>`,
    pulse: `<svg viewBox="0 0 24 24"><path d="M14.5 10c-.83 0-1.5-.67-1.5-1.5v-5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5v5c0 .83-.67 1.5-1.5 1.5z"/><path d="M20.5 10H19V8.5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/><path d="M9.5 14c.83 0 1.5.67 1.5 1.5v5c0 .83-.67 1.5-1.5 1.5S8 21.33 8 20.5v-5c0-.83.67-1.5 1.5-1.5z"/></svg>`,
    book: `<svg viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>`,
    patients: `<svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>`,
};

const SOCIAL_ICONS = {
    whatsapp: {
        cls: "ic-wa",
        svg: `<svg class="ic" viewBox="0 0 24 24" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>`,
    },
    facebook: {
        cls: "ic-fb",
        svg: `<svg class="ic" viewBox="0 0 24 24" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>`,
    },
    youtube: {
        cls: "ic-yt",
        svg: `<svg class="ic" viewBox="0 0 24 24" stroke-width="2"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/></svg>`,
    },
    website: {
        cls: "ic-web",
        svg: `<svg class="ic" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>`,
    },
};

const PAYMENT_ICONS = {
    cash: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>`,
    card: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>`,
    shield: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>`,
};

const renderProfileStats = () => {
    const stats = APP.profile?.stats || [];
    setHTML(
        "profileStats",
        stats
            .map(
                (item, index) => `
                    <div class="pstat">
                        <div class="pstat-num">${item.value || ""}</div>
                        <div class="pstat-lbl">${item.label || ""}</div>
                    </div>
                    ${index < stats.length - 1 ? `<div class="stat-divider"></div>` : ""}`,
            )
            .join(""),
    );
};

const renderSpecializations = () => {
    setHTML(
        "specializationChips",
        (APP.specializations || [])
            .map(
                (item) => `
                    <span class="chip ${item.tone || ""}">
                        ${CHIP_ICONS[item.icon] || ""}
                        ${item.name || ""}
                    </span>`,
            )
            .join(""),
    );
};

const renderSlots = () => {
    const slots = APP.appointment?.slots || [];
    const defaultSlot =
        slots.find((slot) => slot.selected && !slot.full)?.slot ||
        APP.appointment?.defaultSlot ||
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
                            <div class="slot-full">${slot.fullLabel || ""}</div>
                        </div>`;
                }

                const isSelected = selectedSlot === slot.slot;
                return `
                    <div class="slot-card${isSelected ? " selected" : ""}" onclick="selectSlot(this)" data-slot="${slot.slot || ""}">
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

const renderVisitTypes = () => {
    const select = $id("aType");
    if (!select) {
        return;
    }

    select.innerHTML = (APP.appointment?.form?.visitTypes || [])
        .map(
            (item) =>
                `<option value="${item.value || ""}">${item.label || ""}</option>`,
        )
        .join("");
};

const renderConditions = () => {
    const image = APP.assets?.serviceImage || "";
    setHTML(
        "conditionsGrid",
        (APP.conditions || [])
            .map(
                (item) => `
                    <div class="svc-card" onclick="enquireWA('${sq(item.query || item.name)}')">
                        <div class="svc-img" style="background:url('${item.image || image}') center/cover no-repeat;"></div>
                        <div class="svc-body">
                            <div class="svc-name">${item.name || ""}</div>
                            <div class="svc-desc">${item.desc || ""}</div>
                            <div class="svc-wa">${WA_ICON}${pick("labels.enquire", "Enquire")}</div>
                        </div>
                    </div>`,
            )
            .join(""),
    );
};

const renderFees = () => {
    setHTML(
        "feesList",
        (APP.fees?.items || [])
            .map(
                (item) => `
                    <div class="fee-item">
                        <div class="fee-left">
                            <div class="fee-icon" style="background:${item.bg || "#e0f2fe"};color:${item.color || "#0369a1"}">
                                ${FEE_ICONS[item.icon] || ""}
                            </div>
                            <div>
                                <div class="fee-name">${item.name || ""}</div>
                                <div class="fee-note">${item.note || ""}</div>
                            </div>
                        </div>
                        <div>
                            <span class="fee-amount">${item.amount || ""}</span>${item.oldAmount ? `<span class="fee-old">${item.oldAmount}</span>` : ""}
                        </div>
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
                        <div class="tip-icon" style="background:${item.bg || "#fef3c7"};color:${item.color || "#d97706"}">
                            ${TIP_ICONS[item.icon] || ""}
                        </div>
                        <div class="tip-tag" style="color:${item.color || "#d97706"}">${item.tag || ""}</div>
                        <div class="tip-text">${item.text || ""}</div>
                    </div>`,
            )
            .join(""),
    );
};

const renderReviews = () => {
    setText("reviews-rating-big", APP.reviews?.rating || "");
    setText("reviews-rating-count", APP.reviews?.countText || "");

    setHTML(
        "reviewsList",
        (APP.reviews?.items || [])
            .map(
                (item) => `
                    <div class="review-card">
                        <div class="rv-header">
                            <div class="rv-avatar" style="background:${item.gradient || "linear-gradient(135deg,#0d9488,#0ea5e9)"}">${item.initial || ""}</div>
                            <div>
                                <div class="rv-name">${item.name || ""}</div>
                                <div class="rv-date">${item.date || ""}</div>
                            </div>
                            <div class="rv-stars">${STAR_ICON}${STAR_ICON}${STAR_ICON}${STAR_ICON}${STAR_ICON}</div>
                        </div>
                        <div class="rv-text">${item.text || ""}</div>
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
                        <td class="session">${row.session || ""}</td>
                        <td class="time ${row.timeClass || ""}">${row.time || ""}</td>
                    </tr>`,
            )
            .join(""),
    );
};

const renderAwards = () => {
    setHTML(
        "awardsList",
        (APP.awards || [])
            .map(
                (item) => `
                    <div class="award-item">
                        <div class="award-icon">${AWARD_ICONS[item.icon] || ""}</div>
                        <div>
                            <div class="award-name">${item.name || ""}</div>
                            <div class="award-desc">${item.desc || ""}</div>
                        </div>
                    </div>`,
            )
            .join(""),
    );
};

const socialAction = (item) => {
    if ("openWA" === item.action) {
        return "openWA()";
    }

    if (item.url) {
        return `openExternal('${sq(item.url)}')`;
    }

    return "";
};

const renderSocial = () => {
    setHTML(
        "socialList",
        (APP.social || [])
            .map((item) => {
                const icon = SOCIAL_ICONS[item.type] || SOCIAL_ICONS.website;
                const action = socialAction(item);

                return `
                    <div class="social-item"${action ? ` onclick="${action}"` : ""}>
                        <div class="s-ico ${icon.cls}">${icon.svg}</div>
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
                            <span style="display:flex;color:${item.stroke || "#0d9488"}">${PAYMENT_ICONS[item.icon] || ""}</span>
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

const fillStaticContent = () => {
    document.title = pick("meta.title");

    setText("banner-share-label", pick("banner.shareLabel"));
    setText("banner-verified-label", pick("banner.verifiedLabel"));
    setText("status-open-label", pick("status.openLabel"));
    setText("status-next-slot", pick("status.nextSlot"));

    setText("profile-reg-tag", pick("doctor.regTag"));
    setText("profile-name", pick("doctor.name"));
    setText("profile-role", pick("doctor.role"));
    setText("profile-qualification", pick("doctor.qualification"));

    setText("action-call", pick("profile.actions.call"));
    setText("action-whatsapp", pick("profile.actions.whatsapp"));
    setText("action-save", pick("profile.actions.save"));
    setText("action-email", pick("profile.actions.email"));
    setText("action-direction", pick("profile.actions.direction"));
    setText("action-share", pick("profile.actions.share"));

    setText("sec-title-specializations", pick("sections.specializations"));
    setText("sec-title-appointment", pick("sections.appointment"));
    setText("sec-title-conditions", pick("sections.conditions"));
    setText("sec-title-fees", pick("sections.fees"));
    setText("sec-title-tips", pick("sections.tips"));
    setText("sec-title-reviews", pick("sections.reviews"));
    setText("sec-title-hours", pick("sections.hours"));
    setText("sec-title-awards", pick("sections.awards"));
    setText("sec-title-location", pick("sections.location"));
    setText("sec-title-social", pick("sections.social"));
    setText("sec-title-payments", pick("sections.payments"));
    setText("sec-title-contact-save", pick("sections.contactSave"));

    setText("label-name", pick("appointment.form.nameLabel"));
    setText("label-phone", pick("appointment.form.phoneLabel"));
    setText("label-age", pick("appointment.form.ageLabel"));
    setText("label-visit-type", pick("appointment.form.visitTypeLabel"));
    setText("label-complaint", pick("appointment.form.complaintLabel"));

    setAttr("aName", "placeholder", pick("appointment.form.namePlaceholder"));
    setAttr("aPhone", "placeholder", pick("appointment.form.phonePlaceholder"));
    setAttr("aAge", "placeholder", pick("appointment.form.agePlaceholder"));
    setAttr("aComplaint", "placeholder", pick("appointment.form.complaintPlaceholder"));

    setText("appointment-submit", pick("appointment.form.submitLabel"));
    setText("appointment-success-title", pick("appointment.success.title"));
    setText("appointment-success-text", pick("appointment.success.text"));
    setText("appointment-success-button", pick("appointment.success.buttonLabel"));

    setText("fees-insurance-note", pick("fees.insuranceNote"));
    setText("hours-today", pick("hours.todayLabel"));
    setText("hours-suggest-link", pick("hours.suggestLink"));

    setText("location-clinic-name", pick("location.clinicName"));
    setText("location-line1", pick("location.line1"));
    setText("location-line2", pick("location.line2"));
    setText("location-map-label", pick("location.mapLabel"));

    setText("qr-note", pick("qr.note"));
    setText("qr-save-label", pick("qr.saveLabel"));
    setText("qr-download-label", pick("qr.downloadLabel"));

    setText("footer-line1", pick("footer.line1"));
    setText("footer-line2", pick("footer.line2"));
    setText("footer-line3", pick("footer.line3"));
    setText("footer-line4", pick("footer.line4"));

    setText("fab-call-label", pick("floatBar.call"));
    setText("fab-whatsapp-label", pick("floatBar.whatsapp"));
    setText("fab-appointment-label", pick("floatBar.appointment"));
    setText("fab-save-label", pick("floatBar.save"));

    setText("share-title", pick("share.title"));
    setText("share-whatsapp-label", pick("share.whatsappLabel"));
    setText("share-copy-label", pick("share.copyLabel"));
    setText("share-more-label", pick("share.moreLabel"));
    setText("share-facebook-label", pick("share.facebookLabel"));
    setText("share-cancel-label", pick("share.cancelLabel"));

    setText("promo-title", pick("promo.title"));
    setText("promo-text", pick("promo.text"));
    setText("promo-cta-label", pick("promo.ctaLabel"));
    setText("toastMsg", pick("messages.defaultToast"));

    const image = $id("profile-image");
    if (image) {
        image.src = APP.assets?.profileImage || APP.assets?.fallbackImage || "";
        image.alt = APP.assets?.profileAlt || APP.doctor?.name || "";
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

function bookAppointment() {
    const name = $id("aName")?.value.trim();
    const phone = $id("aPhone")?.value.trim();

    if (!name || !phone) {
        showToast(pick("messages.namePhoneRequired"));
        return;
    }

    const age = $id("aAge")?.value || pick("appointment.form.defaultAge");
    const visitType = $id("aType")?.value || "";
    const complaint =
        $id("aComplaint")?.value || pick("appointment.form.defaultComplaint");

    const msg = tpl(APP.messages?.appointmentTemplate, {
        doctorName: DOC.name,
        name,
        phone,
        age,
        visitType,
        slot: selectedSlot || APP.appointment?.defaultSlot || "",
        complaint,
    });

    window.open(
        `https://wa.me/${DOC.whatsapp}?text=${encodeURIComponent(msg)}`,
        "_blank",
    );

    $id("apptForm").style.display = "none";
    $id("apptSuccess").style.display = "block";
}

function resetAppt() {
    $id("apptForm").style.display = "block";
    $id("apptSuccess").style.display = "none";

    ["aName", "aPhone", "aAge", "aComplaint"].forEach((id) => {
        const field = $id(id);
        field && (field.value = "");
    });
}

function callClinic() {
    window.location.href = `tel:${DOC.phone}`;
}

function openWA() {
    window.open(
        `https://wa.me/${DOC.whatsapp}?text=${encodeURIComponent(pick("messages.waGreeting"))}`,
        "_blank",
    );
}

function emailClinic() {
    window.location.href = `mailto:${DOC.email}`;
}

function openMaps() {
    window.open(DOC.maps, "_blank");
}

function openAppointment() {
    $id("appointmentSection")?.scrollIntoView({
        behavior: "smooth",
        block: "start",
    });
}

function enquireWA(topic) {
    const msg = tpl(APP.messages?.quickEnquiryTemplate, { topic });
    window.open(
        `https://wa.me/${DOC.whatsapp}?text=${encodeURIComponent(msg)}`,
        "_blank",
    );
}

function saveContact() {
    const vcard = `BEGIN:VCARD\nVERSION:3.0\nFN:${DOC.name}\nTITLE:${DOC.vcardTitle}\nORG:${DOC.vcardOrg}\nTEL;TYPE=CELL:${DOC.phone}\nEMAIL:${DOC.email}\nADR:;;${DOC.address};;;;\nURL:${DOC.website}\nNOTE:${DOC.vcardNote}\nEND:VCARD`;
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
        text: DOC.website,
        width: 165,
        height: 165,
        colorDark: "#0f2d4a",
        colorLight: "#f0f9ff",
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
        navigator.clipboard.writeText(DOC.website).then(done).catch(done);
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
        doctorName: DOC.name,
        website: DOC.website,
    });

    window.open(`https://wa.me/?text=${encodeURIComponent(msg)}`, "_blank");
    closeShareModal();
}

function shareFB() {
    window.open(
        `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(DOC.website)}`,
        "_blank",
    );
    closeShareModal();
}

function shareNative() {
    if (navigator.share) {
        navigator.share({ title: DOC.name, url: DOC.website });
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
    }, 2500);
}

const renderAll = () => {
    fillStaticContent();
    renderProfileStats();
    renderSpecializations();
    renderSlots();
    renderVisitTypes();
    renderConditions();
    renderFees();
    renderTips();
    renderReviews();
    renderHours();
    renderAwards();
    renderSocial();
    renderPayments();
    genQR();
};

async function boot() {
    try {
        const response = await fetch("default.json", { cache: "no-cache" });
        if (!response.ok) {
            throw new Error(`default.json load failed with status ${response.status}`);
        }

        APP = await response.json();
        DOC = APP.doctor || {};
        DOC.website = DOC.website || window.location.href;

        renderAll();

        if (APP.promo?.enabled) {
            setTimeout(() => {
                $id("promoOverlay")?.classList.add("show");
            }, APP.promo?.delayMs || 2200);
        }
    } catch (error) {
        console.error("Failed to load default.json", error);
    }
}

"loading" === document.readyState
    ? document.addEventListener("DOMContentLoaded", boot)
    : boot();
