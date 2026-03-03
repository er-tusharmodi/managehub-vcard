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

let APP = {};
let DOC = {};
let selectedSlot = "";

const CHIP_ICONS = {
    pulse: () => iconTpl("icon-chip-pulse", "icon-chip-pulse"),
    heart: () => iconTpl("icon-chip-heart", "icon-chip-heart"),
    info: () => iconTpl("icon-chip-info", "icon-chip-info"),
    respiratory: () =>
        iconTpl("icon-chip-respiratory", "icon-chip-respiratory"),
    home: () => iconTpl("icon-chip-home", "icon-chip-home"),
    search: () => iconTpl("icon-chip-search", "icon-chip-search"),
    preventive: () => iconTpl("icon-chip-preventive", "icon-chip-preventive"),
};

const SLOT_AVAIL_ICON = () => iconTpl("icon-chip-info", "icon-chip-info");
const WA_ICON = () => iconTpl("icon-social-whatsapp", "icon-social-whatsapp");
const STAR_ICON = () => iconTpl("icon-ui-star", "icon-ui-star");

const TIP_ICONS = {
    sun: () => iconTpl("icon-tip-sun", "icon-tip-sun"),
    heart: () => iconTpl("icon-tip-heart", "icon-tip-heart"),
    drop: () => iconTpl("icon-tip-drop", "icon-tip-drop"),
    cup: () => iconTpl("icon-tip-cup", "icon-tip-cup"),
};

const AWARD_ICONS = {
    medal: () => iconTpl("icon-award-medal", "icon-award-medal"),
    pulse: () => iconTpl("icon-award-pulse", "icon-award-pulse"),
    book: () => iconTpl("icon-award-book", "icon-award-book"),
    patients: () => iconTpl("icon-award-patients", "icon-award-patients"),
};

const SOCIAL_ICONS = {
    whatsapp: {
        cls: "ic-wa",
        svg: () => iconTpl("icon-social-whatsapp", "icon-social-whatsapp"),
    },
    facebook: {
        cls: "ic-fb",
        svg: () => iconTpl("icon-social-facebook", "icon-social-facebook"),
    },
    youtube: {
        cls: "ic-yt",
        svg: () => iconTpl("icon-social-youtube", "icon-social-youtube"),
    },
    website: {
        cls: "ic-web",
        svg: () => iconTpl("icon-social-website", "icon-social-website"),
    },
};

const PAYMENT_ICONS = {
    cash: () => iconTpl("icon-pay-cash", "icon-pay-cash"),
    card: () => iconTpl("icon-pay-card", "icon-pay-card"),
    shield: () => iconTpl("icon-pay-shield", "icon-pay-shield"),
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
                        ${CHIP_ICONS[item.icon]?.() || ""}
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
                        <div class="slot-avail">${SLOT_AVAIL_ICON()}${slot.availability || ""}</div>
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
                            <div class="svc-wa">${WA_ICON()}${pick("labels.enquire", "Enquire")}</div>
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
                            ${TIP_ICONS[item.icon]?.() || ""}
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
                            <div class="rv-stars">${STAR_ICON()}${STAR_ICON()}${STAR_ICON()}${STAR_ICON()}${STAR_ICON()}</div>
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
                        <div class="award-icon">${AWARD_ICONS.medal?.() || ""}</div>
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
                        <div class="s-ico ${icon.cls}">${icon.svg?.() || ""}</div>
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
                            <span style="display:flex;color:${item.stroke || "#0d9488"}">${PAYMENT_ICONS[item.icon]?.() || ""}</span>
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
    setAttr(
        "aComplaint",
        "placeholder",
        pick("appointment.form.complaintPlaceholder"),
    );

    setText("appointment-submit", pick("appointment.form.submitLabel"));
    setText("appointment-success-title", pick("appointment.success.title"));
    setText("appointment-success-text", pick("appointment.success.text"));
    setText(
        "appointment-success-button",
        pick("appointment.success.buttonLabel"),
    );

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

function boot() {
    APP = window.__APP__ || {};
    DOC = APP.doctor || {};
    DOC.website = DOC.website || window.location.href;
}

"loading" === document.readyState
    ? document.addEventListener("DOMContentLoaded", boot)
    : boot();
